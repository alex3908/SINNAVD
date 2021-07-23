<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$id_centro=$_GET['id'];
	$fecha= date ("j/n/Y");
	$cas="SELECT c.id, c.nombre, c.titular, c.rfc, c.tipo, c.telefono, c.celular, c.correo1, c.correo2, 
	c.sup, c.const, c.numacta, c.fecha_acta, c.notaria, c.repreL, c.calle, c.cp, e.estado, m.municipio, 
	l.localidad, c.completo from centros c, estados e, municipios m, localidades l where c.id='$id_centro'
	 and c.id_estado=e.id and c.id_mun=m.id and c.id_loc=l.id";
	$ecas=$mysqli->query($cas);

	$total="SELECT id from nna_centros where id_centro='$id_centro'";
	$etotal=$mysqli->query($total);	
	$rows=$etotal->num_rows;

	$vald="SELECT id from departamentos where id='$idDEPTO' and casp='1' 
	and (id_depto='13' OR id_depto='16' OR id_depto='10')";
	$evald=$mysqli->query($vald);
	$rows2=$evald->num_rows;
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Lista</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" 
		/>
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner"> 
		<section id="search" class="alt">
			<?php while ($row=$ecas->fetch_assoc()) { ?>

		<div class="box alt" align="center">
			<div class="row 10% uniform">
				<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
				<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
				<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
			</div>
		</div> 
		<div class="box alt" align="center">
			<div class="row 10% uniform">
			<div class="1u" align="left"><input type="button" class="button small" name="" value="atras" onclick="location='listaxcentro.php?id=<?php echo $id_centro; ?>'"></div>
			<div class="8u" align="left"><h2>NNA albergados en <?php echo $row['nombre']; ?></h2></div>
			<?php if ($rows2>0) { ?>
			<div class="3u" align="left"><input type="button" class="button fit" name="" value="Ingresar" onclick="location='ingresa.php?id=<?php echo $id_centro; ?>'"></div>
			</div>
			<?php } ?>
		</div> 
		
				
			</section>
						<?php } ?>
				
				<table>			
				<tr>
					<td><b>Folio</b></td>
					<td><b>Nombre</b></td>
					<td><b>Fecha de ingreso</b></td>
					<td><b>Motivo</b></td>
					<td></td>
					
				</tr>
				<tbody>
				<?php	
						$query="SELECT nna.folio, nna.id, nna.nombre, nna.apellido_p, nna.apellido_m, 
						date_format(nna_centros.fecha_ing,'%d/%m/%Y') as fecha_ing, nna_centros.motivo
						from departamentos inner join  nna_centros on  nna_centros.respo_reg=departamentos.id
						inner join nna on nna_centros.id_nna=nna.id 
						where nna_centros.id_centro='$id_centro' and nna_centros.activo='1'";
						$resultado=$mysqli->query($query);
						$rows2=$resultado->num_rows;
						echo "Resultados: ".$rows2;
 					while($row=$resultado->fetch_assoc()){ ?>
					
				<tr>
					<td><a href="perfil_nna.php?id=<?php echo $row['id']; ?>"><strong><?php echo $row['folio'];?></strong></a></td>
					<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
					<td><?php echo $row['fecha_ing'] ?></td>
					<td><?php echo $row['motivo'];?></td>
					<td><input type="button" class="button small" name="" value="VER" onclick="location='perfil_ingreso.php?id=<?php echo $row['id']; ?>'"></td>
					
				</tr>
					<?php } ?>
				</tbody>
			</table>	




			<?php if(@$bandera) { 
			header("Location: welcome.php");

			?>	<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>

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
										
										<li><span class="opener">Usuarios</span>
											<ul>
												<li><a href="registro_usuarios.php">Alta</a></li>
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" >Cerrar sesión</a></li>
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
												
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" >Cerrar sesión</a></li>
									</ul>
								</nav>						
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
										
										<li><span class="opener">Usuarios</span>
											<ul>
												
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" >Cerrar sesión</a></li>
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