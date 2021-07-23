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

	$idCaso= $_GET['id'];	
	$sqlcaso="SELECT nombre from casos where id='$idCaso'";
	$ecaso=$mysqli->query($sqlcaso);

	$consulta="SELECT derecho, id FROM derechos_nna";
	$econsulta=$mysqli->query($consulta);

	$cuadro="SELECT derechos_nna.derecho, cuadro_guia.id, medidas.medida_p, cuadro_guia.marco, cuadro_guia.med_prot, cuadro_guia.beneficiario, cuadro_guia.responsable_med, cuadro_guia.atp_encargada, cuadro_guia.periodicidad, cuadro_guia.estado, cuadro_guia.observaciones, cuadro_guia.fecha, cuadro_guia.id_sp_registro, departamentos.responsable from cuadro_guia, derechos_nna, departamentos, medidas where cuadro_guia.id_caso='$idCaso' AND cuadro_guia.id_medida=medidas.id and derechos_nna.id=cuadro_guia.id_derecho and cuadro_guia.id_sp_registro=departamentos.id";
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
							<?php while ($row=$ecaso->fetch_assoc()) { ?>
							
						
							<h3>Caso: <?php echo $row['nombre']; }?></h3>
									<div class="box">
								<div class="table-wrapper">
								
									<table class="alt">
									<h3>CUADRO GUÍA PARA LA ELABORACIÓN DEL PLAN DE RESTITUCIÓN DE DERECHOS</h3>
										<thead>
											<tr>
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
												<th></th>	
												<th>RESPONSABLE</th>										
											</tr>
										</thead>
										<tbody>
										
											<tr>
												<?php while ($row=$ecuadro->fetch_assoc()) { ?>
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
												<td><?php $idMed=$row['med_prot'];
														$pmedida="SELECT folio, medidaC from catalogo_medidas where id='$idMed'";
														$epmedida=$mysqli->query($pmedida);
														while ($row=$epmedida->fetch_assoc()) {
														   	echo $row['folio'].'- '.$row['medidaC'];
														   }   ?></td>
												<td><?php $bene=$row['beneficiario'];
														@list($idMorro, $tipoMorro)=explode(' ', $bene);
														
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
														}
													 ?>
													
												</td>
												<td><?php echo $responsable_med;   ?></td>
												<td><?php echo $atp_encargada;   ?></td>		
												<td><?php echo $periodicidad;   ?></td>			
												<td><?php echo $fecha;   ?></td>
												<?php
												if ($idpersonal==1 && $porevaluar==$idDEPTO) { ?>
												<td><?php  //siendo tu medida
													if ($es==0) { ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/no_ejecutada.png" height="40" width="40" onclick="location='medida_ejecutada.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														</div>
													<?php }else if($es==1 ){ ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/ejecutada.png" height="40" width="40" onclick="location='medida_noejecutada.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														</div>
													<?php } //cierre siendo tu medida ?>
												</td>

												<td>
												<?php if ($es==1) { 
													if (empty($observaciones)) { ?>
													 <input type="image" src="images/seguimiento.png" height="45" width="45" onclick="location='ag_comment.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
													<?php } else{ echo $observaciones; } ?>
													
												<?php } else if($es==0){ ?>
														<input type="image" src="images/editar.png" width="30" height="30" onclick="location='editar_medida.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														<input type="image" src="images/eliminar.png" width="30" height="30" onclick="location='eliminar_medida.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														<input type="image" src="images/seguimiento.png" height="30" width="30" onclick="location='ag_comment.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
												<?php } ?>
												</td>
												<?php }else if ($porevaluar==$idDEPTO ) { ?>
												<td><?php  //siendo tu medida
													if ($es==0) { ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/no_ejecutada.png" height="40" width="40" onclick="location='medida_ejecutada.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														</div>
													<?php }else if($es==1 ){ ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/ejecutada.png" height="40" width="40" disabled>
														</div>
													<?php } //cierre siendo tu medida ?>
												</td>

												<td>
												<?php if ($es==1) { 
													if (empty($observaciones)) { ?>
													 <input type="image" src="images/seguimiento.png" height="45" width="45" onclick="location='ag_comment.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
													<?php } else{ echo $observaciones; } ?>
													
												<?php } else if($es==0){ ?>
														<input type="image" src="images/editar.png" width="30" height="30" onclick="location='editar_medida.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														<input type="image" src="images/eliminar.png" width="30" height="30" onclick="location='eliminar_medida.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														<input type="image" src="images/seguimiento.png" height="30" width="30" onclick="location='ag_comment.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
												<?php } ?>
												</td>
												<?php }
												else if ( $idpersonal==1 ) { ?>
												<td><?php  //siendo tu medida
													if ($es==0) { ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/no_ejecutada.png" height="40" width="40" onclick="location='medida_ejecutada.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														</div>
													<?php }else if($es==1 ){ ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/ejecutada.png" height="40" width="40" onclick="location='medida_noejecutada.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														</div>
													<?php } //cierre siendo tu medida ?>
												</td>

												<td>
												<?php if ($es==1) { 
													if (empty($observaciones)) { ?>
													 <input type="image" src="images/seguimiento.png" height="45" width="45" onclick="location='ag_comment.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
													<?php } else{ echo $observaciones; } ?>
													
												<?php } else if($es==0){ ?>
														<input type="image" src="images/editar.png" width="30" height="30" onclick="location='editar_medida.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														<input type="image" src="images/eliminar.png" width="30" height="30" onclick="location='eliminar_medida.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
														<input type="image" src="images/seguimiento.png" height="30" width="30" onclick="location='ag_comment.php?id=<?php echo $id_CG;?>&idCaso=<?php echo $idCaso;?>'">
												<?php } ?>
												</td> 
												<?php }else  { //sin ser tu medida ?>
													<td><?php 
													if ($es==0) { ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/no_ejecutada.png" height="40" width="40" disabled>
														</div>
													<?php }else if($es==1){ ?>
													 	<div class="6u 12u$(small)">
															<input type="image" src="images/ejecutada.png" height="40" width="40" disabled>
														</div>
													<?php } ?>
												</td>
												<td>
												<?php if ($es==1) { 
													if (empty($observaciones)) { ?>
													 <input type="image" src="images/seguimiento.png" height="45" width="45" disabled>
													<?php } else{ echo $observaciones; } ?>
													
												<?php } else if($es==0){ ?>
														<input type="image" src="images/editar.png" width="30" height="30" disabled>
														<input type="image" src="images/eliminar.png" width="30" height="30" disabled>
														<input type="image" src="images/seguimiento.png" width="30" height="30" disabled>
												<?php } ?>
												</td>
												<?php } //cierre sin ser tu medida ?>
												
												<td><?php  echo $responsable; ?></td>
											</tr>
										<?php } ?>
										</tbody>
									</table>

										<input type="button" name="asignar_curso" class="button fit" value="Agregar medida de protección" onclick="location='reg_medida.php?idCaso=<?php echo $idCaso;?>'">

										<input type="button" name="asignar_curso" class="button special fit" value="cancelar" onclick="location='perfil_caso.php?id=<?php echo $idCaso;?>'">
										
										<a href="reporte.php?idCaso=<?php echo $idCaso;?>" class="button fit" target="_blank">descargar</a>
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