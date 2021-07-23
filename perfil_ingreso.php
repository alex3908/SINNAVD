<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$idNNA = $_GET['id'];

	$vald="SELECT id from departamentos where id='$idDEPTO' and (id_depto='13' OR id_depto='16') 
	or (id_depto='10' and casp='1')";
	$evald=$mysqli->query($vald);

$ingreso="SELECT centros.id as idC, nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, 
centros.nombre as nomC, date_format(nna_centros.fecha_ing, '%d/%m/%Y') as fecha_ing, nna_centros.motivo, nna_centros.cuidado_procu, 
nna_centros.nna_estado, nna_centros.nna_calle, nna_centros.nna_actaD, nna_centros.nna_curpD, 
nna_centros.nna_consAD, nna_centros.nombreG, nna_centros.apellido_pG, nna_centros.apellido_mG, 
nna_centros.parentescoG, nna_centros.tel1G, nna_centros.tel2G, nna_centros.correoG, nna_centros.estadoG, 
nna_centros.calleG, nna_centros.nombreT, nna_centros.apellido_pT, nna_centros.apellido_mT, 
nna_centros.parentescoT, nna_centros.tel1T, nna_centros.tel2T, nna_centros.correoT, nna_centros.situacionJ 
from centros, nna_centros, nna where centros.id=nna_centros.id_centro and nna_centros.id_nna='$idNNA' 
and nna_centros.id_nna=nna.id";
$eingreso=$mysqli->query($ingreso);

$vald="SELECT id from departamentos where id='$idDEPTO' and casp='1' and (id_depto='13' OR id_depto='16' OR id_depto='10')";
	$evald=$mysqli->query($vald);
	$rows2=$evald->num_rows;
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Perfil ingreso</title>
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

						<div class="inner"><br> <br> 
		<div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
			</div>
		</div> <br>	
									
					<div class="row uniform">
						<?php while($row=$eingreso->fetch_assoc()){ ?>
						<div class="1u">
							<input type="button" class="button small" value="ATRAS" onclick="location='nnaENcas.php?id=<?php echo $row['idC']; ?>'">
						</div>
						<div class="8u">
						
							<h2><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m'];  ?> </h2>
						</div>
						<?php if ($rows2>0) { ?>
						<div class="3u">
							<input type="button" class="button special small fit" onclick="location='egreso_cas.php?id=<?php echo $idNNA; ?>'" value="egresar">
						</div>
						<?php } ?>
							<div class="12u 12u$(xsmall)">
								
								<div class="row uniform">
									<div class="5u">
										<strong>Centro al que ingreso: </strong><?php echo $row['nomC']; ?>
										<br>
										<strong>Fecha de ingreso: </strong><?php echo $row['fecha_ing']; ?>
										<br>
										
									</div>
									<div class="7u">
										<strong>Motivo del ingreso: </strong><?php echo $row['motivo']; ?>
										<br>
										<strong>Bajo cuidado de Procuraduría: </strong><?php echo $row['cuidado_procu']; ?>
										<br>

									</div>
									<div class="12u">
										<strong>Documentos de identidad del NNA</strong>
										<ul class="pagination">
											<?php $ac=$row['nna_actaD']; $cu=$row['nna_curpD']; $co=$row['nna_consAD'];
											if ($ac=='SI') { ?>
												<li><a class="page active">Acta de nacimiento</a></li>
											<?php }else {} if ($cu=='SI') { ?>
												<li><a class="page active">CURP</a></li>
											<?php }else {} if ($co=='SI') { ?>
												<li><a class="page active">Constancia de alumbramiento</a></li>
											<?php } ?>									
									</ul>
									</div>
									
								</div>
							</div>
							<div class="4u 12u$(xsmall)">
							<ul class="alt">
								<div class="box"><strong>Ultimo domicilio del NNA antes del ingreso</strong>
								<li><strong>Estado: </strong><?php echo $row['nna_estado'];  ?> </li>
								<li><strong>Calle: </strong><?php echo $row['nna_calle'];  ?> </li><br>
								<strong>Situación Juridica</strong>
								<li><?php echo $row['situacionJ']; ?></li>
								</div>
							</ul>
							</div>						
							<div class="4u 12u$(xsmall)">
							<ul class="alt">
								<div class="box"><strong>Persona que ejerce la guardia o custodia</strong> 
								<li><strong>Nombre: </strong><?php echo $row['nombreG'].' '.$row['apellido_pG'].' '.$row['apellido_mG']; ?> </li>
								<li><strong>Parentesco: </strong><?php echo $row['parentescoG']; ?> </li>
								<li><strong>Teléfono 1: </strong><?php echo $row['tel1G'];  ?> </li>		
								<li><strong>Teléfono 2: </strong><?php echo $row['tel2G'];  ?> </li>
								<li><strong>Correo: </strong><?php echo $row['correoG'];  ?> </li>			
								<li><strong>Estado: </strong><?php echo $row['estadoG'];  ?> </li>			
								<li><strong>Calle: </strong><?php echo $row['calleG'];  ?> </li>			
								</div>
							</ul>
							</div>
							<div class="4u 12u$(xsmall)">
							<ul class="alt">
								<div class="box"><strong>Persona que ejerce la tutela</strong> 
								<li><strong>Nombre: </strong><?php echo $row['nombreT'].' '.$row['apellido_pT'].' '.$row['apellido_mT']; ?> </li>
								<li><strong>Parentesco: </strong><?php echo $row['parentescoT'];  ?> </li>
								<li><strong>Teléfono 1: </strong><?php echo $row['tel1T'];  ?> </li>
								<li><strong>Teléfono 2: </strong><?php echo $row['tel2T'];  ?> </li>
								<li><strong>Correo: </strong><?php echo $row['correoT'];  ?> </li>
								</div>
							</ul>
							</div>
						<?php } ?>
					</div>
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
										<li><a href="logout.php" >Cerrar sesión</a></li>
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