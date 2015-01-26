<!DOCTYPE html>
<html lang='<?=$lang;?>' class='<?=$htmlClasses;?>'>
    <head>
        <meta charset='utf-8'/>
        <title><?=$title;?></title>
        <link rel='shortcut icon' href='<?=$favicon;?>'>
        <?php foreach($stylesheets as $stylesheet): ?>
        <link rel='stylesheet' type='text/css' href='<?=$this->url->asset($stylesheet)?>'/>
        <?php endforeach; ?>
        <?php if(isset($style)): ?><style><?=$style?></style><?php endif; ?>
        <script src='<?=$this->url->asset($modernizr)?>'></script>
    </head>
    <body class='<?=$bodyClasses;?>'>
        <div id='wrapper'>
            <?php if ($this->views->hasContent('header')) : ?>
            <header><?php $this->views->render('header'); ?></header>
            <?php endif; ?>

            <?php if ($this->views->hasContent('nav')) : ?>
            <div id='nav'><?php $this->views->render('nav'); ?></div>
            <?php endif; ?>    
            
            <?php if ($this->views->hasContent('alert')) : ?>
            <div id='alert'><?php $this->views->render('alert'); ?></div>
            <?php endif; ?>  

            <?php if ($this->views->hasContent('flash')) : ?>
            <div id='flash'><?php $this->views->render('flash'); ?></div>
            <?php endif; ?>
            
            <?php if ($this->views->hasContent('main')) : ?>
            <main>
            <?php $this->views->render('main'); ?>
            </main>
            <?php endif; ?>

            <?php if ($this->views->hasContent('panel-col-1', 'panel-col-2', 'panel-col-3', 'panel-col-4')) : ?>
            <div id='wrap-panel-col'>
                <?php if ($this->views->hasContent('panel-col-1')) : ?>
                <div id='panel-col-1'><?php $this->views->render('panel-col-1'); ?></div>
                <?php endif; ?>
                <?php if ($this->views->hasContent('panel-col-2')) : ?>
                <div id='panel-col-2'><?php $this->views->render('panel-col-2'); ?></div>
                <?php endif; ?>
                <?php if ($this->views->hasContent('panel-col-3')) : ?>
                <div id='panel-col-3'><?php $this->views->render('panel-col-3'); ?></div>
                <?php endif; ?>
                <?php if ($this->views->hasContent('panel-col-4')) : ?>
                <div id='panel-col-4'><?php $this->views->render('panel-col-4'); ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <footer>
                <?php if ($this->views->hasContent('footer-col-1', 'footer-col-2', 'footer-col-3')) : ?>
                <div id='wrap-footer-col'>
                    <?php if ($this->views->hasContent('footer-col-1')) : ?>
                    <div id='footer-col-1'><?php $this->views->render('footer-col-1'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->views->hasContent('footer-col-2')) : ?>
                    <div id='footer-col-2'><?php $this->views->render('footer-col-2'); ?></div>
                    <?php endif; ?>
                    <?php if ($this->views->hasContent('footer-col-3')) : ?>
                    <div id='footer-col-3'><?php $this->views->render('footer-col-3'); ?></div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php if ($this->views->hasContent('footer')) : ?>
                <div id='footer'><?php $this->views->render('footer'); ?></div>
                <?php endif; ?>
            </footer>
        </div>
    </body>
</html>