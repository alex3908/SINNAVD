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

	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;
	$idPc= ($_SESSION['idPc']);
	@$buscar = $_POST["palabra"];
	if (empty($buscar)) {
		$qcasos="SELECT distinct c.id, c.folio_c, c.nombre FROM casos c inner join nna_caso r on r.id_caso=c.id
		inner join nna on nna.id=r.id_nna where nna.apellido_p in (SELECT distinct nna.apellido_p
		from nna inner join relacion_nna_nnareportado r on nna.id=r.id_nna
		inner join nna_reportados n on n.id=r.id_nna_reportado
		 where n.id_posible_caso=$idPc) or  nna.apellido_m in (SELECT distinct nna.apellido_m
		from nna inner join relacion_nna_nnareportado r on nna.id=r.id_nna
		inner join nna_reportados n on n.id=r.id_nna_reportado
		 where n.id_posible_caso=$idPc) order by c.id desc limit 30";
	} else {
		$qcasos="SELECT c.id, c.folio_c, c.nombre FROM casos c 
		where c.folio_c like '%$buscar%' or c.nombre like '%$buscar%' order by id desc";
	}

	$qpc="SELECT folio from posible_caso where id=$idPc";
	$rpc=$mysqli->query($qpc);
	$folioPc=implode($rpc->fetch_assoc());
	$qreportes="SELECT folio from reportes_vd where id_posible_caso=$idPc";
	$rReportes=$mysqli->query($qreportes);
	$qnnaRep="SELECT nna.id, nna.nombre, nna.apellido_p, nna.apellido_m 
	from nna_reportados n inner join relacion_nna_nnareportado r on r.id_nna_reportado=n.id
	inner join nna on nna.id=r.id_nna
	where id_posible_caso=$idPc";
	$rNnaRep=$mysqli->query($qnnaRep);

?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Vincular caso</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
	</head>
	<body>
		<div id="wrapper">
			<div id="main">
				<div class="inner">
					<div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div>
					<div class="box">
						<div class="row uniform">
							<div class="3u">
								Folio posible caso: <b><?=$folioPc ?></b><br>
								Reportes: <?php while ($rwrep=$rReportes->fetch_assoc()) {
									echo $rwrep['folio']."<br>";
								} ?>
							</div>
							<div class="7u">
								<h3> NNA: </h3>
								<?php while ($rwNnaRep= $rNnaRep->fetch_assoc()) {
									echo $rwNnaRep['nombre']." ".$rwNnaRep['apellido_p']." ".$rwNnaRep['apellido_m'].", ";
								}
								?>
							</div>
							<div class="2u">
								<input type="button" name="regresar" class="button special" value="Cancelar" onclick="location='verificar_datos_nna.php?idPc=<?=$idPc?>'">
							</div>
						</div>
					</div>
					<br>

					<table class="alt">
						<thead>
							<tr><td>Ingrese el número de folio o nombre del caso: </td></tr>
							<tr>
								<td colspan="2">
									<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
										<input type="search" style="text-transform:uppercase;" name="palabra" id="query" placeholder="BUSCAR..." />
									</form>
								</td>
							</tr>
						</thead>
					</table>
					<h2>Casos </h2>
					<table>			
						<tr>
							<td><b>Folio</b></td>
							<td><b>Nombre</b></td>
							<td><b>NNA involucrados</b></td>
							<td></td>
						</tr>
						<tbody>
							<?php	
							$resultado=$mysqli->query($qcasos);
							$rows2=$resultado->num_rows;
							echo "Resultados: ".$rows2;
							while($row=$resultado->fetch_assoc()){ 
								$idC=$row['id'];?>
								<tr>
									<td>
										<?php echo $row['folio_c'];?>
									</td>
									<td>
										<?php echo $row['nombre'];?>
									</td>
									
									<td><?php 
										$qnna="SELECT nna.nombre, apellido_p, apellido_m from nna inner join nna_caso on nna.id=nna_caso.id_nna where nna_caso.id_caso=$idC";
										$rnna=$mysqli->query($qnna);
										while($rwNna=$rnna->fetch_assoc()){
											echo $rwNna['nombre']." ".$rwNna['apellido_p']." ".$rwNna['apellido_m']."<br>";
										}
									?></td>	
									<td><input type="button" name="relacion" value="Vincular" onclick="location='verificar_relacion_pc_caso.php?idCaso=<?= $idC ?>'">								
								</tr>
							<?php } ?>
						</tbody>
					</table>

					<!--cod -->
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
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>