<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	

	//seleccion datos reportes
	$qReportes="SELECT pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y') 
	as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ts, 
	d2.responsable 
	 as asig_ps, hat.estadoAtencion from reportes_vd r inner join posible_caso pc 
	 on r.id_posible_caso=pc.id 
	 left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps 
	left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts 
	left join departamentos d1 on d1.id=hps.id_departamentos_asignado
	 left join departamentos d2 on d2.id=hts.id_departamentos_asignado 
	 left join historico_atenciones_pos_casos hat on hat.id=pc.id_estado_atencion 
	order by id desc limit 25";
	
	if (isset($_POST['texto'])) {
		$palabra=mysqli_real_escape_string($mysqli,$_POST['texto']);
		$qReportes="SELECT pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y') 
		as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ts, 
		d2.responsable 
		 as asig_ps, hat.estadoAtencion from reportes_vd r inner join posible_caso pc 
		 on r.id_posible_caso=pc.id 
		 left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps 
		left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts 
		left join departamentos d1 on d1.id=hps.id_departamentos_asignado
		 left join departamentos d2 on d2.id=hts.id_departamentos_asignado 
		 left join historico_atenciones_pos_casos hat on hat.id=pc.id_estado_atencion 
		 where r.folio like '%$palabra%' or r.fecha like '%$palabra%' or r.persona_reporte 
		 like '%$palabra%' or d1.responsable like '%$palabra%' or d2.responsable like '%$palabra%' or r.ubicacion like '%$palabra%' order by id desc";
	}
	$rReportes=$mysqli->query($qReportes);
	$total=$rReportes->num_rows;
	$salida="";
	if($total>0){
	$salida.= "Reportes mostrados: ".$total."
					<table>		
					<thead>						
						<tr>
							<th>FOLIO</th>
							<th>FECHA</th>
							<th>NNA</th>
							<th>UBICACIÓN</th>
							<th>PERSONA QUE REPORTO</th>
							<th>T.S.</th>
							<th>PSIC.</th>
							<th>ESTATUS</th>
						</tr>
				</thead> <tbody>"	;
			while ($fila = $rReportes->fetch_assoc()) {
    		$salida.="
    				
    				<tr>
    					<td>".$fila['folio']."</td>
    					<td>".$fila['fecha']."</td>
    					<td>".$fila['nom_nna']."</td>
    					<td>".$fila['ubicacion']."</td>
    					<td>".$fila['persona_reporte']."</td>
    					<td>".$fila['asig_ts']."</td>
    					<td>".$fila['asig_ps']."</td>
    					<td>".$fila['estadoAtencion']."</td>
    				</tr>";
    				}
    	$salida.="</tbody></table>";
    } else $salida.= "Reportes mostrados: ".$total;

?>