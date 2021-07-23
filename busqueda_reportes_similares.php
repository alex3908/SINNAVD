<?php
ob_start();
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
$zonahoraria = date_default_timezone_get();

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
//$reporte= $_SESSION['reporte']);
$idDEPTO = $_SESSION['id'];
$folioReporte = ($_SESSION['reporte']);
$name = $_SESSION['name'];
$fec = date("Y-m-d  H:i:s", time());
//para meniu
$pv = "SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
$epv = $mysqli->query($pv);
while ($row = $epv->fetch_assoc()) {
    $idDepartamento = $row['id_depto'];
    $idPersonal = $row['id_personal'];
}

//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
$reportesvd = "SELECT id from reportes_vd where atendido='1'
    and (asignado='$idDEPTO' or asignado_psic='$idDEPTO')";
$erepo = $mysqli->query($reportesvd);
$wow = $erepo->num_rows;
/*$folioReporte= $_GET['folioReporte'];
$qReporte="SELECT reportes_vd.id, fecha_registro, cat_estados.estado, municipios.municipio, localidades.localidad,
codigo_postal, asentamiento, nombre_asentamiento, vialidad, calle, num_ext, num_interior, ubicacion,
narracion, otros_datos
from reportes_vd inner join cat_estados on entidad=cat_estados.id_estado
inner join municipios on clm=municipios.id
inner join localidades on id_localidad=localidades.id
inner join cat_asentamientos on id_tipo_asentamiento=cat_asentamientos.id
inner join cat_vialidades on id_tipo_calle=cat_vialidades.id
where folio='$folioReporte'";
$rReporte=$mysqli->query($qReporte);
while ($row=$rReporte->fetch_assoc()) {
$fechaReg=$row['fecha_registro'];
$estado=$row['estado'];
$municipio=$row['municipio'];
$localidad=$row['localidad'];
$cp=$row['codigo_postal'];
$tipoAsentamiento=$row['asentamiento'];
$nombreAsentamiento=$row['nombre_asentamiento'];
$vialidadTipo=$row['vialidad'];
$calle=$row['calle'];
$numExt=$row['num_ext'];
$numInt=$row['num_interior'];
$ubicacion=$row['ubicacion'];
$narracion=$row['narracion'];
$otros=$row['otros_datos'];
$idReporte=$row['id'];
}*/

//obtener datos a traves de un array que pase desde la pagina anterior, en esta pagina se hara el registro

$fechaReg = $folioReporte["fecha_registro"];
$recepcion = $folioReporte["id_recepcion"];
$qrecepcion = "SELECT recepcion from cat_recepcion_reporte where id=$recepcion";
$rRecepcion = $mysqli->query($qrecepcion);
$aRecpcion = implode($rRecepcion->fetch_assoc());
$distrito = $folioReporte["id_distrito"];
$qDistrito = "SELECT distrits from distritos where id=$distrito";
$rDistrito = $mysqli->query($qDistrito);
if (empty($distrito)) {
    $aDistrito = "Sin Registro";
} else {
    $aDistrito = implode($rDistrito->fetch_assoc());
}

$maltrato = $folioReporte["id_maltrato"];
$persona_reporte = $folioReporte["persona_reporte"];
$narracion = $folioReporte["narracion"];
$idmunicipio = $folioReporte["clm"];
$idLocalidad = $folioReporte["id_localidad"];
$calle = $folioReporte["calle"];
$ubicacion = $folioReporte["ubicacion "];
$otros = $folioReporte["otros_datos"];
$entidad = $folioReporte["entidad"];
$cp = $folioReporte["codigo_postal"];
$idAsentamiento = $folioReporte["id_tipo_asentamiento"];
$nombreAsentamiento = $folioReporte["nombre_asentamiento"];
$idCalle = $folioReporte["id_tipo_calle"];
$numExt = $folioReporte["num_ext"];
$numInt = $folioReporte["num_interior"];
$viajaS_A = $folioReporte["viaja_s_a"];
$mig = $folioReporte["migrante"];

