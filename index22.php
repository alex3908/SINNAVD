<?php
	require('conexion.php');
	
	session_start();
	
	
	
	if(!empty($_POST))
	{
		$responsable = mysqli_real_escape_string($mysqli,$_POST['departamento']);
		$password = mysqli_real_escape_string($mysqli,$_POST['password']);
		$error = '';
		

		$sha1_pass = sha1($password);
		
		$sql = "SELECT id, id_depto FROM departamentos WHERE  responsable= '$responsable' AND password = '$sha1_pass'";
		$result=$mysqli->query($sql);
		$rows = $result->num_rows;
		
		if($rows > 0) {
			$row = $result->fetch_assoc();
			$_SESSION['id'] = $row['id'];
			$_SESSION['departamento'] = $row['id_depto'];
			
			header("location: welcome.php");
			} else {
			$error = "El nombre o contraseña son incorrectos";
		}
	}
?>
<html>
	<html lang="en" class="no-js">
	<head>
		<title>Inicio de sesión</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />

	</head>
	
	<body>

	
        <br><br><br>
<div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="100px" height="90px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="60px" height="100px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="100px" height="100px"/></div>
		</div>
	</div> <br><h2 align="center">Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia</h2>
		<form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >     
		<div class="box">
		<h2>Inicio de sesión</h2>

			<div class="row uniform">
				<div class="6u 12u$(xsmall)">
					<input type="text" name="departamento" id="departamento" value="" placeholder="Responsable" />
				</div>
				<div class="6u$ 12u$(xsmall)">
					<input type="password" name="password" id="password" value="" placeholder="Contraseña" />
				</div>
			<br>
				<div class="12u$">
					<input type="submit" value="Entrar" class="special" />
				</div>											
															
			</div></div>
		</form>
		<div align="right"><a href="index2.php">probar Version 2.0</a></div>
		
		<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>

			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		

	