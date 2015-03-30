/** 
 * Shim layer, polyfill, for requestAnimationFrame with setTimeout fallback.
 * http://paulirish.com/2011/requestanimationframe-for-smart-animating/
 */ 
window.requestAnimFrame = (function(){
  return  window.requestAnimationFrame       || 
          window.webkitRequestAnimationFrame || 
          window.mozRequestAnimationFrame    || 
          window.oRequestAnimationFrame      || 
          window.msRequestAnimationFrame     || 
          function( callback ){
            window.setTimeout(callback, 1000 / 60);
          };
})();
 
 
/**
 * Shim layer, polyfill, for cancelAnimationFrame with setTimeout fallback.
 */
window.cancelRequestAnimFrame = (function(){
  return  window.cancelRequestAnimationFrame || 
          window.webkitCancelRequestAnimationFrame || 
          window.mozCancelRequestAnimationFrame    || 
          window.oCancelRequestAnimationFrame      || 
          window.msCancelRequestAnimationFrame     || 
          window.clearTimeout;
})();

//****************
// Configurations
//****************
var config = {
  canW: 500,
  canH: 400,
  vehiclesR: ['../img/game/lorry_green.png',
    '../img/game/bus_yellow.png',
    '../img/game/sportscar_red.png',
    '../img/game/pickup_blue.png',
    '../img/game/dumpster_brown.png'],
  vehiclesL: ['../img/game/lorry_green_l.png',
    '../img/game/bus_yellow_l.png',
    '../img/game/sportscar_red_l.png',
    '../img/game/pickup_blue_l.png',
    '../img/game/dumpster_brown_l.png']
};

//***********************
// Game loop-module
//***********************
var Game = (function(conf) {
  var running = true,
    lastFrame,
    ctx,
    screen,
    now,
    timeDiff;

  //*** Init game ***
  var init = function(context) {
    ctx = context;
  };

  //*** Game loop ***
  var gameLoop = function(){
    now = Date.now();

    lastFrame = lastFrame || now;

    // Time difference since last call. This can vary and
    // you need to adjust for it, else the speed of the game
    // will vary as well. You want the speed of the game to be
    // consistent.
    timeDiff = now - lastFrame;
    lastFrame = now;
    if (running) {
      requestAnimFrame(gameLoop);
      ctx.clearRect(0, 0, config.canW, config.canH);
//      ctx.fillRect(0, 0, config.canW, config.canH); // Provar olika sätt att snabba upp
      screen.update(timeDiff);
      screen.draw();
    }
  };

  var start = function() {
    running = true;
    lastFrame = Date.now();
    requestAnimFrame(gameLoop);
  };

  var stop = function() {
    running = false;
  };

  var setScreen = function(scr) {
    screen = scr;
  };

  // Draws the screen only once
  var singleFrame = function() {
    ctx.clearRect(0, 0, config.canW, config.canH);
    screen.draw();
  };

  // The public interface that is returned
  return {
    'init': init,
    'startLoop': start,
    'stopLoop': stop,
    'setScreen': setScreen,
    'singleFrame': singleFrame
  };
})();

