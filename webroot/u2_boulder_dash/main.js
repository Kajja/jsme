$(document).ready(function(){
    'use strict';

    // Event listener to control the player
    document.addEventListener('keydown', function(event) {
        var target = document.getElementById('player'), step = 1, player = boulderD.player;
        switch(event.keyCode) {
            case 37:  // ascii value for arrow left 
                player.setOrientation('left');
                boulderD.activate(player, [player.getXPos() - step, player.getYPos()]);
                break;
            case 39:  // ascii value for arrow right 
                player.setOrientation('right'); 
                boulderD.activate(player, [player.getXPos() + step, player.getYPos()]);
                break;
            case 38:  // arrow up
                player.setOrientation('up');
                boulderD.activate(player, [player.getXPos(), player.getYPos() - step]);
                break;
            case 40:  // arrow down
                player.setOrientation('down');
                boulderD.activate(player, [player.getXPos(), player.getYPos() + step]);                
                break;
        }
        //event.preventDefault();
    });
    boulderD.player.type = 'baddie';
    boulderD.generatePlan();
    boulderD.draw();
});

// Game object
boulderD = {
    // The blueprint of the plan
    planBlueprint: [
            [ 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'cr solid', 'cr solid', 'cr solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'cr solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'cr solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'crg price', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],            
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'mw solid', 'mw solid', 'mw solid', 'mw solid', 'mw solid', 'mw solid', 'mw solid', 'mw solid', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'mw solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'mw solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'mw solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'gr', 'br solid'],
            [ 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid', 'br solid'], 
        ],

    // The actual plan consisting of tile objects
    plan: [],

    // Metod that generate a game plan consisting of tile objects
    // from the plan blueprint.
    generatePlan: function() {

        var i, that = this;

        for (i = 0 ; i < this.planBlueprint.length ; i++) {
            this.plan[i] = this.planBlueprint[i].map(function(x) { 
                
                var tileObj, tokens, type, property;

                tileObj = Object.create(that.tile);
                tokens = x.split(' ');  // Tokenizing

                // Interpreting tile type
                switch(tokens[0]) {
                    case 'br':
                        type = 'brick';
                        break;
                    case 'gr':
                        type = 'grass';
                        break;
                    case 'cr':
                        type = 'crystal';
                        break;
                    case 'crg':
                        type = 'crystal_green';
                        break;
                    case 'mw':
                        type = 'metal_wall';
                        break;
                }
                tileObj.type = type;

                if (tokens[1]) { // If it doesn't exist it is undefined which gives false
                    switch(tokens[1]) {
                        case 'solid':
                            tileObj.solid = true;
                            break;
                        case 'price':
                            tileObj.price = 1000;
                            break;
                    }
                }
                return tileObj;
            });
        }
    },

    // Object representing a tile in the game plan
    tile: {
        type: 'grass',  // Default value
        solid: false    // Default value
    },

    // The figure that the player controls
    player: {
        xPos: 10,
        yPos: 10,
        type: '',
        orientation: 'right',
        getXPos: function() {
            return this.xPos;
        },
        getYPos: function() {
            return this.yPos;
        },
        setPos: function(pos) {
            this.xPos = pos[0];
            this.yPos = pos[1];
        },
        setOrientation: function(orientation) {
            this.orientation = orientation;
        },
        getOrientation: function() {
            return this.orientation;
        }
    },

    // The game rules.
    // Decides what happens when a figure tries to go to a specific tile.
    activate: function(figure, tilePos) {
        var tile = this.plan[tilePos[1]][tilePos[0]];

        if (!tile.solid) {
            figure.setPos(tilePos);
        }
        this.draw();
        if (tile.price) {
            alert('Du har fått ' + tile.price + ' poäng!');
        }
    }
};


boulderD.draw = function() {
    var y, x, html = '';

    for (y = 0 ; y < this.plan.length ; y++) {
        for (x = 0 ; x < this.plan[y].length ; x++) {
            html += '<td class="' + this.plan[y][x].type + '">';
            // Draw figure if on this position
            if (this.player.xPos === x && this.player.yPos === y) {
                html += '<div class="' + this.player.type + ' ' + this.player.getOrientation() + ' id="player"/>';
            }
            html += '</td>';
        }
        html += '</tr>';
    }
    document.getElementById('plan').innerHTML = html;
};