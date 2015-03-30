<?php $title='Template for testprogram'; include(__DIR__ . '/../mall/header.php'); ?>
 
<div id='flash'>
    <audio id='splash'>
        <source src='../sounds/lava.ogg'></source>
        <source src='../sounds/lava.mp3'></source>
    </audio>
    <canvas id='game' style='border: 1px solid;'>
        Your browser does not support HTML Canvas.
    </canvas>
</div>
 
<?php $path=__DIR__; include(__DIR__ . '/../mall/footer.php'); ?>