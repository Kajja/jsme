<?php $title='Template for testprogram'; include(__DIR__ . '/../mall/header.php'); ?>
 
<div id='flash'>
    <div id='ex1'>
        <h1>Uppgift 1</h1>
        <p>Här kommer lite intetsägande text bara för att få ett textelement</p>
        <img id='img1' src='../img/bike_small.png'/>
    </div>
    <div id='ex2'>
        <h1>Uppgift 2</h1>
        <p>Här kommer lite intetsägande text bara för att få ett textelement</p>
        <img id='img2' src='../img/bike_small.png'/>
    </div>
     <div id='ex3'>
        <h1>Uppgift 3</h1>
        <p>Här kommer lite intetsägande text bara för att få ett textelement</p>
        <p>Cykeln är förresten en holländsk paketcykel från De Fietsfabriek</p>
        <button id='btn1'>Lägg till element</button>
    </div>
    <div id='ex4'>
        <h1>Uppgift 4</h1>
        <p>Ändra dimensioner på bild</p>
        <div>
            <label>Ändra höjd: <button id='incH'>+</button></label><button id='decH'>-</button>
            <label>Ändra bredd: <button id='incW'>+</button></label><button id='decW'>-</button>
        </div>
        <img id='img4' src='../img/bike_small.png'/>
    </div>
    <div id='ex5' class='wrapper'>
        <h1>Uppgift 5</h1>
        <p>Slide och fade (måste sätta bildstorleken först annars fungerar det inte)</p>
        <div>
            <button id='btn_slide'>Slide</button>
            <button id='btn_fade'>Fade</button>
        </div>
        <img id='img_slide' src='../img/bike_small.png'/>
        <img id='img_fade' src='../img/bike_small.png'/>
    </div>
    <div id='ex6'>
        <h1>Uppgift 6</h1>
        <p>Lightbox</p>
        <a href='../img/bike_small.png' class='lightbox'><img src='../img/bike_small.png'/></a>
    </div>
    <div id='ex7'>
        <h1>Uppgift 7</h1>
        <p>Bildgalleri</p>
        <div id='gallery'>
            <div id='img_large'><img/></div>
            <ul>
                <li class='thumb'><img data-img='../img/bike_small.png' src='../img/bike_small.png'/></li>
                <li class='thumb'><img data-img='../img/stereo.png' src='../img/stereo.png'/></li>
                <li class='thumb'><img data-img='../img/bord.png' src='../img/bord.png'/></li>
                <li class='thumb'><img data-img='../img/bike_small.png' src='../img/bike_small.png'/></li>
                <li class='thumb'><img data-img='../img/stereo.png' src='../img/stereo.png'/></li>
                <li class='thumb'><img data-img='../img/bord.png' src='../img/bord.png'/></li>
                <li class='thumb'><img data-img='../img/bike_small.png' src='../img/bike_small.png'/></li>
                <li class='thumb'><img data-img='../img/stereo.png' src='../img/stereo.png'/></li>
                <li class='thumb'><img data-img='../img/bord.png' src='../img/bord.png'/></li>
            </ul>
        </div>
    </div>
    <div id='ex8'>
        <h1>Uppgift 8</h1>
        <p>Slideshow</p>
        <div id='slider'>
            <img src='../img/bike_small.png'/></li>
            <img class='shown'src='../img/stereo.png'/></li>
            <img src='../img/bord.png'/></li>
        </div>
    </div>
</div>
 
<?php $path=__DIR__; include(__DIR__ . '/../mall/footer.php'); ?>