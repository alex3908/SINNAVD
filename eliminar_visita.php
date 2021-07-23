<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$sql = "SELECT id, responsable, id_depto, id_personal FROM departamentos WHERE id= '$idDEPTO'";
	
	
	$result=$mysqli->query($sql);

	@$buscar = $_POST['palabra'];

	while ($row=$result->fetch_assoc()) {
		$mi=$row['id_depto'];
		$cargo=$row['id_personal'];
		$nom=$row['responsable'];

		

	if ($cargo==2 OR $cargo==1) {
		$listaUsuarios="SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.atencion_brindada, historial.fecha_ingreso, historial.fecha_salida, historial.responsable FROM historial, usuarios, depto WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.hora_salida is not null AND (usuarios.nombre like '%$buscar%' OR usuarios.apellido_p like '%$buscar%' OR usuarios.apellido_m like '%$buscar%' OR historial.responsable like '%$buscar%' OR historial.fecha_ingreso like '%$buscar%') ORDER BY historial.fecha_ingreso DESC LIMIT 20 ";
	$rUsuarios=$mysqli->query($listaUsuarios);

	$listaUsuariosNull="SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, historial.responsable FROM historial, usuarios, depto WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.fecha_salida is null";
	$rUsuariosNull=$mysqli->query($listaUsuariosNull);
	}else if($cargo==3){

	$listaUsuarios="SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.atencion_brindada, historial.fecha_ingreso, historial.fecha_salida, historial.responsable FROM historial, usuarios, depto WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.id_departamento=$mi && historial.fecha_salida is not null AND (usuarios.nombre like '%$buscar%' OR usuarios.apellido_p like '%$buscar%' OR usuarios.apellido_m like '%$buscar%' OR historial.responsable like '%$buscar%' OR historial.fecha_ingreso like '%$buscar%') ORDER BY historial.fecha_ingreso DESC LIMIT 20";
	$rUsuarios=$mysqli->query($listaUsuarios);
$listaUsuariosNull="SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, historial.responsable FROM historial, usuarios, depto WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.id_departamento=$mi && historial.fecha_salida is null";
	$rUsuariosNull=$mysqli->query($listaUsuariosNull);

}else {
	$listaUsuarios="SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, historial.responsable FROM historial, usuarios, depto WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.id_departamento=$mi && historial.fecha_salida is not null AND (usuarios.nombre like '%$buscar%' OR usuarios.apellido_p like '%$buscar%' OR usuarios.apellido_m like '%$buscar%' OR historial.responsable like '%$buscar%' OR historial.fecha_ingreso like '%$buscar%') ORDER BY historial.fecha_ingreso DESC LIMIT 20 ";
	$rUsuarios=$mysqli->query($listaUsuarios);
$listaUsuariosNull="SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, historial.responsable FROM historial, usuarios, depto WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.id_departamento=$mi && historial.fecha_salida is null AND ( historial.responsable='$nom' OR historial.responsable is null)";
	$rUsuariosNull=$mysqli->query($listaUsuariosNull);
}

$car="SELECT personal FROM personal WHERE id='$cargo'";
		$care=$mysqli->query($car);
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
		</div></div> <br>				
							
							<h3 align="center">BIENVENIDO(A):  <?php  echo $row['responsable']." ";} ?><br><?php while ($row=$care->fetch_assoc()) {
								echo $row['personal'];
							} ?></h3>

							
								<div class="box">
								<div class="table-wrapper"><h4>Pendientes</h4>
									<table class="alt">
									
										<thead>
											<tr>
												<th>FOLIO</th>
												<th>NOMBRE</th>
												<th>DEPARTAMENTO</th>
												<th>RESPONSABLE</th>
												<th>ASUNTO</th>
												
												<th>FECHA DE INGRESO</th>
												<th></th>
												
											</tr>
										</thead>
										<tbody><?php while($row=$rUsuariosNull->fetch_assoc()){ $res=$row['responsable']; ?>
											<tr>
											<td><?php echo $row['id_usuario'];?></td>
												<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
												<td><?php echo $row['departamento'];?></td>
												<td><?php echo $row['responsable'];?></td>
												<td><?php echo $row['asunto'];?></td>
												
												<td><?php echo $row['fecha_ingreso'];?></td>
												
												<?php
													
												 if ($mi==7 OR $mi==16) { ?>
												 		
												 			<td><input type="submit" class="button special fit small" name="Terminar visita" value="Eliminar visita" onclick="location='drop.php?id=<?php echo $row['id'];?>'"></td>

												 <?php  }?>
											</tr>
							<?PHP } ?>
										</tbody>
									</table>
								</div></div>
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