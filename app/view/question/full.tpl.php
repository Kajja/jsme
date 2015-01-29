<div class='question-full panel'>
    <h3><?=$title;?></h3>
    <p><?=$text;?></p>
    <div>
        <?=$askedBy;?>
        <span><sub><?=$created?></sub></span>
    </div>
    <div id='question-actions'>
        <span><a href='<?=$this->url->create("answers/create/$questionId");?>'>Svara</a></span>
        <span><a href='<?=$this->url->create("comments/create/q/$questionId");?>'>Kommentera</a></span>
    </div>
</div>