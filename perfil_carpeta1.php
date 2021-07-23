<?php
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idCarpeta = $_GET['id'];
	$fecha= date ("j/n/Y");
	$fec= date("Y-m-d H:i:s");
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
	$datosCarp = "SELECT casos.id as idCaso, casos.folio_c, carpeta_inv.nuc, date_format(carpeta_inv.fecha_inicio,'%d/%m/%Y') as fecha_ini, 
	distritos.distrits, municipios.municipio, delitos.delito, carpeta_inv.imputado, carpeta_inv.relacion, 
	carpeta_inv.mesa, h.porcentaje, date_format(carpeta_inv.fecha_registro,'%d/%m/%Y %H:%i:%s') as fecha_reg, 
    carpeta_inv.respo_reg, d.responsable, d.id as idAsignado
	from casos inner join carpeta_inv on carpeta_inv.id_caso=casos.id
	left join historico_avances_carpeta h on h.id=carpeta_inv.id_avance
	inner join distritos on distritos.id=carpeta_inv.distrito
	inner join municipios on municipios.id=carpeta_inv.municipio_d
	inner join delitos on delitos.id=carpeta_inv.id_delito
    left join historico_asignaciones_carpeta asi on asi.id=carpeta_inv.id_asignado
    left join departamentos d on d.id=asi.id_respo_asignado
	where carpeta_inv.id=$idCarpeta"; 
	$qDatosCarp=$mysqli->query($datosCarp);
	while ($rwCarp=$qDatosCarp->fetch_assoc()) {
		$folio_c=$rwCarp['folio_c'];
		$nuc=$rwCarp['nuc'];
		$fecha_ini=$rwCarp['fecha_ini'];
		$distrits=$rwCarp['distrits'];
		$municipio=$rwCarp['municipio'];
		$delito=$rwCarp['delito'];
		$imputado=$rwCarp['imputado'];
		$relacion=$rwCarp['relacion'];
		$mesa=$rwCarp['mesa'];
		$porcentaje=$rwCarp['porcentaje'];
		$fecha_reg=$rwCarp['fecha_reg'];
		$respo_reg=$rwCarp['respo_reg'];
		$responsable=$rwCarp['responsable'];
		$idAsignado=$rwCarp['idAsignado'];
		$idCaso=$rwCarp['idCaso'];
	}

	$reportes="SELECT pc.id as idPc, rp.id, rp.folio
	FROM relacion_carpeta_reporte rl inner join reportes_vd rp on rl.id_reporte=rp.id
	inner join posible_caso pc on pc.id=rp.id_posible_caso where id_carpeta=$idCarpeta";
	$qReportes=$mysqli->query($reportes);
	$numRep=$qReportes->num_rows;