$qdireccion1 = "SELECT e.estado, m.municipio, l.localidad
    FROM procu.localidades l inner join municipios m on m.id=l.id_mun
    inner join estados e on e.id=m.id_estado where e.id=$entidad and m.id=$idmunicipio and l.id=$idLocalidad";
$rdireccion1 = $mysqli->query($qdireccion1);

while ($rd = $rdireccion1->fetch_assoc()) {
    $estado = $rd['estado'];
    $municipio = $rd['municipio'];
    $localidad = $rd['localidad'];
}

$qasentamiento = "SELECT asentamiento FROM procu.cat_asentamientos where id=$idAsentamiento";
$rasentamiento = $mysqli->query($qasentamiento);
$aAsentamiento = $rasentamiento->fetch_assoc();
$tipoAsentamiento = implode($aAsentamiento);
$qvialidad = "SELECT vialidad FROM procu.cat_vialidades where id=$idCalle";
$rvialidad = $mysqli->query($qvialidad);
$aVialidad = $rvialidad->fetch_assoc();
$tipoCalle = implode($aVialidad);

$limitefecha = strtotime('-6 month', strtotime($fechaReg)); //establece las fechas entre las que se buscaran reportes que tengan que ver con lo  mismo
$limitefecha = date('Y-m-j', $limitefecha);
$limitefecha2 = strtotime('+1 day', strtotime($fec));
$limitefecha2 = date('Y-m-j', $limitefecha2);
$qReportesRelacionados = "SELECT pc.folio as folioPC, pc.id as IdPc, r.folio, r.fecha_registro, entidad, cat_estados.estado, municipios.municipio,
    localidades.localidad, r.codigo_postal, asentamiento, r.nombre_asentamiento, vialidad, r.calle, r.num_ext, r.id_posible_caso,
    r.num_interior, r.ubicacion, r.narracion, r.otros_datos, r.nom_nna
    from reportes_vd r inner join posible_caso pc on pc.id=r.id_posible_caso
    inner join cat_estados on entidad=cat_estados.id_estado
    inner join municipios on clm=municipios.id
    inner join localidades on id_localidad=localidades.id
    left join cat_asentamientos on id_tipo_asentamiento=cat_asentamientos.id
    left join cat_vialidades on id_tipo_calle=cat_vialidades.id
    where estado='$estado' and municipio='$municipio' and localidad='$localidad'
    or nombre_asentamiento like '%$nombreAsentamiento%'
    and r.fecha_registro BETWEEN CAST('$limitefecha' as date) and CAST('$limitefecha2' as date)
    and id_posible_caso is not null";

