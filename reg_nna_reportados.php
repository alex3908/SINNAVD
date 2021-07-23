<?php
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
$zonahoraria = date_default_timezone_get();
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}

$idDEPTO = $_SESSION['id']; //depto: proteccion, representacion, vinculacion; personal: administrativo y todas las subprocus
$valida = "SELECT id from departamentos where (id_depto in ('9','10','14') and id_personal='3'
    and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5'
    and id='$idDEPTO') or (id_personal='1' and id='$idDEPTO' and id_depto='16')"; //ayuda a validar la persona que puede mover
$evalida = $mysqli->query($valida);
$reva = $evalida->num_rows;
$folio = ($_GET['idPosibleCaso']);
$qidReporte = "SELECT folio from reportes_vd where id_posible_caso='$folio' ";
$ridReporte = $mysqli->query($qidReporte);
$vidReporte = $ridReporte->fetch_assoc();
$idReporte = implode($vidReporte);
$fechaMostrar = date("d/m/Y");
$fecMax = date("Y-m-d");
$fec = strtotime('-18 year, -2 month', strtotime($fecMax));
$fecMin = date('Y-m-d', $fec);
$buscaf = "SELECT id_personal, responsable, maltratos as maltrato
    from departamentos inner join reportes_vd on reportes_vd.respo_reg=departamentos.id
     where id_posible_caso='$folio'";
$ebf = $mysqli->query($buscaf);
while ($rowCargo = $ebf->fetch_assoc()) {
    $responsable = $rowCargo['responsable'];
    $cargo = $rowCargo['id_personal'];
    $maltrato = $rowCargo['maltrato'];
}

$qNnas = "SELECT nna_reportados.id as idNna, nombre, apellido_p, apellido_m, sexo.sexo, date_format(nna_reportados.fecha_nacimiento,'%d/%m/%Y') as fecha_nac, lugar_nacimiento, edad
	from nna_reportados left join sexo on nna_reportados.sexo=sexo.id
	where id_posible_caso='$folio'";
$rNnas = $mysqli->query($qNnas);
$rNnas2 = $mysqli->query($qNnas);
$numNna = $rNnas->num_rows;
$padreCovid = 0;
$madreCovid = 0;
if (!empty($_POST['registrar'])) {
    $time = time();
    $fecha = date("Y-m-d H:i:s", $time);
    $nombre = mysqli_real_escape_string($mysqli, $_POST['nombre']);
    $apellido_p = mysqli_real_escape_string($mysqli, $_POST['apellido_p']);
    $apellido_m = mysqli_real_escape_string($mysqli, $_POST['apellido_m']);
    $edad = mysqli_real_escape_string($mysqli, $_POST['edad']);
    $sexo = $_POST['sexo'];
    $padresCovid = $_POST['padresCovid'];
    $fecha_nacimiento = mysqli_real_escape_string($mysqli, $_POST['fecha_nacimiento']);
    if ($fecha_nacimiento == "") {
        $fecha_nacimiento = "1900-01-01";
    }

    if ($padresCovid == 1 or $padresCovid == 3) {
        $padreCovid = 1;
    }

    if ($padresCovid == 2 or $padresCovid == 3) {
        $madreCovid = 1;
    }

    $lugar_nacimiento = mysqli_real_escape_string($mysqli, $_POST['lugar_nacimiento']);
    $lugar_registro = mysqli_real_escape_string($mysqli, $_POST['lugar_registro']);
    $sqlNino = "INSERT INTO nna_reportados (id_posible_caso, nombre, apellido_p, apellido_m, sexo, edad, fecha_nacimiento, lugar_nacimiento, lugar_registro, fecha_registro, responsable_registro, fecha_actualizacion, padre_fallecido_covid, madre_fallecida_covid)
                values ('$folio','$nombre','$apellido_p','$apellido_m', '$sexo', '$edad', '$fecha_nacimiento','$lugar_nacimiento', '$lugar_registro','$fecha', '$idDEPTO', '$fecha', $padreCovid, $madreCovid)";
    $resultNino = $mysqli->query($sqlNino);

    if ($resultNino > 0) {
        header("Location: reg_nna_reportados.php?idPosibleCaso=$folio");
    } else {
        echo "Error: " . $sqlNino;
    }

}

?>
<!DOCTYPE HTML>

<html>

<head lang="es-ES">
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
                <h3>Reporte con folio: <?php echo $idReporte ?></h3>

                <div class="row uniform">
                    <div class="4u 12u$(xsmall)">Fecha de registro:
                        <input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $fechaMostrar; ?>"
                            disabled>
                    </div>
                    <div class="4u 12u$(xsmall)">Responsable del registro:
                        <input id="responsable" name="responsable" type="text" value="<?php echo $responsable; ?>"
                            disabled>
                    </div>

                    <div class="4u 12u$(xsmall)">Tipo de maltrato:
                        <input id="maltrato" name="maltrato" type="text" value="<?php echo $maltrato ?>" disabled>
                    </div>
                </div><br>
                <div class="box">
                    <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF'];?>" method="POST">

                        <div class="row uniform">
                            <div class="4u 12u$(xsmall)">Nombre (s):
                                <input id="nombre" name="nombre" type="text" maxlength="50" required
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                            <div class="3u 12u$(xsmall)">Primer apellido:
                                <input id="apellido_p" name="apellido_p" type="text" value=" " maxlength="50"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                            <div class="3u 12u$(xsmall)">Segundo apellido:
                                <input id="apellido_m" name="apellido_m" type="text" value=" " maxlength="50"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                            <div class="2u 12u$(xsmall)">Sexo
                                <div class="select-wrapper">
                                    <select id="sexo" name="sexo" style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();" required>
                                        <option value="">Seleccione</option>
                                        <?php
