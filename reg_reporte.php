<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}

date_default_timezone_set('America/Mexico_City');
$zonahoraria = date_default_timezone_get();
$idDEPTO = $_SESSION['id']; //depto: proteccion, representacion, vinculacion; personal: administrativo y todas las subprocus
$valida = "SELECT id from departamentos where (id_depto in ('9','10','14') and id_personal='3' and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5' and id='$idDEPTO') or (id_personal='1' and id='$idDEPTO' and id_depto='16')"; //ayuda a validar la persona que puede mover
$evalida = $mysqli->query($valida);
$reva = $evalida->num_rows;

$fechaMostrar = date("d/m/Y");
$buscaf = "SELECT id_personal, responsable  from departamentos where id='$idDEPTO'";
$ebf = $mysqli->query($buscaf);
while ($rowCargo = $ebf->fetch_assoc()) {
    $responsable = $rowCargo['responsable'];
    $cargo = $rowCargo['id_personal'];
}
$query = "SELECT * from estados where id!='0' and id!=13";
$equery = $mysqli->query($query);
$countries = array();
while ($row = $equery->fetch_object()) {$countries[] = $row;}

$tmaltrato = "SELECT maltrato, id FROM tipo_maltrato where id!='0' order by id";
$etmaltrato = $mysqli->query($tmaltrato);

$reportesvd = "SELECT id from reportes_vd where atendido='1' and (asignado='$idDEPTO' or asignado_psic='$idDEPTO')";
$erepo = $mysqli->query($reportesvd);
$wow = $erepo->num_rows;

if (!empty($_POST['registar'])) {
    $sqlf = "SELECT max(id) from reportes_vd";
    $esqlf = $mysqli->query($sqlf);
    while ($row = $esqlf->fetch_assoc()) {
        $num = $row['max(id)'];
    }
    $num2 = $num + 1;
    $folio = "RP0" . $num2;
    $time = time();
    $fecha = date("Y-m-d H:i:s", $time);
    $recepcion = $_POST['recepcion'];
    $distrito = $_POST['distrito'];

    foreach ((array) @$_POST["maltrato"] as $valor) {
        @$maltrato = $valor . ", " . $maltrato;

    }

    if (empty($maltrato)) {
        echo "Seleccione al menos un tipo de maltrato";
    } else {
        $mal = "";
        $persona_reporte = mysqli_real_escape_string($mysqli, $_POST['persona_reporte']);
        $entidad = $_POST['country_id'];
        $municipio = $_POST['state_id'];
        $localidad = $_POST['city_id'];

        //ojo: no recupera el id, sino el nombre, lo hice para llenar automaticamente el txt de asentamiento
        $viajaSoloAcomp = $_POST['viaja'];
        $mig = $_POST['migrante'];
        if ($viajaSoloAcomp == "-- SELECCIONE --" || !$mig) {
            $viajaSoloAcomp = "";
        }

        $migracion = "";
        $qidLoc = "SELECT id from localidades where localidad='$localidad' and id_mun='$municipio'";
        $ridLoc = $mysqli->query($qidLoc);
        while ($rowidLoc = $ridLoc->fetch_assoc()) {
            $idLocalidad = $rowidLoc['id']; //Seleciona el id de la comunidad de la base de datos
        }
        if (!$idLocalidad) {

            $idLocalidad = 0;
        }
        $calle = mysqli_real_escape_string($mysqli, $_POST['txtCalle']);
        $narracion = mysqli_real_escape_string($mysqli, $_POST['narracion']);
        $ubicacion = mysqli_real_escape_string($mysqli, $_POST['ubicacion']);
        $datosrelevates = mysqli_real_escape_string($mysqli, $_POST['datosrelevates']);
        $cp = mysqli_real_escape_string($mysqli, $_POST['txtCP']);
        $tipoAsentamiento = $_POST['ddlTipoAsentamiento'];
        $asentamiento = mysqli_real_escape_string($mysqli, $_POST['txtAsentamiento']);
        if ($asentamiento) {
            $asentamiento = mysqli_real_escape_string($mysqli, $_POST['txtAsentamiento']);
        } else {
            $asentamiento = "No definido";
        }
        $tipoCalle = $_POST['ddlTipoCalle'];
        $numExt = mysqli_real_escape_string($mysqli, $_POST['txtNumEx']);
        $numInt = mysqli_real_escape_string($mysqli, $_POST['txtNumInt']);

        /*$error = '';
        $sqlNino = "INSERT INTO reportes_vd (folio, fecha_registro, id_recepcion, id_distrito, id_maltrato,
        persona_reporte, narracion, clm, id_localidad, calle, ubicacion, otros_datos, respo_reg, entidad, codigo_postal,
        id_tipo_asentamiento, nombre_asentamiento, id_tipo_calle, num_ext, num_interior)
        values ('$folio','$fecha','$recepcion','$distrito', '$maltrato', '$persona_reporte','$narracion',
        '$municipio', '$idLocalidad', '$calle', '$ubicacion','$datosrelevates','$idDEPTO', '$entidad' , '$cp',
        '$tipoAsentamiento', '$asentamiento', '$tipoCalle', '$numExt', '$numInt')";
        $resultNino = $mysqli->query($sqlNino);

        if ($resultNino)
        header("Location: busqueda_reportes_similares.php?folioReporte=$folio");
        else
        echo "ERROR: ".$sqlNino;*/
        $reporte = [
            "fecha_registro" => "$fecha",
            "id_recepcion" => "$recepcion",
            "id_distrito" => "$distrito",
            "id_maltrato" => "$maltrato",
            "persona_reporte" => "$persona_reporte",
            "narracion" => "$narracion",
            "clm" => "$municipio",
            "id_localidad" => "$idLocalidad",
            "calle" => "$calle",
            "ubicacion " => "$ubicacion",
            "otros_datos" => "$datosrelevates",
            "entidad" => "$entidad",
            "codigo_postal" => "$cp",
            "id_tipo_asentamiento" => "$tipoAsentamiento",
            "nombre_asentamiento" => "$asentamiento",
            "id_tipo_calle" => "$tipoCalle",
            "num_ext" => "$numExt",
            "num_interior" => "$numInt",
            "viaja_s_a" => "$viajaSoloAcomp",
            "migrante" => "$mig",

        ];
        $_SESSION['reporte'] = $reporte;
        $_SESSION['name'] = null;
        header("Location: busqueda_reportes_similares.php");
    }
}
?>
<!DOCTYPE HTML>