//*******************
// Game sequence/logic object, decides which screens to create and display
// based on different game events, controls the game loop.
//*******************
var GameSeq = (function() {
  var gameLoop,
    status,
    level,
    scrFactory,
    highScore,
    player,
    ctx,
    initSpec;

    // Init function
    var init = function(spec) {
      level = spec.level || 1;
      gameLoop = spec.gameLoop;
      scrFactory = spec.scrFactory;
      ctx = spec.ctx;
      initSpec = spec;

      // Creates a player, the same Player-obj is used throughout
      // the game.
      player = new Player({
        'ctx': ctx,
        'x': config.canW / 2, 'y': config.canH * 0.9,
        'type': 'player',   // Känns lite onödigt
        'img': '../img/game/tomato.png',
        'keyCheck': keyMem.isDown
      });

      // Register the GameSeq-obj as listener on player events
      player.registerListener(listener);
    };

    // Event handler.
    // Here you specify the game sequence, what happens when a player
    // has completed a level for example.
    var listener = function(event) {

      // Handling game events
      switch(event) {

        case 'welcome': // Start/Restart the game
          gameLoop.setScreen(screenFactory(ctx, 'welcome', player));
          gameLoop.singleFrame();
          break;

        case 'start': // Start a level
          // Create a screen with objects and start the game loop
          gameLoop.setScreen(screenFactory(ctx, 'level' + level, player));
          gameLoop.startLoop();
          break;

        case 'finish': // The player has finished a level
          gameLoop.stopLoop();
          level++;
          if (level <= 2) {  // There are only two levels in this version
            console.log('level' + level);
            setTimeout(function() {
                gameLoop.setScreen(screenFactory(ctx, 'nextLevel', player));
                gameLoop.singleFrame();
                setTimeout(function() {
                  player.resetPos();
                  listener('start');
                }, 3000);
            }, 1000);
          } else {
              // The player has completed the game
              gameLoop.setScreen(screenFactory(ctx, 'completed', player));
              resetGame();
              setTimeout(function() {
                listener('welcome');
              }, 4000);
          }
          break;

        case 'dead': // The player is dead
          gameLoop.stopLoop();
          gameLoop.setScreen(screenFactory(ctx, 'end', player));
          gameLoop.singleFrame();
          resetGame();
          setTimeout(function() {
                listener('welcome');
          }, 4000);
          break;
      }
    };

    var keyListener = function(event) {
      // Start the game with the space bar
      if (event.keyCode === 32) listener('start');
    };

    var resetGame = function() {
      player.fullReset();
      level = initSpec.level || 1; // Duplicering!!!
    };

    return {
      'init': init,
      'listener': listener,
      'keyListener': keyListener
    };

})();


//*******************
// A game object constructor.
// All visible part in a screen is a game object, for example: 
// start and finish areas, cars, players, ...
//*******************
var GameObj = function(spec) {

  this.ctx = spec.ctx;
  this.position = {
      x: spec.x || 0,
      y: spec.y || 0
    };
  this.width = spec.width || 10;
  this.height = spec.height || 10;
  this.pattern = spec.pattern || null;
  this.type = spec.type || 'background'; // Type needed for rules
  this.keyCheck = spec.keyCheck;

  if (this.pattern !== null) { // Inte så snyggt!!!
    this.img = new Image();
    this.img.src = this.pattern;
    var that = this;
    this.img.onload = function() {
      that.canvasPat = that.ctx.createPattern(that.img, 'repeat');
    };
  }

  if (spec.background) {
    this.ctx.fillStyle = spec.background;
  }
};

GameObj.prototype.update = function() { };
GameObj.prototype.draw = function() {

  this.ctx.save();

  // If a pattern is specified, fill the game object's area with it
  if (this.pattern && this.img.complete) {
    this.ctx.fillStyle = this.canvasPat;
  }
  
//    this.ctx.rect(this.position.x, this.position.y, this.width, this.height); // När jag bytte detta mot fillRect försvann prestandaprob.
//    this.ctx.fill();

  this.ctx.fillRect(this.position.x, this.position.y, this.width, this.height);

  this.ctx.restore();
};

GameObj.prototype.position = function() {
      return this.position;
};
GameObj.prototype.width = function() {
      return this.width;
};
GameObj.prototype.height = function() {
    return this.height;
};

// Check if two objects touch
// Code adapted from this place: http://blog.sklambert.com/html5-canvas-game-2d-collision-detection/
GameObj.prototype.isTouching = function(gameObj) {

  var x1 = this.position.x,
    y1 = this.position.y,
    w1 = this.width,
    h1 = this.height,
    x2 = gameObj.position.x,
    y2 = gameObj.position.y,
    w2 = gameObj.width,
    h2 = gameObj.height;

  if (x1 < x2 + w2  && x1 + w1  > x2 &&
    y1 < y2 + h2 && y1 + h1 > y2) {
    return true; // The objects are touching
  }
  return false;
};

