<?php
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$idCaso=$_GET['id'];

	$fecha= date ("j/n/Y");
	$dis="SELECT id, distrits from distritos";
	$edis=$mysqli->query($dis);
	$del="SELECT id, delito from delitos order by delito";
	$edel=$mysqli->query($del);
	
	$bandera = false;
	if(!empty($_POST))
	{

		$fecha1 = date("Y-m-d H:i:s", time());
		$nuc = mysqli_real_escape_string($mysqli,$_POST['nuc']);
		$distrits = $_POST['distrits'];
		$municipio = $_POST['municipio'];
		$fecha_ini = mysqli_real_escape_string($mysqli,$_POST['fecha_ini']);
		$delito = $_POST['delito'];
		$imputado = mysqli_real_escape_string($mysqli,$_POST['imputado']);		
		$relacion_i = $_POST['relacion_i'];		
		$mesa = mysqli_real_escape_string($mysqli,$_POST['mesa']);
		$estado = $_POST['estado'];
		
		$error = '';
		
		$sqlUser = "SELECT id FROM carpeta_inv WHERE nuc = '$nuc'";
		$resultUser=$mysqli->query($sqlUser);
		$rows = $resultUser->num_rows;
		$padresCovid = $_POST['padresCovid'];
		if(empty($padresCovid)){
			$madre_falle_covid = 0;
			$padre_falle_covid = 0;
		} elseif($padresCovid==1) {
			 $madre_falle_covid = 0;
			$padre_falle_covid = 1;
		} elseif($padresCovid==2){
			$madre_falle_covid = 1;
			$padre_falle_covid = 0;
		} else {
			$madre_falle_covid = 1;
			$padre_falle_covid = 1;
		}
		

		if($rows > 0) {
			?>
			<script type="text/javascript">alert('Carpeta ya registrada');</script>
			
			<?php } else {
			
			$sqlNino = "INSERT INTO carpeta_inv (id_caso,nuc,fecha_inicio,distrito,municipio_d,id_delito,imputado,relacion,mesa,estado,respo_estado,fecha_estado,fecha_act,fecha_registro,respo_reg,tipo_pross,fecha_tipo,respo_tipo,asignado,respo_asig,fecha_asig, padre_fallecido_covid, madre_fallecida_covid) 
			VALUES ('$idCaso', '$nuc', '$fecha_ini', '$distrits', '$municipio', '$delito', '$imputado', '$relacion_i', '$mesa', '$estado', '$idDEPTO', '$fecha', '0', '$fecha1', '$idDEPTO', '0', '0', '$idDEPTO','0','0','0', $padre_falle_covid, $madre_falle_covid)";
			$resultNino = $mysqli->query($sqlNino);
			echo $sqlNino;
			if($resultNino>0)
			header("Location: perfil_caso.php?id=$idCaso");
			else
			$error = "Error al Registrar";
			
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
								<h1>Carpeta de investigación</h1>
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
									<div class="4u 12u$(xsmall)">
										<input id="nuc" name="nuc" maxlength="30" type="text" placeholder="NUC"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									
									<div class="4u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="distrits" name="distrits" onchange="red(this);" required>
												<option value="">DISTRITO DE SEGUIMIENTO...</option>
												<?php while ($row=$edis->fetch_assoc()) { ?>						
												<option value="<?php echo $row['id']; ?>"><?php echo $row['distrits']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="4u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="municipio" name="municipio"  required>
												<option value="">MUNICIPIO DEL DELITO...</option>
												<?php 
												
												$mun="SELECT id, municipio from municipios";
												$emun=$mysqli->query($mun);
											
												while ($row=$emun->fetch_assoc()) { ?>						
												<option value="<?php echo $row['id']; ?>"><?php echo $row['municipio']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="6u 12u$(xsmall)">
										<input id="fecha_ini" name="fecha_ini" maxlength="30" type="date"  required>	
									</div>
									<div class="6u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="delito" name="delito" required>
												<option value="">DELITO...</option>
												<?php while ($row=$edel->fetch_assoc()) { ?>						
												<option value="<?php echo $row['id']; ?>"><?php echo $row['delito']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									
									<div class="6u 12u$(xsmall)">
										<input id="imputado" name="imputado" maxlength="100" type="text" placeholder="imputado"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>	
									</div>
																	
									<div class="6u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="relacion_i" name="relacion_i" required>
												<option value="">RELACION CON EL IMPUTADO...</option>
												<option value="MAMÁ">MAMÁ</option>
												<option value="PAPÁ">PAPÁ</option>
												<option value="HERMANO(A)">HERMANO(A)</option>
												<option value="TIO(A)">TIO(A)</option>
												<option value="PRIMO(A)">PRIMO(A)</option>
												<option value="ABUELO(A)">ABUELO(A)</option>
												<option value="VECINO(A)">VECINO(A)</option>
												<option value="DESCONOCIDO">DESCONOCIDO</option>
												<option value="AMIGO(A)">AMIGO(A)</option>
												<option value="COMPAÑERO(A) DE ESCUELA">COMPAÑERO(A) DE ESCUELA</option>
												<option value="CONOCIDO(A)">CONOCIDO(A)</option>
												<option value="MAESTRO(A)">MAESTRO(A)</option>
												<option value="PADRINO">PADRINO</option>
												<option value="MADRINA">MADRINA</option>
												<option value="PADRASTRO">PADRASTRO</option>
												<option value="MADRASTRA">MADRASTRA</option>
												<option value="PAREJA">PAREJA</option>
												<option value="EXPAREJA">EXPAREJA</option>
												<option value="NOVIO">NOVIO</option>
												<option value="EXNOVIO">EXNOVIO</option>
											</select>
										</div>
									</div>
									<div class="4u 12u$(xsmall)">
										<input id="mesa" name="mesa" maxlength="40" type="text" placeholder="mesa"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
								
									<div class="4u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="estado" name="estado" required>
												<option value="">ESTADO ACTUAL...</option>
												<option value="20">INVESTIGACION INICIAL</option>
												<option value="40">INVESTIGACION COMPLEMENTARIA</option>
												<option value="60">INTERMEDIA</option>
												<option value="80">JUICIO</option>
												<option value="100">EJECUCION</option>										
											</select>
										</div>
									</div>
									
									<div class="4u 12u$(xsmall)">
										<input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $fecha; ?>" placeholder="fecha_reg"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
									</div>
									
									</div>
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
		                            </div>
						</div>
				
	<div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_caso.php?id=<?php echo $idCaso; ?>'" >
		</ul>
	</div>
</form>


		<?php if($bandera) { 
			header("Location: perfil_caso.php?id=$idCaso");

			?>
						
			<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>
		
						</div>
					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							<nav id="menu">
								<header class="major">
									<h2>Menú</h2>
								</header>
									<ul><li><a href="welcome.php">Inicio</a></li>				
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
							</nav>	
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
									<p class="copyright">&copy; Sistema DIF Hidalgo </p>
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