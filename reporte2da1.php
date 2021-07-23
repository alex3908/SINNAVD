<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}	
	$idDEPTO = $_SESSION['id'];
	$mes=$_SESSION['mes'];
	$area=$_SESSION['area'];
	$persona=$_SESSION['persona'];
	
	$vid="SELECT id from departamentos where id_depto='$area'";
	$evid=$mysqli->query($vid);
	
	if ($persona=='Todos') {
		$losR=$persona;
	}else {
		$losrr="SELECT responsable from departamentos where id=$persona";
		$elosrr=$mysqli->query($losrr);
		
		while ($row=$elosrr->fetch_assoc()) {
			$losR=$row['responsable'];
		}
	}

	if ($persona=='Todos') {
		$idRespo='';
	while ($row=$evid->fetch_assoc()) {	
		$idRespo="'".$row['id']."',".$idRespo;
	}
	$idRespo=trim($idRespo, ',');
	}else {
		$idRespo="'".$persona."'";
	}
	$deee="SELECT departamento from depto where id='$area'";
	$edeee=$mysqli->query($deee);

	$pf="SELECT fechas, mes, fechai, fechac from cortes where id=$mes";
	$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$mesesito=$row['mes'];
				$feci=$row['fechai'];
				$fecc=$row['fechac'];
			}
?>

<!DOCTYPE HTML>

