$(document).ready(function() {
   
    //***** Exercise 1
    $('#ex1 h1, #ex1 p, #ex1 img').click(function(e) {
        $(e.target).toggleClass('highlight');
    }); 

    //***** Exercise 2
    $('#ex2').click(function(e) {
        $(this).toggleClass('highlight');
    });

    $('#ex2 img').click(function(e) {
        e.stopPropagation();
        $(this).animate({
            height: '-=10%',
            width: '-=10%'
        });
    });

    //***** Exercise 3
    var i = 1;
    $('#btn1').click(function() {
        $('<div class="new_el">Nytt element nr' + i + '</div>').insertAfter('#ex3 button');
        i++;
    });

    $('#ex3').on('click', '.new_el', function() {
        $(this).remove();
    });

    //***** Exercise 4
    var img =   $('#img4');

    $('#incH').click(function() {
        img.height(img.height() + 5);
    });
    $('#decH').click(function() {
        img.height(img.height() - 5);
    });
    $('#incW').click(function() {
        img.width(img.width() + 5);
    });
    $('#decW').click(function() {
        img.width(img.width() - 5);
    });

    //***** Excercise 5
    $('#btn_slide').click(function() {
        $('#img_slide').slideToggle(500);
    });
    $('#btn_fade').click(function() {
        $('#img_fade').fadeToggle(500);
    });

    //***** Excercise 6 Lightbox
    function lightbox(event) {
        //$('body').css('overflow-y', 'hidden');

        $('<div id="overlay"></div>')
            .width('100%')
            .height('100%')
            .appendTo('body');

        $('<div id="lightbox"></div>')
            .hide()
            .appendTo('body');

        $('<img src="' + $(this).attr('href') + '"/>')
            .load(positionImage)
            .click(removeLightbox)
            .appendTo('#lightbox');

//        event.preventDefault();
        return false;
    }

    function positionImage() {
        var top = ($(window).height() - $('#lightbox').height())/2,
            left = ($(window).width() - $('#lightbox').width())/2;

        $('#lightbox')
            .css({  
                'top': top, 
                'left': left})
            .fadeIn();
    }

    function removeLightbox() {
        $('#overlay, #lightbox').remove();
        $('body').css('overflow-y', 'auto');
    }

    $('.lightbox').click(lightbox);

    //***** Exercise 7 Gallery
    var thumbs = $('ul img');

    thumbs.click(function() {
        var $img = $(this);
        thumbs.removeClass('active');

        // The image-url to be displayed is located in the data-img attr
        $('#img_large img')
            .replaceWith('<img src="' + $img.attr('data-img') + '"/>');
        
        $img.addClass('active');
    });

    // Show which thumb the user is hovering over
    thumbs.hover(function() {
        $(this).addClass('hover');
    }, function() {
        $(this).removeClass('hover');
    });

    //Initial picture
    thumbs.first().trigger('click');

    //***** Exercise 8 Slideshow
    var $imgs = $('#slider img');
    function changeFront() {
        var $now = $imgs.filter('.shown'),
            $next = $now.next().length ? $now.next() : $imgs.first();

        $next.show();
        $now.fadeOut('slow', function() {
            $(this).removeClass('shown');
            $next.addClass('shown');
        });
    }

    $imgs.click(changeFront);

    setInterval(changeFront, 3000);

});