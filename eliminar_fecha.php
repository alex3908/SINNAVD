<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idSol = filter_input(INPUT_POST, 'idSolicitud');
$id = filter_input(INPUT_POST, 'id');
$area = filter_input(INPUT_POST, 'area');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
if ($area == "PS") {
    $delete = "DELETE FROM entrevistas_pscio_adop WHERE (id = $id);";
} else if ($area == "TS") {
    $delete = "DELETE FROM entrevistas_ts_adop WHERE (id = $id);";
}

if (mysqli_query($mysqli, $delete)) {
    echo json_encode(array('success' => 1));
} else {
    echo json_encode(array('success' => 0));
}
$mysqli->close();