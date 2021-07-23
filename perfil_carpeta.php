<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idCarpeta = $_GET['id'];
	$fecha= date ("j/n/Y");
	$fec= date("Y-m-d H:i:s");
	$ad="SELECT id from departamentos where id_personal='1' and id_depto='16' and id='$idDEPTO'";
	$ead=$mysqli->query($ad);
	$road=$ead->num_rows;

	$query = "SELECT casos.folio_c, carpeta_inv.nuc, date_format(carpeta_inv.fecha_inicio,'%d/%m/%Y') as fecha_ini, distritos.distrits, municipios.municipio, delitos.delito, carpeta_inv.imputado, carpeta_inv.relacion, carpeta_inv.mesa, carpeta_inv.estado, carpeta_inv.fecha_reg, carpeta_inv.respo_reg, departamentos.responsable, padre_fallecido_covid, madre_fallecida_covid from casos, distritos, municipios, delitos, carpeta_inv inner join departamentos on departamentos.id=carpeta_inv.respo_reg where carpeta_inv.id='$idCarpeta' and carpeta_inv.id_caso=casos.id and distritos.id=carpeta_inv.distrito and municipios.id=carpeta_inv.municipio_d and delitos.id=carpeta_inv.id_delito"; 
	$resultado=$mysqli->query($query);
	$resultado2=$mysqli->query($query);

	$sss="SELECT departamentos.responsable, carpeta_inv.asignado, carpeta_inv.respo_reg from carpeta_inv, departamentos where carpeta_inv.id='$idCarpeta' and departamentos.id=carpeta_inv.asignado";
	$ssss=$mysqli->query($sss);
	while ($row=$ssss->fetch_assoc()) {
		$RC=$row['responsable'];
		$idAsignado=$row['asignado'];
		$resReg=$row['respo_reg'];
	}

$carp="SELECT id_caso from carpeta_inv where id='$idCarpeta'";
$ecarp=$mysqli->query($carp);
while ($row=$ecarp->fetch_assoc()) {
	$idCaso=$row['id_caso'];
}
$sqlnna="SELECT nna.id, nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, nna.fecha_nacimiento as fecha_nac, nna.sexo from nna inner join nna_caso on nna_caso.id_nna=nna.id
where nna_caso.id_caso='$idCaso' and nna_caso.estado='NE'";
	$esqlnna=$mysqli->query($sqlnna);

	$sqlnnaE="SELECT nna_exposito.id, nna_exposito.folio, nna_exposito.sexo, nna_exposito.fecha_reg, municipios.municipio, departamentos.responsable from nna_exposito, municipios, departamentos, nna_caso where nna_caso.id_caso='$idCaso' and nna_caso.id_nna=nna_exposito.id and nna_caso.estado='E' and departamentos.id=nna_exposito.respo_reg and municipios.id=nna_exposito.municipio_deteccion";
	$esqlnnaE=$mysqli->query($sqlnnaE);

	
	$validaNE="SELECT id from nna_caso where id_caso='$idCaso' and estado='NE'";
	$evalNE=$mysqli->query($validaNE);
	$rowNE=$evalNE->num_rows;
	
	$validaE="SELECT id from nna_caso where id_caso='$idCaso' and estado='E'";
	$evalE=$mysqli->query($validaE);
	$rowE=$evalE->num_rows;




	while ($row=$resultado2->fetch_assoc()) {
		$es=$row['estado'];
		$respo_r=$row['respo_reg'];
	}
	$nesta=$es+20;
	
	if (isset($_POST['sig_estado'])) { 
		$ss="UPDATE carpeta_inv set estado='$nesta', respo_estado='$idDEPTO', fecha_estado='$fecha' where id='$idCarpeta'";
		$ess=$mysqli->query($ss);
		header("location:perfil_carpeta.php?id=$idCarpeta");
	}
	if (isset($_POST['victimaNE'])) { 
		$idNNA = mysqli_real_escape_string($mysqli,$_POST['id_NNA']);
		$valida="SELECT id from victimas_c_inv where id_carp='$idCarpeta', id_nna='$idNNA', estado='NE'";
$eva=$mysqli->query($valida);

$rows=$eva->num_rows;
if ($rows>0) {
	
}else {

$sql="INSERT into victimas_c_inv (id_carp, id_nna, estado, res_reg) values ('$idCarpeta', '$idNNA','NE','$idDEPTO')";
$esql=$mysqli->query($sql);
	header("Location: perfil_carpeta.php?id=$idCarpeta");
}
	}

	if (isset($_POST['victimaE'])) { 
		$idNNA = mysqli_real_escape_string($mysqli,$_POST['id_NNAE']);
		$valida="SELECT id from victimas_c_inv where id_carp='$idCarpeta', id_nna='$idNNA', estado='E'";
$eva=$mysqli->query($valida);

$rows=$eva->num_rows;
if ($rows>0) {
	
}else {

$sql="INSERT into victimas_c_inv (id_carp, id_nna, estado, res_reg) values ('$idCarpeta', '$idNNA','E','$idDEPTO')";
$esql=$mysqli->query($sql);
	header("Location: perfil_carpeta.php?id=$idCarpeta");
}
	}

