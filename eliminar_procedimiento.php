<?php

include 'conexion.php';

if (isset($_POST['id'])) {

    $id = $_POST['id'];

    $query = "UPDATE procedimiento_juridico SET activo = 0 WHERE (id = $id)";
    $result = mysqli_query($mysqli, $query);
    if (!$result) {
        die("Ha ocurrido un error");
    }
    echo "Registro guardado correctamente";
}
mysqli_close($mysqli);