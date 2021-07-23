<?php
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$idNNA=$_GET['idn'];
	$id_centro=$_GET['idc'];

	$qCentros="SELECT * FROM `centros` where id!='0'";
	$rCentros=$mysqli->query($qCentros);

	$query="SELECT * from municipios where id!='0'";
	$equery=$mysqli->query($query);

	$countries = array();
	while($row=$equery->fetch_object())	{ $countries[]=$row; }


	$nomNNA="SELECT nombre, apellido_p, apellido_m from nna where id='$idNNA'";
	$enom=$mysqli->query($nomNNA);
	
	$bandera = false;
	if(!empty($_POST))
	{
		$fecha_ing = mysqli_real_escape_string($mysqli,$_POST['fecha_ing']);
		$motivo = $_POST['motivo'];
		$cuiP='NO'; if ($_POST['cuidadoPROCU']) { $cuiP='SI'; }
		$estadoN = mysqli_real_escape_string($mysqli,$_POST['estadoN']);
		$munN = $_POST['munN'];	
		$calleN = mysqli_real_escape_string($mysqli,$_POST['calleN']);
		$actaNAC='NO'; if ($_POST['actaNAC']) { $actaNAC='SI'; }
		$docCURP='NO'; if ($_POST['docCURP']) { $docCURP='SI'; }
		$docALUMBRA='NO'; if ($_POST['docALUMBRA']) { $docALUMBRA='SI'; }		
		$nomG = mysqli_real_escape_string($mysqli,$_POST['nomG']);
		$ap_paG = mysqli_real_escape_string($mysqli,$_POST['ap_paG']);
		$ap_maG = mysqli_real_escape_string($mysqli,$_POST['ap_maG']);
		$parentescoG = mysqli_real_escape_string($mysqli,$_POST['parentescoG']);
		$tel1G = mysqli_real_escape_string($mysqli,$_POST['tel1G']);
		$tel2G = mysqli_real_escape_string($mysqli,$_POST['tel2G']);
		$emailG = mysqli_real_escape_string($mysqli,$_POST['emailG']);
		$estadoG = mysqli_real_escape_string($mysqli,$_POST['estadoG']);
		$munG = $_POST['munG'];
		$calleG = mysqli_real_escape_string($mysqli,$_POST['calleG']);
		$nomT = mysqli_real_escape_string($mysqli,$_POST['nomT']);
		$ap_paT = mysqli_real_escape_string($mysqli,$_POST['ap_paT']);
		$ap_maT = mysqli_real_escape_string($mysqli,$_POST['ap_maT']);
		$parentescoT = mysqli_real_escape_string($mysqli,$_POST['parentescoT']);
		$tel1T = mysqli_real_escape_string($mysqli,$_POST['tel1T']);
		$tel2T = mysqli_real_escape_string($mysqli,$_POST['tel2T']);
		$emailT = mysqli_real_escape_string($mysqli,$_POST['emailT']);
		$situacionJ = mysqli_real_escape_string($mysqli,$_POST['situacionJ']);
		$id_cent = $_POST['id_cent'];
		$apoyo = $_POST['apoyo'];
		$cant_apoyo = mysqli_real_escape_string($mysqli,$_POST['cant_apoyo']);
		$fechaa= date ("Y-m-d H:i:s", time());
		$sqlUser = "SELECT id_nna FROM nna_centros WHERE id_nna = '$idNNA'";
		$resultUser=$mysqli->query($sqlUser);
		$rows = $resultUser->num_rows;

		if($rows > 0) {
			?>
			<script type="text/javascript">alert('Ya existe');</script>
			
			<?php } else {
			
			$sqlNino = "INSERT INTO nna_centros (id_centro, id_nna, fecha_reg, fecha_ing, motivo, 
			cuidado_procu, nna_estado, nna_municipio, nna_calle, nna_actaD, nna_curpD, nna_consAD, 
			nombreG, apellido_pG, apellido_mG, parentescoG, tel1G, tel2G, correoG, estadoG, municipioG,
			 calleG, nombreT, apellido_pT, apellido_mT, parentescoT, tel1T, tel2T, correoT, situacionJ,
			  respo_reg, apoyo, cant_apoyo) VALUES ('$id_cent', '$idNNA', '$fechaa', '$fecha_ing', 
			  '$motivo', '$cuiP', '$estadoN', '$munN', '$calleN', '$actaNAC', '$docCURP', '$docALUMBRA', 
			  '$nomG', '$ap_paG', '$ap_maG', '$parentescoG', '$tel1G', '$tel2G', '$emailG', '$estadoG', 
			  '$munG', '$calleG', '$nomT', '$ap_paT', '$ap_maT', '$parentescoT', '$tel1T', '$tel2T', 
			  '$emailT', '$situacionJ', '$idDEPTO', '$apoyo', '$cant_apoyo')";
			$resultNino = $mysqli->query($sqlNino);
				
			if($resultNino>0)
			header("Location: nnaENcas.php?id=$id_cent");
			else
			$error = "Error al Registrar: ".$sqlNino;;
			
		}
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
		<script type="text/javascript" src="jquery.min.js"></script>
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
							
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
			 					<?php while ($row=$enom->fetch_assoc()) { ?>		 					
									<div class="4u 12u$(xsmall)">NNA
										<input id="nombre" name="nombre" type="text" value="<?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?>"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
									</div>
								<?php } ?>
									
									<div class="2u 12u$(xsmall)">Fecha de ingreso
										<input id="fecha_ing" name="fecha_ing" type="date" required>	
									</div>
									<div class="3u 12u$(xsmall)">Motivo
										<div class="select-wrapper">
											<select id="motivo" name="motivo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
												<option value="MEDIDA DE PROTECCION ESPECIAL">MEDIDA DE PROTECCION ESPECIAL</option>
												<option value="MEDIDA URGENTE DE PROTECCION">MEDIDA URGENTE DE PROTECCION</option>
												<option value="MEDIDA URGENTE DE PROTECCION DECRETADA POR PROCURADURIA">MEDIDA URGENTE DE PROTECCION DECRETADA POR PROCURADURIA</option>
												<option value="INGRESO VOLUNTARIO">INGRESO VOLUNTARIO</option>
												<option value="ALBERGUE POR SITUACION MIGRATORIA">ALBERGUE POR SITUACION MIGRATORIA</option>
												<option value="ALBERGUE TEMPORAL DETERMINADO POR AUTORIDAD ADMINISTRATIVA O JUDICIAL">ALBERGUE TEMPORAL DETERMINADO POR AUTORIDAD ADMINISTRATIVA O JUDICIAL</option>
												<option value="CANALIZACION A OTRO CENTRO">CANALIZACION A OTRO CENTRO</option>
												<option value="CONSENTIMIENTO DE ADOPCION">CONSENTIMIENTO DE ADOPCIÓN</option>
											</select>
										</div>
									</div>
									<div class="3u 12u$(xsmall)">										
										<input type="checkbox" id="cuidadoPROCU" name="cuidadoPROCU">	<label for="cuidadoPROCU">NNA BAJO CUIDADO DE LA PROCURADURIA</label>	
									</div>
									<div class="9u">
										<div class="box">Ultimo domicilio antes del Ingreo a CAS
											<div class="row uniform">									
												<div class="3u 12u$(xsmall)">
													<input id="estado" name="estadoN" type="text" class="estado" placeholder="Estado" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>	
												</div>
												<div class="4u 12u(xsmall)">
													<div class="select-wrapper">
														<select id="country_id" class="form-control" name="munN" required>
      													<option value="">-- MUNICIPIO --</option>
														<?php foreach($countries as $c):?>
     													<option value="<?php echo $c->id; ?>"><?php echo $c->municipio; ?></option>
														<?php endforeach; ?>
    													</select>
													</div>
												</div>
												<div class="5u 12u$(xsmall)">
													<input id="calle" name="calleN" type="text" class="direccion" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="calle y numero" required>	
												</div>
											</div>
										</div>
									</div>
									<div class="3u">
										Documentos de identidad del NNA
										<input type="checkbox" id="actaNAC" name="actaNAC">	<label for="actaNAC">ACTA DE NACIMIENTO</label><br>
										<input type="checkbox" id="docCURP" name="docCURP">	<label for="docCURP">CURP</label>	
										<input type="checkbox" id="docALUMBRA" name="docALUMBRA">	<label for="docALUMBRA">CONSTANCIA DE ALUMBRAMIENTO</label>	
									</div>
									<div class="12u">
										<div class="box">Persona que ejerce la Guarda o Custodia
											<div class="row uniform">									
												<div class="2u 12u$(xsmall)">
													<input type="text" name="nomG" placeholder="nombre(s)" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>	
												</div>
												<div class="2u 12u(xsmall)">
													<input type="text" name="ap_paG" placeholder="apellido paterno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
												</div>
												<div class="2u 12u(xsmall)">
													<input type="text" name="ap_maG" placeholder="apellido materno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
												</div>
												<div class="2u">
													<div class="select-wrapper">
														<select id="relacion_i" name="parentescoG" required>
															<option value="">PARENTESCO</option>
															<option value="MAMÁ">MAMÁ</option>
															<option value="PAPÁ">PAPÁ</option>
															<option value="HERMANO(A)">HERMANO(A)</option>
															<option value="TIO(A)">TIO(A)</option>
															<option value="PRIMO(A)">PRIMO(A)</option>
															<option value="ABUELO(A)">ABUELO(A)</option>
															<option value="VECINO(A)">VECINO(A)</option>
															<option value="NINGUNO">NINGUNO</option>
															<option value="MAESTRO(A)">MAESTRO(A)</option>
															<option value="PADRINO">PADRINO</option>
															<option value="MADRINA">MADRINA</option>
															<option value="PADRASTRO">PADRASTRO</option>
															<option value="MADRASTRA">MADRASTRA</option>	
														</select>
													</div>
												</div>
												<div class="2u 12u$(xsmall)">
													<input type="text" name="tel1G" placeholder="telefono 1" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
												</div>
												<div class="2u 12u$(xsmall)">
													<input type="text" name="tel2G" placeholder="telefono 2" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
												</div>
												<div class="3u 12u$(xsmall)">
													<input type="text" name="emailG" placeholder="CORREO ELECTRONICO" required>
												</div>
												<div class="2u 12u$(xsmall)">
													<input id="estado" name="estadoG" type="text" class="estado" placeholder="Estado" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>	
												</div>
												<div class="3u 12u(xsmall)">
													<div class="select-wrapper">
														<select id="country_id" class="form-control" name="munG" required>
      													<option value="">-- MUNICIPIO --</option>
														<?php foreach($countries as $c):?>
     													<option value="<?php echo $c->id; ?>"><?php echo $c->municipio; ?></option>
														<?php endforeach; ?>
    													</select>
													</div>
												</div>												
												<div class="4u 12u$(xsmall)">
													<input id="calle" name="calleG" type="text" class="direccion" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="calle y numero" required>	
												</div>
											</div>
										</div>
									</div>
									<div class="8u">
										<div class="box">Persona que ejerce la Tutela
											<div class="row uniform">									
												<div class="3u 12u$(xsmall)">
													<input type="text" name="nomT" placeholder="nombre(s)" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>	
												</div>
												<div class="3u 12u(xsmall)">
													<input type="text" name="ap_paT" placeholder="apellido paterno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
												</div>
												<div class="3u 12u(xsmall)">
													<input type="text" name="ap_maT" placeholder="apellido materno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
												</div>
												<div class="3u">
													<div class="select-wrapper">
														<select id="relacion_i" name="parentescoT" required>
															<option value="">PARENTESCO</option>
															<option value="MAMÁ">MAMÁ</option>
															<option value="PAPÁ">PAPÁ</option>
															<option value="HERMANO(A)">HERMANO(A)</option>
															<option value="TIO(A)">TIO(A)</option>
															<option value="PRIMO(A)">PRIMO(A)</option>
															<option value="ABUELO(A)">ABUELO(A)</option>
															<option value="VECINO(A)">VECINO(A)</option>
															<option value="NINGUNO">NINGUNO</option>
															<option value="MAESTRO(A)">MAESTRO(A)</option>
															<option value="PADRINO">PADRINO</option>
															<option value="MADRINA">MADRINA</option>
															<option value="PADRASTRO">PADRASTRO</option>
															<option value="MADRASTRA">MADRASTRA</option>	
														</select>
													</div>
												</div>
												<div class="3.5u 12u$(xsmall)">
													<input type="text" name="tel1T" placeholder="telefono 1" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
												</div>
												<div class="3.5u 12u$(xsmall)">
													<input type="text" name="tel2T" placeholder="telefono 2" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
												</div>
												<div class="5u 12u$(xsmall)">
													<input type="text" name="emailT" placeholder="CORREO ELECTRONICO" required>
												</div>												
											</div>
										</div>
									</div>
									<div class="4u">
										<textarea name="situacionJ" placeholder="SITUACION JURIDICA" rows="6" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
									</div>
									<div class="6u">
										<div class="select-wrapper">
											<select id="centros" name="id_cent" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required >
												<option value="0">CENTRO</option>
												<?php while($row = $rCentros->fetch_assoc()){ ?>
												<option value="<?php echo $row['id']; ?>"><?php echo $row['nombre']; ?></option>
												<?php }?>
											</select>
										</div>
									</div>
									<div class="2u">
										<div class="select-wrapper">
											<select id="apoyo" name="apoyo" required>
													<option value="">Apoyo</option>
													<option value="0">Ninguno</option>
													<option value="1">Cuota</option>
													<option value="2">Especie</option>
											</select>
										</div>
									</div>
									<div class="4u">
										<input type="text" name="cant_apoyo" placeholder="Valor del apoyo recibido" style="text-transform:uppercase;">
									</div>
								</div>
									
						</div>
				
	<div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='cas.php'" >
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