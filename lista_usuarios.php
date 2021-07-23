<?php
	ob_start();
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	$fec= date("Y-m-d H:i:s");
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$sql = "SELECT id, responsable, id_personal FROM departamentos WHERE id= '$idDEPTO'";	
	$result=$mysqli->query($sql);

	$cuenta="SELECT count(id) as total from usuarios";
	$eje=$mysqli->query($cuenta);
	
	while ($row=$result->fetch_assoc()) {
		$cargo=$row['id_personal'];
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Lista</title>
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
						<div class="inner"><br> <br> 
			<div class="box alt" align="center">
				<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
				</div>
			</div>
			<table class="alt">
				<thead>
					<tr>
						<td><h1>Usuarios</h1></td>
						<td><?php if ($_SESSION['departamento']==16 or $_SESSION['departamento']==7 or $cargo=='5') { ?>
							<input type="button" value="ALTA" class="button special" onclick="location='registro_usuarios.php'">
						<?php } ?></td>
					</tr>
					<tr>
						<td colspan="2"><form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" name="palabra" id="query" style="text-transform:uppercase;" placeholder="Nombre o número de usuario" />
						<?php while ($row=$eje->fetch_assoc()) { ?><h4>Total de usuarios: <?php echo $row['total'];?></h4><?php } ?></form></td>
					</tr>
				</thead>
			</table>
			
				<div class="table-wrapper">
					<table class="alt">
						<thead>
							<tr>
								<td><b>FOLIO</b></td>
								<td><b>NOMBRE</b></td>
								<td><b>EDAD</b></td>
							</tr>
						</thead>
						<tbody>
	<?php @$buscar = mysqli_real_escape_string($mysqli, $_POST["palabra"]);

	if (empty($buscar)) {
		$query="SELECT id, nombre, apellido_p,apellido_m, fecha_nacimiento as fecha_nac FROM usuarios  order by id desc limit 20 ";
	}else {
		$trozos=explode(" ", $buscar);
		$numPal=count($trozos);
		if($numPal>1){
			$query="SELECT id, match(nombre, apellido_p, apellido_m) against ('$buscar')as p, nombre,apellido_p,apellido_m, fecha_nacimiento as fecha_nac FROM usuarios where match(nombre, apellido_p, apellido_m) against ('$buscar') and match(nombre, apellido_p, apellido_m) against ('$buscar')>4 ORDER BY p desc ";	
		} else {
			$query="SELECT id, nombre, apellido_p, apellido_m, fecha_nacimiento as fecha_nac FROM usuarios where  id like '%$buscar%' or nombre like '%$buscar%' or apellido_p like '%$buscar%' or apellido_m like '%$buscar%'";	
		}
		
	}	
	$resultado=$mysqli->query($query);
	$rows=$resultado->num_rows;
	echo "Resultados: ".$rows;
 	while($row=$resultado->fetch_assoc()){ ?>
				<tr>
					<td style="text-transform:uppercase;"><a href="perfil_usuarios.php?id=<?php echo $row['id'];?>"><?php echo $row['id']; ?></a></td>
					<td style="text-transform:uppercase;"><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
					<td style="text-transform:uppercase;"><?php 
					$fecha_nacimiento= $row['fecha_nac'];	
										if($fecha_nacimiento=='1900-01-01' or empty($fecha_nacimiento))	
											$edad="Sin registro"; 
										else {
											$anioN=date('Y', strtotime($fecha_nacimiento));  //calcular edad
				         					$anioA=date('Y', strtotime($fec));
				         					$mesN=date('m', strtotime($fecha_nacimiento));
				         					$mesA=date('m', strtotime($fec));
				         					$diaN=date('d', strtotime($fecha_nacimiento));
				         					$diaA=date('d', strtotime($fec));
				         					if(($mesN<$mesA) or ($mesN==$mesA and $diaN<=$diaA)){
				         					    $anios=$anioA-$anioN;
				         					    $meses=$mesA-$mesN;	
				         					    if($anios==1)
				         					    	$cadAnio=" año, ";
				         					    else
				         					    	$cadAnio=" años, ";
				         					    if ($meses==1)
				         					    	$cadMes= " mes";
				         					    else 
				         					    	$cadMes=" meses";	         					    
				         					} else {
				         					    $anios=$anioA-$anioN-1; 
				         					    $meses=12-($mesN-$mesA);
				         					    if($anios==1)
				         					    	$cadAnio=" año, ";
				         					    else
				         					    	$cadAnio=" años, ";
				         					    if ($meses==1)
				         					    	$cadMes= " mes";
				         					    else 
				         					    	$cadMes=" meses";	
				         					}
				         					$edad= $anios.$cadAnio.$meses.$cadMes;
			         					} 
			         					echo $edad; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
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