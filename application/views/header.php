<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title><?php echo $titulo ?></title>
</head>
<body>
<menu>
	<li><?php echo anchor('', 'Recarga') ?></li>
	
	<?php if (!$login): ?>
	<li><?php echo anchor('user', 'Login') ?></li>
	<?php else: ?>
	<li><?php echo anchor('user/crear', 'Crear Usuario') ?></li>
	<li><?php echo anchor('cuenta/crear', 'Crear Cuenta') ?></li>
	<li><?php echo anchor('user/crear_login', 'Crear Login') ?></li>
	<li><?php echo anchor('user/logout', 'Logout') ?></li>
	<?php endif; ?>
	
</menu>
<h2><?php echo $titulo ?></h2>