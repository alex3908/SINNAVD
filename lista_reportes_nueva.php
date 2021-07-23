<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
$zonahoraria = date_default_timezone_get();
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idDEPTO = $_SESSION['id'];
$pv = "SELECT id, id_depto, id_personal from departamentos where id='$idDEPTO'";
$epv = $mysqli->query($pv);
while ($row = $epv->fetch_assoc()) {
    $idEspecial = ['id'];
    $idDep = $row['id_depto'];
    $idPersonal = $row['id_personal'];
}
//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
$reportesvd = "SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo = $mysqli->query($reportesvd);
$wow = $erepo->num_rows;

//seleccion datos reportes
$qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
		as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ps,
		d2.responsable
		 as asig_ts, hat.estadoAtencion from reportes_vd r inner join posible_caso pc
		 on r.id_posible_caso=pc.id
		 left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps
		left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts
		left join departamentos d1 on d1.id=hps.id_departamentos_asignado
		 left join departamentos d2 on d2.id=hts.id_departamentos_asignado
		 left join historico_atenciones_pos_casos hat on hat.id=pc.id_estado_atencion
		 where r.activo=1
		order by id desc limit 25";

if (isset($_POST['txtbusqueda'])) {
    $palabra = mysqli_real_escape_string($mysqli, $_POST['txtbusqueda']);
    $tipo = $_POST['tipoBsq'];
    switch ($tipo) {
        case 1:
            $qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
					as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ps,
					d2.responsable as asig_ts, hat.estadoAtencion
					from reportes_vd r inner join posible_caso pc on r.id_posible_caso=pc.id
					left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps
					left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts
					left join departamentos d1 on d1.id=hps.id_departamentos_asignado
					left join departamentos d2 on d2.id=hts.id_departamentos_asignado
					left join historico_atenciones_pos_casos hat on hat.id=pc.id_estado_atencion
					where r.activo=1 and (r.folio like '%$palabra%' or pc.folio like '%$palabra%') order by r.id desc";
            break;
        case 2:
            $qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
			as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ps,
			d2.responsable as asig_ts, hat.estadoAtencion, match (u.nombre, u.apellido_p, u.apellido_m) against ('$palabra') as p
			from reportes_vd r inner join posible_caso pc on r.id_posible_caso=pc.id
			left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps
			left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts
			left join departamentos d1 on d1.id=hps.id_departamentos_asignado
			left join departamentos d2 on d2.id=hts.id_departamentos_asignado
			left join historico_atenciones_pos_casos hat on hat.id=pc.id_estado_atencion
			left join nna_reportados u on u.id_posible_caso=pc.id
			where match(u.nombre,  u.apellido_p,  u.apellido_m) against ('$palabra')  order by p desc";
            break;
        case 3:
            $qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
					as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ps,
					d2.responsable as asig_ts, hat.estadoAtencion
					from reportes_vd r inner join posible_caso pc on r.id_posible_caso=pc.id
					left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps
					left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts
					left join departamentos d1 on d1.id=hps.id_departamentos_asignado
					left join departamentos d2 on d2.id=hts.id_departamentos_asignado
					left join historico_atenciones_pos_casos hat on hat.id=pc.id_estado_atencion
					where r.activo=1 and (r.ubicacion like '%$palabra%') order by r.id desc";
            break;
        case 4:
            $qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
					as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ps,
					d2.responsable as asig_ts, hat.estadoAtencion
					from reportes_vd r inner join posible_caso pc on r.id_posible_caso=pc.id
					left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps
					left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts
					left join departamentos d1 on d1.id=hps.id_departamentos_asignado
					left join departamentos d2 on d2.id=hts.id_departamentos_asignado
					left join historico_atenciones_pos_casos hat on hat.id=pc.id_estado_atencion
					where r.activo=1 and (r.persona_reporte like '%$palabra%') order by r.id desc";
            break;
        case 5:
            $qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
					as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ps,
					d2.responsable as asig_ts, hat.estadoAtencion
					from reportes_vd r inner join posible_caso pc on r.id_posible_caso=pc.id
					left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps
					left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts
					left join departamentos d1 on d1.id=hps.id_departamentos_asignado
					left join departamentos d2 on d2.id=hts.id_departamentos_asignado
					left join historico_atenciones_pos_casos hat on hat.id=pc.id_estado_atencion
					where r.activo=1 and (d1.responsable like '%$palabra%' or d2.responsable like '%$palabra%') order by r.id desc";
            break;
    }
}

