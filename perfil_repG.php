<?php 
	
	session_start();
	require 'conexion.php';
	$idDEPTO = $_SESSION['id'];
	$id=$_GET['id'];
	
	$fecha=date("j/n/Y");
	$cuadro="SELECT reporte_guardia.id, reporte_guardia.folio, reporte_guardia.fecha_ini, reporte_guardia.fecha_fin, departamentos.responsable from reporte_guardia, departamentos where reporte_guardia.respo_reg=departamentos.id and reporte_guardia.id='$id'";
	$ecuadro=$mysqli->query($cuadro);

	$val="SELECT id, envio from reporte_guardia where respo_reg='$idDEPTO' and id='$id'";
	$eval=$mysqli->query($val);
	$val1="SELECT id, envio from reporte_guardia where id='$id'";
	$eval1=$mysqli->query($val1);
	while ($row=$eval1->fetch_assoc()) {
		$envio=$row['envio'];
	}
	$neval=$eval->num_rows;

	$segui="SELECT carpeta_inv.id, carpetas_guardia.id_guardia, carpeta_inv.nuc, casos.folio_c, carpeta_inv.fecha_ini, departamentos.responsable, carpeta_inv.asignado from departamentos, carpeta_inv, carpetas_guardia, casos where carpetas_guardia.id_guardia='$id' and carpeta_inv.id=carpetas_guardia.id_carpeta and departamentos.id=carpeta_inv.respo_reg and casos.id=carpeta_inv.id_caso";
	$esegui=$mysqli->query($segui);
	$numt=$esegui->num_rows;


if(!empty($_POST['CER']))
	{	
		$idP=mysqli_real_escape_string($mysqli,$_POST['idd']);
		$sql="UPDATE reporte_guardia set envio='1', fecha_envio='$fecha' where id='$idP'";
		$esql=$mysqli->query($sql);
		header("location:perfil_repG.php?id=$idP");
	
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
							<br> <br> 

		<div class="uniform row">
			<div class="6u 12u$(small)">
			<input class="button fit" type="button" name="cancelar" value="regresar" onclick="location='lista_guardias.php'" >
			</div>
			<div class="6u 12u$(small)">
			<form id="reporteee" name="reporteee" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
			<?php if ($envio=='0') { ?>
				<?php if ($neval>0) { ?>
					<?php if ($numt>0) {  ?>
						<input type="submit" class="button special fit" name="CER" value="cerrar y enviar reporte" >
					<?php }else { ?>
						<input type="submit" class="button special fit" name="" value="cerrar y enviar reporte" disabled>
					<?php } ?>
				<?php }else { ?>
						<input type="submit" class="button special fit" name="CER" value="cerrar y enviar reporte"  disabled>					
				<?php } ?>					
			<?php }else if($envio='1'){ ?>
					<input type="submit" class="button special fit" name="" value="reporte enviado" disabled>
			<?php } ?>
			<input type="hidden" name="idd" value="<?php echo $id; ?>">
			</form>
			</div>
				<div class="12u$">
				<div class="box">			
					<div class="uniform row">
						<?php while ($row=$ecuadro->fetch_assoc()) { ?>							
							<div class="4u 12u$(xsmall)"><strong>Folio de reporte: </strong><?php echo $row['folio'];?></div>
							<div class="4u 12u$(xsmall)">	<strong>Fecha inicio: </strong><?php echo $row['fecha_ini'];?></div>
							<div class="4u 12u$(xsmall)">	<strong>Fecha fin: </strong><?php echo$fecha=$row['fecha_fin']; ?></div>
						<?php } ?>	
						</div>							
				
							<br>
				<div class="12u$">
				
					<div class="table-wrapper">
						<table class="alt">

						<thead>
							<tr>
								<td colspan="2">CARPETAS INICIADAS EN LA GUARDIA</td>
								<td>Total:<?php echo $numt; ?></td>
							</tr>
						</thead>
							<thead>
								<tr>
									<th>NUC</th>
									<th>CASO</th>
									<th>FECHA DE INICIO</th>
									<th>RESPONSABLE DE REGISTRO</th>

								</tr>
							</thead>
							<tbody>
							<?php while ($row=$esegui->fetch_assoc()) { ?>
								<tr>
									<td><a href="perfil_carpeta.php?id=<?php echo $row['id']; ?>"><?php echo $row['nuc'];?></a></td>
									<td><?php echo $row['folio_c'];?></td>
									<td><?php echo $row['fecha_ini'];?></td>
									<td><?php echo $row['responsable'];?></td>
									<td><?php $as=$row['asignado'];
									if ($as=='0') {
										echo "NO ASIGNADA";	
									 }else {
									 	echo "ASIGNADA";
									 	} ?></td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
			</div>
			</div>
			</div>
		
				<div class="12u$">
				
					<div class="box">			
						<strong>CARPETAS<br>Buscar carpetas por NUC o por CAP</strong>
					<form id="buscador" name="buscador" method="post" action="perfil_repG.php?id=<?php echo $id; ?>" onSubmit="return validarForm(this)">
						
						<input type="search" name="palabra" id="query" placeholder="Search" />
						<br>
						
							
							<?php
	@$buscar = $_POST["palabra"];
	
	
		$query="SELECT carpeta_inv.id, carpeta_inv.nuc, casos.folio_c, carpeta_inv.fecha_ini, departamentos.responsable from carpeta_inv, departamentos, casos where carpeta_inv.respo_reg=departamentos.id and casos.id=carpeta_inv.id_caso and ( carpeta_inv.nuc like '%$buscar%' OR casos.folio_c like '%$buscar%' OR carpeta_inv.fecha_ini like '%$buscar%' OR departamentos.responsable like '%$buscar%') ";

	$resultado=$mysqli->query($query); 
	
		if (empty($buscar)) {
			
		}else {
	?>
			<div class="table-wrapper">
						<table class="alt">
						<thead>
								<tr>
									<th>NUC</th>
									<th>CASO</th>
									<th>Fecha de inicio</th>
									<th>Responsable de registro</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
							<?php while ($row=$resultado->fetch_assoc()) { ?>
								<tr>
									<td><?php echo $row['nuc'];?></td>
									<td><?php echo $row['folio_c'];?></td>
									<td><?php echo $row['fecha_ini'];?></td>
									<td><?php echo $row['responsable'];?></td>						
	<td><input type="button" name="agCAP" value="AÑADIR" class="button special fit small" onclick="location='alimenta-reporte-guardia.php?idC=<?php echo $row['id']; ?>&idR=<?php echo $id; ?>'">
									<input type="hidden" name="idCAR" id="idCAR" value="<?php echo $row['id']; ?>" ></td>
									
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
	<?php } ?>
						</form>	
					</div>
				
			</div>
		
			
			
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