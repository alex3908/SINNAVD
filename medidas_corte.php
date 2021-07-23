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

	$per=$_GET['fec'];
	$res=$_GET['res'];
	$sql="SELECT fechas from cortes where id='$per'";
	$esql=$mysqli->query($sql);
	while ($row=$esql->fetch_assoc()) {
		$fff=$row['fechas'];
	}

	$consulta="SELECT derecho, id FROM derechos_nna";
	$econsulta=$mysqli->query($consulta);

	$cuadro="
	SELECT derechos_nna.derecho, cuadro_guia.id, medidas.medida_p, cuadro_guia.marco, cuadro_guia.med_prot, cuadro_guia.id_mp, cuadro_guia.beneficiario, cuadro_guia.responsable_med, cuadro_guia.atp_encargada, cuadro_guia.periodicidad, cuadro_guia.estado, cuadro_guia.observaciones, cuadro_guia.fecha, cuadro_guia.id_sp_registro, departamentos.responsable, casos.folio_c, casos.id as idc from cuadro_guia, derechos_nna, departamentos, medidas, casos where cuadro_guia.id_caso=casos.id AND cuadro_guia.id_medida=medidas.id and derechos_nna.id=cuadro_guia.id_derecho and cuadro_guia.id_medida in('03','01','02') and cuadro_guia.id_sp_registro=departamentos.id and cuadro_guia.fecha in($fff) and cuadro_guia.id_sp_registro in($res)";
	$ecuadro=$mysqli->query($cuadro);	
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
							<input type="button" value="atras" onclick="location='informemm.php'">
									<div class="box">
								<div class="table-wrapper">
								
									<table class="alt">
									<h3>CORTE</h3>
										<thead>
											<tr>
												<th>CASO</th>
												<th>DERECHO VULNERADO O RESTRINGIDO</th>
												<th>MARCO JURIDICO</th>
												<th>TIPO DE MEDIDA</th>
												<th>MEDIDA DE PROTECCIÓN ESPECIAL</th>
												<th>BENEFICIARIO</th>
												<th>INSTITUCIÓN O PERSONA RESPONSABLE</th>
												<th>RESPONSABLE DE LLEVARLA A CABO</th>
												<th>PERIODICIDAD</th>
												<th>FECHA</th>
												<th>EJECUCIÓN</th>												
												<th>RESPONSABLE</th>										
											</tr>
										</thead>
										<tbody>
										
											<tr>
												<?php while ($row=$ecuadro->fetch_assoc()) { ?>
												<td><a href="cuadro_guia.php?id=<?php echo $row['idc']; ?>"><?php echo $row['folio_c'];?></a></td>
												<td><?php echo $row['derecho'];?></td>
												<td><?php echo $row['marco'];   ?></td>
												<td><?php echo $row['medida_p']; ?></td>
												<?php $responsable_med=$row['responsable_med'];    
													  $atp_encargada=$row['atp_encargada'];  
													  $periodicidad=$row['periodicidad'];    
													  $fecha=$row['fecha'];   
													  $responsable=$row['responsable'];
													  $id_CG=$row['id'];
													  $es=$row['estado']; 
													  $porevaluar=$row['id_sp_registro'];
													  $observaciones=$row['observaciones'] ?>
												<td><?php $lamed=$row['med_prot']; $lamedc=$row['id_mp'];
												if (empty($lamed)) {
													$me="SELECT medidaC from catalogo_medidas where id='$lamedc'";
													$eme=$mysqli->query($me);
													while ($row=$eme->fetch_assoc()) {
														echo $row['medidaC'];
													}
												   
												   }else {
												   	echo $lamed;
												   }   ?></td>
											
												<td><?php $bene=$row['beneficiario'];
												if (empty($bene)) {
													$nnaa="SELECT nna.nombre, nna.apellido_p, nna.apellido_m from benefmed, nna where benefmed.id_medida='$id_CG' and benefmed.id_nna=nna.id";
													$ennaa=$mysqli->query($nnaa);
													while ($row=$ennaa->fetch_assoc()) {		
																echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];
															echo '<br><br>';
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
												<td><?php echo $responsable_med;   ?></td>
												<td><?php echo $atp_encargada;   ?></td>		
												<td><?php echo $periodicidad;   ?></td>			
												<td><?php echo $fecha;   ?></td>
												<td><?php  //siendo tu medida
													if ($es==0) { ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/no_ejecutada.png" height="40" width="40" >
														</div>
													<?php }else if($es==1 ){ ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/ejecutada.png" height="40" width="40" >
														</div>
													<?php } //cierre siendo tu medida ?>
												</td>										
												<td><?php  echo $responsable; ?></td>
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