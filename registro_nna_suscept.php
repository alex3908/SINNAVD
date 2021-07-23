<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idDEPTO = $_SESSION['id'];
$pv = "SELECT id,id_depto, id_personal, responsable from departamentos where id='$idDEPTO'";
$epv = $mysqli->query($pv);
while ($row = $epv->fetch_assoc()) {
    $idDepartamento = $row['id_depto'];
    $idPersonal = $row['id_personal'];
    $responsable = $row['responsable'];
    $idResponsable = $row['id'];
}
$existeCurp = null;
//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)

$persona1 = $_SESSION['persona'];
$curp = trim($persona1['curp']);
$nombre = trim($persona1['nombre']);
$ape1 = trim($persona1['ape1']);
$ape2 = trim($persona1['ape2']);
$sexo = $persona1['sexo'];
if ($sexo == 1) {
    $sex = "H";
} elseif ($sexo == 2) {
    $sex = "M";
}

$fecNac = $persona1['fecNac'];
$edoNac = $persona1['edoNac'];
$estatus = null;
$qEdCivil = "SELECT * from cat_estado_civil";
$rEdoCivil = $mysqli->query($qEdCivil);
$query = "SELECT * from estados where id!='0'";
$equery = $mysqli->query($query);
$countries = array();
while ($rowEstados = $equery->fetch_object()) {$countries[] = $rowEstados;}