if (!empty($_POST['btnNoAsig'])) { //consulta de no asignados en ningun area y no atendidos
    $qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
			as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, pc.id_asignado_ts as asig_ps,
			pc.id_asignado_ps as asig_ts, pc.id_estado_atencion as estadoAtencion
			FROM posible_caso pc inner join reportes_vd r on r.id_posible_caso=pc.id
			left join historico_atenciones_pos_casos h on pc.id_estado_atencion=h.id
			where pc.id_asignado_ts=0  and pc.id_asignado_ps=0 and pc.id_asignado_juridico=0 and (pc.id_estado_atencion=0 or h.estadoAtencion=1) and pc.activo=1
			order by r.id desc";
}
if (!empty($_POST['btnNoAten'])) { //consulta para no atendidos (estado 1)
    $qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
			as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ps,
			d2.responsable as asig_ts, h.estadoAtencion
			FROM posible_caso pc
			inner join reportes_vd r on pc.id=r.id_posible_caso
			left join historico_asignaciones_psicologia hp on pc.id_asignado_ps=hp.id
			left join historico_asignaciones_trabajo_social ht on pc.id_asignado_ts=ht.id
			left join departamentos d1 on hp.id_departamentos_asignado=d1.id
			left join departamentos d2 on ht.id_departamentos_asignado=d2.id
			left join historico_atenciones_pos_casos h on pc.id_estado_atencion=h.id
			where r.activo=1 and pc.id_estado_atencion=0 or h.estadoAtencion=1 order by r.id desc";
}
if (!empty($_POST['btnProc'])) { //consulta para en proceso (estado 2)
    $qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
			as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ps,
			d2.responsable as asig_ts, h.estadoAtencion
			FROM posible_caso pc
			inner join reportes_vd r on pc.id=r.id_posible_caso
			left join historico_asignaciones_psicologia hp on pc.id_asignado_ps=hp.id
			left join historico_asignaciones_trabajo_social ht on pc.id_asignado_ts=ht.id
			left join departamentos d1 on hp.id_departamentos_asignado=d1.id
			left join departamentos d2 on ht.id_departamentos_asignado=d2.id
			left join historico_atenciones_pos_casos h on pc.id_estado_atencion=h.id
			where r.activo=1 and h.estadoAtencion=2 order by r.id desc";
}
if (!empty($_POST['btnNeg'])) { //consulta para atendidos negativos (estado 3)
    $qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
			as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ps,
			d2.responsable as asig_ts, h.estadoAtencion
			FROM posible_caso pc
			inner join reportes_vd r on pc.id=r.id_posible_caso
			left join historico_asignaciones_psicologia hp on pc.id_asignado_ps=hp.id
			left join historico_asignaciones_trabajo_social ht on pc.id_asignado_ts=ht.id
			left join departamentos d1 on hp.id_departamentos_asignado=d1.id
			left join departamentos d2 on ht.id_departamentos_asignado=d2.id
			left join historico_atenciones_pos_casos h on pc.id_estado_atencion=h.id
			where r.activo=1 and h.estadoAtencion=3 order by r.id desc";
}
if (!empty($_POST['btnPos'])) { //consulta para atendidos positivos (estado 4)
    $qReportes = "SELECT pc.folio as foliopc, pc.id as idPc, r.id, r.folio, date_format(r.fecha_registro, '%d/%m/%Y')
			as fecha, r.nom_nna, r.ubicacion, r.persona_reporte, d1.responsable as asig_ps,
			d2.responsable as asig_ts, h.estadoAtencion
			FROM posible_caso pc
			inner join reportes_vd r on pc.id=r.id_posible_caso
			left join historico_asignaciones_psicologia hp on pc.id_asignado_ps=hp.id
			left join historico_asignaciones_trabajo_social ht on pc.id_asignado_ts=ht.id
			left join departamentos d1 on hp.id_departamentos_asignado=d1.id
			left join departamentos d2 on ht.id_departamentos_asignado=d2.id
			left join historico_atenciones_pos_casos h on pc.id_estado_atencion=h.id
			where r.activo=1 and h.estadoAtencion=4 order by r.id desc limit 25";
}

