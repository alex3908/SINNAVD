<?php
	require('conexion.php');	
	session_start();	
	

	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	

	$ss="SELECT id, departamento from depto";
	$ess=$mysqli->query($ss);
	$num_ss=$ess->num_rows; 
	
	if(!empty($_POST))	{
	$fec=$_POST['mess'];	
	$sp = $_POST['sp'];  //subprocu
	$persona=$_POST['persona'];
	
	$_SESSION['mes'] = $fec;
	$_SESSION['area'] = $sp;
	$_SESSION['persona'] = $persona;
	header("Location: reporte2da.php");
	}
?>
<html>
	<html lang="en" class="no-js">
	<head>
		<title>Inicio de sesión</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>	
	</head>
	
	<body>

	
        <br><br>
	<div class="box alt" align="center">
	<div class="row">
		<div class="6u 12u$(xsmall)">
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >     
			<div class="box">
		<?php if ($_SESSION['departamento']==16) { ?>
								<input type="button" class="button fit" value="siguiente" onclick="location='informedd2019.php'">
							<?php } ?>
							
			 					<div class="row uniform">
									<div class="12u 12u$(xsmall)">
										<select name="mess">
											<option value="">MES</option>
											<?php $cor="SELECT id, mes from cortes";
						 						  $ecor=$mysqli->query($cor);
												while ($row=$ecor->fetch_assoc()) { ?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['mes']; ?></option>	 	
											<?php } ?>
										</select>
									</div>
									<div class="12u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="sp" name="sp" class="from-control" required>
												<option value="">--SUBPROCU--</option>
												<?php while ($row=$ess->fetch_assoc()) { ?>
												<option value="<?php echo $row['id'] ?>"><?php echo $row['departamento']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="12u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="persona" name="persona" class="from-control">
												<option value="Todos">--SELECCIONE</option>							
											</select>
										</div>
									</div>
									<div class="6u 12u$(xsmall)">
										<input type="submit" class="button fit" name="" value="Generar reporte">
										<?php
	require('conexion.php');	
	session_start();	
	

	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$ss="SELECT id, departamento from depto";
	$ess=$mysqli->query($ss);
	
	if(!empty($_POST))	{
	$fec=$_POST['mess'];	
	$sp = $_POST['sp'];
	$persona=$_POST['persona'];
	
	$_SESSION['mes'] = $fec;
	$_SESSION['area'] = $sp;
	$_SESSION['persona'] = $persona;
	header("Location: reporte2da.php");
	}
?>
<html>
	<html lang="en" class="no-js">
	<head>
		<title>Inicio de sesión</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
	</head>
	
	<body>

	
        <br><br>
	<div class="box alt" align="center">
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >     
			<div class="box">
		<?php if ($_SESSION['departamento']==16) { ?>
								<input type="button" class="button fit" value="siguiente" onclick="location='informedd2019.php'">
							<?php } ?>
						
			 					<div class="row uniform">
									<div class="12u 12u$(xsmall)">
										<select name="mess">
											<option value="">MES</option>
											<?php $cor="SELECT id, mes from cortes";
						 						  $ecor=$mysqli->query($cor);
												while ($row=$ecor->fetch_assoc()) { ?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['mes']; ?></option>	 	
											<?php } ?>
										</select>
									</div>
									<div class="12u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="sp" name="sp" class="from-control" required>
												<option value="">--SUBPROCU--</option>
												<?php while ($row=$ess->fetch_assoc()) { ?>
												<option value="<?php echo $row['id'] ?>"><?php echo $row['departamento']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="12u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="persona" name="persona" class="from-control">
												<option value="Todos">--SELECCIONE</option>							
											</select>
										</div>
									</div>
									<div class="6u 12u$(xsmall)">
										<input type="submit" class="button fit" name="" value="Generar reporte">
									</div>
									<div class="6u 12u$(xsmall)">
										<input type="button" class="button fit" name="" value="salir" onclick="location='welcome.php'">
									</div>
								</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#sp").change(function(){
			$.get("get_personal.php","sp="+$("#sp").val(), function(data){
				$("#persona").html(data);
				console.log(data);
			});
		});		
	});
</script>
		</div></form>
		
	</div>
		
		<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>

			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		

					</div>
									<div class="6u 12u$(xsmall)">
										<input type="button" class="button fit" name="" value="salir" onclick="location='welcome.php'">
									</div>
								</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#sp").change(function(){
			$.get("get_personal.php","sp="+$("#sp").val(), function(data){
				$("#persona").html(data);
				console.log(data);
			});
		});		
	});
</script>
		</div></form>
		</div>
		<div class="6u 12u(xsmall)">
		
	</div>
		
		<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>

			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		

