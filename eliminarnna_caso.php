<?php 
	
	session_start();
	require 'conexion.php';
	$idDEPTO = $_SESSION['id'];
	$idnna=$_GET['idnna'];
	$idC=$_GET['idC'];
	
	if(!empty($_POST))
	{

	$query2="DELETE from nna_caso where id_caso='$idC' and id_nna='$idnna'";
	$resultado=$mysqli->query($query2);
	header("Location: perfil_caso.php?id=$idC");
	}
	
?>

<html>
	<html lang="es-ES" class="no-js">
	<head>
		<title>Eliminar</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />

	</head>
	
	<body>
		 <div class="page-container">			
			<h1>¿Seguro que desea dar de baja al NNA?</h1>				
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Eliminar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_caso.php?id=<?php echo $idC; ?>'" >
						</ul>
					</div>
				</form>				
		</div>
	<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		