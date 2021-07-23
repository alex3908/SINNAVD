<?php
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idDEPTO = $_SESSION['id'];
$idSolicitud = $_GET['id'];
$estado = $_GET['estado'];
$fecha = date('Y-m-d H:i:s', time());
$query = "CALL actualizar_estado_sol_adop('$idSolicitud', '$estado', '$fecha', '$idDEPTO')";
$query = $mysqli->query($query);
header("Location: perfilSolAdop.php?id=$idSolicitud");