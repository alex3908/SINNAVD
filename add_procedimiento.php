<?php

include 'conexion.php';

if (isset($_POST['abogadoResp'])) {

    $abogadoResp = $_POST['abogadoResp'];
    $promoventes = $_POST['promoventes'];
    $consentimiento = $_POST['consentimiento'];
    $juzgado = $_POST['juzgado'];
    $numExpediente = $_POST['numExpediente'];

    $fechaInicial = $_POST['fechaInicial'];
    $fechaSentencia = $_POST['fechaSentencia'];
    $fechaEjecucion = $_POST['fechaEjecucion'];

    if ($fechaInicial == "") {
        $fechaInicial = "1000-01-01";
    }
    if ($fechaSentencia == "") {
        $fechaSentencia = "1000-01-01";
    }
    if ($fechaEjecucion == "") {
        $fechaEjecucion = "1000-01-01";
    }

    $query = "INSERT INTO procedimiento_juridico (abogado_resp, promoventes, cosentimiento, juzgado, numero_expediente, fecha_inicial, fecha_sentencia, fecha_ejecucion) VALUES ('$abogadoResp', '$promoventes', '$consentimiento', '$juzgado', '$numExpediente', '$fechaInicial', '$fechaSentencia', '$fechaEjecucion')";
    $result = mysqli_query($mysqli, $query);
    if (!$result) {
        die("Ha ocurrido un error");
    }
    echo "Registro guardado correctamente";
}
mysqli_close($mysqli);