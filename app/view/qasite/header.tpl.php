<div>
    <span class='fa fa-bicycle fa-5x'></span>
    <span class='sitetitle'><?=$title?></span>
    <div>
        <?php if ($this->session->has('logged_in_userid')) : ?>
            <div><a href='<?=$this->url->create('users/logout')?>'>Logout</a></div>
            <div><p>Inloggad som: <?=$this->session->get('logged_in_usermail');?>
        <?php else : ?>
            <div><a href='<?=$this->url->create('users/login')?>'>Login</a></div>
        <?php endif; ?>
        <div><a href='<?=$this->url->create('users/add')?>'>Skapa användare</a></div>
    </div>
</div>