<?php    
    session_start();
    require 'conexion.php';
    date_default_timezone_set('America/Mexico_City');
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
    $reportesvd="SELECT id from reportes_vd where atendido='1' 
        and (asignado='$idDEPTO' or asignado_psic='$idDEPTO')";
    $erepo=$mysqli->query($reportesvd);
    $wow=$erepo->num_rows;
    $idReporte= $_GET['id'];
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
    $fecha= date ("j/n/Y");
    $fechas="SELECT id, date_format(fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, date_format(fecha_acercamiento, '%d/%m%/%Y') as fecha_acerca, info_fam, num_redes, registro_fam, grado_negacion, observaciones_afecta, dialogo_experimental, observaciones, inter from acercamiento_familiar where id_reporte='$idReporte'";
    $efechas=$mysqli->query($fechas);
        if($efechas->num_rows>0){
            while ($row=$efechas->fetch_assoc()) {
                $freg=$row['fecha_reg'];
                $facerca=$row['fecha_acerca'];
                $infoFam=$row['info_fam'];
                $idAcerca=$row['id'];
                $num_redes=$row['num_redes'];
                $reg_fam=$row['registro_fam'];
                $gn=$row['grado_negacion'];
                $obs_afecta=$row['observaciones_afecta'];
                $dia_exp=$row['dialogo_experimental'];
                $otros=$row['observaciones'];
                $inter=$row['inter'];
            }
        }
    $sql="SELECT r.id, r.nombre, r.edad, r.ocupacion , r.datos_economicos, r.telefono, r.direccion, 
    r.estado_civil, r.escolaridad, r.religion 
    from respon_nna r inner join acercamiento_familiar a on r.id_acerca_fam=a.id
    where id_reporte=$idReporte and r.activo=1";
    $esql=$mysqli->query($sql);
    $res=$esql->num_rows;

    $nnaen="SELECT nna_ac.id, nna_ac.nombre, nna_ac.apellido_p, nna_ac.apellido_m, nna_ac.sexo, nna_ac.fecha_nac, nna_ac.lugar_nac, nna_ac.nacionalidad, nna_ac.ocupacion, nna_ac.religion, nna_ac.fecha_reg, nna_ac.respo_reg FROM nna_ac, part1ac WHERE nna_ac.id_acerca=part1ac.id and part1ac.id_reporte='$idReporte'";
    
    $enna=$mysqli->query($nnaen);
    $connna=$enna->num_rows;

    $redFam="SELECT redes_familiares.id, redes_familiares.parentesco, redes_familiares.nombre, redes_familiares.edad, redes_familiares.direccion, redes_familiares.telefono, redes_familiares.observa from redes_familiares, acercamiento_familiar where redes_familiares.id_acerca=acercamiento_familiar.id and acercamiento_familiar.id_reporte='$idReporte' and redes_familiares.activo=1";
    $eredFam=$mysqli->query($redFam);
    $conredF=$eredFam->num_rows;

    $preg="SELECT registro_fam, acta_nac,hijo_sin_res, hijo_sin, hijo_nna,hijo_nna_res, opinion_nna, cuidado_nna,cuidado_nna_res, vivienda_nna,vivienda_nna_res, violencia_nna, maltrato_nna, alimentacion_nna,violencia_nna_res, maltrato_nna_res, alimentacion_nna_res, doctor_nna, doctor_nna_res, cartilla_vacunacion, cartilla_completa, enfermo_nna_res, enfermo_nna, asistencia_medica, servicio_medico_res,servicio_medico, alguna_discapacidad, discapacidad_res, aditamentos,aditamentos_res, nna_escuela,nna_escuela_res, nna_asiste,nna_asiste_res, desempeño,desempeño_res, act_recreativas_res, actividades_recreativas, grado_negacion, reconoce_paso, tiene_responsabilidad, necesita_ayuda, esta_dispuesto, descripcion from acercamiento_familiar where id_reporte='$idReporte' and id='$idAcerca'";
    $epreg=$mysqli->query($preg);
    $epreg2=$mysqli->query($preg);



    if (!empty($_POST['ReginfoFam'])) {
        $infoFam=$_POST['infoFam'];
        $sql="UPDATE acercamiento_familiar set info_fam='$infoFam' where id='$idAcerca'";
        $esql=$mysqli->query($sql);
        if ($esql>0) {
            header("Location: acercamiento_ts.php?id=$idReporte");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN";
        }

    }
    if (!empty($_POST['Preguntas'])) {
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
        $fecha= date("Y-m-d H:i:s", time());
        $sql="UPDATE acercamiento_familiar set registro_fam='$p1', acta_nac='$p2', hijo_sin='$p3', 
        hijo_nna='$p4', opinion_nna='$p5', cuidado_nna='$p6', vivienda_nna='$p7', 
        violencia_nna='$p8', maltrato_nna='$p9', alimentacion_nna='$p10', doctor_nna='$p11', 
        cartilla_vacunacion='$p12', cartilla_completa='$p13', enfermo_nna='$p14', 
        asistencia_medica='$p15', servicio_medico='$p16', alguna_discapacidad='$p17', 
        aditamentos='$p18', nna_escuela='$p19', nna_asiste='$p20', desempeño='$p21', 
        actividades_recreativas='$p22',	hijo_sin_res='$p3r', hijo_nna_res='$p4r', 
        cuidado_nna_res='$p6r', vivienda_nna_res='$p7r',	violencia_nna_res='$p8r',
        maltrato_nna_res='$p9r', alimentacion_nna_res='$p10r', doctor_nna_res='$p11r',
        enfermo_nna_res='$p14r', servicio_medico_res='$p16r', discapacidad_res='$p17r',
        aditamentos_res='$p18r', nna_escuela_res='$p19r', nna_asiste_res='$p20r',
        desempeño_res='$p21r', act_recreativas_res='$p22r', fecha_registro_pre='$fecha' 
        where id_reporte='$idReporte'";
        $esql=$mysqli->query($sql);
        if ($esql>0) {
            header("Location: acercamiento_ts.php?id=$idReporte");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN";
        }
    }
    if (!empty($_POST['registrar_negacion'])) {
        $neg=$_POST['neg'];
        $n1=$_POST['n1'];
        $n2=$_POST['n2'];
        $n3=$_POST['n3'];        
        $n4=$_POST['n4'];        
        $descneg=$_POST['descneg'];
        $fecha= date("Y-m-d H:i:s", time());
        $sql="UPDATE acercamiento_familiar set grado_negacion='$neg', reconoce_paso='$n1', tiene_responsabilidad='$n2', necesita_ayuda='$n3', esta_dispuesto='$n4', descripcion='$descneg', fecha_registro_grado='$fecha' where id_reporte='$idReporte'";
        $esql=$mysqli->query($sql);
        if ($esql>0) {
            header("Location: acercamiento_ts.php?id=$idReporte");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN";
        }
    }
    if (!empty($_POST['antepenultimo'])) {
        $afecta_emo=$_POST['afecta_emo'];
        $fecha= date("Y-m-d H:i:s", time());
        
        $sql="UPDATE acercamiento_familiar set observaciones_afecta='$afecta_emo', fecha_registro_afecta='$fecha' where id_reporte='$idReporte'";
        $esql=$mysqli->query($sql);
        if ($esql>0) {
            header("Location: acercamiento_ts.php?id=$idReporte");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN";
        }
    }
    if (!empty($_POST['penultimo'])) {
        $dialogo=$_POST['dialogo'];
        $fecha= date("Y-m-d H:i:s", time());
        
        $sql="UPDATE acercamiento_familiar set dialogo_experimental='$dialogo', fecha_registro_dialogo='$fecha' where id_reporte='$idReporte'";
        $esql=$mysqli->query($sql);
        if ($esql>0) {
            header("Location: acercamiento_ts.php?id=$idReporte");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN";
        }
    }
    if (!empty($_POST['ultimo'])) {
        $otros_datos=$_POST['otros_datos'];
        $fecha= date("Y-m-d H:i:s", time());        
        $sql="UPDATE acercamiento_familiar set observaciones='$otros_datos', fecha_registro_observa='$fecha' where id_reporte='$idReporte'";
        $esql=$mysqli->query($sql);
        if ($esql>0) {
            header("Location: acercamiento_ts.php?id=$idReporte");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN";
        }
    }
    if (!empty($_POST['ninter'])) {
        $numI=$_POST['numI'];        
        $fecha= date("Y-m-d H:i:s", time());
        $sql="UPDATE acercamiento_familiar set inter='$numI', fecha_registro_inter='$fecha' where id_reporte='$idReporte'";
        $esql=$mysqli->query($sql);
        if ($esql>0) {
            header("Location: acercamiento_ts.php?id=$idReporte");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN";
        }
    }
   /* if (!empty($_POST['eliminar'])) {
        $pid="SELECT id from acercamiento_familiar where id_reporte='$idReporte'";//selecciona el id del acercamiento fam
        $epid=$mysqli->query($pid);
        while ($row=$epid->fetch_assoc()) {
            $idacf=$row['id'];
        }
        $erf="DELETE from redes_familiares where id_acerca='$idacf'"; //elimina las redes familiares del acerca. fam
        $eerf=$mysqli->query($erf);
        $ern="DELETE from respon_nna where id_acerca_fam='$idacf'"; //elimina los responsables del nna del acerca. fam
        $eern=$mysqli->query($ern);
        $elfam="DELETE from acercamiento_familiar where id_reporte='$idReporte'"; //elimina el acerca. fam
        $eelfam=$mysqli->query($elfam);

        header("Location: acercamiento.php?id=$idReporte");
    } */
    ?>
