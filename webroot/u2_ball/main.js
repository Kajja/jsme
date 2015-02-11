var myBall = {
    position: {x:100, y:50},
    HTMLelement: document.getElementById('ball'),
    draw: function() {
        this.HTMLelement.style.top = this.position.y + 'px';
        this.HTMLelement.style.left = this.position.x + 'px';
    }
};

$(document).ready(function(){
    'use strict';
    
    myBall.draw();

    // Adds click-eventlistener to ball
    myBall.HTMLelement.addEventListener('click', function(event) {
            var factor = 8;

            myBall.position.x -= (event.layerX - myBall.HTMLelement.clientWidth/2) * factor;
            myBall.position.y -= (event.layerY - myBall.HTMLelement.clientHeight/2) * factor;
            myBall.draw();
    });

    // Adds drag-eventlistener to ball --- DOES NOT WORK
    myBall.HTMLelement.addEventListener('dragend', function(event) {

            console.log('clientX: ' + event.clientX + ' clientY: ' + event.clientY);
            console.log('pageX:   ' + event.pageX +   ' pageY:   ' + event.pageY);
            console.log('offsetX:   ' + event.offsetX +   ' offsetY:   ' + event.offsetY);
            console.log('bollenX: ' + event.target.style.left + ' bollenY: ' + event.target.style.top);
            console.log(event.clientY - event.target.offsetTop);            
            //myBall.draw();

            console.log(event);
    });
});