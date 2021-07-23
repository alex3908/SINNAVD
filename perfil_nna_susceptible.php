<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}

$idDEPTO = $_SESSION['id'];

$idnna = $_GET['id'];

$pv = "SELECT id, id_depto, id_personal from departamentos where id='$idDEPTO'";
$epv = $mysqli->query($pv);
while ($row = $epv->fetch_assoc()) {
    $idDepartamento = $row['id_depto'];
    $idPersonal = $row['id_personal'];
    $idResponsable = $row['id'];
}

$nnaCentros = "SELECT nna_susceptible_adop.id,nna.id as id_nna_susc,nna_susceptible_adop.id_nna_centro, nna_susceptible_adop.id_nna_centro, nna.folio,nna.nombre,nna.apellido_p,nna.apellido_m,nna.curp, centros.nombre AS nombre_centro,nna_centros.motivo,nna_centros.situacionJ,nna_centros.cuidado_procu, date_format(nna_susceptible_adop.fecha_registro, '%d/%m/%Y') as fecha_registro,nna_centros.motivo, nna_susceptible_adop.id_responsable,nna.fecha_nacimiento, nna_susceptible_adop.asignado
FROM nna_susceptible_adop
JOIN nna ON nna_susceptible_adop.folio = nna.folio
jOIN nna_centros ON nna_susceptible_adop.id_nna_centro = nna_centros.id
JOIN centros ON nna_susceptible_adop.id_centro = centros.id
WHERE nna_susceptible_adop.id =$idnna";

$query = "SELECT nna_centros.id AS id_nna_centros, centros.id AS id_centro,nna.id AS id_nna
from nna_centros
INNER JOIN nna ON nna_centros.id_nna = nna.id
INNER JOIN centros ON nna_centros.id_centro = centros.id
WHERE nna_centros.id=$idnna";

$nnaCentros = $mysqli->query($nnaCentros);
$query = $mysqli->query($query);

while ($row = $query->fetch_assoc()) {
    $idnna = $row['id_nna'];
    $idCentro = $row['id_centro'];

}

function responsable($id)
{
    require 'conexion.php';
    $pv = "SELECT responsable from departamentos where id='$id'";
    $epv = $mysqli->query($pv);
    return implode($epv->fetch_assoc());
}

function calculaedad($fecha)
{
    list($ano, $mes, $dia) = explode("-", $fecha);
    $ano_diferencia = date("Y") - $ano;
    $mes_diferencia = date("m") - $mes;
    $dia_diferencia = date("d") - $dia;
    if ($dia_diferencia < 0 && $mes_diferencia <= 0) {
        $ano_diferencia--;
    }

    return $ano_diferencia;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil NNA</title>
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="shortcut icon" href="images/favicon.png" type="image/png" />

</head>

<body>

    <div id="wrapper">
        <!-- Main -->
        <div id="main">
            <div class="inner"><br> <br>
                <div class="box alt" align="center">
                    <div class="row 10% uniform">
                        <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
                        <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
                        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
                    </div>
                </div>
                <input class="button small" type="button" value="Regresar" onclick="history.back()">
                <br>
                <br>
                <?php while ($row = $nnaCentros->fetch_assoc()) {
    $idNnaCentro = $row['id_nna_susc'];
    ?>
                <div style="display: inline-block; width: 100%; margin-bottom: 35px; margin-left: -12px;">
                    <b
                        style="font-size: 40px;"><?php echo $row['nombre'] . ' ' . $row['apellido_p'] . ' ' . $row['apellido_m']; ?></b>
                    <input id="editarAsignacion" style="margin-right: 20px; float: right; margin-top:15px;"
                        type="button" class="button special" <?php if ($row['asignado'] == 1) {?>value="NNA Asignado"
                        disabled <?php } else {?> value="Asignar NNA" <?php }?>>
                </div>

                <div class="row uniform">
                    <table>
                        <tr>
                            <td colspan="5">
                                <h3>Curp: <?php echo $row['curp']; ?></h3>
                            </td>
                        </tr>
                        <td colspan="3">
                            <h4 style="margin: 0px;">
                                <b style="font-size: 17px;"> Folio: </b> <a href=""><?=$row['folio'];?></a>
                            </h4>
                        </td>
                        <td>
                            <strong>Edad: </strong><?=calculaedad($row['fecha_nacimiento']);?>
                        </td>

                        <tr>
                            <td colspan="3">
                                <strong>Centro al que ingreso: </strong><?=$row['nombre_centro']?>
                            </td>
                            <td>
                                <strong>Motivo de ingreso: </strong><?=$row['motivo']?>
                            </td>
                            <td>
                                <strong>Bajo cuidado de Procuraduría: </strong><?=$row['cuidado_procu']?>
                            </td>
                        </tr>

                    </table>
                </div>

                <div class="row uniform">
                    <div class="12u" style="padding-top: 0px;">
                        <h2>Fecha de Ingreso: <?php echo $row['fecha_registro']; ?></h2>
                    </div>
                    <div class="12u" style="padding-top: 0px;">
                        <h2>Responsable del registro: <?php echo responsable($row['id_responsable']); ?></h2>
                    </div>
                </div>
                <br>
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




        <!-- Scripts -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/skel.min.js"></script>
        <script src="assets/js/util.js"></script>
        <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
        <script src="assets/js/main.js"></script>

        <script>
        $('#editarAsignacion').click(function() {

            var idnnaCentro = '<?=$idNnaCentro?>';
            var idnna = '<?=$idnna?>';
            var mensaje = confirm("¿El estado del NNA cambiara a asignado?");
            //Detectamos si el usuario acepto el mensaje
            if (mensaje) {

                $.ajax({
                    type: 'POST',
                    url: 'editar_asignar.php',
                    data: {
                        idnna: idnna,
                    },
                    datatype: 'json',
                    success: function(response) {
                        var jsonData = JSON.parse(response);
                        if (jsonData.success == 1) {
                            alert("" + jsonData.mensaje);
                            window.location.href = "nna_susceptibles_adopcion.php";
                        } else {
                            alert("" + jsonData.mensaje);
                        }
                    }
                })
            } else {

            }
        })
        </script>
</body>

</html>