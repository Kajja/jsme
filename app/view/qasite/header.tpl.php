<span class='fa-stack fa-2x'>
    <i class='fa fa-circle fa-stack-2x'></i>
    <i class='fa fa-bicycle fa-stack-1x fa-inverse'></i>
</span>
<span class='sitetitle'><?=$title?></span>
<div id='header-user'>
    <?php if ($this->session->has('logged_in_userid')) : ?>
        <a href='<?=$this->url->create('users/logout')?>'>Logout</a>
        <p>Inloggad som: 
            <a href='<?=$this->url->create('users/profile/' . $this->session->get('logged_in_userid'));?>'>
                <?=$this->session->get('logged_in_username');?>
            </a>
    <?php else : ?>
        <a href='<?=$this->url->create('users/login')?>'>Login</a>
    <?php endif; ?>
    <p><a href='<?=$this->url->create('users/add')?>'>Skapa användare</a></p>
</div>