if (!empty($_POST['CrearPosibleCaso'])) {
    $qidPosibleCaso = "SELECT max(id) from posible_caso";
    $ridPosibleCaso = $mysqli->query($qidPosibleCaso);
    while ($row1 = $ridPosibleCaso->fetch_assoc()) {
        $num = $row1['max(id)'];
    }
    $idPosCaso = $num + 1;
    $folioPC = "PC0" . $idPosCaso;
    $sqlf = "SELECT max(id) from reportes_vd";
    $esqlf = $mysqli->query($sqlf);
    while ($row = $esqlf->fetch_assoc()) {
        $num = $row['max(id)'];
    }
    $idReporte = $num + 1;
    $folioRep = "RP0" . $idReporte;
    $qrelacionarPosibleCaso = "INSERT INTO reportes_vd (id,folio, fecha_registro, id_recepcion, id_distrito, maltratos,
    persona_reporte, narracion, clm, id_localidad, calle, ubicacion, otros_datos, respo_reg, entidad, codigo_postal,
    id_tipo_asentamiento, nombre_asentamiento, id_tipo_calle, num_ext, num_interior, id_posible_caso,viajaSA, tipo_migrante)
    values ('$idReporte','$folioRep','$fechaReg','$recepcion','$distrito', '$maltrato', '$persona_reporte','$narracion',
    '$idmunicipio', '$idLocalidad', '$calle', '$ubicacion','$otros','$idDEPTO', '$entidad' , '$cp',
    '$idAsentamiento', '$nombreAsentamiento', '$idCalle', '$numExt', '$numInt','$idPosCaso','$viajaS_A','$mig')";
    $rRelacionarPosibleCaso = $mysqli->query($qrelacionarPosibleCaso);
    if ($rRelacionarPosibleCaso) {
        $_SESSION['reporte'] = null;
        $qRegPosibleCaso = "INSERT INTO posible_caso (id, folio, fecha_registro, responsable_registro)
    values ('$idPosCaso', '$folioPC', '$fec', '$idDEPTO')";
        $rRegPosibleCaso = $mysqli->query($qRegPosibleCaso);
        if ($rRegPosibleCaso) {
            if (!empty($name)) {
                $nombre = $name["nombre"];
                $apellido_p = $name["apellido_p"];
                $apellido_m = $name["apellido_m"];
                $fecha_nacimiento = $name["fecha_nacimiento"];
                $edad = $name["edad"];
                $lugar_nacimiento = $name["lugar_nacimiento"];
                $lugar_registro = $name["lugar_registro"];
                $idReporteName = $name["idReporteName"];
                $numControl = $name["numControl"];
                $numReporte = $name["numReporte"];
                $fechaRegName = $name["fechaRegName"];
                $nombreCaso = $name["nombreCaso"];
                $estado_coespo = $name["estado_coespo"];

                $qInsertarName = "INSERT INTO nna_reportados (id_posible_caso, nombre, apellido_p, apellido_m,
          sexo, edad, fecha_nacimiento, lugar_nacimiento, lugar_registro, fecha_registro, responsable_registro, fecha_actualizacion)
          values ('$idPosCaso', '$nombre', '$apellido_p', '$apellido_m', '2', '$edad', '$fecha_nacimiento',
          '$lugar_nacimiento', '$lugar_registro', '$fec', '$idDEPTO', '$fec')";
                $rInsertarName = $mysqli->query($qInsertarName);
                if ($rInsertarName) {
                    $qidNna = "SELECT id from nna_reportados where id_posible_caso='$idPosCaso'";
                    $ridNna = $mysqli->query($qidNna);
                    $AidNna = $ridNna->fetch_assoc();
                    $idNna = implode($AidNna);
                    $qRelacionNames = "INSERT INTO `relacion_names`(id_nna_reportado, `id_name`, `num_control_coespo`, `numero_reporte_coespo`,
          `id_reporte_sinnavd`, `fecha_registro`, `id_persona_reg`, `fecha_registro_si_names`, nombre_caso_coespo, estado_coespo)
          VALUES ('$idNna', '$idReporteName', '$numControl', '$numReporte', '$idReporte', '$fec', '$idDEPTO', '$fechaRegName', '$nombreCaso', '$estado_coespo')";
                    $rRelacionNames = $mysqli->query($qRelacionNames);
                    if ($rRelacionNames) {
                        header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosCaso");
                    } else {
                        echo "Error: " . $qRelacionNames;
                    }

                } else {
                    echo "Error: " . $qInsertarName;
                }

            } else {
                header("Location: reg_nna_reportados.php?idPosibleCaso=$idPosCaso");
            }

        } else {
            echo "Error: " . $qRegPosibleCaso;
        }

    } else {
        echo "Error: " . $qrelacionarPosibleCaso;
    }

}

