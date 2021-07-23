	<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idUsuario = $_SESSION['id'];
	
	$sql = "SELECT personal.id, personal.nombre, tipo_usuario.tipo FROM personal, tipo_usuario WHERE personal.id= '$idUsuario' AND tipo_usuario.id=personal.id_cargo";
	
	$result=$mysqli->query($sql);
	$idCaso=$_GET['id'];

	 if (isset($_POST['agregar'])) {

$idnin = mysqli_real_escape_string($mysqli,$_POST['idnin']);
$validacion="SELECT id FROM nna_caso WHERE id_caso='$idCaso' AND id_nna='$idnin' and estado='E'";
$eje=$mysqli->query($validacion);
$cuenta=$eje->num_rows;

if ($cuenta>0) {
	?>
			<script type="text/javascript">alert('Niño ya agregado en este caso');</script>
			<?php
			 header("Location: ag_nna_caso.php?idCaso=$idCaso");
}else{

$agregar="INSERT INTO nna_caso (id_caso, id_nna, estado) VALUES ('$idCaso', '$idnin', 'E')";

$ejeagregar=$mysqli->query($agregar);


if($ejeagregar>0)
            header("Location: ag_nna_caso.php");
            else
            $error = "Error al Registrar";
}



	 }
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Registrar NNA</title>
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
						<div class="inner"><br><br>
						
			<section id="search" class="alt"><h1>Añadir NNA Exposito</h1>
				<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" name="palabra" id="query" placeholder="Search" />			
				</form>
			</section>
						<div class="table-wrapper">
							<table class="alt">
							<thead>
								<tr><td><b>Folio</b></td>
									<td><b>Fecha de registro</b></td>
									<td><b>Sexo</b></td>
									<td><b>Municipio de detección</b></td>
									<td><b>Servidor que registro</b></td>

									<td></td>
								</tr>
							</thead>
							<tbody>
							<?php
	

	@$buscar = $_POST["palabra"];
	
	$query="SELECT nna_exposito.id, nna_exposito.folio, nna_exposito.fecha_reg, municipios.municipio, departamentos.responsable, nna_exposito.sexo from nna_exposito, departamentos, municipios WHERE municipios.id=nna_exposito.municipio_deteccion and departamentos.id=nna_exposito.respo_reg and nna_exposito.nna_n='0' and (nna_exposito.folio like '%$buscar%' OR nna_exposito.fecha_reg like '%$buscar%' OR municipios.municipio like '%$buscar%' OR nna_exposito.folio like '%$buscar%' OR nna_exposito.sexo like '%$buscar%') limit 20";
	
	$resultado=$mysqli->query($query);
	
 while($row=$resultado->fetch_assoc()){ 
 		
 	?>
								<tr>
									<td><?php echo $row['folio'];?></td>
									<td><?php echo $row['fecha_reg'];?></td>
									<td><?php echo $row['sexo'];?></td>
									<td><?php echo $row['municipio'];?></td>
									<td><?php echo $row['responsable'];?></td>
									<td><form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
										<input type="hidden" name="idnin" value="<?php echo $row['id'] ?>" >
										<?php $idnna=$row['id'] ?>
									<input type="submit" name="agregar" value="Agregar" class="button special fit small" ></form></td>
								</tr>
								<?php } ?>
							</tbody>
							</table>
						</div>
						<div class="6u 12u$(xsmall)">
      						<input name="guardar_datos" class="button special fit" type="button" value="Caso" onclick ="location= 'perfil_caso.php?id=<?php echo $idCaso;?>'" >
      					</div>
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