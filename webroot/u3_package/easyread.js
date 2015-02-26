(function($) {

        $.fn.easyRead = function() {

            return $(this).each(function() {
               var that = this;
                $('<div>ez</div>').click(function() {
                    var overlay, textArea;

                    overlay = $('<div id="easyRead-overlay">')
                        .css({
                            'background-color': 'black',
                            'opacity': 0.8,
                            'width': $(window).width(),
                            'height': $(window).height(),
                            'position': 'fixed',
                            'top': 0
                            //'z-index': 100
                        });
                    
                    $textArea = $('<p id="easyRead-text">')
                        .css({
                            'background-color': 'white',
                            'position': 'fixed',
                            'top': 0,
                            'width': '27em',
                            'margin': '0 auto',
                            'padding': '1em',
                            'font-family': 'times, serif'
                            }).text($(that).text());

                    $textArea.click(function() {
                        $('#easyRead-overlay, #easyRead-text')
                            .remove();
                    });
                    overlay.appendTo('body');
                    $textArea.appendTo('body');
                   
                }).insertAfter(this);
            });
        }
    })(jQuery);

    $('#ex9 p').easyRead();