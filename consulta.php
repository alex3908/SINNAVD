<?php
	ob_start();
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$consulta="SELECT municipios.municipio, localidades.localidad, CONCAT(nna.apellido_p,' ',nna.apellido_m,' ',nna.nombre) AS nom, nna.curp, nna.sexo, seguimientos.fecha from localidades, municipios, nna, benefmed, cuadro_guia, seguimientos where benefmed.id_nna=nna.id and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id=seguimientos.id_med and seguimientos.fecha LIKE '%2019%' and (nna.fecha_reg LIKE '%2018%' or nna.fecha_reg LIKE '%2017%') and localidades.id=nna.localidad and municipios.id=nna.municipio group by nom limit 20";
	$econsul=$mysqli->query($consulta);
	
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
		<table class="alt">
			<thead>
				<tr>
					<th>MUNICIPIO</th>
					<th>LOCALIDAD</th>
					<th>NOMBRE</th>
					<th>CURP</th>
					<th>SEXO</th>
					<th>FECHA DE ATENCION</th>												
				</tr>
			</thead>
			<tbody>
				<?php while($row=$econsul->fetch_assoc()){ ?>
					<tr>
						<td><?php echo $row['municipio']; ?></td>
						<td><?php echo $row['localidad']; ?></td>
						<td><?php echo $row['nom']; ?></td>
						<td><?php echo $row['curp']; ?></td>
						<td><?php echo $row['sexo']; ?></td>
						<td><?php echo $row['fecha']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
	</html>
