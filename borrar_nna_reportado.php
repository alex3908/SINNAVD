<?php 
	
	session_start();
	require 'conexion.php';
    $idDEPTO = $_SESSION['id'];
	$idNNA = $_GET['idNna'];
	$folio= $_GET['id'];

    $qNna="SELECT nombre from nna_reportados where id='$idNNA'";
    $rNna=$mysqli->query($qNna);
    while ($row=$rNna->fetch_assoc()) {
		$nombre=$row['nombre'];
    }	
	
	if(!empty($_POST))
	{
	$qEliminarNna="DELETE from nna_reportados where id='$idNNA'";
    $rEliminarNna=$mysqli->query($qEliminarNna);
    
    header("Location: reg_nna_reportados.php?idPosibleCaso=$folio");
	}
	
?>

<html>
	<html lang="es-ES" class="no-js">
	<head>
		<title>Eliminar NNA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />

	</head>
	
	<body>
		 <div class="page-container">			
			<h3>¿Seguro que desea eliminar al nna <?php echo $nombre." del reporte ". $folio?> </h3>				
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Eliminar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='reg_nna_reportados.php?idPosibleCaso=<?php echo $folio?>'" >
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