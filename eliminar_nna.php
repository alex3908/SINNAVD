<?php 
	
	session_start();
	require 'conexion.php';
	$idDEPTO = $_SESSION['id'];
	$idNNA = $_SESSION['idNNA'];


	
	
	if(!empty($_POST))
	{

	$query2="UPDATE nna SET activo=0 where id='$idNNA'";
	$resultado2=$mysqli->query($query2);

	/*$query3="DELETE from nna_caso where id_nna='$idNNA' and estado='NE'";
	$resultado3=$mysqli->query($query3);

	$query4="DELETE from victimas_c_inv where id_nna='$idNNA' and estado='NE'";
	$resultado4=$mysqli->query($query4);

	$query5="DELETE from nna_exposito where nna_n='$idNNA'";
	$resultado5=$mysqli->query($query5);*/
	header("Location: lista_nna.php");
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
			<h1>¿Seguro que desea eliminar al nna?</h1>				
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Eliminar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_nna.php?id=<?=$idNNA?>'" >
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