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

	$idPc=$_GET['id'];
	$qPC="SELECT pc.folio, date_format(pc.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, pc.activo, d.responsable 
		FROM posible_caso pc inner join departamentos d on d.id=pc.responsable_registro where pc.id=$idPc";
	$rPc=$mysqli->query($qPC);
	while($rwPc=$rPc->fetch_assoc()){
		$folioPc=$rwPc['folio'];
		$fechaPc=$rwPc['fecha_reg'];
		if($rwPc['activo']==1)
			$statusPc="Activo";
		else 
			$statusPc="Inactivo";
		$respoPc=$rwPc['responsable'];
	}

	//nna
	$qNnas="SELECT nna_reportados.id as idNna, nombre, apellido_p, apellido_m, sexo.sexo, 
	date_format(nna_reportados.fecha_nacimiento,'%d/%m/%Y') as fecha_nac, 
	lugar_nacimiento, edad, nna_reportados.activo, 
	date_format(fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, 
	d.responsable 
	from nna_reportados left join sexo on nna_reportados.sexo=sexo.id 
	inner join departamentos d on d.id=nna_reportados.responsable_registro 
	where id_posible_caso='$idPc'";
	$rNnas=$mysqli->query($qNnas);

	//asignaciones juridico 
	$qjuridico="SELECT responsable as asigJuridico, date_format(fecha_asignacion,'%d/%m/%Y %H:%i:%s') as fec_juridico
		from historico_asignaciones_juridico inner join departamentos on id_departamentos_asignado=departamentos.id
		where id_posible_caso=$idPc";
	$rJuridico=$mysqli->query($qjuridico);
	//asignaciones ts 
	$qts="SELECT responsable as asigTS, date_format(fecha_asignacion,'%d/%m/%Y %H:%i:%s') as fecTs
		from historico_asignaciones_trabajo_social inner join departamentos on id_departamentos_asignado=departamentos.id
		where id_posible_caso=$idPc";
	$rts=$mysqli->query($qts);
	//asignaciones ts 
	$qpsico="SELECT responsable as asigPs, date_format(fecha_asignacion,'%d/%m/%Y %H:%i:%s') as fecPs
		from historico_asignaciones_psicologia inner join departamentos on id_departamentos_asignado=departamentos.id
		where id_posible_caso=$idPc";
	$rpsico=$mysqli->query($qpsico);
	//estado de atencion
	$qatenciones="SELECT d.responsable, h.estadoAtencion, 
		date_format(h.fechaAtencion, '%d/%m/%Y %H:%i:%s') as fechaAte
		from historico_atenciones_pos_casos h 
		inner join departamentos d on d.id=h.id_departamentos
		where id_posible_caso=$idPc";
	$ratenciones=$mysqli->query($qatenciones);

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
                    <div class="row uniform">
                    	<div class="10u"><h2>Historial del Posible Caso <?= $folioPc?> </h2></div>
                    	<div class="2u">
                    		<input type="button" class="button special small" name="regresar" value="Regresar" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?=$idPc?>'" >
                    	</div>
                    </div>
                    <div class="row uniform">
                    	<div class="4u">
                    		<b>Fecha y hora de registro: </b><?= $fechaPc ?>
                    	</div>
                    	<div class="5u">
                    		<b>Responsable de registro: </b><?= $respoPc ?>
                    	</div>
                    	<div class="3u">
                    		<b>Estatus: </b><?= $statusPc ?>
                    	</div>
                    </div>
                    <div class="box">
                    	<div class="row uniform">
                    		<div class="12u"><h4>NNA vinculados</h4></div>
                    	</div>
                    	<table>
                    		<thead>
                    			<tr>								
									<td><b>Nombre</b></td>
									<td><b>Sexo </b></td>
									<td><b>Fecha de nacimiento</b></td>
									<td><b>Lugar de nacimiento </b></td>
									<td><b>Edad</b></td>	
									<td><b>Estatus</b></td>		
									<td><b>Fecha de registro</b></td>
									<td><b>Responsable de registro</b></td>	
								</tr>
							</thead>
							<body>
								<?php while ($row=$rNnas->fetch_assoc()){
									$qName="SELECT id from relacion_names where id_nna_reportado='$row[idNna]' and activo=1";
									$rName=$mysqli->query($qName);
									$numName= $rName->num_rows; ?>
									<tr>
										<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
										<td><?php echo $row["sexo"]; ?></td>
										<td><?php if($row["fecha_nac"]=="01/01/1900") echo ""; else echo $row["fecha_nac"]; ?> </td>
										<td><?php echo $row["lugar_nacimiento"]; ?></td>
										<td><?php echo $row["edad"]; ?> </td>
										<td><?= $row['activo'] ?></td>
										<td><?= $row['fecha_reg'] ?></td>
										<td><?= $row['responsable'] ?></td>
									</tr>
								<?php } ?>
							</body>
						</table>										
                    </div>
                    <div class="box">	
                    	<div class="row uniform">
                    		<div class="12u"><h4>Historial de asignaciones</h4></div>
                    	</div>
                    	<div class="row uniform">
                    		<div class="12u"><h5>Asignacione de juridico</h5></div>
                    	</div>
                    	<table>
                    		<thead>
                    			<tr>								
									<td><b>Responsable asignado</b></td>									
									<td><b>Fecha de asignación</b></td>	
								</tr>
							</thead>
							<body>
								<?php while ($row1=$rJuridico->fetch_assoc()){?>
									<tr>
										<td><?= $row1['asigJuridico']?></td>
										<td><?= $row1["fec_juridico"]; ?></td>
									</tr>
								<?php } ?>
							</body>
						</table>
						<div class="row uniform">
                    		<div class="12u"><h5>Asignacione de trabajo social</h5></div>
                    	</div>
                    	<table>
                    		<thead>
                    			<tr>								
									<td><b>Responsable asignado</b></td>									
									<td><b>Fecha de asignación</b></td>	
								</tr>
							</thead>
							<body>
								<?php while ($row2=$rts->fetch_assoc()){?>
									<tr>
										<td><?= $row2['asigTS']?></td>
										<td><?= $row2["fecTs"]; ?></td>
									</tr>
								<?php } ?>
							</body>
						</table>
						<div class="row uniform">
                    		<div class="12u"><h5>Asignacione de psicología</h5></div>
                    	</div>
                    	<table>
                    		<thead>
                    			<tr>								
									<td><b>Responsable asignado</b></td>									
									<td><b>Fecha de asignación</b></td>	
								</tr>
							</thead>
							<body>
								<?php while ($row3=$rpsico->fetch_assoc()){?>
									<tr>
										<td><?= $row3['asigPs']?></td>
										<td><?= $row3["fecPs"]; ?></td>
									</tr>
								<?php } ?>
							</body>
						</table>
                    </div>
                    <div class="box">
                    	<div class="row uniform">
                    		<div class="12u"><h4>Historial de estados de atención</h4></div>
                    	</div>
                    	<table>
                    		<thead>
                    			<tr>								
									<td><b>Responsable del cambio de estado</b></td>						
									<td><b>Fecha de cambio</b></td>	
									<td><b>Estado de atención</b></td>
								</tr>
							</thead>
							<body>
								<?php while ($row4=$ratenciones->fetch_assoc()){?>
									<tr>
										<td><?= $row4['responsable']?></td>
										<td><?= $row4['fechaAte']; ?></td>
										<td><?php if($row4['estadoAtencion']==1) 
												echo "No atendido";
											else if($row4['estadoAtencion']==2)
												echo "En proceso";
											else if($row4['estadoAtencion']==3)
												echo "Atendido negativo";
											else if($row['estadoAtencion']==4)
												echo "Atendido positivo";?></td>
									</tr>
								<?php } ?>
							</body>
						</table>
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