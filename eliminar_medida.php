<?php 
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	$idDEPTO = $_SESSION['id'];
	$id=$_GET['id'];
	$idCaso=$_GET['idCaso'];
	
	$qMedidas="SELECT id, id_plan from cuadro_guia where id_caso='$idCaso' and activo=1";
	$rMedidas=$mysqli->query($qMedidas);
	$numMedidas=$rMedidas->num_rows;
	$qacercamientos="SELECT id FROM seguimientos where id_med='$id' and activo=1";
	$racermientos=$mysqli->query($qacercamientos);
	$numAcercamientos=$racermientos->num_rows;

	if(!empty($_POST))
	{
	$time= time();
	$fecha= date("Y-m-d H:i:s", $time);
		if($numMedidas==1){
			$qDesactivarPlan="UPDATE planes_de_restitucion set activo='0', fecha_delete='$fecha', 
			responsable_delete='$idDEPTO' where id_caso='$idCaso'";
			$rDesactivarPlan=$mysqli->query($qDesactivarPlan);
		}
	$query2="UPDATE cuadro_guia set activo='0', fechaDelete='$fecha', respDelete='$idDEPTO' where id='$id'"; //desactiva la medida y registra cuando y quien la elimino
	$resultado=$mysqli->query($query2);
	header("Location: cuadro_guia.php?id=$idCaso");
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
		 <?php if($numAcercamientos==0){ 
			 if($numMedidas==1){?>
				<p><b>¡Esta la única medida de este cuadro guia! Si la borra, también se eliminará el cuadro guía</b></p>
				<h1>¿Seguro que desea eliminarla?</h1>				
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Eliminar" >
			<?php } else {?>		
			<h1>¿Seguro que desea eliminar la medida de protección?</h1>				
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Eliminar" >
		 <?php } }else {?>
		 <h1>Esta medida tiene ya seguimientos. Primero elimine los seguimientos</h1>				
					
		 <?php }?>
		 <div class="12u 12u$(small)">
						<ul class="actions">		 
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='cuadro_guia.php?id=<?php echo $idCaso; ?>'" >
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