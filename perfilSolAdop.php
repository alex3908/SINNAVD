<?php
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idDEPTO = $_SESSION['id'];
$pv = "SELECT id, id_depto, id_personal, perfil, responsable from departamentos where id='$idDEPTO'";
$epv = $mysqli->query($pv);
while ($row = $epv->fetch_assoc()) {
    $idDepartamento = $row['id_depto'];
    $idPersonal = $row['id_personal'];
    $idPerfil = $row['perfil'];
    $asignado = $row['responsable'];
    $id = $row['id'];
}
$id = 0;
$incremento = 0;
$hoy2 = date("Y-m-d");
$hoy = strtotime(date("Y-m-d"));
$fecha_entrega = "";
$fecha_entregaTs = "";
$entregado = 0;
$entregadoTs = 0;

//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
$reportesvd = "SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo = $mysqli->query($reportesvd);
$wow = $erepo->num_rows;

$idSolicitud = $_GET['id'];

$querySeg = "SELECT COUNT(*) as num_eguimientos FROM seguimiento_adop WHERE id_solicitud = $idSolicitud";
$resultSeg = mysqli_query($mysqli, $querySeg);

$numeroSeguimiento = implode($resultSeg->fetch_assoc());

$sql2 = "SELECT entregado FROM entrevistas_rel_psico
    WHERE id_solicitud = $idSolicitud";
$result2 = mysqli_query($mysqli, $sql2);

if (!empty($result2) and mysqli_num_rows($result2) > 0) {
    $entregado = implode($result2->fetch_assoc());
}

$sql3 = "SELECT entregado FROM entrevistas_rel_ts
    WHERE id_solicitud = $idSolicitud";
$result3 = mysqli_query($mysqli, $sql3);

if (!empty($result3) and mysqli_num_rows($result3) > 0) {
    $entregadoTs = implode($result3->fetch_assoc());
}

$query = "SELECT id,id_solicitud, date_format(entrevista_ini, '%d/%m/%Y') as entrevista_ini, date_format(entrevista_fin, '%d/%m/%Y') as entrevista_fin, date_format(fecha_entrega, '%d/%m/%Y') as fecha_entrega
FROM entrevistas_rel_psico WHERE id_solicitud = $idSolicitud";
$relEntrevistaPsico = $mysqli->query($query);

$query = "SELECT id,id_solicitud, date_format(entrevista_ini, '%d/%m/%Y') as entrevista_ini, date_format(entrevista_fin, '%d/%m/%Y') as entrevista_fin, date_format(fecha_entrega, '%d/%m/%Y') as fecha_entrega
FROM entrevistas_rel_ts WHERE id_solicitud = $idSolicitud";
$relEntrevistaTs = $mysqli->query($query);

$qSolicitud = "SELECT folio_solicitud, numAudiencia, numSolicitud, fecha_entrega_solicitud, date_format(fecha_entrega_solicitud, '%d/%m/%Y') as fecha_sol, hi.estado,
	date_format(s.fecha_registro, '%d/%m/%Y %H:%i:%m') as fecha_reg, d.responsable, d.id as id_respo_reg,
	if(s.tipo_solicitante=1, 'SOLTERO', if(tipo_solicitante=2, 'CASADOS', 'UNION LIBRE')) as tipo_solicitante, s.procedencia_solicitantes, numPruebaVIH,
	numAntidrogas, noConsentimiento, noEdadMin, noMenor18, noMayor45, noRecursos, noBenefica, noBuenasCostumbres, noSalud, noMatrimonio, noConcubinato,
	noInexistenciaMatrimonio, requisitoNo, motivoNo, ps.responsable as asig_ps, ts.responsable as asig_ts, ps.id as idPS, ts.id as idTS
	FROM solicitudesadop s
	inner join historico_estados_solicitud h on h.id=s.id_estado_solicitud
	inner join cat_estados_sol_adop hi on hi.id=h.id_estado
	inner join departamentos d on d.id=s.id_personal_registro
	left join hst_asignacion_ts_adop hts on s.id_asig_ts=hts.id
	left join departamentos ts on hts.id_departamento_asig=ts.id
	left join hst_asignacion_psico_adop hps on s.id_asig_ps=hps.id
	left join departamentos ps on hps.id_departamento_asig=ps.id
	where idSolicitud=$idSolicitud";
//echo $qSolicitud;
$qSolicitud = $mysqli->query($qSolicitud);
while ($rw = $qSolicitud->fetch_assoc()) {
    $folio_solicitud = $rw['folio_solicitud'];
    $numAudiencia = $rw['numAudiencia'];
    $numSolicitud = $rw['numSolicitud'];
    $fecha_sol = $rw['fecha_sol'];
    $fecha_entrega_solicitud = $rw['fecha_entrega_solicitud'];
    $estado = $rw['estado'];
    $fecha_reg = $rw['fecha_reg'];
    $responsable = $rw['responsable'];
    $id_respo_reg = $rw['id_respo_reg'];
    $tipo_solicitante = $rw['tipo_solicitante'];
    $procedencia_solicitantes = $rw['procedencia_solicitantes'];
    $numPruebaVIH = $rw['numPruebaVIH'];
    $numAntidrogas = $rw['numAntidrogas'];
    $noConsentimiento = $rw['noConsentimiento'];
    $noEdadMin = $rw['noEdadMin'];
    $noMenor18 = $rw['noMenor18'];
    $noMayor45 = $rw['noMayor45'];
    $noRecursos = $rw['noRecursos'];
    $noBenefica = $rw['noBenefica'];
    $noBuenasCostumbres = $rw['noBuenasCostumbres'];
    $noSalud = $rw['noSalud'];
    $noMatrimonio = $rw['noMatrimonio'];
    $noConcubinato = $rw['noConcubinato'];
    $noInexistenciaMatrimonio = $rw['noInexistenciaMatrimonio'];
    $requisitoNo = $rw['requisitoNo'];
    $motivoNo = $rw['motivoNo'];
    $asig_ps = $rw['asig_ps'];
    $idPS = $rw['idPS'];
    $asig_ts = $rw['asig_ts'];
    $idTS = $rw['idTS'];
}

