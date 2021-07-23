<?php

session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}

$idDEPTO = $_SESSION['id'];

$idnnaCentro = $_GET['id'];

$pv = "SELECT id, id_depto, id_personal from departamentos where id='$idDEPTO'";
$epv = $mysqli->query($pv);
while ($row = $epv->fetch_assoc()) {
    $idDepartamento = $row['id_depto'];
    $idPersonal = $row['id_personal'];
    $idResponsable = $row['id'];
}

$nnaCentros = "SELECT centros.id AS idC,nna.id AS id_nna, nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m,
centros.nombre AS nombre_centro, DATE_FORMAT(nna_centros.fecha_ing, '%d/%m/%Y') AS fecha_ingreso,nna_centros.id AS id_nna_centro, nna_centros.motivo, nna_centros.cuidado_procu,
nna_centros.nna_estado, nna_centros.nna_calle, nna_centros.nna_actaD, nna_centros.nna_curpD,
nna_centros.nna_consAD, nna_centros.nombreG, nna_centros.apellido_pG, nna_centros.apellido_mG,
nna_centros.parentescoG, nna_centros.tel1G, nna_centros.tel2G, nna_centros.correoG, nna_centros.estadoG,
nna_centros.calleG, nna_centros.nombreT, nna_centros.apellido_pT, nna_centros.apellido_mT,
nna_centros.parentescoT, nna_centros.tel1T, nna_centros.tel2T, nna_centros.correoT, nna_centros.situacionJ
from nna_centros
INNER JOIN nna ON nna_centros.id_nna = nna.id
INNER JOIN centros ON nna_centros.id_centro = centros.id
WHERE nna_centros.id=$idnnaCentro";

$query = "SELECT nna_centros.id AS id_nna_centros, centros.id AS id_centro,nna.folio
from nna_centros
INNER JOIN nna ON nna_centros.id_nna = nna.id
INNER JOIN centros ON nna_centros.id_centro = centros.id
WHERE nna_centros.id=$idnnaCentro";
$idnna = 0;
$nnaCentros = $mysqli->query($nnaCentros);
$query = $mysqli->query($query);

while ($row = $query->fetch_assoc()) {
    $folio = $row['folio'];
    $idCentro = $row['id_centro'];
}

if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql1 = "SELECT nna_susceptible_adop.id_nna_centro
FROM nna_susceptible_adop
WHERE id_nna_centro = $idnnaCentro";
$result = mysqli_query($mysqli, $sql1);