$rReportes = $mysqli->query($qReportes);
$total = $rReportes->num_rows;
/*$salida="";
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
</thead> <tbody>"    ;
while ($fila = $rReportes->fetch_assoc()) {
$idPC=$fila['idPc'];
if(empty($fila['nom_nna'])){
$qNnaReportado="SELECT concat(nombre, ' ', apellido_p, ' ', apellido_m)
as nomb FROM nna_reportados where id_posible_caso=$idPC";
$rNnaReportado=$mysqli->query($qNnaReportado);
$nnas="";
while($filaNna = $rNnaReportado->fetch_assoc()){
$nnas.= $filaNna['nomb'].', ';
}
} else $nnas=$fila['nom_nna'];
$salida.="

<tr>
<td>".$fila['folio']."</td>
<td>".$fila['fecha']."</td>
<td>".$nnas."</td>
<td>".$fila['ubicacion']."</td>
<td>".$fila['persona_reporte']."</td>
<td>".$fila['asig_ts']."</td>
<td>".$fila['asig_ps']."</td>
<td>".$fila['estadoAtencion']."</td>
</tr>";
}
$salida.="</tbody></table>";
} else $salida.= "Reportes mostrados: ".$total;*/

?>
<!DOCTYPE HTML>
<html>

<head lang="es-ES">
    <title>Lista reportes</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="shortcut icon" href="images/favicon.png" type="image/png" />
</head>

<body onload="buscar_datos">
    <div id="wrapper">
        <div id="main">
            <div class="inner" style="padding-right: 5px; padding-left: 50px;">
                <div class="box alt" align="center">
                    <div class="row 10% uniform">
                        <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
                        <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
                        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
                    </div>
                </div>
                <script>
                function mensaje() {
                    alert("No tiene los permisos para esta acción");
                }
                </script>
                <div class="row uniform">
                    <div class="2u 12$(xsmall">
                    </div>
                    <div class="8u 12$(xsmall)" aling="center">
                        <h2>Reportes de posible vulneración de derechos a NNA</h2>
                    </div>
                    <div class="2u 12$(xsmall">
                        <?php if ($idPersonal == 1 or $idPersonal == 3 or $idPersonal == 5 or $idEspecial = 70) {?>
                        <input type="button" value="alta" onclick="location='reg_reporte.php'" class="button special">
                        <?php } else {?>
                        <input type="button" value="alta" onclick="mensaje()" class="button special">
                        <?php }?>
                    </div>
                </div>
                <div class="row uniform">
                    <div class="12u 12$(xsmall">
                        <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
                            <table class="alt">
                                <thead>
                                    <tr>
                                        <td>Reportes no asignados: <input type="submit" class="button small"
                                                name="btnNoAsig" value="<?php $ra = "SELECT count(r.id) as total
											FROM posible_caso pc inner join reportes_vd r on r.id_posible_caso=pc.id
											left join historico_atenciones_pos_casos h on pc.id_estado_atencion=h.id
											where r.activo=1 and pc.id_asignado_ts=0 and pc.id_asignado_ps=0 and pc.id_asignado_juridico=0 and (pc.id_estado_atencion=0 or h.estadoAtencion=1)";
$era = $mysqli->query($ra);
while ($row = $era->fetch_assoc()) {
    echo $row['total'];
}?> "> </td>
                                        <td>Reportes no atendidos: <input type="submit" class="button small"
                                                name="btnNoAten" value="<?php $ra = "SELECT count(r.id) as total FROM posible_caso pc inner join reportes_vd r on pc.id=r.id_posible_caso
											left join historico_atenciones_pos_casos h on pc.id_estado_atencion=h.id
											where r.activo=1 and pc.id_estado_atencion=0 or h.estadoAtencion=1";
$era = $mysqli->query($ra);
while ($row = $era->fetch_assoc()) {
    echo $row['total'];
}?>"> </td>
                                        <td>Reportes en proceso: <input type="submit" class="button small"
                                                name="btnProc" value="<?php $ra = "SELECT count(r.id) as total FROM posible_caso pc inner join reportes_vd r on pc.id=r.id_posible_caso
											left join historico_atenciones_pos_casos h on pc.id_estado_atencion=h.id
											where r.activo=1 and h.estadoAtencion=2";
