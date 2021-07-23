<?php 
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
$zonahoraria = date_default_timezone_get();
if(!isset($_SESSION["id"])){
    header("Location: welcome.php");
}
$idDEPTO = $_SESSION['id'];
$idReporte=$_GET['idReporte'];
$idPC=$_GET['idPC'];
$name=$_SESSION['name'];
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
$urlReporte ='https://name-pruebas.inftelapps.com/api/reportes';
$jsonReporte = file_get_contents($urlReporte);
$arrReporte = json_decode($jsonReporte);
$numReportes= count($arrReporte);  //obtiene el numero de reportes en el siname

for($i=0; $i<$numReportes; $i++) //recorre las posiciones del json para encontrar a cual le correponde al nna que se quiere vincular
{
    $idArr=$arrReporte[$i]->id;
    if($idName==$idArr){
        $num=$i; //obtiene la posicion del Json 
        $i=$numReportes;
    }
}
$qNna="SELECT nna_reportados.id, nombre, apellido_p, apellido_m, sexo.sexo, edad 
FROM nna_reportados inner join sexo on nna_reportados.sexo=sexo.id
where id_posible_caso='$idPC' and activo=1 and nna_reportados.sexo!=1";
$rNna=$mysqli->query($qNna);    

if(!empty($_POST['Registrar'])){
    $fecha= date("Y-m-d H:i:s", time());
    
    $qRegistrar="INSERT INTO nna_reportados (id_posible_caso, nombre, apellido_p, apellido_m, sexo, edad, fecha_nacimiento, 
    lugar_nacimiento, lugar_registro, fecha_registro, responsable_registro, fecha_actualizacion) 
    values ('$idPC', '$nombre', '$apPaterno', '$apMaterno', '2', '$edad', '$fecha_nacimiento', '$lugar_nac', '$lugar_reg', 
    '$fecha', '$idDEPTO', '$fecha')";
    $rRegistrar=$mysqli->query($qRegistrar);
    if($rRegistrar){
        $qidNna="SELECT max(id) from nna_reportados where id_posible_caso='$idPC'";
        $ridNna=$mysqli->query($qidNna);
        $AidNna=$ridNna->fetch_assoc();
        $idNna= implode($AidNna);
        $qrelacion="INSERT INTO `relacion_names`(id_nna_reportado, `id_name`, `num_control_coespo`, `numero_reporte_coespo`,
          `id_reporte_sinnavd`, `fecha_registro`, `id_persona_reg`, `fecha_registro_si_names`, nombre_caso_coespo, estado_coespo) 
          VALUES ('$idNna', '$idName', '$numControl', '$numReporte', '$idReporte', '$fecha', '$idDEPTO', '$fechaRegName', '$nombreCaso', '$estado_coespo')";
        $rrealacion=$mysqli->query($qrelacion);
        if($rrealacion){
            header("Location: perfil_posible_caso.php?idPosibleCaso=$idPC"); 
        }
        else echo "Error: ".$qrelacion."--".$qidNna;
    }
    else echo "Error: ".$qRegistrar;
}

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Registrar NNA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>		
	</head>
	<body>
        <div id="wrapper">
			<!-- Main -->
			<div id="main">
				<div class="inner">
					<br> <br> 
					<div class="box alt" align="center">
					    <div class="row 10% uniform">
					        <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					        <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
				        </div>
			        </div>
                    <h3>Añadir a la NAME:</h3>
                    <h4>Por favor, verifique que la NAME no se encuentre ya registrada, de ser así seleccionela.</h4>
                       <h5>NAME: <?= $nombre." ".$apPaterno." ".$apMaterno ?></h5>
                        <div class="box" >
                            <table>
                                <thead>
								    <tr>
									    <td>Nombre</td>
						    			<td>Apellido paterno</td>
							    		<td>Apellido Materno</td>
								    	<td>Edad</td>
									    <td>Sexo</td>
						    		</tr>
							    </thead>
                                <body>
                                    <?php while ($rowNna=$rNna->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $rowNna['nombre']; ?> </td>
                                            <td><?php echo $rowNna['apellido_p']; ?> </td>
                                            <td><?php echo $rowNna['apellido_m']; ?> </td>
                                            <td><?php echo $rowNna['edad']; ?> </td>
                                            <td><?php echo $rowNna['sexo']; ?> </td>
                                            <td><input class="button" name="relacionar" id="relacionar"  value="Seleccionar" onclick="location='actualizar_nna-names.php?idReporte=<?php echo $idReporte; ?>&idPC=<?php echo $idPC; ?>&idNna=<?php echo $rowNna['id']; ?>'"></td>
                                        </tr>
                                    <?php } ?>
                                </body>
                            </table>
                        </div>
                        <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                        <input class="button special fit" name="Registrar" type="submit" value="Registrar como nuevo NNA">
                    <form>

                </div>
            </div>
        </div>

        <script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>