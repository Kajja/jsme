$(document).ready(function(){
  'use strict';
  var button, height, width, div;

    div = document.getElementById('flash');
    height = document.getElementById('height');
    width = document.getElementById('width');

    height.value = div.offsetHeight;
    width.value = div.offsetWidth;

    button = document.getElementById('update_button');

    // Add eventlistener
    button.addEventListener('click', function(event) {

        // Set the new values
        div.style.height = height.value + 'px';
        div.style.width = width.value + 'px';

    });
});