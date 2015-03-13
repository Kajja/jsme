$(document).ready(function(){
    'use strict';

    var getQuote = function() {
        var promise = $.ajax({url: 'quote.php', dataType: 'json'});
        var textEl = $('#text');

        promise.done(function(data) {
                textEl.fadeOut(function() {
                    $(this).text(data.quote).fadeIn();
                });
            })
            //textEl.text(data.quote);})
            .fail(function(jqXHR, textStatus, errorThrown) {
                textEl.text('Något gick fel i Ajax-anropet');
        });
    };

    $("[type='button']").click(getQuote).trigger('click');

});