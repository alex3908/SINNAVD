<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}

$idDEPTO = $_SESSION['id'];

$idnna = $_GET['id'];

$pv = "SELECT id, id_depto, id_personal,responsable from departamentos where id='$idDEPTO'";
$epv = $mysqli->query($pv);
while ($row = $epv->fetch_assoc()) {
    $responsable = $row['responsable'];
    $idPersonal = $row['id_personal'];
    $idResponsable = $row['id'];
}

$nnaCentros = "SELECT nna_susceptible_adop.id, nna_adopcion.id as id_nna, nna_adopcion.folio,nna_adopcion.nombre,nna_adopcion.apellido_p,nna_adopcion.apellido_m,nna_adopcion.curp, date_format(nna_adopcion.fecha_nacimiento,'%d/%m/%Y') as fecha_nacimiento,nna_adopcion.no_acta,date_format(nna_adopcion.fecha_reg,'%d/%m/%Y') as fecha_reg,nna_adopcion.id_responsable, nna_adopcion.observaciones, nna_adopcion.persona_otorga, nna_susceptible_adop.asignado
FROM nna_susceptible_adop
JOIN nna_adopcion ON nna_susceptible_adop.folio = nna_adopcion.folio
WHERE nna_susceptible_adop.id=$idnna";
$nnaSusceptible = $mysqli->query($nnaCentros);

$sql = "SELECT nna_adopcion.fecha_nacimiento
FROM nna_susceptible_adop
JOIN nna_adopcion ON nna_susceptible_adop.folio = nna_adopcion.folio
WHERE nna_susceptible_adop.id=$idnna";
$fecha = $mysqli->query($sql);

$fechanacimiento = implode($fecha->fetch_assoc());

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

                <?php while ($row = $nnaSusceptible->fetch_assoc()) {?>
                <div style="display: inline-block; width: 100%; margin-bottom: 20px;">
                    <b
                        style="font-size: 45px;"><?php echo $row['nombre'] . ' ' . $row['apellido_p'] . ' ' . $row['apellido_m']; ?></b>
                    <input id="editarAsignacion" style="margin-right: 20px; float: right; margin-top:15px;"
                        type="button" class="button special" <?php if ($row['asignado'] == 1) {?>value="NNA Asignado"
                        disabled <?php } else {?> value="Asignar NNA" <?php }?>>
                </div>

                <div class="row uniform">
                    <div style="width: 100%;">
                        <table>
                            <tr>
                                <td colspan="6">
                                    <h3>Curp: <?php echo $row['curp']; ?></h3>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <h4 style="margin: 0px;">
                                        <b style="font-size: 17px;"> Folio: </b> <a href=""><?=$row['folio'];?></a>
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>No. Acta: </strong><?=$row['no_acta'];?>
                                </td>
                                <td colspan="4">
                                    <strong>Edad: </strong><?=calculaedad($fechanacimiento);?>
                                </td>
                                <td colspan="3">
                                    <strong>Responsable del registro: </strong><?php echo $responsable ?>
                                </td>
                            </tr>
                            <td colspan="2">
                                <strong>Fecha de registro: </strong><?php echo $row['fecha_reg']; ?>
                            </td>
                            <td colspan="3">
                                <strong>Persona(s) que otorgan: </strong><?php $nom = strtolower($row['persona_otorga']);
    echo ucwords($nom)?>
                            </td>
                            <td colspan="3">
                                <strong>Observaciones: </strong><?=$row['observaciones']?>
                            </td>
                            <tr>

                            </tr>

                        </table>
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
                            location.reload();
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