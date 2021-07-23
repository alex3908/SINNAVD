	<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	
	$idPosibleCaso = $_GET['idPosibleCaso'];
	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}
	$qReportes="SELECT reportes_vd.id,  reportes_vd.folio, date_format(reportes_vd.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_registro, 
    	cat_recepcion_reporte.recepcion, distritos.distrits, maltratos as maltrato, reportes_vd.otros_datos,
    	reportes_vd.persona_reporte, reportes_vd.narracion, municipios.municipio, 
    	localidades.localidad, reportes_vd.calle, reportes_vd.ubicacion, departamentos.responsable,
    	reportes_vd.respo_reg 
    	from reportes_vd inner join municipios on clm=municipios.id
    	inner join departamentos on departamentos.id=reportes_vd.respo_reg
   		inner join  localidades on localidades.id=reportes_vd.id_localidad 
   	 	left join distritos on distritos.id=reportes_vd.id_distrito
    	inner join cat_recepcion_reporte on id_recepcion=cat_recepcion_reporte.id 
    	where reportes_vd.id_posible_caso='$idPosibleCaso' and reportes_vd.activo=1"; 
    $rReportes=$mysqli->query($qReportes);

    $qnnaRep="SELECT nom_nna, edad_nna, fn_nna, lugarnac_nna, lugarreg_nna 
    FROM reportes_vd where id_posible_caso='$idPosibleCaso' and nom_nna is not null";
    $rnnaRep=$mysqli->query($qnnaRep);    
    $numNnaRep=$rnnaRep->num_rows;
	
    $qPosibleCaso="SELECT folio, tabj.id_departamentos_asignado AS juridico, tabts.id_departamentos_asignado AS ts,
    	tabps.id_departamentos_asignado AS ps, poscaso.estadoAtencion, poscaso.fechaAtencion, 
		poscaso.id_departamentos as responsable_atencion, posible_caso.responsable_registro
		FROM posible_caso LEFT JOIN historico_asignaciones_juridico tabj ON id_asignado_juridico = tabj.id
    	LEFT JOIN historico_asignaciones_trabajo_social tabts ON id_asignado_ts = tabts.id
    	LEFT JOIN historico_asignaciones_psicologia tabps ON id_asignado_ps = tabps.id
    	left join historico_atenciones_pos_casos poscaso on id_estado_atencion = poscaso.id
		WHERE posible_caso.id ='$idPosibleCaso' and posible_caso.activo=1";
	$rPosibleCaso=$mysqli->query($qPosibleCaso);
	while ($rowPC=$rPosibleCaso->fetch_assoc()) {
		$folioPC=$rowPC['folio'];
		$asignadoJ=$rowPC['juridico'];
		$asignadoTS=$rowPC['ts'];
		$asignadoPS=$rowPC['ps'];
		$estadoAtencion=$rowPC['estadoAtencion'];
		$fechaAtencion=$rowPC['fechaAtencion'];
		$responAtencion=$rowPC['responsable_atencion'];	
		$ResponRegistro=$rowPC['responsable_registro'];
	}
	$qresPS="SELECT responsable from departamentos where id='$asignadoPS'";
	$rresPS=$mysqli->query($qresPS);
	while ($rowps=$rresPS->fetch_assoc()) {  //obtiene el nombre del asignado de trabajo social (TS)
		$responsablePS=$rowps['responsable'];
	}
	$qresTS="SELECT responsable from departamentos where id='$asignadoTS'";
    $rresTS=$mysqli->query($qresTS);
    while ($rowts=$rresTS->fetch_assoc()) {  //obtiene el nombre del asignado de trabajo social (TS)
        $responsableTS=$rowts['responsable'];
	}
	$qresJ="SELECT responsable from departamentos where id='$asignadoJ'";
    $rresJ=$mysqli->query($qresJ);
    while ($rowj=$rresJ->fetch_assoc()) {  //obtiene el nombre del asignado de juridico (j)
        $responsableJ=$rowj['responsable'];
    }
	$qacer_psi="SELECT id from acercamiento_psic where  activo=1 and id_reporte ='$idPosibleCaso'";
	$racer_psi=$mysqli->query($qacer_psi);
	$num_acer_psi=$racer_psi->num_rows;
	$qacer_familiar="SELECT id from acercamiento_familiar where  activo=1 and id_reporte ='$idPosibleCaso'";
	$racer_familiar=$mysqli->query($qacer_familiar);
	$num_acer_familiar=$racer_familiar->num_rows;

	//para el menu (aun no se exactamente para que)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;

	$qCaso="SELECT id_caso, casos.folio_c from casos inner join relacion_pc_caso on id_caso=casos.id
 	 where id_posible_caso='$idPosibleCaso' and casos.activo=1";
	$rCaso=$mysqli->query($qCaso);
	$rCaso1=$mysqli->query($qCaso);
	$rCaso2=$mysqli->query($qCaso);
	$TieneCaso=$rCaso->num_rows;

	$qNnas="SELECT nna_reportados.id as idNna, nombre, apellido_p, apellido_m, sexo.sexo, date_format(nna_reportados.fecha_nacimiento,'%d/%m/%Y') as fecha_nac, lugar_nacimiento, edad, padre_fallecido_covid, madre_fallecida_covid
	from nna_reportados left join sexo on nna_reportados.sexo=sexo.id
	where id_posible_caso='$idPosibleCaso' and activo=1";
	$rNnas=$mysqli->query($qNnas);
	$numNna=$rNnas->num_rows;

	$qNnaVulnerados="SELECT distinct c.id_nna, id_nna_reportado FROM relacion_nna_nnareportado r
	inner join nna_reportados n on n.id=r.id_nna_reportado
	inner join nna_caso c on c.id_nna=r.id_nna
	where n.id_posible_caso=$idPosibleCaso and n.activo=1";
	$rNnaVulnerados=$mysqli->query($qNnaVulnerados);
	$NumNnaVul=$rNnaVulnerados->num_rows;
	if (isset($_POST['regresar'])) { 		
		header("Location: lista_reportes_nueva.php?estRep=0");
	}
	if (isset($_POST['byeJuridico'])) {	//desasigna al asignado de juridico
		$NA="UPDATE posible_caso set id_asignado_juridico='0' where id='$idPosibleCaso'";
		$eNA=$mysqli->query($NA);
		header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
	}


	//preguntar si esta bien esta validación. 
	if (isset($_POST['byeTS'])) {	//desasigna al asignado de trabajo social
		if($num_acer_familiar>0){ ?> 
			<script type="text/javascript">
				alert('Esta acción no es posible ya que existen acercamientos registrados');
			</script>
		<?php } else {  //solo sino hay acercamientos de trabajo social
			$NA="UPDATE posible_caso set id_asignado_ts='0' where id='$idPosibleCaso'";
			$eNA=$mysqli->query($NA);
			header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
		}
	} 
	if (isset($_POST['byePS'])) {	//desasigna al encargado de psicologia
		if($num_acer_psi>0){ ?>
			<script type="text/javascript">
				alert('Esta acción no es posible ya que existen acercamientos registrados');
			</script>
		<?php } else {  //solo sino hay acercamientos de psicologia 
			$NA="UPDATE posible_caso set id_asignado_ps='0' where id='$idPosibleCaso'";
			$eNA=$mysqli->query($NA);
			header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
		}
	}
	if (isset($_POST['estado_proceso'])) {
		$fecha=date("Y-m-d H:i:s", time());
		$estado = $_POST['estado_proceso']; 
		echo "<script>if(confirm('¿Desea cambiar el estado de atención?')){
        document.location='estado_pc.php?id=$idPosibleCaso&estado=$estado';}
        </script>"; 
	}
	
	$qperomisos="SELECT id from departamentos where (id_depto in ('9','10','14') and id_personal='3' 
	and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5' and id='$idDEPTO') 
	or (id_personal='1' and id='$idDEPTO' and id_depto='16')"; //ayuda a validar la persona que puede mover 
	// proteccion, representacion, y vinculacion cuando son administrativos. o subprocu, o administrador y control de informacion
	$rpermisos=$mysqli->query($qperomisos);
	$permiso=$rpermisos->num_rows;
	if (isset($_POST['eliminar'])) {	
		$fecha=date("Y-m-d H:i:s", time());
		$qDesPosibleCaso="UPDATE posible_caso set activo=0, fecha_delete='$fecha', responsable_delete='$idDEPTO' where id='$idPosibleCaso'";
		$rDesPosibleCaso=$mysqli->query($qDesPosibleCaso);
		$qSelRep="SELECT id from reportes_vd where id_posible_caso='$idPosibleCaso'";
		$rSelRep=$mysqli->query($qSelRep);
		while($rwEliRepo=$rSelRep->fetch_assoc()){
			$idRep=$rwEliRepo['id'];
			$qdesRep="UPDATE reportes_vd set activo=0, fecha_desact='$fecha', respo_desact='$idDEPTO' where id='$idRep'";
			$rdesRep=$mysqli->query($qdesRep); 
		}
		header("Location: lista_reportes_nueva.php?estRep=0");
	}
