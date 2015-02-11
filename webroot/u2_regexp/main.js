$(document).ready(function(){
  'use strict';
  var button = document.getElementById('check');

  button.addEventListener('click', function() {
    var text = '', mail = document.getElementById('mail').value;
    var result = document.getElementById('res');

    if (mailVerifyer(mail)) {
        text = 'Giltig mailadress';
    } else {
        text = 'Ingen giltig mailadress';
    }

    result.textContent = text;
  });
  
});

function mailVerifyer (mail) {
    var regexp = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    return regexp.test(mail);
}