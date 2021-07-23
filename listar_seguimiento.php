<?php

include 'conexion.php';

$idSolicitud = $_GET['idSolicitud'];
$querySeg = "SELECT COUNT(*) as num_seguimientos FROM seguimiento_adop WHERE id_solicitud = $idSolicitud";
$resultSeg = mysqli_query($mysqli, $querySeg);

while ($row = $resultSeg->fetch_assoc()) {
    $numeroSeguimiento = $row['num_seguimientos'];
}

if (!$resultSeg) {
    die('Ha ocurrido un error' . mysqli_error($mysqli));
}

$query = "SELECT id,id_solicitud, no_seguimiento, date_format(fecha, '%d/%m/%Y') as fecha,responsable,observaciones FROM seguimiento_adop WHERE id_solicitud = $idSolicitud";
$result = mysqli_query($mysqli, $query);

if (!$result) {
    die('Ha ocurrido un error' . mysqli_error($mysqli));
}

$json = array();
while ($row = mysqli_fetch_array($result)) {
    $json[] = array(
        'id' => $row['id'],
        'idSolicitud' => $row['id_solicitud'],
        'noSeguimiento' => $row['no_seguimiento'],
        'fecha' => $row['fecha'],
        'responsable' => $row['responsable'],
        'observaciones' => $row['observaciones'],
        'totalSeguimientos' => $numeroSeguimiento,
    );
}

$jsonstring = json_encode($json);
echo $jsonstring;