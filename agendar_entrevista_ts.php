<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idSol = filter_input(INPUT_POST, 'idSolicitud');
$fechaReg = filter_input(INPUT_POST, 'fechaReg');
$fechaIni = filter_input(INPUT_POST, 'fechaEntrevista');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$datos = "SELECT id FROM entrevistas_rel_ts where id_solicitud = $idSol";
$result = mysqli_query($mysqli, $datos);

$query = "SELECT * FROM entrevistas_ts_adop where id_solicitud = $idSol order by id";
$query = mysqli_query($mysqli, $query);

while ($row = $query->fetch_assoc()) {
    $fecha_fin = $row['fecha_entrevista'];
}

if (!empty($result) and mysqli_num_rows($result) > 0) {
    $id = implode($result->fetch_assoc());
    $insert = "UPDATE entrevistas_rel_ts SET entrevista_fin = '$fecha_fin' WHERE (id = $id);";
    mysqli_query($mysqli, $insert);
} else {
    $insert = "INSERT INTO entrevistas_rel_ts(id_solicitud, entrevista_ini, entrevista_fin)
    VALUES ($idSol, STR_TO_DATE(REPLACE('$fechaIni','/','-') ,'%d-%m-%Y %H:%i:%s'),STR_TO_DATE(REPLACE('$fechaIni','/','-') ,'%d-%m-%Y %H:%i:%s'))";
    mysqli_query($mysqli, $insert);
}
$update = "UPDATE entrevistas_ts_adop SET band = 1 WHERE fecha_registro = (STR_TO_DATE(REPLACE('$fechaReg','/','-') ,'%d-%m-%Y %H:%i:%s')) and (id_solicitud = $idSol)";

if (mysqli_query($mysqli, $update)) {
    echo json_encode(array('success' => 1));
} else {
    echo json_encode(array('success' => 0));
}
$mysqli->close();