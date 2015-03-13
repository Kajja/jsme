$(document).ready(function(){
  'use strict';
    
    Shop.getCart();

    $('button.buy').click(Shop.add);
    $('#clear_btn').click(Shop.clear);
  
});

var Shop = {
    add: function() {
        var promise = $.ajax({
            url: 'cart.php?action=add',
            type: 'post',
            data: {
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
    updateCart: function(data) {
        var items = data.items, html = '';

        for (var item in data.items) {
            if (data.items.hasOwnProperty(item)) {
                html += '<tr><td>' + items[item].name + '</td><td>' + 
                        items[item].num_of + '</td><td>' + 
                        items[item].sum + '</td></tr>';
            }
        }

        $('#items tbody').html(html);
        $('#num_art').text(data.num_items);
        $('#sum_art').text(data.sum);
    },
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