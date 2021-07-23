<?php

function editProcedimiento()
{
    include 'conexion.php';
    if (isset($_POST["var"])) {

        $query = "SELECT id,abogado_resp, promoventes, cosentimiento, juzgado, numero_expediente, date_format(fecha_inicial, '%d/%m/%Y') as fecha_inicial, date_format(fecha_sentencia, '%d/%m/%Y') as fecha_sentencia, date_format(fecha_ejecucion, '%d/%m/%Y') as fecha_ejecucion FROM procedimiento_juridico WHERE id = 15 and activo = 1";
        $result = mysqli_query($mysqli, $query);

        if (!$result) {
            die('Ha ocurrido un error' . mysqli_error($mysqli));
        }

        while ($row = mysqli_fetch_array($result)) {

            $id = $row['id'];
            $responsable = $row['abogado_resp'];
            $promoventes = $row['promoventes'];
            $consentimiento = $row['cosentimiento'];
            $juzgado = $row['juzgado'];
            $numExpediente = $row['numero_expediente'];
            $fechaInicial = $row['fecha_inicial'];
            $fechaSentencia = $row['fecha_sentencia'];
            $fechaEjecucion = $row['fecha_ejecucion'];

        }
    } else {

        $responsable = "";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procedimiento de adopción</title>
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="shortcut icon" href="images/favicon.png" type="image/png" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">

</head>

<body>

    <div id="wrapper">
        <!-- Main -->
        <div id="main">
            <div class="inner">
                <br>
                <br>
                <div class="box alt" align="center">
                    <div class="row 10% uniform">
                        <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
                        <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
                        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
                    </div>
                </div>
                <br>

                <div class="overlay2" id="overlay2">
                    <div class="popup2" id="popup2">

                        <div class="box">
                            <form id="juicios-form-edit" method="post">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="overlay" id="overlay">
                    <div class="popup" id="popup">

                        <div class="box">
                            <form id="juicios-form" method="post">
                                <div class="row uniform">
                                    <div class="4u">
                                        <label>Abogado responsable</label>
                                        <input id="txtResponsable" name="txtResponsable" type="text" maxlength="50"
                                            value="" style="text-transform:uppercase;"
                                            onkeyup="this.value=this.value.toUpperCase();">
                                    </div>
                                    <div class="5u">
                                        <label>Promoventes</label>
                                        <textarea name="txtPromoventes" id="txtPromoventes" rows="2"
                                            style="text-transform:uppercase;"
                                            onkeyup="this.value=this.value.toUpperCase();"></textarea>
                                    </div>
                                    <div class="3u">
                                        <label>Adopción con consentimiento</label>
                                        <input id="txtConsentimiento" name="txtConsentimiento" type="text"
                                            maxlength="50" value="" style="text-transform:uppercase;"
                                            onkeyup="this.value=this.value.toUpperCase();">
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="3u">
                                        <label>Juzgado</label>
                                        <input id="txtJuzgado" name="txtJuzgado" type="text" value="">
                                    </div>
                                    <div class="2u">
                                        <label>No. de expediente</label>
                                        <input id="txtExpediente" name="txtExpediente" type="text" value=""
                                            style="text-transform:uppercase;"
                                            onkeyup="this.value=this.value.toUpperCase();">
                                    </div>
                                </div>
                                <div class="row uniform">

                                    <div class="3u">
                                        <label>Fecha de escrito inicial</label>
                                        <input id="txtFechainicial" name="txtFechainicial" type="date">
                                    </div>
                                    <div class="3u">
                                        <label>Fecha de sentencía</label>
                                        <input id="txtFechasentencia" name="txtFechasentencia" type="date">
                                    </div>
                                    <div class="3u">
                                        <label>Fecha ejecución de sentencía</label>
                                        <input id="txtFechaejecucion" name="txtFechaejecucion" type="date">
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="row uniform">
                                    <div class="3u">
                                    </div>
                                    <div class="3u">
                                    </div>
                                    <div class="3u">
                                        <input type="button" id="btn-cerrar-popup" class="button fit" value="Cancelar">
                                    </div>
                                    <div class="3u">
                                        <input type="submit" class="button special fit" value="Guardar">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div style="display: inline-block; width: 100%; margin-top: 20px;">

                    <input type="button" class="button small" value="Volver al inicio" onclick="location='welcome.php'">
                    <button id="btn-abrir-popup" style="float: right;"
                        class="button small special btn-abrir-popup">Nuevo</button>
                </div>


                <div class="box" style="margin-top: 10px;">
                    <div class="table-wrapper">
                        <form id="juicios-form-eliminar" method="post">
                            <table class="alt">
                                <thead>
                                    <tr>
                                        <th>Abogado responsable</th>
                                        <th>Promoventes</th>
                                        <th>Adopción con consentimiento</th>
                                        <th>Juzgado y no. de expediente</th>
                                        <th>Fecha de escrito inicial</th>
                                        <th>Fecha de sentencía</th>
                                        <th>Fecha ejecución de sentencía</th>
                                    </tr>

                                </thead>
                                <tbody id="procedimientos">

                                </tbody>
                            </table>
                        </form>
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
        <script src="assets/js/app2.js"></script>
        <script>


        </script>
</body>

</html>