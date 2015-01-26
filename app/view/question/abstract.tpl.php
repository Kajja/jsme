<div class='abstract'>
    <a href='<?=$this->url->create("question/id/$questionId");?>'>
        <h3><?=$title;?></h3>
        <p><?=$text;?></p>
        <a href='<?=$this->url->create("answers/create/$questionId");?>'>Svara</a>
        <a href='<?=$this->url->create("comments/create/q/$questionId");?>'>Kommentera</a>
        <?=$user;?>
        <p><?=$created?></p>
    </a>
</div>