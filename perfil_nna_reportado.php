<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
    }
    $idDEPTO = $_SESSION['id'];
    $nnaId=($_GET['idNna']);
    $idPosibleCaso = $_GET['id'];
    date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
    $qNna="SELECT id_posible_caso, nombre, apellido_p, apellido_m, sexo.id as sexId, sexo.sexo, fecha_nacimiento as fecha_nac, 
    lugar_nacimiento, lugar_registro, edad, fecha_actualizacion
	from nna_reportados left join sexo on nna_reportados.sexo=sexo.id
	where nna_reportados.id='$nnaId'";
    $rNna=$mysqli->query($qNna);
    while ($row=$rNna->fetch_assoc()) {
        $nombre=$row['nombre'];
        $apellido_p=$row['apellido_p'];
        $apellido_m=$row['apellido_m'];
        $sexId=$row['sexId'];
        $sexo=$row['sexo'];
        $fecha_nac=$row['fecha_nac'];
        $lugar_nac=$row['lugar_nacimiento'];
        $edad=$row['edad'];
        $lugar_reg=$row['lugar_registro'];
        $folio=$row['id_posible_caso'];
        $fechaAct=$row['fecha_actualizacion'];
    }

    if(!empty($_POST))
	{	
        $fecha= date("Y-m-d H:i:s", time());
      
        $nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);
        $apellido_p = mysqli_real_escape_string($mysqli,$_POST['apellido_p']);
        $apellido_m = mysqli_real_escape_string($mysqli,$_POST['apellido_m']);
        $edad = mysqli_real_escape_string($mysqli,$_POST['edad']);
        $sexo = $_POST['sexo'];
        $fecha_nacimiento = mysqli_real_escape_string($mysqli,$_POST['fecha_nacimiento']);
        if($fecha_nacimiento==""){
            $fecha_nacimiento="1900-01-01";
        }
		$lugar_nacimiento = mysqli_real_escape_string($mysqli,$_POST['lugar_nacimiento']);
		$lugar_registro = mysqli_real_escape_string($mysqli,$_POST['lugar_registro']);
		$error = '';			
			$sqlNino = "UPDATE nna_reportados set nombre='$nombre', apellido_p='$apellido_p', 
            apellido_m='$apellido_m', sexo='$sexo', edad='$edad', fecha_nacimiento='$fecha_nacimiento',
            lugar_nacimiento='$lugar_nacimiento', lugar_registro='$lugar_registro', fecha_actualizacion='$fecha' where id='$nnaId'";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			header("Location: reg_nna_reportados.php?idPosibleCaso=$idPosibleCaso");
			else
            $error = "Error al Registrar: ".$sqlNino;
       
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Editar NNA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>		
	</head>
	<body>		
		<!-- Wrapper -->
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
                    <h3>Editar al NNA:</h3>
                   	<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                       <div class="box" >
                            <div class="row uniform">
                                <div class="4u 12u$(xsmall)">Nombre (s):
                                    <input id="nombre" name="nombre" type="text" value="<?php echo $nombre?>" maxlength="50" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Apellido paterno:
                                    <input id="apellido_p" name="apellido_p" type="text" value="<?php echo $apellido_p?>" maxlength="50">
                                </div>
                                <div class="3u 12u$(xsmall)">Apellido materno:
                                    <input id="apellido_m" name="apellido_m" type="text" value="<?php echo $apellido_m?>" maxlength="50">
                                </div>
                                <div class="2u 12u$(xsmall)">Sexo
			                        <div class="select-wrapper">
			                            <select id="sexo" name="sexo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required >
					                        <option value="<?php echo $sexId?>"><?php echo $sexo?></option>
                                            <?php                                            
	                                        $sexo="SELECT id, sexo FROM sexo";
	                                        $resu=$mysqli->query($sexo);
                                            while($rowS = $resu->fetch_assoc()){ ?>
					                    	    <option value="<?php echo $rowS['id']; ?>"><?php echo $rowS['sexo']; ?></option>
					                        <?php }?>
			                            </select>
			                        </div>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="3u 12u$(xsmall)">Fecha de nacimiento:
                                    <input id="fecha_nacimiento" name="fecha_nacimiento" value="<?php if($fecha_nac=="1900-01-01") echo ""; else echo $fecha_nac?>" type="date"  >
                                </div>
                                <div class="3u 12u$(xsmall)">Lugar de nacimiento:
                                    <input id="lugar_nacimiento" name="lugar_nacimiento"  value="<?php echo $lugar_nac?>" type="text" maxlength="50">
                                </div>
                                <div class="3u 12u$(xsmall)">Lugar de registro:
                                    <input id="lugar_registro" name="lugar_registro" value="<?php echo $lugar_reg?>" type="text" maxlength="50">
                                </div>
                                <div class="3u 12u$(xsmall)">Edad:
                                    <input id="edad" name="edad" type="text" value="<?php echo $edad?>" maxlength="20">
                                </div>
                            </div><br>
                        </div>
                        <div class="row uniform">
                            <div class="6u 12u$(xsmall)">
                                <input class="button special fit" name="aceptar" id="aceptar" type="submit" value="Aceptar">	
                            </div>
                            <div class="6u 12u$(xsmall)">
                                <input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='reg_nna_reportados.php?idPosibleCaso=<?php echo $folio?>'" >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>

    
