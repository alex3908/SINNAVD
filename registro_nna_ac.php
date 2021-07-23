 <?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}


	$idDEPTO = $_SESSION['id'];	
	$idPc=$_GET['id'];
	$qpart1ac="SELECT id from part1ac where id_reporte=$idPc and id<3814";
	$rpart1=$mysqli->query($qpart1ac);
	$YaExiste=$rpart1->num_rows;
	if($YaExiste>0){
		header("Location: acercamiento.php?id=$idPc");
	}

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

	$qNnas="SELECT nna_reportados.id as idNna, nombre, apellido_p, apellido_m, sexo.sexo, 
	date_format(nna_reportados.fecha_nacimiento,'%d/%m/%Y') as fecha_nac, 
	lugar_nacimiento, edad, nna_reportados.activo, 
	date_format(fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, 
	d.responsable 
	from nna_reportados left join sexo on nna_reportados.sexo=sexo.id 
	inner join departamentos d on d.id=nna_reportados.responsable_registro 
	where id_posible_caso='$idPc' and nna_reportados.activo=1";
	$rNnas=$mysqli->query($qNnas);

	$qNnaAc1="SELECT nna_ac.id from nna_ac inner join part1ac on nna_ac.id_acerca=part1ac.id
	where part1ac.id_reporte=$idPc";
	$rNnaAc1=$mysqli->query($qNnaAc1);
    $NnaReg1=$rNnaAc1->num_rows;

    $acts="SELECT id from acercamiento_familiar where id_reporte='$idPc'";  //recupera el id del registro de trabajo social 
    $eacts=$mysqli->query($acts);
    while ($row=$eacts->fetch_assoc()) {
        $idacts=$row['id']; //recupera si hay datos
    }

?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Perfil</title>
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
					<div class="row uniform">
                    	<div class="10u"><h2>Acercamientos</h2></div>
                    	<div class="2u">
                    		<input type="button" class="button special small" name="regresar" value="Regresar" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?=$idPc?>'" >
                    	</div>
                    </div>
                    <div class="box">
                    	<h3>Seleccione el NNA al que se realizará el acercamiento</h3>
                    	
	                    	<?php while ($row=$rNnas->fetch_assoc()){ 
	                    		$idNna=$row['idNna']?>
                    			<div class="box">
                    				<?php 
                    					$qNnaAc="SELECT id, concat(nombre, ' ', apellido_p, ' ', apellido_m) as nombreCompleto,
											date_format(fecha_nac, '%d/%m/%Y' ) as fecha_nac, lugar_nac, nacionalidad, ocupacion, religion
 											from nna_ac where id_nna_reportado=$idNna";
                    					$rNnaAc=$mysqli->query($qNnaAc);
                    					$NnaReg=$rNnaAc->num_rows;
                    					if($NnaReg==0) { ?>
                    						<div class="row uniform">
                    						<div class="7u">
                    						<?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?>
                    						</div>
                    						<div class="5u">
                    							<input type="button" class="button special small" name="Acercamiento" value="Añadir a acercamiento" onclick="location='registro_nna_ac2.php?idPosibleCaso=<?=$idPc?>&idNna=<?=$idNna?>'" >
                    						</div>  
                    				</div>
                    			<?php } else { 
                    					while ($rwN=$rNnaAc->fetch_assoc()) {
                    						$idNnaAc=$rwN['id'];                   					
                    					?>
                    					<div class="row uniform">
                    						<div class="3u">
                    							<label>Nombre</label>
                    							<?= $rwN['nombreCompleto']?>
                    						</div>
                    						<div class="3u">
                    							<label>Fecha de nacimiento</label>
                    							<?= $rwN['fecha_nac']?>
                    						</div>
                    						<div class="3u">
                    							<label>Lugar de nacimiento</label>
                    							<?= $rwN['lugar_nac']?>
                    						</div>
                    						<div class="3u">
                    							<input type="button" class="button special small" name="Acercamiento" value="Acercamiento psicológico" onclick="location='acercamiento_psic.php?id=<?=$idPc?>&idn=<?=$idNnaAc?>'" >
                    						</div> 
                    					</div>
                    					<div class="row uniform">
                    						<div class="4u">
                    							<label>Nacionalidad</label>
                    							<?= $rwN['nacionalidad']?>
                    						</div>
                    						<div class="4u">
                    							<label>Ocupación</label>
                    							<?= $rwN['ocupacion']?>
                    						</div>
                    						<div class="4u">
                    							<label>Religión</label>
                    							<?= $rwN['religion']?>
                    						</div>
                    					</div>
                    				<?php } } ?>
                    			</div>        	
                    		<?php }  ?> 
                    </div>     
                    <br>
                    <?php if($NnaReg1>0) { 
                    	if (empty($idacts)) { ?> <!--si no ha realizado acercamiento direcciona a reg_pacerca-->
                            <input class="button special fit" type="button" name="cancelar" value="Trabajo social" onclick="location='reg_pacerca.php?id=<?php echo $idPc; ?>'" >
                        <?php }else { ?> <!--si ya registro direcciona a acercamiento_t donde muestra los datos de acercamiento-->
                            <input class="button special fit" type="button" name="cancelar" value="Trabajo social" onclick="location='acercamiento_ts.php?id=<?php echo $idPc; ?>'" >
                        <?php }  } ?>          
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