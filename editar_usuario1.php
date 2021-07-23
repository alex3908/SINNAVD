<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idU=$_GET['id'];

	$query="SELECT id, municipio from municipios";
	$equery=$mysqli->query($query);

	$query="SELECT usuarios.id, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, usuarios.curp, usuarios.fecha_nac, sexo.sexo, usuarios.direccion, usuarios.estado, usuarios.id_mun, usuarios.id_loc, usuarios.telefono_fijo, usuarios.telefono_movil, municipios.municipio, localidades.localidad FROM usuarios, sexo, municipios, localidades WHERE usuarios.id_sexo=sexo.id && usuarios.id='$idUsuario' and usuarios.id_mun=municipios.id and usuarios.id_loc=localidades.id";
	$resultado=$mysqli->query($query);

	$bandera = false;
	
	if(!empty($_POST))
	{
        $nombre = 	  mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$apellido_p = mysqli_real_escape_string($mysqli,$_POST['apellido_p']);
		$apellido_m = mysqli_real_escape_string($mysqli,$_POST['apellido_m']);
		$curp =       mysqli_real_escape_string($mysqli,$_POST['curp']);
		$fecha_nac =  mysqli_real_escape_string($mysqli,$_POST['fecha_nac']);
		$sexo = $_POST['sexo'];
		$direccion =  mysqli_real_escape_string($mysqli,$_POST['direccion']);
		$municipio = mysqli_real_escape_string($mysqli,$_POST['country_id']);
		$localidad = mysqli_real_escape_string($mysqli,$_POST['state_id']);
		$estado =  mysqli_real_escape_string($mysqli,$_POST['estado']);
		$fijo =       mysqli_real_escape_string($mysqli,$_POST['fijo']);
		$movil =      mysqli_real_escape_string($mysqli,$_POST['movil']);
		$error = '';
		

			@$sqlActualizar = "UPDATE usuarios SET nombre='$nombre', apellido_p='$apellido_p', apellido_m='$apellido_m ', curp='$curp', fecha_nac='$fecha_nac', id_sexo='$sexo', direccion='$direccion', telefono_fijo='$fijo', telefono_movil='$movil', estado=$estado, municipio='$municipio', localidad='$localidad' WHERE id='$idU'";
			$rActualizar = $mysqli->query($sqlActualizar);
			
			if($rActualizar>0)
			$bandera = true;
			else
			$error = "Error al registrar";
			
		}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Editar usuario</title>
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
			<?php while ($row=$resultado->fetch_assoc()) { ?>
            
			<div class="row uniform">
			<div class="4u 12u$(xsmall)">
			<input id="nombre" name="nombre" type="text" class="nombre"  value="<?php echo $row['nombre'];  ?>"  placeholder="Nombre(s)" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
			<div class="4u 12u$(xsmall)">
			<input id="apellido_p" name="apellido_p" type="text" value="<?php echo $row['apellido_p'];  ?>" placeholder="Apellido Paterno" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
			<div class="4u 12u$(xsmall)">
			<input id="apellido_m" name="apellido_m" type="text" value="<?php echo $row['apellido_m'];  ?>" placeholder="Apellido Materno" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>					
			<div class="4u 12u$(xsmall)">
			<input id="curp" name="curp" type="text" value="<?php echo $row['curp'];  ?>" placeholder="CURP" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>	
            <div class="4u 12u$(xsmall)">
			<input id="fecha_nac" name="lugar_nac" type="text" value="<?php echo $row['lugar_nac']; ?>" placeholder="Lugar de nacimiento" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
			<div class="4u 12u$(xsmall)">
			<div class="select-wrapper">
			    <select id="sexo" name="sexo" >
					<option value="<?php echo $row['id_sexo']; ?>"><?php echo $row['sexo']; ?></option><?php }    ?>
					<?php while($row = $resu->fetch_assoc()){ ?>
						<option value="<?php echo $row['id']; ?>"><?php echo $row['sexo']; ?></option>
					<?php }?>
			    </select>
			</div></div>		
			
			<div class="12u 12u$(xsmall)">
			<input id="direccion" name="direccion" type="text" value="<?php echo $row['direccion']; ?>" placeholder="Direccion" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
            <div class="4u 12u$(xsmall)">
			<input id="estado" name="estado" type="text" value="<?php echo $row['estado']; ?>" placeholder="Estado" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
            <div class="4u 12u$(xsmall)">
			<input id="municipio" name="municipio" type="text" value="<?php echo $row['municipio']; ?>" placeholder="Municipio" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
            <div class="4u 12u$(xsmall)">
			<input id="localidad" name="localidad" type="text" value="<?php echo $row['localidad']; ?>" placeholder="Localidad" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
		    </div>
			
			
			<div class="6u 12u$(xsmall)">
			<input id="fijo" name="fijo" type="text" value="<?php echo $row['fijo']; ?>" placeholder="Teléfono fijo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"> 
			</div>
            <div class="6u 12u$(xsmall)">
			<input id="movil" name="movil" type="text" value="<?php echo $row['movil']; ?>" placeholder="Teléfono movil" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"> 
			</div>
            <?php $idloc=$row['id_localidad']; $loc=$row['localidad']; ?>
			<div class="box">
			<div class="row uniform">
			<div class="6u 12u$(xsmall)">Municipio
			<div class="select-wrapper">
				<select id="country_id" class="form-control" name="country_id" required>
					<option value="<?php echo $row['id_mun']; ?>"><?php echo $row['mun']; } ?></option>
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
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_nna.php?id=<?php echo $idNNA; ?>'" >
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