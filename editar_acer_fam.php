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

	$idReporte=$_GET['id'];
	$sql="SELECT ts.id_departamentos_asignado as asignado, ps.id_departamentos_asignado as asignado_psic, d.responsable 
     FROM historico_asignaciones_trabajo_social ts inner join posible_caso pc on pc.id=ts.id_posible_caso
     left join historico_asignaciones_psicologia ps on ps.id_posible_caso=pc.id
     inner join departamentos d on d.id=ts.id_departamentos_asignado
     where ts.id_posible_caso='$idReporte'";
    $esqlq=$mysqli->query($sql);
    while ($row=$esqlq->fetch_assoc()) {
        $encargado=$row['responsable'];
        $asig=$row['asignado'];
        $asigp=$row['asignado_psic'];
    }

    $qAcerca="SELECT info_fam, registro_fam, acta_nac, hijo_sin_res, hijo_sin, hijo_nna_res, hijo_nna, 
    opinion_nna, cuidado_nna_res, cuidado_nna, vivienda_nna_res, vivienda_nna, violencia_nna_res,
    violencia_nna, maltrato_nna_res, maltrato_nna, alimentacion_nna_res, alimentacion_nna,
    doctor_nna_res, doctor_nna, cartilla_vacunacion, cartilla_completa, enfermo_nna_res,
    enfermo_nna, asistencia_medica, servicio_medico_res, servicio_medico, discapacidad_res,
    alguna_discapacidad, aditamentos_res, aditamentos, nna_escuela_res, nna_escuela,
    nna_asiste_res, nna_asiste, desempeño_res, desempeño, act_recreativas_res,
    actividades_recreativas, grado_negacion, reconoce_paso, tiene_responsabilidad,
    necesita_ayuda, esta_dispuesto, descripcion, observaciones_afecta,
    dialogo_experimental, observaciones, inter
    FROM acercamiento_familiar where id_reporte='$idReporte'";
    $rAcerca=$mysqli->query($qAcerca);
    
    if (!empty($_POST['ReginfoFam'])) {
        $fecha= date("Y-m-d H:i:s", time());
        $hst="INSERT INTO historico_acercamiento_familiar (id_acercamiento_familiar, id_reporte, fecha_actualizacion, info_fam, respo_reg) SELECT id, $idReporte, '$fecha', info_fam,  $idDEPTO from acercamiento_familiar where id_reporte=$idReporte";
        $rhst=$mysqli->query($hst);
        if($rhst){        
            $infoFam=$_POST['infoFam'];
            $sql="UPDATE acercamiento_familiar set info_fam='$infoFam' where id_reporte='$idReporte'";
            $esql=$mysqli->query($sql);
            if ($esql) {
                echo "<script>
                    alert('La información ha sido actualizada');   
                    window.location= 'acercamiento_ts.php?id=$idReporte'
                    </script>";
            }else {
                echo "ERROR: ".$sql;
            } 
        } else echo $hst;
    }
    if (!empty($_POST['Preguntas'])) {
        $fecha= date("Y-m-d H:i:s", time());
         $hst="INSERT INTO historico_acercamiento_familiar (id_acercamiento_familiar, id_reporte, fecha_actualizacion, registro_fam, acta_nac, hijo_sin, hijo_nna, opinion_nna, 
         cuidado_nna, vivienda_nna, violencia_nna, maltrato_nna, alimentacion_nna, doctor_nna,
        cartilla_vacunacion, cartilla_completa, enfermo_nna,asistencia_medica, servicio_medico, 
        alguna_discapacidad, aditamentos, nna_escuela, nna_asiste, desempeño, actividades_recreativas,
        hijo_sin_res, hijo_nna_res,cuidado_nna_res, vivienda_nna_res, violencia_nna_res, 
        maltrato_nna_res, alimentacion_nna_res, doctor_nna_res, enfermo_nna_res, servicio_medico_res,
        discapacidad_res, aditamentos_res, nna_escuela_res, nna_asiste_res,desempeño_res, 
        act_recreativas_res, respo_reg) SELECT id, $idReporte, '$fecha', 
        registro_fam, acta_nac, hijo_sin, hijo_nna, opinion_nna, 
        cuidado_nna, vivienda_nna, violencia_nna, maltrato_nna, alimentacion_nna, doctor_nna,
        cartilla_vacunacion, cartilla_completa, enfermo_nna,asistencia_medica, servicio_medico, 
        alguna_discapacidad, aditamentos, nna_escuela, nna_asiste, desempeño, actividades_recreativas,
        hijo_sin_res, hijo_nna_res,cuidado_nna_res, vivienda_nna_res, violencia_nna_res, 
        maltrato_nna_res, alimentacion_nna_res, doctor_nna_res, enfermo_nna_res, servicio_medico_res,
        discapacidad_res, aditamentos_res, nna_escuela_res, nna_asiste_res,desempeño_res, 
        act_recreativas_res,  $idDEPTO from acercamiento_familiar where id_reporte=$idReporte";
        $qhst=$mysqli->query($hst);
        if($qhst){
            $p1=$_POST['p1'];
            $p2=$_POST['p2'];
            $p3=$_POST['tap3'];  
            $p3r=$_POST['p3'];
            $p4=$_POST['tap4'];
            $p4r=$_POST['p4'];
            $p5=$_POST['p5'];
            $p6=$_POST['tap6'];
            $p6r=$_POST['p6'];
            $p7=$_POST['tap7'];
            $p7r=$_POST['p7'];
            $p8=$_POST['tap8'];
            $p8r=$_POST['p8'];
            $p9=$_POST['tap9'];
            $p9r=$_POST['p9'];
            $p10=$_POST['tap10'];
            $p10r=$_POST['p10'];
            $p11=$_POST['tap11'];
            $p11r=$_POST['p11'];
            $p12=$_POST['p12'];
            $p13=$_POST['p13'];
            $p14=$_POST['tap14'];
            $p14r=$_POST['p14'];
            $p15=$_POST['p15'];
            $p16=$_POST['tap16'];
            $p16r=$_POST['p16'];
            $p17=$_POST['tap17'];
            $p17r=$_POST['p17'];
            $p18=$_POST['tap18'];
            $p18r=$_POST['p18'];
            $p19=$_POST['tap19'];
            $p19r=$_POST['p19'];
            $p20=$_POST['tap20'];
            $p20r=$_POST['p20'];
            $p21=$_POST['tap21'];
            $p21r=$_POST['p21'];
            $p22=$_POST['tap22'];
            $p22r=$_POST['p22'];
            
            
            $sql="UPDATE acercamiento_familiar set registro_fam='$p1', acta_nac='$p2', hijo_sin='$p3', 
            hijo_nna='$p4', opinion_nna='$p5', cuidado_nna='$p6', vivienda_nna='$p7', 
            violencia_nna='$p8', maltrato_nna='$p9', alimentacion_nna='$p10', doctor_nna='$p11', 
            cartilla_vacunacion='$p12', cartilla_completa='$p13', enfermo_nna='$p14', 
            asistencia_medica='$p15', servicio_medico='$p16', alguna_discapacidad='$p17', 
            aditamentos='$p18', nna_escuela='$p19', nna_asiste='$p20', desempeño='$p21', 
            actividades_recreativas='$p22', hijo_sin_res='$p3r', hijo_nna_res='$p4r', 
            cuidado_nna_res='$p6r', vivienda_nna_res='$p7r',    violencia_nna_res='$p8r',
            maltrato_nna_res='$p9r', alimentacion_nna_res='$p10r', doctor_nna_res='$p11r',
            enfermo_nna_res='$p14r', servicio_medico_res='$p16r', discapacidad_res='$p17r',
            aditamentos_res='$p18r', nna_escuela_res='$p19r', nna_asiste_res='$p20r',
            desempeño_res='$p21r', act_recreativas_res='$p22r' 
            where id_reporte='$idReporte'";
            $esql=$mysqli->query($sql);
            if ($esql) {
                echo "<script>
                    alert('La información ha sido actualizada');   
                    window.location= 'acercamiento_ts.php?id=$idReporte'
                    </script>";
            }else {
                echo "ERROR: ".$sql;
            } 
        } else echo $hst;
    }
    if (!empty($_POST['registrar_negacion'])) {
        $fecha= date("Y-m-d H:i:s", time());
        $hst="INSERT INTO historico_acercamiento_familiar (id_acercamiento_familiar, id_reporte, fecha_actualizacion, grado_negacion, reconoce_paso, tiene_responsabilidad, 
        necesita_ayuda, esta_dispuesto, descripcion, respo_reg) SELECT id, $idReporte, '$fecha', grado_negacion, reconoce_paso, tiene_responsabilidad, 
        necesita_ayuda, esta_dispuesto, descripcion,  $idDEPTO from acercamiento_familiar where id_reporte=$idReporte";
        $rhst=$mysqli->query($hst);
        if($rhst){        
            $neg=$_POST['neg'];
            $n1=$_POST['n1'];
            $n2=$_POST['n2'];
            $n3=$_POST['n3'];        
            $n4=$_POST['n4'];        
            $descneg=$_POST['descneg'];
            
            $sql="UPDATE acercamiento_familiar set grado_negacion='$neg', reconoce_paso='$n1', tiene_responsabilidad='$n2', necesita_ayuda='$n3', esta_dispuesto='$n4', descripcion='$descneg', fecha_reg_grado='$fecha' where id_reporte='$idReporte'";
            $esql=$mysqli->query($sql);
            if ($esql) {
                echo "<script>
                    alert('La información ha sido actualizada');   
                    window.location= 'acercamiento_ts.php?id=$idReporte'
                    </script>";
            }else {
                echo "ERROR: ".$sql;
            } 
        } else echo $hst;
    }
    if (!empty($_POST['antepenultimo'])) {
        $fecha= date("Y-m-d H:i:s", time());
        $hst="INSERT INTO historico_acercamiento_familiar (id_acercamiento_familiar, id_reporte, fecha_actualizacion, observaciones_afecta, respo_reg) SELECT id, $idReporte, '$fecha', observaciones_afecta,  $idDEPTO from acercamiento_familiar where id_reporte=$idReporte";
        $rhst=$mysqli->query($hst);
        if($rhst){
            $afecta_emo=$_POST['afecta_emo'];
            
            
            $sql="UPDATE acercamiento_familiar set observaciones_afecta='$afecta_emo' where id_reporte='$idReporte'";
            $esql=$mysqli->query($sql);
            if ($esql) {
                echo "<script>
                    alert('La información ha sido actualizada');   
                    window.location= 'acercamiento_ts.php?id=$idReporte'
                    </script>";
            }else {
                echo "ERROR: ".$sql;
            } 
        } else echo $hst;
    }
    if (!empty($_POST['penultimo'])) {
        $fecha= date("Y-m-d H:i:s", time());
        $hst="INSERT INTO historico_acercamiento_familiar (id_acercamiento_familiar, id_reporte, fecha_actualizacion, dialogo_experimental, respo_reg) SELECT id, $idReporte, '$fecha', dialogo_experimental,  $idDEPTO from acercamiento_familiar where id_reporte=$idReporte";
        $rhst=$mysqli->query($hst);
        if($rhst){
            $dialogo=$_POST['dialogo'];
            $sql="UPDATE acercamiento_familiar set dialogo_experimental='$dialogo' where id_reporte='$idReporte'";
            $esql=$mysqli->query($sql);
            if ($esql) {
                echo "<script>
                    alert('La información ha sido actualizada');   
                    window.location= 'acercamiento_ts.php?id=$idReporte'
                    </script>";
            }else {
                echo "ERROR: ".$sql;
            } 
        } else echo $hst;
    }
    if (!empty($_POST['ultimo'])) {
        $fecha= date("Y-m-d H:i:s", time());
        $hst="INSERT INTO historico_acercamiento_familiar (id_acercamiento_familiar, id_reporte, fecha_actualizacion, observaciones, respo_reg) SELECT id, $idReporte, '$fecha', observaciones,  $idDEPTO from acercamiento_familiar where id_reporte=$idReporte";
        $rhst=$mysqli->query($hst);
        if($rhst){
            $otros_datos=$_POST['otros_datos'];
            
            $sql="UPDATE acercamiento_familiar set observaciones='$otros_datos' where id_reporte='$idReporte'";
            $esql=$mysqli->query($sql);
            if ($esql) {
                echo "<script>
                    alert('La información ha sido actualizada');   
                    window.location= 'acercamiento_ts.php?id=$idReporte'
                    </script>";
            }else {
                echo "ERROR: ".$sql;
            } 
        } else echo $hst;
    }
    if (!empty($_POST['ninter'])) {
        $fecha= date("Y-m-d H:i:s", time());
        $hst="INSERT INTO historico_acercamiento_familiar (id_acercamiento_familiar, id_reporte, fecha_actualizacion, inter, respo_reg) SELECT id, $idReporte, '$fecha', inter,  $idDEPTO from acercamiento_familiar where id_reporte=$idReporte";
        $rhst=$mysqli->query($hst);
        if($rhst){
            $numI=$_POST['numI'];        
            
            $sql="UPDATE acercamiento_familiar set inter='$numI' where id_reporte='$idReporte'";
            $esql=$mysqli->query($sql);
            if ($esql) {
                echo "<script>
                    alert('La información ha sido actualizada');   
                    window.location= 'acercamiento_ts.php?id=$idReporte'
                    </script>";
            }else {
                echo "ERROR: ".$sql;
            } 
        } else echo $hst;
    }


	