$era = $mysqli->query($ra);
while ($row = $era->fetch_assoc()) {
    echo $row['total'];
}?>"></td>
                                        <td>Reportes atendidos negativos: <input type="submit" class="button small"
                                                name="btnNeg" value="<?php $ra = "SELECT count(r.id) as total FROM posible_caso pc inner join reportes_vd r on pc.id=r.id_posible_caso
											left join historico_atenciones_pos_casos h on pc.id_estado_atencion=h.id
											where r.activo=1 and h.estadoAtencion=3";
$era = $mysqli->query($ra);
while ($row = $era->fetch_assoc()) {
    echo $row['total'];
}?>"></td>
                                        <td>Reportes tendidos positivos: <input type="submit" class="button small"
                                                name="btnPos" value="<?php $ra = "SELECT count(r.id) as total FROM posible_caso pc inner join reportes_vd r on pc.id=r.id_posible_caso
											left join historico_atenciones_pos_casos h on pc.id_estado_atencion=h.id
											where r.activo=1 and h.estadoAtencion=4";
$era = $mysqli->query($ra);
while ($row = $era->fetch_assoc()) {
    echo $row['total'];
}?>"></td>
                                    </tr>

                                </thead>
                            </table>
                        </form>
                        <form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>"
                            onSubmit="return validarForm(this)">
                            <p>
                            <div class="row uniform">
                                <div class="3u">
                                    <select id="tipoBsq" name="tipoBsq" required="true">
                                        <option value="1">Folio del posible caso o reporte</option>
                                        <option value="2">Nombre del NNA</option>
                                        <option value="3">Ubicación</option>
                                        <option value="4">Persona que reportó</option>
                                        <option value="5">Personal asignado</option>
                                    </select>
                                    <label>Seleccione el tipo de busqueda</label>
                                </div>
                                <div class="9u">
                                    <input type="text" name="txtbusqueda" placeholder="Ingrese el valor a buscar"
                                        onkeyup="buscar_datos();">
                                    <label>Presione la tecla "Enter" para buscar</label>
                                </div>

                            </div>
                            <div class="row uniform">
                                <div class="5u 12$(xsmall">
                                </div>
                                <div class="6u 12$(xsmall)" aling="center">
                                    <h4 onclick="location='lista_reportes_nueva.php?estRep=0'">Total reportes: <?php $ra = "SELECT count(id) as total FROM reportes_vd";
$era = $mysqli->query($ra);
while ($row = $era->fetch_assoc()) {
    echo $row['total'];
}?>
                                    </h4>
                                </div>
                            </div>
                            </p>
                        </form>
                        <div id="datos">
                            <table>
                                <thead>
                                    <tr>
                                        <td colspan="3">Reportes mostrados: <?=$total?></td>
                                    </tr>
                                    <tr>
                                        <th>FOLIO PC</th>
                                        <th>FOLIO REPORTE</th>
                                        <th>FECHA</th>
                                        <th>NNA</th>
                                        <th>UBICACIÓN</th>
                                        <th>PERSONA QUE REPORTO</th>
                                        <th>T.S.</th>
                                        <th>PSIC.</th>
                                        <th>ESTATUS</th>
                                    </tr>
                                </thead>

                                <body>
                                    <?php
