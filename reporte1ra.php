<?php
	require('conexion.php');	
	session_start();	
	$_SESSION['mes'] = null; 
	$_SESSION['area'] = null;
	$_SESSION['persona'] = null;	
	$_SESSION['anio'] = null;
	$band="";

	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$ss="SELECT id, departamento from depto";
	$ess=$mysqli->query($ss);
		if(!empty($_POST['pag_sig']))	{
		$anio= $_POST['anio'];
		$_SESSION['anio'] = $anio;
		if($anio=='2019')
			header("Location: informedd2019.php");
		else 
			header("Location: informedd2020.php");
	}
	
	if(!empty($_POST['reporte']))	{
		$anio= 2021;
		$fec=$_POST['mess'];
		
		$sp = $_POST['sp'];
		$persona=$_POST['persona'];
		
		$_SESSION['mes'] = $fec;  //valores  pasaran a la siguiente pagina
		$_SESSION['area'] = $sp;
		$_SESSION['persona'] = $persona;
		$_SESSION['anio'] = $anio;
		if(empty($fec)){
			$band="Seleccione un mes";
		} else 
		header("Location: reporte2da.php");
	}
?>
<html>
	<html lang="en" class="no-js">
	<head>
		<title>Informes</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$("#sp").change(function(){
					$.get("get_personal.php","sp="+$("#sp").val(), function(data){
						$("#persona").html(data);
						console.log(data);
					});
				});		
			});
		</script> <!--Llenar 2o select(personal)-->
	</head>
	
	<body>
		<br><br>
		<div class="box" align="center">
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
				<div class="box">
					<?php if ($_SESSION['departamento']==16 or $idDEPTO==22) { ?>
						<div class="row">
							<div class="6u 12u$(xsmall">
								<select name="anio">
									<option value="2021">2021</option>
									<option value="2020">2020</option>
									<option value="2019">2019</option>										
								</select>
							</div>
							<div class="6u">
								<input type="submit" value="IR..." name="pag_sig" id="pag_sig" class="button fit">
							</div>
						</div>
					<?php } ?>
					<div class="row uniform">
						<div class="12u 12u$(xsmall)">
							<select name="mess">
								<option value="">MES</option>
								<?php $cor="SELECT id, mes from cortes where año='2021'";
								$ecor=$mysqli->query($cor);
								while ($row=$ecor->fetch_assoc()) { ?>
									<option value="<?php echo $row['id']; ?>"><?php echo $row['mes']; ?></option>
								<?php } ?>
							</select>
							<div style = "font-size:16px; color:#cc0000;"><?= $band ?></div>
						</div>
						<div class="12u 12u$(xsmall)">
							<div class="select-wrapper">
								<select id="sp" name="sp" class="from-control">
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
							<input type="submit" class="button fit" name="reporte" value="Generar reporte">
						</div>
						<div class="6u 12u$(xsmall)">
							<input type="button" class="button fit" name="salir" value="salir" onclick="location='welcome.php'">
						</div>
					</div>
					<div class="row uniform">
						<div class="12u 12u$(xsmall)">
							<input type="button" class="button fit special" name="datos" value="Datos reportados" onclick="location='informe_planeacion.php'">
						</div>
					</div>
				</div>
			</form>
		</div>
		<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>		

