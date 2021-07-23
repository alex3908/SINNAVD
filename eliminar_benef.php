<?php 
	
	session_start();
	require 'conexion.php';
	$idDEPTO = $_SESSION['id'];
	$idBenef = $_SESSION['idBenef'];
	
	
	if(!empty($_POST))
	{

	$query2="DELETE from benef_unidad where id='$idBenef'";
	$resultado2=$mysqli->query($query2);
	
	/*$query3="DELETE from visitas_unidad where id_benef='$idBenef'";
	$resultado3=$mysqli->query($query3);*/
	header("Location: lista_unidad.php");

	}
	
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
			<h1>¿Seguro que desea eliminar este usuario?</h1>				
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="eliminar" type="submit" value="Eliminar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onClick="location='perfil_beneficiarios.php?id=<?php echo $idBenef; ?>'">
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