<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli("localhost:3308", "root", "111", "procuprueba1"); //servidor, usuario de base de datos,  contraseña del usuario, nombre de base de datos, ("172.16.1.37","root","b11a4e7f62","procu")
$acentos = $mysqli->query("SET NAMES 'utf8'");
if (mysqli_connect_errno()) {
    echo 'Conexion Fallida : ', mysqli_connect_error();
    exit();
}