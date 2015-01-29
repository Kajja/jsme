<div class='panel user-profile'>
    <h2><?=$userName;?></h2>
    <div>
        <img src='https://www.gravatar.com/avatar/<?=md5(strtolower(trim($email)));?>?s=100&d=identicon' alt='Profilbild'/>
        <div id='profile-content'>
            <p><em>E-post: </em><?=$email;?></p>
            <p><em>Medlem sedan: </em><?=$created;?></p>
        </div>
    </div>
    <a id='update' href='<?=$this->url->create("users/update/{$id}");?>'>Uppdatera</a>
</div>