<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$id_centro=$_GET['id'];


?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Ingreso</title>
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
		<div class="box alt" align="center">
			<div class="row 10% uniform">
				<div class="11u" align="left"><h1>Ingresar NNA</h1></div>
				<div class="1u" align="left"><input type="button" value="cancelar" class="fit small" onclick="location='nnaENcas.php?id=<?php echo $id_centro; ?>'"></div>				
			</div>
		</div> 
			
				<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" name="palabra" id="query" placeholder="Search" />			
				</form>
						<div class="table-wrapper">
							<table class="alt">
							<thead>
								<tr><td><b>Folio</b></td>
									<td><b>Nombre</b></td>
									<td><b>Fecha de nacimiento</b></td>
									<td><b>Sexo</b></td>
									<td><b>CURP</b></td>
									<td></td>
								</tr>
							</thead>
							<tbody>
							<?php
	

	@$buscar = $_POST["palabra"];
					if (empty($buscar)) {
						$query="SELECT id, folio, nombre, apellido_p, apellido_m, 
						concat(nombre,' ',apellido_p,' ',apellido_m) as nom, fecha_nac, 
						sexo, curp from nna having (nombre like '%$buscar%' OR apellido_p 
						like '%$buscar%' OR apellido_m like '%$buscar%' OR folio like '%$buscar%' 
						OR nom like '%$buscar%' OR fecha_nac like '%$buscar%' or curp like '%$buscar%') 
						order by fecha_reg desc limit 20";
						
					}else {
						$query="SELECT id, folio, nombre, apellido_p, apellido_m, 
						concat(nombre,' ',apellido_p,' ',apellido_m) as nom, fecha_nac, 
						sexo, curp from nna having (nombre like '%$buscar%' OR apellido_p 
						like '%$buscar%' OR apellido_m like '%$buscar%' OR folio like '%$buscar%'
						OR nom like '%$buscar%' OR fecha_nac like '%$buscar%' or curp like '%$buscar%')";
					}
					
						$resultado=$mysqli->query($query);
						$rows2=$resultado->num_rows;
						echo "Resultados: ".$rows2;
 					while($row=$resultado->fetch_assoc()){ ?>
					
				<tr>
					<td><?php echo $row['folio'];?></td>
					<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['sexo'];?></td>
					<td><?php echo $row['curp'];?></td>
										
									<td><input type="button" name="agregar" onclick="location='ingreso_cas.php?idc=<?php echo $id_centro; ?>&idn=<?php echo $row['id']; ?>'" value="Ingresar" class="button special fit small" ></td>
								</tr>
								<?php }?>
							</tbody>
							</table>
						</div>
					
						</div>
					</div>

				

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>