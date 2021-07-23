<?php
	ob_start();
	session_start();
	require 'conexion.php';
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$sql = "SELECT id, responsable, id_depto, id_personal from departamentos where id='$idDEPTO'";
	$result=$mysqli->query($sql);
	$result2=$mysqli->query($sql);

	while($row=$result2->fetch_assoc()){
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}

	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
	from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
	left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
	left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
	where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
	$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;
	//Establecemos zona horaria por defecto
    date_default_timezone_set('America/Mexico_City');
    //preguntamos la zona horaria
    $zonahoraria = date_default_timezone_get();   
	$idUsuario = $_GET['id'];

	$sql = "SELECT id, departamento FROM depto WHERE id!=16 && id!=7 and id!=12";
	$result=$mysqli->query($sql);

	$nombreU="SELECT nombre, apellido_p, apellido_m FROM usuarios WHERE id='$idUsuario'";
	$r=$mysqli->query($nombreU);
	
	$bandera = false;
	
	if(!empty($_POST))
	{
		$fecha= date("Y-m-d H:i:s", time());		
		$asunto =$_POST['asunto'];				
		$responsable = $_POST['depto'];		
		if ($responsable==0) {
			$error = "Error al Registrar";
		}else{
			$sqlUsuario = "INSERT INTO historial
			(id_usuario, id_departamento, fecha_ingreso, responsable, asunto, respo_reg) 
			VALUES
			('$idUsuario', '$responsable', '$fecha', '0', '$asunto','$idDEPTO')";
			$resultUsuario = $mysqli->query($sqlUsuario);
			if($resultUsuario>0)
				$bandera = true;
			else
				$error = "Error al Registrar";
		}
	}
	
?>


