$(document).ready(function(){
    'use strict';
      
    var loginControl = function(e) {
        var promise = $.ajax({
                        url: 'login.php?do=' + e.data,
                        type: 'post',
                        dataType: 'json',
                        data: $('#lform').serialize()
                    });

        promise.done(function(data) {
                $('#out').text(data.out);
            })
            .fail(function() {
                console.log('Något gick fel med Ajax-anropet');
        });

    // Eftersom det inte är submit-knappar, så submittas aldrig formuläret
    // och man behöver inte göra e.preventDefault().
    }

    $('#login').click('login', loginControl);
    $('#logout').click('logout', loginControl);
    $('#status').click('status', loginControl);

});