<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idcentro=$_GET['id'];
	$sql="SELECT nombre from centros where id='$idcentro'";
	$esql=$mysqli->query($sql);
	while ($row=$esql->fetch_assoc()) {
		$nomCas=$row['nombre'];
	}
	$fecha= date ("j/n/Y");
	$bandera = false;
	if(!empty($_POST))
	{	
		@$a=$_POST['a'];
		@$b=$_POST['b'];
		@$c=$_POST['c'];
		@$d=$_POST['d'];
		@$e=$_POST['e'];
		@$f=$_POST['f'];
		@$g=$_POST['g'];
		@$h=$_POST['h'];
		@$i=$_POST['i'];
		@$j=$_POST['j'];
		@$k=$_POST['k'];
		$min = mysqli_real_escape_string($mysqli,$_POST['min']);
		$max = mysqli_real_escape_string($mysqli,$_POST['max']);
		@$hom=$_POST['hom'];
		@$muj=$_POST['muj'];
		$capmax = mysqli_real_escape_string($mysqli,$_POST['capmax']);
		$canconate = mysqli_real_escape_string($mysqli,$_POST['canconate']);
		$pre1=$_POST['pre1'];
		$pre2=$_POST['pre2'];
		$pre3=$_POST['pre3'];
		$pre4=$_POST['pre4'];
		$pre5=$_POST['pre5'];
		$pre6=$_POST['pre6'];

		$sexo=$hom.' '.$muj;
				
		$error = '';
		
			
			$sqlNino = "INSERT INTO cas2(id_cas, fecha_reg, respo_reg, medicina, nutricion, psicologia, ts, pedagogia, juridico, pericultura, fisioterapia, psiquiatria, administracion, servicios_g, edadmin, edadmax, sexo, capacidad, cantidad, pre1, pre2, pre3, pre4, pre5, pre6) VALUES ('$idcentro', '$fecha', '$idDEPTO', '$a', '$b', '$c', '$d', '$e', '$f', '$g', '$h', '$i', '$j', '$k', '$min', '$max', '$sexo', '$capmax', '$canconate', '$pre1', '$pre2', '$pre3', '$pre4', '$pre5', '$pre6')";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			header("Location: listaxcentro.php?id=$idcentro");
			else
			$error = "Error al Registrar";
			
		
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
		<script type="text/javascript" src="jquery.min.js"></script>
	</head>
	<body>

		
		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">
							<br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div>
								
			<div class="box" >
				<h2>Centro de Asistencia Social: <?php echo $nomCas; ?></h2>
				<h3>Servicios que brinda</h3>

				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
					<div class="row uniform">
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="a" name="a" value="SI">
							<label for="a">Medicina</label>
						</div>
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="b" name="b" value="SI">
							<label for="b">Nutrición</label>
						</div>
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="c" name="c" value="SI">
							<label for="c">Psicología</label>
						</div>
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="d" name="d" value="SI">
							<label for="d">Trabajo social</label>
						</div>
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="e" name="e" value="SI">
							<label for="e">Pedagogía</label>
						</div>
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="f" name="f" value="SI">
							<label for="f">Jurídico</label>
						</div>
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="g" name="g" value="SI">
							<label for="g">Pericultura</label>
						</div>
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="h" name="h" value="SI">
							<label for="h">Fisioterapia</label>
						</div>
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="i" name="i" value="SI">
							<label for="i">Psiquiatria</label>
						</div>
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="j" name="j" value="SI">
							<label for="j">Administración</label>
						</div>
						<div class="2u 12u$(small)" align="left">
							<input type="checkbox" id="k" name="k" value="SI">
							<label for="k">Servicios generales</label>
						</div>
					</div>
			</div>
			<div class="box" >
				<h3>Caracteristicas del CAS</h3>
				<div class="table-wrapper">
					<table class="alt">
						<thead>
							<tr>
								<th></th>
								<th width="300"></th>								
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><strong>Rango de edades de NNA que aloja</strong></td>
								<td><div class="row uniform"><div class="3u 12u$(small)"><input type="text" name="min" placeholder="de" onkeypress="return justNumbers(event);" required></div><div class="3u 12u$(small)"><input type="text" name="max" placeholder="a" onkeypress="return justNumbers(event);" required></div><div class="3u 12u$(small)"><br>años</div></div> </td>
							</tr>
							<tr>
								<td><strong>Sexo de las NNA que aloja</strong></td>
								<td><input type="checkbox" id="hom" name="hom" value="HOMBRES">
										<label for="hom">HOMBRES</label>						
									<input type="checkbox" id="muj" name="muj" value="MUJERES">
										<label for="muj">MUJERES</label></td>
							</tr>
							<tr>
								<td><strong>¿Capacidad maxima de alojamiento?</strong></td>
								<td><div class="row uniform"><div class="4u 12u$(small)"><input type="text" name="capmax" placeholder=" " onkeypress="return justNumbers(event);" required></div><div class="3u 12u$(small)"><br>NNA</div></div></td>
								
							</tr>
							<tr>
								<td><strong>¿Cantidad de NNA en condiciones de atender de conformidad con la capacidad presupuestal?</strong></td>
								<td><div class="row uniform"><div class="4u 12u$(small)"><input type="text" name="canconate" placeholder=" " onkeypress="return justNumbers(event);" required></div><div class="3u 12u$(small)"><br>NNA</div></div></td>
								
							</tr>
							<tr>
								<td><strong>¿Brinda atencion a NNA con discapacidad?</strong></td>
								<td>	<input type="radio" id="si1" name="pre1" value="SI">
										<label for="si1">SI</label>						
									<input type="radio" id="no1" name="pre1" value="NO">
										<label for="no1">NO</label></td>
							</tr>
							<tr>
								<td><strong>¿Brinda atención a NNA victimas de algun delito?</strong></td>
								<td>	<input type="radio" id="si2" name="pre2" value="SI">
										<label for="si2">SI</label>						
									<input type="radio" id="no2" name="pre2" value="NO">
										<label for="no2">NO</label></td>
							</tr>
							<tr>
								<td><strong>¿Recibe a NNA de otras entidades federativas?</strong></td>
								<td>	<input type="radio" id="si3" name="pre3" value="SI">
										<label for="si3">SI</label>						
									<input type="radio" id="no3" name="pre3" value="NO">
										<label for="no3">NO</label></td>
							</tr>
							<tr>
								<td><strong>¿Brinda acogimiento a NNA migrantes no acompañados?</strong></td>
								<td>	<input type="radio" id="si4" name="pre4" value="SI">
										<label for="si4">SI</label>						
									<input type="radio" id="no4" name="pre4" value="NO">
										<label for="no4">NO</label></td>
							</tr>
							<tr>
								<td><strong>¿Cuenta con instalaciones para acogimiento residencial en otras entidades federativas?</strong></td>
								<td>	<input type="radio" id="si5" name="pre5" value="SI">
										<label for="si5">SI</label>						
										<input type="radio" id="no5" name="pre5" value="NO">
										<label for="no5">NO</label></td>
							</tr>
							<tr>
								<td><strong>¿Cuenta con servicios en modalidad de puerta abierta?</strong></td>
								<td>	<input type="radio" id="si6" name="pre6" value="SI">
										<label for="si6">SI</label>						
									<input type="radio" id="no6" name="pre6" value="NO">
										<label for="no6">NO</label></td>
							</tr>
						</tbody>
					</table>
				</div>
				
			</div>
									
				
	<div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='welcome.php'" >
		</ul>
	</div>
</form>
<script type="text/javascript">
	function justNumbers(event){		
		if (event.charCode>=48 && event.charCode<=57){
			return true;
		}
		return false;
	}

			$(document).ready(function(){
		$("#country_id").change(function(){
			$.get("get_states.php","country_id="+$("#country_id").val(), function(data){
				$("#state_id").html(data);
				console.log(data);
			});
		});

		$("#state_id").change(function(){
			$.get("get_cities.php","state_id="+$("#state_id").val(), function(data){
				$("#city_id").html(data);
				console.log(data);
			});
		});
	});
</script>

		<?php if($bandera) { 
			header("Location: welcome.php");

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
							
						<?php if($_SESSION['departamento']==7) { ?> 
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
									<li><a href="welcome.php" ">Inicio</a></li>
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
									<li><a href="welcome.php" ">Inicio</a></li>
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