<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $this->tpl_vars['title'];?></title>
</head>
<body>
	<h1><?php echo $this->tpl_vars['title'];?></h1>
	<p><?php echo $this->tpl_vars['content'];?></p>

	<p>
		<?php if ($this->tpl_vars['love']):?>
			真爱
		<?php else :?>
			骗子
		<?php endif;?>
	</p>
	<ul>
		<?php foreach ($this->tpl_vars['user'] as $k => $v) :?>
			<li><?php echo $k;?> --- <?php echo $v;?></li>
		<?php endforeach;?>
	</ul>
</body>
</html>