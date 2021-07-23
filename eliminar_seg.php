<?php 
	
	session_start();
	require 'conexion.php';
	$idDEPTO = $_SESSION['id'];
	$id=$_GET['id'];
	$idCaso=$_SESSION['idCaso'];
	$idM=$_SESSION['idM'];
	$fecha=date("Y-m-d");
	if(!empty($_POST))
	{

	$query2="UPDATE seguimientos set activo='0', fecha_delete='$fecha' where id='$id'";
	$resultado=$mysqli->query($query2);
	header("Location: ag_comment.php?id=$idM&idCaso=$idCaso");
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
			<h1>¿Seguro que desea eliminar el seguimiento?</h1>				
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Eliminar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='ag_comment.php?id=<?php echo $idM; ?>&idCaso=<?php echo $idCaso; ?>'" >
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