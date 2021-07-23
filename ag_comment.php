<?php	
	session_start();
	require 'conexion.php';
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
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
	$id=$_GET['id'];
	$idCaso=$_GET['idCaso'];
	$_SESSION['idCaso']=$idCaso;
	$_SESSION['idM']=$id;
	$cuadro="SELECT derechos_nna.derecho, cuadro_guia.id, medidas.medida_p, 
	cuadro_guia.marco, cuadro_guia.med_prot, cuadro_guia.beneficiario, 
	cuadro_guia.responsable_med, cuadro_guia.atp_encargada, cuadro_guia.periodicidad, 
	cuadro_guia.estado, cuadro_guia.observaciones, date_format(cuadro_guia.fecha_registro, '%d/%m/%Y') as fecha, cuadro_guia.id_sp_registro, 
	departamentos.responsable from cuadro_guia, derechos_nna, departamentos, medidas 
	where cuadro_guia.id='$id' AND cuadro_guia.id_medida=medidas.id 
	and derechos_nna.id=cuadro_guia.id_derecho and cuadro_guia.id_sp_registro=departamentos.id";
	$ecuadro=$mysqli->query($cuadro);

	$segui="SELECT seguimientos.id, seguimientos.area, seguimientos.tipo, mini_catalogo.tseg, seguimientos.seg_virtual,
	date_format(seguimientos.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, seguimientos.fecha_registro, seguimientos.seguimiento, 
	seguimientos.respo_reg, seguimientos.respo_reg, departamentos.responsable 
	from seguimientos, mini_catalogo, departamentos 
	where departamentos.id=seguimientos.respo_reg 
	and id_med='$id' and mini_catalogo.id=seguimientos.tipo and seguimientos.activo='1'";
	$esegui=$mysqli->query($segui);
	$esegui2=$mysqli->query($segui);

	$qFechasCorte="SELECT fechai, fechac from cortes where bandera=1";
	$rFechasCorte=$mysqli->query($qFechasCorte);
	while ($rowFechas=$rFechasCorte->fetch_assoc()) { 
		$fechaInicio= strtotime($rowFechas['fechai']);
		$fechaCorte= strtotime($rowFechas['fechac']);
	}

	if(!empty($_POST))
	{
		$time= time();
		$fecha= date("Y-m-d H:i:s", $time);
		$seguimiento = mysqli_real_escape_string($mysqli,$_POST['seguimiento']);
		$area=mysqli_real_escape_string($mysqli,$_POST['demo']);
		$tseg=$_POST['act'];
		
		$update="INSERT into seguimientos (area,tipo,seguimiento,id_med,fecha_registro,respo_reg) values ('$area','$tseg','$seguimiento','$id','$fecha','$idDEPTO')";
		$eupdate=$mysqli->query($update);
		
	header("Location: ag_comment.php?id=$id&idCaso=$idCaso");
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
			<div class="4u 12u$(xsmall)">
			<input class="button fit" type="button" name="cancelar" value="regresar" onclick="location='cuadro_guia.php?id=<?php echo $idCaso; ?>'" >
				<div class="box">			
						<?php while ($row=$ecuadro->fetch_assoc()) { ?>
								<strong>Medida: </strong><?php echo $row['med_prot'];?><br>
								<strong>Fecha: </strong><?php echo$fecha=$row['fecha']; ?><br>		<?php $responsable=$row['responsable'];  $id_CG=$row['id']; ?>
												
								<strong>Beneficiario(s): </strong>
									<?php $bene=$row['beneficiario'];
									if (empty($bene)) {
													$nnaa="SELECT nna.nombre, nna.apellido_p, nna.apellido_m from benefmed, nna where benefmed.id_medida='$id_CG' and benefmed.id_nna=nna.id";
													$ennaa=$mysqli->query($nnaa);
													while ($row=$ennaa->fetch_assoc()) {		
																echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];
															echo ', ';
														} 
													
												}else {

														$Cadabene=explode(':', $bene);
														for ($i=0; $i <count($Cadabene) ; $i++) { 
														
														@list($idMorro, $tipoMorro)=explode(' ', $Cadabene[$i]);
														
														if ($tipoMorro=='NE') {
															$sql="SELECT nombre, apellido_p, apellido_m from nna where id='$idMorro'";
															$esql=$mysqli->query($sql);
															while ($row=$esql->fetch_assoc()) {
																echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];
															}
														}else if ($tipoMorro=='E') {
															$sql="SELECT folio, sexo from nna_exposito where id='$idMorro'";
															$esql=$mysqli->query($sql);
															while ($row=$esql->fetch_assoc()) {
																echo $row['folio']." ".$row['sexo'];
															} 
														} echo ', ';
													}	
												}
							} ?>
				</div>
			<div class="box">
				<?php 
					$cg="SELECT id from seguimientos where id_med='$id'";
					$ecg=$mysqli->query($cg);
					$ccg=$ecg->num_rows;
				?>
				 
				<table>
					<thead>
						<td colspan="2"><strong>Contador</strong></td>
						<td>General: <?php echo $ccg; ?></td>
					</thead>
					<thead>
						<tr>
							<th>TS</th>
							<th>PSIC</th>
							<th>JURIDICO</th>
							<th>S/E</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							$cts="SELECT id from seguimientos where area='TRABAJO SOCIAL' and id_med='$id'";
							$ects=$mysqli->query($cts);
							$ccts=$ects->num_rows;

							$cpsic="SELECT id from seguimientos where area='PSICOLOGIA' and id_med='$id'";
							$ecpsic=$mysqli->query($cpsic);
							$ccpsic=$ecpsic->num_rows;

							$cj="SELECT id from seguimientos where area='JURIDICO' and id_med='$id'";
							$ecj=$mysqli->query($cj);
							$ccj=$ecj->num_rows; 

							$cse="SELECT id from seguimientos where area='' and id_med='$id'";
							$ecse=$mysqli->query($cse);
							$ccse=$ecse->num_rows;?>
						<tr>
							<td><?php echo $ccts; ?></td>
							<td><?php echo $ccpsic; ?></td>
							<td><?php echo $ccj; ?></td>
							<td><?php echo $ccse; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			</div>
			<div class="8u 12u$(xsmall)">
				<div class="box">
					<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
					<h2>Seguimiento</h2>
					<div class="uniform row">								
							<div class="4u 12u$(small)">
								<input type="radio" id="demo-priority-low" name="demo" value="PSICOLOGIA" checked>
								<label for="demo-priority-low">PSICOLOGIA</label>
							</div>
							<div class="4u 12u$(small)">
								<input type="radio" id="demo-priority-normal" name="demo" value="TRABAJO SOCIAL">
								<label for="demo-priority-normal">TRABAJO SOCIAL</label>
							</div>
							<div class="4u$ 12u$(small)">
								<input type="radio" id="demo-priority-high" name="demo" value="JURIDICO">
								<label for="demo-priority-high">JURIDICO</label>
							</div>
							<div class="12u 12u$(small)">
										<div class="select-wrapper">
											<select id="act" name="act" required>
												<option value="">Seguimiento...</option>
												<?php $s="SELECT id, tseg from mini_catalogo";
														$es=$mysqli->query($s); 
												while ($row=$es->fetch_assoc()) { ?>				
												<option value="<?php echo $row['id']; ?>"><?php echo $row['tseg']; } ?></option>
												
											</select>
										</div>
									</div>		
							<div class="12u$">
								<textarea name="seguimiento" rows="4" maxlength="800" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
							</div>	
						<br>
							<div class="12u$">
								<input class="button special fit" name="registar" type="submit" value="Guardar" >
							</div>
						</div>
					</div>
				</form>
			</div>
			
			<div class="12u$">				
					<div class="table-wrapper">
						<table class="alt">
						<thead>
							<tr>
								<td colspan="6"><h3>Historial de seguimientos</h3></td>
								
							</tr>
						</thead>
							<thead>
								<tr>
									<th>Fecha</th>
									<th>Area</th>
									<th>Tipo</th>
									<th>Seguimiento</th>
									<th>Responsable</th>
								</tr>
							</thead>						
							<tbody>
							<?php while ($row=$esegui->fetch_assoc()) { 
								$fechaReg=strtotime($row['fecha_registro']);
								?>
								<tr>
									<td><?php echo $row['fecha'];?></td>
									<td><?php echo $row['area']; ?></td>
									<td><?php echo $row['tseg']; ?>
										<?php if($row['area']=='PSICOLOGIA' and $row['id']>17260 and $row['respo_reg']==$idDEPTO) { 
											if($row['seg_virtual']==1) { ?>	
											<br><b>Seguimento virtual</b>
											<?php } else { ?>										
												<br><a href="virtualizar_seguimiento.php?id=<?= $row['id']; ?>">Agregar como virtual  </a>
										<?php } } ?>
									</td>
									<td><?php echo $row['seguimiento'];?></td>
									<td><?php echo $row['responsable'];?></td>
									<?php if (($idDEPTO==$row['respo_reg'] or $idDepartamento==16 and $idPersonal==1) and $fechaInicio<$fechaReg) { ?>
									<td>
									<input type="image" src="images/editar.png" width="35" height="35" onclick="location='editar_seg.php?id=<?php echo $row['id']; ?>'">
									<input type="image" src="images/eliminar.png" width="35" height="35" onclick="location='eliminar_seg.php?id=<?php echo $row['id']; ?>'"></td>
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

	<!-- Sidebar -->
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
			</div><!--cierre menu-->

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>