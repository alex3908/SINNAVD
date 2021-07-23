<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$fechas ='24/11/2018,25/11/2018,26/11/2018,27/11/2018,28/11/2018,29/11/2018,30/11/2018,1/12/2018,2/12/2018,3/12/2018,4/12/2018,5/12/2018,6/12/2018,7/12/2018,8/12/2018,9/12/2018,10/12/2018,11/12/2018,12/12/2018';
	
	$arry=explode(",", $fechas);
	$long=count($arry);
	$fff='';
	for ($i=0; $i <$long ; $i++) { 
		$ff="'".$arry[$i]."',";
		$fff=$ff.$fff;
	}
	
	$fff=trim($fff, ',');
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
							
			

			<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
			<div class="row uniform">
			<div class="6u 12u$(xsmall)">
			<input type="button" value="REgresar" class="button special fit" onclick="location='welcome.php'"></div>
			<div class="6u 12u$(xsmall)">
			<input type="submit" value="Muni y Loca" name="ml" class="button special fit"></div>
			<div class="3u 12u$(xsmall)">
			<input type="submit" class="button fit" name="pobnv" value="población nueva"></div>					
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I1" name="I1"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I2" name="I2"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I3" name="I3"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I4" name="I4"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I5" name="I5"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I6" name="I6"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I7" name="I7"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I8" name="I8"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I19" name="I19"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I9" name="I9"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I10" name="I10"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I11" name="I11"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I12" name="I12"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I13" name="I13"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I14" name="I14"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I15" name="I15"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I16" name="I16"></div>
			<div class="1u 12u$(xsmall)">
			<input type="submit" class="button fit" value="I17" name="I17"></div>			
			<div class="3u 12u$(xsmall)">
			<input type="submit" class="button fit" name="nnadr" value="nna derechos restituidos">
			</div>	
			</div>
			
			
			
			
			
			</form>

	<?php if(!empty($_POST['pobnv'])){ 
			$primera="SELECT municipios.municipio, localidades.localidad, nna.id, concat(nna.apellido_p, ' ', nna.apellido_m, ' ',nna.nombre) as nom, nna.curp, nna.fecha_nac, nna.sexo from nna, municipios, localidades where fecha_reg in ($fff) and municipios.id=nna.municipio and localidades.id=nna.localidad";
			$eprimera=$mysqli->query($primera);
			$contadorniños=$eprimera->num_rows; ?>
		<div class="box">
			<h3>Poblacion nueva</h3>
			<table>	
				<?php echo 'Total de nna nuevos: '.$contadorniños; ?>		
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Localidad</b></td>
					<td><b>Nombre</b></td>
					<td><b>Fecha de nac</b></td>
					<td><b>Edad</b></td>
					<td><b>CURP</b></td>				
					<td><b>Sexo</b></td>					
				</tr>
				<tbody>
				<?php while ($row=$eprimera->fetch_assoc()) { ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['localidad'];?></td>					
					<td><?php echo $row['nom'];?></td>	
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['curp'];?></td>										
					<td><?php echo $row['sexo'];?></td>					
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } if(!empty($_POST['nnadr'])){
		$sql="SELECT municipios.municipio, localidades.localidad, nna.id, concat(nna.apellido_p, ' ', nna.apellido_m, ' ',nna.nombre) as nom, nna.curp, nna.fecha_nac, nna.sexo from nna, municipios, localidades, nna_restituidos where nna_restituidos.fecha_reg in ($fff) and municipios.id=nna.municipio and localidades.id=nna.localidad and nna_restituidos.id_nna=nna.id";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>Acciones en el plan de restitución de derechos contenidad</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Localidad</b></td>
					<td><b>Nombre</b></td>
					<td><b>Fecha de nac</b></td>
					<td><b>Edad</b></td>
					<td><b>CURP</b></td>				
					<td><b>Sexo</b></td>										
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>					
					<td><?php echo $row['localidad'];?></td>					
					<td><?php echo $row['nom'];?></td>	
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['fecha_nac'];?></td>
					<td><?php echo $row['curp'];?></td>										
					<td><?php echo $row['sexo'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>			
	<?php } if(!empty($_POST['I1'])){ 
			$sql="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total from municipios, benefmed, nna, cuadro_guia where nna.municipio=municipios.id and nna.id=benefmed.id_nna and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida in ('03') and cuadro_guia.fecha_eje in($fff) and cuadro_guia.estado='1' group by municipios.id having count(benefmed.id_nna)";				
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I1.- Acciones en el plan de restitución de derechos realizadas</h3>
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
	<?php } if(!empty($_POST['I2'])){
		$sql="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total from municipios, benefmed, nna, cuadro_guia where nna.municipio=municipios.id and nna.id=benefmed.id_nna and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida in ('03') and cuadro_guia.estado in('1','0') and cuadro_guia.fecha in ($fff) group by municipios.id having count(benefmed.id_nna)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I2.- Acciones en el plan de restitución de derechos contenidad</h3>
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
	<?php } if(!empty($_POST['I3'])){
		$sql="SELECT clm, count(clm) as total from reportes_vd where atendido in('3','4') and fecha in ($fff) group by clm having count(clm)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I3.- Reportes de casos de vulneracion de derechos atendidos</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['clm'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php }  if(!empty($_POST['I4'])){
		$sql="SELECT clm, count(clm) as total from reportes_vd where atendido in('1','2','3','4') and fecha_ate in ($fff) group by clm having count(clm)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I4.- Reportes de casos de vulneracion de derechos recibidos</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['clm'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>	
	<?php }  if(!empty($_POST['I5'])){
		$sql="SELECT clm, count(clm) as total from reportes_vd where atendido in('4') and fecha in ($fff) group by clm having count(clm)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h4>I5.- Investigaciones de trabajo social que resultaron positivas en vulneracion de derechos</h4>
			<h3>I3.- Reportes de casos de vulneracion de derechos atendidos</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['clm'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php }  if(!empty($_POST['I6'])){
		$sql="SELECT clm, count(clm) as total from reportes_vd where atendido in('3','4') and fecha in ($fff) group by clm having count(clm)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h4>I6.- Investigaciones de trabajo social realizadas</h4>
			<h3>I3.- Reportes de casos de vulneracion de derechos atendidos</h3>
			<table>
				<tr>
					<td><b>Municipio</b></td>
					<td><b>Cantidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['clm'];?></td>					
					<td><?php echo $row['total'];?></td>							
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php }  if(!empty($_POST['I7'])){
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
	<?php }  if(!empty($_POST['I8'])){
		$sql="SELECT municipios.municipio, count(carpeta_inv.id) as total from municipios, carpeta_inv where carpeta_inv.municipio_d=municipios.id and carpeta_inv.fecha_reg in ($fff) group by municipios.municipio having count(carpeta_inv.id)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I8.- Representaciones juridicas iniciadas</h3>
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
	<?php }  if(!empty($_POST['I19'])){
		$sql="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total from municipios, benefmed, nna, cuadro_guia where nna.municipio=municipios.id and nna.id=benefmed.id_nna and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida in ('01','02') and cuadro_guia.estado in('1') and cuadro_guia.fecha in ($fff) group by municipios.id having count(benefmed.id_nna)";			
			$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h3>I19.- Medidas de proteccion urgentes realizadas</h3>
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
	<?php }  if(!empty($_POST['I9'])){
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
	<?php }  if(!empty($_POST['ml'])){
		$sql="SELECT DISTINCT municipios.municipio from nna, municipios where municipios.id=nna.municipio and nna.fecha_reg in ($fff)";			
			$esql=$mysqli->query($sql);
		$sql1="SELECT DISTINCT localidades.localidad from nna, localidades, municipios where municipios.id=nna.municipio and localidades.id=nna.localidad and nna.fecha_reg in ($fff)";		
			$esql1=$mysqli->query($sql1); ?>
		<div class="row uniform">
			<div class="6u 12u$(xsmall)">
				<div class="box">
			
			<table>
				<tr>
					<td><b>Municipio</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['municipio'];?></td>										
				</tr>
				<?php } ?>
				</tbody>
			</table>
				</div>
			</div>
			<div class="6u 12u$(xsmall)">
				<div class="box">
			
			<table>
				<tr>
					<td><b>Localidad</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$esql1->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['localidad'];?></td>										
				</tr>
				<?php } ?>
				</tbody>
			</table>
				</div>
			</div>
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