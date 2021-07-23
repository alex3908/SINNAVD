<?php	
	session_start();
	require 'conexion.php';
	$me=null;
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}	
	$idDEPTO = $_SESSION['id'];	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Informes 2019</title>
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
			<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
			<center><h2>-Informes 2019-</h2></center>
			<div class="row uniform">
			<div class="1u 12u$(xsmall)">
			<input type="button" value="<--" class="button special fit" onclick="location='welcome.php'"></div>
			<div class="1u 12u$(xsmall)">
				<select name="mess">
					<option value="">mes</option>
					<?php $cor="SELECT id, mes from cortes where año='2019'";
						  $ecor=$mysqli->query($cor);
						while ($row=$ecor->fetch_assoc()) { ?>
					<option value="<?php echo $row['id']; ?>"><?php echo $row['mes']; ?></option>	 	
					<?php } ?>
				</select>
			</div>
			<div class="2u 12u$(xsmall)">
				<input type="submit" value="Muni y Loca" name="ml" class="button special fit">
			</div>
			<div class="2u 12u$(xsmall)">
				<input type="submit" class="button fit" name="poba" value="pobl. adulta">
			</div>			
			<div class="2u 12u$(xsmall)">
				<input type="submit" class="button fit" name="pobnv" value="pobl. nueva nna">
			</div>
			<div class="2u 12u$(xsmall)">
				<input type="submit" class="button fit" name="pobns" value="pobl. nna seg.">
			</div>
			<div class="2u 12u$(xsmall)">
				<input type="submit" class="button fit" name="nnadr" value="nna d. restituidos">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="C1" name="C1">
			</div>	
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="A1" name="A1">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="A2" name="A2">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="A3" name="A3">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="A4" name="A4">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="A5" name="A5">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="A6" name="A6">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="A10" name="A10">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="A22" name="A22">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="A23" name="A23">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="A24" name="A24">
			</div>
			<div class="1u 12u$(xsmall)">
				<input type="submit" class="button fit small" value="EXTRA" name="EXTRA">
			</div>
			</div>
			</form>
	<?php  if(!empty($_POST['ml'])){
			$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
		$sql="SELECT  municipios.municipio, count(DISTINCT localidades.localidad) as total 
				from nna, municipios, localidades 
				where municipios.id=localidades.id_mun and localidades.id=nna.localidad and municipios.id=nna.municipio and nna.fecha_reg in ($fff) and nna.curp!='0' 
				group by municipios.municipio";			
		$esql=$mysqli->query($sql);
		$sql1="SELECT distinct localidades.localidad as total 
				from nna, municipios, localidades 
				where municipios.id=localidades.id_mun and localidades.id=nna.localidad and municipios.id=nna.municipio and nna.fecha_reg in ($fff) and nna.curp!='0'";			
		$esql1=$mysqli->query($sql1);	
		$tt=$esql1->num_rows;
			 ?>
		<div class="box">
			<div class="row uniform">
			<div class="6u 12u$(xsmall)">			
			<table><caption><?php echo $tt; ?></caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Localidades</b></td>

				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) { ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>										
					<td><?php echo $row['total'];?></td>										
				</tr>
				<?php } ?>
				</tbody>
			</table>
			</div>
			</div>
		</div>	
	<?php } } if(!empty($_POST['poba'])){
			$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
			$primera="SELECT municipios.municipio as mm, localidades.localidad as ll, usuarios.municipio, usuarios.localidad, usuarios.apellido_p, usuarios.apellido_m, usuarios.nombre, usuarios.fecha_nac, usuarios.curp, sexo.sexo from usuarios, sexo, municipios, localidades where  usuarios.curp!='0' and sexo.id=usuarios.id_sexo and usuarios.fecha_reg in ($fff) and municipios.id=usuarios.id_mun and localidades.id=usuarios.id_loc" ;
			$eprimera=$mysqli->query($primera);
			$contadorniños=$eprimera->num_rows; ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>Poblacion nueva adulta</h3>
			<table>	
				<?php echo 'Total de adultos nuevos: '.$contadorniños; ?>		
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Localidad</b></td>
					<td><b>Apellido paterno</b></td>
					<td><b>Apellido materno</b></td>
					<td><b>Nombre(s)</b></td>
					<td><b>Fecha de nac</b></td>
					<td><b>Edad</b></td>
					<td><b>CURP</b></td>				
					<td><b>Sexo</b></td>					
				</tr>
				<tbody>
				<?php while ($row=$eprimera->fetch_assoc()) { ?>				
				<tr>
					<td><?php echo $row['mm'];?></td>					
					<td><?php echo $row['ll'];?></td>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['localidad'];?></td>					
					<td><?php echo $row['apellido_p'];?></td>	
					<td><?php echo $row['apellido_m'];?></td>	
					<td><?php echo $row['nombre'];?></td>	
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['curp'];?></td>										
					<td><?php echo $row['sexo'];?></td>					
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>	
	<?php } } if(!empty($_POST['pobnv'])){
			$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
			$primera="SELECT municipios.id as claveid, municipios.municipio, localidades.clave, localidades.localidad, nna.id, nna.apellido_p, nna.apellido_m, nna.nombre, nna.curp, nna.fecha_nac, nna.sexo from nna, municipios, localidades where nna.fecha_reg in ($fff) and municipios.id=nna.municipio and localidades.id=nna.localidad and nna.curp!='0'";
			$eprimera=$mysqli->query($primera);
			$contadorniños=$eprimera->num_rows; ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>Poblacion nueva</h3>
			<table>	
				<?php echo 'Total de nna nuevos: '.$contadorniños; ?>		
				<tr>
					<td><b>Clave M</b></td>
					<td><b>Municipio</b></td>
					<td><b>Clave L</b></td>
					<td><b>Localidad</b></td>
					<td><b>Apellido paterno</b></td>
					<td><b>Apellido materno</b></td>
					<td><b>Nombre(s)</b></td>
					<td><b>Fecha de nac</b></td>
					<td><b>Edad</b></td>
					<td><b>CURP</b></td>				
					<td><b>Sexo</b></td>					
				</tr>
				<tbody>
				<?php while ($row=$eprimera->fetch_assoc()) { ?>				
				<tr>
					<td><?php echo $row['claveid'];?></td>					
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['clave'];?></td>					
					<td><?php echo $row['localidad'];?></td>					
					<td><?php echo $row['apellido_p'];?></td>	
					<td><?php echo $row['apellido_m'];?></td>	
					<td><?php echo $row['nombre'];?></td>	
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['curp'];?></td>										
					<td><?php echo $row['sexo'];?></td>					
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } } if(!empty($_POST['pobns'])){
			$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
			$primera="SELECT municipios.id as clam, municipios.municipio, localidades.clave, localidades.localidad, nna.apellido_p, nna.apellido_m, nna.nombre, nna.fecha_nac, nna.curp, nna.sexo from localidades, municipios, nna, benefmed, cuadro_guia, seguimientos where benefmed.id_nna=nna.id and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id=seguimientos.id_med and seguimientos.fecha in($fff) and localidades.id=nna.localidad and municipios.id=nna.municipio";
			$segunda="SELECT municipios.id as clam, municipios.municipio, localidades.clave, localidades.localidad, nna.apellido_p, nna.apellido_m, nna.nombre, nna.fecha_nac, nna.curp, nna.sexo from localidades, municipios, nna, benefmed, cuadro_guia where benefmed.id_nna=nna.id and cuadro_guia.id=benefmed.id_medida and cuadro_guia.fecha in($fff) and localidades.id=nna.localidad and municipios.id=nna.municipio";
			$eprimera=$mysqli->query($primera);
			$esegunda=$mysqli->query($segunda);
			$contador1=$eprimera->num_rows;
			$contador2=$esegunda->num_rows; ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>Poblacion subsecuente</h3>
			<table>	
				<?php echo 'Total de nna seguimientos: '.$contador1; ?>		
				<?php echo 'Total de nna medidas: '.$contador2; ?>		
				<tr>
					<td><b>Clave M</b></td>
					<td><b>Municipio</b></td>
					<td><b>Clave L</b></td>
					<td><b>Localidad</b></td>
					<td><b>Apellido paterno</b></td>
					<td><b>Apellido materno</b></td>
					<td><b>Nombre(s)</b></td>
					<td><b>Fecha de nac</b></td>
					<td><b>Edad</b></td>
					<td><b>CURP</b></td>				
					<td><b>Sexo</b></td>					
				</tr>
				<tbody>
				<?php while ($row=$eprimera->fetch_assoc()) { ?>				
				<tr>
					<td><?php echo $row['clam'];?></td>					
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['clave'];?></td>					
					<td><?php echo $row['localidad'];?></td>					
					<td><?php echo $row['apellido_p'];?></td>	
					<td><?php echo $row['apellido_m'];?></td>	
					<td><?php echo $row['nombre'];?></td>	
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['curp'];?></td>										
					<td><?php echo $row['sexo'];?></td>					
				</tr>
				<?php } ?>
				<tr><td></td></tr>
				<?php while ($row=$esegunda->fetch_assoc()) { ?>				
				<tr>
					<td><?php echo $row['clam'];?></td>					
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['clave'];?></td>					
					<td><?php echo $row['localidad'];?></td>					
					<td><?php echo $row['apellido_p'];?></td>	
					<td><?php echo $row['apellido_m'];?></td>	
					<td><?php echo $row['nombre'];?></td>	
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['curp'];?></td>										
					<td><?php echo $row['sexo'];?></td>					
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } } if(!empty($_POST['nnadr'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
		$sql="SELECT  municipios.id as claveid, localidades.clave, municipios.municipio, localidades.localidad, nna.id, nna.apellido_p, nna.apellido_m, nna.nombre, nna.curp, nna.fecha_nac, nna.sexo from nna, municipios, localidades, nna_restituidos where nna_restituidos.fecha_reg in ($fff) and municipios.id=nna.municipio and localidades.id=nna.localidad and nna_restituidos.id_nna=nna.id";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>NNA que se restituyen sus derechos</h3>
			<table>
				<tr>
					<td><b>Clave M</b></td>
					<td><b>Municipio</b></td>
					<td><b>Clave L</b></td>
					<td><b>Localidad</b></td>
					<td><b>Apellido paterno</b></td>
					<td><b>Apellido materno</b></td>
					<td><b>Nombre(s)</b></td>
					<td><b>Fecha de nac</b></td>
					<td><b>Edad</b></td>
					<td><b>CURP</b></td>				
					<td><b>Sexo</b></td>										
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['claveid'];?></td>					
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['clave'];?></td>				
					<td><?php echo $row['localidad'];?></td>				
					<td><?php echo $row['apellido_p'];?></td>	
					<td><?php echo $row['apellido_m'];?></td>	
					<td><?php echo $row['nombre'];?></td>	
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['curp'];?></td>										
					<td><?php echo $row['sexo'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>			
	<?php } } if(!empty($_POST['C1'])){
	 		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
			$sql="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total from municipios, benefmed, nna, cuadro_guia where nna.municipio=municipios.id and nna.id=benefmed.id_nna and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida in ('03','01') and cuadro_guia.fecha_eje in($fff) and cuadro_guia.estado='1' group by municipios.id having count(benefmed.id_nna)";				
			$esql=$mysqli->query($sql); 
			$sql2="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total from municipios, benefmed, nna, cuadro_guia where nna.municipio=municipios.id and nna.id=benefmed.id_nna and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida in ('03','01') and cuadro_guia.estado in('1','0') and cuadro_guia.fecha in ($fff) group by municipios.id having count(benefmed.id_nna)";			
			$esql2=$mysqli->query($sql2); ?>
			
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1.- Planes de restitución de derechos</h3>
			<div class="uniform row">
		<div class="6u 12u$(xsmall)">
			<table>
				<caption>Medidas decretadas ejecutadas</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="6u 12u$(xsmall)">
			<table>
				<caption>Medidas decretadas</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql2->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		</div>
		</div>
	<?php } } if(!empty($_POST['A1'])){
	 		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
			$sql="SELECT municipios.municipio, count(reportes_vd.clm) as total from reportes_vd, municipios where reportes_vd.atendido in('4') and reportes_vd.fecha_ate in ($fff) and reportes_vd.clm=municipios.id group by municipios.municipio having count(reportes_vd.clm)";			
			$esql=$mysqli->query($sql);
			$sql2="SELECT municipios.municipio, count(reportes_vd.clm) as total from reportes_vd, municipios where reportes_vd.atendido in ('1','2','3','4') and reportes_vd.fecha in ($fff) and reportes_vd.clm=municipios.id group by municipios.municipio having count(reportes_vd.clm)";			
			$esql2=$mysqli->query($sql2);
			 ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A1.- Recibir reportes de posible vulneracion de derechos</h3>
			<div class="uniform row">
				<div class="6u 12u$(xsmall)">
				<table>
				<caption>Reportes positivos</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
				</table>	
				</div>
				<div class="6u 12u$(xsmall)">
				<table>
				<caption>Reportes recibidos</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql2->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
				</table>	
				</div>
			</div>
			
		</div>
	
	<?php } } if(!empty($_POST['A2'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.id, municipios.municipio, sum(acercamiento_familiar.inter) as total 
		from municipios, reportes_vd, acercamiento_familiar 
		where acercamiento_familiar.id_reporte=reportes_vd.id 
		and reportes_vd.clm=municipios.id and acercamiento_familiar.fecha_reg in ($fff) 
		and acercamiento_familiar.fecha_inter in ($fff) 
		group by municipios.id having sum(acercamiento_familiar.inter)";			
		$esql=$mysqli->query($sql);
		
		$sql2="SELECT municipios.municipio, count(reportes_vd.clm) as total 
		from reportes_vd, municipios where reportes_vd.atendido in ('1','2','3','4') 
		and reportes_vd.recepcion!='MINISTERIO PUBLICO' and reportes_vd.fecha in ($fff) 
		and reportes_vd.clm=municipios.id group by municipios.municipio having count(reportes_vd.clm)";			
		$esql2=$mysqli->query($sql2); ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A2.- Realizar intervención de trabajo social</h3>
			<div class="uniform row">
				<div class="4u 12u$(xsmall)">
				<table>
				<caption>Intervenciones de trabajo social</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
				</table>	
				</div>
				<div class="4u 12u$(xsmall)">
				<table>
				<caption>Reportes recibidos</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql2->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
				</table>	
				</div>
			</div>
		</div>
	<?php } } if(!empty($_POST['A3'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.id, municipios.municipio, count(acercamiento_psic.id) as total 
		from municipios, reportes_vd, acercamiento_psic where acercamiento_psic.id_reporte=reportes_vd.id 
		and reportes_vd.clm=municipios.id and acercamiento_psic.fecha_reg in ($fff)
		group by municipios.id having count(acercamiento_psic.id)";			
		$esql=$mysqli->query($sql);
		$sql2="SELECT municipios.municipio, count(nna.municipio) as total from municipios, nna 
		where nna.fecha_reg in ($fff) and nna.curp!='0' and municipios.id=nna.municipio 
		group by municipios.id having count(nna.municipio)";			
		$esql2=$mysqli->query($sql2);
		 ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A3.- Realizar intervención de psicologia</h3>
			<div class="uniform row">
				<div class="6u 12u$(xsmall)">
				<table>
				<caption>Intervenciones de psicologia</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
				</table>	
				</div>				
				
				<div class="6u 12u$(xsmall)">
				<table>
				<caption>NNA con derechos vulnerados atendidos</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql2->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
				</table>	
				</div>
			</div>
		</div>
	<?php } } if(!empty($_POST['A4'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.municipio, count(substring(historial.fecha_ingreso,1,
		LOCATE(' ', historial.fecha_ingreso)-1)) as total from usuarios, historial, municipios 
		where usuarios.id=historial.id_usuario and usuarios.id_mun=municipios.id 
		and substring(historial.fecha_ingreso,1,LOCATE(' ', historial.fecha_ingreso)-1)  in($fff) 
		and ( historial.atencion_brindada like '%ORIENTACION JURIDICA%') and historial.asunto='INICIAL' 
		group by usuarios.id_mun having count(substring(historial.fecha_ingreso,1,
		LOCATE(' ', historial.fecha_ingreso)-1))";			
		$esql=$mysqli->query($sql); 
		$ev=$esql->num_rows; ?>

		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A4.- Realizar orientaciones juridicas</h3>
			<div class="uniform row">
				<div class="12u 12u$(xsmall)">
				<?php if ($ev>0) {  ?>
					<table>
				<caption>Orientaciones juridicas realizadas</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			<?php }else { echo $ev; }  ?>
				</table>	
				</div>
				
			</div>
		</div>
	<?php } } if(!empty($_POST['A5'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
			$sql="SELECT municipios.municipio, count(carpeta_inv.id) as total from municipios, carpeta_inv 
			where carpeta_inv.municipio_d=municipios.id and carpeta_inv.fecha_reg in ($fff) 
			group by municipios.municipio having count(carpeta_inv.id)";			
			$esql=$mysqli->query($sql);
			$esql2=$mysqli->query($sql); ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A5.- Brindar Representación coadyuvante</h3>
			<div class="uniform row">
				<div class="6u 12u$(xsmall)">
			<table>
				<caption>Representaciones brindadas</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
				<div class="6u 12u$(xsmall)">
			<table>
				<caption>Representaciones solicitadas</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql2->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
			</div>
		</div>
	<?php } } if(!empty($_POST['A6'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
			$sql="SELECT municipios.municipio, count(seguimientos.id) as total 
			from municipios, nna, benefmed, seguimientos where municipios.id=nna.municipio 
			and nna.id=benefmed.id_nna and benefmed.id_medida=seguimientos.id_med
			 and seguimientos.fecha in ($fff) group by municipios.municipio having count(seguimientos.id)";			
			$esql=$mysqli->query($sql); 
			$sql2="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as
			 total from municipios, benefmed, nna, cuadro_guia where nna.municipio=municipios.id 
			 and nna.id=benefmed.id_nna and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida 
			 in ('03','01') and cuadro_guia.estado in('1','0') and cuadro_guia.fecha in ($fff) 
			 group by municipios.id having count(benefmed.id_nna)";			
			$esql2=$mysqli->query($sql2);?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A6.- Realizar seguimientos</h3>
			<div class="uniform row">
				<div class="6u 12u$(xsmall)">
			<table>
				<caption>Seguimientos realizados</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
				<div class="6u 12u$(xsmall)">
			<table>
				<caption>Medidas de proteccion decretadas</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql2->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
			</div>
		</div>
	<?php } } if(!empty($_POST['A10'])){
		$sql="SELECT municipios.municipio, count(carpeta_inv.id) as total from municipios, carpeta_inv where carpeta_inv.municipio_d=municipios.id and carpeta_inv.estado='100' and carpeta_inv.fecha_reg in ($fff) group by municipios.municipio having count(carpeta_inv.id)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I7.- Representaciones juridicas concluidas</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php }  if(!empty($_POST['A22'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.municipio, count(benefmed.id_nna) as total from cuadro_guia, benefmed, nna, municipios where cuadro_guia.id_mp='28' and cuadro_guia.fecha in($fff) and nna.municipio=municipios.id and nna.id=benefmed.id_nna and benefmed.id_medida=cuadro_guia.id group by municipios.municipio having count(benefmed.id_nna)";			
		$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A22.- Realizar retorno seguro de NNA hidalguenses</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) { ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } } if(!empty($_POST['A23'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.municipio, count(benefmed.id_nna) as total from municipios, nna,benefmed, cuadro_guia where municipios.id=nna.municipio and benefmed.id_nna=nna.id and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_mp='30' AND cuadro_guia.fecha in ($fff) group by municipios.municipio";
		$esql=$mysqli->query($sql);

		$sql2="SELECT municipios.municipio, count(nna.id) as total from municipios, nna where municipios.id=nna.municipio and nna.fecha_reg in ($fff) and nna.curp='EXTRANJERO' group by municipios.municipio";
		$esql2=$mysqli->query($sql2); ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A23.- Brindar acompañamiento de atención especial a NNA extranjeros migrantes</h3>
			<div class="uniform row">
				<div class="6u 12u$(xsmall)">
			<table>
				<caption>Acompañamientos brindados</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="6u 12u$(xsmall)">
			<table>
				<caption>Total de NNA migrantes decretados</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql2->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } } if(!empty($_POST['A24'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.municipio, sum(ccpi.na+ccpi.ni+ccpi.adm+ccpi.adh+ccpi.am+ccpi.ah) as total from ccpi, municipios where ccpi.municipio=municipios.id and fecha_reg in ($fff) group by municipios.id";
		$esql=$mysqli->query($sql);

		$sql2="SELECT municipios.municipio, count(ccpi.id) as total from ccpi, municipios where ccpi.municipio=municipios.id and fecha_reg in ($fff) group by municipios.id";
		$esql2=$mysqli->query($sql2); ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A24.- Implementar actividades en materia de migración infantil</h3>
			<div class="uniform row">
				<div class="6u 12u$(xsmall)">
			<table>
				<caption>Asistentes a actividades</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="6u 12u$(xsmall)">
			<table>
				<caption>Total de actividades</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql2->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } } if(!empty($_POST['EXTRA'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where id=$fec";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
			$sql="SELECT municipios.municipio, count(carpeta_inv.id) as total from municipios, carpeta_inv where carpeta_inv.municipio_d=municipios.id and carpeta_inv.fecha_reg in ($fff) group by municipios.municipio having count(carpeta_inv.id)";			
			$esql=$mysqli->query($sql);
			$sql2="SELECT municipios.municipio, count(carpeta_inv.id) as total from municipios, carpeta_inv where carpeta_inv.municipio_d=municipios.id and ((carpeta_inv.fecha_estado in ($fff) and carpeta_inv.estado='100') or (carpeta_inv.fecha_tipo in ($fff) and carpeta_inv.tipo_pross>'0')) group by municipios.municipio having count(carpeta_inv.id)";		
			$esql2=$mysqli->query($sql2); ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A5.- Brindar Representación coadyuvante</h3>
			<div class="uniform row">
				<div class="6u 12u$(xsmall)">
			<table>
				<caption>Representaciones solicitadas</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
				<div class="6u 12u$(xsmall)">
			<table>
				<caption>Representaciones concluidas</caption>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql2->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
			</div>
		</div>
	<?php } } if(!empty($_POST['I9'])){
		$sql="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total from municipios, benefmed, nna, cuadro_guia where nna.municipio=municipios.id and nna.id=benefmed.id_nna and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida in ('01','02') and cuadro_guia.estado in('1','0') and cuadro_guia.fecha in ($fff) group by municipios.id having count(benefmed.id_nna)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I9.- Medidas de proteccion urgentes determinadas</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php }  if(!empty($_POST['I10'])){
		$sql="SELECT municipios.id ,municipios.municipio, count(seguimientos.id) as total from municipios, benefmed, nna, cuadro_guia, seguimientos where nna.municipio=municipios.id and nna.id=benefmed.id_nna and cuadro_guia.id=benefmed.id_medida and seguimientos.fecha in($fff) and cuadro_guia.id_medida in ('01','02','03') and cuadro_guia.estado in('1','0') and seguimientos.id_med=cuadro_guia.id group by municipios.id having count(seguimientos.id)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I10.- Seguimientos a la ejecucion de medidas</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } if(!empty($_POST['I11'])){ 
			$sql="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total from municipios, benefmed, nna, cuadro_guia where nna.municipio=municipios.id and nna.id=benefmed.id_nna and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida in ('03') and cuadro_guia.fecha_eje in($fff) and cuadro_guia.estado='1' group by municipios.id having count(benefmed.id_nna)";				
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I11.- Medidas de proteccion especial realizadas</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php }  if(!empty($_POST['I16'])){
		$sql="SELECT municipios.municipio, count(carpeta_inv.id) as total from municipios, carpeta_inv where carpeta_inv.municipio_d=municipios.id and carpeta_inv.estado='100' and carpeta_inv.fecha_reg in ($fff) group by municipios.municipio having count(carpeta_inv.id)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I16.- Procedimientos penales concluidos</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php }  if(!empty($_POST['I17'])){
		$sql="SELECT municipios.municipio, count(carpeta_inv.id) as total from municipios, carpeta_inv where carpeta_inv.municipio_d=municipios.id and carpeta_inv.fecha_reg in ($fff) group by municipios.municipio having count(carpeta_inv.id)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I17.- Procedimientos penales tramitados</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } ?>
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