(function($) {

        $.fn.ezyRead = function(options) {

            return this.each(function() {
               var that = this;

                options = $.extend({}, $.fn.ezyRead.defaults, options);

                $('<span> &#9636</span>').click(function() {
                    var overlay, textArea;

                    overlay = $('<div id="easyRead-overlay">')
                        .css({
                            'background-color': 'black',
                            'opacity': 0.9,
                            'position': 'fixed',
                            'width': '100%',
                            'height': '100%',
                            'top': 0,
                            'left': 0
                        });
                    
                    $textArea = $('<p id="easyRead-text">')
                        .css({
                            'background-color': 'white',
                            'position': 'fixed',
                            'top': '5em',
                            'width': options.width,
                            'max-height': $(window).height() * 0.6,
                            'padding': options.padding,
                            'font-family': options['font-face'],
                            'line-height': options['line-height'],
                            'overflow-y': 'scroll'
                            })
                        .text($(that).text());
/*
                    $textArea.css({
                        'left': ($(window).width() - $textArea.width())/2
                    });
*/
                    $textArea.click(function() {
                        $('#easyRead-overlay, #easyRead-text')
                            .remove();
                    });

                    overlay.appendTo('body');
                    $textArea.appendTo('body');

                    //Fix to make the width-calculation work in Chrome
                    //works fine in IE and Firefox without this
                    $('#easyRead-text').css({
                        'left': ($(window).width() - $textArea.width())/2
                    });
                   
                }).appendTo(this);
            });
        };

        $.fn.ezyRead.defaults = {
            'font-face': 'times, serif',
            'line-height': '1.5em',
            'padding': '3em',
            'width': '27em'
        };
    })(jQuery);