//*****************
// Making a vehicle-constructor using Crockfords
// functional inheritance pattern. A vehicle is based
// on a GameObj.
//*****************
var makeVehicle = function(spec) {
  var obj,
    speed = spec.speed || 0;

    spec.type = 'vehicle';

    // A vehicle inherits GameObj
    obj = new GameObj(spec);

    // Handling the image representing the vehicle
    if (spec.img !== undefined) {
      obj.img = new Image();
      obj.img.src = spec.img;

      obj.img.onload = function() {
        // Sets the objects width and height equal to the image's
        obj.width = obj.img.width;
        obj.height = obj.img.height;

        // Adjust the position of the vehicle so that all vehicles
        // in a lane drives att the same height.
        obj.position.y += spec.laneHeight - obj.height;
      };
    }

    // Overrides the update method in GameObj
    obj.update = function(timeDiff) {

      this.position.x += speed * (timeDiff/1000); // speed in pixels per second
 
      // When the vehicle moves outside of a canvas border,
      // set the vehicles position so that it reappears on the
      // facing side.
      if (this.position.x > config.canW) { // TODO: Behöver göra liten justering här för hur långa obj är
        this.position.x = -this.width;
      }
      if (this.position.x + this.width < 0) {
        this.position.x = config.canW;
      }
    };

    // Overrides the draw method in GameObj
    obj.draw = function() {
      if(this.img.complete) { // First check if the image has been loaded
        this.ctx.drawImage(this.img, Math.floor(this.position.x), Math.floor(this.position.y)); // Math.floor for canvas optimization
      }
    };

    return obj;
};

//*****************
// Making a player constructor object using 
// "classical" inheritance. A player inherits
// the GameObj object.
//*****************
var Player = function(spec) {
  GameObj.call(this, spec); //GameObj-constructor adding properties to the new obj ie. "this"
  this.startPos = {x: spec.x, y: spec.y};
  this.startLifes = spec.lifes || 3;
  this.lifes = this.startLifes;
  this.score = 0;
  this.listeners = []; // Array with listeners on Player events

  // Handling the image
  if (spec.img !== undefined) { //Duplicering av kod!!!
    this.img = new Image();
    this.img.src = spec.img;

    var that = this;
    this.img.onload = function() {
      // Sets the objects width and height equal to the image's
      that.width = that.img.width;
      that.height = that.img.height;

      // Adjusts the start position of the player based
      // on the size of the image representing the player
      that.position.x = that.startPos.x -= that.width / 2;
      that.position.y = that.startPos.y -= that.height / 2;
    };
  }
};

Player.prototype = Object.create(GameObj.prototype); // Inherits GameObj
Player.prototype.constructor = Player; // Fixing constructor property that was overwritten in the step above
Player.prototype.update = function(timeDiff) {

  var movement = 60 * (timeDiff/1000); // Moves at 60 pixels per second

  if (this.keyCheck(38)) this.position.y -= movement; //Up
  if (this.keyCheck(40)) this.position.y += movement; //Down
  if (this.keyCheck(37)) this.position.x -= movement; //Left
  if (this.keyCheck(39)) this.position.x += movement; //Right
};

// Called when a player touch another object,
// then generates different events based on
// what the player touched.
Player.prototype.touch = function(obj) {

  //(Move these rules to a frog class???)

  // Event: Player has hit a vehicle
  if (obj.type === 'vehicle') {

    //Play sound effect
    document.getElementById('splash').play();

    // Count down lifes
    this.lifes--;
    console.log('Liv kvar:' + this.lifes);

    // Check lifes left
    if (this.lifes < 0) {
    
      // Inform listeners that the player is dead
      this.listeners.forEach(function(listener) {
        listener('dead');
      });
    } else {
      // Reset player to start position
      this.resetPos();
    }
  }

  // Event: Player has reached the finish
  if (obj.type === 'finish') {

    // Inform listeners that the player has reached the finish
    this.listeners.forEach(function(listener) {
      listener('finish');
    });
  } 
};

Player.prototype.resetPos = function() {
  this.position.x = this.startPos.x;
  this.position.y = this.startPos.y;
};

Player.prototype.fullReset = function() {
  this.resetPos();
  this.lifes = this.startLifes;
  this.score = 0;
};

Player.prototype.registerListener = function(listener) { // Kanske lyfta upp på GameObj nivå?
  this.listeners.push(listener);
};

// Overrides the draw method in GameObj - Gör om, flytta till GameObj?! Nu duplicering av kod!!!
Player.prototype.draw = function() {
  if(this.img.complete) {
    this.ctx.drawImage(this.img, Math.floor(this.position.x), Math.floor(this.position.y));       
  }
};

