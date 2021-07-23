<?php
session_start();
require 'conexion.php';
require 'validar_fecha.php';
date_default_timezone_set('America/Mexico_City');
/*if(!isset($_SESSION["id"])){
header("Location: index.php");
}*/
$idDEPTO = $_SESSION['id'];
$pv = "SELECT id_depto, id_personal, responsable from departamentos where id='$idDEPTO'";
$epv = $mysqli->query($pv);
while ($row = $epv->fetch_assoc()) {
    $idDepartamento = $row['id_depto'];
    $idPersonal = $row['id_personal'];
    $responsable = $row['responsable'];
}

$atencion = $_SESSION['atenciones'];
$asunto = $_SESSION['asunto'];
$respuesta = $_SESSION['respuesta'];
$telefonica = $_SESSION['telefonica'];
$hora_salida = $_SESSION['hora'];
$error = '';
//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
$reportesvd = "SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo = $mysqli->query($reportesvd);
$wow = $erepo->num_rows;

$idVisita = $_GET['id'];
$qEdCivil = "SELECT * from cat_estado_civil";
$rEdoCivil = $mysqli->query($qEdCivil);

$qdatosUsuario = "SELECT id_usuario, nombre, apellido_p, apellido_m, s.sexo, ec.estado_civil, fecha_nacimiento, + year(curdate())-year(fecha_nacimiento) + if(date_format(curdate(),'%m-%d') > date_format(fecha_nacimiento,'%m-%d'), 0 , -1) as edad, date_format(fecha_ingreso, '%d/%m/%Y') as fecha_entrada, date_format(fecha_ingreso, '%H:%i:%s') as hora_entrada, d.responsable, u.fecha_registro, u.id_estado_civil
 		FROM historial inner join usuarios u on historial.id_usuario=u.id
 		inner join sexo s on s.id=u.id_sexo
 		left join cat_estado_civil ec on u.id_estado_civil=ec.id
 		inner join departamentos d on historial.responsable=d.id
 		where historial.id=$idVisita";
$datosUsuario = $mysqli->query($qdatosUsuario);
while ($row = $datosUsuario->fetch_assoc()) {
    $id_usuario1 = $row['id_usuario'];
    $nombre1 = $row['nombre'] . " " . $row['apellido_p'] . " " . $row['apellido_m'];
    $sexo1 = $row['sexo'];
    $estado_civil1 = $row['estado_civil'];
    $fecha_nac1 = $row['fecha_nacimiento'];
    $edad1 = $row['edad'];
    $fecha_entrada = $row['fecha_entrada'];
    $hora_entrada = $row['hora_entrada'];
    $id_est_civil = $row['id_estado_civil'];
    $Personal = $row['responsable'];
    $fech_reg_us = $row['fecha_registro'];
}
$catEstadoCivil = "SELECT * FROM procu.cat_estado_civil where id!=6";
if (!empty($estado_civil1)) {
    $catEstadoCivil .= " and id!=$id_est_civil";
}

