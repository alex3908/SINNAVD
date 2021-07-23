<?php
ob_start();	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$sql = "SELECT id, responsable FROM departamentos WHERE id= '$idDEPTO'";
	
	
	$result=$mysqli->query($sql);
	
	$row = $result->fetch_assoc();
	
$idEditar= $_GET['id'];

 $consulta= "SELECT departamentos.responsable, departamentos.telefono, departamentos.extencion, departamentos.id_depto, depto.departamento, departamentos.id_personal, personal.personal, departamentos.tel_part, departamentos.fecha_nac, departamentos.rfc, departamentos.curp, departamentos.num_empleado FROM departamentos, depto, personal WHERE departamentos.id=$idEditar && departamentos.id_depto=depto.id and departamentos.id_personal=personal.id";
  
 	$rcon=$mysqli->query($consulta);
 	$rcon2=$mysqli->query($consulta);

	$sql = "SELECT id, departamento FROM depto";
	$resultlista=$mysqli->query($sql);
	$sql2= "SELECT id, personal FROM personal";
	$resultlista2=$mysqli->query($sql2);
	
	$bandera = false;
	
	if(!empty($_POST)){

		$responsable = mysqli_real_escape_string($mysqli,$_POST['responsable']);		
		$telefono = mysqli_real_escape_string($mysqli,$_POST['telefono']);
		$extencion = mysqli_real_escape_string($mysqli,$_POST['extencion']);
		$fecha_nac = mysqli_real_escape_string($mysqli,$_POST['fecha_nac']);
		$tel_part = mysqli_real_escape_string($mysqli,$_POST['tel_part']);
		$rfc = mysqli_real_escape_string($mysqli,$_POST['rfc']);
		$curp = mysqli_real_escape_string($mysqli,$_POST['curp']);
		$num_em = mysqli_real_escape_string($mysqli,$_POST['num_em']);		
		$tipo_depto = $_POST['tipo_depto'];
		$tipo_personal = $_POST['tipo_personal'];
		$error = '';				
			
			$sqlUsuario = "UPDATE departamentos SET responsable='$responsable', telefono='$telefono', extencion='$extencion', fecha_nac='$fecha_nac', tel_part='$tel_part', rfc='$rfc', curp='$curp', num_empleado='$num_em', id_depto='$tipo_depto', id_personal='$tipo_personal' WHERE id='$idEditar'";
			$resultUsuario = $mysqli->query($sqlUsuario);
			
			if($resultUsuario>0)
			$bandera = true;
			else
			$error = "Error al Modificar";			
		}	
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Editar</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />		
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">
				<!-- Main -->
					<div id="main">
						<div class="inner"><br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div> <br>	
						<div class="box">
						<h1>Editar</h1>
				<form id="registro" enctype="multipart/form-data" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
				<?php while ($row=$rcon->fetch_assoc()) { ?>
					
				
		<div class="row uniform">
			<div class="5u 12u$(xsmall)">
			<input id="responsable" name="responsable" type="text" value="<?php echo $row['responsable']; ?>" placeholder="Responsable">
			</div>			
			<div class="3u 12u$(xsmall)">
			<input id="telefono" name="telefono" type="text" value="<?php echo $row['telefono']; ?>" placeholder="Telefono">
			</div>
			<div class="2u 12u$(xsmall)">
			<input id="extencion" name="extencion" type="text" value="<?php echo $row['extencion']; ?>" placeholder="Ext">
			</div>
			<div class="2u 12u$(xsmall)">
			<input id="fecha_nac" name="fecha_nac" type="text" value="<?php echo $row['fecha_nac']; ?>" placeholder="Fecha de nacimiento">
			</div>
			<div class="3u 12u$(xsmall)">
			<input id="tel_part" name="tel_part" type="text" value="<?php echo $row['tel_part']; ?>" placeholder="Telefono particular">
			</div>			
			<div class="3u 12u$(xsmall)">
			<input id="rfc" name="rfc" type="text" value="<?php echo $row['rfc']; ?>" placeholder="RFC">
			</div>
			<div class="4u 12u$(xsmall)">
			<input id="curp" name="curp" type="text" value="<?php echo $row['curp']; ?>" placeholder="CURP">
			</div>
			<div class="2u 12u$(xsmall)">
			<input id="num_em" name="num_em" type="text" value="<?php echo $row['num_empleado']; ?>" placeholder="Numero de empleado">
			</div>
			<div class="6u 12u$(xsmall)">
				<div class="select-wrapper">
				<select id="tipo_depto" name="tipo_depto" >
					<option value="<?php echo $row['id_depto']; ?>"><?php echo $row['departamento'];?></option><?php } ?>
					<?php while($row = $resultlista->fetch_assoc()){ ?>
						<option value="<?php echo $row['id']; ?>"><?php echo $row['departamento']; ?></option>
					<?php }?>
				</select>
				</div>
			</div>
			<div class="6u 12u$(xsmall)">
				<div class="select-wrapper">
				<select id="tipo_personal" name="tipo_personal" >
				<?php while ($row=$rcon2->fetch_assoc()) { ?>
					<option value="<?php echo $row['id_personal']; ?>"><?php echo $row['personal'];?></option><?php } ?>
					<?php while($row = $resultlista2->fetch_assoc()){ ?>
						<option value="<?php echo $row['id']; ?>"><?php echo $row['personal']; ?></option>
					<?php }?>
				</select>
				</div>
			</div>

			
			
			<div class="12u$">
			<ul class="actions">
			<input class="button special fit" name="Actualizar" type="submit" value="Actualizar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_personal.php?id=<?php echo $idEditar;?>'" >
			</ul></div>
			</div>
		</form>
		</div>
					<?php if($bandera) { 
			header("Location:perfil_personal.php?id=$idEditar");

			?>	<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>

							</div>
							</div>
				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							<nav id="menu">
								<header class="major">
									<h2>Menú</h2>
								</header>
									<ul><li><a href="welcome.php">Inicio</a></li>				
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
							</nav>	
								<section>
									<header class="major">
										<h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
									</header>
									<p></p>
									<ul class="contact">
										<li class="fa-envelope-o"><a href="#">laura.ramirez@hidalgo.gob.mx</a></li>
										<li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
										<li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
										<li class="fa-home">Plaza Juarez #118<br />
										Col. Centro <br> Pachuca Hidalgo</li>
									</ul>
								</section>
							<!-- Footer -->
								<footer id="footer">
									<p class="copyright">&copy; Sistema DIF Hidalgo </p>
								</footer>

						</div>
					</div>
					<!--cierre menu-->

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>