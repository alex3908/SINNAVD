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
	$idNna=$_GET['id'];
	$qDatosNNA="SELECT folio, responna, parentesco, direccion_respo, observaciones, telefono
	from nna where id=$idNna";
	$rDatosNna=$mysqli->query($qDatosNNA);

	if(!empty($_POST['actualizar'])){
		$fecha= date("Y-m-d H:i:s", time());
		$nombre=$_POST['txtRespo'];
		$parentesco=$_POST['txtParentesco'];
		$direccion=$_POST['txtDireccion'];
		$telefono=$_POST['txtTelefono'];
		$observaciones=$_POST['txtObserva'];

		$qHts="INSERT INTO historico_responsables_nna (id_nna, nombre_persona, parentesco, direccion, observaciones, telefono, fecha_registro, id_respo_reg)
		select $idNna, responna, parentesco, direccion_respo, observaciones, telefono, '$fecha', '$idDEPTO' from nna where id=$idNna";
		$rHts=$mysqli->query($qHts);
		if($rHts){
			$qActu="UPDATE nna set responna='$nombre', parentesco='$parentesco', direccion_respo='$direccion',
			observaciones='$observaciones', telefono='$telefono' where id=$idNna";
			$rAct=$mysqli->query($qActu);
			if($rAct) {
				echo "<script>
					alert('Se ha actualizado la informaci??n correctamente');
					window.location= 'perfil_nna.php?id=$idNna'
				</script>";
			} else echo $qActu;
		} else echo $qHts;

	}

	

?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Editar responsable</title>
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
					<div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div>
					<?php while($rw=$rDatosNna->fetch_assoc()) { ?>
						<div class="row uniform">
							<div class="9u">
								<h3>Editar responsable del NNNA <?=$rw['folio']?></h3>
							</div>
							<div class="3u">
								<div class="6u">
									<input type="button" name="cancelar" value="Cancelar" onclick="location='perfil_nna.php?id=<?=$idNna?>'">
								</div>
							</div>
						</div>
						<br>
						<form method="POST" id="frmEdicion" name="frmEdicion">
							<div class="box">
								<div class="row uniform">
									<div class="7u">
										<label for="txtRespo">Nombre: </label>
										<input type="text" name="txtRespo" maxlength="100" onkeyup="this.value=this.value.toUpperCase();" required="true" value="<?=$rw['responna']?>">
									</div>
									<div class="5u">
										<label for="txtParentesco">Parentesco: </label>
										<input type="text" name="txtParentesco" maxlength="50" onkeyup="this.value=this.value.toUpperCase();"  required="true" value="<?=$rw['parentesco']?>">
									</div>
								</div>
								<div class="row uniform">
									<div class="8u">
										<label for="txtDireccion">Direcci??n: </label>
										<input type="text" name="txtDireccion" maxlength="150" onkeyup="this.value=this.value.toUpperCase();" required="true" value="<?=$rw['direccion_respo']?>">
									</div>
									<div class="4u">
										<label for="txtTelefono">Tel??fono: </label>
										<input type="text" name="txtTelefono" maxlength="50" onkeyup="this.value=this.value.toUpperCase();"  value="<?=$rw['telefono']?>">
									</div>
								</div>
								<div class="row uniform">
									<div class="12u">
										<label for="txtObserva">Observaciones: </label>
										<input type="text" name="txtObserva" maxlength="150" onkeyup="this.value=this.value.toUpperCase();" value="<?=$rw['observaciones'] ?>" >
									</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="12u">
									<input class="button special fit" type="submit" name="actualizar" value="Actualizar">
								</div>
								
							</div>
						</form>
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
								<h2>Men??</h2>
							</header>
							<ul>
								<li><a href="welcome.php">Inicio</a></li>	
								<li><a href="lista_unidad.php">UIENNAVD</a></li>
								<li><a href="logout.php">Cerrar sesi??n</a></li>
							</ul>
						</nav>	
					<?php }else if($idPersonal==5) { //Subprocu ?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Men??</h2>
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
								<li><a href="logout.php">Cerrar sesi??n</a></li>
							</ul>
						</nav>	
					<?php }else { ?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Men??</h2>
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
								<li><a href="logout.php">Cerrar sesi??n</a></li>
							</ul>
						</nav>	
					<?php }?>
					<section>
						<header class="major">
							<h4>PROCURADUR??A DE PROTECCI??N DE NI??AS, NI??OS, ADOLESCENTES Y LA FAMILIA</h4>
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