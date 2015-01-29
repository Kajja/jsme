<div class='panel answer'>
    <p><?=$text;?></p>
    <div>
        <?=$user;?>
        <span><sub><?=$created?></sub></span>
    </div>
    <div id='answer-actions'>
        <span><a href='<?=$this->url->create("comments/create/a/$answerId");?>'>Kommentera</a></span>
    </div>
</div>