//	if($idDEPTO==220)
	//echo $TieneCaso."-".$NumNnaVul."-".$numNna;
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Posible caso <?=$folioPC?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
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
					<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						<div class="row uniform">
							<div class="2u$(xsmall)">
								<input type="submit" class="button special small" name="regresar" value="regresar">
							</div>
							<div class="2u 12$(xsmall)">	
								<?php if ($_SESSION['departamento']==16) { ?>
										<input type="submit" class="button special small" name="eliminar" value="eliminar">
									<?php } ?>
							</div>
							<div class="1u 12$(xsmall)">
							</div>
							<div class="6u 12$(xsmall">
								<h3>Folio posible caso: <?=$folioPC?></h3>
							</div>
							<div class="1u 12$(xsmall)">
								<input type="button" class="button special small" name="btnImprimir" value="Imprimir" onclick="location='pdfPosibleCaso.php?idPosibleCaso=<?php echo $idPosibleCaso; ?>'">
							</div>
						</div>
						<br>
						<div class="box" align="center">
						<table>
							<caption>Asignados</caption>
							<thead>
								<tr>
									<td>Juridico: 
										<?php if ($asignadoJ==0) { if ($permiso=='0') { ?> sin asignar
										<?php }else{ ?><a href="asignar_posible_caso.php?id=<?php echo $idPosibleCaso;?>&area=1">Asignar</a>
										<?php } }else{ echo $responsableJ; if($_SESSION['departamento']==16 or $idDEPTO==223 or $permiso>0){ ?> <!--solo control de información y erika en su cuenta de administrativo-->
											<input type="submit" class="button small" name="byeJuridico" value="BYE" width="30" height="30">
										<?php } }?> 
									</td>
									<td>Trabajo Social:  
										<?php if ($asignadoTS==0) { if ($permiso=='0') { ?> sin asignar
										<?php }else{ ?><a href="asignar_posible_caso.php?id=<?php echo $idPosibleCaso;?>&area=2">Asignar</a>
										<?php } }else{ echo $responsableTS; if($permiso>0){ ?>
											<input type="submit" class="button small" name="byeTS" value="BYE" width="30" height="30">
										<?php } }?>
									</td>
									<td>Psicologia:
										<?php if ($asignadoPS==0) { if ($permiso=='0') { ?> sin asignar
										<?php }else{ ?><a href="asignar_posible_caso.php?id=<?php echo $idPosibleCaso;?>&area=3">Asignar</a>
										<?php } }else{ echo $responsablePS; if($permiso>0){ ?>
											<input type="submit" class="button small" name="byePS" value="BYE" width="30" height="30">
										<?php } }?>
									</td>
								</tr>
							</thead>
						</table>
							<table class="alt">									
		<thead>
			<tr>
			<?php 
			
			if($idDepartamento==16 and $idPersonal==1) { //control de informacion y administrador lo cambian a acualquier estado-->
				if($estadoAtencion==4) { //pero si ya es atendido positivo checa que tenga un caso
					if($TieneCaso>0 and  $NumNnaVul>=$numNna){ ?>  <!--Si hay caso vinculado ya no puede cambiarlo-->
						<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()"  disabled >
							<label for="NA">No atendido</label></th> 								
						<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" disabled>
							<label for="EP">En proceso</label></th>
						<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" disabled>
							<label for="AN">Atendido negativo</label></th>
						<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()" checked disabled >
							<label for="AP">Atendido positivo</label></th>						
						<th>
							<?php while ($rowCaso=$rCaso->fetch_assoc()) { 
								$idCaso=$rowCaso['id_caso'];
								$folioCaso=$rowCaso['folio_c']; ?>
								<a href="perfil_caso.php?id=<?php echo $idCaso ?>"> <?php echo $folioCaso ?></a></br> 
							<?php } ?> 
						</th>
					<?php } else { ?> <!--sino hay caso lo puede cambiar a cualquier estado-->
							<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" >
							<label for="NA">No atendido</label></th> 								
						<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()">
							<label for="EP">En proceso</label></th>
						<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()">
							<label for="AN">Atendido negativo</label></th>
						<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()" checked disabled >
							<label for="AP">Atendido positivo</label></th>						
						<th><a href="verificar_datos_nna.php?idPc=<?= $idPosibleCaso ?>">Crear Caso</a></th>
						<?php } } else { ?> <!--si es estado no 4 puede solo checa en que estado es y se puede cambiar a cualquier otro estado-->
							<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" <?php  if($estadoAtencion==1 or $estadoAtencion==0) { ?> checked disabled <?php } ?> >
							<label for="NA">No atendido</label></th> 								
						<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" <?php if($estadoAtencion==2) { ?> checked disabled <?php } ?>>
							<label for="EP">En proceso</label></th>
						<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" <?php if($estadoAtencion==3) { ?> checked disabled <?php } ?>>
							<label for="AN">Atendido negativo</label></th>							
						<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()">
							<label for="AP">Atendido positivo</label></th>
						<?php } ?>					

			<?php } else if($idDEPTO==$ResponRegistro){ //Si es el que lo registro puede moverlo a cualquier estado hacia adelante c/s acercamientos
				if($estadoAtencion==1 or $estadoAtencion==0) {?>	
					<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" disabled checked  >
					<label for="NA">No atendido</label></th> 								
					<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" >
					<label for="EP">En proceso</label></th>
					<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" >
					<label for="AN">Atendido negativo</label></th>
					<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()">
					<label for="AP">Atendido positivo</label></th>		
				<?php } else if($estadoAtencion==2){ ?>
					<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" disabled>
					<label for="NA">No atendido</label></th> 								
					<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" disabled checked>
					<label for="EP">En proceso</label></th>
					<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" >
					<label for="AN">Atendido negativo</label></th>
					<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()">
					<label for="AP">Atendido positivo</label></th>	
				<?php } else if($estadoAtencion==3){ ?>
					<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" disabled>
					<label for="NA">No atendido</label></th> 								
					<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" disabled>
					<label for="EP">En proceso</label></th>
					<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" disabled checked>
					<label for="AN">Atendido negativo</label></th>
					<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()">
					<label for="AP">Atendido positivo</label></th>
				<?php } else { ?>
					<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" disabled>
					<label for="NA">No atendido</label></th> 								
					<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" disabled>
					<label for="EP">En proceso</label></th>
					<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" disabled>
					<label for="AN">Atendido negativo</label></th>
					<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()" disabled checked>
					<label for="AP">Atendido positivo</label></th>
					<th><?php if($TieneCaso>0  and $NumNnaVul>=$numNna){ ?>
						<?php while ($rowCaso=$rCaso1->fetch_assoc()) { 
							$idCaso=$rowCaso['id_caso'];
							$folioCaso=$rowCaso['folio_c']; ?>
							<a href="perfil_caso.php?id=<?php echo $idCaso ?>"> <?php echo $folioCaso ?></a></br> 
						<?php } ?> 
					<?php } else { ?>
						<a href="verificar_datos_nna.php?idPc=<?= $idPosibleCaso ?>">Crear Caso</a>
					<?php } ?></th>
					
			<?php } } else if($idDEPTO==$asignadoJ or $idDEPTO==$asignadoPS or $idDEPTO==$asignadoTS){ //si se le ha asignado 
				if($estadoAtencion==1 or $estadoAtencion==0) {?>	
					<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" disabled checked  >
					<label for="NA">No atendido</label></th> 								
					<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" >
					<label for="EP">En proceso</label></th>
					<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" disabled>
					<label for="AN">Atendido negativo</label></th>
					<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()" disabled>
					<label for="AP">Atendido positivo</label></th>		
				<?php } else if($estadoAtencion==2){ ?>
					<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" disabled>
					<label for="NA">No atendido</label></th> 								
					<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" disabled checked>
					<label for="EP">En proceso</label></th>
					<?php if(empty($asignadoPS) and empty($asignadoTS)){ ?> <!--si no hay asignado de ts o ps puede ponerlo a atendido-->
						<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" >
						<label for="AN">Atendido negativo</label></th>
						<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()">
						<label for="AP">Atendido positivo</label></th>	
					<?php } else { 
						if($num_acer_psi>0 or $num_acer_familiar>0) { ?> <!-- si hay acercaientos ya puede cambiar a atendido-->
							<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" >
							<label for="AN">Atendido negativo</label></th>
							<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()">
							<label for="AP">Atendido positivo</label></th>	
						<?php } else { ?>
							<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" disabled>
							<label for="AN">Atendido negativo</label></th>
							<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()" disabled>
							<label for="AP">Atendido positivo</label></th>	
					<?php }	}	?>
				<?php } else if($estadoAtencion==3){ ?>
					<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" disabled>
					<label for="NA">No atendido</label></th> 								
					<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" disabled>
					<label for="EP">En proceso</label></th>
					<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" disabled checked>
					<label for="AN">Atendido negativo</label></th>
					<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()">
					<label for="AP">Atendido positivo</label></th>
				<?php } else { ?>
					<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" disabled>
					<label for="NA">No atendido</label></th> 								
					<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" disabled>
					<label for="EP">En proceso</label></th>
					<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" disabled>
					<label for="AN">Atendido negativo</label></th>
					<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()" disabled checked>
					<label for="AP">Atendido positivo</label></th>
					<th><?php if($TieneCaso>0 and $NumNnaVul>=$numNna){ ?>
						<?php while ($rowCaso=$rCaso2->fetch_assoc()) { 
							$idCaso=$rowCaso['id_caso'];
							$folioCaso=$rowCaso['folio_c']; ?>
							<a href="perfil_caso.php?id=<?php echo $idCaso ?>"> <?php echo $folioCaso ?></a></br> 
						<?php } 
						} else { ?>
						<a href="verificar_datos_nna.php?idPc=<?= $idPosibleCaso ?>">Crear Caso</a>
						<?php } ?></th>
			<?php } } else { ?>
				<th><input type="radio" id="NA" name="estado_proceso" value="1" onchange="this.form.submit()" disabled <?php  if($estadoAtencion==1 or $estadoAtencion==0) { ?> checked  <?php } ?> >
				<label for="NA">No atendido</label></th> 								
				<th><input type="radio" id="EP" name="estado_proceso" value="2" onchange="this.form.submit()" disabled <?php if($estadoAtencion==2) { ?> checked <?php } ?>>
				<label for="EP">En proceso</label></th>
				<th><input type="radio" id="AN" name="estado_proceso"value="3" onchange="this.form.submit()" disabled <?php if($estadoAtencion==3) { ?> checked <?php } ?>>
				<label for="AN">Atendido negativo</label></th>
				<th><input type="radio" id="AP" name="estado_proceso"value="4" onchange="this.form.submit()" disabled <?php if($estadoAtencion==4) { ?> checked <?php } ?>>
				<label for="AP">Atendido positivo</label></th>	

			<?php } ?>																														
			</tr>
			<?php if(($estadoAtencion==1 or $estadoAtencion==0 or $estadoAtencion==2)and ($idDEPTO==$asignadoPS or $idDEPTO==$asignadoTS or $idDEPTO==$asignadoJ)) { ?>
				<tr>
					<th colspan="5">Nota: Para cambiar el estado a positivo o negativo primero debe cambiar a "En proceso" y existir el registro de al menos un acercamiento en caso de que existan personal asignado</th>
				</tr>
			<?php } ?>
		</thead>
	</table>
							<?php if($asignadoPS>0 or $asignadoTS>0) { 
								$xv="SELECT id from part1ac where id_reporte='$idPosibleCaso'";
								$exv=$mysqli->query($xv);//permite saber si hay un registro de acercamiento 							
								$can=$exv->num_rows;
								if ($can>0) {?>
									<input class="button special fit small" type="button" name="" value="acercamientos" onclick="location='registro_nna_ac.php?id=<?php echo $idPosibleCaso; ?>'">	
								<?php }  else { 
									if ($asignadoPS==$idDEPTO or $asignadoTS==$idDEPTO or $_SESSION['departamento']==16) { ?>
										<input class="button special fit small" type="button" name="" value="Registrar acercamientos" onclick="location='registro_nna_ac.php?id=<?php echo $idPosibleCaso; ?>'">
								<?php } }
							}?>
						</div>
						<div class="box" align="center">
						<table>			
										<thead><td colspan="6">NNA registrados: </td>
										<td><input type="button" class="button special small" name="Add" value="Añadir" onclick="location='agregarNnaRep.php?idPosibleCaso=<?= $idPosibleCaso?>'" >
										</td><tr>								
											<td><b>Nombre</b></td>
											<td><b>Sexo </b></td>
											<td><b>Fecha de nacimiento</b></td>
											<td><b>Lugar de nacimiento </b></td>
											<td><b>Fallecido por COVID</b></td>
											<td><b>Edad</b></td>				
										</tr></thead>
								
										<body>
										<?php while ($row=$rNnas->fetch_assoc()){
											 $qName="SELECT id from relacion_names where id_nna_reportado='$row[idNna]' and activo=1";
											 $rName=$mysqli->query($qName);
											 $numName= $rName->num_rows;
											 $qNnaRelacionados="SELECT nna.id, nna.folio from nna inner join relacion_nna_nnareportado r on r.id_nna=nna.id
											 inner join nna_reportados n on n.id=r.id_nna_reportado where n.id='$row[idNna]'";
											 $rNnaRel=$mysqli->query($qNnaRelacionados);
											 $relacion=$rNnaRel->num_rows;
											 while ($rwNna=$rNnaRel->fetch_assoc()) {
											 	$idNnaRel =$rwNna['id'];
											 	$folioNnaRel =$rwNna['folio'];
											 }
											 ?>
											
										<tr>
											<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
											<td><?php echo $row["sexo"]; ?></td>
											<td><?php if($row["fecha_nac"]=="01/01/1900") echo ""; else echo $row["fecha_nac"]; ?> </td>
											<td><?php echo $row["lugar_nacimiento"]; ?></td>
											<td><?php if($row['padre_fallecido_covid']==1 and $row['madre_fallecida_covid']==0) 
												echo "Padre"; elseif($row['padre_fallecido_covid']==0 and $row['madre_fallecida_covid']==1) echo "Madre"; elseif($row['padre_fallecido_covid']==1 and $row['madre_fallecida_covid']==1) echo "Ambos"; ?>
											<td><?php echo $row["edad"]; ?> </td>
											<?php if($numName==0) {
											if($idDEPTO==$asignadoJ or $idDEPTO==$asignadoPS or $idDEPTO==$asignadoTS or $idDEPTO==$ResponRegistro or ($idDepartamento==16 and $idPersonal==1)){ ?>
											<td><a href="editar_nna_reportado.php?id=<?php echo $row['idNna'];?>&idPosCaso=<?php echo $idPosibleCaso;?>">Editar</a></td>
											<td>
												<?php if(empty($relacion)) {?>
													<a href="borrar_nna_reportado_pc.php?id=<?php echo $row['idNna'];?>">Eliminar</a>
												<?php } else { ?>
													<a href="perfil_nna.php?id=<?=$idNnaRel?>"><?=$folioNnaRel?></a>
												<?php } ?>
											</td>
											<?php }	} else { ?>
												<td>NAME</td>
											<?php }
											 } ?>
										</tr>
										</body>
									</table>
						</div>
						<?php // if($idDEPTO==220) echo $qReportes; 
						while ($rowReportes=$rReportes->fetch_assoc()) { $idRep=$rowReportes['id']?>
							<div class=	
							"12u$">
								<div class="box">
									<ul class="alt">
									<div class="row uniform">
										<div class="4u 12u$(small)">
										<li><h4>Folio del reporte:	
										 <?php echo $rowReportes['folio'];  ?></h4> </li>
										<li><h4>Persona que reporto: </h4><?php echo $rowReportes['persona_reporte'];  ?> </li>
										</div>
										<div class="3u 12u$(small)">
										<li><h4>Fecha: <?php echo $rowReportes['fecha_registro'];  ?> </h4></li>
										<li><h4>Tipo de maltrato: <?php echo $rowReportes['maltrato'];  ?> </h4></li>
										</div>
										<div class="5u 12u$(small)">
										<li><h4>Forma de recepcion: <?php echo $rowReportes['recepcion'];  ?> </h4></li>
										<li><h4>Distrito: <?php echo $rowReportes['distrits'];  ?> </h4></li>
										</div>
									</div>
									<div class="row uniform">
									<div class="6u 12u$(small)">
										<li><h4>Narración de lo sucedido: </h4><?php echo $rowReportes['narracion'];  ?> </li>
										<li><h4>Otros datos u observaciones relevantes: </h4><?php echo $rowReportes['otros_datos'];  ?> </li>

									</div>
									<div class="6u 12u$(small)">
										<li><h4>Ubicación: </h4><?php echo 'Municipio '.$rowReportes['municipio'].'<br>Localidad '.$rowReportes['localidad'].'<br>Calle '.$rowReportes['calle'].'<br>Referencias '.$rowReportes['ubicacion'];  ?> </li>
										<li><h4>Responsable de registro: </h4><?php echo $rowReportes['responsable'];  ?> </li>	
										</div>
										<?php if(($rowReportes['recepcion']=="MINISTERIO PÚBLICO (NUC)") and (($idDEPTO==$asignadoJ or $idDEPTO==$asignadoTS or $idDEPTO==$asignadoPS or $idDEPTO==$rowReportes['respo_reg'] ) or ($idDepartamento=='16' and $idPersonal=='1'))) {  ?>
										<div class="12u">
											<a href="editar_reporte.php?idRep=<?=$idRep?>&idPc=<?=$idPosibleCaso?>">Editar</a>
										</div>
										<?php } ?>
									</ul>
								</div>
							</div>
							<?php } ?>

					</form>
					<div class="row uniform">
						<div class="12u">
							<input type="button" class="button special small" name="btnHistorial" value="Ver Historial" onclick="location='historial_pc.php?id=<?php echo $idPosibleCaso; ?>'">
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