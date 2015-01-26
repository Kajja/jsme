<article class='page'>
    <p><?=$title?></p>
    <?=$content?>

    <?php if(isset($byline)) : ?>
    <footer class="byline">
    <?=$byline?>
    </footer>
    <?php endif; ?>
</article>