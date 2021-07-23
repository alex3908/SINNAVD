<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idSesion = $_SESSION['id'];	
	$nivel= $_SESSION['nivel'];

?>
<!DOCTYPE HTML>
<html>
	<head lang="es-Es">
		<title>Entregas</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
	</head>
	<body>
		<div id="wrapper">
			<div id="main">
				<div class="inner">
					<div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div>
					<h2>Entregas</h2>
					<div class="box">
						<form id="registro" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
							<div class="row uniform">
								<div class="2u">Lugar de entrega</div>
								<div class="3u">
									<div class="select-wrapper">
										<select name="LugEntrega" id="LugEntrega">
										</select>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>	
					
			
			<div id="sidebar">
				<div class="inner">
					<!-- Menu -->
					<nav id="menu">
						<header class="major">
							<h2>Menú</h2>
						</header>
						<ul>
							<li><a href="inicio.php">Padrón de beneficiarios</a></li>	
							<li><a href="Entregas.php">UIENNAVD</a></li>
							<li><a href="logout.php">Cerrar sesión</a></li>
						</ul>
					</nav>	
					<section>
						<header class="major">
							<h4>Sistema para el Desarrollo Integral de la Familia Hidalgo </h4>
						</header>
						<p></p>						
					</section>
					<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; Sistema DIF Hidalgo </p>
					</footer>
				</div>
			</div><!--cierre de menu-->
		</div>  <!--cierre de wrapper-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>