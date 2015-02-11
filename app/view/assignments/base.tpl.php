<div class='page'>
    <p><?=$title?></p>
        <ol>
            <?php foreach($exercises as $key => $val) : ?>
            <li><a href='<?=$val;?>'><?=$key;?></a></li>
            <?php endforeach; ?>
        </ol>
</div>