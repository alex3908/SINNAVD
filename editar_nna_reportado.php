<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
    }
    $idDEPTO = $_SESSION['id'];
    $nnaId=($_GET['id']);
    $idPosibleCaso = $_GET['idPosCaso'];
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
    $padres="SELECT padre_fallecido_covid, madre_fallecida_covid from nna_reportados where id=$nnaId";
    $padres = $mysqli->query($padres);
    while ($rowp= $padres->fetch_assoc()) {
        $padre= $rowp['padre_fallecido_covid'];
        $madre = $rowp['madre_fallecida_covid'];
    }
    if(!empty($_POST))
	{	
        $fecha= date("Y-m-d H:i:s", time());
        $qhistorialNna="INSERT INTO historico_nna_reportados (id_nna, nombre, apellido_p, apellido_m, sexo, edad, fecha_nacimiento, 
        lugar_nacimiento, lugar_registro, fecha_registro, responsable_registro) values ('$nnaId', '$nombre', '$apellido_p', '$apellido_m',
        '$sexId', '$edad', '$fecha_nac', '$lugar_nac', '$lugar_reg', '$fechaAct', '$idDEPTO')";
        $rhistoricoNna=$mysqli->query($qhistorialNna);
        if($rhistoricoNna){
        $nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);
        $apellido_p = mysqli_real_escape_string($mysqli,$_POST['apellido_p']);
        $apellido_m = mysqli_real_escape_string($mysqli,$_POST['apellido_m']);
        $edad = mysqli_real_escape_string($mysqli,$_POST['edad']);
        $sexo = $_POST['sexo'];
        $fecha_nacimiento = mysqli_real_escape_string($mysqli,$_POST['fecha_nacimiento']);
        if($fecha_nacimiento==""){
            $fecha_nacimiento="1900-01-01";
        }
        $padresCovid = $_POST['padresCovid'];
        $padres="SELECT padre_fallecido_covid, madre_fallecida_covid from nna_reportados where id=$nnaId";
        $padres = $mysqli->query($padres);
        while ($rowp= $padres->fetch_assoc()) {
            $padreCovid= $rowp['padre_fallecido_covid'];
            $madreCovid = $rowp['madre_fallecida_covid'];
        }
        if($padresCovid==1 or $padresCovid==3)
            $padreCovid = 1;
        if($padresCovid==2 or $padresCovid==3)
            $madreCovid=1;
		$lugar_nacimiento = mysqli_real_escape_string($mysqli,$_POST['lugar_nacimiento']);
		$lugar_registro = mysqli_real_escape_string($mysqli,$_POST['lugar_registro']);
		$error = '';			
			$sqlNino = "UPDATE nna_reportados set nombre='$nombre', apellido_p='$apellido_p', 
            apellido_m='$apellido_m', sexo='$sexo', edad='$edad', fecha_nacimiento='$fecha_nacimiento',
            lugar_nacimiento='$lugar_nacimiento', lugar_registro='$lugar_registro', fecha_actualizacion='$fecha', padre_fallecido_covid = '$padreCovid', madre_fallecida_covid = '$madreCovid' where id='$nnaId'";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
			else
            $error = "Error al Registrar: ".$sqlNino;
        } else {
            echo "Error 1: ".$qhistorialNna;
        }
	}
?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
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
                                    <input id="nombre" name="nombre" type="text" value="<?php echo $nombre?>" maxlength="50" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Primer apellido:
                                    <input id="apellido_p" name="apellido_p" type="text" value="<?php echo $apellido_p?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="50">
                                </div>
                                <div class="3u 12u$(xsmall)">Segundo apellido:
                                    <input id="apellido_m" name="apellido_m" type="text" value="<?php echo $apellido_m?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="50">
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
                                    <input id="fecha_nacimiento" name="fecha_nacimiento" value="<?php if($fecha_nac=="1900-01-01") echo ""; else  echo $fecha_nac?>" type="date"  >
                                </div>
                                <div class="3u 12u$(xsmall)">Lugar de nacimiento:
                                    <input id="lugar_nacimiento" name="lugar_nacimiento" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $lugar_nac?>" type="text" maxlength="50">
                                </div>
                                <div class="3u 12u$(xsmall)">Lugar de registro:
                                    <input id="lugar_registro" name="lugar_registro" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $lugar_reg?>" type="text" maxlength="50">
                                </div>
                                <div class="3u 12u$(xsmall)">Edad:
                                    <input id="edad" name="edad" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $edad?>" maxlength="20">
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="3u 12u$(xsmall">
                                    Fallecido por COVID-19:
                                </div>
                                <div class="3u 12u$(xsmall)">
                                    <input type="radio" id="padreCovid" value="1" name="padresCovid" <?php if($padre==1) { ?>checked="true" <?php } if($madre==1) { ?>disabled="true" <?php }?>>
                                    <label for="padreCovid">Padre</label>
                                </div>
                                <div class="3u 12u$(xsmall)">
                                    <input type="radio" id="madreCovid" value="2" name="padresCovid" <?php if($madre==1) { ?>checked="true" <?php } if($padre==1) { ?>disabled="true" <?php }?>>
                                    <label for="madreCovid">Madre</label>
                                </div>
                                <div class="3u 12u$(xsmall)">
                                    <input type="radio" id="ambosCovid" value="3" name="padresCovid" <?php if($padre==1 and $madre==1) { ?>checked="true" disabled="true"  <?php }?>>
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

    