$qusuarios = "SELECT id_usuario, nombre, apellido_p, apellido_m, s.sexo, ec.estado_civil, date_format(fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento, + year(curdate())-year(fecha_nacimiento) + if(date_format(curdate(),'%m-%d') > date_format(fecha_nacimiento,'%m-%d'), 0 , -1) as edad
 		FROM usuarios u
 		inner join sexo s on s.id=u.id_sexo
 		left join cat_estado_civil ec on u.id_estado_civil=ec.id
 		inner join solicitantes_adop so on so.id_usuario= u.id
 		where so.id_solicitud=$idSolicitud";
$qusuarios = $mysqli->query($qusuarios);

// if ($estado == 'EN ESPERA DE INTEGRAR EXPEDIENTE') {

//     $fecha_lim = strtotime('+6 month', strtotime($fecha_entrega_solicitud)); //establece la fecha limite para integrar el expediente
//     $fecha_limite = date('d/m/Y', $fecha_lim);
//     if ($hoy <= $fecha_lim) {
//         $mensaje1 = "Tiene hasta el $fecha_limite para integar el expediente";
//         $tiempoExcedido = false;
//     } else {
//         $mensaje1 = "La fecha limite fue $fecha_limite. El tiempo para integrar el expediente ha sido excedido";
//         $tiempoExcedido = true;
//     }

// }
$psicologos = "SELECT id, responsable from departamentos where id_depto=11 and perfil=3";
if (!empty($asig_ps)) {
    $psicologos .= " and id!=$idPS";
}

$psicologos = $mysqli->query($psicologos);
$trabajadoresSol = "SELECT id, responsable from departamentos where id_depto=11 and perfil=2";
if (!empty($asig_ts)) {
    $trabajadoresSol .= " and id!=$idPS";
}

$trabajadoresSol = $mysqli->query($trabajadoresSol);

$entrev_psico = "SELECT e.id, date_format(fecha_entrevista, '%d/%m/%Y %H:%i:%s') as fecha_ent, responsable, date_format(fecha_registro,'%d/%m/%Y %H:%i:%s') as fecha_reg, band
FROM entrevistas_pscio_adop e join departamentos d on d.id = e.id_responsable where id_solicitud=$idSolicitud order by id";
$qEntrev_psico = $mysqli->query($entrev_psico);

$qEntrev_ts = "SELECT e.id, date_format(fecha_entrevista, '%d/%m/%Y %H:%i:%s') as fecha_ent, responsable, date_format(fecha_registro,'%d/%m/%Y %H:%i:%s') as fecha_reg, band
FROM entrevistas_ts_adop e join departamentos d on d.id = e.id_responsable where id_solicitud=$idSolicitud order by id";
$qEntrev_ts = $mysqli->query($qEntrev_ts);

?>
<!DOCTYPE HTML>
<html>

<head lang="es-ES">
    <title>Solicitud <?=$folio_solicitud?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="shortcut icon" href="images/favicon.png" type="image/png" />
    <script type="text/javascript" src="jquery.min.js"></script>
    <script languague="javascript">
    var SolictVi = true;
    var VerSolititud = true;
    var verSeguimiento = true;

    function mostrarSol(bandera) {
        if (SolictVi === true) {
            div = document.getElementById('divSolicitantes');
            div.style.display = '';
            SolictVi = false
        } else {
            div = document.getElementById('divSolicitantes');
            div.style.display = 'none';
            SolictVi = true
        }
    }

    function mostrarSolicitud(bandera) {
        if (VerSolititud === true) {
            div = document.getElementById('divSolicitud');
            div.style.display = '';
            VerSolititud = false
        } else {
            div = document.getElementById('divSolicitud');
            div.style.display = 'none';
            VerSolititud = true
        }
    }



    function mostrarSeguimiento(bandera) {
        if (verSeguimiento === true) {
            div = document.getElementById('divSeguimiento');
            div.style.display = '';
            verSeguimiento = false
        } else {
            div = document.getElementById('divSeguimiento');
            div.style.display = 'none';
            verSeguimiento = true
        }
    }

    var editarps = false

    function edicion() {
        if (!editarps) {
            $("#Editar")[0].type = 'button';
            $("#ActEdicion")[0].value = 'Cancelar';
            $("#listaAsigPS").show();
            $("#valAsiPS").hide();
            $("#btn-acept").hide();
            editarps = true;
        } else {
            $("#Editar")[0].type = 'hidden';
            $("#ActEdicion")[0].value = 'Editar';
            editarps = false;
            $("#listaAsigPS").hide();
            $("#valAsiPS").show();
            $("#btn-acept").hide();
        }
    }


    function asignacionPs() {
        var idSolicitud = '<?=$idSolicitud?>';
        $.ajax({
            type: 'POST',
            url: 'asignacion_sol_adop.php',
            data: {
                tipo: 3,
                idSolicitud: idSolicitud,
                asignado: $('#ddlAsigPs').val()
            },
            datatype: 'json',
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success == '1') {
                    $('#ActEdicion')[0].type = 'button'
                    $('#txtAsigPs')[0].value = $('#ddlAsigPs option:selected').text();
                    editar = true;
                    edicion();
                } else {
                    alert('Ha ocurrido un error');
                }
            }
        });
    }

    var editarts = false

    function edicionS() {
        if (!editarts) {
            $("#EditarS")[0].type = 'button';
            $("#ActEdicionS")[0].value = 'Cancelar';
            $("#listaAsigTS").show();
            $("#valAsiTS").hide();

            editarts = true;
        } else {
            $("#EditarS")[0].type = 'hidden';
            $("#ActEdicionS")[0].value = 'Editar';
            editarts = false;
            $("#listaAsigTS").hide();
            $("#valAsiTS").show();
        }
    }

    function asignacionTs() {
        var idSolicitud = '<?=$idSolicitud?>';
        $.ajax({
            type: 'POST',
            url: 'asignacion_sol_adop.php',
            data: {
                tipo: 2,
                idSolicitud: idSolicitud,
                asignado: $('#ddlAsigTs').val()
            },
            datatype: 'json',
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success == '1') {
                    $('#ActEdicionS')[0].type = 'button'
                    $('#txtAsigTs')[0].value = $('#ddlAsigTs option:selected').text();
                    editarts = true;
                    edicionS();
                } else {
                    alert('Ha ocurrido un error');
                }
            }
        });
    }

    $(document).ready(function() {
        <?php if (!empty($asig_ps)) {?>
        $("#listaAsigPS").hide();
        $("#valAsiPS").show();
        <?php } else {?>
        $("#listaAsigPS").show();
        $("#valAsiPS").hide();
        <?php }if (!empty($asig_ts)) {?>
        $("#listaAsigTS").hide();
        $("#valAsiTS").show();
        <?php } else {?>
        $("#listaAsigTS").show();
        $("#valAsiTS").hide();
        <?php }?>
    });
    </script>
