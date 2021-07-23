<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	
	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}
	$idRespo=$_GET['id'];
	$idReporte=$_GET['idRep'];
	$qResponsable="SELECT nombre from redes_familiares where id=$idRespo";
	$rResponsable=$mysqli->query($qResponsable);
	while ($rwRes=$rResponsable->fetch_assoc()) {
		$nombreRes=$rwRes['nombre'];
	}
	if(!empty($_POST['eliminar']))
	{
		$fecha= date("Y-m-d H:i:s", time());
		$qDesactivar="UPDATE redes_familiares set activo=0, respo_inactivo=$idDEPTO, fecha_inactivo='$fecha' where id=$idRespo";
		$rDesactivar=$mysqli->query($qDesactivar);
		if($rDesactivar){
			echo "<script>
				alert('Eliminación correcta');
				window.location= 'reg_redF_acercats.php?id=$idReporte'
				</script>";
		} else echo "No se pudo eliminar: ".$qDesactivar;
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
			<h3>¿SEGURO QUE DESEA ELIMINAR AL RESPONSABLE <?=$nombreRes?>?</h3>				
			<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	
				<div class="12u$">
					<ul class="actions">
						<input class="button special fit" name="eliminar" type="submit" value="Eliminar" >
						<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='reg_redF_acercats.php?id=<?= $idReporte ?>'">
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