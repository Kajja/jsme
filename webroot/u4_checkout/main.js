$(document).ready(function(){
    'use strict';
  
    $('#pay_form').validate({
        rules: {
            name: {
                required: true
            },
            card_num: {
                required: true,
                creditcard: true
            },
            cvc: {
                required: true,
                digits: true,
                minlength: 3,
                maxlength: 3
            }
        }
    });

    $('#pay_form').submit(function(e) {

       // if ( $('#pay_form').valid() ) {
            Checkout.pay();            
        //}
        e.preventDefault();
    });
});

var Checkout = {

    pay: function() {
        var promise = $.ajax({
            url: 'pay_service.php?action=pay',
            type: 'post',
            data: $('#pay_form').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#btn_bar').append('<img class="loader" src="../img/preloader.gif" />');
            }
        });

        promise.done(function(data) {

                $('.loader').remove();

                if (data.status === 'ok') {
                    $('#info').removeClass().addClass('ok').text(data.msg);
                } else {
                    $('#info').removeClass().addClass('nok').text(data.msg);
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log('Något gick fel med Ajax-anropet:' + errorThrown);

        });
    }
}