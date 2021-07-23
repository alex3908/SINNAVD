<?php

include 'conexion.php';

$query = "SELECT id,abogado_resp, promoventes, cosentimiento, juzgado, numero_expediente, date_format(fecha_inicial, '%d/%m/%Y') as fecha_inicial, date_format(fecha_sentencia, '%d/%m/%Y') as fecha_sentencia, date_format(fecha_ejecucion, '%d/%m/%Y') as fecha_ejecucion FROM procedimiento_juridico WHERE activo = 1";
$result = mysqli_query($mysqli, $query);

if (!$result) {
    die('Ha ocurrido un error' . mysqli_error($mysqli));
}

$json = array();
while ($row = mysqli_fetch_array($result)) {
    $json[] = array(
        'id' => $row['id'],
        'responsable' => $row['abogado_resp'],
        'promoventes' => $row['promoventes'],
        'consentimiento' => $row['cosentimiento'],
        'juzgado' => $row['juzgado'],
        'numExpediente' => $row['numero_expediente'],
        'fechaInicial' => $row['fecha_inicial'],
        'fechaSentencia' => $row['fecha_sentencia'],
        'fechaEjecucion' => $row['fecha_ejecucion'],
    );
}

$jsonstring = json_encode($json);
echo $jsonstring;