$sexo = "SELECT id, sexo FROM sexo";
$resu = $mysqli->query($sexo);
while ($rowS = $resu->fetch_assoc()) {?>
                                        <option value="<?php echo $rowS['id']; ?>"><?php echo $rowS['sexo']; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="3u 12u$(xsmall)">Fecha de nacimiento:
                                <input id="fecha_nacimiento" name="fecha_nacimiento" value="" type="date"
                                    max='<?php echo $fecMax ?>'>
                            </div>
                            <div class="3u 12u$(xsmall)">Lugar de nacimiento:
                                <input id="lugar_nacimiento" name="lugar_nacimiento" value=" " type="text"
                                    maxlength="50" style="text-transform:uppercase;"
                                    onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                            <div class="3u 12u$(xsmall)">Lugar de registro:
                                <input id="lugar_registro" name="lugar_registro" value=" " type="text" maxlength="50"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                            <div class="3u 12u$(xsmall)">Edad:
                                <input id="edad" name="edad" type="text" value=" " maxlength="20"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                        </div><br>
                        <div class="row uniform">
                            <div class="3u 12u$(xsmall">
                                Fallecido por COVID-19:
                            </div>
                            <div class="3u 12u$(xsmall)">
                                <input type="radio" id="padreCovid" value="1" name="padresCovid">
                                <label for="padreCovid">Padre</label>
                            </div>
                            <div class="3u 12u$(xsmall)">
                                <input type="radio" id="madreCovid" value="2" name="padresCovid">
                                <label for="madreCovid">Madre</label>
                            </div>
                            <div class="3u 12u$(xsmall)">
                                <input type="radio" id="ambosCovid" value="3" name="padresCovid">
                                <label for="ambosCovid">Ambos</label>
                            </div>
                        </div>
                </div><br>
                <div clas="row uniform">
                    <div class="12u 12u$(xsmall)">
                        <input class="button special fit" name="registrar" id="registrar" type="submit"
                            value="Añadir NNA">
                    </div>
                </div>
                <div class="box alt" align="center">
                    <?php if ($numNna > 0) {?>
                    <table>
                        <thead>NNA registrados:<tr>
                                <td><b>Nombre</b></td>
                                <td><b>Sexo </b></td>
                                <td><b>Fecha de nacimiento</b></td>
                                <td><b>Lugar de nacimiento </b></td>
                                <td><b>Edad</b></td>
                            </tr>
                        </thead>

                        <body>
                            <?php while ($row = $rNnas2->fetch_assoc()) {
    $qName = "SELECT id from relacion_names where id_nna_reportado='$row[idNna]' and activo=1";
    $rName = $mysqli->query($qName); //verifica que el nna sea o no name
    $numName = $rName->num_rows;?>

                            <tr>
                                <td><?php echo $row['nombre'] . " " . $row['apellido_p'] . " " . $row['apellido_m']; ?>
                                </td>
                                <td><?php echo $row["sexo"]; ?></td>
                                <td><?php if ($row["fecha_nac"] == "01/01/1900") {
        echo "";
    } else {
        echo $row["fecha_nac"];
    }
    ?> </td>
                                <td><?php echo $row["lugar_nacimiento"]; ?></td>
                                <td><?php echo $row["edad"]; ?> </td>
                                <?php if ($reva == 1) {?>
                                <td><a
                                        href="perfil_nna_reportado.php?idNna=<?php echo $row['idNna']; ?>&id=<?php echo $folio; ?>">Editar</a>
                                </td>
                                <td><a
                                        href="borrar_nna_reportado.php?idNna=<?php echo $row['idNna']; ?>&id=<?php echo $folio; ?>">Eliminar</a>
                                </td>
                                <?php if ($row["sexo"] == 'Mujer' && $row["edad"] < 15) {if ($numName == 0) {?>
                                <td><a href="name.php?id=<?php echo $row['idNna']; ?>&idPc=<?php echo $folio; ?>">Registrar
                                        como NAME</a></td>
                                <?php } else {?>
                                <td><a
                                        href="name.php?id=<?php echo $row['idNna']; ?>&idPc=<?php echo $folio; ?>">NAME</a>
                                </td>
                                <?php }}}}?>
                            </tr>
                        </body>
                    </table>
                    <?php }?>
                </div>
                <div class="box alt" align="center">
                    <div class="row uniform">
                        <div class="12u 12u$(xsmall)">
                            <input class="button fit" type="button" name="Terminar" value="Siguiente"
                                onclick="location='perfil_posible_caso.php?idPosibleCaso=<?php echo $folio ?>'">
                        </div>
                    </div>
                    </form>
                    <script type="text/javascript">
                    $(document).ready(function() {
                        $("#country_id").change(function() {
                            $.get("get_localidades.php", "country_id=" + $("#country_id").val(),
                                function(data) {
                                    $("#state_id").html(data);
                                    console.log(data);
                                });
                        });
                    });
                    </script>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script src="assets/js/main.js"></script>

</body>

</html>