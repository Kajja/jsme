<?php $title='Template for testprogram'; include(__DIR__ . '/../mall/header.php'); ?>
 
<div id='flash'>
    <form>
        <input type='text' id='mail' value='test@javascript.se'/>
        <input type='button' id='check' value='Kolla mail'/>
    </form>
    <p id='res'></p>
</div>
 
<?php $path=__DIR__; include(__DIR__ . '/../mall/footer.php'); ?>