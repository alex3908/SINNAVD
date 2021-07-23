<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
    }
    $idDEPTO = $_SESSION['id'];
    $idPosibleCaso = $_GET['idPosibleCaso'];
    date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
    $padreCovid = 0;
    $madreCovid = 0;
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
        $padresCovid = $_POST['padresCovid'];
        if($padresCovid==1 or $padresCovid==3)
            $padreCovid = 1;
        if($padresCovid==2 or $padresCovid==3)
            $madreCovid=1;
		$error = '';			
			$sqlNino = "INSERT INTO nna_reportados (id_posible_caso, nombre, apellido_p, apellido_m, sexo, edad, fecha_nacimiento, 
            lugar_nacimiento, lugar_registro, fecha_registro, responsable_registro, fecha_actualizacion, padre_fallecido_covid, madre_fallecida_covid)
            values ('$idPosibleCaso','$nombre','$apellido_p','$apellido_m', '$sexo', '$edad', '$fecha_nacimiento','$lugar_nacimiento', '$lugar_registro','$fecha', '$idDEPTO', '$fecha', $padreCovid, $madreCovid)";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
			else
            $error = "Error al Registrar: ".$sqlNino;
        
	}
?>
<!DOCTYPE HTML>
<html >
	<head lang="es-ES">
		<title>Agregar NNA</title>
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
                    <h3>Por favor, llene los campos con los que cuente:</h3>
                   	<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                       <div class="box" >
                            <div class="row uniform">
                                <div class="4u 12u$(xsmall)">Nombre (s):
                                    <input id="nombre" name="nombre" type="text" value="" required style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="3u 12u$(xsmall)">Primer apellido:
                                    <input id="apellido_p" name="apellido_p" type="text" value="" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="3u 12u$(xsmall)">Segundo apellido:
                                    <input id="apellido_m" name="apellido_m" type="text" value="" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="2u 12u$(xsmall)">Sexo
			                        <div class="select-wrapper">
			                            <select id="sexo" name="sexo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required >
					                        <option value="">--Seleccione--</option>
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
                                    <input id="fecha_nacimiento" name="fecha_nacimiento" value="" type="date"  >
                                </div>
                                <div class="3u 12u$(xsmall)">Lugar de nacimiento:
                                    <input id="lugar_nacimiento" name="lugar_nacimiento"  value="" type="text" maxlength="50" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="3u 12u$(xsmall)">Lugar de registro:
                                    <input id="lugar_registro" name="lugar_registro" value="" type="text" maxlength="50" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="3u 12u$(xsmall)">Edad:
                                    <input id="edad" name="edad" type="text" value="" maxlength="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                            </div><br>
                            <div class="row uniform">
                                <div class="3u 12u$(xsmall">
                                    Fallecido por COVID-19:
                                </div>
                                <div class="3u 12u$(xsmall)">
                                    <input type="radio" id="padreCovid" value="1" name="padresCovid">
                                    <label for="padreCovid">Padre</label>
                                </div>
                                <div class="3u 12u$(xsmall)">
                                    <input type="radio" id="madreCovid" value="2" name="padresCovid">
                                    <label for="madreCovid">Madre</label>
                                </div>
                                 <div class="3u 12u$(xsmall)">
                                    <input type="radio" id="ambosCovid" value="3" name="padresCovid">
                                    <label for="ambosCovid">Ambos</label>
                                </div>
                            </div><br>
                        </div>
                        <div class="row uniform">
                            <div class="6u 12u$(xsmall)">
                                <input class="button special fit" name="aceptar" id="aceptar" type="submit" value="Aceptar">	
                            </div>
                            <div class="6u 12u$(xsmall)">
                                <input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?php echo $idPosibleCaso?>'" >
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

    
