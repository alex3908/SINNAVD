<?php
ob_start();

session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}

$idDEPTO = $_SESSION['id'];

$query = "SELECT nna_centros.id AS id_nna_centros,nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, centros.nombre
AS cas, date_format(nna_centros.fecha_ing, '%d/%m/%Y') AS fecha_ing, nna_centros.motivo
FROM nna_centros, centros, nna
WHERE nna_centros.id_centro=centros.id
AND nna_centros.id_nna=nna.id AND centros.nombre REGEXP 'CASA CUNA|CASA DEL NIÑO|CASA DE LA NIÑA'";

$nna_centros = $mysqli->query($query);
$rowing = $nna_centros->num_rows;
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
                <div>
                    <input type="button" onclick="location='nna_susceptibles_adopcion.php'" name="regresar"
                        value="Regresar" class="small">
                </div>
                <br>

                <div style="font-size: 25px;">
                    <b>Lista de NNA en Centros de Asistencía Social.</b>
                </div>

                <div style="display: block;">
                    <div style="width: fit-content; display: block; margin: 2em 0 0 auto;">
                        <div style="display: inline-block; ">
                            <div style="display: inline-block; font-size: 16px;">
                                <b>Buscar:</b>

                            </div>
                            <div style="display: inline-block; ">
                                <input type="search" id="search" style="text-transform:uppercase; width: 300px;" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-wrapper" style="padding-top: 20px;">


                    <div class="box">
                        <table id="mytable" class="alt">
                            <thead>
                                <tr>
                                    <th>Folio</th>
                                    <th>Nombre</th>
                                    <th>Centro</th>
                                    <th>Fecha de Ingreso</th>
                                    <th>Motivo</th>
                                </tr>
                            </thead>
                            <tbody>


                                <?php while ($row = $nna_centros->fetch_assoc()) {?>
                                <tr class="td-hv"
                                    onclick="window.location.href='perfil_nna_centros.php?id=<?php echo $row['id_nna_centros'] ?>'">
                                    <td><?php echo $row['folio']; ?></td>
                                    <td><?php echo $row['nombre'] . ' ' . $row['apellido_p'] . ' ' . $row['apellido_m']; ?>
                                    </td>
                                    <td><?php echo $row['cas']; ?></td>
                                    <td><?php echo $row['fecha_ing']; ?></td>
                                    <td><?php echo $row['motivo']; ?></td>
                                </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <br>

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
        // Write on keyup event of keyword input element
        $(document).ready(function() {
            $("#search").keyup(function() {
                _this = this;
                // Show only matching TR, hide rest of them
                $.each($("#mytable tbody tr"), function() {
                    if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -
                        1)
                        $(this).hide();
                    else
                        $(this).show();
                });
            });
        });
        </script>

</body>

</html>