<!DOCTYPE HTML>
<html>
	<head>
		<title>Visitas</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />	
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />	
	</head>
	<body>
		<div id="wrapper">
			<div id="main">
				<div class="inner"><br> <br> 
					<div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div><br>
					<div class="box">
						<h1>Registrar visita</h1>
						<form id="registro" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
							<div class="row uniform">
								<div class="12u$">
									<?php while($row = $r->fetch_assoc()){ ?>
										<label for="nombre">Nombre del usuario</label>
										<input id="nombre" name="nombre" type="text" value="<?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m']; ?>" disabled >
									<?php }?>
								</div>
								<?php if ($idPersonal==5) { 
									$valida="SELECT departamentos.id_depto, depto.departamento from depto, departamentos where departamentos.id='$idDEPTO' and departamentos.id_personal='5' and departamentos.id_depto=depto.id";
									$evalida=$mysqli->query($valida); ?>
									<div class="6u 12u$(xsmall)">
										<label>Departamento</label>
										<div class="select-wrapper">
											<select id="responsable" name="depto" required="true">	
												<option value="">Seleccione</option>
												<?php while($row = $evalida->fetch_assoc()){ ?>
													<option value="<?php echo $row['id_depto']; ?>"><?php echo $row['departamento']; ?></option>
												<?php }?>
											</select>
										</div>
									</div>
								<?php } else { ?>
									<div class="6u 12u$(xsmall)">
										<label>Departamento</label>
										<div class="select-wrapper">
											<select id="responsable" name="depto" required="true">
												<option value="">Seleccione</option>
												<?php while($row = $result->fetch_assoc()){ ?>
													<option value="<?php echo $row['id']; ?>"><?php echo $row['departamento']; ?></option>
												<?php }?>
											</select>
										</div>
									</div>
								<?php } ?>
								<div class="6u 12u$(xsmall)">
									<label>Tipo de visita</label>
									<div class="select-wrapper">
										<select id="asunto" name="asunto" required="true">
											<option value="">Seleccione</option>
											<option value="INICIAL">INICIAL</option>
											<option value="SUBSECUENTE">SUBSECUENTE</option>
										</select>
									</div>
								</div>
								<label>Fecha y hora de visita:</label><br>
								<div class="date">
								    <span id="weekDay" class="weekDay"></span>, 
								    <span id="day" class="day"></span> de
								    <span id="month" class="month"></span> del
								    <span id="year" class="year"></span>
								</div>
								<div class="clock">
								    <span id="hours" class="hours"></span> :
								    <span id="minutes" class="minutes"></span> :
								    <span id="seconds" class="seconds"></span>
								</div>
							 	
								<div class="12u$">
									<ul class="actions">
										<input class="button special fit" name="registar" type="submit" value="Registrar" >
										<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_usuarios.php?id=<?=$idUsuario?>'" >
									</ul>
								</div>
							</div>
						</form>
					</div>
					<?php if($bandera) {
						echo "<script>
						alert('Visita registrada');
						window.location= 'welcome.php'
					</script>";
					} else { ?>
						<br />
						<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
					<?php } ?>
				</div>
			</div>
			<div id="sidebar">
				<div class="inner">
					<?php $_SESSION['spcargo'] = $idPersonal; ?>
					<?php if($idPersonal==6) { //UIENNAVD?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Menú</h2>
							</header>
							<ul>
								<li><a href="welcome.php">Inicio</a></li>	
								<li><a href="lista_unidad.php">UIENNAVD</a></li>
								<li><a href="logout.php">Cerrar sesión</a></li>
							</ul>
						</nav>	
					<?php }else if($idPersonal==5) { //Subprocu ?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Menú</h2>
							</header>
							<ul>
								<li><a href="welcome.php">Inicio</a></li>	
								<li><a href="lista_personal.php">Personal</a></li>
								<li><a href="lista_usuarios.php">Usuarios</a></li>			
								<li><a href="lista_reportes_nueva.php?estRep=0">Reportes VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a></li>
								<li><a href="lista_casos.php">Casos</a></li>
								<li><a href="lista_nna.php">NNA</a></li>
								<li><span class="opener">Carpetas</span>
									<ul>
										<li><a href="lista_carpeta.php">Carpetas</a></li>
										<li><a href="lista_imputados.php">Imputados</a></li>			
									</ul>
								</li>		
								<li><a href="cas.php">CAS</a></li>							
								<li><span class="opener">Pendientes</span>
									<ul>
										<li><a href="carpetas_sasignar.php">Carpetas por asignar</a></li>
										<li><a href="nna_pendientes.php">NNA sin curp</a></li>
										<li><a href="visitas_fecha.php">Buscador</a></li>			
									</ul>
								</li>
								<li><a href="lista_documentos.php">Descarga de oficios</a></li>
								<li><a href="alta_medida.php">Catalogo de medidas</a></li>
								<li><a href="logout.php">Cerrar sesión</a></li>
							</ul>
						</nav>	
					<?php }else { ?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Menú</h2>
							</header>
							<ul>
								<li><a href="welcome.php">Inicio</a></li>	
								<li><a href="lista_personal.php">Personal</a></li>
								<li><a href="lista_usuarios.php">Usuarios</a></li>
								<?php if ($_SESSION['departamento']==7) { ?>
									<li><a href="canalizar.php">Canalizar visita</a></li>	
								<?php } ?>												
								<li><a href="lista_reportes_nueva.php?estRep=0">Reportes VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a></li>
								<li><a href="lista_casos.php">Casos</a></li>
								<li><a href="lista_nna.php">NNA</a></li>		
								<li><a href="reg_reporte_migrantes.php">Migrantes</a></li>
								<li><span class="opener">Carpetas</span>
									<ul>
										<li><a href="lista_carpeta.php">Carpetas</a></li>
										<li><a href="lista_imputados.php">Imputados</a></li>			
									</ul>
								</li>
								<li><a href="cas.php">CAS</a></li>
								<li><span class="opener">UIENNAVD</span>
									<ul>
										<li><a href="lista_unidad.php">Beneficiarios</a></li>
										<li><a href="visitas_gen_unidad.php">Historial de visitas</a></li>
									</ul>
								</li>						
								<?php if (($_SESSION['departamento']==16) or ($_SESSION['departamento']==7)) { ?>
									<li><span class="opener">Visitas</span>
										<ul>
											<li><a href="editar_visitadepto.php">Editar departamento</a></li>
											<li><a href="editar_visitarespo.php">Editar responsable</a></li>
											<li><a href="eliminar_visita.php">Eliminar</a></li>
										</ul>
									</li>
								<?php } ?>									
								<li><span class="opener">Pendientes</span>
									<ul>
										<li><a href="carpetas_sasignar.php">Carpetas por asignar</a></li>
										<li><a href="nna_pendientes.php">NNA sin curp</a></li>			
										<li><a href="visitas_fecha.php">Buscador</a></li>				
									</ul>
								</li>									
								<li>
									<span class="opener">Adopciones</span>
									<ul>
										<li><a href="reg_expAdop.php">Generar expediente</a></li>
										<li><a href="">Expedientes</a></li>
									</ul>
								</li>
								<?php if ($_SESSION['departamento']==16 or $_SESSION['departamento']==14) {  ?>
									<li><a href="reg_actccpi.php">CCPI</a></li>
								<?php } ?>
								<li><a href="numoficio.php">Numero de oficio</a></li>
								 
								<li><a href="lista_documentos.php">Descarga de oficios</a></li>
								<li><a href="alta_medida.php">Catalogo de medidas</a></li>
								<li><a href="logout.php">Cerrar sesión</a></li>
							</ul>
						</nav>	
					<?php }?>
					<section>
						<header class="major">
							<h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
						</header>
						<p></p>
						<ul class="contact">
							<li class="fa-envelope-o">laura.ramirez@hidalgo.gob.mx</li>
							<li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
							<li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
							<li class="fa-phone"><a href="directorio.php">Directorio interno</a></li>
							<li class="fa-home">Plaza Juarez #118<br />
								Col. Centro <br> Pachuca Hidalgo</li>
						</ul>
					</section>
					<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; Sistema DIF Hidalgo </p>
					</footer>
				</div>
			</div><!--cierre de menu-->
		</div>

		<!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
		<script src="clock.js"></script>

	</body>
</html>