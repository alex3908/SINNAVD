<?php 
	
	session_start();
	require 'conexion.php';
	$idDEPTO = $_SESSION['id'];
	$idCarpeta=$_GET['id'];
	$fecha= date ("j/n/Y");
	
	if(!empty($_POST))
	{

		$forma= $_POST['forma'];

		$update="UPDATE carpeta_inv set tipo_pross='$forma', respo_tipo='$idDEPTO', fecha_tipo='$fecha' where id='$idCarpeta'";
		$eupdate=$mysqli->query($update);
	
	header("Location: perfil_carpeta.php?id=$idCarpeta");
	}
	
?>

<html>
	<html lang="en" class="no-js">
	<head>
		<title>Terminar investigación</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />

	</head>
	
	<body>
		 <div class="page-container">			
						
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="row uniform">
                    <div class="12u$">FORMAS DE TERMINAR LA INVESTIGACIÓN
						<div class="select-wrapper">							
								<select id="forma" name="forma" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >
									<option value="1">archivo temporal</option>
									<option value="2">facultad de abstenerse de investigar</option>
									<option value="3">no ejercicio de la accion</option>
									<option value="4">casos en los que operan los criterios de oportunidad</option>
									<option value="8">incompetencia</option>
								</select>
						</div>
					</div>
                                      
				<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Actualizar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_carpeta.php?id=<?php echo $idCarpeta; ?>'" >
						</ul>
					</div></div>
				</form>				
		</div>
	<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		