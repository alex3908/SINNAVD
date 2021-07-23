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

//Reportes registrados SELECT count(id) as total from reportes_vd where fecha in('27/2/2019','28/2/2019','1/3/2019,'2/3/2019','3/3/2019','4/3/2019','5/3/2019','6/3/2019','7/3/2019','8/3/2019','9/3/2019','10/3/2019','11/3/2019','12/3/2019','13/3/2019','14/3/2019','15/3/2019','16/3/2019','17/3/2019','18/3/2019','19/3/2019','20/3/2019','21/3/2019','22/3/2019','23/3/2019','24/3/2019','25/3/2019')
$repRc="SELECT count(id) as total from reportes_vd where fecha in($fff) and respo_reg in($idRespo)";
$erepRc=$mysqli->query($repRc);
while ($row=$erepRc->fetch_assoc()) {
	$cantRepRc=$row['total'];
}

//Reportes recibidos
$rep1="SELECT count(id) as total from reportes_vd where fecha_asi in($fff) and asignado in($idRespo)";
$erep1=$mysqli->query($rep1);
while ($row=$erep1->fetch_assoc()) {
	$cant1=$row['total'];
}
$rep2="SELECT count(id) as total from reportes_vd where fecha_asigp in($fff) and asignado_psic in($idRespo)";
$erep2=$mysqli->query($rep2);
while ($row=$erep2->fetch_assoc()) {
	$cant2=$row['total'];
}
$rep3="SELECT count(id) as total from reportes_vd where fecha_asij in($fff) and asignado_j in($idRespo)";
$erep3=$mysqli->query($rep3);
while ($row=$erep3->fetch_assoc()) {
	$cant3=$row['total'];
}
$cantRA=$cant1+$cant2+$cant3;

//Reportes positivos
$repP="SELECT count(id) as total from reportes_vd where fecha_ate in($fff) and (asignado in($idRespo) or asignado_j in($idRespo) or asignado_psic in($idRespo)) and atendido='4'";
$erepP=$mysqli->query($repP);
while ($row=$erepP->fetch_assoc()) {
	$cantRepP=$row['total'];
}

//Reportes atendidos
$repA="SELECT count(id) as total from reportes_vd where fecha_ate in($fff) and (asignado in($idRespo) or asignado_j in($idRespo) or asignado_psic in($idRespo)) and atendido in('2','3','4')";
$erepA=$mysqli->query($repA);
while ($row=$erepA->fetch_assoc()) {
	$cantRepA=$row['total'];
}

//acercamientos fam
$acerF="SELECT count(id) as total from acercamiento_familiar where fecha_reg in ($fff) and respo_reg in ($idRespo) and inter is not null";
$eacerF=$mysqli->query($acerF);
while ($row=$eacerF->fetch_assoc()) {
	$cantAcF=$row['total'];
}

//intervenciones familiares
$intf="SELECT sum(inter) as total from acercamiento_familiar where fecha_reg in ($fff) and respo_reg in ($idRespo) and inter is not null";
$eintf=$mysqli->query($intf);
while ($row=$eintf->fetch_assoc()) {
	$cantint=$row['total'];
}

//cantidad de acercamiento psic
$acp="SELECT count(id) as total from acercamiento_psic where fecha_reg in ($fff) and respo_reg in ($idRespo) and otros is not null";
$eacp=$mysqli->query($acp);
while ($row=$eacp->fetch_assoc()) {
	$cantacP=$row['total'];
}
//cantidad de nna
$primera="SELECT municipios.municipio, localidades.localidad, nna.id, concat(nna.apellido_p, ' ', nna.apellido_m, ' ',nna.nombre) as nom, nna.curp, nna.fecha_nac, nna.sexo from nna, municipios, localidades where fecha_reg in ($fff) and municipios.id=nna.municipio and localidades.id=nna.localidad and nna.respo_reg in ($idRespo)";
$eprimera=$mysqli->query($primera);
$contadorniños=$eprimera->num_rows;

//cantidad de casos registrados
$casR="SELECT count(id) as total from casos where funcionario_reg in ($idRespo) and fecha in ($fff)";
$ecasR=$mysqli->query($casR);
while ($row=$ecasR->fetch_assoc()) {
	$cantcas=$row['total'];
}

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

