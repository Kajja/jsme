$(document).ready(function(){
    'use strict';
    var sum = 0;

    // jQuery validation
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

    // Set up handler for the submit event on the form
    $('#pay_form').submit(function(e) {

        if ( $('#pay_form').valid() ) { // First check that the form has passed validation
            // Try to execute payment
            Checkout.pay();
        }
        // Stop the default behavior of a submit-event on a form
        e.preventDefault(); 
    });

    // Add styling to button
    $('input[type="submit"], a').button();

    // Display sum to pay and check if the 'Betala' button should be shown
    Checkout.checkCart().done(Checkout.displaySum).done(Checkout.showButton);
});

// Namespace object
var Checkout = {

    pay: function() {
        // Call the pay-service to process the payment
        var promise = $.ajax({
            url: 'pay_service.php?action=pay',
            type: 'post',
            data: $('#pay_form').serialize(),
            dataType: 'json',
            beforeSend: function() { // Display loader while processing
                $('#btn_bar').append('<img class="loader" src="../img/ajax-loader.gif" />');
            }
        });

        promise.done(function(data) {

                $('.loader').remove();

                if (data.status === 'ok') {
                    // Display ok message and remove any error message
                    $('#info').removeClass().addClass('ok').text(data.msg);
                    $('#error').remove();

                    //Clear cart
                    $.ajax({
                        url: 'cart.php?action=clear',
                        type: 'get',
                        dataType: 'json'
                    });

                    // Check if the 'Betala' button should be shown
                    Checkout.checkCart().done(Checkout.showButton);
                } else {
                    // Show an error message
                    $('#error').removeClass().addClass('nok').text(data.msg);
                }
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log('Något gick fel med Ajax-anropet:' + errorThrown);

        });
    },
    // Get the cart contents, returns promise that interested
    // parties can use to access the returned info
    checkCart: function() {
        
        // Using the cart service
        var promise = $.ajax({
            url: 'cart.php?action=contents',
            dataType: 'json'
        }), sum = 0;

        return promise;
    },

    // Only show the "Betala"-button if there is a sum to pay
    // (a callback for the Cart-service)
    showButton: function(data) {
        // Check if there is anything to pay if so show button
        if (data.sum !== 0) {
            $('#pay_btn').show();
        } else {
            $('#pay_btn').hide();
        }
    },

    // Display the sum to pay (a callback for the Cart-service)
    displaySum: function(data) {
        // Insert the sum to be payed, there is a hook in the HTML
        $('#sum').text(data.sum);
    }
}