//*******************
// A panel constructor function that inherits from GameObj
//*******************

var Panel = function(spec) {
  GameObj.call(this, spec);
  this.headline = spec.headline || '';
  this.sub = spec.sub || '';
  this.bg = spec.background;
};

Panel.prototype = Object.create(GameObj.prototype); // Inherits GameObj
Panel.prototype.constructor = Panel;

Panel.prototype.draw = function() { // TODO: Gör om det mesta här och flytta ut värden

  this.ctx.save();
  this.ctx.fillStyle = 'green';
  this.ctx.fillRect(0, 0, config.canW, config.canH);
  this.ctx.fillStyle = 'red';
  this.ctx.font = 'bold 50px sans-serif';
  this.ctx.shadowOffsetX = 2;
  this.ctx.shadowOffsetY = 2;
  this.ctx.shadowColor = "yellow";
  this.ctx.fillText(this.headline, 150, 150);
  this.ctx.restore();
  this.ctx.save();
  this.ctx.font = 'bold 20px sans-serif';
  this.ctx.fillStyle = 'yellow';
  this.ctx.fillText(this.sub, 30, 200);
  this.ctx.restore();
};


//*******************
// A Simple factory that creates screens. There are different
// screens for: start, level1, end, ....
// A screen dictates how the game looks and which objects it
// contains.
//*******************
var screenFactory = function(ctx, type, player) {
  var objects = [];

  switch (type) {

    // Welcome screen
    case 'welcome':
      objects.push(new Panel({
        'ctx': ctx,
        'x': 0, 'y': 0,
        'width': config.canW,
        'height': config.canH,
        'type': 'panel',
        'headline': 'Ketchup',
        'sub': 'Starta med mellanslag, styr med piltangenterna',
        'background': 'green',
        'keyCheck': keyMem.isDown // Behövs den verkligen här?
      }));
      break;

    // Main game screens
    case 'level2':
      var speedFactor = 1.4; // Increase the speed in level 2
    case 'level1':
      // Creates the start area
      objects.push(new GameObj({
        'ctx': ctx,
        'x': 0, 'y': config.canH * 0.8,
        'width': config.canW,
        'height': config.canH * 0.2,
        'type': 'start',
        'pattern': '../img/game/floor/grass/grass_flowers_yellow1.png',
        'keyCheck': keyMem.isDown // Behövs den verkligen här?
      }));
      
      // Creates the road
      objects.push(new GameObj({
        'ctx': ctx,
        'x': 0, 'y':config.canH * 0.2,
        'width': config.canW,
        'height': config.canH * 0.6,
        'background': 'black'
      }));

/*
      // Curbs
      objects.push(new GameObj({
        'ctx': ctx,
        'x': 0, 'y': config.canH * 0.75,
        'width': config.canW,
        'height': config.canH * 0.05,
        'type': 'curb',
        'pattern': '../img/game/floor/rect_gray0.png',
        'keyCheck': keyMem.isDown // Behövs den verkligen här?
      }));
*/
      // Creates the finishing area
      objects.push(new GameObj({
        'ctx': ctx,
        'x': 0, 'y': 0,
        'width': config.canW,
        'height': config.canH * 0.2,
        'type': 'finish',
        'pattern': '../img/game/floor/grass/grass_flowers_yellow1.png',
        'keyCheck': keyMem.isDown
      }));

/*
      // Creates a info panel for score, lifes, ...
      objects.push(new GameObj({
        'ctx': ctx,
        'x': 0, 'y': 0,
        'width': config.canW/5,
        'height': 50,
        'type': 'panel',
      }));
*/
      // Creates the cars
      var laneY = config.canH * 0.2,
        laneNo = 4,
        laneHeight = config.canH * 0.55 / laneNo,
        cars = 3,
        distance = config.canW / cars,
        speeds = [60, -60, 45, -30]; // In pixels per second

      for (var i = 0 ; i < laneNo ; i++) {
        for (var j = 0 ; j < cars ; j++) {
          objects.push(makeVehicle({
            'ctx': ctx,
//            'x': distance * j - (distance * 0.7 * Math.random()),
            'x': distance * j + (distance * 0.7 * Math.random()),
            'y': laneY,
            'speed': speeds[i] * (speedFactor || 1),
            'img': speeds[i] > 0 ? config.vehiclesR[Kajja.random(0, config.vehiclesR.length - 1)] : 
              config.vehiclesL[Kajja.random(0, config.vehiclesL.length - 1)],
            'laneHeight': laneHeight,
            'keyCheck': keyMem.isDown
          }));
        }
        laneY += laneHeight;
      }

      // Adds the player object (which is not connected to a specific screen
      // like other objects are).
      objects.push(player);
      break;

    // Screen when the player moves to a new level
    case 'nextLevel':
      objects.push(new Panel({
        'ctx': ctx,
        'x': 0, 'y': 250,
        'width': config.canW,
        'height': 50,
        'type': 'panel',
        'sub': 'Bra jobbat, nu blir det lite svårare',
        'keyCheck': keyMem.isDown // Behövs den verkligen här?
    }));
    break;

    // Screen when the player has no lifes left
    case 'end':
      objects.push(new Panel({
        'ctx': ctx,
        'x': 0, 'y': 250,
        'width': config.canW,
        'height': 50,
        'type': 'panel',
        'sub': '   Tyvärr, inga liv kvar. Spelet är slut!',
        'keyCheck': keyMem.isDown // Behövs den verkligen här?
      }));
      break;

    // Screen when the player has completed the whole game
    case 'completed':
      objects.push(new Panel({
        'ctx': ctx,
        'x': 0, 'y': 250,
        'width': config.canW,
        'height': 50,
        'type': 'panel',
        'sub': 'Grattis!!! Du klarade hela spelet!',
        'keyCheck': keyMem.isDown // Behövs den verkligen här?
      }));
      break;
  }
  return new Screen(objects, player);
};

