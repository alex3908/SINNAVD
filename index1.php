<?php
	require('conexion.php');	
	session_start();	
	
	if(!empty($_POST)) 
	{
		if(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'])
		{
			$responsable = mysqli_real_escape_string($mysqli,$_POST['departamento']);
			$password = mysqli_real_escape_string($mysqli,$_POST['password']);
			$error = '';
			$sha1_pass = sha1($password);
			
			$sql = "SELECT id, id_depto FROM departamentos WHERE  responsable= '$responsable' AND password = '$sha1_pass' and activo='1'";
			$result=$mysqli->query($sql);
			$rows = $result->num_rows;

			if($rows > 0) {
				$row = $result->fetch_assoc();
				$idd=$row['id'];
				$_SESSION['id'] = $row['id'];
				$_SESSION['departamento'] = $row['id_depto'];

				$newd="SELECT id from departamentos where id='$idd' and fecha_nac is null";
				$enew=$mysqli->query($newd);
				$r2=$enew->num_rows;
				if ($r2>0) {
					header("location: newdatepersonal.php");
				}else {
				header("location: welcome.php");
				}
				} else { ?>
				<script type="text/javascript">
					alert('El nombre o contraseña son incorrectos o su cuenta a sido bloqueada');
				</script>
			<?php }
		} else {
			echo "<script type='text/javascript'>
				alert('repCatpcha vacio');
			</script>";
		}
	
	}
?>
<html>
	<html lang="es-MX" class="no-js">
	<head>
		<title>Inicio de sesión</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	</head>
	
	<body>
		<br><br>
		<div class="box alt" align="center">
			<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >     
				<div class="box">
					<div class="row 10% uniform">
						<div class="4u"><img src="images/crece.jpg" width="40px" height="30px" /></div>
						<div class="4u"><img src="images/dif.jpg" width="20px" height="30px" /></div>
						<div class="4u"><img src="images/armas.jpg" width="40px" height="35px"/></div>
					</div>
					<br>
					<img src="images/logo.png" width="170px" height="90px">
					<br>
					<h5>Sistema de Información de Niñas, Niños y Adolescentes en Situación de Vulneración de Derechos</h5>
					<hr class="major" />
					<h2>INICIO DE SESIÓN</h2>
					<div class="row uniform">
						<div class="6u 12u$(xsmall)">
							<input type="text" name="departamento" id="departamento" value="" placeholder="Responsable" />
						</div>
						<div class="6u$ 12u$(xsmall)">
							<input type="password" name="password" id="password" value="" placeholder="Contraseña" />
						</div>
					</div>
					<div class="row uniform">
						<div class="12u" align="center">
							<div id="cap" class="g-recaptcha" data-sitekey="6LdwpskZAAAAAOUYBKpEygbc5oCrdMVC-kEmWEFO"></div>	
						</div>
						<div class="12u" align="center">
							<input type="submit" value="Entrar" class="special small" />
						</div>	
					</div>
				</div>
				<p class="copyright">&copy; Sistema DIF Hidalgo </p>
			</form>
		</div>
		
		<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>

		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>		

	