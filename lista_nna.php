<?php
	ob_start();
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	$fec= date("Y-m-d H:i:s");
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

	$total="SELECT id from nna";
	$etotal=$mysqli->query($total);
	$total2="SELECT id from nna_exposito where nna_n=0";
	$etotal2=$mysqli->query($total2);
	$rows=$etotal->num_rows;
	$rows2=$etotal2->num_rows;
	$suma=$rows+$rows2
?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Lista NNA</title>
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
				<div class="inner"><br>
					<div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div>
					<table class="alt">
						<thead>
							<tr>
								<td><h1>NNA</h1></td>
								<?php if ($_SESSION['spcargo']==3 or $_SESSION['departamento']==16 or $_SESSION['spcargo']==5) { ?>
									<td>
										<input type="button" value="ALTA" class="button special" onclick="location='registrar_nna_sr.php'">
									</td>
								<?php } ?>
							</tr>
							<tr>
								<td colspan="2">
									<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
										<input type="search" style="text-transform:uppercase;" name="palabra" id="query" placeholder="BUSCAR..." />
										<h3>NNA registrados: <?php echo $suma; ?></h3>
									</form>
								</td>
							</tr>
						</thead>
					</table>
					<table>			
						<tr>
							<td><b>Folio</b></td>
							<td><b>No. identificador</b></td>
							<td><b>Nombre</b></td>
							<td><b>Edad </b></td>
							<td><b>Responsable del NNA</b></td>
							<td><b>CURP validada</b></td>
						</tr>
						<tbody>
							<?php	@$buscar = $_POST["palabra"];
							if (empty($buscar)) {
								$query="SELECT id, validacionRenapo, folio, nombre, apellido_p, apellido_m, concat(nombre,' ',apellido_p,' ',apellido_m) as nom, fecha_nacimiento as fecha_nac, responna, curp, fecha_registro from nna where activo=1 order by fecha_registro desc limit 20";
							} else {
								$query="SELECT id, validacionRenapo, folio, nombre, apellido_p, apellido_m, concat(nombre,' ',apellido_p,' ',apellido_m) as nom, fecha_nacimiento as fecha_nac,  responna, curp from nna where activo=1 having (nombre like '%$buscar%' OR apellido_p like '%$buscar%' OR apellido_m like '%$buscar%' OR folio like '%$buscar%' OR nom like '%$buscar%' OR fecha_nac like '%$buscar%' or curp like '%$buscar%')";
							}
							$resultado=$mysqli->query($query);
							$rows2=$resultado->num_rows;
							echo "Resultados: ".$rows2;
							while($row=$resultado->fetch_assoc()){ 
								$idNNA=$row['id'];?>
								<tr>
									<td>
										<a href="perfil_nna.php?id=<?php echo $row['id'];?>"><?php echo $row['folio'];?></a>
									</td>
									<td>
										<?php echo $row['id'];?>
									</td>
									<td>
										<?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?>
									</td>
									<td style="text-transform:uppercase;">
										<?php /* $fec=$row['fecha_nac'];
										@list($dia, $mes, $ano)=explode('/', $fec);
										@$diah=date(j);
										@$mesh=date(n);
										@$anoh=date(Y);
										if (($mes == $mesh) && ($dia > $diah)) {
											$anoh=($anoh-1);
										}
										if ($mes > $mesh) {
											$anoh=($anoh-1);
										}
										$edad=($anoh-$ano);
										$meses=($mesh-$mes);
										echo $edad.' años '.$meses.' meses';*/
										$fecha_nacimiento= $row['fecha_nac'];	
										if($fecha_nacimiento=='1900-01-01' or empty($fecha_nacimiento))	
											$edad="Sin registro"; 
										else {
											$anioN=date('Y', strtotime($fecha_nacimiento));  //calcular edad
				         					$anioA=date('Y', strtotime($fec));
				         					$mesN=date('m', strtotime($fecha_nacimiento));
				         					$mesA=date('m', strtotime($fec));
				         					$diaN=date('d', strtotime($fecha_nacimiento));
				         					$diaA=date('d', strtotime($fec));
				         					if(($mesN<$mesA) or ($mesN==$mesA and $diaN<=$diaA)){
				         					    $anios=$anioA-$anioN;
				         					    $meses=$mesA-$mesN;	
				         					    if($anios==1)
				         					    	$cadAnio=" año, ";
				         					    else
				         					    	$cadAnio=" años, ";
				         					    if ($meses==1)
				         					    	$cadMes= " mes";
				         					    else 
				         					    	$cadMes=" meses";	         					    
				         					} else {
				         					    $anios=$anioA-$anioN-1; 
				         					    $meses=12-($mesN-$mesA);
				         					    if($anios==1)
				         					    	$cadAnio=" año, ";
				         					    else
				         					    	$cadAnio=" años, ";
				         					    if ($meses==1)
				         					    	$cadMes= " mes";
				         					    else 
				         					    	$cadMes=" meses";	
				         					}
				         					$edad= $anios.$cadAnio.$meses.$cadMes;
			         					} 
			         					echo $edad; ?>
			         				</td>
									<td><?php echo $row['responna'];?></td>
									<td>
										<?php if($row['validacionRenapo']==1){ ?>
											<input type="image" alt="Validada" name="validada" src="images/ejecutada.png" height="30" width="30">
										<?php } else {  ?>
											<input type="image" alt="No validada" name="noValidada" src="images/no_ejecutada.png" height="30" width="30" onclick="location='validarCurp.php?id=<?= $idNNA?>&T=1'">
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					<?php if(@$bandera) { 
						header("Location: welcome.php");
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
		</div>  <!--cierre de wrapper-->
		<!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>