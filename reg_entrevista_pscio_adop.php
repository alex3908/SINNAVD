<?php
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
$zonahoraria = date_default_timezone_get();
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idDEPTO = $_SESSION['id'];
$idSol = filter_input(INPUT_POST, 'idSolicitud');
$fecha = date('Y-m-d H:i:s', time());
$fechaEntre = filter_input(INPUT_POST, 'fecha');
$idPerfil = filter_input(INPUT_POST, 'id_perfil');

$responsable = "SELECT responsable from departamentos where id=$idDEPTO";
$responsable = $mysqli->query($responsable);
$responsable = implode($responsable->fetch_assoc());

$registro = "INSERT INTO entrevistas_pscio_adop (id_solicitud, fecha_entrevista, id_responsable, fecha_registro, id_perfil) values ($idSol, '$fechaEntre', $idDEPTO, '$fecha', $idPerfil)";
$registro = $mysqli->query($registro);
if ($registro) {
    $fecha = date('d/m/Y H:i:s', time());
    echo json_encode(array('success' => 1, 'responsable' => $responsable, 'fecha_reg' => $fecha, 'fechaEntrevista' => $fechaEntre));
} else {
    echo json_encode(array('success' => 0));
}