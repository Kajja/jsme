<div class='page'>
	<h1><?=$title?></h1>
 
 	<table>
	<?php foreach ($users as $user) : ?>
  		<tr>
  		<?php foreach ($user as $val) : ?>
  			<td><pre><?=$val?></pre></td>
	  	<?php endforeach; ?>
	    </tr>
	<?php endforeach; ?>
	</table>
</div>