<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];
	$id_cas=$_GET['id'];	
	$fecha= date ("j/n/Y");
	$limFec= date("Y-j-n");

	$xcas="SELECT nombre from centros where id='$id_cas'";
	$excas=$mysqli->query($xcas);
	while ($row=$excas->fetch_assoc()) {
		$nomC=$row['nombre'];
	}

	$lmotivo="SELECT id, motivo from motivoscas";
	$elmotivo=$mysqli->query($lmotivo);

	$lautoridad="SELECT id, autoridad from autoridadescas";
	$elautoridad=$mysqli->query($lautoridad);


	if(!empty($_POST))
	{
		
		$fecha_sup = mysqli_real_escape_string($mysqli,$_POST['fecha']);
		$motivo = $_POST['motivo'];
		$cargo = mysqli_real_escape_string($mysqli,$_POST['cargo']);		
		$nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);		
		$ap_paterno = mysqli_real_escape_string($mysqli,$_POST['ap_paterno']);		
		$ap_materno = mysqli_real_escape_string($mysqli,$_POST['ap_materno']);		
		$identi = mysqli_real_escape_string($mysqli,$_POST['identi']);		
		$mat = $_POST['Mat'];

		$pma="SELECT max(id) from supervisiones";
		$epma=$mysqli->query($pma);
		while ($row=$epma->fetch_assoc()) {
			$mid=$row['max(id)'];
		}
		$mid=$mid+1;
		$folio='SPC0'.$mid;
		
		if (empty($_POST['autoridad'])) {
			echo "Seleccione al menos una autoridad";
		}else {
			$sql="INSERT INTO supervisiones (folio, id_centro, fecha_sup, id_motivo, cargo, nombre, ap_paterno, ap_materno, t_identificacion, material, fecha_reg, respo_reg) VALUES ('$folio','$id_cas','$fecha_sup','$motivo','$cargo','$nombre','$ap_paterno','$ap_materno','$identi','$mat','$fecha','$idDEPTO')";
			$esql=$mysqli->query($sql);
			if ($esql>0) {
			$pid="SELECT max(id) from supervisiones";
			$epid=$mysqli->query($pid);
			while ($row=$epid->fetch_assoc()) {
				$idSup=$row['max(id)'];
			}
			foreach((array)@$_POST["autoridad"] as $valor){ 
				$regaut="INSERT INTO autoridad_sup (id_autoridad, id_supervision) VALUES ('$valor','$idSup')";
				$eregaut=$mysqli->query($regaut);
			}
			if ($eregaut>0) {
				header("Location: perfil_supervision.php?id=$idSup");
			}
		} else { ?>
			<script type="text/javascript">
				alert('Error al registrar');
			</script>
			
		<?php echo $sql; }
		}
		
	}			
	
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Supervisión</title>
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
								<h1>Visita de supervisión</h1>
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
									<div class="6u 12u$(xsmall)">Centro:
										<input id="cas" name="cas" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled value="<?php echo $nomC; ?>">
									</div>
									<div class="3u 12u$(xsmall)">Fecha:
										<input id="fecha" name="fecha" type="date"  placeholder="DD/MM/AAAA" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="3u 12u$(xsmall)">Motivo: 
										<div class="select-wrapper">
											<select id="motivo" name="motivo" required>
												<?php while ($row=$elmotivo->fetch_assoc()) { ?>
													<option value="<?php echo $row['id']; ?>"><?php echo $row['motivo']; ?></option>
												<?php } ?>
												
											</select>
										</div>
									</div>
									<div class="12u 12u$(small)">
									<div class="box">
										AUTORIDADES RESPONSABLES DE REALIZAR LA VISITA
										<div class="row uniform">
											<?php while ($row=$elautoridad->fetch_assoc()) { ?>						
											<div class="6u 12u$(xsmall)">
												<input type="checkbox" id="<?php echo $row['id']; ?>" name="autoridad[]" value="<?php echo $row['id'];?>" >
												<label for="<?php echo $row['id']; ?>"><?php echo $row['autoridad'];?></label>
											</div>
											<?php } ?>
										</div>										
									</div>
									</div>
									<div class="12u">
										<div class="box">Personal del CAS que atendió la visita
											<div class="row uniform">
											<div class="2u">
												<input type="text" name="cargo" placeholder="CARGO" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
											</div>
											<div class="3u">
												<input type="text" name="nombre" placeholder="NOMBRE(S)" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
											</div>
											<div class="2u">
												<input type="text" name="ap_paterno" placeholder="ap. paterno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
											</div>
											<div class="2u">
												<input type="text" name="ap_materno" placeholder="ap. materno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
											</div>
											<div class="3u">
												<input type="text" name="identi" placeholder="TIPO DE IDENTIFICACION" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
											</div>
											</div>
										</div>
									</div>
									<div class="6u">
										¿Cuenta con material soporte de la visita?
											<input type="radio" id="siMat" value="SI" name="Mat" checked>
											<label for="siMat">SI</label>
															
											<input type="radio" id="noMat" value="NO" name="Mat">
											<label for="noMat">NO</label>													
									</div>
									
								</div>								
							</div>
				
						<div class="12u$">
							<ul class="actions">
								<input class="button special fit" name="registar" type="submit" value="Registrar" >
								<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='lista_supervisionCAS.php?id=<?php echo $id_cas; ?>'" >
							</ul>
						</div>
					</form>
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
										<li><a href="logout.php">Cerrar sesión</a></li>
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