<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}

$s="SELECT id from atenciones_nna";
$es=$mysqli->query($s);
$rows=$es->num_rows;

?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Historial</title>
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

		</div></div> <br>	


		<h2 align="center">Historial</h2>
		<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" style="text-transform:uppercase;" name="palabra" id="query" placeholder="BUSCAR POR FOLIO..." />
						<h3>Atenciones registradas:<?php echo $rows; ?></h3>
						
				</form>
				
				<div class="box">
								<div class="table-wrapper">
									<table class="alt">
									
										<thead>
											<tr>
												
												<th>FOLIO</th>
												<th>NOMBRE</th>
												<th>DEPARTAMENTO</th>
												<th>RESPONSABLE</th>
												<th>ATENCION</th>
												<th>FECHA</th>
												
												
											</tr>
										</thead>
										<tbody>
											<?php	@$buscar = $_POST["palabra"];
					if (empty($buscar)) {

						$query="SELECT nna.folio, nna.id, nna.nombre, nna.apellido_p, nna.apellido_m, atenciones_nna.id_caso, depto.departamento, departamentos.responsable, brindadas.brindada, atenciones_nna.fecha from nna, atenciones_nna, brindadas, depto, departamentos where atenciones_nna.id_nna=nna.id and atenciones_nna.id_depto=depto.id and atenciones_nna.respo_reg=departamentos.id and atenciones_nna.tipo_ate=brindadas.id and (nna.folio like '%$buscar%' or nna.nombre like '%$buscar%' or nna.apellido_p like '%$buscar%' or nna.apellido_m like '%$buscar%' or departamentos.responsable like '%$buscar%' or brindadas.brindada like '%$buscar%') limit 20";
					}else {
						$query="SELECT nna.folio, nna.id, nna.nombre, nna.apellido_p, nna.apellido_m, atenciones_nna.id_caso, depto.departamento, departamentos.responsable, brindadas.brindada, atenciones_nna.fecha from nna, atenciones_nna, brindadas, depto, departamentos where atenciones_nna.id_nna=nna.id and atenciones_nna.id_depto=depto.id and atenciones_nna.respo_reg=departamentos.id and atenciones_nna.tipo_ate=brindadas.id and (nna.folio like '%$buscar%' or nna.nombre like '%$buscar%' or nna.apellido_p like '%$buscar%' or nna.apellido_m like '%$buscar%' or departamentos.responsable like '%$buscar%' or brindadas.brindada like '%$buscar%')";
					}
					
						$resultado=$mysqli->query($query);
						$row2=$resultado->num_rows;
						echo 'Resultados: '.$row2;
 					while($row=$resultado->fetch_assoc()){ 
 						?>

										
											<tr>
												<td><a href="perfil_nna.php?id=<?php echo $row['id']; ?>"><?php echo $row['folio'];?></a></td>
												<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>							
												<td><?php echo $row['departamento'];?></td>
												<td><?php echo $row['responsable'];?></td>							
												<td><?php echo $row['brindada'];?></td>								
												<td><?php echo $row['fecha'];?>
												
											</tr>
							<?PHP } ?>
										</tbody>
									</table>
								</div></div>
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
									<ul><li><a href="welcome.php">Inicio</a></li>
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
									<ul><li><a href="welcome.php">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												<li><a href="registro_personal.php">Alta</a></li>
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
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												
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