<?php 
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	$idDEPTO = $_SESSION['id'];
	$id=$_GET['id'];
	$idCaso=$_SESSION['idCaso'];
	$idM=$_SESSION['idM'];
	
	$seg="SELECT seguimientos.id, seguimientos.area, seguimientos.tipo, mini_catalogo.tseg, date_format(seguimientos.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, seguimientos.seguimiento, seguimientos.respo_reg, departamentos.responsable, date_format(seguimientos.fecha_edicion, '%d/%m/%Y %H:%i:%s') as fecha_edicion
	 from seguimientos, mini_catalogo, departamentos where departamentos.id=seguimientos.respo_reg and seguimientos.id='$id' and mini_catalogo.id=seguimientos.tipo";
	$eseg=$mysqli->query($seg);

	if(!empty($_POST))
	{
	$seguimiento = mysqli_real_escape_string($mysqli,$_POST['seguimiento']);
	$area = $_POST['area'];
	$tipo = $_POST['tipo'];
	$time= time();
	$fechaEdicion= date("Y-m-d H:i:s", $time);
	$query2="UPDATE seguimientos set area='$area', tipo='$tipo', seguimiento='$seguimiento', fecha_edicion='$fechaEdicion' where id='$id'";
	$resultado=$mysqli->query($query2);
	header("Location: ag_comment.php?id=$idM&idCaso=$idCaso");
	}
	
?>

<html>
	<html lang="en" class="no-js">
	<head>
		<title>Editar seguimiento</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />

	</head>
	
	<body>
		<div id="wrapper">	
		<div id="main">			
		<div class="inner">
			<h3>Editar seguimiento</h3>	
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
				<div class="row uniform">
				<?php while ($row=$eseg->fetch_assoc()) { ?>
					<br>	
					<div class="5u 12u$(small)">Area:							
							<select id="area" name="area">
								<option value="<?php echo $row['area']; ?>"><?php echo $row['area']; ?></option>
								<option value="PSICOLOGIA">PSICOLOGIA</option>
								<option value="TRABAJO SOCIAL">TRABAJO SOCIAL</option>
								<option value="JURIDICO">JURIDICO</option>
							</select>	
						</div>	
						<div class="7u 12u$(small)">Tipo:					
								<select id="tipo" name="tipo" >
									<option value="<?php echo $row['tipo']; ?>"><?php echo $row['tseg']; ?></option>
									<?php $s="SELECT id, tseg from mini_catalogo";
											$es=$mysqli->query($s); 
											while ($row1=$es->fetch_assoc()) { ?>				
									<option value="<?php echo $row1['id']; ?>"><?php echo $row1['tseg']; } ?></option>
								</select>	
						</div>	
					</div>
						<div class="row uniform">				
						<div class="12u 12u$(small)">Seguimiento:
							<textarea name="seguimiento" rows="3" maxlength="800" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required><?php echo $row['seguimiento'];?></textarea>
						</div>
						</div>
						<div class="row uniform">
						<div class="3u 12u$(small)">Fecha:
							<input type="text" name="fecha" value="<?php echo $row['fecha'];?>" disabled>				
						</div>
						
						<div class="6u 12u$(small)">Responsable:
							<input type="text" name="" value="<?php echo $row['responsable'];?>" disabled>
						</div>
						<div class="3u 12u$(small)">Ultima edicion:
							<input type="text" name="" value="<?php echo $row['fecha_edicion'];?>" disabled>
						</div>
						</div>
						<?php } ?>					
						<br>
						<input type="submit" name="actualizar" value="ACTUALIZAR">
						<input type="button" name="cancelar" value="cancelar" onclick="location='ag_comment.php?id=<?php echo $idM; ?>&idCaso=<?php echo $idCaso; ?>'">
				</form></div>				
		</div>
		</div>
		</div>
	<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		