if (!empty($_POST['btnBuscar'])) {
    $nomNna = mysqli_real_escape_string($mysqli, $_POST['txtNomNna']);
    $apeP = mysqli_real_escape_string($mysqli, $_POST['txtApePNna']);
    $apeM = mysqli_real_escape_string($mysqli, $_POST['txtApeMNna']);
    $qReportesRelacionados = "SELECT distinct pc.folio as folioPC,  pc.id as IdPc, r.folio, r.fecha_registro, entidad, cat_estados.estado, municipios.municipio,
    localidades.localidad, r.codigo_postal, asentamiento, r.nombre_asentamiento, vialidad, r.calle, r.num_ext, r.id_posible_caso,
    r.num_interior, r.ubicacion, r.narracion, r.otros_datos,  concat(n.nombre,' ', n.apellido_p,' ', n.apellido_m) as nom_nna
    from reportes_vd r inner join posible_caso pc on pc.id=r.id_posible_caso
    inner join cat_estados on entidad=cat_estados.id_estado
    inner join municipios on clm=municipios.id
    inner join localidades on id_localidad=localidades.id
    left join nna_reportados n on n.id_posible_caso=pc.id
    left join cat_asentamientos on id_tipo_asentamiento=cat_asentamientos.id
    left join cat_vialidades on id_tipo_calle=cat_vialidades.id
    where (nombre like '%$nomNna%' and apellido_p like '%$apeP%' and apellido_m like '%$apeM%')";

}
$rReportesRelacionados = $mysqli->query($qReportesRelacionados);
$numReportes = $rReportesRelacionados->num_rows;

?>
<!DOCTYPE HTML>
<html>