if (!empty($curp)) { //hay una curp
    $cliente = new SoapClient('http://172.16.1.42/sistemas/wssieb/WebServiceBeneficiarios.asmx?wsdl'); //crea un nuevo cliente
    $parametros = array('CURP' => "$curp");
    $respuesta = $cliente->ConsultaPorCurp($parametros); //consulta por curp
    $respuesta = json_decode(json_encode($respuesta), true);
    $estatus = $respuesta['ConsultaPorCurpResult']['StatusOper'];
    if ($estatus == 'EXITOSO') //devuelve valores
    {
        $nombre = $respuesta['ConsultaPorCurpResult']['Nombres'];
        $ape1 = $respuesta['ConsultaPorCurpResult']['Apellido1'];
        $ape2 = $respuesta['ConsultaPorCurpResult']['Apellido2'];
        $sexo = $respuesta['ConsultaPorCurpResult']['Sexo'];
        if ($sexo == 'M') {
            $sexo = 'MUJER';
        } else if ($sexo == 'H') {
            $sexo = 'HOMBRE';
        }

        $fechaHorNac = $respuesta['ConsultaPorCurpResult']['FechNac'];
        $ArfechaHorNac = explode("T", $fechaHorNac);
        $fecNac = $ArfechaHorNac[0];
        $date = new DateTime($fechaHorNac);
        $fechaNac = date_format($date, 'd/m/Y');
        if ($respuesta['ConsultaPorCurpResult']['Nacionalidad'] == 'MEX') {
            $nacionalidad = 'MEXICANA';
        } else {
            $nacionalidad = 'EXTRANJERO';
        }

        $lugNac = $respuesta['ConsultaPorCurpResult']['EntidadFederativa'];
        $qEstadoNac = "SELECT estado as edoNac, id FROM estados where clave='$lugNac'";
        $rEstadoNac = $mysqli->query($qEstadoNac);
        while ($rlugNac = $rEstadoNac->fetch_assoc()) {
            $idEdoNac = $rlugNac['id'];
            $edoNac = $rlugNac['edoNac'];
        }
        $statusCurp = $respuesta['ConsultaPorCurpResult']['StatusCURP'];
        switch ($statusCurp) {
            case 'AN':
                $stCurp = "ACTIVA: ALTA NORMAL";
                break;
            case 'AH':
                $stCurp = "ACTIVA: ALTA CON HOMONIMIA";
                break;
            case 'CRA':
                $stCurp = "ACTIVA: CURP REACTIVADA";
                break;
            case 'RCN':
                $stCurp = "ACTIVA: REGISTRO DE CAMBIO NO AFECTANDO A CURP";
                break;
            case 'RCC':
                $stCurp = "ACTIVA: REGISTRO DE CAMBIO AFECTANDO A CURP";
                break;
            case 'BD':
                $stCurp = "BAJA POR DEFUNCION";
                break;
            case 'BDA':
                $stCurp = "BAJA POR DUPLICIDAD";
                break;
            case 'BCC':
                $stCurp = "BAJA POR CAMBIO EN CURP";
                break;
            case 'BCN':
                $stCurp = "BAJA NO AFECTANDO A CURP";
                break;
            default:
                $stCurp = "NINGUNO";
                break;
        }
        $DocProbatorio = $respuesta['ConsultaPorCurpResult']['DocProbatorio'];
        switch ($DocProbatorio) {
            case '1':
                $ADocProbatorio = "ACTA DE NACIMIENTO";
                $anioReg = $respuesta['ConsultaPorCurpResult']['AnioReg'];
                $numActa = $respuesta['ConsultaPorCurpResult']['NumActa'];
                $idEstadoReg = $respuesta['ConsultaPorCurpResult']['EntidadRegistro'];
                $idMunReg = $respuesta['ConsultaPorCurpResult']['MunicipioRegistro'];
                if ($idEstadoReg != '13') {
                    $qEstadoReg = "SELECT estadosMayus from estados where id='$idEstadoReg'";
                    $rEstadoReg = $mysqli->query($qEstadoReg);
                    if ($rEstadoReg->num_rows == 0) {
                        $lugarReg = "";
                    } else {
                        $arrEstadoReg = $rEstadoReg->fetch_assoc();
                        $lugarReg = implode($arrEstadoReg);}
                } else {
                    $qMunReg = "SELECT municipioMayus from municipios where id='$idMunReg'";
                    $rMunReg = $mysqli->query($qMunReg);
                    $arrMunReg = $rMunReg->fetch_assoc();
                    $MunReg = implode($arrMunReg);
                    $lugarReg = $MunReg . ", HIDALGO";
                }
                break;
            case '3':
                $ADocProbatorio = "DOCUMENTO MIGRATORIO";
                $numRegExtrajero = ['ConsultaPorCurpResult']['NumRegExtranjeros'];
                break;
            case '4':
                $ADocProbatorio = "CARTA DE NATURALIZACIÓN";
                $anioReg = $respuesta['ConsultaPorCurpResult']['AnioReg'];
                $folioCarta = $respuesta['ConsultaPorCurpResult']['FolioCarta'];
                break;
            case '7':
                $ADocProbatorio = "CERTIFICADO DE NACIONALIDAD MEXICANA";
                $anioReg = $respuesta['ConsultaPorCurpResult']['AnioReg'];
                $folioCarta = $respuesta['ConsultaPorCurpResult']['FolioCarta'];
                break;
            default:
                $ADocProbatorio = "TRAMITE ANTE SEGOB";
                $folio = $respuesta['ConsultaPorCurpResult']['CRIP'];
                break;
        }
        if ($DocProbatorio != 1) {
            $anioReg = null;
            $numActa = null;
            $lugarReg = null;
        }

    } else if (!empty($nombre) and !empty($ape1) and !empty($sexo) and !empty($fecNac) and !empty($edoNac)) { //no se pudo consultar x curp
        $persona = [
            "Nombres" => "$nombre",
            "Apellido1" => "$ape1",
            "Apellido2" => "$ape2",
            "FechNac" => "$fecNac",
            "Sexo" => "$sex",
            "EntidadRegistro" => "$edoNac",
        ];
        $cliente = new SoapClient('http://172.16.1.42/sistemas/wssieb/WebServiceBeneficiarios.asmx?wsdl'); //crea un nuevo cliente
        $parametros = new stdClass();
        $parametros->Persona = $persona;
        $respuesta = $cliente->ConsultaPorDatos($parametros);
        $respuesta = json_decode(json_encode($respuesta), true);
        $estatus = $respuesta['ConsultaPorDatosResult']['StatusOper'];
        if ($estatus == 'EXITOSO') //devuelve valores
        {
            $curp = $respuesta['ConsultaPorDatosResult']['CURP'];
            $nombre = $respuesta['ConsultaPorDatosResult']['Nombres'];
            $ape1 = $respuesta['ConsultaPorDatosResult']['Apellido1'];
            $ape2 = $respuesta['ConsultaPorDatosResult']['Apellido2'];
            $sexo = $respuesta['ConsultaPorDatosResult']['Sexo'];
            if ($sexo == 'M') {
                $sexo = 'MUJER';
            } else if ($sexo == 'H') {
                $sexo = 'HOMBRE';
            }

            $fechaHorNac = $respuesta['ConsultaPorDatosResult']['FechNac'];
            $ArfechaHorNac = explode("T", $fechaHorNac);
            $fecNac = $ArfechaHorNac[0];
            $date = new DateTime($fechaHorNac);
            $fechaNac = date_format($date, 'd/m/Y');
            if ($respuesta['ConsultaPorDatosResult']['Nacionalidad'] == 'MEX') {
                $nacionalidad = 'MEXICANA';
            } else {
                $nacionalidad = 'EXTRANJERO';
            }

            $lugNac = $respuesta['ConsultaPorDatosResult']['EntidadFederativa'];
            $qEstadoNac = "SELECT estado as edoNac, id FROM estados where clave='$lugNac'";
            $rEstadoNac = $mysqli->query($qEstadoNac);
            while ($rlugNac = $rEstadoNac->fetch_assoc()) {
                $idEdoNac = $rlugNac['id'];
                $edoNac = $rlugNac['edoNac'];
            }
            $statusCurp = $respuesta['ConsultaPorDatosResult']['StatusCURP'];
            switch ($statusCurp) {
                case 'AN':
                    $stCurp = "ACTIVA: ALTA NORMAL";
                    break;
                case 'AH':
                    $stCurp = "ACTIVA: ALTA CON HOMONIMIA";
                    break;
                case 'CRA':
                    $stCurp = "ACTIVA: CURP REACTIVADA";
                    break;
                case 'RCN':
                    $stCurp = "ACTIVA: REGISTRO DE CAMBIO NO AFECTANDO A CURP";
                    break;
                case 'RCC':
                    $stCurp = "ACTIVA: REGISTRO DE CAMBIO AFECTANDO A CURP";
                    break;
                case 'BD':
                    $stCurp = "BAJA POR DEFUNCION";
                    break;
                case 'BDA':
                    $stCurp = "BAJA POR DUPLICIDAD";
                    break;
                case 'BCC':
                    $stCurp = "BAJA POR CAMBIO EN CURP";
                    break;
                case 'BCN':
                    $stCurp = "BAJA NO AFECTANDO A CURP";
                    break;
                default:
                    $stCurp = "NINGUNO";
                    break;
            }
            $DocProbatorio = $respuesta['ConsultaPorDatosResult']['DocProbatorio'];
            switch ($DocProbatorio) {
                case '1':
                    $ADocProbatorio = "ACTA DE NACIMIENTO";
                    $anioReg = $respuesta['ConsultaPorDatosResult']['AnioReg'];
                    $numActa = $respuesta['ConsultaPorDatosResult']['NumActa'];
                    $idEstadoReg = $respuesta['ConsultaPorDatosResult']['EntidadRegistro'];
                    $idMunReg = $respuesta['ConsultaPorDatosResult']['MunicipioRegistro'];
                    if ($idEstadoReg != '13') {
                        $qEstadoReg = "SELECT estadosMayus from estados where id='$idEstadoReg'";
                        $rEstadoReg = $mysqli->query($qEstadoReg);
                        if ($rEstadoReg->num_rows == 0) {
                            $lugarReg = "";
                        } else {
                            $arrEstadoReg = $rEstadoReg->fetch_assoc();
                            $lugarReg = implode($arrEstadoReg);}
                    } else {
                        $qMunReg = "SELECT municipioMayus from municipios where id='$idMunReg'";
                        $rMunReg = $mysqli->query($qMunReg);
                        $arrMunReg = $rMunReg->fetch_assoc();
                        $MunReg = implode($arrMunReg);
                        $lugarReg = $MunReg . ", HIDALGO";
                    }
                    break;
                case '3':
                    $ADocProbatorio = "DOCUMENTO MIGRATORIO";
                    $numRegExtrajero = ['ConsultaPorDatosResult']['NumRegExtranjeros'];
                    break;
                case '4':
                    $ADocProbatorio = "CARTA DE NATURALIZACIÓN";
                    $anioReg = $respuesta['ConsultaPorDatosResult']['AnioReg'];
                    $folioCarta = $respuesta['ConsultaPorDatosResult']['FolioCarta'];
                    break;
                case '7':
                    $ADocProbatorio = "CERTIFICADO DE NACIONALIDAD MEXICANA";
                    $anioReg = $respuesta['ConsultaPorDatosResult']['AnioReg'];
                    $folioCarta = $respuesta['ConsultaPorDatosResult']['FolioCarta'];
                    break;
                default:
                    $ADocProbatorio = "TRAMITE ANTE SEGOB";
                    $folio = $respuesta['ConsultaPorDatosResult']['CRIP'];
                    break;
            }
            if ($DocProbatorio != 1) {
                $anioReg = null;
                $numActa = null;
                $lugarReg = null;
            }
        }
    }
} else if (!empty($nombre) and !empty($ape1) and !empty($sexo) and !empty($fecNac) and !empty($edoNac)) { // no hay curp checa que los demas datos esten completos
    $persona = [
        "Nombres" => "$nombre",
        "Apellido1" => "$ape1",
        "Apellido2" => "$ape2",
        "FechNac" => "$fecNac",
        "Sexo" => "$sex",
        "EntidadRegistro" => "$edoNac",
    ]; //si estan completos hace la consulta
    $cliente = new SoapClient('http://172.16.1.42/sistemas/wssieb/WebServiceBeneficiarios.asmx?wsdl'); //crea un nuevo cliente
    $parametros = new stdClass();
    $parametros->Persona = $persona;
    $respuesta = $cliente->ConsultaPorDatos($parametros);
    $respuesta = json_decode(json_encode($respuesta), true);
    $estatus = $respuesta['ConsultaPorDatosResult']['StatusOper'];
    if ($estatus == 'EXITOSO') //devuelve valores
    {
        $curp = $respuesta['ConsultaPorDatosResult']['CURP'];
        $nombre = $respuesta['ConsultaPorDatosResult']['Nombres'];
        $ape1 = $respuesta['ConsultaPorDatosResult']['Apellido1'];
        $ape2 = $respuesta['ConsultaPorDatosResult']['Apellido2'];
        $sexo = $respuesta['ConsultaPorDatosResult']['Sexo'];
        if ($sexo == 'M') {
            $sexo = 'MUJER';
        } else if ($sexo == 'H') {
            $sexo = 'HOMBRE';
        }

        $fechaHorNac = $respuesta['ConsultaPorDatosResult']['FechNac'];
        $ArfechaHorNac = explode("T", $fechaHorNac);
        $fecNac = $ArfechaHorNac[0];
        $date = new DateTime($fechaHorNac);
        $fechaNac = date_format($date, 'd/m/Y');
        if ($respuesta['ConsultaPorDatosResult']['Nacionalidad'] == 'MEX') {
            $nacionalidad = 'MEXICANA';
        } else {
            $nacionalidad = 'EXTRANJERO';
        }

        $lugNac = $respuesta['ConsultaPorDatosResult']['EntidadFederativa'];
        $qEstadoNac = "SELECT estado as edoNac, id FROM estados where clave='$lugNac'";
        $rEstadoNac = $mysqli->query($qEstadoNac);
        while ($rlugNac = $rEstadoNac->fetch_assoc()) {
            $idEdoNac = $rlugNac['id'];
            $edoNac = $rlugNac['edoNac'];
        }
        $statusCurp = $respuesta['ConsultaPorDatosResult']['StatusCURP'];
        switch ($statusCurp) {
            case 'AN':
                $stCurp = "ACTIVA: ALTA NORMAL";
                break;
            case 'AH':
                $stCurp = "ACTIVA: ALTA CON HOMONIMIA";
                break;
            case 'CRA':
                $stCurp = "ACTIVA: CURP REACTIVADA";
                break;
            case 'RCN':
                $stCurp = "ACTIVA: REGISTRO DE CAMBIO NO AFECTANDO A CURP";
                break;
            case 'RCC':
                $stCurp = "ACTIVA: REGISTRO DE CAMBIO AFECTANDO A CURP";
                break;
            case 'BD':
                $stCurp = "BAJA POR DEFUNCION";
                break;
            case 'BDA':
                $stCurp = "BAJA POR DUPLICIDAD";
                break;
            case 'BCC':
                $stCurp = "BAJA POR CAMBIO EN CURP";
                break;
            case 'BCN':
                $stCurp = "BAJA NO AFECTANDO A CURP";
                break;
            default:
                $stCurp = "NINGUNO";
                break;
        }
        $DocProbatorio = $respuesta['ConsultaPorDatosResult']['DocProbatorio'];
        switch ($DocProbatorio) {
            case '1':
                $ADocProbatorio = "ACTA DE NACIMIENTO";
                $anioReg = $respuesta['ConsultaPorDatosResult']['AnioReg'];
                $numActa = $respuesta['ConsultaPorDatosResult']['NumActa'];
                $idEstadoReg = $respuesta['ConsultaPorDatosResult']['EntidadRegistro'];
                $idMunReg = $respuesta['ConsultaPorDatosResult']['MunicipioRegistro'];
                if ($idEstadoReg != '13') {
                    $qEstadoReg = "SELECT estadosMayus from estados where id='$idEstadoReg'";
                    $rEstadoReg = $mysqli->query($qEstadoReg);
                    if ($rEstadoReg->num_rows == 0) {
                        $lugarReg = "";
                    } else {
                        $arrEstadoReg = $rEstadoReg->fetch_assoc();
                        $lugarReg = implode($arrEstadoReg);}
                } else {
                    $qMunReg = "SELECT municipioMayus from municipios where id='$idMunReg'";
                    $rMunReg = $mysqli->query($qMunReg);
                    $arrMunReg = $rMunReg->fetch_assoc();
                    $MunReg = implode($arrMunReg);
                    $lugarReg = $MunReg . ", HIDALGO";
                }
                break;
            case '3':
                $ADocProbatorio = "DOCUMENTO MIGRATORIO";
                $numRegExtrajero = ['ConsultaPorDatosResult']['NumRegExtranjeros'];
                break;
            case '4':
                $ADocProbatorio = "CARTA DE NATURALIZACIÓN";
                $anioReg = $respuesta['ConsultaPorDatosResult']['AnioReg'];
                $folioCarta = $respuesta['ConsultaPorDatosResult']['FolioCarta'];
                break;
            case '7':
                $ADocProbatorio = "CERTIFICADO DE NACIONALIDAD MEXICANA";
                $anioReg = $respuesta['ConsultaPorDatosResult']['AnioReg'];
                $folioCarta = $respuesta['ConsultaPorDatosResult']['FolioCarta'];
                break;
            default:
                $ADocProbatorio = "TRAMITE ANTE SEGOB";
                $folio = $respuesta['ConsultaPorDatosResult']['CRIP'];
                break;
        }
        if ($DocProbatorio != 1) {
            $anioReg = null;
            $numActa = null;
            $lugarReg = null;
        }
    }
}
if ($sex == 'M') {
    $sexo1 = 'MUJER';
} elseif ($sex == 'H') {
    $sexo1 = 'HOMBRE';
}