$sqlnna="SELECT nna.id, nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, nna.fecha_nacimiento as fecha_nac, nna.sexo from nna inner join nna_caso on nna_caso.id_nna=nna.id
where nna_caso.id_caso='$idCaso' and nna_caso.estado='NE'";
	$esqlnna=$mysqli->query($sqlnna);

	$sqlnnaE="SELECT nna_exposito.id, nna_exposito.folio, nna_exposito.sexo, nna_exposito.fecha_reg, municipios.municipio, departamentos.responsable from nna_exposito, municipios, departamentos, nna_caso where nna_caso.id_caso='$idCaso' and nna_caso.id_nna=nna_exposito.id and nna_caso.estado='E' and departamentos.id=nna_exposito.respo_reg and municipios.id=nna_exposito.municipio_deteccion";
	$esqlnnaE=$mysqli->query($sqlnnaE);

	
	$validaNE="SELECT id from nna_caso where id_caso='$idCaso' and estado='NE'";
	$evalNE=$mysqli->query($validaNE);
	$rowNE=$evalNE->num_rows;
	
	$validaE="SELECT id from nna_caso where id_caso='$idCaso' and estado='E'";
	$evalE=$mysqli->query($validaE);
	$rowE=$evalE->num_rows;

	
	if (isset($_POST['sig_estado'])) { 
		/*$ss="UPDATE carpeta_inv set estado='$nesta', respo_estado='$idDEPTO', fecha_estado='$fecha' where id='$idCarpeta'";
		$ess=$mysqli->query($ss);
		header("location:perfil_carpeta.php?id=$idCarpeta");*/
		echo "<script>if(confirm('¿Cambiar a la siguiente etapa?')){
        document.location='avance_carpeta.php?id=$idCarpeta&estado=$porcentaje';}
        </script>"; 
	}
	if (isset($_POST['victimaNE'])) { 
		$idNNA = mysqli_real_escape_string($mysqli,$_POST['id_NNA']);
		$valida="SELECT id from victimas_c_inv where id_carp='$idCarpeta', id_nna='$idNNA', estado='NE'";
$eva=$mysqli->query($valida);

$rows=$eva->num_rows;
if ($rows>0) {
	
}else {

$sql="INSERT into victimas_c_inv (id_carp, id_nna, estado, res_reg) values ('$idCarpeta', '$idNNA','NE','$idDEPTO')";
$esql=$mysqli->query($sql);
	header("Location: perfil_carpeta.php?id=$idCarpeta");
}
	}

	if (isset($_POST['victimaE'])) { 
		$idNNA = mysqli_real_escape_string($mysqli,$_POST['id_NNAE']);
		$valida="SELECT id from victimas_c_inv where id_carp='$idCarpeta', id_nna='$idNNA', estado='E'";
$eva=$mysqli->query($valida);

$rows=$eva->num_rows;
if ($rows>0) {
	
}else {

$sql="INSERT into victimas_c_inv (id_carp, id_nna, estado, res_reg) values ('$idCarpeta', '$idNNA','E','$idDEPTO')";
$esql=$mysqli->query($sql);
	header("Location: perfil_carpeta.php?id=$idCarpeta");
}
	}

if (!empty($_POST['eliminar'])) {
        
        $sql="DELETE from carpeta_inv where id='$idCarpeta'";
        $esql=$mysqli->query($sql);
        if ($esql>0) {
            header("Location: perfil_caso.php?id=$idCaso");
        }
    }
?>



<!DOCTYPE HTML>