<html>
	<head lang="Es-es">
		<title>Informe mensual</title>
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
						<div class="inner">
					<br>
	<div class="row uniform">
	<div class="1u 12u$(xsmall)"><a href="reporte1ra.php">NUEVO</a></div>
	<div class="10u 12u$(xsmall)"></div>
	<div class="1u 12u$(xsmall)"><a href="welcome.php">TERMINAR</a></div>
	</div>
	
				<table class="alt">				
						<tr>
							<td colspan="3" align="center"><strong>Reporte</strong></td>
						</tr>
						<tr>							
							<td><strong><?php echo $mesesito; ?></strong></td>
							<td><?php while ($row=$edeee->fetch_assoc()) { ?>
								<strong><?php echo $row['departamento']; ?></strong>
								<?php } ?></td>
							<td><strong><?php echo $losR; ?></strong></td>
						</tr>
				</table>
				
	<div class="box"> 
		<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
		<div class="row uniform">	
		<?php 
				$ind1="SELECT id, nombre, apellido_p, apellido_m, curp 
				from usuarios where respo_reg in($idRespo)  and fecha_registro BETWEEN '$feci' and '$fecc' and activo=1";
				$eind1=$mysqli->query($ind1);
				$row1=$eind1->num_rows; //usuarios registrados

				$ind2="SELECT usuarios.id, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, historial.atencion_brindada, historial.fecha_ingreso, historial.fecha_salida from historial left join  usuarios on usuarios.id=historial.id_usuario where  substring(historial.fecha_ingreso,1,LOCATE(' ', historial.fecha_ingreso)-1) in ($fff) and historial.responsable in ($idRespo) and historial.fecha_salida is not null";				
				$eind2=$mysqli->query($ind2);
				$row2=$eind2->num_rows;

				$ind3="SELECT pc.id as idPc, r.id, r.folio, cr.recepcion, r.narracion, 
				date_format(r.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, hpc.estadoAtencion as atendido
				from reportes_vd r inner join cat_recepcion_reporte cr on r.id_recepcion=cr.id
				inner join posible_caso pc on pc.id=r.id_posible_caso
				left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion
				where respo_reg in($idRespo) 
				and r.fecha_registro BETWEEN  '$feci' and '$fecc' and r.activo=1";
				$eind3=$mysqli->query($ind3);
				$row3=$eind3->num_rows; // reportes registrados

				$ind4="SELECT pc.id as idPc, r.folio, cr.recepcion, date_format(r.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, hpc.estadoAtencion as atendido
				from reportes_vd r inner join posible_caso pc on pc.id=r.id_posible_caso
				inner join cat_recepcion_reporte cr on r.id_recepcion=cr.id
				left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion
				left join historico_asignaciones_juridico hj on hj.id=pc.id_asignado_juridico
				left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts
				left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps
				where r.activo=1 and (hj.fecha_asignacion between '$feci' and '$fecc' and hj.id_departamentos_asignado in($idRespo))
				or (hts.fecha_asignacion between '$feci' and '$fecc' and hts.id_departamentos_asignado in($idRespo))
				or (hps.fecha_asignacion between '$feci' and '$fecc' and hps.id_departamentos_asignado in($idRespo))";
				$eind4=$mysqli->query($ind4);
				$row4=$eind4->num_rows;

				$ind5="SELECT af.id, pc.id as rep, pc.folio, af.observaciones, 
				date_format(af.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, 
				date_format(af.fecha_acercamiento,'%d/%m/%Y') as fecha_acerca, af.inter 
				from acercamiento_familiar af left join posible_caso pc on pc.id=af.id_reporte
				where af.activo=1 and af.fecha_registro between '$feci' and '$fecc'
				and af.respo_reg in($idRespo) 
				and af.inter is not null";
				$eind5=$mysqli->query($ind5);
				$datosInd51=$mysqli->query($ind5);
				$row5=$eind5->num_rows;
				$ind51="SELECT sum(inter) as total 
				from acercamiento_familiar 
				where activo=1 and fecha_registro between '$feci' and '$fecc'
				and respo_reg in($idRespo)  and inter is not null";
				$eind51=$mysqli->query($ind51);
					while ($row=$eind51->fetch_assoc()) {
						$row51=$row['total'];
					}

				$ind6="SELECT ap.id, pc.folio, pc.id as rep, nna_ac.nombre, nna_ac.apellido_p, nna_ac.apellido_m, 
					date_format(ap.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, date_format(ap.fecha_acercamiento, '%d/%m/%Y') as fecha_acps, ap.otros 
					FROM acercamiento_psic ap inner join nna_ac on nna_ac.id=ap.id_nna 
					inner join posible_caso pc  on pc.id=ap.id_reporte 
					where ap.activo=1 and ap.fecha_registro between '$feci' and '$fecc'
					and ap.respo_reg in($idRespo) and ap.otros is not null";
				$eind6=$mysqli->query($ind6);
				$row6=$eind6->num_rows;

				$ind7="SELECT pc.id as idPc, r.folio, cr.recepcion, 
				date_format(r.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, r.narracion,
				hpc.estadoAtencion as atendido
				from reportes_vd r inner join posible_caso pc on pc.id=r.id_posible_caso 
				inner join cat_recepcion_reporte cr on r.id_recepcion=cr.id 
				left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion 
				left join historico_asignaciones_juridico hj on hj.id=pc.id_asignado_juridico 
				left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts 
				left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps 
				where r.activo=1 and (hpc.fechaAtencion between '$feci' and '$fecc') 
				and (hj.id_departamentos_asignado in($idRespo) 
				or hts.id_departamentos_asignado in($idRespo)
				or hps.id_departamentos_asignado in($idRespo)) and hpc.estadoAtencion in('2','3','4')";
				$eind7=$mysqli->query($ind7);
				$row7=$eind7->num_rows;

				$ind8="SELECT pc.id as idPc, r.folio, cr.recepcion, 
				date_format(r.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, r.narracion,
				hpc.estadoAtencion as atendido
				from reportes_vd r inner join posible_caso pc on pc.id=r.id_posible_caso 
				inner join cat_recepcion_reporte cr on r.id_recepcion=cr.id 
				left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion 
				left join historico_asignaciones_juridico hj on hj.id=pc.id_asignado_juridico 
				left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts 
				left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps 
				where r.activo=1 and (hpc.fechaAtencion between '$feci' and '$fecc') 
				and (hj.id_departamentos_asignado in($idRespo) 
				or hts.id_departamentos_asignado in($idRespo)
				or hps.id_departamentos_asignado in($idRespo)) and hpc.estadoAtencion in('4')";
				$eind8=$mysqli->query($ind8);
				$row8=$eind8->num_rows;

				$ind9="SELECT pc.id as idPc, r.folio, cr.recepcion, 
				date_format(r.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, r.narracion,
				hpc.estadoAtencion as atendido
				from reportes_vd r inner join posible_caso pc on pc.id=r.id_posible_caso 
				inner join cat_recepcion_reporte cr on r.id_recepcion=cr.id 
				left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion 
				left join historico_asignaciones_juridico hj on hj.id=pc.id_asignado_juridico 
				left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts 
				left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps 
				where r.activo=1 and ((hpc.fechaAtencion between '$feci' and '$fecc') 
				and hpc.estadoAtencion in('1') and (hj.id_departamentos_asignado in($idRespo) 
				or hts.id_departamentos_asignado in($idRespo)
				or hps.id_departamentos_asignado in($idRespo))) or (pc.id_estado_atencion=0 
				and ((hj.fecha_asignacion between '$feci' and '$fecc' 
				and hj.id_departamentos_asignado in($idRespo)) 
				or (hts.fecha_asignacion between '$feci' and '$fecc' 
				and hts.id_departamentos_asignado in($idRespo)) 
				or (hps.fecha_asignacion between '$feci' and '$fecc'
				and hps.id_departamentos_asignado in($idRespo))))";
				$eind9=$mysqli->query($ind9);
				$row9=$eind9->num_rows;

				$ind10="SELECT id, folio_c, nombre, descripcion, date_format(fecha_registro , '%d/%m/%Y %H:%i:%s') as fecha
					from casos 
					where activo=1 and funcionario_reg in($idRespo) and 
					fecha_registro between '$feci' and '$fecc'";
				$eind10=$mysqli->query($ind10);
				$row10=$eind10->num_rows;

				$ind11="SELECT nna.id, nna.folio, nna.apellido_p, nna.apellido_m, nna.nombre, nna.curp, 
				date_format(nna.fecha_nacimiento, '%d/%m/%Y') as fecha_nac, validacionRenapo,
				nna.sexo, municipios.municipio, localidades.localidad, indigena, afrodescendiente, migrante
				from nna left join municipios on municipios.id=nna.municipio
				left join localidades on localidades.id=nna.localidad
				where nna.activo=1 and fecha_registro between '$feci' and '$fecc' and nna.respo_reg in ($idRespo)";
				$eind11=$mysqli->query($ind11);
				$row11=$eind11->num_rows;

				$ind111="SELECT nna.id, nna.folio, nna.apellido_p, nna.apellido_m, nna.nombre, nna.curp, 
				date_format(nna.fecha_nacimiento, '%d/%m/%Y') as fecha_nac, validacionRenapo,
				nna.sexo, municipios.municipio, localidades.localidad, indigena, afrodescendiente, migrante
				from nna left join municipios on municipios.id=nna.municipio
				left join localidades on localidades.id=nna.localidad
				where nna.activo=1 and fecha_registro between '$feci' and '$fecc' and nna.respo_reg in ($idRespo) and (nna.validacionRenapo=0 or nna.validacionRenapo is null)";
				$eind111=$mysqli->query($ind111);
				$row111=$eind111->num_rows;

				$ind12="SELECT carpeta_inv.id, carpeta_inv.nuc, casos.folio_c, delitos.delito, date_format(carpeta_inv.fecha_inicio,'%d/%m/%Y') as fecha_ini 
				from carpeta_inv inner join casos on carpeta_inv.id_caso=casos.id
				inner join delitos on delitos.id=carpeta_inv.id_delito 
				where carpeta_inv.fecha_registro between '$feci' and '$fecc' 
				and carpeta_inv.respo_reg in($idRespo)";
				$eind12=$mysqli->query($ind12);
				$row12=$eind12->num_rows;

				$ind13="SELECT carpeta_inv.id, carpeta_inv.nuc, casos.folio_c, delitos.delito, date_format(carpeta_inv.fecha_inicio,'%d/%m/%Y') as fecha_ini 
				from carpeta_inv inner join casos on carpeta_inv.id_caso=casos.id
				inner join delitos on delitos.id=carpeta_inv.id_delito 
				where carpeta_inv.fecha_registro between '$feci' and '$fecc' 
				and carpeta_inv.asignado in($idRespo)";
				$eind13=$mysqli->query($ind13);
				$row13=$eind13->num_rows;

				$ind14="SELECT casos.id, casos.folio_c, derechos_nna.derecho, medidas.medida_p, catalogo_medidas.medidaC, 
				nna.nombre, nna.apellido_p, nna.apellido_m, cuadro_guia.responsable_med, 
				date_format(cuadro_guia.fecha_registro, '%d/%m/%Y') as fecha, cuadro_guia.estado 
				from  casos inner join cuadro_guia on casos.id=cuadro_guia.id_caso
				left join benefmed on cuadro_guia.id=benefmed.id_medida
				left join derechos_nna on derechos_nna.id=cuadro_guia.id_derecho
				left join nna on benefmed.id_nna=nna.id 
				left join medidas on medidas.id=cuadro_guia.id_medida 
				left join catalogo_medidas on catalogo_medidas.id=cuadro_guia.id_mp 
				where cuadro_guia.id_medida in('03') and cuadro_guia.id_sp_registro in($idRespo) 
				and (cuadro_guia.fecha_registro BETWEEN '$feci' and '$fecc') and cuadro_guia.activo=1";
				$eind14=$mysqli->query($ind14);
				$row14=$eind14->num_rows;

				$ind15="SELECT casos.id, casos.folio_c, derechos_nna.derecho, medidas.medida_p, catalogo_medidas.medidaC,
				nna.nombre, nna.apellido_p, nna.apellido_m, cuadro_guia.responsable_med, 
				date_format(cuadro_guia.fecha_registro, '%d/%m/%Y') as fecha, cuadro_guia.estado 
				from  casos inner join cuadro_guia on casos.id=cuadro_guia.id_caso 
				left join benefmed on cuadro_guia.id=benefmed.id_medida  
				left join derechos_nna on derechos_nna.id=cuadro_guia.id_derecho
				left join nna on benefmed.id_nna=nna.id
				left join medidas on medidas.id=cuadro_guia.id_medida  
				left join catalogo_medidas on catalogo_medidas.id=cuadro_guia.id_mp
				where cuadro_guia.id_medida in('03') and cuadro_guia.estado=1 and cuadro_guia.id_sp_registro 
				in($idRespo) and (cuadro_guia.fecha_ejecucion BETWEEN '$feci' and '$fecc')
				and cuadro_guia.activo=1";
				$eind15=$mysqli->query($ind15);
				$row15=$eind15->num_rows;

				$ind16="SELECT casos.id, casos.folio_c, derechos_nna.derecho, medidas.medida_p, catalogo_medidas.medidaC, 
				nna.nombre, nna.apellido_p, nna.apellido_m, cuadro_guia.responsable_med, 
				date_format(cuadro_guia.fecha_registro, '%d/%m/%Y') as fecha, cuadro_guia.estado 
				from  casos inner join cuadro_guia on casos.id=cuadro_guia.id_caso
				inner join benefmed on cuadro_guia.id=benefmed.id_medida
				inner join derechos_nna on derechos_nna.id=cuadro_guia.id_derecho
				inner join nna on benefmed.id_nna=nna.id 
				inner join medidas on medidas.id=cuadro_guia.id_medida 
				inner join catalogo_medidas on catalogo_medidas.id=cuadro_guia.id_mp 
				where cuadro_guia.id_medida in('01') and cuadro_guia.id_sp_registro in($idRespo) 
				and (cuadro_guia.fecha_registro BETWEEN '$feci' and '$fecc') and cuadro_guia.activo=1";
				$eind16=$mysqli->query($ind16);
				$row16=$eind16->num_rows;

				$ind17="SELECT casos.id, casos.folio_c, derechos_nna.derecho, medidas.medida_p, catalogo_medidas.medidaC,
				 nna.nombre, nna.apellido_p, nna.apellido_m, cuadro_guia.responsable_med, 
				 date_format(cuadro_guia.fecha_registro, '%d/%m/%Y') as fecha, 
				 cuadro_guia.estado from  casos inner join cuadro_guia on casos.id=cuadro_guia.id_caso
				left join benefmed on cuadro_guia.id=benefmed.id_medida
				left join derechos_nna on derechos_nna.id=cuadro_guia.id_derecho
				left join nna on benefmed.id_nna=nna.id 
				left join medidas on medidas.id=cuadro_guia.id_medida 
				left join catalogo_medidas on catalogo_medidas.id=cuadro_guia.id_mp 
				where cuadro_guia.id_medida in('01') and cuadro_guia.estado=1 
				and cuadro_guia.id_sp_registro in($idRespo) 
				and (cuadro_guia.fecha_ejecucion BETWEEN '$feci' and '$fecc') 
				and cuadro_guia.activo=1";
				$eind17=$mysqli->query($ind17);
				$row17=$eind17->num_rows;

				$ind18="SELECT casos.id as idC, cuadro_guia.id as idM, casos.folio_c, nna.nombre, nna.apellido_p, nna.apellido_m, 
				catalogo_medidas.medidaC, seguimientos.area, seguimientos.seguimiento, date_format(seguimientos.fecha_registro, '%d/%m/%Y') as fecha 
				from seguimientos inner join cuadro_guia on seguimientos.id_med=cuadro_guia.id 
				left join catalogo_medidas on catalogo_medidas.id=cuadro_guia.id_mp 
				inner join casos on casos.id=cuadro_guia.id_caso
				left join benefmed on benefmed.id_medida=cuadro_guia.id
				left join nna on nna.id=benefmed.id_nna  
				where seguimientos.respo_reg in($idRespo) and (seguimientos.fecha_registro BETWEEN '$feci' and '$fecc') and seguimientos.activo=1";
				$eind18=$mysqli->query($ind18);
				$row18=$eind18->num_rows;

				$ind19="SELECT nna.id, nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, nna.curp, nna_restituidos.fecha_reg from nna_restituidos
				inner join nna on nna.id=nna_restituidos.id_nna where nna_restituidos.fecha_reg in($fff) and nna_restituidos.respo_reg in($idRespo) ";
				$eind19=$mysqli->query($ind19);
				$row19=$eind19->num_rows;
				
					  ?>			
			<div class="4u 12u$(xsmall)">
				<ul class="alt">
					<li><input type="submit"  name="I1" class="button fit small" value="1. Usuarios registrados: <?php echo $row1; ?>"></li>
					<li><input type="submit"  name="I2" class="button fit small" value="2. Visitas de usuarios atendidas: <?php echo $row2; ?>"></li>
					<li><input type="submit"  name="I3" class="button fit small" value="3. Reportes registrados: <?php echo $row3; ?>"></li>
					<li><input type="submit"  name="I4" class="button fit small" value="4. Reportes asignados: <?php echo $row4; ?>"></li>
					<li><input type="submit"  name="I5" class="button fit small" value="5. Acercamientos familiares: <?php echo $row5; ?>"></li>	
					<li><input type="submit"  name="I51" class="button fit small" value="5.1 Intervenciones: <?php echo $row51; ?>"></li>	
					<li><input type="submit"  name="I6" class="button fit small" value="6. Acercamientos con NNA: <?php echo $row6; ?>"></li>
				</ul>		
			</div>
			<div class="4u 12u$(xsmall)">
				<ul class="alt">	
					<li><input type="submit"  name="I7" class="button fit small" value="7. Reportes atendidos: <?php echo $row7; ?>"></li>
					<li><input type="submit"  name="I8" class="button fit small" value="8. Reportes positivos: <?php echo $row8; ?>"></li>
					<li><input type="submit"  name="I9" class="button fit small" value="9. Reportes no atendidos: <?php echo $row9; ?>"></li>			
					<li><input type="submit"  name="I10" class="button fit small" value="10. Casos registrados: <?php echo $row10; ?>"></li>
					<li><input type="submit"  name="I11" class="button fit small" value="11. NNA registrados: <?php echo $row11; ?>"></li>
					<li><input type="submit"  name="I111" class="button fit small" value="11.1 NNA registrados SIN CURP validada: <?php echo $row111; ?>"></li>
					<li><input type="submit"  name="I12" class="button fit small" value="12. Carpetas registradas: <?php echo $row12; ?>"></li>	
				</ul>		
			</div>
			<div class="4u 12u$(xsmall)">
				<ul class="alt">	
					<li><input type="submit"  name="I13" class="button fit small" value="13. Carpetas asignadas: <?php echo $row13; ?>"></li>	
					<li><input type="submit"  name="I14" class="button fit small" value="14. Medidas especiales decretadas: <?php echo $row14; ?>"></li>
					<li><input type="submit"  name="I15" class="button fit small" value="15. Medidas especiales ejecutadas: <?php echo $row15; ?>"></li>
					<li><input type="submit"  name="I16" class="button fit small" value="16. Medidas urgentes decretadas: <?php echo $row16; ?>"></li>
					<li><input type="submit"  name="I17" class="button fit small" value="17. Medidas urgentes ejecutadas: <?php echo $row17; ?>"></li>
					<li><input type="submit"  name="I18" class="button fit small" value="18. Seguimientos ejecutados: <?php echo $row18; ?>"></li>
					<li><input type="submit"  name="I19" class="button fit small" value="19. NNA con derechos restituidos: <?php echo $row19; ?>"></li>
				</ul>		
			</div>
		</div>
	</form>
	</div>
<?php  if(!empty($_POST['I1'])){ ?>
		<div class="box">
			<div class="row uniform">
			<div class="12u 12u$(xsmall)">	
			<?php if ($row1==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>		
			<table><caption>Usuarios registrados</caption>
				<tr>
					<td><b>Folio</b></td>
					<td><b>Nombre</b></td>
					<td><b>Apellido paterno</b></td>
					<td><b>Apellido materno</b></td>
					<td><b>CURP</b></td>
				</tr>
				<tbody>
				<?php while ($row=$eind1->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['id']; ?></td>										
					<td><?php echo $row['nombre']; ?></td>										
					<td><?php echo $row['apellido_p']; ?></td>										
					<td><?php echo $row['apellido_m']; ?></td>										
					<td><?php echo $row['curp']; ?></td>									
				</tr>
				<?php }  ?>
				
				
				</tbody>
			</table>
		<?php } ?>
			</div>
			</div>
		</div>	
	<?php } if(!empty($_POST['I2'])){ ?>
		<div class="box">	
		<?php if ($row2==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Visitas de usuarios atendidas</caption>
					
				<tr>
					<td><b>Folio</b></td>
					<td><b>Nombre</b></td>
					<td><b>Atención brindada</b></td>
					<td><b>Fecha de ingreso</b></td>
					<td><b>Fecha de salida</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind2->fetch_assoc()) { ?>							
				<tr>
					<td><a href="historial_usuario.php?id=<?php echo $row['id']; ?>"><?php echo $row['id']; ?></a></td>					
					<td><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?></td>
					<td><?php echo $row['atencion_brindada']; ?></td>					
					<td><?php echo $row['fecha_ingreso']; ?></td>					
					<td><?php echo $row['fecha_salida']; ?></td>										
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>	
	<?php } if(!empty($_POST['I3'])){ ?>
		<div class="box">	
		<?php if ($row3==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Reportes registrados</caption>
					
				<tr>
					<td><b>Folio</b></td>
					<td><b>Forma de recepcion</b></td>
					<td><b>Nombre de los NNA</b></td>					
					<td><b>Fecha</b></td>
					<td><b>Estatus</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind3->fetch_assoc()) { $idPc=$row['idPc'];?>							
				<tr>
					<td><a href="perfil_posible_caso.php?idPosibleCaso=<?= $idPc?>"><?php echo $row['folio']; ?></a></td>					
					<td><?php echo $row['recepcion']; ?></td>
					<td><?php $Qnna="SELECT nombre, apellido_p, apellido_m from nna_reportados where id_posible_caso=$idPc and activo=1";
					$Rnna=$mysqli->query($Qnna);
					while ($rwNna=$Rnna->fetch_assoc()) {
					 		echo $rwNna['nombre']." ".$rwNna['apellido_p']." ".$rwNna['apellido_m'].", ";
					 } ?></td>					
					<td><?php echo $row['fecha']; ?></td>					
					<td align="center" valign="middle">
						<?php $atend=$row['atendido'];
							if ($atend=='1' or empty($atend)) { ?>
						<img src="images/advertencia.png" width="50px" height="50px">
						<?php }else if ($atend=='2') { ?>
						<img src="images/proceso.png" width="65px" height="65px">
						<?php }else if ($atend=='3') { ?>
						<img src="images/Anegativo.png" width="65px" height="65px">
						<?php }else if ($atend=='4') { ?>
						<img src="images/Apositivo.png" width="65px" height="65px"	>
						<?php } ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>	
	<?php } if(!empty($_POST['I4'])){ ?>
		<div class="box">	
		<?php if ($row4==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Reportes asignados</caption>
					
				<tr>
					<td><b>Folio</b></td>
					<td><b>Forma de recepcion</b></td>
					<td><b>Nombre de los NNA</b></td>					
					<td><b>Fecha</b></td>
					<td><b>Estatus</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind4->fetch_assoc()) { $idPc=$row['idPc'];?>							
				<tr>
					<td><a href="perfil_posible_caso.php?idPosibleCaso=<?= $idPc?>"><?php echo $row['folio']; ?></a></td>					
					<td><?php echo $row['recepcion']; ?></td>
					<td>
						<?php $Qnna="SELECT nombre, apellido_p, apellido_m from nna_reportados where id_posible_caso=$idPc and activo=1";
						$Rnna=$mysqli->query($Qnna);
						while ($rwNna=$Rnna->fetch_assoc()) {
						 		echo $rwNna['nombre']." ".$rwNna['apellido_p']." ".$rwNna['apellido_m'].", ";
						 } ?>
					</td>				
					<td><?php echo $row['fecha']; ?></td>					
					<td align="center" valign="middle">
						<?php $atend=$row['atendido'];
							if ($atend=='1' or empty($atend)) { ?>
						<img src="images/advertencia.png" width="50px" height="50px">
						<?php }else if ($atend=='2') { ?>
						<img src="images/proceso.png" width="65px" height="65px">
						<?php }else if ($atend=='3') { ?>
						<img src="images/Anegativo.png" width="65px" height="65px">
						<?php }else if ($atend=='4') { ?>
						<img src="images/Apositivo.png" width="65px" height="65px"	>
						<?php } ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>	
	<?php } if(!empty($_POST['I5'])){ ?>
		<div class="box">	
		<?php if ($row5==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Acercamientos familiares</caption>
					
				<tr>
					<td><b>Folio del posible caso</b></td>								
					<td><b>Fecha del acercamiento</b></td>
					<td><b>Fecha de registro</b></td>
					<td><b>Observaciones</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind5->fetch_assoc()) { ?>							
				<tr>
					<td><a href="perfil_posible_caso.php?idPosibleCaso=<?php echo $row['rep']; ?>"><?php echo $row['folio']; ?></a></td>					
					<td><?php echo $row['fecha_acerca']; ?></td>					
					<td><?php echo $row['fecha_reg']; ?></td>					
					<td><?php echo $row['observaciones']; ?></td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>	
	<?php } if(!empty($_POST['I51'])){ ?>
		<div class="box">	
		<?php if ($row51==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else { ?>			
			<table><caption>Intervenciones</caption>
					
				<tr>
					<td><b>Folio</b></td>
					<td><b>Fecha del acercamiento</b></td>
					<td><b>Fecha de registro</b></td>					
					<td><b>Observaciones</b></td>
					<td><b>Intervenciones</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$datosInd51->fetch_assoc()) { ?>							
				<tr>
					<td><a href="perfil_posible_caso.php?idPosibleCaso=<?php echo $row['rep']; ?>"><?php echo $row['folio']; ?></a></td>					
					<td><?php echo $row['fecha_acerca']; ?></td>					
					<td><?php echo $row['fecha_reg']; ?></td>					
					<td><?php echo $row['observaciones']; ?></td>					
					<td><?php echo $row['inter']; ?></td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>	
	<?php } if(!empty($_POST['I6'])){ ?>
		<div class="box">	
		<?php if ($row6==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Acercamientos con NNA</caption>
					
				<tr>
					<td><b>Folio del reporte</b></td>
					<td><b>NNA</b></td>
					<td><b>Fecha del acercamiento</b></td>					
					<td><b>Fecha de registro</b></td>
					<td><b>Observaciones</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind6->fetch_assoc()) { ?>							
				<tr>
					<td><a href="perfil_posible_caso.php?idPosibleCaso=<?php echo $row['rep']; ?>"><?php echo $row['folio']; ?></a></td>					
					<td><?php $Qnna="SELECT nombre, apellido_p, apellido_m from nna_reportados where id_posible_caso=$idPc and activo=1";
					$Rnna=$mysqli->query($Qnna);
					while ($rwNna=$Rnna->fetch_assoc()) {
					 		echo $rwNna['nombre']." ".$rwNna['apellido_p']." ".$rwNna['apellido_m'].", ";
					 } ?></td>
					<td><?php echo $row['fecha_acps']; ?></td>					
					<td><?php echo $row['fecha_reg']; ?></td>					
					<td><?php echo $row['otros']; ?></td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>	
		<?php } if(!empty($_POST['I7'])){ ?>
		<div class="box">	
		<?php if ($row7==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Reportes atendidos</caption>
					
				<tr>
					<td><b>Folio del reporte</b></td>
					<td><b>Fecha</b></td>								
					<td><b>NNA</b></td>				
					<td><b>Narración</b></td>
					<td><b>Estatus</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind7->fetch_assoc()) { $idPc=$row['idPc'];?>							
				<tr>
					<td><a href="perfil_posible_caso.php?idPosibleCaso=<?= $idPc?>"><?php echo $row['folio']; ?></a></td>					
					<td><?php echo $row['fecha']; ?></td>					
					<td>
						<?php $Qnna="SELECT nombre, apellido_p, apellido_m from nna_reportados where id_posible_caso=$idPc and activo=1";
						$Rnna=$mysqli->query($Qnna);
						while ($rwNna=$Rnna->fetch_assoc()) {
						 		echo $rwNna['nombre']." ".$rwNna['apellido_p']." ".$rwNna['apellido_m'].", ";
						 } ?>
					</td>				
					<td><?php echo $row['narracion']; ?></td>
					<td align="center" valign="middle">
						<?php $atend=$row['atendido'];
							if ($atend=='1') { ?>
						<img src="images/advertencia.png" width="50px" height="50px">
						<?php }else if ($atend=='2') { ?>
						<img src="images/proceso.png" width="65px" height="65px">
						<?php }else if ($atend=='3') { ?>
						<img src="images/Anegativo.png" width="65px" height="65px">
						<?php }else if ($atend=='4') { ?>
						<img src="images/Apositivo.png" width="65px" height="65px"	>
						<?php } ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>		
	<?php } if(!empty($_POST['I8'])){ ?>
		<div class="box">	
		<?php if ($row8==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Reportes positivos</caption>
					
				<tr>
					<td><b>Folio del reporte</b></td>
					<td><b>Fecha</b></td>								
					<td><b>NNA</b></td>				
					<td><b>Narración</b></td>
					<td><b>Estatus</b></td>										
				</tr>
				<tbody>
				<?php while ($row=$eind8->fetch_assoc()) { $idPc=$row['idPc'];?>							
				<tr>
					<td><a href="perfil_posible_caso.php?idPosibleCaso=<?= $idPc?>"><?php echo $row['folio']; ?></a></td>					
					<td><?php echo $row['fecha']; ?></td>					
					<td>
						<?php $Qnna="SELECT nombre, apellido_p, apellido_m from nna_reportados where id_posible_caso=$idPc and activo=1";
						$Rnna=$mysqli->query($Qnna);
						while ($rwNna=$Rnna->fetch_assoc()) {
						 		echo $rwNna['nombre']." ".$rwNna['apellido_p']." ".$rwNna['apellido_m'].", ";
						 } ?>
					</td>				
					<td><?php echo $row['narracion']; ?></td>
					<td align="center" valign="middle">
						<?php $atend=$row['atendido'];
							if ($atend=='1') { ?>
						<img src="images/advertencia.png" width="50px" height="50px">
						<?php }else if ($atend=='2') { ?>
						<img src="images/proceso.png" width="65px" height="65px">
						<?php }else if ($atend=='3') { ?>
						<img src="images/Anegativo.png" width="65px" height="65px">
						<?php }else if ($atend=='4') { ?>
						<img src="images/Apositivo.png" width="65px" height="65px"	>
						<?php } ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I9'])){ ?>
		<div class="box">	
		<?php if ($row9==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Reportes no atendidos</caption>
					
				<tr>
					<td><b>Folio del reporte</b></td>
					<td><b>Fecha</b></td>								
					<td><b>NNA</b></td>				
					<td><b>Narración</b></td>
					<td><b>Estatus</b></td>				
				</tr>
				<tbody>
				<?php while ($row=$eind9->fetch_assoc()) { $idPc=$row['idPc'];?>							
				<tr>
					<td><a href="perfil_posible_caso.php?idPosibleCaso=<?= $idPc?>"><?php echo $row['folio']; ?></a></td>					
					<td><?php echo $row['fecha']; ?></td>					
					<td>
						<?php $Qnna="SELECT nombre, apellido_p, apellido_m from nna_reportados where id_posible_caso=$idPc and activo=1";
						$Rnna=$mysqli->query($Qnna);
						while ($rwNna=$Rnna->fetch_assoc()) {
						 		echo $rwNna['nombre']." ".$rwNna['apellido_p']." ".$rwNna['apellido_m'].", ";
						 } ?>
					</td>				
					<td><?php echo $row['narracion']; ?></td>
					<td align="center" valign="middle">
						<?php $atend=$row['atendido'];
							if ($atend=='1') { ?>
						<img src="images/advertencia.png" width="50px" height="50px">
						<?php }else if ($atend=='2') { ?>
						<img src="images/proceso.png" width="65px" height="65px">
						<?php }else if ($atend=='3') { ?>
						<img src="images/Anegativo.png" width="65px" height="65px">
						<?php }else if ($atend=='4') { ?>
						<img src="images/Apositivo.png" width="65px" height="65px"	>
						<?php } ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I10'])){ ?>
		<div class="box">	
		<?php if ($row10==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Casos registrados</caption>
					
				<tr>
					<td><b>Folio del caso</b></td><td></td>						
					<td><b>Nombre</b></td><td></td>
					<td><b>Deteccion</b></td>
					<td><b>Fecha</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind10->fetch_assoc()) { ?>							
				<tr>
					<td><a href="perfil_caso.php?id=<?php echo $row['id']; ?>"><?php echo $row['folio_c']; ?></a></td>					
					<td colspan="3"><?php echo $row['nombre']; ?></td>					
					<td><?php echo $row['descripcion']; ?></td>					
					<td><?php echo $row['fecha']; ?></td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I11'])){ ?>
		<div class="box">	
		<?php if ($row11==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>NNA registrados</caption>
					
				<tr>
					<td><b>Folio</b></td>								
					<td><b>Nombre</b></td>
					<td><b>CURP</b></td>
					<td><b>Fecha de nac.</b></td>
					<td><b>Sexo</b></td>
					<td><b>Municipio</b></td>
					<td><b>Localidad</b></td>
					<td><b>Origen</b></td>
					<td><b>Validación CURP</b></td>										
				</tr>
				<tbody>
				<?php while ($row=$eind11->fetch_assoc()) { $id_nna=$row['id'];?>							
				<tr>
					<td><a href="perfil_nna.php?id=<?= $id_nna ?>"><?php echo $row['folio']; ?></a></td>					
					<td><?= $row['apellido_p']." ".$row['apellido_m']." ".$row['nombre'] ?></td>					
					<td><?php echo $row['curp']; ?></td>					
					<td><?php echo $row['fecha_nac']; ?></td>											
					<td><?php echo $row['sexo']; ?></td>											
					<td><?php echo $row['municipio']; ?></td>											
					<td><?php echo $row['localidad']; ?></td>
					<td><?php 
						if($row['indigena']==1) echo "INDIGENA ";
						if($row['afrodescendiente']==1) echo "AFRODESCENDIENTE ";
						if($row['migrante']==1) echo "MIGRANTE ";?>
					</td>
					<td>
						<?php if($row['validacionRenapo']==1){ ?>
							<input type="image" alt="Validada" name="validada" src="images/ejecutada.png" height="30" width="30">
						<?php } else {  ?>
							<input type="image" alt="No validada" name="noValidada" src="images/no_ejecutada.png" height="30" width="30" onclick="location='validarCurp.php?id=<?= $id_nna?>&T=1'">
						<?php } ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I111'])){ ?>
		<div class="box">	
		<?php if ($row111==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>NNA registrados</caption>
					
				<tr>
					<td><b>Folio</b></td>								
					<td><b>Nombre</b></td>
					<td><b>CURP</b></td>
					<td><b>Fecha de nac.</b></td>
					<td><b>Sexo</b></td>
					<td><b>Municipio</b></td>
					<td><b>Localidad</b></td>
					<td><b>Origen</b></td>
					<td><b>Validación CURP</b></td>	
				</tr>
				<tbody>
				<?php while ($row=$eind111->fetch_assoc()) { $id_nna=$row['id'];?>							
				<tr>
					<td><a href="perfil_nna.php?id=<?= $id_nna ?>"><?php echo $row['folio']; ?></a></td>					
					<td><?= $row['apellido_p']." ".$row['apellido_m']." ".$row['nombre'] ?></td>					
					<td><?php echo $row['curp']; ?></td>					
					<td><?php echo $row['fecha_nac']; ?></td>											
					<td><?php echo $row['sexo']; ?></td>											
					<td><?php echo $row['municipio']; ?></td>											
					<td><?php echo $row['localidad']; ?></td>
					<td><?php 
						if($row['indigena']==1) echo "INDIGENA ";
						if($row['afrodescendiente']==1) echo "AFRODESCENDIENTE ";
						if($row['migrante']==1) echo "MIGRANTE ";?>
					</td>
					<td>
						<?php if($row['validacionRenapo']==1){ ?>
							<input type="image" alt="Validada" name="validada" src="images/ejecutada.png" height="30" width="30">
						<?php } else {  ?>
							<input type="image" alt="No validada" name="noValidada" src="images/no_ejecutada.png" height="30" width="30" onclick="location='validarCurp.php?id=<?= $id_nna?>&T=1'">
						<?php } ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I12'])){ ?>
		<div class="box">	
		<?php if ($row12==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Carpetas registradas</caption>
					
				<tr>
					<td><b>NUC</b></td>								
					<td><b>Folio del caso</b></td>
					<td><b>Delito</b></td>
					<td><b>Fecha de inicio</b></td>											
				</tr>
				<tbody>
				<?php while ($row=$eind12->fetch_assoc()) { ?>							
				<tr>
					<td><a href="perfil_carpeta.php?id=<?php echo $row['id']; ?>"><?php echo $row['nuc']; ?></a></td>					
					<td><?php echo $row['folio_c']; ?></td>					
					<td><?php echo $row['delito']; ?></td>					
					<td><?php echo $row['fecha_ini']; ?></td>												
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I13'])){ ?>
		<div class="box">	
		<?php if ($row13==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Carpetas asignadas</caption>
					
				<tr>
					<td><b>NUC</b></td>								
					<td><b>Folio del caso</b></td>
					<td><b>Delito</b></td>
					<td><b>Fecha de inicio</b></td>															
				</tr>
				<tbody>
				<?php while ($row=$eind13->fetch_assoc()) { ?>							
				<tr>
					<td><a href="perfil_carpeta.php?id=<?php echo $row['id']; ?>"><?php echo $row['nuc']; ?></a></td>					
					<td><?php echo $row['folio_c']; ?></td>					
					<td><?php echo $row['delito']; ?></td>					
					<td><?php echo $row['fecha_ini']; ?></td>												
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I14'])){ ?>
		<div class="box">	
		<?php if ($row14==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Medidas de proteccion especial decretadas</caption>
					
				<tr>
					<td><b>Folio del caso</b></td>								
					<td><b>Derecho</b></td>
					<td><b>Tipo de medida</b></td>
					<td><b>Medida de proteccion</b></td>
					<td><b>NNA</b></td>
					<td><b>Institucion</b></td>
					<td><b>Fecha</b></td>
					<td><b>Estado</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind14->fetch_assoc()) { ?>							
				<tr>
					<td><a href="cuadro_guia.php?id=<?php echo $row['id']; ?>"><?php echo $row['folio_c']; ?></a></td>					
					<td><?php echo $row['derecho']; ?></td>					
					<td><?php echo $row['medida_p']; ?></td>					
					<td><?php echo $row['medidaC']; ?></td>											
					<td><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?></td>	
					<td><?php echo $row['responsable_med']; ?></td>											
					<td><?php echo $row['fecha']; ?></td>
					<td><?php $es=$row['estado'];
						if ($es==0) { ?>
						<input type="image" src="images/no_ejecutada.png" height="40" width="40">
							
						<?php }else if($es==1 ){ ?>
						<input type="image" src="images/ejecutada.png" height="40" width="40">
							
						<?php } //cierre siendo tu medida ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I15'])){ ?>
		<div class="box">	
		<?php if ($row15==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Medidas de proteccion especial ejecutadas</caption>
					
				<tr>
					<td><b>Folio del caso</b></td>								
					<td><b>Derecho</b></td>
					<td><b>Tipo de medida</b></td>
					<td><b>Medida de proteccion</b></td>
					<td><b>NNA</b></td>
					<td><b>Institucion</b></td>
					<td><b>Fecha</b></td>
					<td><b>Estado</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind15->fetch_assoc()) { ?>							
				<tr>
					<td><a href="cuadro_guia.php?id=<?php echo $row['id']; ?>"><?php echo $row['folio_c']; ?></a></td>					
					<td><?php echo $row['derecho']; ?></td>					
					<td><?php echo $row['medida_p']; ?></td>					
					<td><?php echo $row['medidaC']; ?></td>											
					<td><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?></td>	
					<td><?php echo $row['responsable_med']; ?></td>											
					<td><?php echo $row['fecha']; ?></td>
					<td><?php $es=$row['estado'];
						if ($es==0) { ?>
						<input type="image" src="images/no_ejecutada.png" height="40" width="40">
							
						<?php }else if($es==1 ){ ?>
						<input type="image" src="images/ejecutada.png" height="40" width="40">
							
						<?php } //cierre siendo tu medida ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I16'])){ ?>
		<div class="box">	
		<?php if ($row16==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Medidas de proteccion urgentes decretadas</caption>
					
				<tr>
					<td><b>Folio del caso</b></td>								
					<td><b>Derecho</b></td>
					<td><b>Tipo de medida</b></td>
					<td><b>Medida de proteccion</b></td>
					<td><b>NNA</b></td>
					<td><b>Institucion</b></td>
					<td><b>Fecha</b></td>
					<td><b>Estado</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind16->fetch_assoc()) { ?>							
				<tr>
					<td><a href="cuadro_guia.php?id=<?php echo $row['id']; ?>"><?php echo $row['folio_c']; ?></a></td>					
					<td><?php echo $row['derecho']; ?></td>					
					<td><?php echo $row['medida_p']; ?></td>					
					<td><?php echo $row['medidaC']; ?></td>											
					<td><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?></td>	
					<td><?php echo $row['responsable_med']; ?></td>											
					<td><?php echo $row['fecha']; ?></td>
					<td><?php $es=$row['estado'];
						if ($es==0) { ?>
						<input type="image" src="images/no_ejecutada.png" height="40" width="40">
							
						<?php }else if($es==1 ){ ?>
						<input type="image" src="images/ejecutada.png" height="40" width="40">
							
						<?php } //cierre siendo tu medida ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I17'])){ ?>
		<div class="box">	
		<?php if ($row17==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Medidas de proteccion urgentes ejecutadas</caption>
					
				<tr>
					<td><b>Folio del caso</b></td>								
					<td><b>Derecho</b></td>
					<td><b>Tipo de medida</b></td>
					<td><b>Medida de proteccion</b></td>
					<td><b>NNA</b></td>
					<td><b>Institucion</b></td>
					<td><b>Fecha</b></td>
					<td><b>Estado</b></td>
										
				</tr>
				<tbody>
				<?php while ($row=$eind17->fetch_assoc()) { ?>							
				<tr>
					<td><a href="cuadro_guia.php?id=<?php echo $row['id']; ?>"><?php echo $row['folio_c']; ?></a></td>					
					<td><?php echo $row['derecho']; ?></td>					
					<td><?php echo $row['medida_p']; ?></td>					
					<td><?php echo $row['medidaC']; ?></td>											
					<td><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?></td>	
					<td><?php echo $row['responsable_med']; ?></td>											
					<td><?php echo $row['fecha']; ?></td>
					<td><?php $es=$row['estado'];
						if ($es==0) { ?>
						<input type="image" src="images/no_ejecutada.png" height="40" width="40">
							
						<?php }else if($es==1 ){ ?>
						<input type="image" src="images/ejecutada.png" height="40" width="40">
							
						<?php } //cierre siendo tu medida ?>
					</td>											
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I18'])){ ?>
		<div class="box">	
		<?php if ($row18==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>Seguimientos registrados</caption>
					
				<tr>
					<td><b>Folio del caso</b></td>								
					<td><b>NNA</b></td>
					<td><b>Medida de proteccion</b></td>
					<td><b>Area de seguimiento</b></td>
					<td><b>Seguimiento</b></td>
					<td><b>Fecha</b></td>										
				</tr>
				<tbody>
				<?php while ($row=$eind18->fetch_assoc()) { ?>							
				<tr>
					<td><a href="ag_comment.php?id=<?php echo $row['idM']; ?>&idCaso=<?php echo $row['idC'] ?>"><?php echo $row['folio_c']; ?></a></td>		
					<td><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?></td>		
					<td><?php echo $row['medidaC']; ?></td>	
					<td><?php echo $row['area']; ?></td>					
					<td><?php echo $row['seguimiento']; ?></td>					
					<td><?php echo $row['fecha']; ?></td>													
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } if(!empty($_POST['I19'])){ ?>
		<div class="box">	
		<?php if ($row19==0) { ?>
				<strong>SIN REGISTROS</strong>
			<?php  } else{ ?>			
			<table><caption>NNA con derechos restituidos</caption>
					
				<tr>
					<td><b>Folio</b></td>								
					<td><b>Nombre</b></td>
					<td><b>CURP</b></td>
					<td><b>Fecha</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$eind19->fetch_assoc()) { ?>							
				<tr>
					<td><a href="perfil_nna.php?id=<?php echo $row['id']; ?>"><?php echo $row['folio']; ?></a></td>					
					<td><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?></td>
					<td><?php echo $row['curp']; ?></td>					
					<td><?php echo $row['fecha_reg']; ?></td>													
				</tr>
				<?php } ?>
				</tbody>
			</table>
		<?php } ?>
		</div>
	<?php } ?>


</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>