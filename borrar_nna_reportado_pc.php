<?php 
	
	session_start();
	require 'conexion.php';
    $idDEPTO = $_SESSION['id'];
	$idNNA = $_GET['id'];

    $qNna="SELECT nombre, id_posible_caso from nna_reportados where id='$idNNA'";
    $rNna=$mysqli->query($qNna);
    while ($row=$rNna->fetch_assoc()) {
		$nombre=$row['nombre'];
        $idPosCaso=$row['id_posible_caso'];
	}	
	
	$qNnaReportes="SELECT id from nna_reportados where id_posible_caso='$idPosCaso' and activo=1";
	$rNnaReportes=$mysqli->query($qNnaReportes);
	$numNNA=$rNnaReportes->num_rows;

	$qfolio="SELECT folio from posible_caso where id='$idPosCaso'";
	$rfolio=$mysqli->query($qfolio);
    while ($rowF=$rfolio->fetch_assoc()) {
		$folio=$rowF['folio'];
	}	
	
	if(!empty($_POST))
	{
	$qEliminarNna="UPDATE nna_reportados SET activo=0 where id='$idNNA'";
    $rEliminarNna=$mysqli->query($qEliminarNna);
    
    header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosCaso");
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
		 <?php if($numNNA>1) { ?>		
			<h3>¿Seguro que desea eliminar al nna <?php echo $nombre." del reporte ". $folio?> </h3>				
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Eliminar" >
						</ul>
					</div>
				</form>	
		 <?php } else { ?>
			<h3>No se puede eliminar al NNA <?php echo $nombre." del reporte ". $folio?> </h3>
		 <?php } ?>
				<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?php echo $idPosCaso?>'" >			
		</div>
	<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		