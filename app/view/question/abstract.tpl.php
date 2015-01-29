<div class='abstract'>
    <a href='<?=$this->url->create("question/id/$questionId");?>'>
        <p><?=$title;?></p>
    </a>
    <sub>Av: <a href='<?=$this->url->create("users/profile/$userId");?>'><?=$askedBy?></a>, <?=$created?></sub>
</div>