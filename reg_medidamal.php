<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
	}
	
	$idDEPTO = $_SESSION['id'];

	$respo="SELECT responsable from departamentos where id='$idDEPTO'";
	$erespo=$mysqli->query($respo);
	while ($row=$erespo->fetch_assoc()) {
		$re=$row['responsable'];
	}
	$idCaso= $_GET['idCaso'];	
	$sqlcaso="SELECT nombre from casos where id='$idCaso'";
	$ecaso=$mysqli->query($sqlcaso);

	$consulta="SELECT id, medida_p FROM medidas";
	$econsulta=$mysqli->query($consulta);

	$sqlbene="SELECT nna.id, nna.nombre, nna.apellido_p, nna.apellido_m, nna_caso.estado from nna, nna_caso where nna_caso.id_caso='$idCaso' and nna_caso.id_nna=nna.id and nna_caso.estado='NE'";
	$esqlbene=$mysqli->query($sqlbene);

	$cuadro="SELECT id_derecho, marco, med_prot, beneficiario, responsable_med, atp_encargada, periodicidad, estado, observaciones, fecha, id_sp_registro from cuadro_guia where id_caso='$idCaso'";
	$ecuadro=$mysqli->query($cuadro);
	$derechos="SELECT id, derecho from derechos_nna";
	$ederechos=$mysqli->query($derechos);
	$fec=date("j/n/Y");

	if(!empty($_POST))
	{
		$derecho = $_POST['derecho'];
		$medida = $_POST['medida'];
		$marco = mysqli_real_escape_string($mysqli,$_POST['marco']);
		$med_prot = $_POST['med_prot'];
		$beneficiario =$_POST['beneficiario'];
		$responsable_med = mysqli_real_escape_string($mysqli,$_POST['responsable_med']);
		$atp_encargada = mysqli_real_escape_string($mysqli,$_POST['atp_encargada']);
		$periodicidad = mysqli_real_escape_string($mysqli,$_POST['periodicidad']);
		
		$nbene=count($beneficiario);
		$sqlNino = "INSERT INTO cuadro_guia (id_caso, id_derecho, id_medida, marco, id_mp, responsable_med, atp_encargada, periodicidad, estado, fecha_eje, fecha, id_sp_registro) VALUES ('$idCaso', '$derecho', '$medida', '$marco', '$med_prot', '$responsable_med', '$atp_encargada', '$periodicidad', '0', '', '$fec', '$idDEPTO')";
			$resultNino = $mysqli->query($sqlNino);
		$idmm="SELECT id from cuadro_guia where id_caso='$idCaso' and id_derecho='$derecho' and id_medida='$medida' and marco='$marco' and responsable_med='$responsable_med' and fecha='$fec' and atp_encargada='$atp_encargada'";
		
		$eidmm=$mysqli->query($idmm);
		while ($row=$eidmm->fetch_assoc()) {
				$idmedida=$row['id'];
			}	
		for ($i=0; $i <count($beneficiario) ; $i++) { 
				$bene1=$beneficiario[$i];
				
			$bene2="INSERT INTO benefmed (id_medida, id_caso, id_nna) values ('$idmedida','$idCaso','$bene1')";
			$ebene2=$mysqli->query($bene2);
			}
		if($resultNino>0)
			header("Location: cuadro_guia.php?id=$idCaso");
			else
			$error = "Error al Registrar";
	}
?>

<!DOCTYPE HTML>

<html>


