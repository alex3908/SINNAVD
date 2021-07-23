<?php
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}	
	$idDEPTO = $_SESSION['id'];
	
	$idNNA=$_GET['id'];

	$cass="SELECT centros.nombre as nom, nna.nombre, nna.apellido_p, nna.apellido_m, centros.id, 
	nna_centros.fecha_reg, nna_centros.fecha_ing, nna_centros.motivo, nna_centros.cuidado_procu, 
	nna_centros.nna_estado, nna_centros.nna_municipio, nna_centros.nna_calle, nna_centros.nna_actaD,
	nna_centros.nna_curpD, nna_centros.nna_consAD, nna_centros.nombreG, nna_centros.apellido_pG, 
	nna_centros.apellido_mG, nna_centros.parentescoG, nna_centros.tel1G, nna_centros.tel2G, 
	nna_centros.correoG, nna_centros.estadoG, nna_centros.municipioG, nna_centros.calleG, 
	nna_centros.nombreT, nna_centros.apellido_pT, nna_centros.apellido_mT, nna_centros.parentescoT, 
	nna_centros.tel1T, nna_centros.tel2T, nna_centros.correoT, nna_centros.situacionJ, nna_centros.respo_reg
	from nna, centros, nna_centros where centros.id=nna_centros.id_centro 
	and nna_centros.id_nna='$idNNA' and nna.id=nna_centros.id_nna and nna_centros.activo='1'";
	$ecas=$mysqli->query($cass);
	$ecas2=$mysqli->query($cass);

	while ($row=$ecas->fetch_assoc()) {		
		$idC= $row['id'];
		$fechar= $row['fecha_reg'];
		$fechai= $row['fecha_ing'];
		$motivoI= $row['motivo'];
		$cuip= $row['cuidado_procu'];
		$nna_estado=$row['nna_estado'];
		$nna_municipio=$row['nna_municipio'];
		$nna_calle=$row['nna_calle'];
		$nna_actaD=$row['nna_actaD'];
		$nna_curpD=$row['nna_curpD'];
		$nna_consAD=$row['nna_consAD'];
		$nombreG=$row['nombreG'];
		$apellido_pG=$row['apellido_pG'];
		$apellido_mG=$row['apellido_mG'];
		$parentescoG=$row['parentescoG'];
		$tel1G=$row['tel1G'];
		$tel2G=$row['tel2G'];
		$correoG=$row['correoG'];
		$estadoG=$row['estadoG'];
		$municipioG=$row['municipioG'];
		$calleG=$row['calleG'];
		$nombreT=$row['nombreT'];
		$apellido_pT=$row['apellido_pT'];
		$apellido_mT=$row['apellido_mT'];
		$parentescoT=$row['parentescoT'];
		$tel1T=$row['tel1T'];
		$tel2T=$row['tel2T'];
		$correoT=$row['correoT'];
		$situacionJ=$row['situacionJ'];
		$respo_reg=$row['respo_reg'];
	}
	
	$bandera = false;
	if(!empty($_POST))
	{
		$fecha_eg = mysqli_real_escape_string($mysqli,$_POST['fecha_eg']);
		$seguimiento = mysqli_real_escape_string($mysqli,$_POST['seguimiento']);
		$motivoE = $_POST['motivo'];
		$fechaa= date ("Y-m-d H:i:s", time());
		$egre="INSERT INTO ingresos_historial (id_centro, id_nna, fecha_reg, fecha_ing, motivo, 
		cuidado_procu, nna_estado, nna_municipio, nna_calle, nna_actaD, nna_curpD, nna_consAD, 
		nombreG, apellido_pG, apellido_mG, parentescoG, tel1G, tel2G, correoG, estadoG, municipioG,calleG,
		 nombreT, apellido_pT, apellido_mT, parentescoT, tel1T, tel2T, correoT, situacionJ, respo_reg, 
		 fecha_his, respo_his) VALUES ('$idC', '$idNNA', '$fechar', '$fechai', '$motivoI', '$cuip',
		  '$nna_estado', '$nna_municipio', '$nna_calle', '$nna_actaD' ,'$nna_curpD' ,'$nna_consAD' ,
		  '$nombreG' ,'$apellido_pG' ,'$apellido_mG' ,'$parentescoG' ,'$tel1G', '$tel2G', '$correoG', 
		  '$estadoG', '$municipioG', '$calleG', '$nombreT', '$apellido_pT', '$apellido_mT', '$parentescoT', 
		  '$tel1T', '$tel2T', '$correoT', '$situacionJ', '$respo_reg', '$fechaa', '$idDEPTO')";
		$eg=$mysqli->query($egre);
		if($eg){
		$elimi="UPDATE nna_centros SET activo=0 where id_nna='$idNNA'";
		$eeli=$mysqli->query($elimi);
		$sqlNino = "INSERT INTO egreso_cas (id_centro, id_nna, fecha_ing, motivo_ing, 
		fecha_reg, fecha_eg, motivo, seguimiento, respo_reg) VALUES ('$idC', '$idNNA', '
		$fechai', '$motivoI', '$fechaa', '$fecha_eg', '$motivoE', '$seguimiento', '$idDEPTO')";
		$resultNino = $mysqli->query($sqlNino);
			
			
			if($resultNino>0)
			header("Location: nnaENcas.php?id=$idC");
			else
			$error = "Error al Registrar: ".$sqlNino;	
		} else echo "Error: ".$egre;
	}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Inicio</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />		
	</head>
	<body>		
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div>
							<h3 align="center">EGRESO DE CENTRO ASISTENCIAL</h3>
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
			 					<?php while ($row=$ecas2->fetch_assoc()) { ?>		 					
									<div class="4u 12u$(xsmall)">NNA
										<input id="nombre" name="nombre" type="text" value="<?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?>"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
									</div>
								
									<div class="3u 12u$(xsmall)">Centro de egreso
										<input id="fecha_ing" value="<?php echo $row['nom']; ?>" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>	
									</div>
								<?php } ?>
									<div class="2u 12u$(xsmall)">Fecha de egreso
										<input id="fecha_eg" name="fecha_eg" type="date" placeholder="dd/mm/aaaa"  required>	
									</div>
									<div class="3u 12u$(xsmall)">Motivo
										<div class="select-wrapper">
											<select id="motivo" name="motivo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
												<option value="DERIVACION A CENTRO ASISTENCIAL">DERIVACION A CENTRO ASISTENCIAL</option>
												<option value="REINTEGRACION FAMILIAR">REINTEGRACION FAMILIAR</option>
												<option value="ADOPCION">ADOPCION</option>
												<option value="REPATRIACION">REPATRIACION</option>
												<option value="ACOGIMIENTO FAMILIAR">ACOGIMIENTO FAMILIAR</option>
												<option value="SALIDA SIN AUTORIZACION">SALIDA SIN AUTORIZACIÓN</option>
											</select>
										</div>
									</div>
									<div class="12u">
										<textarea name="seguimiento" placeholder="SEGUIMIENTO AL PROCESO DE REINCORPORACIÓN FAMILIAR O SOCIAL" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
									</div>
									
									</div>
									
						</div>
				
	<div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='nnaENcas.php?id=<?php echo $idC; ?>'" >
		</ul>
	</div>
</form>


		<?php if($bandera) { 
			header("Location: welcome.php");
			?>
			<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
		<?php } ?>
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