if (isset($_POST["submit"])) {

    // Check connection

    if (!empty($result) and mysqli_num_rows($result) > 0) {
        // Si es mayor a cero imprimimos que ya existe el usuario
        echo '<script type="text/javascript">
        alert("Ok");
        window.location.href="nna_cas.php";
        </script>';
    } else {
        $sql = "INSERT INTO nna_susceptible_adop (folio, id_nna_centro, id_centro, fecha_registro, id_responsable)
    VALUES ('$folio','$idnnaCentro','$idCentro',CURDATE(),$idResponsable)";

        if (mysqli_query($mysqli, $sql)) {
            echo '<script type="text/javascript">
        alert("Registro exitoso");
        window.location.href="nna_cas.php";
        </script>';
        } else {
            echo '<script type="text/javascript">
        alert("Se ha producido un error.' . mysqli_error($mysqli) . '");
        window.location.href="nna_cas.php";
        </script>';
        }
        $mysqli->close();
    }

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
                <?php while ($row = $nnaCentros->fetch_assoc()) {
    $idnna = $row['id_nna'];
    ?>

                <div class="row uniform">
                    <div style="font-size: 25px; padding-left: 20px;">
                        <b> <?php echo 'NNA: ' . $row['nombre'] . ' ' . $row['apellido_p'] . ' ' . $row['apellido_m']; ?>
                            <b>
                    </div>


                    <div>
                        <div style="display: inline-block; width: 100%;">
                            <strong class="txt-title">Centro al que ingreso:</strong>
                            <?php echo $row['nombre_centro']; ?>
                        </div>
                        <div style="display: inline-block; width: 100%;">
                            <strong class="txt-title">Motivo de
                                ingreso:</strong>
                            <b><?php echo $row['motivo']; ?></b>
                        </div>

                        <div style="display: inline-block; width: 100%;">
                            <strong class="txt-title">Bajo cuidado de
                                Procuraduría:</strong>
                            <b><?php echo $row['cuidado_procu']; ?></b>
                        </div>

                        <div style="display: inline-block; width: 100%;">
                            <strong class="txt-title">Fecha de
                                Ingreso:</strong>
                            <b><?php echo $row['fecha_ingreso']; ?></b>
                        </div>
                    </div>
                </div>
                <br>

                <div class="12u">
                    <div style="font-size: 20px;">
                        <strong>Documentos de identidad del NNA</strong>
                    </div>
                    <ul class="pagination">
                        <?php $ac = $row['nna_actaD'];
    $cu = $row['nna_curpD'];
    $co = $row['nna_consAD'];
    if ($ac == 'SI') {?>
                        <li><a class="page active">Acta de nacimiento</a></li>
                        <?php } else {}if ($cu == 'SI') {?>
                        <li><a class="page active">CURP</a></li>
                        <?php } else {}if ($co == 'SI') {?>
                        <li><a class="page active">Constancia de alumbramiento</a></li>
                        <?php }?>
                    </ul>
                </div>


                <br>
                <div class="row uniform">

                    <div class="4u 12u$(xsmall)">
                        <ul class="alt">
                            <div class="box"><strong>Ultimo domicilio del NNA antes del ingreso</strong>
                                <li><strong>Estado: </strong><?php echo $row['nna_estado']; ?> </li>
                                <li><strong>Calle: </strong><?php echo $row['nna_calle']; ?> </li><br>
                                <strong>Situación Juridica</strong>
                                <li><?php echo $row['situacionJ']; ?></li>
                            </div>
                        </ul>
                    </div>
                    <div class="4u 12u$(xsmall)">
                        <ul class="alt">
                            <div class="box"><strong>Persona que ejerce la guardia o custodia</strong>
                                <li><strong>Nombre:
                                    </strong><?php echo $row['nombreG'] . ' ' . $row['apellido_pG'] . ' ' . $row['apellido_mG']; ?>
                                </li>
                                <li><strong>Parentesco: </strong><?php echo $row['parentescoG']; ?> </li>
                                <li><strong>Teléfono 1: </strong><?php echo $row['tel1G']; ?> </li>
                                <li><strong>Teléfono 2: </strong><?php echo $row['tel2G']; ?> </li>
                                <li><strong>Correo: </strong><?php echo $row['correoG']; ?> </li>
                                <li><strong>Estado: </strong><?php echo $row['estadoG']; ?> </li>
                                <li><strong>Calle: </strong><?php echo $row['calleG']; ?> </li>
                            </div>
                        </ul>
                    </div>
                    <div class="4u 12u$(xsmall)">
                        <ul class="alt">
                            <div class="box"><strong>Persona que ejerce la tutela</strong>
                                <li><strong>Nombre:
                                    </strong><?php echo $row['nombreT'] . ' ' . $row['apellido_pT'] . ' ' . $row['apellido_mT']; ?>
                                </li>
                                <li><strong>Parentesco: </strong><?php echo $row['parentescoT']; ?> </li>
                                <li><strong>Teléfono 1: </strong><?php echo $row['tel1T']; ?> </li>
                                <li><strong>Teléfono 2: </strong><?php echo $row['tel2T']; ?> </li>
                                <li><strong>Correo: </strong><?php echo $row['correoT']; ?> </li>
                            </div>
                        </ul>
                    </div>
                </div>
                <?php }?>
                <div class="box">
                    <form action="" method="post">
                        <?php if (!empty($result) and mysqli_num_rows($result) > 0) {?>

                        <?php } else {?>

                        <input class="button special fit" name="submit" type="submit"
                            value="NNA Susceptible de adopción" onclick="location='nna_subsetibles_adopcion.php'">
                        <?php }?>

                        <input class="button fit" type="button" name="cancelar" value="Cancelar"
                            onclick="history.back()">

                    </form>
                </div>
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
</body>

</html>