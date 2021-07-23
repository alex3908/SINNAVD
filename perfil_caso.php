<?php
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	$fec= date("Y-m-d H:i:s", time());
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idCaso = $_GET['id'];
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

	$query="SELECT casos.id, casos.folio_c, casos.nombre, casos.descripcion, departamentos.responsable, 
	date_format(casos.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha FROM departamentos, casos 
	WHERE casos.funcionario_reg=departamentos.id and casos.id='$idCaso' and casos.activo=1";
	$resultado=$mysqli->query($query);

	$qPc="SELECT pc.folio, pc.id from posible_caso pc
	inner join relacion_pc_caso r on r.id_posible_caso=pc.id
	where r.id_caso=$idCaso and pc.activo=1"; 
	$rPc=$mysqli->query($qPc);
	$NumPc=$rPc->num_rows;
	
	$qVerificarPlan="SELECT planes_de_restitucion.id from planes_de_restitucion where id_caso='$idCaso'";
		$rplan=$mysqli->query($qVerificarPlan);
		$numPlan=$rplan->num_rows;

	$sqlnna="SELECT nna.id, nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, nna.fecha_nacimiento as fecha_nac, nna.sexo, validacionRenapo
	from nna inner join nna_caso on nna_caso.id_nna=nna.id where nna_caso.id_caso='$idCaso'  and nna_caso.estado='NE' and nna.activo=1";
	$esqlnna=$mysqli->query($sqlnna);

	$sqlnnaE="SELECT nna_exposito.id, nna_exposito.folio, nna_exposito.sexo, nna_exposito.fecha_reg, municipios.municipio, departamentos.responsable from nna_exposito, municipios, departamentos, nna_caso where nna_caso.id_caso='$idCaso' and nna_caso.id_nna=nna_exposito.id and nna_caso.estado='E' and departamentos.id=nna_exposito.respo_reg and municipios.id=nna_exposito.municipio_deteccion";
	$esqlnnaE=$mysqli->query($sqlnnaE);
	
	$validaNE="SELECT id from nna_caso where id_caso='$idCaso' and estado='NE'";
	$evalNE=$mysqli->query($validaNE);
	$rowNE=$evalNE->num_rows;
	
	$validaE="SELECT id from nna_caso where id_caso='$idCaso' and estado='E'";
	$evalE=$mysqli->query($validaE);
	$rowE=$evalE->num_rows;

	$validaC="SELECT id from carpeta_inv where id_caso='$idCaso'";
	$evalC=$mysqli->query($validaC);
	$rowC=$evalC->num_rows;

	$valida="SELECT id from departamentos where (id_depto='10' and id_personal='3' and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5' and id='$idDEPTO')";
	$evalida=$mysqli->query($valida);
	$rows=$evalida->num_rows;
	if($idDEPTO==220)
		$rows=1;

	if (!empty($_POST['cuadro_guia'])) {
        
        if($numPlan==0){
			$qRegistrarPlan="INSERT INTO `planes_de_restitucion` (`id_caso`) VALUES ('$idCaso')";
			$eRegistrarPlan=$mysqli->query($qRegistrarPlan);
		}
            header("Location: cuadro_guia.php?id=$idCaso");
	}
	if (!empty($_POST['eliminar'])) {
        $hoy= date("Y-m-d H:i:s", time());
        $sql="UPDATE casos SET activo=0, fecha_desact='$hoy', respo_desact='$idDEPTO' where id='$idCaso'";
        $esql=$mysqli->query($sql);
        if ($esql>0) {
            header("Location: lista_casos.php");
        } 
    }
?>



<!DOCTYPE HTML>

<html>
	<head>
		<title>Caso</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>		
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
					<?php while($row=$resultado->fetch_assoc()) { ?>
						<div class="row uniform">
							<div class="10u"><h1><?php echo $row['nombre'];  ?> </h1></div>
							<div class="2u">
								<input type="button" class="button special" onclick="location='lista_casos.php'" value="Regresar">
							</div>
						</div>
						<ul class="alt">
							<li><h4>Folio: </h4><?php echo $row['folio_c'];  ?> </li>
							<li><h4>Reporte: </h4>
								<?php while($rowpc=$rPc->fetch_assoc()){
									$FPc=$rowpc['folio'];
									$idPc=$rowpc['id']; ?>
									<b> <a href="perfil_posible_caso.php?idPosibleCaso=<?=$idPc?>"><?="-".$FPc.": "?></a> </b>
									<?php $qreportes="SELECT folio from reportes_vd where id_posible_caso='$idPc' and activo=1";
									$rReportes=$mysqli->query($qreportes);
									while($rwRep=$rReportes->fetch_assoc()){
										echo $rwRep['folio'].", ";
									} ?>
								<br>
								<?php } ?>
							</li>
							<li><h4>Detección: </h4><?php echo $row['descripcion'];  ?> </li>
							<li><h4>Responsable del registro: </h4><?php echo $row['responsable'];  ?> </li>
							<li><h4>Fecha: </h4><?php echo $row['fecha'];  ?> </li>
						</ul>
						<div class="box">
							<div class="table-wrapper">
								<?php if ($rowNE>0) { ?>
									<h4>Niñas, Niños y Adolescentes</h4>								
									<table class="alt">
										<thead>
											<tr>
												<th>FOLIO</th>
												<th>NOMBRE</th>
												<th>EDAD</th>
												<th>SEXO</th>
												<th>VALIDACION CURP</th>
												<th>SITUACION</th>
												<th></th>												
											</tr>
										</thead>
										<tbody>
											<?php while($row=$esqlnna->fetch_assoc()){ ?>
												<tr>
													<?php $idNNA=$row['id']; ?>
													<td><a href="perfil_nna.php?id=<?php echo $idNNA; ?>"><?php echo $row['folio'];?></a></td>
													<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
													<td>
														<?php $fecha_nacimiento= $row['fecha_nac'];	
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
													<td><?php echo $row['sexo'];?></td>
													<td>
														<?php if($row['validacionRenapo']==1){ ?>
															<input type="image" alt="Validada" name="validada" src="images/ejecutada.png" height="30" width="30">
														<?php } else {  ?>
															<input type="image" alt="No validada" name="noValidada" src="images/no_ejecutada.png" height="30" width="30" onclick="location='validarCurp.php?id=<?= $idNNA?>&T=1'">
														<?php } ?>
													</td>
													<td>
														<?php $idn= $row['id']; 
														$porcentaje=0;
														$decretas="SELECT count(cuadro_guia.id) as total from cuadro_guia inner join benefmed on cuadro_guia.id=benefmed.id_medida 
															where activo=1 and cuadro_guia.id_caso=$idCaso and benefmed.id_nna=$idn";
															$qdecretadas=$mysqli->query($decretas);
															$numdecretadas= implode($qdecretadas->fetch_assoc());
															if($numdecretadas>0){
															$ejecutadas="SELECT count(cuadro_guia.id) as total from cuadro_guia inner join benefmed on cuadro_guia.id=benefmed.id_medida 
															where activo=1 and cuadro_guia.id_caso=$idCaso and benefmed.id_nna=$idn and cuadro_guia.estado=1";
															$qejecutadas=$mysqli->query($ejecutadas);
															$numejecutadas= implode($qejecutadas->fetch_assoc());
															$porcentaje = intval($numejecutadas/$numdecretadas*100);

														}
														$va="SELECT id from nna_restituidos where id_nna='$idn'";
														$eva=$mysqli->query($va);
														$reva=$eva->num_rows;
														if ($reva>0) { ?>
														 	DERECHOS RESTITUIDOS
														 	<img src="images\alegre.png" width="50 px">
														<?php } else { 
															if($porcentaje>=60){
																echo "Medidas ejecuatas al ".$porcentaje."% cambiar a ";
															?>
															<input name="btnrestituidos" class="button fit small" type="button" value="nna con derechos restituidos" onclick="if (confirm('¿Restituir derechos?'))
															location='derechos_rest.php?id=<?php echo $idn; ?>&idC=<?php echo $idCaso; ?>'">
														<?php } else { ?>
															<input name="btnCuadroguia" class="button fit small" type="button" value="Completar cuadro guia" onclick="location='cuadro_guia.php?id=<?=$idCaso?>'">
														<?php } } ?>
													</td>
													<?php if ($idPersonal==1 and $NumPc==0) { ?>
														<td>
															<input name="btneliminar" class="button special fit small" type="button" value="Eliminar" onclick="location='eliminarnna_caso.php?idnna=<?php echo $row['id']; ?>&idC=<?php echo $idCaso; ?>'" >
														</td>
													<?php }  ?>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								<?php } if ($rowE>0) { ?>
									<h4>Niñas, Niños y Adolescentes Expositos</h4>
									<table class="alt">
										<thead>
											<tr>
												<th>FOLIO</th>
												<th>SEXO</th>
												<th>MUNICIPIO DE DETECCION</th>
												<th>FECHA DE REGISTRO</th>
												<th>RESPONSABLE DE REGISTRO</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
											<?php while($row=$esqlnnaE->fetch_assoc()){ ?>
												<tr>
													<td><?php echo $row['folio'];?></td>
													<td><?php echo $row['sexo'];?></td>
													<td><?php echo $row['municipio'];?></td>
													<td><?php echo $row['fecha_reg'];?></td>
													<td><?php echo $row['responsable'];?></td>
													<?php if ($idPersonal==1) { ?>
														<td><input name="eliminar" class="button special fit small" type="button" value="Eliminar" onclick="location='eliminarnna_caso.php?idnna=<?php echo $row['id']; ?>&idC=<?php echo $idCaso; ?>'">
														</td>
													<?php }  ?>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								<?php } ?>
							</div>
						</div>
						<div class="box">
							<div class="table-wrapper">
								<h4>Carpetas de investigación iniciadas</h4>
								<?php if ($rowC>0) { ?>
									<table class="alt" >
										<thead>
											<tr>
												<td><b>NUC</b></td>
												<td><b>Fecha de inicio</b></td>
												<td><b>Representante coadyuvante</b></td>
												<td><b>Estatus</b></td>
												<td></td>
											</tr>
										</thead>
										<tbody>
											<?php 
											$query="SELECT carpeta_inv.id, carpeta_inv.nuc, date_format(carpeta_inv.fecha_inicio,'%d/%m/%Y' ) as fecha_ini, casos.folio_c, carpeta_inv.estado, departamentos.responsable 
											from carpeta_inv inner join casos on casos.id=carpeta_inv.id_caso
											left join departamentos on departamentos.id=carpeta_inv.asignado
											 where  carpeta_inv.id_caso='$idCaso'";
											$resultado=$mysqli->query($query);
											while($row=$resultado->fetch_assoc()){ ?>
												<tr>
													<td><?php echo $row['nuc'];?></td>
													<td><?php echo $row['fecha_ini'];?></td>
													<td>
														<?php $id_asi=$row['responsable']; 
														if (is_null($id_asi)) { ?>
															<?php if ($rows=='0') { ?>
																<input type="button" class="special button fit small" name="Asignar" value="Asignar" >
															<?php }else{ ?>
																<input type="button" class="special button fit small" name="Asignar" value="Asignar" onclick="location='asignar_carpeta.php?id=<?php echo $row['id'];?>'">
															<?php }
														} else { ?>  
															<?php  echo $row['responsable']; 
														} ?>
													</td>
													<td>
														<?php $est=$row['estado'];
														if ($est==20) { ?>
												 			<img src="images/G20.png" width="80">
												 		<?php } else if ($est==40) { ?>
												 			<img src="images/G40.png" width="80">
														<?php } else if ($est==60) { ?>
												 			<img src="images/G60.png" width="80">
														<?php }else if ($est==80) { ?>
												 			<img src="images/G80.png" width="80">
														<?php }else if($est==100){ ?>
												 			<img src="images/G100.png" width="80">
														<?php } ?>
													</td>
													<td><input type="button" name="Ver" value="Ver" onclick="location='perfil_carpeta.php?id=<?php echo $row['id'];?>'">
													</td>
												</tr>
											<?php } ?>
										</tbody>
									</table>
								<?php } else { ?>
									<h4>NINGUNA</h4> 
								<?php } ?>	
							</div>
						</div>
						<div class="row uniform">
							<?php if ($_SESSION['spcargo']==3 or $_SESSION['departamento']==16 or $_SESSION['spcargo']==5) { ?>
								<div class="6u 12u$(xsmall)">									
									<input type="button" name="asignar_curso" class="button fit" value="carpeta de investigación" onclick="location='reg_carpeta.php?id=<?php echo $idCaso;?>'">
								</div>
								<div class="6u 12u$(xsmall)">
									<input type="button" name="asignar_curso" class="button special fit" value="Añadir NNA" onclick="location='ag_nna_caso.php<?php $_SESSION['idCaso']=$idCaso;?>'" <?php if($NumPc>0) { ?> disabled <?php } ?> >		
								</div>
							<?php } ?>
							<div class="6u 12u$(xsmall)">
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
									<input type="submit" name="cuadro_guia" class="button special fit" value="cuadro guia" onclick="location='cuadro_guia.php?id=<?php echo $idCaso;?>'">
								</form>
							</div>
							<div class="6u 12u$(xsmall)">
									<input type="button" name="asignar_curso" class="button fit" value="Editar" onclick="location='editar_caso.php?id=<?php echo $idCaso;?>'">
							</div>
							<?php if ($_SESSION['departamento']==16){ ?>							
								<div class="12u 12u$(xsmall)">
									<input type="button" name="eliminar" value="eliminar" class="button fit">
								</div>
							<?php } ?>
						</div>

						<br>
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
			</div>

		</div>

		<!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>

	</body>
</html>