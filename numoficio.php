<?php 
	
	session_start();
	require 'conexion.php';
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

	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;
	$ssl="SELECT responsable from departamentos where id='$idDEPTO'";
	$essl=$mysqli->query($ssl);
	while ($row=$essl->fetch_assoc()) {
		$nrespo=$row['responsable'];
	}
	$fecha=date("j/n/Y");
	
	if(!empty($_POST['registrar'])){
		$fechaI = date("Y-m-d H:i:s", time());
		$dest=  mysqli_real_escape_string($mysqli,$_POST['dest']);
		$asunto= mysqli_real_escape_string($mysqli,$_POST['asunto']);

		$ins="CALL nuevo_oficio($idDEPTO, '$dest', '$asunto', '$fechaI');";
		$eins=$mysqli->query($ins);
		if ($eins>0) {
			header("Location:numoficio.php");
		}else {
			echo $ins;
		}
		
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
						<div class="inner"><br> <br> 	
		<div class="uniform row">
			
			<div class="12u 12u$(xsmall)">
				<div class="box">
					<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
					<h2>Numero de oficio</h2>
					<div class="uniform row">								
						<div class="5u 12u$(xsmall)">Responsable
							<input id="respo" name="respo" type="text" value="<?php echo $nrespo; ?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
						</div>	
						<div class="5u 12u$(xsmall)">Destinatario
							<input id="dest" name="dest" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
						</div>
						<div class="2u 12u$(xsmall)">Fecha
							<input value="<?php echo $fecha; ?>" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
						</div>	
						<div class="12u 12u$(xsmall)">Asunto
							<textarea name="asunto" rows="4" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
						</div>	
						<div class="12u 12u$(xsmall)">
							<input class="button special fit" name="registrar" type="submit" value="Guardar" >
						</div>
					</div>
					</form>
				</div>
			</div>
			<div class="12u 12u$(xsmall)">
				<div class="table-wrapper">
					<section id="search" class="alt">
						<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
							<input type="search" style="text-transform:uppercase;" name="palabra" id="query" placeholder="BUSCAR..." />
						</form>
					</section>
					<table class="alt">
						
							<thead>
								<tr>
									<th>Folio</th>
									<th>Responsable</th>
									<th>Destinatario</th>
									<th>Asunto</th>
									<th>Fecha</th>
									
								</tr>
							</thead>
							<tbody>
				<?php	@$buscar = $_POST["palabra"];
					if (empty($buscar)) {
						$llenartabla="SELECT oficios.id, oficios.num_identificador, departamentos.responsable, oficios.destinatario, oficios.asunto, date_format(oficios.fecha, '%d/%m/%Y') as fecha from oficios left join departamentos on oficios.respo =  departamentos.id where   oficios.activo='1' order by oficios.id desc limit 20";
	
					}else {
						$llenartabla="SELECT oficios.id, oficios.num_identificador, departamentos.responsable, oficios.destinatario, oficios.asunto, date_format(oficios.fecha, '%d/%m/%Y') as fecha from oficios left join departamentos on oficios.respo =  departamentos.id where  oficios.activo='1' and (oficios.num_identificador like '%$buscar%' or departamentos.responsable like '%$buscar%' or oficios.destinatario like '%$buscar%' or oficios.asunto like '%$buscar%' or oficios.fecha like '%$buscar%') order by oficios.id desc";
					}
					$ellenar=$mysqli->query($llenartabla);
					 while ($row=$ellenar->fetch_assoc()) { ?>
								<tr>
									<td><?php echo $row['num_identificador']; ?></td>	
									<td><?php echo $row['responsable']; ?></td>	
									<td><?php echo $row['destinatario']; ?></td>	
									<td><?php echo $row['asunto']; ?></td>	
									<td><?php echo $row['fecha']; ?></td>
									<?php  if ($_SESSION['departamento']==16) { ?>
										<td>
										<input type="image" src="images/editar.png" width="35" height="35" onclick="location='editar_numOfi.php?id=<?php echo $row['id']; ?>'">
										<input type="image" src="images/eliminar.png" width="35" height="35" onclick="location='eliminar_numOfi.php?id=<?php echo $row['id']; ?>'"></td>
									<?php } ?>
									
								</tr>
								<?php } ?>
							</tbody>
					</table>
				</div>
			</div>
		</div>

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

	</body>
</html>