<html>
	<head lang="ES-es">
		<title>Perfil carpeta</title>
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
					<div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div> <br>	
					<div class="row uniform">
						<div class="8u 12u$(xsmall)" align="center">
							<h1>NUC: <?= $nuc ?></h1>
							<h2>Representante coadyuvante: <?="<br>". $responsable ?></h2>
						</div>
						<div class="4u 12u$(xsmall)" align="center">
							<?php if ($porcentaje==20) { ?>
								<img src="images/G20.png" width="150">
								<h3>Etapa: Investigación inicial</h3>
							<?php }else if ($porcentaje==40) { ?>
								<img src="images/G40.png" width="150">
								<h3>Etapa: Investigación complementaria</h3>
							<?php }else if ($porcentaje==60) { ?>
								<img src="images/G60.png" width="150">
								<h3>Etapa: Intermedia</h3>
							<?php }else if ($porcentaje==80) { ?>
								<img src="images/G80.png" width="150">
								<h3>Etapa: Juicio</h3>
							<?php }else if ($porcentaje==100) { ?>
								<img src="images/G100.png" width="150">
								<h3>Etapa: Ejecución</h3>
							<?php }  ?>
						</div>
					</div>
					<div class="row uniform">
						<div class="8u">
							<?php if(($idDepartamento==16 and $idPersonal==1) or ($idDEPTO==$respo_reg)) { ?>
								<input type="button" name="" class="button fit special" <?php if(!empty($idAsignado)) { ?> value="reasignar" <?php } else { ?> value="asignar" <?php } ?>onclick="location='reasignar_car.php?id=<?php echo $idCarpeta;?>'" >
							<?php } ?>
						</div>
						<div class="4u">

							<form id="estado_es" name="estado_es" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								<?php if ($porcentaje<100 and ($idDEPTO==$respo_reg or $idDEPTO==$idAsignado or ($idDepartamento==16 and $idPersonal==1)) ) { ?>
									<input type="submit" name="sig_estado" class="button fit" value="siguiente etapa"> 
								<?php } ?>
							</form>
						</div>
					</div>
					
					<div class="row uniform">
						<div class="6u 12u$(xsmall)">
							<div class="box">
								<ul class="alt">
									<li><h4>Folio de caso: </h4><a href="perfil_caso.php?id=<?= $idCaso;?>"><?= $folio_c ?></a> </li>
									<?php if($numRep>0) { ?><li><h4>Reporte(s) del que deriva: </h4>
										<?php while ($rwRep=$qReportes->fetch_assoc()) { ?><a href="perfil_posible_caso.php?idPosibleCaso=<?= $rwRep['idPc'];?>"><?= $rwRep['folio'] ?> </a> <?= " - "?><?php } ?>
										</li> <?php } ?>
									<li><h4>Fecha de inicio: </h4><?= $fecha_ini?> </li>
									<li><h4>Distrito judicial: </h4><?= $distrits?> </li>
									
								</ul>
							</div>
						</div>
						<div class="6u 12u$(xsmall)">
							<div class="box">
								<ul class="alt">
									<li><h4>Delito: </h4><?=$delito?> </li>
									<li><h4>Municipio del delito: </h4><?= $municipio?> </li>
									<li><h4>Mesa: </h4><?=$mesa?> </li>
									<?php if($idDepartamento==16 and $idPersonal==1)  ?>
									<?php if($idDEPTO==$respo_reg or $idDEPTO==$idAsignado or ($idDepartamento==16 and $idPersonal==1)) { echo "
									<li><a href="."editar_carpeta.php?id=".$idCarpeta.">Editar</a></li>";
								} ?>
								</ul>
							</div>
						</div>
					</div><br>

					<div class="box">
						<div class="row uniform">
							<div class="10u">
								<h4>Imputado(s)</h4>
							</div>
							<div class="2u">
								<input type="button" name="" class="button fit special" value="Agregar" onclick="location='reasignar_car.php?id=<?php echo $idCarpeta;?>'" >
							</div>
						</div>
					</div>

					<div class="box">
						<div class="table-wrapper"><h4>VICTIMAS</h4>
							<?php if ($rowNE>0) { ?> <!--niños no expositos en el caso-->
								<table class="alt">								
									<thead>
										<tr>
											<th>FOLIO</th>
											<th>NOMBRE</th>
											<th>EDAD</th>
											<th>SEXO</th>
											<th></th>												
										</tr>
									</thead>
									<tbody>
										<?php while($row=$esqlnna->fetch_assoc()){ ?>
											<tr>
												<td><?php echo $row['folio'];?></td>
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
													<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
														<input type="hidden" name="id_NNA" value="<?php echo $row['id'];?>">
														<?php $id_nn=$row['id'];
														$carita="SELECT id_nna, id_carp, estado from victimas_c_inv where id_nna='$id_nn' and id_carp='$idCarpeta' and estado='NE'";
														$ecarita=$mysqli->query($carita);
														$rowa=$ecarita->num_rows; 
														if ($rowa>0) { ?>
															<input type="button"  name="" value="DIRECTA">
														<?php }else { ?>
															<input type="submit"  name="victimaNE" value="INDIRECTA">
														<?php } ?>
													</form>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							<?php } if ($rowE>0) { ?> <!--Niños expositos en el caso-->
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
												<td>
													<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
														<input type="hidden" name="id_NNAE" value="<?php echo $row['id'];?>">
														<?php $id_nn=$row['id'];
														$carita="SELECT id_nna, id_carp, estado from victimas_c_inv where id_nna='$id_nn' and id_carp='$idCarpeta' and estado='E'";
														$ecarita=$mysqli->query($carita);
														$rowa=$ecarita->num_rows; 
														if ($rowa>0) { ?>
															<input type="button"  name="" value="DIRECTA">
														<?php }else { ?>
															<input type="submit"  name="victimaE" value="INDIRECTA">
														<?php } ?>
													</form>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							<?php } ?>
						</div>
					</div>
					<?php 
					$consulta="SELECT carpeta_inv.tipo_pross, departamentos.responsable 
					from departamentos, carpeta_inv where carpeta_inv.id='$idCarpeta' 
					and carpeta_inv.respo_tipo=departamentos.id";
					$eco=$mysqli->query($consulta);
					while ($row=$eco->fetch_assoc()) {
						$tipoP=$row['tipo_pross'];
						$rr=$row['responsable'];
					} ?>
					<div class="12u$">
						<input type="button" name="asignar_curso" class="button fit" value="audiencias" onclick="location='audienciaxcarpeta.php?id=<?php echo $idCarpeta;?>'">	
					</div>
					<?php if ($tipoP==0) { ?>
						<div class="row uniform">
							<div class="6u 12u$(xsmall)">
								<input type="button" name="asignar_curso" class="button special fit" value="terminar investigación" onclick="location='terminar_inves.php?id=<?php echo $idCarpeta;?>'">
							</div>
							<div class="6u 12u$(xsmall)">
								<?php if ($idAsignado==$idDEPTO) { ?>
									<input type="button" name="asignar_curso" class="button special fit" value="Editar" onclick="location='editar_carpeta.php?id=<?php echo $idCarpeta;?>'">
								<?php }else if ($respo_reg==$idDEPTO) { ?>
									<input type="button" name="asignar_curso" class="button special fit" value="Editar" onclick="location='editar_carpeta.php?id=<?php echo $idCarpeta;?>'">
								<?php }else if ($idDepartamento==16 and $idPersonal==1) { ?>
									<input type="button" name="asignar_curso" class="button special fit" value="Editar" onclick="location='editar_carpeta.php?id=<?php echo $idCarpeta;?>'">
								<?php }else { ?>
									<input type="button" name="asignar_curso" class="button special fit" value="Editar">
								<?php } ?>
							</div>
							<div class="6u 12u$(xsmall)">
								<input type="button" name="asignar_curso" class="button fit" value="solucion alterna o terminacion anticipada" onclick="location='solucion.php?id=<?php echo $idCarpeta;?>'">
							</div>
							
							<div class="6u 12u$(xsmall)">
								
							</div>
							<?php if ($_SESSION['departamento']==16){ ?>
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
									<div class="12u 12u$(xsmall)">
										<input type="submit" name="eliminar" value="eliminar" class="button fit">
									</div>
								</form>
							<?php } ?>
						</div><br><br>
					<?php } else{ ?>
						<br>
						<div class="box">
							<div class="row uniform">
								<br>
								<div class="12u$">
									<?php if ($tipoP==1) { ?>
										<h3>Investigación terminada</h3>		
	                   					<h2>Archivo temporal</h2>
	                   				<?php }else if($tipoP==2){ ?>
	                   					<h3>Investigación terminada</h3>
										<h2>Facultad de abstenerse de investigar</h2>
	                   				<?php }else if($tipoP==3){ ?>
	                   					<h3>Investigación terminada</h3>
										<h2>No ejercicio de la accion</h2>
	                   				<?php }else if($tipoP==4){ ?>
	                   					<h3>Investigación terminada</h3>
										<h2>Casos en los que operan los criterios de oportunidad</h2>
	                   				<?php }else if($tipoP==5){ ?>
	                   					<h3>Solución alterna</h3>
										<h2>Acuerdo reparatorio</h2>
	                   				<?php }else if($tipoP==6){ ?>
	                   					<h3>Solución alterna</h3>
										<h2>Suspensión condicional del proceso antes del juicio</h2>
	                   				<?php }else if($tipoP==7){ ?>
	                   					<h3>Terminación anticipada</h3>
	                   					<h2>Procedimiento abreviado</h2>
	                   				<?php }else if($tipoP==8){ ?>
	                   					<h3>Investigación terminada</h3>
										<h2>Incompetencia</h2>
	                   				<?php } ?>
	                   					<h4>Dictado por: <?php echo $rr; ?></h4>
								</div>
								<br><br>
							</div>
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