//*********************
// A Screen constructor function
// A screen is an object that contains a collection of game objects
// which it manages, ie. execute command on the collection.
// (Interface for screens: update(), draw())
//*********************
var Screen = function(objects, player) {
  this.objects = objects;
  this.player = player;
};

Screen.prototype.update = function(timeDiff) {

  var that = this;

  // Update all objects
  this.objects.forEach(function(obj) {
      obj.update(timeDiff);
  });

  // Check if the player is touching any other object
  this.objects.forEach(function(obj) {
    if (obj.type !== 'player' && obj.isTouching(that.player)) {
      that.player.touch(obj);
    }
  });
};

Screen.prototype.draw = function() {

  // Draw all objects
  this.objects.forEach(function(obj) {
    obj.draw();
  });
};

//(Man kanske kan optimera ngt genom att skapa de vanligaste screens från början?)

//********************
// A log object that holds information on which keys that are pressed down, 
// to avoid choppy movement of the player since the key-events happens at
// some interval when a key is pressed down, not synced with redraws. 
// Created as a module. A global singleton object (so there can be "hidden" references in code).
// Idea from here (although a different implementation): 
// http://nokarma.org/2011/02/27/javascript-game-development-keyboard-input/index.html
//********************
var keyMem = (function() {

  var keysDown = [];

  var listenerDown = function(event) {
    keysDown[event.keyCode] = true;
    return false;
  };

  var listenerUp = function(event) {
    delete keysDown[event.keyCode];
    return false;
  };

  var isDown = function(key) {
    return keysDown[key] ? true : false;
  };

  return {
    'listenerDown': listenerDown,
    'listenerUp': listenerUp,
    'isDown': isDown
  };
})();


//********************
// Starts the game when the document is ready
//********************
$(document).ready(function(){
  'use strict';

  // Setting the canvas dimensions
  var canvas = document.getElementById('game'),
    context = canvas.getContext('2d');
  canvas.width = config.canW;
  canvas.height = config.canH;

  // Handling key events
  $(document).on('keydown', keyMem.listenerDown);
  $(document).on('keyup', keyMem.listenerUp);

  // Init the gameLoop
  Game.init(context);

  // Init the GameSeq object
  GameSeq.init({
      gameLoop: Game,
      scrFctory: screenFactory,
      ctx: context
  });

  // Register listeners for start and pause events
  $(document).on('keydown', GameSeq.keyListener);

  // Start the game by triggering a 'welcome' event
  GameSeq.listener('welcome');

});