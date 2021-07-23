<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idBenef=$_GET['id'];

	$query="SELECT id, municipio from municipios";
	$equery=$mysqli->query($query);

	$sqlBenef="SELECT benef_unidad.folio, benef_unidad.nombre, benef_unidad.apellido_p, benef_unidad.apellido_m, benef_unidad.curp,  benef_unidad.fecha_nac, benef_unidad.sexo, benef_unidad.calle, benef_unidad.telefono, localidades.localidad, municipios.municipio, benef_unidad.localidad as id_localidad, benef_unidad.municipio as id_municipio from benef_unidad, municipios, localidades where benef_unidad.id='$idBenef' and benef_unidad.localidad=localidades.id and benef_unidad.municipio=municipios.id";
	$esqlBenef=$mysqli->query($sqlBenef);

	$bandera = false;
	
	if(!empty($_POST))
	{
		$nombre = 	  mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$ap_paterno = mysqli_real_escape_string($mysqli,$_POST['ap_paterno']);
		$ap_materno = mysqli_real_escape_string($mysqli,$_POST['ap_materno']);		
		$fecha_nac =  mysqli_real_escape_string($mysqli,$_POST['fecha_nac']);
		$genero = $_POST['genero'];
		$curp =  mysqli_real_escape_string($mysqli,$_POST['curp']);
		$calle =  mysqli_real_escape_string($mysqli,$_POST['calle']);
		$telefono =       mysqli_real_escape_string($mysqli,$_POST['telefono']);
		$municipio = $_POST['country_id'];
		$localidad = $_POST['state_id'];
		$error = '';
		

			@$sqlNino = "UPDATE benef_unidad SET nombre='$nombre', apellido_p='$ap_paterno', apellido_m='$ap_materno', fecha_nac='$fecha_nac', sexo='$genero', curp='curp', calle='$calle', telefono='$telefono', municipio='$municipio', localidad='$localidad' WHERE id='$idBenef'";
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
		<title>Editar beneficiarios UIENNAVD</title>
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
			<?php while ($row=$esqlBenef->fetch_assoc()) { ?>
				
			<h2>Folio: <?php echo $row['folio']; ?></h2>
			<div class="row uniform">
			<div class="4u 12u$(xsmall)">            
			<input id="nombre" name="nombre" type="text" class="nombre"  value="<?php echo $row['nombre'];  ?>"  placeholder="Nombre(s)" required style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
			<div class="4u 12u$(xsmall)">
			<input id="ap_paterno" name="ap_paterno" type="text" value="<?php echo $row['apellido_p'];  ?>" placeholder="Apellido Paterno" required style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
			<div class="4u 12u$(xsmall)">
			<input id="ap_materno" name="ap_materno" type="text" value="<?php echo $row['apellido_m'];  ?>" placeholder="Apellido Materno" required style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>	
            				
			<div class="2u 12u$(xsmall)">
			<input id="fecha_nac" name="fecha_nac" type="text" value="<?php echo $row['fecha_nac'];  ?>" placeholder="Fecha de nacimiento   dd/mm/aaaa" required style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>	
			<div class="2u 12u$(xsmall)">
			<div class="select-wrapper">
			<select id="genero" name="genero" >
					<option value="<?php echo $row['sexo']; ?>"><?php echo $row['sexo']; ?></option>
					<option value="MUJER">MUJER</option>
					<option value="HOMBRE">HOMBRE</option>
					
			</select>
			</div></div>		
			<div class="4u 12u$(xsmall)">
			<input id="curp" name="curp" type="text" value="<?php echo $row['curp'];  ?>" placeholder="CURP" required style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
            <div class="4u 12u$(xsmall)">
			<input id="telefono" name="telefono" type="text" value="<?php echo $row['telefono']; ?>" placeholder="Teléfono" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"> 
			</div>
            
		</div>
			<br>
			<div class="box">
			<div class="row uniform">
			<div class="12u 12u$(xsmall)">
			<input id="calle" name="calle" type="text" value="<?php echo $row['calle']; ?>" placeholder="calle" required style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
            </div>
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
            <?php $idloc=$row['id_localidad']; $loc=$row['localidad']; ?>
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
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_beneficiarios.php?id=<?php echo $idBenef; ?>'" >
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
			header("Location: lista_unidad.php");
			}else{ ?>
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