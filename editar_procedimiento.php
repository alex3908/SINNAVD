<?php

include 'conexion.php';

if (isset($_POST['abogadoResp'])) {

    $id = $_POST['id'];
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

    $query = "UPDATE procedimiento_juridico SET abogado_resp = '$abogadoResp', promoventes = '$promoventes', cosentimiento = '$consentimiento', juzgado = '$juzgado', numero_expediente = '$numExpediente', fecha_inicial = '$fechaInicial', fecha_sentencia = '$fechaSentencia', fecha_ejecucion = '$fechaEjecucion' WHERE (id = $id)";
    $result = mysqli_query($mysqli, $query);
    if (!$result) {
        die("Ha ocurrido un error");
    }
    echo "Registro guardado correctamente";
}
mysqli_close($mysqli);