<?php 
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
$zonahoraria = date_default_timezone_get();
if(!isset($_SESSION["id"])){
    header("Location: index.php");
}
$idDEPTO = $_SESSION['id'];
$idReporte=$_GET['idReporte'];
$idPC=$_GET['idPC'];
$idNna=$_GET['idNna'];
$name=$_SESSION['name'];

    //obtener datos del ws
    $fecha= date("Y-m-d H:i:s", time());
$nombre=$name["nombre"]; 
$apPaterno=$name["apellido_p"]; 
$apMaterno=$name["apellido_m"];        
$fecha_nacimiento=$name["fecha_nacimiento"]; 
$edad=$name["edad"]; 
$lugar_nac=$name["lugar_nacimiento"]; 
$lugar_reg=$name["lugar_registro"]; 
$idName= $name["idReporteName"];
$numControl=$name["numControl"];
$numReporte=$name["numReporte"];
$fechaRegName=$name["fechaRegName"];
$nombreCaso= $name["nombreCaso"];
$estado_coespo=$name["estado_coespo"];

    //obtiene los datos ya ingresados 
    $qNna="SELECT id_posible_caso, nombre, apellido_p, apellido_m, sexo, fecha_nacimiento as fecha_nac, 
    lugar_nacimiento, lugar_registro, edad, fecha_actualizacion
    from nna_reportados 
    where nna_reportados.id='$idNna'";
    $rNna=$mysqli->query($qNna);
    while ($row=$rNna->fetch_assoc()) {
        $nombreAnt=$row['nombre'];
        $apellido_pAnt=$row['apellido_p'];
        $apellido_mAnt=$row['apellido_m'];
        $sexoAnt=$row['sexo'];
        $fecha_nacAnt=$row['fecha_nac'];
        $lugar_nacAnt=$row['lugar_nacimiento'];
        $edadAnt=$row['edad'];
        $lugar_regAnt=$row['lugar_registro'];
        $fechaAct=$row['fecha_actualizacion'];
    }

    //crea historico con los datos ya ingresado
    $qhistorialNna="INSERT INTO historico_nna_reportados (id_nna, nombre, apellido_p, apellido_m, sexo, edad, fecha_nacimiento, 
    lugar_nacimiento, lugar_registro, fecha_registro, responsable_registro) values ('$idNna', '$nombreAnt', '$apellido_pAnt', 
    '$apellido_mAnt', '$sexoAnt', '$edadAnt', '$fecha_nacAnt', '$lugar_nacAnt', '$lugar_regAnt', '$fechaAct', '$idDEPTO')";
    $rhistorialNna=$mysqli->query($qhistorialNna);
    if($rhistorialNna){ //si se crea correctamente procede a actulizar la tabla nna_reportados
        $qRegistrar="UPDATE nna_reportados SET id_posible_caso='$idPC', nombre='$nombre', apellido_p='$apPaterno', apellido_m='$apMaterno', 
        edad='$edad', fecha_nacimiento='$fecha_nacimiento', lugar_nacimiento='$lugar_nac', lugar_registro='$lugar_reg', 
        fecha_actualizacion='$fecha' where id='$idNna'";
        $rRegistrar=$mysqli->query($qRegistrar);
      
        if($rRegistrar){ //si se actuliza adecuadamente procede a relacionarlo en names con el registro que ya habia 
            $qrelacion="INSERT INTO `relacion_names`(id_nna_reportado, `id_name`, `num_control_coespo`, `numero_reporte_coespo`,
          `id_reporte_sinnavd`, `fecha_registro`, `id_persona_reg`, `fecha_registro_si_names`, nombre_caso_coespo, estado_coespo) 
          VALUES ('$idNna', '$idName', '$numControl', '$numReporte', '$idReporte', '$fecha', '$idDEPTO', '$fechaRegName', '$nombreCaso', '$estado_coespo')";
            $rrealacion=$mysqli->query($qrelacion);
            if($rrealacion)//Si es correcto direcciona al posible caso 
                header("Location: perfil_posible_caso.php?idPosibleCaso=$idPC"); 
            else echo "Error: ".$qrelacion;
        } 
        else echo "Error: ".$qRegistrar;

    } 
    else echo "Error: ".$qhistorialNna;
?>