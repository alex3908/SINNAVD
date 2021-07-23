<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idEstado = 0;
$folio = "";
$nombre = filter_input(INPUT_POST, 'nombre');
$apellidoP = filter_input(INPUT_POST, 'apellidoP');
$apellidoM = filter_input(INPUT_POST, 'apellidoM');
$curp = filter_input(INPUT_POST, 'curp');
$fechaNac = filter_input(INPUT_POST, 'fechaNac');
$sexo = filter_input(INPUT_POST, 'sexo');
$estado = filter_input(INPUT_POST, 'estado');
$noActa = filter_input(INPUT_POST, 'noActa');
$fechaReg = filter_input(INPUT_POST, 'fechaReg');
echo "fecha nac: " . $fechaNac;
$idResponsable = filter_input(INPUT_POST, 'idResponsable');
$personaOtorga = filter_input(INPUT_POST, 'personaOtorga');
$observaciones = filter_input(INPUT_POST, 'observaciones');
$lnom = substr($nombre, 0, 1);
$lap = substr($apellidoP, 0, 1);
$lam = substr($apellidoM, 0, 1);
$snum = 'SELECT terminacion from nfolio where id=1';
$esnum = $mysqli->query($snum);
while ($row = $esnum->fetch_assoc()) {
    $ter = $row['terminacion'];
}
$ter2 = $ter + 1;
$folio = $lnom . $lap . $lam . $ter2;
$contador = "UPDATE nfolio set terminacion='$ter2' where id=1";
mysqli_query($mysqli, $contador);

if ($nombre == "" || $folio == "") {
    echo 'No se pudo realizar la acción, asegurece de llenar los campos correctamente';
} else {
    if (!empty($estado)) {
        $estadoNac = "SELECT id from estados where estado='$estado'";
        $ridEstadoNac = $mysqli->query($estadoNac);
        while ($filaIdEdo = $ridEstadoNac->fetch_assoc()) {
            $idEstado = $filaIdEdo['id'];
        }
    } else {
        $idEstado = 0;
    }
    if ($sexo == 'HOMBRE' || $sexo == 1) {
        $sexo = 'H';
    } else if ($sexo == 'MUJER' || $sexo == 0) {
        $sexo = 'M';
    }
    $sqlInsertarNNA = "INSERT INTO nna_adopcion (folio, nombre, apellido_p, apellido_m, curp, fecha_nacimiento, sexo, id_estado_nacimiento, no_acta, fecha_reg, id_responsable, persona_otorga, observaciones)
    VALUES ('$folio', '$nombre', '$apellidoP', '$apellidoM', '$curp', '$fechaNac', '$sexo', $idEstado, '$noActa', '$fechaReg', $idResponsable, '$personaOtorga', '$observaciones')";
    $result = mysqli_query($mysqli, $sqlInsertarNNA);

    if ($result) {
        $sqlSelect = "SELECT folio FROM nna_adopcion";
        $result2 = mysqli_query($mysqli, $sqlSelect);
        $folionna = implode($result2->fetch_assoc());
        $sqlInsertar = "INSERT INTO nna_susceptible_adop (folio, fecha_registro, id_responsable)
        VALUES ('$folionna','$fechaReg',$idResponsable)";
        $result3 = mysqli_query($mysqli, $sqlInsertar);

        if ($result2 and $result3) {
            echo 'Se inserto correctamente';
        } else {
            echo 'No se pudo realizar la accion, intentelo de nuevo mas tarde';
        }

    } else {
        echo 'No se pudo realizar la accion, intentelo de nuevo mas tarde';
    }
    mysqli_close($mysqli);
}