$catEstadoCivil = $mysqli->query($catEstadoCivil);
if (!empty($_POST['btnRegistrar'])) {
    /*$atencion;
    $asunto;
    $respuesta;
    $telefonica;
    $hora_salida; */
    $idUsuario2 = 0;
    $numSolicitud = '0';
    $fecha_entrega = '1900-01-01';
    $soliVIH = '0';
    $exAntDrogas = '0';
    $noConsentimiento = 0;
    $noEdad = 0;
    $noMenor = 0;
    $noMayor = 0;
    $noRecursos = 0;
    $noBenefica = 0;
    $noBuena = 0;
    $noSalud = 0;
    $noConcubi = 0;
    $noMat = 0;
    $noInexMat = 0;
    $requisito = '';
    $motivo = '';
    $numAud = mysqli_real_escape_string($mysqli, $_POST['numAud']);
    $procedencia = mysqli_real_escape_string($mysqli, $_POST['txtProce']);

    if ($estado_civil1 == "Casado(a)") {
        $tipoSoli = 2;
        $idUsuario2 = $_POST['ddlusuario2'];
    } elseif ($estado_civil1 == "Unión libre") {
        $tipoSoli = 3;
        $idUsuario2 = $_POST['ddlusuario2'];
    } else {
        $tipoSoli = 1;
    }
    $estado = $_POST['requisitosCumplidos'];
    if ($estado == 1) { // si no cumplio con los requisitos
        if (isset($_POST['requ1'])) //se recogen datos
        {
            $noConsentimiento = 1;
        }

        if (isset($_POST['requ2'])) {
            $noEdad = 1;
        }

        if (isset($_POST['requ3'])) {
            $noMenor = 1;
        }

        if (isset($_POST['requ4'])) {
            $noMayor = 1;
        }

        if (isset($_POST['requ6'])) {
            $noRecursos = 1;
        }

        if (isset($_POST['requ7'])) {
            $noBenefica = 1;
        }

        if (isset($_POST['requ8'])) {
            $noBuena = 1;
        }

        if (isset($_POST['requ10'])) {
            $noSalud = 1;
        }

        if ($tipoSoli == 2) {
            if (isset($_POST['requ5'])) {
                $noMat = 1;
            }

        } elseif ($tipoSoli == 3) { //si es concubinato
            if (isset($_POST['requ11'])) {
                $noConcubi = 1;
            }

        } else {
            if (isset($_POST['requ9'])) {
                $noInexMat = 1;
            }

        }
        $requisito = $_POST['CumReq'];
        $motivo = $_POST['motivo'];
    } else {
        $numSolicitud = mysqli_real_escape_string($mysqli, $_POST['txtNumSolicitud']);
        $fecha_entrega = mysqli_real_escape_string($mysqli, $_POST['txtfechaEntSol']);
        $soliVIH = mysqli_real_escape_string($mysqli, $_POST['NumOfiVih']);
        $exAntDrogas = mysqli_real_escape_string($mysqli, $_POST['NumOfiAntDro']);
    }

    if ($fecha_entrega != '1900-01-01' and (substr($fecha_entrega, 4, 1) != '-' or substr($fecha_entrega, 7, 1) != '-')) {
        $fecha_entrega = validar_fecha($fecha_entrega);
    }
    if ($fecha_entrega == 0) {
        $error = "<br>Ingrese una fecha de entrega valida";
    }
    if ($estado == 1 and $noConsentimiento == 0 and $noEdad == 0 and $noMenor == 0 and $noMayor == 0 and $noRecursos == 0 and $noBenefica == 0 and $noBuena == 0 and $noSalud == 0 and $noMat == 0 and $noConcubi == 0 and $noInexMat == 0 and $requisito == 0 and $motivo == 0) {
        $error = "<br>Seleccione al menos un motivo por el que no procede la solicitud";
    }

    if ($error == '') {
        $registro = "CALL registrarSolicitudAdop('$hora_salida', '$idDEPTO', '$tipoSoli', '$numAud', '$procedencia', '$estado', '$numSolicitud', '$soliVIH', '$exAntDrogas', '$noEdad', '$noConsentimiento', '$noMenor', '$noMayor', '$noRecursos', '$noBenefica', '$noBuena', '$noSalud', '$noMat', '$noConcubi', '$noInexMat', '$requisito', '$motivo', '$id_usuario1', '$idUsuario2', '$atencion', '$asunto', '$respuesta', '$telefonica', '$hora_salida', '$idVisita', '$fecha_entrega')";
        $qregistro = $mysqli->query($registro);
        if ($qregistro) {
            echo "<script type='text/javascript'>
	               	alert('¡Solicitud de adopcion registrada con exito!');
	             	 	location.href = 'welcome.php';
	              	  	</script>";
        } else {
            $error = "Error al realizar el registro: $registro" . $registro;
        }
//echo $registro;
    }

}

?>
<!DOCTYPE HTML>
<html>

<head lang="es-MX">
    <title>Visita inicial</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="shortcut icon" href="images/favicon.png" type="image/png" />
    <script type="text/javascript" src="jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

</head>

