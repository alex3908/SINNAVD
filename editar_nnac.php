<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idNNA=$_GET['id'];

	$query="SELECT id, municipio from municipios";
	$equery=$mysqli->query($query);

	$sqlnna="SELECT nna.folio, nna.nombre, nna.apellido_p, nna.curp, nna.apellido_m, nna.fecha_nac, nna.sexo, nna.responna, nna.parentesco, nna.direccion, nna.telefono, nna.lugar_nac, nna.lugar_reg, nna.nna_ex, nna.fecha_reg, nna.respo_reg, localidades.localidad, municipios.municipio, nna.localidad as id_localidad, nna.municipio as id_municipio from nna, municipios, localidades where nna.id='$idNNA' and nna.localidad=localidades.id and nna.municipio=municipios.id";
	$esqlnna=$mysqli->query($sqlnna);

	$bandera = false;
	
	if(!empty($_POST))
	{
		$nombre = 	  mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$ap_paterno = mysqli_real_escape_string($mysqli,$_POST['ap_paterno']);
		$ap_materno = mysqli_real_escape_string($mysqli,$_POST['ap_materno']);
		$curp =       mysqli_real_escape_string($mysqli,$_POST['curp']);
		$fecha_nac =  mysqli_real_escape_string($mysqli,$_POST['fecha_nac']);
		$genero = $_POST['genero'];
		$lugar_nac =  mysqli_real_escape_string($mysqli,$_POST['lugar_nac']);
		$lugar_reg =  mysqli_real_escape_string($mysqli,$_POST['lugar_reg']);
		$responna =  mysqli_real_escape_string($mysqli,$_POST['responna']);
		$parentesco =  mysqli_real_escape_string($mysqli,$_POST['parentesco']);
		$direccion =  mysqli_real_escape_string($mysqli,$_POST['direccion']);
		$telefono =       mysqli_real_escape_string($mysqli,$_POST['telefono']);
		$municipio = $_POST['country_id'];
		$localidad = $_POST['state_id'];
		$fecha_reg= mysqli_real_escape_string($mysqli,$_POST['fecha_reg']);
		$respo_reg = mysqli_real_escape_string($mysqli,$_POST['respo_reg']);
		
		$error = '';
		

			@$sqlNino = "UPDATE nna SET nombre='$nombre', apellido_p='$ap_paterno', apellido_m='$ap_materno', curp='$curp', fecha_nac='$fecha_nac', 
			sexo='$genero', lugar_nac='$lugar_nac', lugar_reg='$lugar_reg', responna='$responna', 
			parentesco='$parentesco', direccion='$direccion', telefono='$telefono', respo_reg='$respo_reg', 
			municipio='$municipio', localidad='$localidad', fecha_reg='$fecha_reg' WHERE id='$idNNA'";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			$bandera = true;
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
			
			<div class="box" >
			<h1>Editar</h1>
			
			<form id="familia"  enctype="multipart/form-data" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
			<?php while ($row=$esqlnna->fetch_assoc()) { ?>
				
			<h2>Folio: <?php echo $row['folio']; ?></h2>
			<div class="row uniform">
			<div class="4u 12u$(xsmall)">
			<input id="nombre" name="nombre" type="text" class="nombre"  value="<?php echo $row['nombre'];  ?>"  placeholder="Nombre(s)" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
			<div class="4u 12u$(xsmall)">
			<input id="ap_paterno" name="ap_paterno" type="text" value="<?php echo $row['apellido_p'];  ?>" placeholder="Apellido Paterno" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
			<div class="4u 12u$(xsmall)">
			<input id="ap_materno" name="ap_materno" type="text" value="<?php echo $row['apellido_m'];  ?>" placeholder="Apellido Materno" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
			<div class="6u 12u$(xsmall)">
			<input id="curp" name="curp" type="text" value="<?php echo $row['curp'];  ?>" placeholder="CURP" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>			
			<div class="6u 12u$(xsmall)">
			<input id="fecha_nac" name="fecha_nac" type="text" value="<?php echo $row['fecha_nac'];  ?>" placeholder="Fecha de nacimiento   dd/mm/aaaa" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>	
			<div class="4u 12u$(xsmall)">
			<div class="select-wrapper">
			<select id="genero" name="genero" >
					<option value="<?php echo $row['sexo']; ?>"><?php echo $row['sexo']; ?></option>
					<option value="MUJER">MUJER</option>
					<option value="HOMBRE">HOMBRE</option>
					
			</select>
			</div></div>		
			<div class="4u 12u$(xsmall)">
			<input id="lugar_nac" name="lugar_nac" type="text" value="<?php echo $row['lugar_nac']; ?>" placeholder="Lugar de nacimiento" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
			<div class="4u 12u$(xsmall)">
			<input id="lugar_reg" name="lugar_reg" type="text" value="<?php echo $row['lugar_reg']; ?>" placeholder="Lugar de registro" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>	

			
		</div>
			<br>
			<div class="box">
			<div class="row uniform">
			<div class="6u 12u$(xsmall)">
			<input id="responna" name="responna" type="text" value="<?php echo $row['responna']; ?>" placeholder="responsable del nna" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
			<div class="6u 12u$(xsmall)">
			<input id="parentesco" name="parentesco" type="text" value="<?php echo $row['parentesco']; ?>" placeholder="Parentesco" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
			<div class="6u 12u$(xsmall)">
			<input id="direccion" name="direccion" type="text" value="<?php echo $row['direccion']; ?>" placeholder="Dirección" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
			<div class="6u 12u$(xsmall)">
			<input id="telefono" name="telefono" type="text" value="<?php echo $row['telefono']; ?>" placeholder="Teléfono" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"> 
			</div>
			</div>
			</div>
			<br>
			<div class="box">
			<div class="row uniform">
			<div class="6u 12u$(xsmall)">
			<input id="respo_reg" name="respo_reg" type="text" value="<?php echo $row['respo_reg']; ?>" placeholder="responsable del registro" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
			<div class="6u 12u$(xsmall)">
			<input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $row['fecha_reg']; ?>" placeholder="Fecha de registro" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"><?php $idloc=$row['id_localidad']; $loc=$row['localidad'];  ?>	
			</div>
			
			</div>
			</div>
			<br>
			<div class="box">
			<div class="row uniform">
			<div class="6u 12u$(xsmall)">Municipio
			<div class="select-wrapper">
			<select id="country_id" class="form-control" name="country_id" required>
					<option value="<?php echo $row['id_municipio']; ?>"><?php echo $row['municipio']; } ?></option>
					<?php while ($row=$equery->fetch_assoc()) { ?>					
     					<option value="<?php echo $row['id']; ?>"><?php echo $row['municipio']; ?></option>
					<?php } ?>					
			</select>
			</div>
			</div>
			<div class="6u 12u$(xsmall)">Localidad
			<div class="select-wrapper">
			<select id="state_id" class="form-control" name="state_id" required>
					<option value="<?php echo $idloc; ?>"><?php echo $loc; ?></option>
			</select>
			</div>
			</div>
		</div>
	</div>
			<div class="12u$">
			<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Actualizar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='lista_nna.php'" >
			</ul></div>

			
			</form>	
			<script type="text/javascript">
	$(document).ready(function(){
		$("#country_id").change(function(){
			$.get("get_localidades.php","country_id="+$("#country_id").val(), function(data){
				$("#state_id").html(data);
				console.log(data);
			});
		});

		
	});
</script>
		</div>
	
						
	<?php if($bandera) { 
			header("Location: perfil_nna.php?id=$idNNA");

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
									<ul><li><a href="welcome.php">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												<li><a href="registro_usuarios.php">Alta</a></li>
												
												
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
												<li><a href="atenciones_area.php">Atenciones</a></li>
												
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