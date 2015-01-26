<div class='abstract answer'>
    <p><?=$text;?></p>
    <a href='<?=$this->url->create("comments/create/a/$answerId");?>'>Kommentera</a>
    <?=$user;?>
    <p><?=$created?></p>
</div>