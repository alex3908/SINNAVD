	<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$sql = "SELECT id, responsable, id_depto, id_personal FROM departamentos WHERE id= '$idDEPTO'";
	$result=$mysqli->query($sql);
	$result2=$mysqli->query($sql);

	@$buscar = $_POST['palabra'];

	while ($row=$result->fetch_assoc()) {
		$mi=$row['id_depto'];
		$cargo=$row['id_personal'];
		$nom=$row['responsable']; 
	 //unidad
}
	
$listaBeneficiariosNull="SELECT visitas_unidad.id, visitas_unidad.id_benef, visitas_unidad.responsable, visitas_unidad.tipo, visitas_unidad.asunto, visitas_unidad.fecha, benef_unidad.folio, benef_unidad.nombre, benef_unidad.apellido_p, benef_unidad.apellido_m, cat_asuntos_unidad.asunto FROM visitas_unidad, benef_unidad, cat_asuntos_unidad WHERE visitas_unidad.id_benef=benef_unidad.id && visitas_unidad.asunto=cat_asuntos_unidad.id";
	$rBeneficiariosNull=$mysqli->query($listaBeneficiariosNull);

	
$listaBeneficiariosNull2="SELECT visitas_unidad.id, visitas_unidad.id_benef, visitas_unidad.responsable, visitas_unidad.tipo, visitas_unidad.asunto, visitas_unidad.fecha, benef_unidad.folio, benef_unidad.nombre, benef_unidad.apellido_p, benef_unidad.apellido_m, cat_asuntos_unidad.asunto FROM visitas_unidad, benef_unidad, cat_asuntos_unidad WHERE visitas_unidad.id_benef=benef_unidad.id && visitas_unidad.asunto=cat_asuntos_unidad.id";
	$rBeneficiariosNull2=$mysqli->query($listaBeneficiariosNull2);


$par="SELECT departamentos.id from departamentos, depto, personal where departamentos.id_personal=personal.id and departamentos.id_depto=depto.id and departamentos.id_depto='10' and departamentos.id_personal='3' and departamentos.id='$idDEPTO'";
$epar=$mysqli->query($par);
$eparrows=$epar->num_rows;

$car="SELECT personal FROM personal WHERE id='$cargo'";
		$care=$mysqli->query($car);

$cuenta="SELECT count(id) as cuenta, count(fecha_salida) as cuenta2, count(atencion_brindada) as cuenta3 FROM historial";
$ejecuenta=$mysqli->query($cuenta);

$cuenta2="SELECT count(id) as cuenta FROM historial WHERE fecha_salida is null";
$ejecuenta2=$mysqli->query($cuenta2);

$cuentaUnidad="SELECT count(id) as cuentaUnidad from visitas_unidad";
$QcuentaUnidad=$mysqli->query($cuentaUnidad);

$reportes="SELECT count(id) as cuenta from reportes_int where estado='0'";
$ereportes=$mysqli->query($reportes);
while ($row=$ereportes->fetch_assoc()) {
	$pendi=$row['cuenta'];
}
$reportesvd="SELECT id from reportes_vd where atendido='1' and (asignado='$idDEPTO' or asignado_psic='$idDEPTO')";
$erepo=$mysqli->query($reportesvd);
$wow=$erepo->num_rows;
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Visitas a UIENNAVD</title>
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
							<br> <br> 
			<div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="3u"><img src="images/crece.jpg" width="80px" height="75px" /></div>
					<div class="3u"><img src="images/dif.jpg" width="45px" height="75px" onclick="location='new.php'"/></div>
					<div class="3u"><img src="images/armas.jpg" width="80px" height="80px" onclick="location='reporte1ra.php'" /></div>
					<div class="3u"><img src="images/logo.png" width="120px" height="78px"></div>
				</div>
				
			</div> 	
							<h2 align="center">Historial de visitas del UIENNAVD</h2>
							
								<div class="box">
																		
									<div class="table-wrapper"><h4>Visitas</h4>
									<table class="alt">
									
										<thead>
											<tr>
												<th>FOLIO</th>
												<th>NOMBRE</th>
												<th>RESPONSABLE</th>
												<th>TIPO</th>
												<th>ASUNTO</th>
												<th>FECHA DE INGRESO</th>
												
											</tr>
										</thead>
                                        <tbody>
                                        <?php while($row=$rBeneficiariosNull2->fetch_assoc()){ ?>
											
											<tr>
											<td><?php echo $row['folio'];?></td>
												<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
												<td><?php echo $row['responsable'];?></td>
												<td><?php echo $row['tipo']; ?></td>
												
												<td><?php echo $row['asunto'];?></td>
												
												<td><?php echo $row['fecha'];?></td>
												
												
											</tr>
							<?PHP } ?>
                                        
                                        </tbody>
 
                                     </table>
                                     
								</div>
								</div>
							<br>
					
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
										<li class="fa-envelope-o">laura.ramirez@hidalgo.gob.mx</li>
										<li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
										<li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
										<li class="fa-phone"><a href="directorio.php">Directorio interno</a></li>
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