<body>
    <div id="wrapper">
        <div id="main">
            <div class="inner">
                <div class="box alt" align="center">
                    <div class="row 10% uniform">
                        <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
                        <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
                        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
                    </div>
                </div>
                <!--cod -->
                <h1>Entrevista filtro</h1>
                <div style="font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : ''; ?>
                </div>
                <p><b>Por favor, complete el registro para terminar la visita</b></p>
                <form id="registro" name="registro" action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
                    <div class="row uniform">
                        <div class="4u">
                            <label>No. de audiencia</label>
                            <input id="numAud" name="numAud" type="text" maxlength="50"
                                style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                        </div>
                        <div class="4u">
                            <label>Fecha de recibido</label>
                            <input id="fecha_recibido" name="fecha_recibido" type="text" maxlength="40"
                                style="text-transform:uppercase;" value="<?=$fecha_entrada?>" disabled>
                        </div>
                        <div class="4u">
                            <label>Hora de registro</label>
                            <input id="hora_reg" name="hora_reg" type="text" style="text-transform:uppercase;"
                                value="<?=$hora_entrada?>" disabled>
                        </div>
                    </div>
                    <div class="row uniform">
                        <div class="2u">
                            <label>Hora de atención</label>
                            <input id="horaAten" name="horaAten" type="text" style="text-transform:uppercase;"
                                onkeyup="this.value=this.value.toUpperCase();" required="required"
                                value="<?php echo date('d/m/Y H:i:s', strtotime($hora_salida)); ?>" disabled>
                        </div>
                        <div class="5u">
                            <label>Atendido por:</label>
                            <input id="txtResponsable" name="txtResponsable" type="text" maxlength="40"
                                style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                required="required" value="<?=$responsable?>" disabled>
                        </div>
                        <div class="5u">
                            <label>Procedencia</label>
                            <input id="txtProce" name="txtProce" type="text" maxlength="50"
                                style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                        </div>

                        <!-- <div class="1u"><br><a href="lista_documentos.php">Editar</a>
							</div> -->

                    </div>
                    <div class="row uniform">
                        <div class="5u">
                            <label>Estado civil: </label>
                            <div id="EstadosCiviles">
                                <?php if (empty($id_est_civil) or $id_est_civil == 6) {?>
                                <select id="ddlEstadoCivil" name="ddlEstadoCivil" style="text-transform:uppercase;"
                                    required onkeyup="this.value=this.value.toUpperCase();">
                                    <option value="">Seleccione</option>
                                    <?php while ($row = $catEstadoCivil->fetch_assoc()) {?>
                                    <option value="<?php echo $row['id']; ?>"> <?=$row['estado_civil']?></option>
                                    <?php }?>
                                </select>

                                <?php } else {?>

                                <select id="ddlEstadoCivil" name="ddlEstadoCivil" style="text-transform:uppercase;"
                                    onkeyup="this.value=this.value.toUpperCase();">
                                    <option value="<?=$id_est_civil?>"><?=$estado_civil1?></option>
                                    <?php while ($row = $catEstadoCivil->fetch_assoc()) {?>
                                    <option value="<?php echo $row['id']; ?>"> <?=$row['estado_civil']?></option>
                                    <?php }?>
                                </select>



                                <?php }?>
                            </div>
                            <div id="valEstCivil">
                                <input id="edo_civil" name="edo_civil" type="text" maxlength="40"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                    required="required" value="<?=$estado_civil1?>" disabled>
                            </div>
                            <?php if (!empty($id_est_civil) and $id_est_civil != 6) {?>
                            <input type="button" class="button small" id="ActEdicion" name="ActEdicion" value="Editar"
                                onclick="edicion()" />
                            <input type="hidden" class="button special small" id="Editar" name="Editar" value="Aceptar"
                                onclick="ActualizarEstCivil()" />
                            <?php } else {?>
                            <input type="hidden" class="button small" id="ActEdicion" name="ActEdicion" value="Editar"
                                onclick="edicion()" />
                            <input type="button" class="button special small" id="Editar" name="Editar" value="Aceptar"
                                onclick="ActualizarEstCivil()" />
                            <?php }?>
                        </div>
                        <div class="7u">
                            <label>Nombre de las personas solicitantes</label>
                            <input id="solicitante1" name="solicitante1" type="text" maxlength="100"
                                style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                required="required" value="<?=$nombre1?>" disabled>

                            <div id="solicitante2">
                                <?php if ($estado_civil1 == "Casado(a)" or $estado_civil1 == "Unión libre") {
    $qDatosUsuario2 = "SELECT id_usuario, nombre, apellido_p, apellido_m, s.sexo, ec.estado_civil, fecha_nacimiento, + year(curdate())-year(fecha_nacimiento) + if(date_format(curdate(),'%m-%d') > date_format(fecha_nacimiento,'%m-%d'), 0 , -1) as edad, date_format(fecha_ingreso, '%d/%m/%Y') as fecha_entrada, date_format(fecha_ingreso, '%H:%i:%s') as hora_entrada, d.responsable, u.fecha_registro
								 		FROM historial inner join usuarios u on historial.id_usuario=u.id
								 		inner join sexo s on s.id=u.id_sexo
								 		left join cat_estado_civil ec on u.id_estado_civil=ec.id
								 		inner join departamentos d on historial.responsable=d.id
								 		where fecha_salida is null and id_usuario!=$id_usuario1";
    $DatosUsuario2 = $mysqli->query($qDatosUsuario2);?>
                                <div class="select-wrapper">
                                    <select id="ddlusuario2" name="ddlusuario2" style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                        <option value="0">--Seleccione--</option>
                                        <?php while ($row = $DatosUsuario2->fetch_assoc()) {?>
                                        <option value="<?php echo $row['id_usuario']; ?>">
                                            <?php echo $row['nombre'] . ' ' . $row['apellido_p'] . ' ' . $row['apellido_m']; ?>
                                        </option>
                                        <?php }?>
                                    </select>
                                </div>

                                <?php }?>
                            </div>
                        </div>


                    </div>
                    <br>
                    <h5>¿Procede la solicitud</h5>
                    <input type="radio" id="rbtSi" class="inpCumplido" name="requisitosCumplidos" value="2"
                        required="true">
                    <label for="rbtSi" value="2">SI</label>
                    <input type="radio" id="rbtNo" class="inpCumplido" name="requisitosCumplidos" value="1">
                    <label for="rbtNo" value="1">NO</label>

                    <div class="box">
                        <div id="cumplido" style="display:none;">
                            <div class="row uniform">
                                <div class="3u">
                                    <label>No. de solicitud de adopcion entregada</label>
                                    <input type="text" name="txtNumSolicitud" id="txtNumSolicitud" maxlength="50"
                                        style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="3u">
                                    <label>Fecha de entrega de solicitud y hoja de requisitos</label>
                                    <input type="date" name="txtfechaEntSol" id="txtfechaEntSol">
                                </div>
                                <div class="3u">
                                    <label>No. de oficios para solicitud de prueba de VIH</label>
                                    <input type="text" name="NumOfiVih" id="NumOfiVih" maxlength="50"
                                        style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="3u">
                                    <label>No. de oficios para examen antidrogas</label>
                                    <input type="text" name="NumOfiAntDro" id="NumOfiAntDro" maxlength="50"
                                        style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>

                            </div>
                            <div class="row uniform">

                                <div class="5u">
                                    <label>Prueba de Elisa tercera generacion, Antigenos, Hepatitis B, Virus, Hepatitis
                                        c</label>
                                    <textarea id="pruElisa" name="pruElisa" maxlength="200" rows="4" cols="50"
                                        disabled="true">Costo aproximado $1,487.00 por persona</textarea>
                                </div>
                                <div class="3u">
                                    <label>Examen antidrogas</label>
                                    <textarea id="antDro" name="antDro" maxlength="200" rows="4" cols="50"
                                        disabled="true">Costo aproximado $965.00 por persona</textarea>
                                </div>
                                <div class="4u">
                                    <label>Carta de antecedentes no penales de los solicitantes, realizado po la
                                        Dirección General de Servicios Periciales. </label>
                                    <textarea id="antecedentes" name="antecedentes" maxlength="200" rows="4" cols="50"
                                        disabled="true">Costo aproximado $150.00 por persona</textarea>
                                </div>
                            </div>
                        </div>
                        <div id="noCumplido" style="display:none;">
                            <div class="row uniform">
                                <div class="12u 12u$(xsmall)" align="left">
                                    Nota: Los requisitos solicitados tienen fundamento en: la Ley para la familia en el
                                    Estado de Hidalgo Artículo 208.- Quienes oretenden adoptar, deberán satisfacer los
                                    requisitos según sea el caso.
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="12u 12u$(xsmall)" align="left">
                                    <b>Marcar el o los requisitos con los que <ins>NO</ins> cumplió</b>
                                </div>
                            </div>
                            <br>
                            <div class="row uniform">
                                <div class="12u 12u$(xsmall)" align="left">
                                    <input type="checkbox" id="requ1" name="requ1" value="1">
                                    <label for="requ1">El consentimiento de quienes refiere el numero 210 de este
                                        ordenamiento</label>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="12u 12u$(xsmall)" align="left">
                                    <input type="checkbox" id="requ2" name="requ2" value="1">
                                    <label for="requ2">Tener los adoptantes una edad mínima de veinticinco años y plena
                                        capacidad de goce y de ejercicio.</label>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="12u 12u$(xsmall)" align="left">
                                    <input type="checkbox" id="requ3" name="requ3" value="1">
                                    <label for="requ3">Tener por lo menos dieciocho años de edad mas que el que se
                                        pretende adoptar.</label>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="12u 12u$(xsmall)" align="left">
                                    <input type="checkbox" id="requ4" name="requ4" value="1">
                                    <label for="requ4">Que entre los presuntos adoptantes y aquél quien pretende
                                        adoptar, exista una diferencia de edades no mayor de cuarenta y cinco
                                        años.</label>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="12u 12u$(xsmall)" align="left">
                                    <input type="checkbox" id="requ6" name="requ6" value="1">
                                    <label for="requ6">Tener recursos económicos suficientes para alimentar al menor o
                                        menores que se pretende adoptar.</label>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="12u 12u$(xsmall)" align="left">
                                    <input type="checkbox" id="requ7" name="requ7" value="1">
                                    <label for="requ7">Ser benefica la adopción para el adoptado.</label>
                                    <br>
                                    <input type="checkbox" id="requ8" name="requ8" value="1">
                                    <label for="requ8">Que los adoptantes sean de buenas costumbres.</label>
                                    <br>
                                    <input type="checkbox" id="requ10" name="requ10" value="1">
                                    <label for="requ10">Tener buena salud.</label>
                                    <?php if ($estado_civil1 == 'Casado(a)') {?>
                                    <br>
                                    <input type="checkbox" id="requ5" name="requ5" value="1">
                                    <label for="requ5">Acreditar cuando menos tres años de matrimonio.</label>
                                    <?php } elseif ($estado_civil1 == 'Unión libre') {?>
                                    <br>
                                    <input type="checkbox" id="requ11" name="requ11" value="1">
                                    <label for="requ11">Acreditar el concubinato debidamente inscrito.</label>

                                    <?php } else {?>
                                    <br>
                                    <input type="checkbox" id="requ9" name="requ9" value="1">
                                    <label for="requ9">Constancia de inexistencia de matrimonio</label>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="row uniform">
                                <div class="6u">
                                    <label>Señalar si cumple con todos los requisitos del art. 208 de la ley para la
                                        familia, en caso de faltar alguno, especifique en el recuadro</label>
                                    <textarea name="CumReq" id="CumReq" maxlength="200"
                                        style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                        rows="4" cols="40"></textarea>
                                </div>
                                <div class="6u">
                                    <label>En caso de no proceder solicitud señalar el motivo</label>
                                    <textarea id="motivo" name="motivo" maxlength="200" rows="5" cols="40"
                                        style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row uniform">
                        <div class="6u 12u$(xsmall)">
                            <input name="btnRegistrar" class="button special fit small" type="submit" value="Aceptar">
                        </div>
                        <div class="6u 12u$(xsmall)">
                            <input name="btnCancelar" class="button fit small" type="button" value="Cancelar"
                                onclick="if (confirm('Si cancela ahora no se registrará el termino de la visita ¿Desea realmente cancerla?')) location='welcome.php'">
                        </div>
                    </div>
                </form>


                <script type="text/javascript">
                var editar = false
                var estCivil = '<?=$estado_civil1?>'
                $(document).ready(function() {
                    <?php if (!empty($id_est_civil) and $id_est_civil != 6) {?>
                    $("#EstadosCiviles").hide();
                    $("#valEstCivil").show();
                    <?php } else {?>
                    $("#EstadosCiviles").show();
                    $("#valEstCivil").hide();
                    <?php }?>
                    $(".inpCumplido").click(function(evento) {
                        var valor = $('input:radio[id=rbtSi]:checked').val();
                        if (valor == "2") {
                            $("#cumplido").css("display", "block");
                            $("#noCumplido").css("display", "none");
                        } else {
                            $("#cumplido").css("display", "none");
                            $("#noCumplido").css("display", "block");
                        }
                    });
                });

                function edicion() {
                    if (!editar) {
                        $("#Editar")[0].type = 'button';
                        $("#ActEdicion")[0].value = 'Cancelar';
                        $("#EstadosCiviles").show();
                        $("#valEstCivil").hide();
                        editar = true;
                    } else {
                        $("#Editar")[0].type = 'hidden';
                        $("#ActEdicion")[0].value = 'Editar';
                        editar = false;
                        $("#EstadosCiviles").hide();
                        $("#valEstCivil").show();
                    }
                }
                </script>

            </div>
        </div>



    </div>
    <!--cierre de wrapper-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script src="assets/js/main.js"></script>
    <script>
    $(document).ready(function() {
        var estado = $('#ddlEstadoCivil').val();
        if (estado == 1 || estado == 5) {
            $('#ddlusuario2').show();
        } else {
            $('#ddlusuario2').hide();
        }
        $('#ddlEstadoCivil').change(function() {
            estado = $('#ddlEstadoCivil').val();
            if (estado == 1 || estado == 5) {
                $('#ddlusuario2').show();
            } else {
                $('#ddlusuario2').hide();
            }

        })
    })

    function ActualizarEstCivil() {
        var idUsuario = '<?=$id_usuario1?>';
        $.ajax({
            type: 'POST',
            url: 'editar_edo_civil.php',
            data: {
                idUsuario: idUsuario,
                estado_civil: $('#ddlEstadoCivil').val()
            },
            datatype: 'json',
            success: function(response) {
                var jsonData = JSON.parse(response);
                if (jsonData.success == '1') {

                    $('#ActEdicion')[0].type = 'button'
                    $('#edo_civil')[0].value = $('#ddlEstadoCivil option:selected').text();
                    editar = true;
                    estCivil = $('#ddlEstadoCivil option:selected').text();
                    edicion();
                    $('#solicitante2').empty()
                    console.log(estCivil)
                    if (estCivil === " Casado(a)" || estCivil === " Unión libre") {
                        <?php
$qDatosUsuario2 = "SELECT id_usuario, nombre, apellido_p, apellido_m, s.sexo, ec.estado_civil, fecha_nacimiento, + year(curdate())-year(fecha_nacimiento) + if(date_format(curdate(),'%m-%d') > date_format(fecha_nacimiento,'%m-%d'), 0 , -1) as edad, date_format(fecha_ingreso, '%d/%m/%Y') as fecha_entrada, date_format(fecha_ingreso, '%H:%i:%s') as hora_entrada, d.responsable, u.fecha_registro
							 		FROM historial inner join usuarios u on historial.id_usuario=u.id
							 		inner join sexo s on s.id=u.id_sexo
							 		left join cat_estado_civil ec on u.id_estado_civil=ec.id
							 		inner join departamentos d on historial.responsable=d.id
							 		where historial.responsable=$idDEPTO and fecha_salida is null and id_usuario!=$id_usuario1";
$DatosUsuario2 = $mysqli->query($qDatosUsuario2);?>
                        $('#solicitante2').append(
                            '<div class="select-wrapper"><select id="ddlusuario2" name="ddlusuario2" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" ><option value="0">--Seleccione--</option>'
                            <?php while ($row = $DatosUsuario2->fetch_assoc()) {?> +
                            "<option value=" + "<?php echo $row['id_usuario']; ?>" +
                            "><?php echo $row['nombre'] . ' ' . $row['apellido_p'] . ' ' . $row['apellido_m']; ?></option>"
                            <?php }?> +
                            "</select></div>"
                        )
                    }
                } else {
                    alert('Ha ocurrido un error');
                }
            }
        });
    }
    </script>
</body>

</html>