<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$personas="SELECT id_depto from departamentos where id='$idDEPTO'";
	$epersonas=$mysqli->query($personas);
	while ($row=$epersonas->fetch_assoc()) {
		$idd=$row['id_depto'];
	}
	$tper="SELECT id, responsable from departamentos where id_depto='$idd'";
	$etper=$mysqli->query($tper);

	$idAud=$_GET['id'];
	$conso="SELECT id_carpeta from audiencias where id='$idAud'";
	$econso=$mysqli->query($conso);
	while ($row=$econso->fetch_assoc()) {
		$idCarpeta=$row['id_carpeta'];
	}
if(!empty($_POST))
	{
		foreach((array)@$_POST["atenci"] as $valor){ 
			
			$sqlup="INSERT into personas_aud (id_aud, id_respo) values ('$idAud','$valor')";
			$esql=$mysqli->query($sqlup);
		}
		header("Location: audienciaxcarpeta.php?id=$idCarpeta");

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
		<h2>Representantes</h2>
			<div class="row uniform">
				
				<?php $i=0;	while($row= $etper->fetch_assoc()){	?>
					<div class="6u 12u$(xsmall)">

					<input type="checkbox" id="<?php echo 'demo-'.$i; ?>" name="atenci[]" value="<?php echo $row['id'];?>" >
							<label for="<?php echo 'demo-'.$i; ?>"><?php echo $row['responsable'];?></label>	</div>
    			<?php $i++;	} ?>			
			<br>
				<div class="12u$">
					<input type="submit" value="guardar" class="button special fit small" />
					<input type="button" value="cancelar" class="button fit small" onclick="location='audienciaxcarpeta.php?id=<?php echo $idCarpeta ?>'" />
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

	