if (!empty($_POST['eliminar'])) {
        
        $sql="DELETE from carpeta_inv where id='$idCarpeta'";
        $esql=$mysqli->query($sql);
        if ($esql>0) {
            header("Location: perfil_caso.php?id=$idCaso");
        }
    }
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

						<div class="inner"><br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div> <br>	
								<?php if ($road>='1' or $resReg==$idDEPTO) { ?>
									<div class="12u$"><input type="button" name="" class="button fit special" value="reasignar" onclick="location='reasignar_car.php?id=<?php echo $idCarpeta;?>'"></div>
								<?php }else { } ?>

								<?php while($row=$resultado->fetch_assoc()){ ?>								
								<div class="row uniform">
									<div class="6u 12u$(xsmall)" align="center">
										
										<h1>NUC: <?php echo $row['nuc'];  ?></h1>
										<b>Responsable de registro: <?=$row['responsable']?></b>
										<?php $est=$row['estado'];
										if ($est==20) { ?>
										 <h3>Etapa: Investigación inicial</h3>
										<?php }else if ($est==40) { ?>
										  <h3>Etapa: Investigación complementaria</h3>
										<?php }else if ($est==60) { ?>
										  <h3>Etapa: Intermedia</h3>
										<?php }else if ($est==80) { ?>
										  <h3>Etapa: Juicio</h3>
										<?php }else if ($est==100) { ?>
										  <h3>Etapa: Ejecución</h3>
										<?php } ?>
										<h2>Representante coadyuvante: <?php echo $RC; ?></h2>
									</div>
									<div class="6u 12u$(xsmall)" align="center">
										 		<?php if ($est==20) { ?>
										<img src="images/G20.png" width="200">
										<?php }else if ($est==40) { ?>
										 <img src="images/G40.png" width="200">
										<?php }else if ($est==60) { ?>
										 <img src="images/G60.png" width="200">
										<?php }else if ($est==80) { ?>
										 <img src="images/G80.png" width="200">
										<?php }else if ($est==100) { ?>
										 <img src="images/G100.png" width="200">
										<?php } ?>
									</div>
								</div>
										
						<div class="row uniform">
							<div class="6u 12u$(xsmall)">
								<div class="box">
									<ul class="alt">
										<li><h4>Folio de caso: </h4><a href="perfil_caso.php?id=<?php echo $idCaso;?>"><?php echo $row['folio_c'];  ?></a> </li>
										<li><h4>Fecha de inicio: </h4><?php echo $row['fecha_ini'];  ?> </li>
										<li><h4>Distrito judicial: </h4><?php echo $row['distrits'];  ?> </li>
										<li><h4>Municipio del delito: </h4><?php echo $row['municipio'];  ?> </li>
										<?php if($row['padre_fallecido_covid']==1 and $row['madre_fallecida_covid']==0) { ?>
											<li>Padre fallecido por COVID</li>
										<?php }elseif($row['padre_fallecido_covid']==0 and $row['madre_fallecida_covid']==1) { ?>
											<li>Madre fallecida por COVID</li>
										<?php } elseif($row['padre_fallecido_covid']==1 and $row['madre_fallecida_covid']==1) {?>
											<li>Ambos padres fallecidos por COVID</li>
										<?php } ?>
									</ul>
								</div>
							</div>
							<div class="6u 12u$(xsmall)">
								<div class="box">
									<ul class="alt">
										<li><h4>Delito: </h4><?php echo $row['delito'];  ?> </li>
										<li><h4>Imputado: </h4><?php echo $row['imputado'];  ?> </li>
										<li><h4>Relación: </h4><?php echo $row['relacion'];  ?> </li>
										<li><h4>Mesa: </h4><?php echo $row['mesa'];  ?> </li>
									</ul>
								</div>
							</div>
							</div><br>
							
								<div class="box">
								<div class="table-wrapper"><h4>VICTIMAS</h4>
									<?php if ($rowNE>0) { ?>	
																	
									<table class="alt">								
										<thead>
											<tr>
												<th>FOLIO</th>
												<th>NOMBRE</th>
												<th>EDAD</th>
												<th>SEXO</th>
												<th></th>												
											</tr>
										</thead>
										<tbody>
										<?php while($row=$esqlnna->fetch_assoc()){ ?>
											<tr>
												<td><?php echo $row['folio'];?></td>
												<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
												<td>
													<?php $fecha_nacimiento= $row['fecha_nac'];	
													if($fecha_nacimiento=='1900-01-01' or empty($fecha_nacimiento))	
														$edad="Sin registro"; 
													else {
														$anioN=date('Y', strtotime($fecha_nacimiento));  //calcular edad
							         					$anioA=date('Y', strtotime($fec));
							         					$mesN=date('m', strtotime($fecha_nacimiento));
							         					$mesA=date('m', strtotime($fec));
							         					$diaN=date('d', strtotime($fecha_nacimiento));
							         					$diaA=date('d', strtotime($fec));
							         					if(($mesN<$mesA) or ($mesN==$mesA and $diaN<=$diaA)){
							         					    $anios=$anioA-$anioN;
							         					    $meses=$mesA-$mesN;	
							         					    if($anios==1)
							         					    	$cadAnio=" año, ";
							         					    else
							         					    	$cadAnio=" años, ";
							         					    if ($meses==1)
							         					    	$cadMes= " mes";
							         					    else 
							         					    	$cadMes=" meses";	         					    
							         					} else {
							         					    $anios=$anioA-$anioN-1; 
							         					    $meses=12-($mesN-$mesA);
							         					    if($anios==1)
							         					    	$cadAnio=" año, ";
							         					    else
							         					    	$cadAnio=" años, ";
							         					    if ($meses==1)
							         					    	$cadMes= " mes";
							         					    else 
							         					    	$cadMes=" meses";	
							         					}
							         					$edad= $anios.$cadAnio.$meses.$cadMes;
						         					} 
						         					echo $edad; ?>
						         				</td>
												<td><?php echo $row['sexo'];?></td>
												<td><form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
												<input type="hidden" name="id_NNA" value="<?php echo $row['id'];?>">
												<?php $id_nn=$row['id'];
												$carita="SELECT id_nna, id_carp, estado from victimas_c_inv where id_nna='$id_nn' and id_carp='$idCarpeta' and estado='NE'";
												$ecarita=$mysqli->query($carita);
												$rowa=$ecarita->num_rows; 
												if ($rowa>0) { ?>
													<input type="button"  name="" value="DIRECTA">
												<?php }else { ?>
												<input type="submit"  name="victimaNE" value="INDIRECTA">
												<?php } ?>
												</form>
													</td>
												
											</tr>
										<?php } ?>
										</tbody>
									</table>
									<?php }else {} if ($rowE>0) { ?>
										
									
									<table class="alt">
									
										<thead>
											<tr>
												<th>FOLIO</th>
												<th>SEXO</th>
												<th>MUNICIPIO DE DETECCION</th>
												<th>FECHA DE REGISTRO</th>
												<th>RESPONSABLE DE REGISTRO</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										<?php while($row=$esqlnnaE->fetch_assoc()){ ?>
											<tr>
												<td><?php echo $row['folio'];?></td>
												<td><?php echo $row['sexo'];?></td>
												<td><?php echo $row['municipio'];?></td>
												<td><?php echo $row['fecha_reg'];?></td>
												<td><?php echo $row['responsable'];?></td>
												
												<td><form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
												<input type="hidden" name="id_NNAE" value="<?php echo $row['id'];?>">
												<?php $id_nn=$row['id'];
												$carita="SELECT id_nna, id_carp, estado from victimas_c_inv where id_nna='$id_nn' and id_carp='$idCarpeta' and estado='E'";
												$ecarita=$mysqli->query($carita);
												$rowa=$ecarita->num_rows; 
												if ($rowa>0) { ?>
													<input type="button"  name="" value="DIRECTA">
												<?php }else { ?>
												<input type="submit"  name="victimaE" value="INDIRECTA">
												<?php } ?>
												</form>
													</td>
											</tr>
										<?php } ?>
										</tbody>
									</table>
									<?php }else{} ?>
								</div></div>
						
						<?php 
							$consulta="SELECT carpeta_inv.tipo_pross, departamentos.responsable from departamentos, carpeta_inv where carpeta_inv.id='$idCarpeta' and carpeta_inv.respo_tipo=departamentos.id";
							$eco=$mysqli->query($consulta);
							while ($row=$eco->fetch_assoc()) {
								$tipoP=$row['tipo_pross'];
								$rr=$row['responsable'];
							} ?>
							<div class="12u$">
									<input type="button" name="asignar_curso" class="button fit" value="audiencias" onclick="location='audienciaxcarpeta.php?id=<?php echo $idCarpeta;?>'">		
								</div>
								
							
						<?php if ($tipoP==0) { ?>
							<div class="row uniform">
								
								<div class="6u 12u$(xsmall)">
									<input type="button" name="asignar_curso" class="button special fit" value="terminar investigación" onclick="location='terminar_inves.php?id=<?php echo $idCarpeta;?>'">		
								</div>
								<div class="6u 12u$(xsmall)">
								<?php if ($idAsignado==$idDEPTO) { ?>
									<input type="button" name="asignar_curso" class="button special fit" value="Editar" onclick="location='editar_carpeta.php?id=<?php echo $idCarpeta;?>'">
								<?php }else if ($resReg==$idDEPTO) { ?>
									<input type="button" name="asignar_curso" class="button special fit" value="Editar" onclick="location='editar_carpeta.php?id=<?php echo $idCarpeta;?>'">
								<?php }else if ($road>'0') { ?>
									<input type="button" name="asignar_curso" class="button special fit" value="Editar" onclick="location='editar_carpeta.php?id=<?php echo $idCarpeta;?>'">
								<?php }else { ?>
									<input type="button" name="asignar_curso" class="button special fit" value="Editar">
								<?php } ?>
									
								</div>
								<div class="6u 12u$(xsmall)">
									<input type="button" name="asignar_curso" class="button fit" value="solucion alterna o terminacion anticipada" onclick="location='solucion.php?id=<?php echo $idCarpeta;?>'">
								</div>
								
								<div class="6u 12u$(xsmall)">
								 <form id="estado_es" name="estado_es" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								 <?php if ($es<100) { ?>
								 	<input type="submit" name="sig_estado" class="button fit" value="siguiente etapa" >
								 <?php }else{ ?>
								 	<input type="submit" name=":)" class="button fit" value="carpeta terminada" >
								 	<?php } ?>
									
							

								 </form>
								</div>
								<?php if ($_SESSION['departamento']==16){ ?>							
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								<div class="12u 12u$(xsmall)">
									<input type="submit" name="eliminar" value="eliminar" class="button fit">
								</div>
								</form>
								<?php }else {} ?>
								
							</div><br><br>
						<?php }else{ ?>
							
							<br>
							<div class="box">			
								<div class="row uniform">
								<br>
                    				<div class="12u$">
                    				<?php if ($tipoP==1) { ?>
                    					<h3>Investigación terminada</h3>		
                    					<h2>Archivo temporal</h2>
                    				<?php }else if($tipoP==2){ ?>
                    					<h3>Investigación terminada</h3>
										<h2>Facultad de abstenerse de investigar</h2>
                    				<?php }else if($tipoP==3){ ?>
                    					<h3>Investigación terminada</h3>
										<h2>No ejercicio de la accion</h2>
                    				<?php }else if($tipoP==4){ ?>
                    					<h3>Investigación terminada</h3>
										<h2>Casos en los que operan los criterios de oportunidad</h2>
                    				<?php }else if($tipoP==5){ ?>
                    					<h3>Solución alterna</h3>
										<h2>Acuerdo reparatorio</h2>
                    				<?php }else if($tipoP==6){ ?>
                    					<h3>Solución alterna</h3>
										<h2>Suspensión condicional del proceso antes del juicio</h2>
                    				<?php }else if($tipoP==7){ ?>
                    					<h3>Terminación anticipada</h3>
                    					<h2>Procedimiento abreviado</h2>
                    				<?php }else if($tipoP==8){ ?>
                    					<h3>Investigación terminada</h3>
										<h2>Incompetencia</h2>
                    				<?php } ?>
                    					<h4>Dictado por: <?php echo $rr; ?></h4>
									</div>
								<br><br>
                                </div>
							</div>
						<br>
						<?php 
							}} ?>

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
										<li><a href="logout.php" ">Cerrar sesión</a></li>
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