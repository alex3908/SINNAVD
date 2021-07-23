<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$cargo="SELECT id_personal from departamentos where id=$idDEPTO";
	$ecargo=$mysqli->query($cargo);
	while ($row=$ecargo->fetch_assoc()) {
		$idpersonal=$row['id_personal'];
	}

	$fechas=$_GET['fec'];
	$res=$_GET['res'];
	echo $res;
	$arry=explode(",", $fechas);
	$long=count($arry);
	$fff='';
	for ($i=0; $i <$long ; $i++) { 
		$ff="'".$arry[$i]."',";
		$fff=$ff.$fff;
	}
	
	$fff=trim($fff, ',');

	$consulta="SELECT derecho, id FROM derechos_nna";
	$econsulta=$mysqli->query($consulta);

	$cuadro="SELECT benefmed.id_nna, seguimientos.seguimiento, seguimientos.fecha, departamentos.responsable from benefmed, seguimientos, departamentos where departamentos.id=seguimientos.respo_reg and seguimientos.id_med=benefmed.id_medida and seguimientos.fecha in($fff) and seguimientos.respo_reg in($res)";	
	$ecuadro=$mysqli->query($cuadro);
	$tot=$ecuadro->num_rows;
	echo $tot;
	
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
							<br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div> <br>				
							
									<div class="box">
								<div class="table-wrapper">
								
									<table class="alt">
									<h3>SEGUIMIENTOS</h3>
										<thead>
											<tr>
												<th>NNA</th>
												<th>SEGUIMIENTO</th>
												<th>FECHA</th>
												<th>RESPONSABLE</th>											
											</tr>
										</thead>
										<tbody>
										
											<tr>
												<?php while ($row=$ecuadro->fetch_assoc()) { ?>
												
											<?php $seguimiento=$row['seguimiento'];    
													  $fecha=$row['fecha'];  
													  $responsable=$row['responsable']; ?>
												<td><?php $bene=$row['id_nna'];
												if (empty($bene)) {
													$nnaa="SELECT id_nna from benefmed where id_medida='$id_CG'";
													$ennaa=$mysqli->query($nnaa);
													while ($row=$ennaa->fetch_assoc()) {
														@list($idMorro, $tipoMorro)=split(' ', $row['id_nna']);
														if ($tipoMorro=='NE') {
															$sql="SELECT nombre, apellido_p, apellido_m from nna where id='$idMorro'";
															$esql=$mysqli->query($sql);
															while ($row=$esql->fetch_assoc()) {
																echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];
															}
														}else if ($tipoMorro=='E') {
															$sql="SELECT folio, sexo from nna_exposito where id='$idMorro'";
															$esql=$mysqli->query($sql);
															while ($row=$esql->fetch_assoc()) {
																echo $row['folio']." ".$row['sexo'];
															} 
														} echo '<br><br>';
													}
												}else {

														$Cadabene=explode(':', $bene);
														for ($i=0; $i <count($Cadabene) ; $i++) { 
														
														@list($idMorro, $tipoMorro)=split('[ ]', $Cadabene[$i]);
														
														if ($tipoMorro=='NE') {
															$sql="SELECT nombre, apellido_p, apellido_m from nna where id='$idMorro'";
															$esql=$mysqli->query($sql);
															while ($row=$esql->fetch_assoc()) {
																echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];
															}
														}else if ($tipoMorro=='E') {
															$sql="SELECT folio, sexo from nna_exposito where id='$idMorro'";
															$esql=$mysqli->query($sql);
															while ($row=$esql->fetch_assoc()) {
																echo $row['folio']." ".$row['sexo'];
															} 
														} echo '<br><br>';
													}	
												}
													 ?>
													
												</td>
												<td><?php echo $seguimiento;   ?></td>
												<td><?php echo $fecha;   ?></td>		
												<td><?php echo $responsable;   ?></td>
												
											</tr>
										<?php } ?>
										</tbody>
									</table>

										
								</div></div>
							<br>
								
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