//carpetas registradas
$carpetaI="SELECT nna.apellido_p, nna.apellido_m, nna.nombre, count(carpeta_inv.id) as repre from nna, carpeta_inv, nna_caso where nna_caso.id_caso=carpeta_inv.id_caso and nna_caso.id_nna=nna.id and carpeta_inv.fecha_reg in($fff) and carpeta_inv.respo_reg in($idRespo) group by nna.id";
$ecarpetaI=$mysqli->query($carpetaI);
$contadorcarI=$ecarpetaI->num_rows;

//carpetas asignadas
$carpetaA="SELECT nna.apellido_p, nna.apellido_m, nna.nombre, count(carpeta_inv.id) as repre from nna, carpeta_inv, nna_caso where nna_caso.id_caso=carpeta_inv.id_caso and nna_caso.id_nna=nna.id and carpeta_inv.fecha_reg in($fff) and carpeta_inv.asignado in($idRespo) group by nna.id";
$ecarpetaA=$mysqli->query($carpetaA);
$contadorcarA=$ecarpetaA->num_rows;

$vis="SELECT count(substring(fecha_ingreso,1,LOCATE(' ', fecha_ingreso)-1)) as total from historial where substring(fecha_ingreso,1,LOCATE(' ', fecha_ingreso)-1) in ($fff) and responsable in ($idRespo) and fecha_salida is not null";
$evis=$mysqli->query($vis);
while ($row=$evis->fetch_assoc()) {
	$cantvis=$row['total'];
}

$usua="SELECT count(id) as total from usuarios where fecha_reg in ($fff) and respo_reg in ($idRespo)";
$eusua=$mysqli->query($usua);
while ($row=$eusua->fetch_assoc()) {
	$cantusu=$row['total'];
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
			
					<table class="alt">
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
				</tr>
				<?php } ?>
				</tbody>
			</table>			
			<table class="alt">			
				<tr>
					<td><b>1. Reportes registrados</b></td>
					<td><b>2. Reportes asignados</b></td>
					<td><b>3. Reportes atendidos</b></td>
					<td><b>4. Reportes positivos</b></td>					
					<td><b>5. Acercamientos familiares</b></td>
					<td><b>5.1 Intervenciones</b></td>
					<td><b>6. Acercamientos con NNA</b></td>
				</tr>
				
				<tbody>								
				<tr>
					<td><?php echo $cantRepRc;?></td>					
					<td><?php echo $cantRA;?></td>				
					<td><?php echo $cantRepA;?></td>					
					<td><?php echo $cantRepP;?></td>
					<td><?php echo $cantAcF;?></td>					
					<td><?php echo $cantint;?></td>					
					<td><?php echo $cantacP;?></td>										
				</tr>			
				</tbody>
			</table>	
			<table class="alt">			
				<tr>
					<td><b>7. NNA registrados</b></td>
					<td><b>8. Casos registrados</b></td>
					<td><b>9. Carpetas registradas</b></td>
					<td><b>10. Carpetas asignadas</b></td>						
					<td><b>14. Visitas de usuarios atendidas</b></td>
					<td><b>15. Usuarios registrados</b></td>
				</tr>
				
				<tbody>								
				<tr>
					<td><?php echo $contadorniños;?></td>					
					<td><?php echo $cantcas;?></td>					
					<td><?php echo $contadorcarI;?></td>					
					<td><?php echo $contadorcarA;?></td>									
					<td><?php echo $cantvis;?></td>										
					<td><?php echo $cantusu;?></td>										
				</tr>			
				</tbody>
			</table>
			<table class="alt">			
				<tr>
					<td colspan="2" align="center"><b>11. Medidas decretadas:</b> <?php echo $cantemc+$cantemuc;?></td>
					<td colspan="2" align="center"><b>12. Medidas ejecutadas:</b> <?php echo $cantemr+$cantemur;?></td>
					<td rowspan="2"><b>13. Seguimientos</b></td>	
				</tr>
				<tr>
					<td><b>Medidas de protección especial</b></td>
					<td><b>Medidas urgentes de protección</b></td>
					<td><b>Medidas de protección especial ejecutadas</b></td>
					<td><b>Medidas urgentes de protección ejecutadas</b></td>					
				</tr>
				<tbody>								
				<tr>
					<td><?php echo $cantemc;?></td>					
					<td><?php echo $cantemuc;?></td>					
					<td><?php echo $cantemr;?></td>
					<td><?php echo $cantemur;?></td>
					<td><?php echo $cantseg;?></td>
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