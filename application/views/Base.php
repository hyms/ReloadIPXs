<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin titulo</title>
</head>

<body>
<p>Titulo Base</p>
<ul>
<?php foreach ($resul as $item):?>

<li><?php echo $item;?></li>

<?php endforeach;?>
</ul>
</body>
</html>