<html>

<head lang="Es-es">
    <title>Registrar reporte</title>
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
                <br> <br>
                <div class="box alt" align="center">
                    <div class="row 10% uniform">
                        <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
                        <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
                        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
                    </div>
                </div>
                <div class="row uniform">
                    <div class="9u 12u$(xsmall)">
                        <h1>Reporte</h1>
                    </div>
                    <div class="3u 12u$(xsmall">
                        <?php if ($idDEPTO == '220') {?>
                        <input class="button special fit" name="btnNames" type="submit" value="Buscar en NAMES"
                            onclick="location='names_lista_reportes_ws.php'">
                        <?php }?>
                    </div>
                </div>
                <div class="box">
                    <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF'];?>" method="POST">

                        <div class="row uniform">
                            <div class="3u 12u$(xsmall)">Fecha de registro:
                                <input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $fechaMostrar; ?>"
                                    disabled>
                            </div>
                            <div class="5u 12u$(xsmall)">Forma de recepción de reporte:
                                <div class="select-wrapper">
                                    <select id="recepcion" name="recepcion" required="true">
                                        <option value="">-- Selecione --</option>
                                        <?php $opc_recepcion = "SELECT id, recepcion from cat_recepcion_reporte";
$ropc_recepcion = $mysqli->query($opc_recepcion);?>
                                        <?php while ($rowR = $ropc_recepcion->fetch_assoc()) {?>
                                        <option value="<?php echo $rowR['id']; ?>"><?php echo $rowR['recepcion']; ?>
                                        </option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="4u 12u$(xsmall)">Distrito:
                                <div class="select-wrapper">
                                    <select id="distrito" name="distrito" required="true">
                                        <option value="">-- Selecione --</option>
                                        <?php $mun = "SELECT id, distrits from distritos";
$emun = $mysqli->query($mun);?>
                                        <?php while ($row = $emun->fetch_assoc()) {?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['distrits']; ?>
                                        </option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="12u">Tipos de maltrato identificados:
                                    <div class="box">
                                        <div class="row justify-content-end">

                                            <?php $i = 0;
while ($rwMal = $etmaltrato->fetch_assoc()) {?>
                                            <div class="4u">
                                                <input type="checkbox" id="<?php echo 'demo-' . $i; ?>"
                                                    name="maltrato[]" onchange=" comprobar2(this)"
                                                    value="<?php echo $rwMal['maltrato']; ?>">
                                                <label for="<?php echo 'demo-' . $i; ?>"><?php echo $rwMal['maltrato'];
    $i++; ?></label>
                                            </div>
                                            <?php $i++;}?>
                                        </div>
                                    </div>
                                </div>
                                <div class="" style=" text-align: left; padding: 0px; padding-left:20px;">

                                    <label style="color:red;" id="lbmaltrato"></label>



                                </div>
                            </div>

                            <!-- cambios temporales subomdulo migracion -->

                            <div class="row uniform box-repo" id="divOpcionM" style="display:none;">
                                <div class="12u">
                                    <p>Opciones Migrantes</p>
                                    <div class="box">
                                        <div class="row miw2">

                                            <input type="checkbox" id="cbox1" name="migrante" onchange="comprobar(this)"
                                                value="NNA MIGRANTE EXTRANJERO">
                                            <label for="cbox1">
                                                NNA MIGRANTE EXTRANJERO
                                            </label>
                                            <input type="checkbox" id="cbox2" name="migrante"
                                                onchange=" comprobar(this)" value="NNA REPATRIADO MEXICANO">
                                            <label for="cbox2">NNA
                                                REPATRIADO MEXICANO
                                            </label>
                                        </div>
                                        <div class="row " id="divSolicitud">
                                            <div class="">Viaja Solo/Acompañado
                                                <div class="select-wrapper">
                                                    <select name="viaja" id="opciones">
                                                        <option value="" selected="selected">-- SELECCIONE --</option>
                                                        <option value="VIAJA SOLO">VIAJA SOLO</option>
                                                        <option value="VIAJA ACOMPAÑADO">VIAJA ACOMPAÑADO</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                                <div class="" style=" text-align: left; padding: 0px; padding-left:20px;">
                                    <label style="color:red;" id="lbopcion"></label>
                                </div>
                            </div>


                            <div class="12u 12u$(small)">
                                <div class="box">Datos de localizacion de la NNA:
                                    <div class="row uniform">
                                        <div class="2u 12u(xsmall)">
                                            <label for="txtCP">Código postal</label>
                                            <input type="text" name="txtCP" id="txtCP" value=0>
                                        </div>
                                        <div class="3u 12u(xsmall)">
                                            <label for="estado">Entidad:</label>
                                            <div class="select-wrapper">
                                                <select id="country_id" class="form-control" name="country_id" required>
                                                    <option value="" selected="selected">-- SELECCIONE --</option>
                                                    <option value="13">Hidalgo</option>
                                                    <?php foreach ($countries as $c): ?>
                                                    <option value="<?php echo $c->id; ?>"><?php echo $c->estado; ?>
                                                    </option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="3u 12u(xsmall)">
                                            <label for="estado">Municipio:</label>
                                            <div class="select-wrapper">
                                                <select id="state_id" class="form-control" name="state_id" required>
                                                    <option value="">-- SELECCIONE --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="4u 12u(xsmall)">
                                            <label for="estado">Localidad:</label>
                                            <div class="select-wrapper">
                                                <select id="city_id" class="form-control" name="city_id" required>
                                                    <option value="">-- SELECCIONE --</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="3u 12u$(xsmall)">
                                            <label for="ddlTipoAsentamiento">Tipo de asentamiento:</label>
                                            <div class="select-wrapper">
                                                <select id="ddlTipoAsentamiento" class="form-control"
                                                    name="ddlTipoAsentamiento" required>
                                                    <option value="7">COLONIA</option>
                                                    <?php $qtipoAsentamiento = "SELECT id, asentamiento from cat_asentamientos";
$rtipoAsentamiento = $mysqli->query($qtipoAsentamiento);?>
                                                    <?php while ($rowAsen = $rtipoAsentamiento->fetch_assoc()) {?>
                                                    <option value="<?php echo $rowAsen['id']; ?>">
                                                        <?php echo $rowAsen['asentamiento']; ?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="3u 12u$(small)">
                                            <label for="txtAsentamiento">Nombre del asentamiento:</label>
                                            <input type="text" id="txtAsentamiento" name="txtAsentamiento"
                                                maxlength="100" style="text-transform:uppercase;"
                                                onkeyup="this.value=this.value.toUpperCase();" required>
                                        </div>
                                        <div class="3u 12u$(xsmall)">
                                            <label for="ddlTipoCalle">Tipo de vialidad:</label>
                                            <div class="select-wrapper">
                                                <select id="ddlTipoCalle" class="form-control" name="ddlTipoCalle"
                                                    required>
                                                    <option value="5">CALLE</option>
                                                    <?php $qtipoVialidad = "SELECT id, vialidad from cat_vialidades";
$rtipoVialidad = $mysqli->query($qtipoVialidad);?>
                                                    <?php while ($rowVialidad = $rtipoVialidad->fetch_assoc()) {?>
                                                    <option value="<?php echo $rowVialidad['id']; ?>">
                                                        <?php echo $rowVialidad['vialidad']; ?></option>
                                                    <?php }?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="3u 12u$(small)">
                                            <label for="txtCalle">Nombre de la vialidad:</label>
                                            <input type="text" id="txtCalle" name="txtCalle" maxlength="100"
                                                style="text-transform:uppercase;"
                                                onkeyup="this.value=this.value.toUpperCase();" required>
                                        </div>
                                        <div class="row uniform">
                                            <div class="3u 12$(xsmall)">
                                                <div class="row uniform">
                                                    <div class="12u 12u$(xsmall)">
                                                        <label for="txtNumEx">Número ext.</label>
                                                        <input type="text" id="txtNumEx" name="txtNumEx" maxlength="20"
                                                            style="text-transform:uppercase;"
                                                            onkeyup="this.value=this.value.toUpperCase();">
                                                    </div>
                                                    <div class="12u 12u$(xsmall)">
                                                        <label for="txtNumInt">Número int.</label>
                                                        <input type="text" id="txtNumInt" name="txtNumInt"
                                                            maxlength="20" style="text-transform:uppercase;"
                                                            onkeyup="this.value=this.value.toUpperCase();">
                                                    </div>
                                                </div>
                                                <!--uniform-->
                                            </div>
                                            <!--4u-->
                                            <div class="8u 12$(xsmall)">
                                                <div class="row uniform">
                                                    <div class="12u 12u$(xsmall)">
                                                        <label for="ubicacion">Referencias sobre el domicilio</label>
                                                        <textarea name="ubicacion" rows="5" maxlength="300" cols="100"
                                                            style="text-transform:uppercase;"
                                                            onkeyup="this.value=this.value.toUpperCase();"
                                                            required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="6u 12u$(xsmall)">Descripcion de la situacion:
                                <textarea name="narracion" rows="6" cols="20" maxlength="1000"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                    required></textarea>
                            </div>

                            <div class="6u 12u$(small)">Otros datos u observaciones relevantes:
                                <textarea name="datosrelevates" rows="6" cols="20" maxlength="1000"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                    required></textarea>
                            </div>

                            <div class="6u 12u$(xsmall)">Persona que reporta:
                                <input id="persona_reporte" name="persona_reporte" maxlength="100" type="text"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                    required>
                            </div>

                            <div class="6u 12u$(xsmall)">Servidor publico:
                                <input id="fp_encargado" style="text-transform:uppercase;" name="fp_encargado"
                                    type="text" disabled value="<?php echo $responsable; ?>">
                            </div>
                            <?php if ($reva == '1' or $idDEPTO = 22) {?>
                            <div class="6u 12u$(xsmall)">
                                <input class="button special fit" onclick="quitar();" name="registar" type="submit"
                                    value="Registrar">
                            </div>
                            <?php } else {?>
                            <div class="6u 12u$(xsmall)">
                                <input class="button special fit" name="registar" type="submit" value="Registrar"
                                    disabled>
                            </div>
                            <?php }?>

                            <div class="6u 12u$(xsmall)">
                                <input class="button fit" type="button" name="cancelar" value="Cancelar"
                                    onclick="location='lista_reportes_nueva.php?estRep=0'">
                            </div>
                        </div>
                    </form>
                    <script type="text/javascript">
                    $(document).ready(function() {
                        $("#country_id").change(function() {
                            $.get("get_states.php", "country_id=" + $("#country_id").val(), function(
                                data) {
                                $("#state_id").html(data);
                                console.log(data);
                            });
                        });
                        $("#state_id").change(function() {
                            $.get("get_asentamiento.php", "state_id=" + $("#state_id").val(), function(
                                data) {
                                $("#city_id").html(data);
                                console.log(data);
                            });
                        });
                    });
                    $("#city_id").change(function() {
                        document.getElementById('txtAsentamiento').value = document.getElementById('city_id')
                            .value; //llena automaticamente el txt de asentaiento para evitar errores de dedo a partir de la localidad
                    });
                    </script>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div id="sidebar">
            <div class="inner">
                <?php $_SESSION['spcargo'] = $cargo;?>
                <?php if ($cargo == 6) {?>
                <!-- Menu -->
                <nav id="menu">
                    <header class="major">
                        <h2>Menú</h2>
                    </header>
                    <ul>
                        <li><a href="lista_unidad.php">UIENNAVD</a></li>
                        <li><a href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </nav>
                <?php } else if ($cargo == 5) {?>
                <!-- Menu -->
                <nav id="menu">
                    <header class="major">
                        <h2>Menú</h2>
                    </header>
                    <ul>
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
                        <li><a href="lista_personal.php">Personal</a></li>
                        <li><a href="lista_usuarios.php">Usuarios</a></li>
                        <?php if ($_SESSION['departamento'] == 7) {?>
                        <li><a href="canalizar.php">Canalizar visita</a></li>
                        <?php }?>
                        <li><a href="lista_reportes_nueva.php?estRep=0">Reportes
                                VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a>
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
                        <?php if ($_SESSION['departamento'] == 16) {?>
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
        <!--cierre menu-->

    </div>


    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <script src="assets/js/main.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script type="text/javascript">
    var check = "";
    var valor = "";

    function quitar() {
        var checkbox = document.getElementById('cbox1');
        var checkbox2 = document.getElementById('cbox2');
        if (check.checked && valor == "MIGRANTES") {
            if (checkbox.checked || checkbox2.checked) {
                var opcion = "";
                document.getElementById('lbopcion').innerHTML = opcion;
                $('#opciones').prop("required", true);
            } else {
                var opcion = "*Seleccione al menos una opción";
                document.getElementById('lbopcion').innerHTML = opcion;
                $('#cbox1').prop("required", true);
            }
        } else {
            $('#opciones').removeAttr("required");
        }

    }

    function comprobar(checkbox) {
        otro = checkbox.parentNode.querySelector("[type=checkbox]:not(#" + checkbox.id + ")");
        document.getElementById('lbopcion').innerHTML = "";
        $('#cbox1').removeAttr("required");
        if (otro.checked) {
            otro.checked = false;
        } else {
            $("#opciones").val('')
        }
    }

    function comprobar2(checkbox) {


        check = document.getElementById(checkbox.id);
        valor = check.value;

        if (check.checked && valor == "MIGRANTES") {
            document.getElementById('lbopcion').innerHTML = "";
            div = document.getElementById('divOpcionM');
            div.style.display = '';
            var checkbox = document.getElementById('cbox1');
            var checkbox2 = document.getElementById('cbox2');
            checkbox.checked = false;
            checkbox2.checked = false;
            $("#opciones").val('')
            $('#cbox1').prop("required", true);

        } else {
            if (!check.checked && valor == "MIGRANTES") {
                div = document.getElementById('divOpcionM');
                div.style.display = 'none';
                $('#cbox1').removeAttr("required");
            }

        }



    }
    </script>

    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->

</body>

</html>