<!DOCTYPE HTML>

<html>
    <head>
        <title>Acermiento familiar</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
        <link rel="stylesheet" href="assets/css/main.css" />
        <link rel="shortcut icon" href="images/favicon.png" type="image/png" />        
    </head>

    <body>
        <!-- Wrapper -->
        <div id="wrapper">
            <!-- Main -->
            <div id="main">
                <div class="inner">
                    <br><br>
                    <div class="row uniform">
                        <div class="4u 12u$(xsmall)">
                            <input type="button" name="" class="button special small" value="Atras" onclick="location='registro_nna_ac.php?id=<?php echo $idReporte ?>'">
                        </div>
                        <div class="5u 12u$(xsmall)">
                            <h2>Acercamiento familiar</h2>
                        </div>
                        <div class="1.5u 12u$(xsmall)">
                            <?php if ($_SESSION['departamento']==16 or $asig==$idDEPTO) { ?>
                                <input type="button" name="editar" class="button special small fit" value="Editar" onclick="location='editar_acer_fam.php?id=<?= $idReporte ?>'">
                            <?php } ?>
                        </div>
                        <div class="1.5u 12$(xsmall)">
                            <?php if(!empty($inter) and !empty($infoFam) and !empty($reg_fam) and !empty($gn) and !empty($obs_afecta) and !empty($dia_exp) and !empty($otros)) { ?>
                                <input type="button" class="button special small fit" name="btnImprimir" value="Imprimir" onclick="location='pdfAcercaFami.php?idReporte=<?php echo $idReporte; ?>&idAc=<?=$idAcerca?>'"> <?php } ?>
                            </div>
                    </div>
                    <br> A cargo de <strong> <?php echo $encargado; ?></strong>
                    <div class="box">                           
                        <div class="row uniform">
                            <div class="6u 12u$(xsmall)">
                                <strong>Fecha de acercamiento:</strong> <?php echo $facerca; ?>
                            </div>
                            <div class="6u 12u$(xsmall)">
                                <strong>Fecha de registro:</strong> <?php echo $freg; ?>
                            </div>
                        </div>
                        <br>
                        <div class="box">
                            <h4>Niñas, Niños y Adolescentes</h4>
                            <?php while ($row=$enna->fetch_assoc()) { ?>
                                <hr class="major" />
                                <div class="row uniform">
                                    <div class="7u 12u$(xsmall)">Nombre:
                                        <input type="text" name="" value="<?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?>" disabled>
                                    </div>
                                    <div class="2u 12u$(xsmall)">Sexo:
                                        <input type="text" name="" value="<?php echo $row['sexo']; ?>" disabled>
                                    </div>
                                    <div class="3u 12u$(xsmall)">Fecha de nacimiento:
                                        <input type="text" name="" value="<?php echo $row['fecha_nac']; ?>" disabled>
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="4u 12u$(xsmall)">Lugar de nacimiento:
                                        <input type="text" name="" value="<?php echo $row['lugar_nac']; ?>" disabled>
                                    </div>
                                    <div class="4u 12u$(xsmall)">Ocupación:
                                        <input type="text" name="" value="<?php echo $row['ocupacion']; ?>" disabled>
                                    </div>
                                    <div class="4u 12u$(xsmall)">Nacionalidad:
                                        <input type="text" name="" value="<?php echo $row['nacionalidad']; ?>" disabled>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>  
                        <div class="box">
                            <div class="row uniform">
                                <div class="10u">
                                    <h4>Personas adultas responsables del NNA</h4>
                                </div>
                                <div class="2u">
                                    <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { 
                                        if ($res>0) { ?>
                                            <input type="button" value="Editar" onclick="location='reg_per_respo.php?id=<?php echo $idReporte ?>'">
                                        <?php } else { ?>
                                            <input type="button" value="Registrar" onclick="location='reg_per_respo.php?id=<?php echo $idReporte ?>'">
                                        <?php } 
                                    } ?>
                                </div>
                            </div>
                            <?php if ($res>0) { 
                                while ($row=$esql->fetch_assoc()) { ?>
                                    <hr class="major" />
                                    <div class="row uniform">
                                        <div class="5u 12u$(xsmall)">
                                            <strong>Nombre:</strong> <?php echo $row['nombre']; ?>
                                        </div>
                                        <div class="2u 12u$(xsmall)">
                                            <strong>Edad:</strong> <?php echo $row['edad']; ?>
                                        </div>
                                        <div class="3u 12u$(xsmall)">
                                            <strong>Teléfono:</strong> <?php echo $row['telefono']; ?>
                                        </div>
                                        <div class="2u 12u$(xsmall)">
                                            <strong>Ocupación:</strong> <?php echo $row['ocupacion']; ?>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="3u 12u$(xsmall)">
                                            <strong>Datos economicos:</strong> <?php echo $row['datos_economicos']; ?>   
                                        </div>
                                        <div class="1u 12u$(xsmall)">
                                            <strong>Religion:</strong> <?php echo $row['religion']; ?>
                                        </div>
                                        <div class="2u 12u$(xsmall)">
                                            <strong>Escolaridad:</strong> <?php echo $row['escolaridad']; ?>
                                        </div>
                                        <div class="2u 12u$(xsmall)">
                                            <strong>Estado civil:</strong> <?php echo $row['estado_civil']; ?>
                                        </div>                    
                                        <div class="4u 12u$(xsmall)">
                                            <strong>Dirección:</strong> <?php echo $row['direccion']; ?>
                                        </div>                   
                                    </div>
                                <?php } 
                            } ?>
                        </div>
                        <div class="box">
                            <div class="row uniform">
                                <div class="10u">
                                    <h4>Información aportada por la familia sobre redes familiares y comunitarias</h4>
                                </div>
                                <div class="2u">
                                    <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { 
                                        if ($conredF>0) { ?>
                                            <input type="button" value="Editar" onclick="location='reg_redF_acercats.php?id=<?php echo $idReporte; ?>'">
                                        <?php } else { ?>
                                            <input type="button" value="Registrar" onclick="location='reg_redF_acercats.php?id=<?php echo $idReporte; ?>'">
                                        <?php } 
                                    } ?>
                                </div>
                            </div>
                            <?php if ($conredF>0) { 
                                while ($row=$eredFam->fetch_assoc()) { ?>
                                    <hr class="major" />
                                    <div class="row uniform">
                                        <div class="3u 12u$(xsmall)">
                                            <strong> Parentesco: </strong>
                                            <?php echo $row['parentesco']; ?>
                                        </div>
                                        <div class="6u 12u$(xsmall)">
                                            <strong>Nombre: </strong>
                                            <?php echo $row['nombre'] ?>
                                        </div>
                                        <div class="3u 12u$(xsmall)">
                                            <strong>Edad: </strong> 
                                            <?php echo $row['edad'] ?>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="9u 12u$(xsmall)">
                                            <strong>Dirección: </strong> 
                                            <?php echo $row['direccion'] ?>
                                        </div>
                                        <div class="3u 12u$(xsmall)">
                                            <strong>Teléfono: </strong> 
                                            <?php echo $row['telefono'] ?>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="12u 12u$(xsmall)">
                                            <strong>Observaciones: </strong> 
                                            <?php echo $row['observa'] ?>
                                        </div>
                                    </div>
                                <?php } 
                            } ?>
                        </div>
                        <div class="box">
                            <h4>Información aportada por la familia sobre la situación de la NNA</h4>
                            <?php if (empty($infoFam)) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u$">
                                            <textarea name="infoFam" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000" required></textarea>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="12u$">
                                            <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                                <input type="submit" name="ReginfoFam" value="Guardar">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </form>
                            <?php } else { ?>
                                <strong><?php echo $infoFam; ?></strong>
                            <?php } ?>
                        </div>
                        <div class="box">
                            <h4>Elementos por preguntar a la familia</h4>
                            <?php if (empty($reg_fam)) { ?>
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
                                                    <th><input type="radio" id="p1si" name="p1" value="SI">
                                                        <label for="p1si"></label></th>
                                                    <th><input type="radio" id="p1no" name="p1" value="NO">
                                                        <label for="p1no"></label></th>
                                                    <th><input type="radio" id="p1na" name="p1" value="NO APLICA">
                                                        <label for="p1na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>2. ¿Tiene acta de nacimiento?</th>
                                                    <th><input type="radio" id="p2si" name="p2" value="SI">
                                                        <label for="p2si"></label></th>
                                                    <th><input type="radio" id="p2no" name="p2" value="NO">
                                                        <label for="p2no"></label></th>
                                                    <th><input type="radio" id="p2na" name="p2" value="NO APLICA">
                                                        <label for="p2na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>3. ¿Hay algún hijo o hija que no viva con la familia?
                                                        <textarea name="tap3" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p3si" name="p3" value="SI">
                                                        <label for="p3si"></label></th>
                                                    <th><input type="radio" id="p3no" name="p3" value="NO">
                                                        <label for="p3no"></label></th>
                                                    <th><input type="radio" id="p3na" name="p3" value="NO APLICA">
                                                        <label for="p3na"></label></th>                     
                                                </tr>
                                                <tr>
                                                    <th>4. En caso de que algún hijo o hija no viva con la familia ¿Tiene convivencia con la NNA?
                                                        <textarea name="tap4" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p4si" name="p4" value="SI">
                                                            <label for="p4si"></label></th>
                                                    <th><input type="radio" id="p4no" name="p4" value="NO">
                                                            <label for="p4no"></label></th>
                                                    <th><input type="radio" id="p4na" name="p4" value="NO APLICA">
                                                        <label for="p4na"></label></th>                  
                                                </tr>
                                                <tr>
                                                    <th>5. ¿La opinión de la NNA es considerada y tomada en cuenta?</th>
                                                    <th><input type="radio" id="p5si" name="p5" value="SI">
                                                        <label for="p5si"></label></th>
                                                    <th><input type="radio" id="p5no" name="p5" value="NO">
                                                        <label for="p5no"></label></th>
                                                    <th><input type="radio" id="p5na" name="p5" value="NO APLICA">
                                                        <label for="p5na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>6. ¿Alguien lo cuida la mayor parte del tiempo? ¿Quién?
                                                        <textarea name="tap6" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p6si" name="p6" value="SI">
                                                        <label for="p6si"></label></th>
                                                    <th><input type="radio" id="p6no" name="p6" value="NO">
                                                        <label for="p6no"></label></th>
                                                    <th><input type="radio" id="p6na" name="p6" value="NO APLICA">
                                                        <label for="p6na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>7. ¿La NNA vive en una vivienda adecuada para su desarrollo?
                                                        <textarea name="tap7" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p7si" name="p7" value="SI">
                                                        <label for="p7si"></label></th>
                                                    <th><input type="radio" id="p7no" name="p7" value="NO">
                                                        <label for="p7no"></label></th>
                                                    <th><input type="radio" id="p7na" name="p7" value="NO APLICA">
                                                        <label for="p7na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>8. ¿Ha visto peleas o cualquier otro tipo de violencia?¿Cómo fue?
                                                        <textarea name="tap8" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p8si" name="p8" value="SI">
                                                        <label for="p8si"></label></th>
                                                    <th><input type="radio" id="p8no" name="p8" value="NO">
                                                        <label for="p8no"></label></th>
                                                    <th><input type="radio" id="p8na" name="p8" value="NO APLICA">
                                                        <label for="p8na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>9. ¿Ha recibido golpes o insultos? ¿Por parte de quién?
                                                        <textarea name="tap9" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p9si" name="p9" value="SI">
                                                        <label for="p9si"></label></th>
                                                    <th><input type="radio" id="p9no" name="p9" value="NO">
                                                        <label for="p9no"></label></th>
                                                    <th><input type="radio" id="p9na" name="p9" value="NO APLICA">
                                                        <label for="p9na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>10. ¿Qué come normalmente? ¿Cuántas veces al día consume alimentos?
                                                        <textarea name="tap10" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p10si" name="p10" value="SI">
                                                        <label for="p10si"></label></th>
                                                    <th><input type="radio" id="p10no" name="p10" value="NO">
                                                        <label for="p10no"></label></th>
                                                    <th><input type="radio" id="p10na" name="p10" value="NO APLICA">
                                                        <label for="p10na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>11. ¿Cuándo fue la última vez que lo llevaron al doctor?
                                                        <textarea name="tap11" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p11si" name="p11" value="SI">
                                                        <label for="p11si"></label></th>
                                                    <th><input type="radio" id="p11no" name="p11" value="NO">
                                                        <label for="p11no"></label></th>
                                                    <th><input type="radio" id="p11na" name="p11" value="NO APLICA">
                                                        <label for="p11na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>12. ¿Tiene cartilla de vacunación?</th>
                                                    <th><input type="radio" id="p12si" name="p12" value="SI">
                                                        <label for="p12si"></label></th>
                                                    <th><input type="radio" id="p12no" name="p12" value="NO">
                                                        <label for="p12no"></label></th>
                                                    <th><input type="radio" id="p12na" name="p12" value="NO APLICA">
                                                        <label for="p12na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>13. ¿Está completa?</th>
                                                    <th><input type="radio" id="p13si" name="p13" value="SI">
                                                        <label for="p13si"></label></th>
                                                    <th><input type="radio" id="p13no" name="p13" value="NO">
                                                        <label for="p13no"></label></th>
                                                    <th><input type="radio" id="p13na" name="p13" value="NO APLICA">
                                                        <label for="p13na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>14. ¿Ha estado enfermo? ¿De qué?
                                                        <textarea name="tap14" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p14si" name="p14" value="SI">
                                                        <label for="p14si"></label></th>
                                                    <th><input type="radio" id="p14no" name="p14" value="NO">
                                                        <label for="p14no"></label></th>
                                                    <th><input type="radio" id="p14na" name="p14" value="NO APLICA">
                                                        <label for="p14na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>15. ¿Recibió asistencia médica?</th>
                                                    <th><input type="radio" id="p15si" name="p15" value="SI">
                                                        <label for="p15si"></label></th>
                                                    <th><input type="radio" id="p15no" name="p15" value="NO">
                                                        <label for="p15no"></label></th>
                                                    <th><input type="radio" id="p15na" name="p15" value="NO APLICA">
                                                        <label for="p15na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>16. ¿Tiene servicio médico de seguro social, seguro popular, ISSSTE, PEMEX o SEDENA?
                                                        <textarea name="tap16" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="Especifique" maxlength="500"></textarea></th>
                                                    <th><input type="radio" id="p16si" name="p16" value="SI">
                                                        <label for="p16si"></label></th>
                                                    <th><input type="radio" id="p16no" name="p16" value="NO">
                                                        <label for="p16no"></label></th>
                                                    <th><input type="radio" id="p16na" name="p16" value="NO APLICA">
                                                        <label for="p16na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>17. ¿Alguno de sus hijos o hijas tiene alguna discapacidad?
                                                        <textarea name="tap17" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p17si" name="p17" value="SI">
                                                        <label for="p17si"></label></th>
                                                    <th><input type="radio" id="p17no" name="p17" value="NO">
                                                        <label for="p17no"></label></th>
                                                    <th><input type="radio" id="p17na" name="p17" value="NO APLICA">
                                                        <label for="p17na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>18. Si requiere aditamentos (silla de ruedas, muleta, lentes, etc.) ¿Cuenta con ellos?
                                                        <textarea name="tap18" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p18si" name="p18" value="SI">
                                                        <label for="p18si"></label></th>
                                                    <th><input type="radio" id="p18no" name="p18" value="NO">
                                                        <label for="p18no"></label></th>
                                                    <th><input type="radio" id="p18na" name="p18" value="NO APLICA">
                                                        <label for="p18na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>19. ¿La NNA se encuentra inscrito en la escuela?
                                                        <textarea name="tap19" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p19si" name="p19" value="SI">
                                                        <label for="p19si"></label></th>
                                                    <th><input type="radio" id="p19no" name="p19" value="NO">
                                                        <label for="p19no"></label></th>
                                                    <th><input type="radio" id="p19na" name="p19" value="NO APLICA">
                                                        <label for="p19na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>20. ¿La NNA asiste regularmente a la escuela?
                                                        <textarea name="tap20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p20si" name="p20" value="SI">
                                                        <label for="p20si"></label></th>
                                                    <th><input type="radio" id="p20no" name="p20" value="NO">
                                                        <label for="p20no"></label></th>
                                                    <th><input type="radio" id="p20na" name="p20" value="NO APLICA">
                                                        <label for="p20na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>21. ¿Se da algun seguimiento a su desempeño escolar? ¿Quién?
                                                        <textarea name="tap21" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p21si" name="p21" value="SI">
                                                        <label for="p21si"></label></th>
                                                    <th><input type="radio" id="p21no" name="p21" value="NO">
                                                        <label for="p21no"></label></th>
                                                    <th><input type="radio" id="p21na" name="p21" value="NO APLICA">
                                                        <label for="p21na"></label></th>
                                                </tr>
                                                <tr>
                                                    <th>22. ¿Realiza actividades recreativas? ¿Con quién y de qué forma socializa?
                                                        <textarea name="tap22" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1000"></textarea></th>
                                                    <th><input type="radio" id="p22si" name="p22" value="SI">
                                                        <label for="p22si"></label></th>
                                                    <th><input type="radio" id="p22no" name="p22" value="NO">
                                                        <label for="p22no"></label></th>
                                                    <th><input type="radio" id="p22na" name="p22" value="NO APLICA">
                                                        <label for="p22na"></label></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                            <input type="submit" name="Preguntas" value="Guardar">
                                        <?php } ?>
                                    </div>
                                </form>
                            <?php } else { ?>
                                <div class="table-wrapper">

                                    <?php while ($row=$epreg->fetch_assoc()) { ?>
                                        <table class="alt">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th></th>                        
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>1. ¿Cuenta con registro en el estado familiar? <?php echo $row['registro_fam']; ?></th>                        
                                                </tr>
                                                <tr>
                                                    <th>2. ¿Tiene acta de nacimiento? <?php echo $row['acta_nac']; ?></th>                        
                                                </tr>
                                                <tr>
                                                    <th>3. ¿Hay algún hijo o hija que no viva con la familia? <?php echo $row['hijo_sin_res'].", ".$row['hijo_sin']; ?></th>                     
                                                </tr>
                                                <tr>
                                                    <th>4. En caso de que algún hijo o hija no viva con la familia ¿Tiene convivencia con la NNA? <?php echo $row['hijo_nna_res'].", ".$row['hijo_nna']; ?></th>                  
                                                </tr>
                                                <tr>
                                                    <th>5. ¿La opinión de la NNA es considerada y tomada en cuenta? <?php echo $row['opinion_nna']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>6. ¿Alguien lo cuida la mayor parte del tiempo? ¿Quién? <?php  echo $row['cuidado_nna_res'].", ".$row['cuidado_nna']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>7. ¿La NNA vive en una vivienda adecuada para su desarrollo? <?php  echo $row['vivienda_nna_res'].", ".$row['vivienda_nna']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>8. ¿Ha visto peleas o cualquier otro tipo de violencia?¿Cómo fue? <?php  echo $row['violencia_nna_res'].", ".$row['violencia_nna']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>9. ¿Ha recibido golpes o insultos? ¿Por parte de quién? <?php  echo $row['maltrato_nna_res'].", ".$row['maltrato_nna']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>10. ¿Qué come normalmente? ¿Cuántas veces al día consume alimentos? <?php  echo $row['alimentacion_nna_res'].", ".$row['alimentacion_nna']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>11. ¿Cuándo fue la última vez que lo llevaron al doctor? <?php echo $row['doctor_nna_res'].", ".$row['doctor_nna']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>12. ¿Tiene cartilla de vacunación? <?php echo $row['cartilla_vacunacion']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>13. ¿Está completa? <?php echo $row['cartilla_completa']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>14. ¿Ha estado enfermo? ¿De qué? <?php echo $row['enfermo_nna_res'].", ". $row['enfermo_nna']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>15. ¿Recibió asistencia médica? <?php echo $row['asistencia_medica']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>16. ¿Tiene servicio médico de seguro social, seguro popular, ISSSTE, PEMEX o SEDENA? <?php echo $row['servicio_medico_res'].", ". $row['servicio_medico']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>17. ¿Alguno de sus hijos o hijas tiene alguna discapacidad? <?php echo $row['discapacidad_res'].", ". $row['alguna_discapacidad']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>18. Si requiere aditamentos (silla de ruedas, muleta, lentes, etc.) ¿Cuenta con ellos? <?php echo $row['aditamentos_res'].", ". $row['aditamentos']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>19. ¿La NNA se encuentra inscrito en la escuela? <?php echo $row['nna_escuela_res'].", ". $row['nna_escuela']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>20. ¿La NNA asiste regularmente a la escuela? <?php echo $row['nna_asiste_res'].", ". $row['nna_asiste']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>21. ¿Se da algun seguimiento a su desempeño escolar? ¿Quién? <?php echo $row['desempeño_res'].", ". $row['desempeño']; ?></th>
                                                </tr>
                                                <tr>
                                                    <th>22. ¿Realiza actividades recreativas? ¿Con quién y de qué forma socializa? <?php echo $row['act_recreativas_res'].", ". $row['actividades_recreativas']; ?></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="box">
                            <h4>Grado de negación</h4>
                            <?php if (empty($gn)) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <input type="radio" id="na" name="neg" value="ALTO">
                                    <label for="na">ALTO</label>
                                    <input type="radio" id="nb" name="neg" value="BAJO">
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
                                                <th><input type="radio" id="n1si" name="n1" value="SI">
                                                    <label for="n1si"></label></th>
                                                <th><input type="radio" id="n1no" name="n1" value="NO">
                                                    <label for="n1no"></label></th>                   
                                            </tr>
                                            <tr>
                                                <th>¿Reconoce que, como persona adulta, <u>tiene la responsabilidad</u> de que las NNA tengan todo lo que necesitan para crecer bien y no sufrir vulneración de derechos?</th>
                                                <th><input type="radio" id="n2si" name="n2" value="SI">
                                                        <label for="n2si"></label></th>
                                                <th><input type="radio" id="n2no" name="n2" value="NO">
                                                        <label for="n2no"></label></th>                     
                                            </tr>
                                            <tr>
                                                <th>¿Reconoce que <u>necesita ayuda</u> para que la NNA tenga todo lo que necesita?</th>
                                                <th><input type="radio" id="n3si" name="n3" value="SI">
                                                    <label for="n3si"></label></th>
                                                <th><input type="radio" id="n3no" name="n3" value="NO">
                                                    <label for="n3no"></label></th>                     
                                            </tr> 
                                            <tr>
                                                <th>¿<u>Está dispuesto</u> a hacer esfurzos/compromisos para lograr lo necesario para que las NNA estén bien?</th>
                                                <th><input type="radio" id="n4si" name="n4" value="SI">
                                                    <label for="n4si"></label></th>
                                                <th><input type="radio" id="n4no" name="n4" value="NO">
                                                    <label for="n4no"></label></th>                     
                                            </tr>
                                        </tbody>
                                    </table>
                                    Descripción sobre el grado de negación de las personas adultas a cargo de la NNA
                                    <textarea name="descneg" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
                                    <br>
                                    <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                        <input type="submit" name="registrar_negacion" value="Guardar">
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <?php while ($row=$epreg2->fetch_assoc()) { ?>
                                    <?php echo $row['grado_negacion']; ?>
                                    <table>
                                        <thead>
                                            <tr>
                                                <td></td>
                                                <td></td>
                    
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th>¿La persona a cargo del cuidado de la NNA <u>reconoce que pasó</u> algo que le resulta a este perjudicial o lo pone en riesgo?</th>
                                                <th><?php echo $row['reconoce_paso']; ?></th>                 
                                            </tr>
                                            <tr>
                                                <th>¿Reconoce que, como persona adulta, <u>tiene la responsabilidad</u> de que las NNA tengan todo lo que necesitan para crecer bien y no sufrir vulneración de derechos?</th>
                                                <th><?php echo $row['tiene_responsabilidad']; ?></th>                     
                                            </tr>
                                            <tr>
                                                <th>¿Reconoce que <u>necesita ayuda</u> para que la NNA tenga todo lo que necesita?</th>
                                                <th><?php echo $row['necesita_ayuda']; ?></th>                     
                                            </tr> 
                                            <tr>
                                                <th>¿<u>Está dispuesto</u> a hacer esfurzos/compromisos para lograr lo necesario para que las NNA estén bien?</th>
                                                <th><?php echo $row['esta_dispuesto']; ?></th>                     
                                            </tr>
                                        </tbody>
                                    </table>
                                    Descripción sobre el grado de negación de las personas adultas a cargo de la NNA
                                    <textarea name="descneg" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled><?php echo $row['descripcion']; ?></textarea>
                                <?php } 
                            } ?>
                        </div>
                        <div class="box">
                            <h4>Observaciones sobre el grado de afectación emocional (actitud, disposición y estado de ánimo) y/o física (enfermedades, adicciones, discapacidad) de las personas adultas a cargo de la NNA</h4>
                            <?php if (empty($obs_afecta)) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <textarea name="afecta_emo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
                                    <br>
                                    <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                        <input type="submit" name="antepenultimo" value="guardar">
                                    <?php } ?>
                                </form>
                            <?php } else {
                                $r="SELECT observaciones_afecta from acercamiento_familiar where id_reporte='$idReporte'";
                                $er=$mysqli->query($r); 
                                while ($row=$er->fetch_assoc()) { ?>
                                    <strong><?php echo $row['observaciones_afecta']; ?></strong>
                                <?php }
                            } ?>
                        </div>
                        <div class="box">
                            <h4>Información aportada por la familia durante el dialogo experimental (que manifiestan necesitar para proteger mejor a la NNA)</h4>
                            <?php if (empty($dia_exp)) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <textarea name="dialogo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
                                    <br>
                                    <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                        <input type="submit" name="penultimo" value="registrar">
                                    <?php } ?>
                                </form>
                            <?php }else {
                                $r="SELECT dialogo_experimental from acercamiento_familiar where id_reporte='$idReporte'";
                                $er=$mysqli->query($r); 
                                while ($row=$er->fetch_assoc()) { ?>
                                    <strong><?php echo $row['dialogo_experimental']; ?></strong>
                                <?php } 
                            } ?>
                        </div>
                        <div class="box">
                            <h4>Otros datos u observaciones (información aportada por el entorno escolar, comunitario, institucional, etc.)</h4>
                            <?php if (empty($otros)) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <textarea name="otros_datos" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
                                    <br>
                                    <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                        <input type="submit" name="ultimo" value="registrar">
                                    <?php } ?>
                                </form>
                            <?php } else {
                                $r="SELECT observaciones from acercamiento_familiar where id_reporte='$idReporte'";
                                $er=$mysqli->query($r);
                                while ($row=$er->fetch_assoc()) { ?>
                                    <strong><?php echo $row['observaciones']; ?></strong>
                                <?php } 
                            } ?>
                        </div>
                        <div class="box">        
                            <?php if (empty($inter)) { ?>        
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="uniform row">
                                        <div class="4u 12u$(xsmall)">
                                            <strong>Numero de intervenciones realizadas:</strong>
                                        </div>
                                        <div class="4u 12u$(xsmall)">
                                            <input type="number" name="numI">
                                        </div>
                                        <div class="4u 12u$(xsmall)">
                                            <?php if ($asig==$idDEPTO or $_SESSION['departamento']==16) { ?>
                                                <input type="submit" name="ninter" value="registrar">
                                            <?php } ?>
                                        </div>
                                    </div>
                                </form>
                            <?php } else { ?>
                                <strong>Numero de intervenciones realizadas: <?php echo $inter; ?></strong>
                            <?php } ?> 
                        </div>
                    </div>
                </div>
            </div> <!--cierre de main -->

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
            </div> <!--cierre de menu-->
        </div> <!--cierre de lo wrapper-->

        <!-- Scripts -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/skel.min.js"></script>
        <script src="assets/js/util.js"></script>
        <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
        <script src="assets/js/main.js"></script>
    </body>
</html>