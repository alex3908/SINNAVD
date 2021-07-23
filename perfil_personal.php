<?php	
	session_start();
	require 'conexion.php';	
	if(!isset($_SESSION["id"])){ //no mover. evalua si la variable esta definida
		header("Location: index.php"); //direeciona al welcome 
	}
	
	$idDEPTO = $_SESSION['id'];	//recupera el id del usuario que inicia la sesion
	$sql = "SELECT id, id_depto, id_personal FROM departamentos WHERE id= '$idDEPTO'"; //consulta el departamento y el perfil del usuario	
	$result=$mysqli->query($sql);//ejecuta la consulta
	
	while ($row=$result->fetch_assoc()) {
		$idD=$row['id_depto'];		
	} //llave que cierra el while 

	$idUsuario = $_GET['id'];	
	$query="SELECT departamentos.id, departamentos.responsable, depto.departamento, departamentos.telefono, departamentos.fecha_nac, departamentos.rfc, departamentos.curp, departamentos.num_empleado, departamentos.extencion, personal.personal, departamentos.id_personal, departamentos.activo FROM departamentos, depto, personal WHERE depto.id=departamentos.id_depto && personal.id=departamentos.id_personal && departamentos.id='$idUsuario'";
	$resultado=$mysqli->query($query);

	if(!empty($_POST['bloc'])){
		$pbloc="UPDATE departamentos set activo=0 where id='$idUsuario'";
		$epbloc=$mysqli->query($pbloc);
		header("Location: perfil_personal.php?id=$idUsuario");
	}
	if(!empty($_POST['act'])){
		$pbloc="UPDATE departamentos set activo=1 where id='$idUsuario'";
		$epbloc=$mysqli->query($pbloc);
		header("Location: perfil_personal.php?id=$idUsuario");
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Perfil</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />	
	</head>
	<body>
		<!-- Wrapper -->
			<div id="wrapper">
				<!-- Main -->
					<div id="main">					
					<?php while($row=$resultado->fetch_assoc()){ ?>					
						<div class="inner"><br> <br> 
							<div class="box alt" align="center">
								<div class="row 10% uniform">
									<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
									<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
									<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
								</div>
							</div>								
						<h2><?php echo $row['responsable'];  ?> </h2>
							<div class="uniform row">
								<div class="2u 12u$(xsmall)">
									<h4>Numero de empleado: </h4><?php echo $row['num_empleado']; ?>
								</div>
								<div class="3u 12u$(xsmall)">
									<h4>Fecha de nacimiento: </h4><?php echo $row['fecha_nac']; ?>
								</div>								
								<div class="3u 12u$(xsmall)">
									<h4>RFC: </h4><?php echo $row['rfc']; ?>
								</div>
								<div class="4u 12u$(xsmall)">
									<h4>Curp: </h4><?php echo $row['curp']; ?>
								</div>
							</div>
							<div class="uniform row">
								<div class="2u 12u$(xsmall)">
									<h4>Teléfono: </h4><?php echo $row['telefono']." ext. ".$row['extencion']; ?>
								</div>
								<div class="5u 12u$(xsmall)">
									<h4>Departamento: </h4><?php echo $row['departamento']; ?>
								</div>
								<div class="3u 12u$(xsmall)">
									<h4>Cargo: </h4><?php echo $row['personal']; ?>
								</div>
								<div class="2u 12u$(xsmall)">
									<h4>Estado: </h4><?php $est=$row['activo']; 
									if ($est=='1') {
									 	echo "ACTIVO";
									 }else {
									 	echo "BLOQUEADO";
									 } ?>
								</div>
							</div>
							<ul class="alt"><li></li><li></li></ul>
						
							<div class="uniform row">
							<div class="6u 12u$(xsmall)">
							<?php 
								$med="SELECT id from cuadro_guia where id_sp_registro='$idUsuario'";
								$emed=$mysqli->query($med);
								$rowsmed=$emed->num_rows;
								
								$mede="SELECT id from cuadro_guia where id_sp_registro='$idUsuario' and estado=1";
								$emede=$mysqli->query($mede);
								$rowsmede=$emede->num_rows; 
										
								$sql3="SELECT id from seguimientos where respo_reg='$idUsuario'";
								$esql3=$mysqli->query($sql3);
								$rows3=$esql3->num_rows;

								$sql4="SELECT id from reportes_vd where respo_reg='$idUsuario'";
								$esql4=$mysqli->query($sql4);
								$rows4=$esql4->num_rows;

								$sql5="SELECT id from reportes_vd where asignado='$idUsuario' or asignado_psic='$idUsuario'";
								$esql5=$mysqli->query($sql5);
								$rows5=$esql5->num_rows;

								$sql6="SELECT id from reportes_vd where atendido>1 and (asignado='$idUsuario' or asignado_psic='$idUsuario')";
								$esql6=$mysqli->query($sql6);
								$rows6=$esql6->num_rows;

								$sql7="SELECT id from acercamiento_psic where respo_reg='$idUsuario'";
								$esql7=$mysqli->query($sql7);
								$rows7=$esql7->num_rows;									
							?>
								<ul class="alt">
								<li><strong>1. Medidas decretadas:</strong> <?php echo $rowsmed; ?></li>
								<li><strong>2. Medidas ejecutadas:</strong> <?php echo $rowsmede; ?></li>
								<li><strong>3. Seguimientos:</strong> <?php echo $rows3; ?></li>		
								<li><strong>4. Reportes registrados:</strong> <?php echo $rows4; ?></li>
								<li><strong>5. Reportes asignados:</strong> <?php echo $rows5; ?></li>
								<li><strong>6. Reportes atendidos:</strong> <?php echo $rows6; ?></li>
								<li><strong>7. Acercamientos psic:</strong> <?php echo $rows7; ?></li>
								</ul>								
							</div>
							<div class="6u 12u$(xsmall)">
							<?php
								$sql8="SELECT id from acercamiento_familiar where respo_reg='$idUsuario'";
								$esql8=$mysqli->query($sql8);
								$rows8=$esql8->num_rows;

								$sql9="SELECT id from casos where funcionario_reg='$idUsuario'";
								$esql9=$mysqli->query($sql9);
								$rows9=$esql9->num_rows;

								$sql10="SELECT id from nna where respo_reg='$idUsuario'";
								$esql10=$mysqli->query($sql10);
								$rows10=$esql10->num_rows;

								$sql11="SELECT id from carpeta_inv where respo_reg='$idUsuario'";
								$esql11=$mysqli->query($sql11);
								$rows11=$esql11->num_rows;

								$sql12="SELECT id from carpeta_inv where asignado='$idUsuario'";
								$esql12=$mysqli->query($sql12);
								$rows12=$esql12->num_rows;

								$sql13="SELECT id from historial where responsable='$idUsuario'";
								$esql13=$mysqli->query($sql13);
								$rows13=$esql13->num_rows;

								$sql14="SELECT id from historial where respo_reg='$idUsuario'";
								$esql14=$mysqli->query($sql14);
								$rows14=$esql14->num_rows;
							?>
								<ul class="alt">
								<li><strong>8. Acercamientos ts:</strong> <?php echo $rows8; ?></li>
								<li><strong>9. Casos registrados:</strong> <?php echo $rows9; ?></li>
								<li><strong>10. NNA registrados:</strong> <?php echo $rows10; ?></li>	
								<li><strong>11. Carpetas registradas:</strong> <?php echo $rows11; ?></li>
								<li><strong>12. Carpetas asignadas:</strong> <?php echo $rows12; ?></li>
								<li><strong>13. Visitas de usuarios atendidas:</strong> <?php echo $rows13; ?></li>
								<li><strong>14. Usuarios registrados:</strong> <?php echo $rows14; ?></li>
								</ul>	
								
							</div>							
						</div>
						
						<br>
						
						<?php if ($idD==16) { ?>
							
							<input type="button" name="asignar_curso" class="button fit" value="Editar" onclick="location='editar_personal.php?id=<?php echo $row['id'];?>'">
								
							<input type="button" name="asignar_curso" class="button special fit" value="Eliminar" onclick="location='eliminar_personal.php?id=<?php echo $row['id'];?>'">
							<input type="button" name="asignar_curso" class="button fit" value="Cambiar contraseña" onclick="location='cambiar_password.php?id=<?php echo $row['id'];?>'">
							<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
							<?php if ($est=='1') { ?>
								<input type="submit" name="bloc" class="button special fit" value="bloquear" >
							<?php }else if($est=='0') { ?>
								<input type="submit" name="act" class="button special fit" value="activar" >
							</form>
						<?php } } else {
						}
							} ?>
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