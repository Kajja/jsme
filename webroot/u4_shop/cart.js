$(document).ready(function(){
    'use strict';
 
    // Style button and link
    $('button, #checkout').button();

    // Hide button and link from start
    $('#clear_btn, #checkout').hide();

    // Get the information from the cart and display it
    Shop.getCart();

    // Add handlers to the 'Köp' and 'Rensa' buttons
    $('button.buy').click(Shop.add);
    $('#clear_btn').click(Shop.clear);
  
});

var Shop = {
    // Add an item to the cart
    add: function() {
        var promise = $.ajax({
            url: 'cart.php?action=add',
            type: 'post',
            data: { // Get information about the item from attributes of "Köp"-button
                'id': $(this).attr('data-id'), 
                'name': $(this).attr('data-name'), 
                'price': $(this).attr('data-price')
            },
            dataType: 'json'
        });

        promise.done(function(data) {
            Shop.updateCart(data);
        });
    },
    // Update the cart view with the latest information
    updateCart: function(data) {
        var items = data.items, html = '';

        for (var item in data.items) {
            if (data.items.hasOwnProperty(item)) {
                html += '<tr><td>' + items[item].name + '</td><td>' + 
                        items[item].num_of + '</td><td>' + 
                        items[item].sum + '</td></tr>';
            }
        }

        // Updates the table in the cart view and number of items and total sum
        $('#items tbody').html(html);
        $('#num_art').text(data.num_items);
        $('#sum_art').text(data.sum);

        // Display the "Rensa" and "Betala" buttons if there are items in the cart
        if (data.num_items !== 0) {
            $('#clear_btn, #checkout').show();  
        } else {
            $('#clear_btn, #checkout').hide();
        }
    },
    // Clear the cart
    clear: function() {
        var promise = $.ajax({
            url: 'cart.php?action=clear',
            type: 'get',
            dataType: 'json'
        });

        promise.done(function(data) {
            Shop.updateCart(data);
        });
    },
    // Get cart info
    getCart: function() {
        var promise = $.ajax({
            url: 'cart.php?action=contents',
            type: 'get',
            dataType: 'json'
        });

        promise.done(function(data) {
            Shop.updateCart(data);
        });
    }
};