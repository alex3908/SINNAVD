<?php 
	
	session_start();
	require 'conexion.php';
	$idDEPTO = $_SESSION['id'];
	$id=$_GET['id'];
	


	$query2="DELETE from historial where id='$id'";
	$resultado=$mysqli->query($query2);

	
?>

<html>
	<html lang="en" class="no-js">
	<head>
		<title>Eliminar</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />

	</head>
	
	<body>
		 <div class="page-container">
			<?php 
				if($resultado>0){
				?>
				
				<h1>La visita fue eliminada con exito.</h1>
				
				<?php 	}else{ ?>
				
				<h1>Error al Eliminar visita</h1>
				
			<?php	} ?>
			<p></p>		
				<div class="12u$">
			<input type="button" name="aceptar" value="Aceptar" onclick="location='welcome.php'">
			</div>
		</div>
	<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		