<head>
		<title>Perfil</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
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
		</div></div> <br>				
							<?php while ($row=$ecaso->fetch_assoc()) { ?>
							
						
							<h3>Caso: <?php echo $row['nombre']; }?></h3>
									  <div class="box">
									  <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                    <div class="row uniform">
                    <div class="8u 12u$(small)">
                    	DERECHO VULNERADO O RESTRINGIDO
						<div class="select-wrapper">
							<select id="derecho" name="derecho" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
								<?php while ($row=$ederechos->fetch_assoc()) { ?>
									<option value="<?php echo $row['id'];?>"><?php echo $row['id']."- ".$row['derecho'];?></option>
								<?php }  ?>
							</select>
						</div>
					</div>
					<div class="4u 12u$(small)">
						TIPO DE MEDIDA
						<div class="select-wrapper">
							<select id="medida" name="medida" required>
								<?php while ($row=$econsulta->fetch_assoc()) { ?>
									<option value="<?php echo $row['id'];?>"><?php echo $row['id']."- ".$row['medida_p'];?></option>
								<?php }  ?>
							</select>
						</div>
					</div>
                    <div class="6u 12u$(xsmall)">MARCO JURIDICO
                    <textarea name="marco" cols="" rows="" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
                    </div>
                    <div class="6u 12u$(xsmall)">BENEFICIARIO
                   						<div class="select-wrapper">
											<select name="beneficiario[]" multiple="multiple" size="5" style="height:87px; width:100%"  required>
												<?php while ($row=$esqlbene->fetch_assoc()) { ?>
													<option value="<?php echo $row['id'];?>"><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></option>
												<?php }  ?>
												
											</select>
										</div>
                    </div>
                    <div class="12u 12u$(xsmall)">MEDIDA DE PROTECCIÓN
                    <div class="select-wrapper">
											<select id="med_prot" name="med_prot" required>
													<optgroup label="Medidas NNA">
												<?php $catMed="SELECT id, folio, medidaC from catalogo_medidas where tipo='1' order by id";
														$ecatMed=$mysqli->query($catMed);
												while ($row=$ecatMed->fetch_assoc()) { ?>
													<option value="<?php echo $row['id'];?>"><?php echo $row['folio']."- ".$row['medidaC'];?></option>
												<?php }  ?>
													</optgroup>
													<optgroup label="Medidas CNP">
												<?php $catMed="SELECT id, folio, medidaC from catalogo_medidas where tipo='2'";
														$ecatMed=$mysqli->query($catMed);
												while ($row=$ecatMed->fetch_assoc()) { ?>
													<option value="<?php echo $row['id'];?>"><?php echo $row['folio']."- ".$row['medidaC'];?></option>
												<?php }  ?>
													</optgroup>
													<optgroup label="Medidas NNA Migrantes">
												<?php $catMed="SELECT id, folio, medidaC from catalogo_medidas where tipo='3'";
														$ecatMed=$mysqli->query($catMed);
												while ($row=$ecatMed->fetch_assoc()) { ?>
													<option value="<?php echo $row['id'];?>"><?php echo $row['folio']."- ".$row['medidaC'];?></option>
												<?php }  ?>
													</optgroup>
													<optgroup label="Medidas Adultos">
												<?php $catMed="SELECT id, folio, medidaC from catalogo_medidas where tipo='4'";
														$ecatMed=$mysqli->query($catMed);
												while ($row=$ecatMed->fetch_assoc()) { ?>
													<option value="<?php echo $row['id'];?>"><?php echo $row['folio']."- ".$row['medidaC'];?></option>
												<?php }  ?>
													</optgroup>
											</select>
										</div>
                    </div>
                    <div class="6u 12u$(xsmall)">INSTITUCIÓN O PERSONA RESPONSABLE
                    <input name='responsable_med' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>
                    <div class="6u 12u$(xsmall)">TITULAR O PERSONA ENCARGADA DE LLEVARLA ACABO
                    <input name='atp_encargada' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>
                    <div class="4u 12u$(xsmall)">PERIODICIDAD
                    <input name='periodicidad' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>
                    <div class="3u 12u$(xsmall)">FECHA DE REGISTRO
                    <input name='fecha' type='text' value="<?php echo $fec; ?>" disabled >
                    </div>
                    <div class="5u 12u$(xsmall)">RESPONSABLE DE REGISTRO
                    <input name='id_sp_registro' type='text' value="<?php echo $re; ?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
                    </div>
                    <div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='cuadro_guia.php?id=<?php echo $idCaso;?>'" >
		</ul>
	</div>
                    </div>
                    </form>
                    </div>
							<br>
								
						</div>
					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							
						<?php if($_SESSION['departamento']==7) { ?> 
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												<li><a href="registro_usuarios.php">Alta</a></li>
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>

									</ul>
								</nav>
														
								<?php }elseif ($_SESSION['departamento']==16) { ?>
							
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
										
										<li><a href="welcome.php" ">Inicio</a></li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>						
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>		
							
								<?php }
	
								?>
								<section>
									<header class="major">
										<h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
									</header>
									<p></p>
									<ul class="contact">
										<li class="fa-envelope-o"><a href="#">laura.ramirez@hidalgo.gob.mx</a></li>
										<li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
										<li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
										<li class="fa-home">Plaza Juarez #118<br />
										Col. Centro <br> Pachuca Hidalgo</li>
									</ul>
								</section>
							<!-- Footer -->
								<footer id="footer">
									<p class="copyright">&copy; Ing. Ivan Flores Navarro. </p>
								</footer>

						</div>
					</div>
					<!--cierre menu-->

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>