<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idnna = filter_input(INPUT_POST, 'idnna');
// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE nna_susceptible_adop SET asignado = '1' WHERE (id = $idnna)";
$result2 = mysqli_query($mysqli, $sql);

if ($result2) {
    echo json_encode(array('success' => 1, 'mensaje' => 'NNA se asignó con exito'));
} else {
    echo json_encode(array('success' => 0, 'mensaje' => 'Ha ocurrido un error'));
}
$mysqli->close();