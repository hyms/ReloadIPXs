<?php
//print_r($result);
?>

<ul>
<?php if (is_array($result)): ?>
	<?php foreach ($result as $item):?>
	
		<li><?php print_r($item);?></li>
	
	<?php endforeach;?>
<?php else: ?>

   <li><?php print_r($result); ?></li>

<?php endif; ?>
</ul>