?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Editar acercamiento familiar</title>
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
                        <div class="2u 12u$(xsmall)">
                            <input type="button" name="" class="button special small" value="Atras" onclick="location='acercamiento_ts.php?id=<?= $idReporte ?>'">
                        </div>
                        <div class="10u">
                            <h2>Editar acercamiento</h2>
                        </div>
                    </div>
                    <?php while ($row=$rAcerca->fetch_assoc()) { ?>
                    	<div class="box">
                            <h4>Información aportada por la familia sobre la situación de la NNA</h4>
                            <?php if (!empty($row['info_fam'])) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u$">
                                            <textarea name="infoFam" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000" required><?=$row['info_fam']?> </textarea>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="12u$">
                                            <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                                <input type="submit" name="ReginfoFam" value="Actualizar">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    	<div class="box">
                            <h4>Elementos por preguntar a la familia</h4>
                            <?php if (!empty($row['registro_fam'])) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="table-wrapper">
                                        <table class="alt">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Si</th>
                                                    <th>No</th>
                                                    <th>No aplica</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>1. ¿Cuenta con registro en el estado familiar?</th>
                                                    <th><input type="radio" id="p1si" name="p1" value="SI" <?php if($row['registro_fam']=='SI') { ?> checked="true" <?php } ?> >
                                                        <label for="p1si"></label></th>
                                                    <th><input type="radio" id="p1no" name="p1" value="NO" <?php if($row['registro_fam']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p1no"></label></th>
                                                    <th><input type="radio" id="p1na" name="p1" value="NO APLICA" <?php if($row['registro_fam']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p1na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>2. ¿Tiene acta de nacimiento?</th>
                                                    <th><input type="radio" id="p2si" name="p2" value="SI" <?php if($row['acta_nac']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p2si"></label></th>
                                                    <th><input type="radio" id="p2no" name="p2" value="NO" <?php if($row['acta_nac']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p2no"></label></th>
                                                    <th><input type="radio" id="p2na" name="p2" value="NO APLICA" <?php if($row['acta_nac']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p2na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>3. ¿Hay algún hijo o hija que no viva con la familia?
                                                        <textarea name="tap3" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['hijo_sin']?></textarea></th>
                                                    <th><input type="radio" id="p3si" name="p3" value="SI" <?php if($row['hijo_sin_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p3si"></label></th>
                                                    <th><input type="radio" id="p3no" name="p3" value="NO" <?php if($row['hijo_sin_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p3no"></label></th>
                                                    <th><input type="radio" id="p3na" name="p3" value="NO APLICA" <?php if($row['hijo_sin_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p3na"></label></th>                     
                                                </tr>
                                                <tr>
                                                    <th>4. En caso de que algún hijo o hija no viva con la familia ¿Tiene convivencia con la NNA?
                                                        <textarea name="tap4" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['hijo_nna']?></textarea></th>
                                                    <th><input type="radio" id="p4si" name="p4" value="SI" <?php if($row['hijo_nna_res']=='SI') { ?> checked="true" <?php } ?>>
                                                            <label for="p4si"></label></th>
                                                    <th><input type="radio" id="p4no" name="p4" value="NO" <?php if($row['hijo_nna_res']=='NO') { ?> checked="true" <?php } ?>>
                                                            <label for="p4no"></label></th>
                                                    <th><input type="radio" id="p4na" name="p4" value="NO APLICA" <?php if($row['hijo_nna_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p4na"></label></th>                  
                                                </tr>
                                                <tr>
                                                    <th>5. ¿La opinión de la NNA es considerada y tomada en cuenta?</th>
                                                    <th><input type="radio" id="p5si" name="p5" value="SI" <?php if($row['opinion_nna']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p5si"></label></th>
                                                    <th><input type="radio" id="p5no" name="p5" value="NO" <?php if($row['opinion_nna']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p5no"></label></th>
                                                    <th><input type="radio" id="p5na" name="p5" value="NO APLICA" <?php if($row['opinion_nna']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p5na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>6. ¿Alguien lo cuida la mayor parte del tiempo? ¿Quién?
                                                        <textarea name="tap6" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['cuidado_nna'] ?></textarea></th>
                                                    <th><input type="radio" id="p6si" name="p6" value="SI" <?php if($row['cuidado_nna_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p6si"></label></th>
                                                    <th><input type="radio" id="p6no" name="p6" value="NO" <?php if($row['cuidado_nna_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p6no"></label></th>
                                                    <th><input type="radio" id="p6na" name="p6" value="NO APLICA" <?php if($row['cuidado_nna_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p6na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>7. ¿La NNA vive en una vivienda adecuada para su desarrollo?
                                                        <textarea name="tap7" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['vivienda_nna']?></textarea></th>
                                                    <th><input type="radio" id="p7si" name="p7" value="SI" <?php if($row['vivienda_nna_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p7si"></label></th>
                                                    <th><input type="radio" id="p7no" name="p7" value="NO" <?php if($row['vivienda_nna_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p7no"></label></th>
                                                    <th><input type="radio" id="p7na" name="p7" value="NO APLICA" <?php if($row['vivienda_nna_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p7na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>8. ¿Ha visto peleas o cualquier otro tipo de violencia?¿Cómo fue?
                                                        <textarea name="tap8" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['violencia_nna']?></textarea></th>
                                                    <th><input type="radio" id="p8si" name="p8" value="SI" <?php if($row['violencia_nna_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p8si"></label></th>
                                                    <th><input type="radio" id="p8no" name="p8" value="NO" <?php if($row['violencia_nna_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p8no"></label></th>
                                                    <th><input type="radio" id="p8na" name="p8" value="NO APLICA" <?php if($row['violencia_nna_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p8na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>9. ¿Ha recibido golpes o insultos? ¿Por parte de quién?
                                                        <textarea name="tap9" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['maltrato_nna']?></textarea></th>
                                                    <th><input type="radio" id="p9si" name="p9" value="SI" <?php if($row['maltrato_nna_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p9si"></label></th>
                                                    <th><input type="radio" id="p9no" name="p9" value="NO" <?php if($row['maltrato_nna_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p9no"></label></th>
                                                    <th><input type="radio" id="p9na" name="p9" value="NO APLICA" <?php if($row['maltrato_nna_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p9na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>10. ¿Qué come normalmente? ¿Cuántas veces al día consume alimentos?
                                                        <textarea name="tap10" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['alimentacion_nna']?></textarea></th>
                                                    <th><input type="radio" id="p10si" name="p10" value="SI" <?php if($row['alimentacion_nna_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p10si"></label></th>
                                                    <th><input type="radio" id="p10no" name="p10" value="NO" <?php if($row['alimentacion_nna_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p10no"></label></th>
                                                    <th><input type="radio" id="p10na" name="p10" value="NO APLICA" <?php if($row['alimentacion_nna_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p10na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>11. ¿Cuándo fue la última vez que lo llevaron al doctor?
                                                        <textarea name="tap11" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['doctor_nna']?></textarea></th>
                                                    <th><input type="radio" id="p11si" name="p11" value="SI" <?php if($row['doctor_nna_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p11si"></label></th>
                                                    <th><input type="radio" id="p11no" name="p11" value="NO" <?php if($row['doctor_nna_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p11no"></label></th>
                                                    <th><input type="radio" id="p11na" name="p11" value="NO APLICA" <?php if($row['doctor_nna_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p11na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>12. ¿Tiene cartilla de vacunación?</th>
                                                    <th><input type="radio" id="p12si" name="p12" value="SI" <?php if($row['cartilla_vacunacion']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p12si"></label></th>
                                                    <th><input type="radio" id="p12no" name="p12" value="NO" <?php if($row['cartilla_vacunacion']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p12no"></label></th>
                                                    <th><input type="radio" id="p12na" name="p12" value="NO APLICA" <?php if($row['cartilla_vacunacion']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p12na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>13. ¿Está completa?</th>
                                                    <th><input type="radio" id="p13si" name="p13" value="SI" <?php if($row['cartilla_completa']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p13si"></label></th>
                                                    <th><input type="radio" id="p13no" name="p13" value="NO" <?php if($row['cartilla_completa']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p13no"></label></th>
                                                    <th><input type="radio" id="p13na" name="p13" value="NO APLICA" <?php if($row['cartilla_completa']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p13na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>14. ¿Ha estado enfermo? ¿De qué?
                                                        <textarea name="tap14" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['enfermo_nna']?></textarea></th>
                                                    <th><input type="radio" id="p14si" name="p14" value="SI" <?php if($row['enfermo_nna_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p14si"></label></th>
                                                    <th><input type="radio" id="p14no" name="p14" value="NO" <?php if($row['enfermo_nna_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p14no"></label></th>
                                                    <th><input type="radio" id="p14na" name="p14" value="NO APLICA" <?php if($row['enfermo_nna_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p14na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>15. ¿Recibió asistencia médica?</th>
                                                    <th><input type="radio" id="p15si" name="p15" value="SI" <?php if($row['asistencia_medica']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p15si"></label></th>
                                                    <th><input type="radio" id="p15no" name="p15" value="NO" <?php if($row['asistencia_medica']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p15no"></label></th>
                                                    <th><input type="radio" id="p15na" name="p15" value="NO APLICA" <?php if($row['asistencia_medica']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p15na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>16. ¿Tiene servicio médico de seguro social, seguro popular, ISSSTE, PEMEX o SEDENA?
                                                        <textarea name="tap16" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="Especifique" maxlength="500"><?=$row['servicio_medico']?></textarea></th>
                                                    <th><input type="radio" id="p16si" name="p16" value="SI" <?php if($row['servicio_medico_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p16si"></label></th>
                                                    <th><input type="radio" id="p16no" name="p16" value="NO" <?php if($row['servicio_medico_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p16no"></label></th>
                                                    <th><input type="radio" id="p16na" name="p16" value="NO APLICA" <?php if($row['servicio_medico_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p16na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>17. ¿Alguno de sus hijos o hijas tiene alguna discapacidad?
                                                        <textarea name="tap17" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['alguna_discapacidad']?></textarea></th>
                                                    <th><input type="radio" id="p17si" name="p17" value="SI" <?php if($row['discapacidad_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p17si"></label></th>
                                                    <th><input type="radio" id="p17no" name="p17" value="NO" <?php if($row['discapacidad_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p17no"></label></th>
                                                    <th><input type="radio" id="p17na" name="p17" value="NO APLICA" <?php if($row['discapacidad_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p17na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>18. Si requiere aditamentos (silla de ruedas, muleta, lentes, etc.) ¿Cuenta con ellos?
                                                        <textarea name="tap18" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['aditamentos']?></textarea></th>
                                                    <th><input type="radio" id="p18si" name="p18" value="SI" <?php if($row['aditamentos_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p18si"></label></th>
                                                    <th><input type="radio" id="p18no" name="p18" value="NO" <?php if($row['aditamentos_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p18no"></label></th>
                                                    <th><input type="radio" id="p18na" name="p18" value="NO APLICA" <?php if($row['aditamentos_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p18na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>19. ¿La NNA se encuentra inscrito en la escuela?
                                                        <textarea name="tap19" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['nna_escuela']?></textarea></th>
                                                    <th><input type="radio" id="p19si" name="p19" value="SI" <?php if($row['nna_escuela_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p19si"></label></th>
                                                    <th><input type="radio" id="p19no" name="p19" value="NO" <?php if($row['nna_escuela_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p19no"></label></th>
                                                    <th><input type="radio" id="p19na" name="p19" value="NO APLICA" <?php if($row['nna_escuela_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p19na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>20. ¿La NNA asiste regularmente a la escuela?
                                                        <textarea name="tap20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['nna_asiste']?></textarea></th>
                                                    <th><input type="radio" id="p20si" name="p20" value="SI" <?php if($row['nna_asiste_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p20si"></label></th>
                                                    <th><input type="radio" id="p20no" name="p20" value="NO" <?php if($row['nna_asiste_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p20no"></label></th>
                                                    <th><input type="radio" id="p20na" name="p20" value="NO APLICA" <?php if($row['nna_asiste_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p20na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>21. ¿Se da algun seguimiento a su desempeño escolar? ¿Quién?
                                                        <textarea name="tap21" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['desempeño']?></textarea></th>
                                                    <th><input type="radio" id="p21si" name="p21" value="SI" <?php if($row['desempeño_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p21si"></label></th>
                                                    <th><input type="radio" id="p21no" name="p21" value="NO" <?php if($row['desempeño_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p21no"></label></th>
                                                    <th><input type="radio" id="p21na" name="p21" value="NO APLICA" <?php if($row['desempeño_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p21na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>22. ¿Realiza actividades recreativas? ¿Con quién y de qué forma socializa?
                                                        <textarea name="tap22" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"><?=$row['actividades_recreativas']?></textarea></th>
                                                    <th><input type="radio" id="p22si" name="p22" value="SI" <?php if($row['act_recreativas_res']=='SI') { ?> checked="true" <?php } ?>>
                                                        <label for="p22si"></label></th>
                                                    <th><input type="radio" id="p22no" name="p22" value="NO" <?php if($row['act_recreativas_res']=='NO') { ?> checked="true" <?php } ?>>
                                                        <label for="p22no"></label></th>
                                                    <th><input type="radio" id="p22na" name="p22" value="NO APLICA" <?php if($row['act_recreativas_res']=='NO APLICA') { ?> checked="true" <?php } ?>>
                                                        <label for="p22na"></label></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                            <input type="submit" name="Preguntas" value="Actualizar">
                                        <?php } ?>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                        <div class="box">
                            <h4>Grado de negación</h4>
                            <?php if (!empty($row['grado_negacion'])) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <input type="radio" id="na" name="neg" value="ALTO" <?php if($row['grado_negacion']=='ALTO') {?> checked="true" <?php } ?>>
                                    <label for="na">ALTO</label>
                                    <input type="radio" id="nb" name="neg" value="BAJO" <?php if($row['grado_negacion']=='BAJO') {?> checked="true" <?php } ?>>
                                    <label for="nb">BAJO</label>
                                    <table>
                                        <thead>
                                            <tr>
                                                <td></td>
                                                <td>Si</td>
                                                <td>No</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>¿La persona a cargo del cuidado de la NNA <u>reconoce que pasó</u> algo que le resulta a este perjudicial o lo pone en riesgo?</th>
                                                <th><input type="radio" id="n1si" name="n1" value="SI" <?php if($row['reconoce_paso']=='SI') {?> checked="true" <?php } ?>>
                                                    <label for="n1si"></label></th>
                                                <th><input type="radio" id="n1no" name="n1" value="NO" <?php if($row['reconoce_paso']=='NO') {?> checked="true" <?php } ?>>
                                                    <label for="n1no"></label></th>                   
                                            </tr>
                                            <tr>
                                                <th>¿Reconoce que, como persona adulta, <u>tiene la responsabilidad</u> de que las NNA tengan todo lo que necesitan para crecer bien y no sufrir vulneración de derechos?</th>
                                                <th><input type="radio" id="n2si" name="n2" value="SI"<?php if($row['tiene_responsabilidad']=='SI') {?> checked="true" <?php } ?>>
                                                        <label for="n2si"></label></th>
                                                <th><input type="radio" id="n2no" name="n2" value="NO"<?php if($row['tiene_responsabilidad']=='NO') {?> checked="true" <?php } ?>>
                                                        <label for="n2no"></label></th>                     
                                            </tr>
                                            <tr>
                                                <th>¿Reconoce que <u>necesita ayuda</u> para que la NNA tenga todo lo que necesita?</th>
                                                <th><input type="radio" id="n3si" name="n3" value="SI" <?php if($row['necesita_ayuda']=='SI') {?> checked="true" <?php } ?>>
                                                    <label for="n3si"></label></th>
                                                <th><input type="radio" id="n3no" name="n3" value="NO" <?php if($row['necesita_ayuda']=='NO') {?> checked="true" <?php } ?>>
                                                    <label for="n3no"></label></th>                     
                                            </tr> 
                                            <tr>
                                                <th>¿<u>Está dispuesto</u> a hacer esfurzos/compromisos para lograr lo necesario para que las NNA estén bien?</th>
                                                <th><input type="radio" id="n4si" name="n4" value="SI" <?php if($row['esta_dispuesto']=='SI') {?> checked="true" <?php } ?>>
                                                    <label for="n4si"></label></th>
                                                <th><input type="radio" id="n4no" name="n4" value="NO" <?php if($row['esta_dispuesto']=='NO') {?> checked="true" <?php } ?>>
                                                    <label for="n4no"></label></th>                     
                                            </tr>
                                        </tbody>
                                    </table>
                                    Descripción sobre el grado de negación de las personas adultas a cargo de la NNA
                                    <textarea name="descneg" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required><?=$row['descripcion']?></textarea>
                                    <br>
                                    <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                        <input type="submit" name="registrar_negacion" value="Actualizar">
                                    <?php } ?>
                                </form>
                            <?php } ?>
                        </div>
                        <div class="box">
                            <h4>Observaciones sobre el grado de afectación emocional (actitud, disposición y estado de ánimo) y/o física (enfermedades, adicciones, discapacidad) de las personas adultas a cargo de la NNA</h4>
                            <?php if (!empty($row['observaciones_afecta'])) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <textarea name="afecta_emo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required><?=$row['observaciones_afecta']?></textarea>
                                    <br>
                                    <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                        <input type="submit" name="antepenultimo" value="Actualizar">
                                    <?php } ?>
                                </form>
                            <?php } ?>
                        </div>
                        <div class="box">
                            <h4>Información aportada por la familia durante el dialogo experimental (que manifiestan necesitar para proteger mejor a la NNA)</h4>
                            <?php if (!empty($row['dialogo_experimental'])) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <textarea name="dialogo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required><?=$row['dialogo_experimental']?></textarea>
                                    <br>
                                    <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                        <input type="submit" name="penultimo" value="Actualizar">
                                    <?php } ?>
                                </form>
                            <?php } ?>
                        </div>
                        <div class="box">
                            <h4>Otros datos u observaciones (información aportada por el entorno escolar, comunitario, institucional, etc.)</h4>
                            <?php if (!empty($row['observaciones'])) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <textarea name="otros_datos" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required><?=$row['observaciones']?></textarea>
                                    <br>
                                    <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                        <input type="submit" name="ultimo" value="Actualizar">
                                    <?php } ?>
                                </form>
                            <?php } ?>
                        </div>
                        <div class="box">        
                            <?php if (!empty($row['inter'])) { ?>        
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="uniform row">
                                        <div class="4u 12u$(xsmall)">
                                            <strong>Numero de intervenciones realizadas:</strong>
                                        </div>
                                        <div class="4u 12u$(xsmall)">
                                            <input type="number" name="numI" value="<?=$row['inter']?>">
                                        </div>
                                        <div class="4u 12u$(xsmall)">
                                            <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                                <input type="submit" name="ninter" value="Actualizar">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </form>
                            <?php } ?> 
                        </div>
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
		</div>  <!--cierre de wrapper-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>