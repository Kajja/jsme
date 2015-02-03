$(document).ready(function(){
  'use strict';
  var text;
  var step, target, area, top, left, moveIt;

  step = 128;
  target = document.getElementById('b1');
  area = document.getElementById('flash');
  left = target.offsetLeft;
  top  = target.offsetTop;
  
  // Move the baddie
  moveIt = function(moveLeft, moveTop) {
    target.style.left = (target.offsetLeft + moveLeft) + 'px';
    target.style.top  = (target.offsetTop + moveTop) + 'px';
  };
  moveIt(0, 0);
  
  document.onkeydown = function(event) {
    var key;
    key = event.keyCode || event.which;
    switch(key) {
      case 37:  // ascii value for arrow left 
        target.className='baddie left'; 
        moveIt(-step, 0); 
        break;
      case 39:  // ascii value for arrow right 
        target.className='baddie right'; 
        moveIt(step, 0); 
        break;
      case 38:  // arrow up
        target.className='baddie up';
        moveIt(0, -step); 
        break;
      case 40:  // arrow down
        target.className='baddie down';
        moveIt(0, step); 
        break;
      case 66:  // b = back
        target.style.zIndex = -1;
        break;                   
      case 70:  // f = front 
        target.style.zIndex = 1;
        break;
      case 72:  // h = home 
        moveIt(left-target.offsetLeft, top-target.offsetTop);
        break;
      case 32:  // space = jump
        moveIt(0, -step);
        // What jumps up, must come down, a bit later.
        setTimeout(function(){moveIt(0, step);console.log('Timer!');}, 300);
        break;
      case 82:  // r = random
        moveIt(step*Math.floor(Math.random()*(3)-1), step*Math.floor(Math.random()*(3)-1));
        break;
      case 68:  // d = double salto 
        (target.className === 'baddie salto') ? target.className = 'baddie salto again' : target.className = 'baddie salto';
        break;
      default:
        target.className='baddie down';
        break;
    }    
    console.log('Keypress: ' + event + ' key: ' + key + ' new pos: ' + target.offsetLeft + ', ' + target.offsetTop);
  };

  area.onclick = function(event) {
    moveIt(event.clientX-target.offsetLeft-64, event.clientY-target.offsetTop-64);
    console.log('Clicked area.' + event + ' Moving baddie to mouse pointer position.');
  };
  
  console.log('Current position: ' + target.offsetLeft + ', ' + target.offsetTop);
  console.log('Everything is ready.');
  
});