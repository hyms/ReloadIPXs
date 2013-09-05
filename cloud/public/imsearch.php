<?php require_once("res/x5engine.php"); ?>
<!DOCTYPE html><!-- HTML5 -->
<html lang="es" dir="ltr">
	<head>
		<meta charset="utf-8" />
		<!--[if IE]><meta http-equiv="ImageToolbar" content="False" /><![endif]-->
		<meta name="generator" content="Incomedia WebSite X5 Evolution 9.0.2.1699 - www.websitex5.com" />
		<link rel="stylesheet" type="text/css" href="style/reset.css" media="screen,print" />
		<link rel="stylesheet" type="text/css" href="style/print.css" media="print" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen,print" />
		<link rel="stylesheet" type="text/css" href="style/template.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="style/menu.css" media="screen" />
		<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="style/ie.css" media="screen" /><![endif]-->
		<script type="text/javascript" src="res/swfobject.js"></script>
		<script type="text/javascript" src="res/jquery.js"></script>
		<script type="text/javascript" src="res/x5engine.js"></script>
		<script type="text/javascript" src="res/x5cartengine.js"></script>
		<script type="text/javascript" src="res/l10n.js"></script>
		<script type="text/javascript" src="res/x5settings.js"></script>
		
		
		<title>Buscar - Demoserver</title>
		<!-- CSS -->
		<script type="text/javascript">x5engine.imQueue.push_init('x5engine.imSearch.Load()')</script>
	</head>
	<body>
		<div id="imPage">
			<div id="imHeader">
				<h1 class="imHidden">Demoserver</h1>	
				
			</div>
			<a class="imHidden" href="#imGoToCont" title="Salta el menu principal">Vaya al Contenido</a>
			<a id="imGoToMenu"></a><p class="imHidden">Menu Principal</p>
			<div id="imMnMnGraphics"></div>
			<div id="imMnMn" class="auto">
				<ul class="auto">
					<li id="imMnMnNode0">
						<a href="index.html">
							<span class="imMnMnFirstBg">
								<span class="imMnMnTxt"><span class="imMnMnImg"></span>Página de inicio</span>
							</span>
						</a>
					</li>
					<li id="imMnMnNode3">
						<a href="login.html">
							<span class="imMnMnFirstBg">
								<span class="imMnMnTxt"><span class="imMnMnImg"></span>Login</span>
							</span>
						</a>
					</li>
					<li id="imMnMnNode4">
						<a href="informacion.html">
							<span class="imMnMnFirstBg">
								<span class="imMnMnTxt"><span class="imMnMnImg"></span>Informacion</span>
							</span>
						</a>
					</li>
				</ul><script type="text/javascript">x5engine.imQueue.push_init("x5engine.utils.imPreloadImages(['menu/index_h.png','menu/login_h.png','menu/informacion_h.png','menu/sub.png','menu/sub_h.png','menu/sub_f.png','menu/sub_f_h.png','menu/sub_l.png','menu/sub_l_h.png','menu/sub_m.png','menu/sub_m_h.png','res/imLoad.gif','res/imClose.png'])",false);</script>
				
			</div>
			<div id="imContentGraphics"></div>
			<div id="imContent">
				<a id="imGoToCont"></a>
				<?php
$search = new imSearch();
$search->search(@$_GET['search'], @$_GET['page']);
?>
				<div class="imClear"></div>
			</div>
		</div>
		<div id="imFooterBg">
			<div id="imFooter">
				
			</div>
		</div>
		<span class="imHidden"><a href="#imGoToCont" title="Lea esta página de nuevo">Regreso al contenido</a> | <a href="#imGoToMenu" title="Lea este sitio de nuevo">Regreso al menu principal</a></span>
		
	</body>
</html>
