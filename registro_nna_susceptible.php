<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idDEPTO = $_SESSION['id'];
$pv = "SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
$epv = $mysqli->query($pv);
while ($row = $epv->fetch_assoc()) {
    $idDepartamento = $row['id_depto'];
    $idPersonal = $row['id_personal'];
}

$query = "SELECT * from estados where id!='0'";
$equery = $mysqli->query($query);
$countries = array();
while ($rowedo = $equery->fetch_object()) {$countries[] = $rowedo;}

if (!empty($_POST['aceptar'])) {
    $curp = trim($_POST['curp']);
    $nombre = trim($_POST['nombre']);
    $ape1 = trim($_POST['apellido_p']);
    $ape2 = trim($_POST['apellido_m']);
    $sexo = $_POST['sexo'];
    $fecNac = $_POST['fecha_nacimiento'];
    $edoNac = $_POST['country_id'];
    $persona = [
        "curp" => "$curp",
        "nombre" => "$nombre",
        "ape1" => "$ape1",
        "ape2" => "$ape2",
        "sexo" => "$sexo",
        "fecNac" => "$fecNac",
        "edoNac" => "$edoNac",
    ];
    $_SESSION['persona'] = $persona;
    header("Location: registro_nna_suscept.php");
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
                <form id="familia" name="familia" method="POST">
                    <div class="box">
                        <div class="row uniform">
                            <div class="1u 12u$(xsmall)">
                                CURP:
                            </div>
                            <div class="11u 12u$(xsmall)">
                                <input id="curp" pattern="[A-Z]{4}\d{6}[HM][A-Z]{2}[B-DF-HJ-NP-TV-Z]{3}[A-Z0-9][0-9]"
                                    name="curp" type="text" placeholder="(SI NO CUENTA CON EL DATO, DEJAR VACIO)"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                            </div>


                        </div>
                    </div>
                    <div class="box">
                        <div class="row unifor">
                            <div class="4u 12u$(xsmall)">Nombre (s):
                                <input id="nombre" name="nombre" type="text" placeholder="(REQUERIDO)"
                                    pattern="[A-ZÑ./-‘ ]{0,50}" style="text-transform:uppercase;"
                                    onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                            <div class="4u 12u$(xsmall)">Apellido paterno:
                                <input id="apellido_p" name="apellido_p" type="text" placeholder="(REQUERIDO)"
                                    pattern="[A-Z./-‘ ]{0,50}" style="text-transform:uppercase;"
                                    onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                            <div class="4u 12u$(xsmall)">Apellido materno:
                                <input id="apellido_m" name="apellido_m" type="text" pattern="[A-ZÑ./-‘ ]{0,50}"
                                    style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="4u 12u$(xsmall)">Sexo
                                <div class="select-wrapper">
                                    <select id="sexo" name="sexo" style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                        <option value="">--Seleccione--</option>
                                        <?php $sexo = "SELECT id, sexo FROM sexo";
$resu = $mysqli->query($sexo);
while ($rowS = $resu->fetch_assoc()) {?>
                                        <option value="<?php echo $rowS['id']; ?>"><?php echo $rowS['sexo']; ?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </div>

                            <div class="4u 12u$(xsmall)">Fecha de nacimiento:
                                <input id="fecha_nacimiento" name="fecha_nacimiento" type="date">
                            </div>

                            <div class="4u 12u$(xsmall)">Estado de nacimiento:
                                <div class="select-wrapper">
                                    <select id="country_id" class="form-control" name="country_id">
                                        <option value="">-- Seleccione --</option>
                                        <?php foreach ($countries as $c): ?>
                                        <option value="<?php echo $c->clave; ?>"><?php echo $c->estado; ?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>

                        </div><br>
                    </div>
                    <div class="row uniform">
                        <div class="6u 12u$(xsmall)">
                            <input class="button special fit" name="aceptar" id="aceptar" type="submit" value="Aceptar">
                        </div>
                        <div class="6u 12u$(xsmall)">
                            <input class="button fit" type="button" name="cancelar" value="Cancelar"
                                onclick="location='nna_susceptibles_adopcion.php'">
                        </div>
                    </div>
                </form>
                <script type="text/javascript">
                $(document).ready(function() {
                    $("#country_id").change(function() {
                        $.get("get_states.php", "country_id=" + $("#country_id").val(), function(data) {
                            $("#state_id").html(data);
                            console.log(data);
                        });
                    });

                    $("#state_id").change(function() {
                        $.get("get_cities.php", "state_id=" + $("#state_id").val(), function(data) {
                            $("#city_id").html(data);
                            console.log(data);
                        });
                    });
                });
                </script>
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
</body>

</html>