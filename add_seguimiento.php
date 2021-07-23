<?php

include 'conexion.php';

if (isset($_POST['idSolicitud'])) {
    $idSolicitud = $_POST['idSolicitud'];
    $noSeguimiento = $_POST['noSeguimiento'];
    $fecha = $_POST['fecha'];
    $responsable = $_POST['responsable'];
    $observaciones = $_POST['observaciones'];

    $query = "INSERT INTO seguimiento_adop (id_solicitud, no_seguimiento, fecha, responsable, observaciones) VALUES ($idSolicitud, $noSeguimiento, '$fecha', '$responsable', '$observaciones')";
    $result = mysqli_query($mysqli, $query);
    if (!$result) {
        die("Ha ocurrido un error");
    }
    echo "Registro guardado correctamente";
}