</head>

<body>
    <div id="wrapper">
        <div id="main">
            <div class="inner">
                <div class="error" id="error">
                </div>
                <div class="box alt" align="center">
                    <div class="row 10% uniform">
                        <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
                        <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
                        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
                    </div>
                </div>

                <h2>Solicitud de adopción con folio: <?=$folio_solicitud?></h2>
                <div style="display: inline-block; width: 100%;">
                    <b>Estatus: </b>
                    <input type="text" id="status" name="status" style="display: inline-block; max-width: 300px;"
                        disabled value="<?=$estado?>">


                    <?php if ($estado == "EN ESPERA DE INTEGRAR EXPEDIENTE") {?>

                    <?php if (($idPersonal == 1 and $idDepartamento == 16) or ($idPersonal == 3 and $idDepartamento = 11) or $idDEPTO == $id_respo_reg) {?>
                    <input name="btnEvaluacion" style="float: right;" class="button" type="button"
                        value="Expediente integrado"
                        onclick="if (confirm('¿Desea cambiar el estado de la solicitud?')) location='cambiar_estatus_sol_adop.php?id=<?=$idSolicitud?>&estado=4'">
                    <?php } else {?>

                    <input name="btnCerrar" style="float: right;" class="button" type="button" value="Cerrar solicitud"
                        onclick="location='cambiar_estatus_sol_adop.php?id=<?=$idSolicitud?>&estado=3'">
                    <?php }
} elseif ($estado == "EN EVALUACIÓN" and $entregadoTs == 1 and $entregado == 1) {?>
                    <?php if (($idPersonal == 1 and $idDepartamento == 16) or ($idPersonal == 3 and $idDepartamento = 11) or $idDEPTO == $id_respo_reg) {?>

                    <input name="btnEvaluacion" style="float: right;" class="button special" type="button"
                        value="En lista de espera"
                        onclick="if (confirm('¿Desea cambiar el estado de la solicitud?')) location='cambiar_estatus_sol_adop.php?id=<?=$idSolicitud?>&estado=6'">

                    <input name="btnEvaluacion" style="float: right;" class="button" type="button" value="No idoneo"
                        onclick="if (confirm('¿Desea cambiar el estado de la solicitud?')) location='cambiar_estatus_sol_adop.php?id=<?=$idSolicitud?>&estado=5'">


                    <?php }?>
                    <?php } elseif ($estado == "EN LISTA DE ESPERA" and $entregadoTs == 1 and $entregado == 1) {?>

                    <?php if (($idPersonal == 1 and $idDepartamento == 16) or ($idPersonal == 3 and $idDepartamento = 11) or $idDEPTO == $id_respo_reg) {?>

                    <input name="btnEvaluacion" id="btn-concluida" style="float: right;" class="button special"
                        type="button" value="Solicitud concluida"
                        onclick="if (confirm('¿Desea cambiar el estado de la solicitud?')) location='cambiar_estatus_sol_adop.php?id=<?=$idSolicitud?>&estado=9'">



                    <?php }?>

                    <?php } else {?>

                    <?php }?>


                </div>
                <br>
                <div class="box" style="margin-top: 20px;">
                    <a href="javascript:mostrarSolicitud(VerSolititud);">
                        <h4>SOLICITUD</h4>
                    </a>
                    <div id="divSolicitud" style="display:none;">
                        <div class="row uniform">
                            <div class="5u">
                                Número de audiencía:<br>
                                <b><?=$numAudiencia?></b>
                                <hr>
                                Tramite en caso de:<br>
                                <b><?=$tipo_solicitante?></b>
                                <hr>

                            </div>
                            <div class="7u">

                                Responsable de registro:<br>
                                <b><?=$responsable?></b>
                                <hr>
                                Fecha de registro:<br>
                                <b><?=$fecha_reg?></b>
                                <hr>

                            </div>
                        </div>

                        <?php if ($estado == 'NO PROCEDIO') {?>
                        <div class="row uniform">
                            <div class="4u">
                                <label>Requisitos con los que no cumplio</label>
                                <textarea name="NoCumplidos" id="NoCumplidos" rows="6" cols="40" disabled>
											<?php if ($noConsentimiento == 1) {
    echo "El consentimiento de quienes refiere el numero 210 de este ordenamiento.\n";
}

    if ($noEdadMin == 1) {
        echo "Tener los adoptantes una edad mínima de veinticinco años y plena capacidad de goce y de ejercicio.\n";
    }

    if ($noMenor18 == 1) {
        echo "Tener por lo menos dieciocho años de edad mas que el que se pretende adoptar.\n";
    }

    if ($noMayor45 == 1) {
        echo "Que entre los presuntos adoptantes y aquél quien pretende adoptar, exista una diferencia de edades no mayor de cuarenta y cinco años.\n";
    }

    if ($noRecursos == 1) {
        echo "Tener recursos económicos suficientes para alimentar al menor o menores que se pretende adoptar.\n";
    }

    if ($noBenefica == 1) {
        echo "Ser benefica la adopción para el adoptado.\n";
    }

    if ($noBuenasCostumbres == 1) {
        echo "Que los adoptantes sean de buenas costumbres.\n";
    }

    if ($noSalud == 1) {
        echo "Tener buena salud.\n";
    }

    if ($noMatrimonio == 1 and $tipo_solicitante == "CASADOS") {
        echo "Acreditar cuando menos tres años de matrimonio.\n";
    }

    if ($noConcubinato == 1 and $tipo_solicitante == "UNION LIBRE") {
        echo "Acreditar el concubinato debidamente inscrito.\n";
    }

    if ($noInexistenciaMatrimonio == 1 and $tipo_solicitante == "SOLTERO") {
        echo "Constancia de inexistencia de matrimonio.\n";
    }
    ?>
										</textarea>
                            </div>
                            <div class="4u">
                                <label>Especificación</label>
                                <textarea name="Especificacion" id="Especificacion" rows="6" cols="40"
                                    disabled><?=$requisitoNo?></textarea>
                            </div>
                            <div class="4u">
                                <label>Motivo por el que no procedio la solicitud</label>
                                <textarea name="motivo" id="motivo" rows="6" cols="40"
                                    disabled><?=$motivoNo?></textarea>
                            </div>
                        </div>
                        <?php } else {?>
                        <div class="row uniform">
                            <div class="3u">
                                Número de solicitud:<br>
                                <b><?=$numSolicitud?></b>
                                <hr>
                                Fecha de entrega:<br>
                                <b><?=$fecha_sol?></b>

                            </div>
                            <div class="5u">
                                No. de oficios para solicitud de prueba de VIH <br>
                                <b name="motivo" id="motivo"><?=$numPruebaVIH?></b>
                            </div>
                            <div class="4u">
                                No. de oficios para examen antidrogas <br>
                                <b name="motivo" id="motivo"><?=$numAntidrogas?></b>
                            </div>
                        </div><br>
                        <?php }?>
                    </div>
                </div>
                <div class="box">
                    <a href="javascript:mostrarSol(SolictVi);">
                        <h4>SOLICITANTES</h4>
                    </a>
                    <div id="divSolicitantes" style="display:none;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Nombre</th>
                                    <th>Sexo</th>
                                    <th>Estado civil</th>
                                    <th>Fecha_nacimiento</th>
                                    <th>Edad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($rw = $qusuarios->fetch_assoc()) {?>
                                <tr>
                                    <td><?=$rw['id_usuario']?></td>
                                    <td><?=$rw['nombre'] . " " . $rw['apellido_p'] . " " . $rw['apellido_m']?></td>
                                    <td><?=$rw['sexo']?></td>
                                    <td><?=$rw['estado_civil']?></td>
                                    <td><?=$rw['fecha_nacimiento']?></td>
                                    <td><?=$rw['edad']?></td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <td colspan="2"><b>Procedencia</b></td>
                                    <td colspan="4"><?=$procedencia_solicitantes?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <?php if ($estado != 'EN ESPERA DE INTEGRAR EXPEDIENTE' and $estado != 'NO PROCEDIO') {?>
                <div class="box">
                    <a href="javascript:mostrarEvaluacion(verEvaluacion);">
                        <h4>EVALUACIÓN</h4>
                    </a>
                    <div id="divEvaluacion">
                        <div class="row uniform">
                            <div class="6u">
                                <div class="box" style="padding-left: 10px; padding-right: 10px;">
                                    <div class="div" style="text-align: left;">
                                        <b>Asignado de psicología.</b><br>
                                    </div>

                                    <div class="row uniform" style="margin-left: 0px; padding-top: 5px;">

                                        <div class="auto" style="padding-left: 0px;">
                                            <?php if (empty($asig_ps)) {?>
                                            <select id="ddlAsigPs" name="ddlAsigPs" style="height: 31.3px;" disabled>
                                                <option value="">Seleccione</option>
                                                <?php while ($row = $psicologos->fetch_assoc()) {?>
                                                <option value="<?php echo $row['id']; ?>"> <?=$row['responsable']?>
                                                </option>
                                                <?php }?>
                                            </select>
                                            <?php } else {?>
                                            <select id="ddlAsigPs" name="ddlAsigPs" style="height: 31.3px;" disabled>
                                                <option value="<?=$idPS?>"><?=$asig_ps?></option>
                                                <?php while ($row = $psicologos->fetch_assoc()) {?>
                                                <option value="<?php echo $row['id']; ?>"> <?=$row['responsable']?>
                                                </option>
                                                <?php }?>
                                            </select>
                                            <?php }?>
                                        </div>


                                        <div class="auto" style="padding-left: 5px;">
                                            <input id="btn-editar" type="button" value="Editar"
                                                class="button special small"
                                                <?php if (($idPersonal == 4 and $idDepartamento == 11) or $id == 234 or $id == 228) {?>
                                                <?php if ($entregado == 1) {?> disabled <?php }?> <?php } else {?>
                                                disabled <?php }?>>
                                        </div>
                                        <div class="auto" style="padding-left: 0px;">
                                            <input id="btn-acept" name="btn-acept" type="button" value="Aceptar"
                                                class="button special small" onclick="asignacionPs()">
                                        </div>

                                    </div>

                                    <?php if (($idDEPTO == $idPS and $estado == "EN EVALUACIÓN") or $id == 234 or $id == 228) {?>
                                    <div style="padding-top: 20px;">
                                        <b>Registrar entrevista</b>
                                    </div>
                                    <div class="div"
                                        style="text-align: left; font-size: 14px; padding-top: 5px; padding-bottom: 5px;">
                                        <b>Ingrese la fecha y hora.</b><br>
                                    </div>
                                    <div>
                                        <form id="regEntrevistaPsico" name="regEntrevistaPsico"
                                            action="<?php $_SERVER['PHP_SELF'];?>" method="POST">

                                            <div class="row uniform">
                                                <div class="6u">
                                                    <input type="date" name="fecEntrevistaPsico" id="fecEntrevistaPsico"
                                                        required min="<?=$fecha_entrega_solicitud?>"
                                                        style="height: 31.3px;" <?php if ($entregado == 1) {?> disabled
                                                        <?php }?>>
                                                </div>
                                                <div class="3u" style="padding-left: 0px; margin-left: -5px;">
                                                    <input type="time" name="horaEntrevistaPsico"
                                                        id="horaEntrevistaPsico" required style="height: 31.3px;"
                                                        <?php if ($entregado == 1) {?> disabled <?php }?>>
                                                </div>
                                                <div class="1u" style="padding-left: 5px; margin-left: -11px;">
                                                    <input type="button" name="regEntrePsico" id="regEntrePsico"
                                                        class="button special small"
                                                        onclick="reg_entre_psico(<?=$idPerfil?>)" value="Agendar"
                                                        <?php if ($entregado == 1) {?> disabled <?php }?>>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <?php } else {?>
                                    <br>

                                    <?php }?>
                                    <div class="">
                                        <?php //}?>
                                        <b>Entrevistas realizadas</b>

                                        <div class="box" style="padding-left: 10px; padding-right: 10px;">
                                            <form action="" method="post">

                                                <table id="entre_psico" class="alt">
                                                    <thead>
                                                        <tr>
                                                            <th>Responsable</th>
                                                            <th>Fecha de registro</th>
                                                            <th>Fecha agendada</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if ($qEntrev_psico->num_rows > 0) {?>
                                                        <?php $i = 1;
    while ($rwps = $qEntrev_psico->fetch_assoc()) {?>
                                                        <?php $id = $rwps['id'];?>
                                                        <tr>
                                                            <td><?=$rwps['responsable']?></td>
                                                            <td class="fecha_reg"><?=$rwps['fecha_reg']?></td>
                                                            <td class="fecha_entrevista"><?=$rwps['fecha_ent']?></td>

                                                            <td>
                                                                <div>

                                                                    <label class="switch"
                                                                        style="margin: 0px; margin-top: 5px;">
                                                                        <input class="cb" type="checkbox"
                                                                            onclick="comprobar(this)"
                                                                            id="<?php echo 'cb-' . $i++ ?>"
                                                                            <?php if ($rwps['band']) {?>checked="true"
                                                                            disabled
                                                                            <?php } else if ($idDEPTO != $idPS) {?>
                                                                            disabled <?php }?>>
                                                                        <span class="slider round"></span>
                                                                    </label>
                                                                    <?php if (!$rwps['band'] and $idDEPTO == $idPS) {?>
                                                                    <hr style="margin: 0px; margin-bottom: 7px;">
                                                                    <img style="width: 19px; height: 21px; cursor: pointer;"
                                                                        src="images/eliminar2.png"
                                                                        onclick="eliminar(<?=$id?>,'PS')">
                                                                    <?php }?>

                                                                </div>
                                                            </td>

                                                        </tr>
                                                        <?php }
}?>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                        <div style="display: block;">
                                            <div style="width: fit-content; display: block; margin: 0m 0 0 auto;">
                                                <div style="display: inline-block; ">

                                                    <?php while ($row = $relEntrevistaPsico->fetch_assoc()) {?>
                                                    <div style="display: inline-block; font-size: 13px;">
                                                        <b>Entrevista inicial:</b>
                                                    </div>
                                                    <div style="display: inline-block;">
                                                        <input type="text" id="fecha_ini"
                                                            value="<?=$row['entrevista_ini']?>"
                                                            style="width: 85px; height: 28px;  font-size: 11px;"
                                                            disabled />
                                                    </div>
                                                    <div style="display: inline-block; font-size: 13px;">
                                                        <b>Entrevista final:</b>

                                                    </div>
                                                    <div style="display: inline-block;">
                                                        <input type="text" value="<?=$row['entrevista_fin']?>"
                                                            style="width: 85px; height: 28px; font-size: 11px;"
                                                            disabled />
                                                    </div>
                                                    <?php $fecha_entrega = $row['fecha_entrega'];
}?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if ($fecha_entrega) {?>

                                        <div style="display: block; margin-top: -20px;">
                                            <div style="width: fit-content; display: block; margin: 2em 0 0 auto;">
                                                <div style="display: inline-block; ">

                                                    <div style="display: inline-block; font-size: 13px;">
                                                        <b>Fecha de entrega:</b>

                                                    </div>
                                                    <div style="display: inline-block;">
                                                        <input type="text" value="<?php echo $fecha_entrega; ?>"
                                                            style="width: 85px; height: 28px; font-size: 11px;"
                                                            disabled />
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <?php }?>
                                        <?php if (($idDEPTO == $idPS and $estado == "EN EVALUACIÓN") or $id == 234 or $id == 228) {?>
                                        <br>
                                        <form action="" method="post">
                                            <div style="padding-left: 5px; margin-left: -11px;" id="finalizarEva">
                                                <input type="button" name="registrar" id="finEvaluacion"
                                                    class="button special fit small" value="Finalizar evaluación"
                                                    <?php if ($entregado == 1) {?> disabled <?php }?>>
                                            </div>
                                        </form>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                            <div class="6u">
                                <div class="box" style="padding-left: 10px; padding-right: 10px;">
                                    <div class="div" style="text-align: left;">
                                        <b>Asignado de trabajo social</b><br>
                                    </div>

                                    <div class="row uniform" style="margin-left: 0px; padding-top: 5px;">
                                        <div class="auto" style="padding-left: 0px;">
                                            <?php if (empty($asig_ts)) {?>
                                            <select id="ddlAsigTs" name="ddlAsigTs" style="height: 31.3px;" disabled>
                                                <option value="">Seleccione</option>
                                                <?php while ($row = $trabajadoresSol->fetch_assoc()) {?>
                                                <option value="<?php echo $row['id']; ?>"> <?=$row['responsable']?>
                                                </option>
                                                <?php }?>
                                            </select>
                                            <?php } else {?>
                                            <select id="ddlAsigTs" name="ddlAsigTs" style="height: 31.3px;" disabled>
                                                <option value="<?=$idTS?>"><?=$asig_ts?></option>
                                                <?php while ($row = $trabajadoresSol->fetch_assoc()) {?>
                                                <option value="<?php echo $row['id']; ?>"> <?=$row['responsable']?>
                                                </option>
                                                <?php }?>
                                            </select>
                                            <?php }?>
                                        </div>

                                        <div class="auto" style="padding-left: 5px;">
                                            <input id="btn-editar2" type="button" value="Editar"
                                                class="button special small"
                                                <?php if (($idPersonal == 4 and $idDepartamento == 11) or $id == 234 or $id == 228) {?>
                                                <?php if ($entregadoTs == 1) {?> disabled <?php }?> <?php } else {?>
                                                disabled <?php }?>>
                                        </div>
                                        <div class="auto" style="padding-left: 0px;">
                                            <input id="btn-acept2" name="btn-acept2" type="button" value="Aceptar"
                                                class="button special small" onclick="asignacionTs()" />
                                        </div>



                                    </div>
                                    <?php if (($idDEPTO == $idTS and $estado == "EN EVALUACIÓN") or $id == 234 or $id == 228) {?>
                                    <div style="padding-top: 20px;">
                                        <b>Registrar intervención</b>
                                    </div>
                                    <div class="div"
                                        style="text-align: left; font-size: 14px; padding-top: 5px; padding-bottom: 5px;">
                                        <b>Ingrese la fecha.</b><br>
                                    </div>
                                    <div>

                                        <form id="regEntrevistaTS" name="regEntrevistaTS"
                                            action="<?php $_SERVER['PHP_SELF'];?>" method="POST">

                                            <div class="row uniform">
                                                <div class="6u">
                                                    <input type="date" name="fecEntrevistaTS" id="fecEntrevistaTS"
                                                        required min="<?=$fecha_entrega_solicitud?>"
                                                        style="height: 31.3px;" <?php if ($entregadoTs == 1) {?>
                                                        disabled <?php }?>>
                                                </div>
                                                <div class="3u" style="padding-left: 0px; margin-left: -5px;">
                                                    <input type="time" name="horaEntrevistaTs" id="horaEntrevistaTs"
                                                        required style="height: 31.3px;"
                                                        <?php if ($entregadoTs == 1) {?> disabled <?php }?>>
                                                </div>
                                                <div class="1u" style="padding-left: 5px;">
                                                    <input type="button" name="regEntreTS" id="regEntreTS"
                                                        class="button special small"
                                                        onclick="reg_inter_ts(<?=$idPerfil?>)" value="Agendar"
                                                        <?php if ($entregadoTs == 1) {?> disabled <?php }?>>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                    <?php } else {?>
                                    <br>

                                    <?php }?>
                                    <div class="">
                                        <?php //}?>
                                        <b>Entrevistas realizadas</b>

                                        <div class="box" style="padding-left: 10px; padding-right: 10px;">
                                            <table id="entre_TS" class="alt">
                                                <thead>
                                                    <tr>
                                                        <th>Responsable</th>
                                                        <th>Fecha de registro</th>
                                                        <th>Fecha agendada</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if ($qEntrev_ts->num_rows > 0) {?>
                                                    <?php $i = 1;?>
                                                    <?php while ($rwts = $qEntrev_ts->fetch_assoc()) {?>
                                                    <?php $id = $rwts['id'];?>
                                                    <tr>
                                                        <td><?=$rwts['responsable']?></td>
                                                        <td class="fecha_reg_ts"><?=$rwts['fecha_reg']?></td>
                                                        <td class="fecha_ent_ts"><?=$rwts['fecha_ent']?></td>
                                                        <td>

                                                            <label class="switch" style="margin: 0px; margin-top: 5px;">
                                                                <input id="<?php echo 'cb2-' . $i++ ?>" class="cb2"
                                                                    type="checkbox" id="cb2" onclick="comprobar(this)"
                                                                    <?php if ($rwts['band']) {?>checked="true" disabled
                                                                    <?php } else if ($idDEPTO != $idTS) {?> disabled
                                                                    <?php }?>>
                                                                <span class="slider round"></span>
                                                            </label>
                                                            <?php if (!$rwts['band'] and $idDEPTO == $idTS) {?>
                                                            <hr style="margin: 0px; margin-bottom: 7px;">
                                                            <img style="width: 19px; height: 21px; cursor: pointer;"
                                                                src="images/eliminar2.png"
                                                                onclick="eliminar(<?=$id?>,'TS')">
                                                            <?php }?>
                                                        </td>
                                                    </tr>
                                                    <?php }
}?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div style="display: block;">
                                            <div style="width: fit-content; display: block; margin: 0m 0 0 auto;">
                                                <div style="display: inline-block; ">

                                                    <?php while ($row = $relEntrevistaTs->fetch_assoc()) {?>
                                                    <div style="display: inline-block; font-size: 13px;">
                                                        <b>Entrevista inicial:</b>
                                                    </div>
                                                    <div style="display: inline-block;">
                                                        <input type="text" value="<?=$row['entrevista_ini']?>"
                                                            style="width: 85px; height: 28px;  font-size: 11px;"
                                                            disabled />
                                                    </div>
                                                    <div style="display: inline-block; font-size: 13px;">
                                                        <b>Entrevista final:</b>

                                                    </div>
                                                    <div style="display: inline-block;">
                                                        <input type="text" value="<?=$row['entrevista_fin']?>"
                                                            style="width: 85px; height: 28px; font-size: 11px;"
                                                            disabled />
                                                    </div>
                                                    <?php $fecha_entregaTs = $row['fecha_entrega'];
}?>
                                                </div>
                                            </div>
                                        </div>

                                        <?php if ($fecha_entregaTs) {?>

                                        <div style="display: block; margin-top: -20px;">
                                            <div style="width: fit-content; display: block; margin: 2em 0 0 auto;">
                                                <div style="display: inline-block; ">

                                                    <div style="display: inline-block; font-size: 13px;">
                                                        <b>Fecha de entrega:</b>

                                                    </div>
                                                    <div style="display: inline-block;">
                                                        <input type="text" value="<?php echo $fecha_entregaTs; ?>"
                                                            style="width: 85px; height: 28px; font-size: 11px;"
                                                            disabled />
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <?php }?>
                                        <?php if (($idDEPTO == $idTS and $estado == "EN EVALUACIÓN") or $id == 234 or $id == 228) {?>
                                        <br>
                                        <form action="" method="post">
                                            <div style="padding-left: 5px; margin-left: -11px;" id="finalizarEva">
                                                <input type="button" name="registrarTs" id="finEvaluacionTs"
                                                    class="button special fit small" value="Finalizar evaluación"
                                                    <?php if ($entregadoTs == 1) {?> disabled <?php }?>>
                                            </div>
                                        </form>
                                        <?php }?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php }?>


                <?php if ($estado == "EN LISTA DE ESPERA" || $estado == "CONCLUIDA") {?>
                <div class="box">
                    <a href="javascript:mostrarSeguimiento(verSeguimiento);">
                        <h4>SEGUIMIENTO POST-ADOPCIÓN</h4>
                    </a>
                    <br>
                    <div id="divSeguimiento" style="display: none;">


                        <form id="seguimiento-form" method="post">
                            <div class="row uniform">
                                <div class="3u">
                                    <b>No. de seguimiento</b>
                                    <input name="txtNoActa" id="txtNoASeg" type="text" maxlength="50"
                                        style="text-transform:uppercase; margin-top: 10px;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="3u">
                                    <b>Fecha</b>
                                    <input type="date" id="txtFechaReg" name="txtFechaReg"
                                        value="<?php echo date('Y-m-d'); ?>" style="margin-top: 10px;">
                                </div>
                                <div class="6u">
                                    <b>Responsable</b>
                                    <input id="txtResponsable" name="txtResponsable" type="text" maxlength="50"
                                        value="<?=$asignado;?>" style="text-transform:uppercase; margin-top: 10px;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="row uniform">
                                <div style="width: 75%;">
                                    <b>Observaciones</b>
                                    <textarea id="txtObservaciones" rows="4" cols="40"
                                        style="text-transform:uppercase; margin-top: 10px;"
                                        onkeyup="this.value=this.value.toUpperCase();"></textarea>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <div class="row uniform">
                                <div style="width: 100%;">
                                    <input type="submit" class="button special fit" value="Guardar">
                                </div>
                            </div>

                        </form>


                        <div class="row uniform" id="list">
                            <br>
                            <table id="list_seguimiento" class="alt">
                                <thead>
                                    <tr>
                                        <th>No. de Seguimiento</th>
                                        <th>Fecha</th>
                                        <th>Asignado</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody id="seguimientos"></tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <?php }?>
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
        <!--cierre de menu-->
    </div>
    <!--cierre de wrapper-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/app.js"></script>

    <script>
    var verEvaluacion = true;

    function mostrarEvaluacion(bandera) {
        if (verEvaluacion == true) {
            div = document.getElementById('divEvaluacion');
            div.style.display = '';
            verEvaluacion = false
            sessionStorage.setItem('band', 'true')
            band = sessionStorage.getItem('band');
            console.log(band);
        } else {
            div = document.getElementById('divEvaluacion');
            div.style.display = 'none';
            verEvaluacion = true
            sessionStorage.setItem('band', 'false')
            band = sessionStorage.getItem('band');
            console.log(band);
        }
    }

    $(document).ready(function() {
        div = document.getElementById('divEvaluacion');
        band = sessionStorage.getItem('band');

        if (band == 'true') {
            div.style.display = '';
        } else {
            div.style.display = 'none';
        }
        console.log(band);

    })

    var checkID = "";
    var fecha_reg = "";
    var fecha_entrevista = "";
    var fecha_reg_ts = "";
    var fecha_ent_ts = "";
    if ($('#ddlAsigPs').value != '') {
        $('#btn-acept').hide();
    }
    if ($('#ddlAsigTs').value != '') {
        $('#btn-acept2').hide();
    }
    $(function() {

        $('#btn-editar').click(function() {
            $('#btn-editar').hide();
            $('#btn-acept').show();
            $("#ddlAsigPs").prop('disabled', false);
        });
        $('#btn-acept').click(function() {
            $('#btn-acept').hide();
            $('#btn-editar').show();
            $("#ddlAsigPs").prop('disabled', true);
        });
    })
    $(function() {
        $('#btn-editar2').click(function() {
            $('#btn-editar2').hide();
            $('#btn-acept2').show();
            $("#ddlAsigTs").prop('disabled', false);
        });
        $('#btn-acept2').click(function() {
            $('#btn-acept2').hide();
            $('#btn-editar2').show();
            $("#ddlAsigTs").prop('disabled', true);
        });


    })

    $('#finEvaluacionTs').click(function() {

        var idSolicitud = '<?=$idSolicitud?>';
        var mensaje = confirm("¿Estas seguro de que deseas finalizar la evaluación?");
        //Detectamos si el usuario acepto el mensaje
        if (mensaje) {

            $.ajax({
                type: 'POST',
                url: 'finalizarEvaluacionTs.php',
                data: {
                    idSolicitud: idSolicitud,
                },
                datatype: 'json',
                success: function(response) {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success == 1) {
                        alert("" + jsonData.mensaje);
                        location.reload();
                    } else {
                        alert("" + jsonData.mensaje);
                    }
                }
            })
        } else {

        }
    })

    $('#finEvaluacion').click(function() {

        var idSolicitud = '<?=$idSolicitud?>';
        var mensaje = confirm("¿Estas seguro de que deseas finalizar la evaluación?");
        //Detectamos si el usuario acepto el mensaje
        if (mensaje) {

            $.ajax({
                type: 'POST',
                url: 'finalizarEvaluacion.php',
                data: {
                    idSolicitud: idSolicitud,
                },
                datatype: 'json',
                success: function(response) {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success == 1) {
                        alert("" + jsonData.mensaje);
                        location.reload();
                    } else {
                        alert("" + jsonData.mensaje);
                    }
                }
            })
        } else {

        }
    })

    function comprobar(checkbox) {
        check = document.getElementById(checkbox.id);
        checkID = check.id;
    }

    function eliminar(id, area) {
        $.ajax({
            type: "POST",
            url: "eliminar_fecha.php",
            data: {
                id: id,
                area: area
            },
            datatype: 'json',
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success == 1) {
                    location.reload();
                } else {
                    alert("Ha ocurrido un error.");
                }
            }
        })
    }
    $(".cb").click(function() {
        var valores = "";
        var valores2 = "";
        var mensaje = confirm(
            "¿Deseas terminar la entrevista? \n Si realizas esta acción ya no podrás realizar cambios."
        );

        if (mensaje) {
            $(this).parents("tr").find(".fecha_reg").each(function() {
                valores += $(this).html() + "\n";
            });
            $(this).parents("tr").find(".fecha_entrevista").each(function() {
                valores2 += $(this).html() + "\n";
            });
            fecha_reg = valores;
            fecha_entrevista = valores2;

            var idSolicitud = '<?=$idSolicitud?>';
            $.ajax({
                type: "POST",
                url: "agendar_entrevista_psico.php",
                data: {
                    idSolicitud: idSolicitud,
                    fechaReg: fecha_reg,
                    fechaEntrevista: fecha_entrevista
                },
                datatype: 'json',
                success: function(response) {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success == 1) {
                        location.reload();
                    } else {
                        alert("Ha ocurrido un error.");
                    }
                }
            })
        } else {
            $("#" + checkID).prop('checked', false);
        }
    });



    function reg_entre_psico(idPerfil) {
        var idSolicitud = '<?=$idSolicitud?>';
        var fecha = $('#fecEntrevistaPsico').val() + " " + $('#horaEntrevistaPsico').val() + ":" + "00";
        console.log(idPerfil);
        $.ajax({
            type: 'POST',
            url: 'reg_entrevista_pscio_adop.php',
            data: {
                idSolicitud: idSolicitud,
                fecha: fecha,
                id_perfil: idPerfil
            },
            datatype: 'json',
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success == '1') {
                    alert('La entrevista se agendó correctamente.');
                    location.reload();
                } else {
                    alert('Ha ocurrido un error');
                }
            }


        })

    }

    $(".cb2").click(function() {

        var valores = "";
        var valores2 = "";
        var mensaje = confirm(
            "¿Deseas terminar la entrevista? \n Si realizas esta acción ya no podrás realizar cambios."
        );

        if (mensaje) {

            $(this).parents("tr").find(".fecha_reg_ts").each(function() {
                valores += $(this).html() + "\n";
            });
            $(this).parents("tr").find(".fecha_ent_ts").each(function() {
                valores2 += $(this).html() + "\n";
            });
            fecha_reg_ts = valores;
            fecha_ent_ts = valores2;

            console.log(fecha_reg_ts);
            console.log(fecha_ent_ts);
            var idSolicitud = '<?=$idSolicitud?>';
            console.log(idSolicitud);
            $.ajax({
                type: "POST",
                url: "agendar_entrevista_ts.php",
                data: {
                    idSolicitud: idSolicitud,
                    fechaReg: fecha_reg_ts,
                    fechaEntrevista: fecha_ent_ts
                },
                datatype: 'json',
                success: function(response) {
                    var jsonData = JSON.parse(response);
                    if (jsonData.success == 1) {
                        location.reload();
                    } else {
                        alert('Ha ocurrido un error');
                    }
                }
            })
        } else {
            $("#" + checkID).prop('checked', false);
        }
    });

    function reg_inter_ts(idPerfil) {
        var idSolicitud = '<?=$idSolicitud?>';
        var fecha = $('#fecEntrevistaTS').val() + " " + $('#horaEntrevistaTs').val() + ":" + "00";
        $.ajax({
            type: 'POST',
            url: 'reg_inter_ts.php',
            data: {
                idSolicitud: idSolicitud,
                fecha: fecha,
                id_perfil: idPerfil
            },
            datatype: 'json',
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success == '1') {
                    alert('La entrevista se agendó correctamente.');
                    location.reload();
                } else {
                    alert('Ha ocurrido un error');
                }
            }
        })
    }
    </script>
</body>

</html>