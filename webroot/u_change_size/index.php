<?php $title='Template for testprogram'; include(__DIR__ . '/../mall/header.php'); ?>
 
<div id='flash'>
    <form id='size'>
        <label for='width'>Längd:</label>
        <input type='number' id='width' />
        <label for='height'>Höjd:</label>
        <input type='number' id='height' />
        <input type='button' id='update_button' value='Uppdatera' />
    </form>
</div>
 
<?php $path=__DIR__; include(__DIR__ . '/../mall/footer.php'); ?>