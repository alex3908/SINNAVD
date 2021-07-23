<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$ss="SELECT id, departamento from depto";
	$ess=$mysqli->query($ss);
	if(!empty($_POST))
	{
	$fec=$_POST['mess'];
	$pf="SELECT fechas, mes from cortes where id=$fec";
	$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
	$sp = $_POST['sp'];
	$persona=$_POST['persona'];
	
	$vid="SELECT id from departamentos where id_depto='$sp'";
	$evid=$mysqli->query($vid);
	
	if ($persona=='Todos') {
		$losR=$persona;
	}else {
		$losrr="SELECT responsable from departamentos where id=$persona";
		$elosrr=$mysqli->query($losrr);
		
		while ($row=$elosrr->fetch_assoc()) {
			$losR=$row['responsable'];
		}
	}

	if ($persona=='Todos') {
		$idRespo='';
	while ($row=$evid->fetch_assoc()) {	
		$idRespo="'".$row['id']."',".$idRespo;
	}
	$idRespo=trim($idRespo, ',');
	}else {
		$idRespo="'".$persona."'";
	}

	$deee="SELECT departamento from depto where id='$sp'";
	$edeee=$mysqli->query($deee);
	
	$primera="SELECT municipios.municipio, localidades.localidad, nna.id, concat(nna.apellido_p, ' ', nna.apellido_m, ' ',nna.nombre) as nom, nna.curp, nna.fecha_nac, nna.sexo from nna, municipios, localidades where fecha_reg in ($fff) and municipios.id=nna.municipio and localidades.id=nna.localidad and nna.respo_reg in ($idRespo)";
	$eprimera=$mysqli->query($primera);
	$contadorniños=$eprimera->num_rows;

//Medidas contenidas
$mc="SELECT benefmed.id from benefmed, cuadro_guia where cuadro_guia.id_medida in('03') and cuadro_guia.id_sp_registro in($idRespo) and cuadro_guia.fecha in($fff) and cuadro_guia.id=benefmed.id_medida";
$emc=$mysqli->query($mc);
$cantemc=$emc->num_rows;

//Medidas realizadas
$mr="SELECT benefmed.id from benefmed, cuadro_guia where cuadro_guia.id_medida in('03') and cuadro_guia.estado=1 and cuadro_guia.id_sp_registro in($idRespo) and cuadro_guia.fecha_eje in($fff) and cuadro_guia.id=benefmed.id_medida";
$emr=$mysqli->query($mr);
$cantemr=$emr->num_rows;

//Medidas urgentes contenidas
$muc="SELECT benefmed.id from benefmed, cuadro_guia where cuadro_guia.id_medida in('01','02') and cuadro_guia.id_sp_registro in($idRespo) and cuadro_guia.fecha in($fff) and cuadro_guia.id=benefmed.id_medida";
$emuc=$mysqli->query($muc);
$cantemuc=$emuc->num_rows;

//Medidas urgentes realizadas
$mur="SELECT benefmed.id from benefmed, cuadro_guia where cuadro_guia.id_medida in('01','02') and cuadro_guia.estado=1 and cuadro_guia.id_sp_registro in($idRespo) and cuadro_guia.fecha_eje in($fff) and cuadro_guia.id=benefmed.id_medida";
$emur=$mysqli->query($mur);
$cantemur=$emur->num_rows;

//Seguimientos
$seg="SELECT count(id) as total from seguimientos where respo_reg in($idRespo) and fecha in($fff)";
$eseg=$mysqli->query($seg);
while ($row=$eseg->fetch_assoc()) {
	$cantseg=$row['total'];
}

//Reportes recibidos
$repR="SELECT count(id) as total from reportes_vd where fecha in($fff) and asignado in($idRespo)";
$erepR=$mysqli->query($repR);
while ($row=$erepR->fetch_assoc()) {
	$cantRepR=$row['total'];
}

//Reportes positivos
$repP="SELECT count(id) as total from reportes_vd where fecha in($fff) and asignado in($idRespo) and atendido='4'";
$erepP=$mysqli->query($repP);
while ($row=$erepP->fetch_assoc()) {
	$cantRepP=$row['total'];
}

//Reportes atendidos
$repA="SELECT count(id) as total from reportes_vd where fecha in($fff) and asignado in($idRespo) and atendido in('2','3')";
$erepA=$mysqli->query($repA);
while ($row=$erepA->fetch_assoc()) {
	$cantRepA=$row['total'];
}

//carpetas iniciadas
$carpetaI="SELECT nna.apellido_p, nna.apellido_m, nna.nombre, count(carpeta_inv.id) as repre from nna, carpeta_inv, nna_caso where nna_caso.id_caso=carpeta_inv.id_caso and nna_caso.id_nna=nna.id and carpeta_inv.fecha_reg in($fff) and carpeta_inv.respo_reg in($idRespo) group by nna.id";
$ecarpetaI=$mysqli->query($carpetaI);
$contadorcarI=$ecarpetaI->num_rows;

//carpetas concluidas
$carpetaC="SELECT nna.apellido_p, nna.apellido_m, nna.nombre, count(carpeta_inv.id) as repre from nna, carpeta_inv, nna_caso where nna_caso.id_caso=carpeta_inv.id_caso and nna_caso.id_nna=nna.id and (carpeta_inv.estado=100 or carpeta_inv.tipo_pross>0) and carpeta_inv.fecha_reg in($fff) and carpeta_inv.respo_reg in($idRespo) group by nna.id";
$ecarpetaC=$mysqli->query($carpetaC);
$contadorcarC=$ecarpetaC->num_rows;

//carpetas asignadas
$carpetaA="SELECT nna.apellido_p, nna.apellido_m, nna.nombre, count(carpeta_inv.id) as repre from nna, carpeta_inv, nna_caso where nna_caso.id_caso=carpeta_inv.id_caso and nna_caso.id_nna=nna.id and carpeta_inv.fecha_reg in($fff) and carpeta_inv.asignado in($idRespo) group by nna.id";
$ecarpetaA=$mysqli->query($carpetaA);
$contadorcarA=$ecarpetaA->num_rows;

//carpetas concluidas asignadas
$carpetaCA="SELECT nna.apellido_p, nna.apellido_m, nna.nombre, count(carpeta_inv.id) as repre from nna, carpeta_inv, nna_caso where nna_caso.id_caso=carpeta_inv.id_caso and nna_caso.id_nna=nna.id and (carpeta_inv.estado=100 or carpeta_inv.tipo_pross>0) and carpeta_inv.fecha_reg in($fff) and carpeta_inv.asignado in($idRespo) group by nna.id";
$ecarpetaCA=$mysqli->query($carpetaCA);
$contadorcarCA=$ecarpetaCA->num_rows;
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
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
	
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">
				<!-- Main -->
					<div id="main">
						<div class="inner">
							<br> <br>	
							<?php if ($_SESSION['departamento']==16) { ?>
								<input type="button" class="button fit" value="siguiente" onclick="location='informedd2019.php'">
							<?php } ?>
						<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
									<div class="2u 12u$(xsmall)">
										<select name="mess">
											<option value="">MES</option>
											<?php $cor="SELECT id, mes from cortes";
						 						  $ecor=$mysqli->query($cor);
												while ($row=$ecor->fetch_assoc()) { ?>
											<option value="<?php echo $row['id']; ?>"><?php echo $row['mes']; ?></option>	 	
											<?php } ?>
										</select>
									</div>
									<div class="4u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="sp" name="sp" class="from-control" required>
												<option value="">--SUBPROCU--</option>
												<?php while ($row=$ess->fetch_assoc()) { ?>
												<option value="<?php echo $row['id'] ?>"><?php echo $row['departamento']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="3u 12u$(xsmall)">
										<div class="select-wrapper">
											<select id="persona" name="persona" class="from-control">
												<option value="Todos">--SELECCIONE</option>							
											</select>
										</div>
									</div>
									<div class="2u 12u$(xsmall)">
										<input type="submit" class="button fit" name="" value="Generar reporte">
									</div>
									<div class="1u 12u$(xsmall)">
										<input type="button" class="button fit" name="" value="salir" onclick="location='welcome.php'">
									</div>
								</div>
						</form>
<script type="text/javascript">
	$(document).ready(function(){
		$("#sp").change(function(){
			$.get("get_personal.php","sp="+$("#sp").val(), function(data){
				$("#persona").html(data);
				console.log(data);
			});
		});

		
	});
</script>
				<table>
					<thead>
						<tr>
							<td><h3><?php echo $me; ?></h3></td>
							<td><?php while ($row=$edeee->fetch_assoc()) { ?>
								<h3>Resultados de: <?php echo $row['departamento']; ?></h3>
								<?php } ?></td>
							<td><h4><?php echo $losR; ?></h4></td>
						</tr>
					</thead>
				</table>				
							
				<table>	
					<?php echo 'Total de nna nuevos: '.$contadorniños; ?>		
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Localidad</b></td>
					<td><b>Nombre</b></td>
					<td><b>CURP</b></td>
					<td><b>Fecha de nac</b></td>
					<td><b>Sexo</b></td>
					<td><b>NNA que se restituyen</b></td>
					<td><b>NNA con DV atendidos</b></td>
					<td><b>Planes</b></td>
					<td><b>Acciones contenidas</b></td>
					<td><b>Acciones realizadas</b></td>
					<td><b>Reportes recibidos</b></td>
					<td><b>Reportes atendidos</b></td>
					<td><b>Investigaciones realizadas</b></td>
					<td><b>Investigaciones positivas</b></td>
					<td><b>Representaciones iniciadas</b></td>
					<td><b>Representaciones concluidas</b></td>
					<td><b>Medidas urgentes determinadas</b></td>
					<td><b>Medidas urgentes realizadas</b></td>
					<td><b>Seguimientos otorgados</b></td>
					<td><b>Asistencia psicologica solicitada</b></td>
					<td><b>Asistencia psicologica realizada</b></td>
				</tr>
				<tbody>
				<?php while ($row=$eprimera->fetch_assoc()) { ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['localidad'];?></td>					
					<td><?php echo $row['nom'];?></td>					
					<td><?php echo $row['curp'];?></td>					
					<td><?php echo $row['fecha_nac'];?></td>					
					<td><?php echo $row['sexo'];?></td>		
					<?php $idn=$row['id']; 
					$tmed="SELECT count(cuadro_guia.id) as total from cuadro_guia, benefmed where benefmed.id_nna='$idn' and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida='03' and cuadro_guia.fecha in($fff) and cuadro_guia.id_sp_registro in($idRespo)"; $etmed=$mysqli->query($tmed);
					$tmede="SELECT count(cuadro_guia.id) as total from cuadro_guia, benefmed where benefmed.id_nna='$idn' and cuadro_guia.id=benefmed.id_medida and cuadro_guia.estado='1' and cuadro_guia.id_medida='03' and cuadro_guia.fecha in($fff) and cuadro_guia.id_sp_registro in($idRespo)"; $etmede=$mysqli->query($tmede);
					 ?>			
					<td></td>
					<td></td>
					<td></td>
					<td><?php while ($row=$etmed->fetch_assoc()) {
						echo $row['total'];
					} ?></td>
					<td><?php while ($row=$etmede->fetch_assoc()) {
						echo $row['total'];
					} ?></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<?php 
					$tmedu="SELECT count(cuadro_guia.id) as total from cuadro_guia, benefmed where benefmed.id_nna='$idn' and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida in('01','02') and cuadro_guia.fecha in($fff) and cuadro_guia.id_sp_registro in($idRespo)"; $etmedu=$mysqli->query($tmedu);
					$tmedue="SELECT count(cuadro_guia.id) as total from cuadro_guia, benefmed where benefmed.id_nna='$idn' and cuadro_guia.id=benefmed.id_medida and cuadro_guia.estado='1' and cuadro_guia.id_medida in('01','02') and cuadro_guia.fecha in($fff) and cuadro_guia.id_sp_registro in($idRespo)"; $etmedue=$mysqli->query($tmedue);
					 ?>	
					<td><?php while ($row=$etmedu->fetch_assoc()) {
						echo $row['total'];
					} ?></td>
					<td><?php while ($row=$etmedue->fetch_assoc()) {
						echo $row['total'];
					} ?></td>
					<?php $seg="SELECT count(seguimientos.id) as total from seguimientos, benefmed, cuadro_guia where seguimientos.respo_reg in($idRespo) and seguimientos.fecha in($fff) and seguimientos.id_med=cuadro_guia.id and cuadro_guia.id=benefmed.id_medida and benefmed.id_nna='$idn'";
					$eseg=$mysqli->query($seg); ?>
					<td><?php while ($row=$eseg->fetch_assoc()) {
						echo $row['total'];
					} ?></td>
					<td></td>
					<td></td>
				</tr>
				<?php } ?>
				</tbody>
			</table>	
			
			<table>	
			<?php echo 'Total de carpetas iniciadas: '.$contadorcarI; ?>		
				<tr>
					<td><b>Nombre</b></td>
					<td><b>Carpetas iniciadas</b></td>
				</tr>
				<tbody>
					<?php while ($row=$ecarpetaI->fetch_assoc()) { ?>		
				<tr>
					<td><?php echo $row['apellido_p'].' '.$row['apellido_m'].' '.$row['nombre'];?></td>
					<td><?php echo $row['repre'];?></td>		
				</tr>
			<?php } ?>
				</tbody>
			</table>

			<table>		
			<?php echo 'Total de carpetas concluidas: '.$contadorcarC; ?>	
				<tr>
					<td><b>Nombre</b></td>
					<td><b>Carpetas concluidas</b></td>
				</tr>
				<tbody>
				<?php while ($row=$ecarpetaC->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['apellido_p'].' '.$row['apellido_m'].' '.$row['nombre'];?></td>
					<td><?php echo $row['repre'];?></td>					
				</tr>
				<?php } ?>
				</tbody>
			</table>

			<table>	
			<?php echo 'Total de carpetas asignadas: '.$contadorcarA; ?>		
				<tr>
					<td><b>Nombre</b></td>
					<td><b>Carpetas asignadas</b></td>
				</tr>
				<tbody>
					<?php while ($row=$ecarpetaA->fetch_assoc()) { ?>		
				<tr>
					<td><?php echo $row['apellido_p'].' '.$row['apellido_m'].' '.$row['nombre'];?></td>
					<td><?php echo $row['repre'];?></td>		
				</tr>
			<?php } ?>
				</tbody>
			</table>

			<table>		
			<?php echo 'Total de carpetas asignadas concluidas: '.$contadorcarCA; ?>	
				<tr>
					<td><b>Nombre</b></td>
					<td><b>Carpetas asignadas concluidas</b></td>
				</tr>
				<tbody>
				<?php while ($row=$ecarpetaCA->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['apellido_p'].' '.$row['apellido_m'].' '.$row['nombre'];?></td>
					<td><?php echo $row['repre'];?></td>					
				</tr>
				<?php } ?>
				</tbody>
			</table>

			<table>			
				<tr>
					<td><b><a href="medidas_corte.php?fec=<?php echo $fec; ?>&res=<?php echo $idRespo; ?>">Medidas de proteccion contenidas</a></b></td>
					<td><b>Medidas de proteccion realizadas</b></td>
					<td><b>Medidas de proteccion urgentes contenidas</b></td>
					<td><b>Medidas de proteccion urgentes realizadas</b></td>					
					<td><b><a href="seguimientos_corte.php?fec=<?php echo $fec; ?>&res=<?php echo $persona; ?>">Seguimientos registrados</a></b></td>
					<td><b>Reportes recibidos</b></td>
					<td><b>Reportes atendidos</b></td>
					<td><b>Reportes positivos</b></td>
				</tr>
				
				<tbody>								
				<tr>
					<td><?php echo $cantemc;?></td>					
					<td><?php echo $cantemr;?></td>					
					<td><?php echo $cantemuc;?></td>					
					<td><?php echo $cantemur;?></td>
					<td><?php echo $cantseg;?></td>					
					<td><?php echo $cantRepR;?></td>					
					<td><?php echo $cantRepA;?></td>					
					<td><?php echo $cantRepP;?></td>										
				</tr>			
				</tbody>
			</table>		
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