<head lang="ES-es">
    <title>Posibles reportes relacionados</title>
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
                        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
                    </div>
                </div>
                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
                    <div class="box">
                        <div class="row uniform">
                            <div class="8u 12u$(xsmall)">
                                <h2>Por favor, verifique su información</h2>
                            </div>
                            <div class="1u 12u$(xsmall)">
                                <label for="txtFechaReg">Fecha de registro:</label>
                            </div>
                            <div class="3u 12u$(xsmall)">
                                <input type="text" id="txtFechaReg" name="txtFechaReg" value="<?php echo $fechaReg; ?>"
                                    disabled>
                            </div>
                        </div>
                    </div>
                    <div class="box">
                        <div class="row uniform">
                            <div class="3u">
                                <label for="txtRecepcion">Recepción</label>
                                <input type="text" id="txtRecepcion" name="txtRecepcion"
                                    value="<?php echo $aRecpcion; ?>" disabled>
                            </div>
                            <div class="2u">
                                <label for="txtDistrito">Distrito</label>
                                <input type="text" id="txtDistrito" name="txtDistrito" value="<?php echo $aDistrito; ?>"
                                    disabled>
                            </div>
                            <div class="7u">
                                <label for="txtMal">Maltratos identificados</label>
                                <input type="text" id="txtMal" name="txtMal" value="<?php echo $maltrato; ?>" disabled>
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="2u 12u$(xsmall)">
                                <label for="txtEntidad">Entidad:</label>
                                <input type="text" id="txtEntidad" name="txtEntidad" value=" <?php echo $estado; ?>"
                                    disabled>
                            </div>
                            <div class="4u 12u$(xsmall)">
                                <label for="txtMunicipio">Municipio:</label>
                                <input type="text" id="txtMunicipio" name="txtMunicipio"
                                    value="<?php echo $municipio; ?>" disabled>
                            </div>
                            <div class="4u 12u$(xsmall)">
                                <label for="txtLocalidad">Localidad:</label>
                                <input type="text" id="txtLocalidad" name="txtLocalidad"
                                    value="<?php echo $localidad; ?>" disabled>
                            </div>
                            <div class="2u 12u$(xsmall)">
                                <label for="txtCP">CP:</label>
                                <input type="text" id="txtCP" name="txtCP" value="<?php echo $cp; ?>" disabled>
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="3u 12u$(xsmall)">
                                <label for="txtTipoAsentamiento">Tipo de asentamiento:</label>
                                <input type="text" id="txtTipoAsentamiento" name="txtTipoAsentamiento"
                                    value=" <?php echo $tipoAsentamiento; ?>" disabled>
                            </div>
                            <div class="3u 12u$(xsmall)">
                                <label for="txtAsentamiento">Nombre del asentamiento:</label>
                                <input type="text" id="txtAsentamiento" name="txtAsentamiento"
                                    value=" <?php echo $nombreAsentamiento; ?>" disabled>
                            </div>
                            <div class="3u 12u$(xsmall)">
                                <label for="txtTipoVialidad">Tipo de vialidad:</label>
                                <input type="text" id="txtTipoVialidad" name="txtTipoVialidad"
                                    value="<?php echo $tipoCalle; ?>" disabled>
                            </div>
                            <div class="3u 12u$(xsmall)">
                                <label for="txtVialidad">Nombre de la vialidad:</label>
                                <input type="text" id="txtVialidad" name="txtVialidad" value="<?php echo $calle; ?>"
                                    disabled>
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="3u 12$(xsmall)">
                                <div class="row uniform">
                                    <div class="12u 12u$(xsmall)">
                                        <label for="txtNumEx">Número ext.</label>
                                        <input type="text" id="txtNumEx" name="txtNumEx" maxlength="100"
                                            style="text-transform:uppercase;" value="<?php echo $numExt; ?>"
                                            onkeyup="this.value=this.value.toUpperCase();" disabled>
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="12u 12u$(xsmall)">
                                        <label for="txtNumInt">Número int.</label>
                                        <input type="text" id="txtNumInt" name="txtNumInt" maxlength="100"
                                            style="text-transform:uppercase;" value="<?php echo $numInt; ?>"
                                            onkeyup="this.value=this.value.toUpperCase();" disabled>
                                    </div>
                                </div>
                                <!--uniform-->
                            </div>
                            <!--3u-->
                            <div class="9u 12$(xsmall)">
                                <div class="row uniform">
                                    <div class="12u 12u$(xsmall)">
                                        <label for="ubicacion">Referencias sobre el domicilio</label>
                                        <textarea name="ubicacion" rows="5" maxlength="300" cols="100"
                                            style="text-transform:uppercase;"
                                            onkeyup="this.value=this.value.toUpperCase();" disabled><?php echo $ubicacion; ?>
                    </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--cierre row-->
                        <br>
                        <h3>Descripción de la situación</h3>
                        <div class="row uniform">
                            <div class="6u 12u$(xsmall)">
                                <label for="txtNarracion">Descripción de la situacion:</label>
                                <textarea name="txtNarracion" id="txtNarracion" rows="6" cols="20" maxlength="1000"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                    disabled><?php echo $narracion; ?></textarea>
                            </div>
                            <div class="6u 12u$(xsmall)">
                                <label for="txtDatosRelevates">Otros datos u observaciones relevantes:</label>
                                <textarea name="txtDatosRelevates" id="txtDatosRelevates" rows="6" cols="20"
                                    maxlength="1000" style="text-transform:uppercase;"
                                    onkeyup="this.value=this.value.toUpperCase();"
                                    disabled><?php echo $otros; ?></textarea>
                            </div>
                        </div><br>
                    </div><br>
                    <div class="row uniform">
                        <div class="12u 12u$(xsmall)">
                            <input class="button special fit" name="CrearPosibleCaso" type="submit"
                                value="Crear posible caso">
                        </div>
                    </div>
                </form>
                <div class="box alt" align="center">
                    <h3>Reportes posiblemente relacionados</h3>
                    <p>
                    <div class="row uniform">
                        <div class="12u 12u$(xsmall)">
                            Busqueda por NNA</div>
                    </div>
                    <form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>"
                        onSubmit="return validarForm(this)">
                        <div class="row uniform">
                            <div class="3u 12u$(xsmall)">
                                <input type="text" id="txtNomNna" name="txtNomNna" maxlength="30"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                    placeholder="NOMBRE">
                            </div>
                            <div class="3u 12u$(xsmall)">
                                <input type="text" id="txtApePNna" name="txtApePNna" maxlength="30"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                    placeholder="Apellido paterno">
                            </div>
                            <div class="3u 12u$(xsmall)">
                                <input type="text" id="txtApeMNna" name="txtApeMNna" maxlength="30"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                    placeholder="Apellido Materno">
                            </div>
                            <div class="3u 12u$(xsmall)">
                                <input class="button special fit" name="btnBuscar" type="submit" value="Buscar">
                            </div>
                        </div>
                    </form>
                    </p>
                    <?php while ($row2 = $rReportesRelacionados->fetch_assoc()) {
    $idPC = $row2['IdPc'];?>
                    <div class="box">
                        <div class="box-header with-border">
                            <div class="row uniform">
                                <div class="9u 12u$(xsmall)">
                                    <h5>Reporte con folio <?php echo $row2['folio']; ?> perteneciente al posible caso
                                        <?php echo $row2['folioPC']; ?></h5>
                                </div>
                                <div class="3u 12u$(xsmall)">
                                    <input class="button" name="relacionar" id="relacionar" value="Añadir"
                                        onclick="location='relacionarRep-PosCaso.php?idPC=<?php echo $idPC; ?>'">
                                </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <table>
                                <thead>
                                    <tr>
                                        <td>Fecha de registro</td>
                                        <td>Entidad</td>
                                        <td>Municipio</td>
                                        <td>Localidad</td>
                                        <td>Asentamiento</td>
                                        <td>Vialidad</td>
                                        <td>No. ext.</td>
                                        <td>Ubicación</td>
                                    </tr>
                                </thead>

                                <body>
                                    <tr>
                                        <td><?php echo $row2['fecha_registro']; ?></td>
                                        <td><?php echo $row2['estado']; ?></td>
                                        <td><?php echo $row2['municipio']; ?></td>
                                        <td><?php echo $row2['localidad']; ?></td>
                                        <td><?php echo $row2['asentamiento'] . " " . $row2['nombre_asentamiento']; ?>
                                        </td>
                                        <td><?php echo $row2['vialidad'] . " " . $row2['calle'] ?></td>
                                        <td><?php echo $row2['num_ext'] ?></td>
                                        <td><?php echo $row2['ubicacion'] ?></td>
                                    </tr>
                                </body>
                            </table>
                            <table>
                                <thead>
                                    <tr>
                                        <td>Narración</td>
                                        <td>Otros datos</td>
                                    </tr>
                                </thead>

                                <body>
                                    <tr>
                                        <td><?php echo $row2['narracion'] ?></td>
                                        <td><?php echo $row2['otros_datos'] ?></td>
                                    </tr>
                                </body>
                            </table>
                        </div>
                        <!--cierrre box-body-->
                        <div class="box-footer">
                            <div class="table-wrapper">
                                <h5>Datos del NNA</h5>
                                <table>
                                    <thead>
                                        <tr>
                                            <td>Nombre</td>
                                            <td>Apellido paterno</td>
                                            <td>Apellido Materno</td>
                                            <td>Edad</td>
                                            <td>Sexo</td>
                                        </tr>
                                    </thead>

                                    <body>
                                        <?php $qnnas = "SELECT nombre, apellido_p, apellido_m, edad, sexo.sexo
                      FROM nna_reportados nna left join sexo on sexo.id=nna.sexo
                      where id_posible_caso='$idPC'";
    $rnnas = $mysqli->query($qnnas);
    while ($row3 = $rnnas->fetch_assoc()) {?>
                                        <tr>
                                            <td><?php echo $row3['nombre']; ?></td>
                                            <td><?php echo $row3['apellido_p']; ?></td>
                                            <td><?php echo $row3['apellido_m']; ?></td>
                                            <td><?php echo $row3['edad']; ?></td>
                                            <td><?php echo $row3['sexo']; ?></td>
                                        </tr>
                                        <?php }?>
                                    </body>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--cierre box-->
                    <?php }?>
                </div>
                <br>
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
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script src="assets/js/main.js"></script>
</body>

</html>