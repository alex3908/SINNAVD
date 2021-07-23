<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idSolicitud = filter_input(INPUT_POST, 'idSolicitud');
$band = false;
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql1 = "SELECT e.id, e.id_solicitud, e.fecha_entrevista, e.fecha_registro, e.band, a.entrevista_ini, a.entrevista_fin
FROM entrevistas_ts_adop e
RIGHT JOIN entrevistas_rel_ts a on e.id_solicitud = a.id_solicitud
WHERE e.id_solicitud = $idSolicitud";

$result = mysqli_query($mysqli, $sql1);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (isset($row['band']) and $row['band'] == 0) {
            $band = true;
        }
    }
} else {
    $band = true;
}

if ($band == true) {
    echo json_encode(array('success' => 0, 'mensaje' => 'No se pudo finalizar la evaluación, es posible que una o mas entrevistas no se hayan cerrado correctamente.'));
} else {
    $fechaEntrega = date('Y-m-d H:i:s', time());
    $sql = "UPDATE entrevistas_rel_ts SET entregado = '1', fecha_entrega = '$fechaEntrega' WHERE (id_solicitud = $idSolicitud)";
    $result2 = mysqli_query($mysqli, $sql);

    if ($result2) {
        echo json_encode(array('success' => 1, 'mensaje' => 'La evaluación se finalizo con exito'));
    } else {
        echo json_encode(array('success' => 0, 'mensaje' => 'Ha ocurrido un error'));
    }
}
$mysqli->close();