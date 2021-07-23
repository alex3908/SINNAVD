<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];
	
	$query="SELECT * from municipios where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($row=$equery->fetch_object())	{ $countries[]=$row; }
	
	
	$fecha= date ("j/n/Y");
	$bandera = false;
	if(!empty($_POST))
	{
		$nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$ap_paterno = mysqli_real_escape_string($mysqli,$_POST['ap_paterno']);
		$ap_materno = mysqli_real_escape_string($mysqli,$_POST['ap_materno']);		
		$fecha_nac = mysqli_real_escape_string($mysqli,$_POST['fecha_nac']);		
		$sexo = $_POST['sexo'];
		$curp = mysqli_real_escape_string($mysqli,$_POST['curp']);
		$lug_nac = mysqli_real_escape_string($mysqli,$_POST['lug_nac']);
		$lug_reg = mysqli_real_escape_string($mysqli,$_POST['lug_reg']);
		$country_id = mysqli_real_escape_string($mysqli,$_POST['country_id']);
		$state_id = mysqli_real_escape_string($mysqli,$_POST['state_id']);
		$responna = mysqli_real_escape_string($mysqli,$_POST['responna']);
		$parentesco = mysqli_real_escape_string($mysqli,$_POST['parentesco']);
		$direccion = mysqli_real_escape_string($mysqli,$_POST['direccion']);
		$telefono = mysqli_real_escape_string($mysqli,$_POST['telefono']);
		
		if (isset($_POST['indigena']) && $_POST['indigena'] == '1')
			$banIndigena="1";
		else 
			$banIndigena="0";

		if (isset($_POST['afrodescendiente']) && $_POST['afrodescendiente'] == '1')
			$banAfro="1";
		else 
			$banAfro="0";


		$error = '';
		$lnom=substr($nombre, 0,1);
		$lap=substr($ap_paterno, 0,1);
		$lam=substr($ap_materno, 0,1);
		
		$snum='SELECT terminacion from nfolio where id=1';
		$esnum=$mysqli->query($snum);
		while ($row=$esnum->fetch_assoc()) {
			$ter=$row['terminacion'];
		}
		$ter2=$ter+1;
		$folio=$lnom.$lap.$lam.$ter2;
	

			if ($curp=="-" OR $curp==".") {
				header("Location: welcome.php");
			}else {
			
			$sqlNino = "INSERT INTO nna (folio, nombre, apellido_p, apellido_m, curp, fecha_nac, sexo,  lugar_nac, lugar_reg, municipio, localidad, responna, parentesco, direccion, telefono, estado, respo_reg, nna_ex, fecha_reg, indigena, afrodescendiente) VALUES ('$folio', '$nombre', '$ap_paterno', '$ap_materno', '$curp', '$fecha_nac', '$sexo', '$lug_nac', '$lug_reg', '$country_id', '$state_id', '$responna', '$parentesco', '$direccion', '$telefono', 'P', '$idDEPTO', '0', '$fecha', '$banIndigena', '$banAfro')";
			echo $sqlNino;
			$resultNino = $mysqli->query($sqlNino);
			$contador="UPDATE nfolio set terminacion='$ter2' where id=1";
			$econt=$mysqli->query($contador);
			
			if($resultNino>0)
			header("Location:lista_nna.php");
			else
			$error = "Error al Registrar";
		}	
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
							<br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div>
								<h1>Registro de NNA</h1>
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
									<div class="4u 12u$(xsmall)">
										<input id="nombre" name="nombre" type="text" placeholder="Nombre(s)"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="4u 12u$(xsmall)">
										<input id="ap_paterno" name="ap_paterno" type="text"  placeholder="Apellido Paterno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="4u 12u$(xsmall)">
										<input id="ap_materno" name="ap_materno" type="text"  placeholder="Apellido materno"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									
									<div class="4u 12u$(xsmall)">
										<input id="fecha_nac" name="fecha_nac" type="text" placeholder="Fecha de nacimiento   dd/mm/aaaa"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>	
									</div>
									<div class="5u 12u$(xsmall)">
										<input id="curp" name="curp" type="text" placeholder="CURP"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="3u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="sexo" name="sexo" required>
												<option value="">SEXO...</option>
												<option value="HOMBRE">HOMBRE</option>
												<option value="MUJER">MUJER</option>
											</select>
										</div>
									</div>
									
									<div class="3u 12u$(xsmall)">
										<input id="lug_nac" name="lug_nac" type="text" placeholder="Lugar de nacimiento"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="3u 12u$(xsmall)">
										<input id="lug_reg" name="lug_reg" type="text" placeholder="Lugar de registro"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="3u 12u$(xmall">
										<input type="checkbox" id="indigena" name="indigena" value="1">
										<label for="indigena">Origen indigena</label>
									</div>
									<div class="3u 12u$(xmall">
										<input type="checkbox" id="afrodescendiente" name="afrodescendiente" value="1">
										<label for="afrodescendiente">Afrodescendiente</label>
									</div>
									<div class="12u 12u(xsmall)">
									Dirección actual: 
									</div>
									<div class="6u 12u(xsmall)">
									<div class="select-wrapper">
											<select id="country_id" class="form-control" name="country_id" required>
      											<option value="">-- MUNICIPIO --</option>
												<?php foreach($countries as $c):?>
     											<option value="<?php echo $c->id; ?>"><?php echo $c->municipio; ?></option>
												<?php endforeach; ?>
    										</select>
										</div>
									</div>
									<div class="6u 12u(xsmall)">
									<div class="select-wrapper">
											<select id="state_id" class="form-control" name="state_id" required>
     										<option value="">-- SELECCIONE --</option>
   											</select>
										</div> 
									</div>
									</div>
									<br>
									<div class="box">(Datos sobre el responsable actual del menor)
									<div class="row uniform">

									<div class="6u 12u$(xsmall)">
										<input id="responna" name="responna" type="text" placeholder="Persona responsable del nna"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									
									<div class="6u 12u$(xsmall)">
										<input id="parentesco" name="parentesco" type="text" placeholder="Parentesco"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="6u 12u$(xsmall)">
										<input id="direccion" name="direccion" type="text" placeholder="Dirección" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<div class="6u 12u$(xsmall)">
										<input id="telefono" name="telefono" type="text" placeholder="Telefono"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									</div>
									</div>
							</div>
				
	<div class="12u$">
		<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Registrar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='lista_nna.php'" >
		</ul>
	</div>
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

		<?php if($bandera) { 
			header("Location: lista_nna.php");

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
							<nav id="menu">
								<header class="major">
									<h2>Menú</h2>
								</header>
									<ul><li><a href="welcome.php">Inicio</a></li>				
										<li><a href="logout.php">Cerrar sesión</a></li>
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