while ($fila = $rReportes->fetch_assoc()) {
    $idPC = $fila['idPc'];
    if (empty($fila['nom_nna'])) {
        $qNnaReportado = "SELECT concat(nombre, ' ', apellido_p, ' ', apellido_m)
												as nomb FROM nna_reportados where id_posible_caso=$idPC and activo=1";
        $rNnaReportado = $mysqli->query($qNnaReportado);
        $nnas = "";
        while ($filaNna = $rNnaReportado->fetch_assoc()) {
            $nnas .= $filaNna['nomb'] . ', ';
        }
    } else {
        $nnas = $fila['nom_nna'];
    }

    ?>
                                    <tr>
                                        <td><a
                                                href="perfil_posible_caso.php?idPosibleCaso=<?=$idPC?>"><?=$fila['foliopc']?></a>
                                        </td>
                                        <td><?=$fila['folio']?></td>
                                        <td><?=$fila['fecha']?></td>
                                        <td><?=$nnas?></td>
                                        <td><?=$fila['ubicacion']?></td>
                                        <td><?=$fila['persona_reporte']?></td>
                                        <td><?php if (is_null($fila['asig_ts']) or $fila['asig_ts'] == '0') {
        if (($idDep == 9 and $idPersonal == 3) or ($idDep >= 18 and $idDep <= 33 and $idPersonal == 5) or ($idPersonal == 1 and $idDep == 16)) {?>
                                            <input type="button" class="special button fit small" name="AsignarTs"
                                                value="Asignar"
                                                onclick="location='asignar_posible_caso.php?id=<?=$idPC;?>&area=2'">
                                            <?php } else {?>
                                            <input type="button" class="special button fit small" name="AsignarTs"
                                                value="No asignado">
                                            <?php }} else {
        echo $fila['asig_ts'];
    }
    ?>
                                        </td>
                                        <td><?php if (is_null($fila['asig_ps']) or $fila['asig_ps'] == '0') {
        if (($idDep == 9 and $idPersonal == 3) or ($idDep >= 18 and $idDep <= 33 and $idPersonal == 5) or ($idPersonal == 1 and $idDep == 16)) {?>
                                            <input type="button" class="special button fit small" name="AsignarPs"
                                                value="Asignar"
                                                onclick="location='asignar_posible_caso.php?id=<?=$idPC;?>&area=3'">
                                            <?php } else {?>
                                            <input type="button" class="special button fit small" name="AsignarPs"
                                                value="No asignado">
                                            <?php }} else {
        echo $fila['asig_ps'];
    }
    ?>
                                        </td>
                                        <td><?php if ($fila['estadoAtencion'] == 0 or $fila['estadoAtencion'] == 1) {?>
                                            <img src="images/advertencia.png" width="50px" height="50px">
                                            <?php } else if ($fila['estadoAtencion'] == 2) {?>
                                            <img src="images/proceso.png" width="50px" height="50px">
                                            <?php } else if ($fila['estadoAtencion'] == 3) {?>
                                            <img src="images/Anegativo.png" width="50px" height="50px">
                                            <?php } else if ($fila['estadoAtencion'] == 4) {?>
                                            <img src="images/Apositivo.png" width="65px" height="65px">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <?php }?>
                                </body>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="sidebar">
            <div class="inner">
                <?php $_SESSION['spcargo'] = $idPersonal;?>
                <?php if ($idPersonal == 6) { //UIENNAVD?>
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
                <?php } else if ($idPersonal == 5) { //Subprocu ?>
                <!-- Menu -->
                <nav id="menu">
                    <header class="major">
                        <h2>Menú</h2>
                    </header>
                    <ul>
                        <li><a href="welcome.php">Inicio</a></li>
                        <li><a href="lista_personal.php">Personal</a></li>
                        <li><a href="lista_usuarios.php">Usuarios</a></li>
                        <li><a href="lista_reportes_nueva.php?estRep=0">Reportes
                                VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a>
                        </li>
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
                <?php } else {?>
                <!-- Menu -->
                <nav id="menu">
                    <header class="major">
                        <h2>Menú</h2>
                    </header>
                    <ul>
                        <li><a href="welcome.php">Inicio</a></li>
                        <li><a href="lista_personal.php">Personal</a></li>
                        <li><a href="lista_usuarios.php">Usuarios</a></li>
                        <?php if ($_SESSION['departamento'] == 7) {?>
                        <li><a href="canalizar.php">Canalizar visita</a></li>
                        <?php }?>
                        <li><a href="lista_reportes_nueva.php?estRep=0">Reportes
                                VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a>
                        </li>
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
                        <?php if (($_SESSION['departamento'] == 16) or ($_SESSION['departamento'] == 7)) {?>
                        <li><span class="opener">Visitas</span>
                            <ul>
                                <li><a href="editar_visitadepto.php">Editar departamento</a></li>
                                <li><a href="editar_visitarespo.php">Editar responsable</a></li>
                                <li><a href="eliminar_visita.php">Eliminar</a></li>
                            </ul>
                        </li>
                        <?php }?>
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
                        <?php if ($_SESSION['departamento'] == 16 or $_SESSION['departamento'] == 14) {?>
                        <li><a href="reg_actccpi.php">CCPI</a></li>
                        <?php }?>
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
    <!--cierre de wrapper-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script src="assets/js/main.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
</body>

</html>