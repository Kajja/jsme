<div class='page'>
    <a href='<?=$this->url->create("users/profile/$userId"); ?>'>
    	<h2>Användare</h2>
    	<ul>
    	<?php foreach ($userValues as $key => $value) : ?>
    		<li><pre><?=$key . ': ' . $value; ?></pre></li>
    	<?php endforeach; ?>
    	</ul>
    </a>
</div>