$qEstado = "SELECT estado from estados where clave='$edoNac'";
$rEstado = $mysqli->query($qEstado);
while ($rwEdo = $rEstado->fetch_assoc()) {
    $edoNac = $rwEdo['estado'];
}
if (!empty($curp)) {
    $qverificarCurp = "SELECT id, folio from nna_adopcion where curp='$curp'";
    $rverificarCurp = $mysqli->query($qverificarCurp);
    $existeCurp = $rverificarCurp->num_rows; //virifica que esa curp no este registrada ya
    if ($existeCurp > 0) {
        while ($rowNnaRegistrado = $rverificarCurp->fetch_assoc()) { //si ya esta registrada toma el id y folio de la tabla nna
            $idNnaReg = $rowNnaRegistrado['id'];
            $folioNnaReg = $rowNnaRegistrado['folio'];
        }
    }
}

?>
<!DOCTYPE HTML>
<html>

<head lang="es-ES">
    <title>Registrar NNA</title>
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
                        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
                    </div>
                </div>
                <!--cod -->
                <h2>Registro de NNA</h2>
                <?php if (empty($estatus)) {?>
                <h3>Datos incompletos, no fue posible la validación de CURP.</h3>
                <?php } elseif ($estatus == 'NO EXITOSO') {?>
                <h3>No se encontraron los datos.</h3>
                <?php } elseif ($estatus == 'EXITOSO') {?>
                <h3>¡Consulta exitosa! CURP validada.</h3>
                <?php }?>
                <form id="formRegistro">
                    <div class="box">
                        <div class="row uniform">
                            <div class="4u">
                                <label>Nombre(s):</label>
                                <input type="text" name="txtNombre" id="txtNombre" value="<?=$nombre?>" disabled>
                            </div>
                            <div class="4u">
                                <label>Apellido Paterno:</label>
                                <input type="text" name="txtApeP" id="txtApeP" value="<?=$ape1?>" disabled>
                            </div>
                            <div class="4u">
                                <label>Apellido Materno:</label>
                                <input type="text" name="txtApeM" id="txtApeM" value="<?=$ape2?>" disabled>
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="4u">
                                <label>CURP:</label>
                                <input type="text" name="txtCurp" id="txtCurp" value="<?=$curp?>" disabled>
                            </div>
                            <div class="2u">
                                <label>Sexo:</label>
                                <input type="text" name="txtSexo" id="txtSexo" value="<?=$sexo?>" disabled>
                            </div>
                            <div class="3u">
                                <label>Fecha de nacimiento:</label>
                                <input type="date" name="txtFechaNac" id="txtFechaNac" value="<?=$fecNac?>" disabled>
                            </div>
                            <div class="3u">
                                <label>Estado de nacimiento:</label>
                                <input type="text" name="txtEdoNac" id="txtEdoNac" style="text-transform:uppercase;"
                                    value="<?=$edoNac?>" disabled>
                            </div>
                        </div>

                    </div>
                    <?php if ($existeCurp > 0) {?>
                    <div class="row uniform">
                        <div class="12u">
                            <input type="button" name="nnaExistente" class="button special fit"
                                value="Esta CURP ya esta registrada con el folio <?=$folioNnaReg?>"
                                onclick="location='perfil_nna.php?id=<?=$idNnaReg?>'">
                        </div>
                    </div>
                    <?php } else {?>
                    <h3>Completa la siguiente información para continuar.</h3>
                    <div class="box">
                        <div class="row uniform">
                            <div class="3u">
                                <label>No. de acta</label>
                                <input name="txtNoActa" id="txtNoActa" type="text" maxlength="50"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                            <div class="3u">
                                <label>Fecha</label>
                                <input type="date" id="txtFechaReg" name="txtFechaReg"
                                    value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="6u">
                                <label>Responsable de registro</label>
                                <input id="txtResponsable" name="txtResponsable" type="text" maxlength="50"
                                    value="<?=$responsable;?>" style="text-transform:uppercase;"
                                    onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="6u">
                                <label>Persona(s) otorgantes</label>
                                <textarea name="txtOtorgante" id="txtOtorgante" rows="2" cols="40"
                                    style="text-transform:uppercase;"
                                    onkeyup="this.value=this.value.toUpperCase();"></textarea>
                            </div>
                            <div class="6u">
                                <label>Observaciones</label>
                                <textarea name="txtObservaciones" id="txtObservaciones" rows="2" cols="40"
                                    style="text-transform:uppercase;"
                                    onkeyup="this.value=this.value.toUpperCase();"></textarea>
                            </div>

                        </div>
                    </div>
                    <input type="button" id="btnGuardar" class="button special fit" value="Guardar">
                    <input type="button" id="btnCancelar" class="button fit" value="Cancelar"
                        onclick="location='nna_susceptibles_adopcion.php'">
                </form>
                <?php }?>
            </div>
        </div>
        <div id="sidebar">
            <div class="inner">
                <!-- Menu -->
                <nav id="menu">
                    <header class="major">
                        <h2>Menú</h2>
                    </header>
                    <ul>
                        <li><a href="welcome.php">Inicio</a></li>
                        <li><a href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </nav>
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
    <script>
    $('#btnGuardar').click(function() {
        var idResponsable = "<?=$idResponsable?>";
        $.ajax({
            type: 'POST',
            url: 'insertar_nna_s.php',
            data: {
                nombre: $('#txtNombre').val(),
                apellidoP: $('#txtApeP').val(),
                apellidoM: $('#txtApeM').val(),
                curp: $('#txtCurp').val(),
                fechaNac: $('#txtFechaNac').val(),
                sexo: $('#txtSexo').val(),
                estado: $('#txtEdoNac').val(),
                noActa: $('#txtNoActa').val(),
                fechaReg: $('#txtFechaReg').val(),
                idResponsable: idResponsable,
                personaOtorga: $('#txtOtorgante').val(),
                observaciones: $('#txtObservaciones').val(),
            },
            success: function(data) {
                alert(data);
                location.href = "nna_susceptibles_adopcion.php";
            }
        });
        return false;
    });
    </script>

</body>

</html>