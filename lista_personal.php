<?php
ob_start();
session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}

$idDEPTO = $_SESSION['id'];
$sql = "SELECT id, personal FROM personal WHERE id= '$idDEPTO'";
$result = $mysqli->query($sql);
?>
<!DOCTYPE HTML>

<html>

<head>
    <title>Lista</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="shortcut icon" href="images/favicon.png" type="image/png" />
</head>

<body>

    <!-- Wrapper -->
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

                <table class="alt">
                    <thead>
                        <tr>
                            <td>
                                <h1>Personal</h1>
                            </td>
                            <td><?php if ($_SESSION['departamento'] == 16) {?>
                                <input type="button" class="button special" onclick="location='registro_personal.php'"
                                    value="ALTA"><?php }?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <form id="buscador" name="buscador" method="post"
                                    action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
                                    <input type="search" name="palabra" id="query" placeholder="Search" />
                                </form>
                            </td>
                        </tr>
                    </thead>
                </table>

                <table class="alt">
                    <thead>
                        <tr>
                            <td><b>ID</b></td>
                            <td><b>Nombre</b></td>
                            <td><b>Departamento</b></td>
                            <td><b>Cargo</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
@$buscar = $_POST["palabra"];

$query = "SELECT departamentos.id, departamentos.responsable, depto.departamento, personal.personal FROM departamentos, depto, personal WHERE depto.id=departamentos.id_depto AND personal.id=departamentos.id_personal AND (departamentos.responsable like '%$buscar%' OR depto.departamento like '%$buscar%' OR personal.personal like '%$buscar%') and departamentos.id!='0' limit 20";

$resultado = $mysqli->query($query);

while ($row = $resultado->fetch_assoc()) {?>

                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><a
                                    href="perfil_personal.php?id=<?php echo $row['id']; ?>"><?php echo $row['responsable']; ?></a>
                            </td>
                            <td><?php echo $row['departamento']; ?></td>
                            <td><?php echo $row['personal']; ?></td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>




                <?php if (@$bandera) {
    header("Location: welcome.php");

    ?> <?php } else {?>

                <div style="font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : ''; ?>
                </div>

                <?php }?>

            </div>
        </div>
        <!-- Sidebar -->
        <div id="sidebar">
            <div class="inner">
                <nav id="menu">
                    <header class="major">
                        <h2>Menú</h2>
                    </header>
                    <ul>
                        <li><a href="welcome.php">Inicio</a></li>
                        <li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
							</nav>
							<section>
								<header class=" major">
                                <h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
                                </header>
                                <p></p>
                                <ul class="contact">
                                    <li class="fa-envelope-o"><a href="#">laura.ramirez@hidalgo.gob.mx</a></li>
                                    <li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
                                    <li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
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
    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script src="assets/js/main.js"></script>

</body>

</html>