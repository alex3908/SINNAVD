<?php
ob_start();

	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];
    $idPosibleCaso= $_GET['id'];
    $area=$_GET['area'];    
    $qperomisos="SELECT id from departamentos where (id_depto in ('9','10','14') and id_personal='3' 
	and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5' and id='$idDEPTO') 
	or (id_personal='1' and id='$idDEPTO' and id_depto='16')"; //ayuda a validar la persona que puede mover 
	// proteccion, representacion, y vinculacion cuando son administrativos. o subprocu, o administrador y control de informacion
	$rpermisos=$mysqli->query($qperomisos);
    $permiso=$rpermisos->num_rows;

    $qfolio="SELECT folio from posible_caso where id='$idPosibleCaso'";
    $rfolio=$mysqli->query($qfolio);
    $afolio=$rfolio->fetch_assoc();
    $folio=implode($afolio);

	$qdepartamentos="SELECT id, responsable from departamentos";
    $rdepartamentos=$mysqli->query($qdepartamentos);

	if(!empty($_POST)){
		$respo=$_POST['respo'];	
        $fecha=date("Y-m-d H:i:s", time());	
		if (empty($respo)) {
			echo "ERROR 1";
		}else {
            if($permiso>0) {
                if($area==1){
                    $qregjuridico="INSERT into historico_asignaciones_juridico 
                    (id_posible_caso, id_departamentos_asignado, fecha_asignacion) 
                    values ('$idPosibleCaso', '$respo', '$fecha')";
                    $rregjuridico=$mysqli->query($qregjuridico);
                    if($rregjuridico){
                        $qidJuridico="SELECT max(id) from historico_asignaciones_juridico where id_posible_caso='$idPosibleCaso'";
                        $ridJuridico=$mysqli->query($qidJuridico);
                        $aidJuridico=$ridJuridico->fetch_assoc();
                        $idJuridico=implode($aidJuridico);
                        $qactjuridico="UPDATE posible_caso set id_asignado_juridico='$idJuridico' where id='$idPosibleCaso'";
		                $ractjuridico=$mysqli->query($qactjuridico);
                        if($ractjuridico) {
                            header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
                        } else {
                            echo "ERROR 2: ".$qactjuridico;
                        }
                    } else 
                    echo "ERROR 3: ".$qregjuridico;
                } else if($area==2){
                    $qregts="INSERT into historico_asignaciones_trabajo_social 
                    (id_posible_caso, id_departamentos_asignado, fecha_asignacion) 
                    values ('$idPosibleCaso', '$respo', '$fecha')";
                    $rregts=$mysqli->query($qregts);
                    if($rregts){
                        $qidTS="SELECT max(id) from historico_asignaciones_trabajo_social where id_posible_caso='$idPosibleCaso'";
                        $ridTS=$mysqli->query($qidTS);
                        $aidTS=$ridTS->fetch_assoc();
                        $idTS=implode($aidTS);
                        $qactTS="UPDATE posible_caso set id_asignado_ts='$idTS' where id='$idPosibleCaso'";
		                $ractTS=$mysqli->query($qactTS);
                        if($ractTS) {
                            header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
                        } else {
                            echo "ERROR 2: ".$qactTS;
                        }
                    } else 
                    echo "ERROR 3: ".$qregts;
                } else {
                    $qregps="INSERT into historico_asignaciones_psicologia 
                    (id_posible_caso, id_departamentos_asignado, fecha_asignacion) 
                    values ('$idPosibleCaso', '$respo', '$fecha')";
                    $rregps=$mysqli->query($qregps);
                    if($rregps){
                        $qidPS="SELECT max(id) from historico_asignaciones_psicologia where id_posible_caso='$idPosibleCaso'";
                        $ridPS=$mysqli->query($qidPS);
                        $aidPS=$ridPS->fetch_assoc();
                        $idPS=implode($aidPS);
                        $qactPS="UPDATE posible_caso set id_asignado_ps='$idPS' where id='$idPosibleCaso'";
		                $ractPS=$mysqli->query($qactPS);
                        if($ractPS) {
                            header("Location: perfil_posible_caso.php?idPosibleCaso=$idPosibleCaso");
                        } else {
                            echo "ERROR 2: ".$qactPS;
                        }
                    } else 
                    echo "ERROR 3: ".$qregps;
                }
            } else echo "Usted no tiene permisos para realizar esa acción";
	}
	}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Lista</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
	</head>
	<body>
	 <br><br><br>

		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >     
		<div class="box">
		
			<h3>Folio de posible caso: <?php echo $folio; ?> </h3>
		
		<br>
			<div class="row uniform">
			<div class="12u$">
				<div class="select-wrapper">
					<select id="respo" name="respo" required>
                        <?php if($area==1) { ?>
						<option value="">Seleccione a responsable de juridico</option>
                        <?php } else if($area==2) { ?>
						<option value="">Seleccione a responsable de trabajo social</option>
                        <?php } else  {?>    
						<option value="">Seleccione a responsable de psicología</option>
						<?php } while($row = $rdepartamentos->fetch_assoc()){ ?>
						<option value="<?php echo $row['id']; ?>"><?php echo $row['responsable']; ?></option>
                        <?php } if($area!=1) { ?><option value="-1">DIF Municipal</option> <?php } ?>
					</select>					
				</div>
			</div>	
				<div class="12u$">
					<input type="submit" value="guardar" class="button special fit small" />
					<input type="button" value="cancelar" class="button fit small" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?php echo $idPosibleCaso; ?>'" />
				</div>
														
															
			</div></div>
		</form>
		
		
		<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>

			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		