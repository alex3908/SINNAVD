<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	
	$idPosibleCaso = $_GET['id'];
	$estado=$_GET['estado'];
	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}
	 echo $idPosibleCaso."-".$estado;
		
		$fecha=date("Y-m-d H:i:s", time());
	 $qPosibleCaso="SELECT folio, tabj.id_departamentos_asignado AS juridico, tabts.id_departamentos_asignado AS ts,
    	tabps.id_departamentos_asignado AS ps, poscaso.estadoAtencion, poscaso.fechaAtencion, 
		poscaso.id_departamentos as responsable_atencion, posible_caso.responsable_registro
		FROM posible_caso LEFT JOIN historico_asignaciones_juridico tabj ON id_asignado_juridico = tabj.id
    	LEFT JOIN historico_asignaciones_trabajo_social tabts ON id_asignado_ts = tabts.id
    	LEFT JOIN historico_asignaciones_psicologia tabps ON id_asignado_ps = tabps.id
    	left join historico_atenciones_pos_casos poscaso on id_estado_atencion = poscaso.id
		WHERE posible_caso.id ='$idPosibleCaso'";
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

	$qacer_psi="SELECT id from acercamiento_psic where  activo=1 and id_reporte ='$idPosibleCaso'";
	$racer_psi=$mysqli->query($qacer_psi);
	$num_acer_psi=$racer_psi->num_rows;
	$qacer_familiar="SELECT id from acercamiento_familiar where  activo=1 and id_reporte ='$idPosibleCaso'";
	$racer_familiar=$mysqli->query($qacer_familiar);
	$num_acer_familiar=$racer_familiar->num_rows;

	if (($idDepartamento=='16' and $idPersonal=='1') or ($idDEPTO==$ResponRegistro)) {  //control de informacion y administrador o quien lo registro
			$qhisAtenciones="INSERT INTO historico_atenciones_pos_casos (id_posible_caso, id_departamentos,
			estadoAtencion, fechaAtencion) values
			('$idPosibleCaso', '$idDEPTO', '$estado', '$fecha')";
			$rhisAtenciones=$mysqli->query($qhisAtenciones);
			if($rhisAtenciones){  // si se registro correctamente en el historial procede a actualizarlo en la tabla posible 
				$qidHisPC="SELECT max(id) from historico_atenciones_pos_casos where id_posible_caso='$idPosibleCaso'";
				$ridHisPC=$mysqli->query($qidHisPC);
				$AhisPC=$ridHisPC->fetch_assoc();
				$idHisPosCaso=implode($AhisPC);
				$qActEstPC="UPDATE posible_caso set id_estado_atencion='$idHisPosCaso' where id='$idPosibleCaso'";
				$rActEstPC=$mysqli->query($qActEstPC);
				if($rActEstPC) 	
					header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
				else echo $qActEstPC;	
		} else echo $qhisAtenciones;
	} else if ($estado=='3' or $estado=='4') { //cualquier persona lo puede poner en proceso, pero si es a atendido 
		if(empty($asignadoPS) and empty($asignadoTS)){ //primero verifica q no tenga asignados de ps y ts
			$qhisAtenciones="INSERT INTO historico_atenciones_pos_casos (id_posible_caso, id_departamentos,
			estadoAtencion, fechaAtencion) values
			('$idPosibleCaso', '$idDEPTO', '$estado', '$fecha')";
			$rhisAtenciones=$mysqli->query($qhisAtenciones);
			if($rhisAtenciones){  // si se registro correctamente en el historial procede a actualizarlo en la tabla posible 
				$qidHisPC="SELECT max(id) from historico_atenciones_pos_casos where id_posible_caso='$idPosibleCaso'";
				$ridHisPC=$mysqli->query($qidHisPC);
				$AhisPC=$ridHisPC->fetch_assoc();
				$idHisPosCaso=implode($AhisPC);
				$qActEstPC="UPDATE posible_caso set id_estado_atencion='$idHisPosCaso' where id='$idPosibleCaso'";
				$rActEstPC=$mysqli->query($qActEstPC);
				if($rActEstPC) 
					header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
				else echo $qActEstPC;
		} else echo $qhisAtenciones;
		} else { //si lo hay verifica que tenga acercamientos 
			if($num_acer_familiar>0 or $num_acer_psi>0) {
				$qhisAtenciones="INSERT INTO historico_atenciones_pos_casos (id_posible_caso, id_departamentos,
				estadoAtencion, fechaAtencion) values
				('$idPosibleCaso', '$idDEPTO', '$estado', '$fecha')";
				$rhisAtenciones=$mysqli->query($qhisAtenciones);
				if($rhisAtenciones){  // si se registro correctamente en el historial procede a actualizarlo en la tabla posible 
					$qidHisPC="SELECT max(id) from historico_atenciones_pos_casos where id_posible_caso='$idPosibleCaso'";
					$ridHisPC=$mysqli->query($qidHisPC);
					$AhisPC=$ridHisPC->fetch_assoc();
					$idHisPosCaso=implode($AhisPC);
					$qActEstPC="UPDATE posible_caso set id_estado_atencion='$idHisPosCaso' where id='$idPosibleCaso'";
					$rActEstPC=$mysqli->query($qActEstPC);
					if($rActEstPC) 
					header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
				else echo $qActEstPC;
		} else echo $qhisAtenciones;
			} else {
				echo "Sin acercamientos no se puede cambiar a atendido atendido";
			}
		}
	} else {
		$qhisAtenciones="INSERT INTO historico_atenciones_pos_casos (id_posible_caso, id_departamentos,
		estadoAtencion, fechaAtencion) values
		('$idPosibleCaso', '$idDEPTO', '$estado', '$fecha')";
		$rhisAtenciones=$mysqli->query($qhisAtenciones);
		if($rhisAtenciones){  // si se registro correctamente en el historial procede a actualizarlo en la tabla posible 
			$qidHisPC="SELECT max(id) from historico_atenciones_pos_casos where id_posible_caso='$idPosibleCaso'";
			$ridHisPC=$mysqli->query($qidHisPC);
			$AhisPC=$ridHisPC->fetch_assoc();
			$idHisPosCaso=implode($AhisPC);
			$qActEstPC="UPDATE posible_caso set id_estado_atencion='$idHisPosCaso' where id='$idPosibleCaso'";
			$rActEstPC=$mysqli->query($qActEstPC);
			if($rActEstPC) 
					header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
				else echo $qActEstPC;
		} else echo $qhisAtenciones;
	}
	

	?>