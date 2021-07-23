<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
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
	$idPc=$_GET['idPc'];
	$idNnaRep=$_GET['idNnaR'];
	$idCaso=$_GET['c'];
	$idNna=$_GET['idNna'];
	$curp=$_GET['curp'];
	$qExisteRel="SELECT id from relacion_nna_nnareportado where id_nna=$idNna and id_nna_reportado=$idNnaRep";
	$rExisteRel=$mysqli->query($qExisteRel);
	$existeRel=$rExisteRel->num_rows;
	//if($existeRel>0)
	//	header("Location: verificar_datos_nna.php?idPc=$idPc");

	$qDatosNna="SELECT nna.folio, nna.nombre, nna.apellido_p, nna.curp, nna.apellido_m, 
	date_format(nna.fecha_nacimiento, '%d/%m/%Y') as fecha_nac,
	nna.sexo, nna.responna, nna.parentesco, nna.direccion, nna.telefono, nna.lugar_nac, nna.lugar_reg, 
	departamentos.responsable, nna.nna_ex, municipios.municipio, localidades.localidad, 
	date_format(nna.fecha_registro,'%d/%m/%Y %H:%i:%s') as fecha_reg
	from  nna inner join departamentos on departamentos.id=nna.respo_reg 
	left join localidades on localidades.id=nna.localidad
	left join municipios on municipios.id=nna.municipio where nna.id='$idNna'";
	$rDatosNna=$mysqli->query($qDatosNna);
	if(!empty($idCaso)) {
		$qCasos="SELECT folio_c, nombre, descripcion, responsable, date_format(fecha_registro, '%d/%m/%Y') as fecha FROM casos
		left join departamentos on departamentos.id=casos.funcionario_reg where casos.id=$idCaso";
		$rCasos=$mysqli->query($qCasos);
	}

	if(isset($_POST['btnRelacion'])) {  
		$qRelacionNna="INSERT INTO relacion_nna_nnareportado (id_nna, id_nna_reportado) 
		values ($idNna, $idNnaRep)";
		$rRelacionNna=$mysqli->query($qRelacionNna);
		if($rRelacionNna){
			if(empty($idCaso))
				header("Location: verificar_datos_nna.php?idPc=$idPc");
			else {
				$qRelacionCaso="INSERT INTO relacion_pc_caso (id_posible_caso, id_caso) 
				values ('$idPc', '$idCaso')";
				$rRelacionCaso=$mysqli->query($qRelacionCaso);
				if($rRelacionCaso)
					header("Location: verificar_datos_nna.php?idPc=$idPc");
				else echo $qRelacionCaso;
			}
		} else echo $qRelacionNna;
	}
	

	

?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Vincular NNA</title>
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
					<!--cod -->
					<?php while($rwNna=$rDatosNna->fetch_assoc()) { ?>
					<h2><?=$rwNna['nombre']." ".$rwNna['apellido_p']." ".$rwNna['apellido_m']?></h2>
					<h4>CURP: <?=$rwNna['curp'] ?></h4>
					<h2>Fecha de registro al SINNAVD: <?php if(empty($rwNna['fecha_reg'])) echo "Sin fecha valida"; else echo $rwNna['fecha_reg'];?></h2>
					<div class="row uniform">
						<div class="6u">
							<div class="box">
								<ul class="alt">
									<li><h4>Folio: </h4><?php echo $rwNna['folio'];  ?> </li>
									<li><h4>Número identificador: </h4><?php echo $idNna;  ?> </li>
									<li><h4>Sexo: </h4><?php echo $rwNna['sexo'];  ?> </li>
									<li><h4>Fecha de nacimiento: </h4><?php echo $rwNna['fecha_nac'];  ?> </li>
									<li><h4>Lugar de nacimiento: </h4><?php echo $rwNna['lugar_nac'];  ?> </li>
									<li><h4>Lugar de registro: </h4><?php echo $rwNna['lugar_reg'];  ?> </li>
								</ul>
							</div>
						</div>
						<div class="6u">
							<div class="box">
								<ul class="alt">
									<li><h4>Responsable del NNA: </h4><?= $rwNna['responna'];  ?> </li>
									<li><h4>Parentesco: </h4><?= $rwNna['parentesco'];  ?> </li>
									<li><h4>Dirección: </h4><?= $rwNna['direccion'];  ?> </li>
									<li><h4>Telefono: </h4><?= $rwNna['telefono'];  ?> </li>
									<li><h4>Responsable de registro: </h4><?= $rwNna['responsable'];?> </li>
								</ul>
							</div>
						</div>
					</div>
					<?php } if(!empty($idCaso)) { 
						while($rwCaso=$rCasos->fetch_assoc()) { ?>
							<br>
							<div class="row uiform">
								<div class="12u">
									<div class="box">
										<ul class="alt">
											<li><h4>Folio del caso: <?= $rwCaso['folio_c'];  ?></h4> </li>
											<li><h4>Nombre: <?= $rwCaso['nombre'];  ?></h4> </li>
											<li><h4>Fecha de registro: <?= $rwCaso['fecha'];  ?></h4> </li>
											<li><h4>Responsable de registro: <?=$rwCaso['responsable']; ?></h4></li>
										</ul>
									</div>
								</div>
							</div>
						<?php } 
					} ?>
					<div class="row uniform">
						<div class="6u">
							<form id="relacion" name="relacion" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								<input class="button special fit" name="btnRelacion" type="submit" value="Vincular" >
							</form>
						</div>
						<div class="6u">
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='registro_curp_nna.php?idNna=<?=$idNnaRep?>&idPc=<?=$idPc?>&curp=<?=$curp?>'">
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
			</div>
		</div>  <!--cierre de wrapper-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>