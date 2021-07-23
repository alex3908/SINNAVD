<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
	}	
	$idDEPTO = $_SESSION['id'];	

	$qmes="SELECT id, mes, fechas from cortes where año='2020'"; 
	$rmes=$mysqli->query($qmes); 
	$numMeses=$rmes->num_rows;
	if ($numMeses> 0)  //llena un array con los datos de la consulta
	{  
		for ($i=1; $i<=$numMeses; $i++)  
		{  
			$vermes=$rmes->fetch_assoc();  
			$meses[$i] = $vermes["mes"];  
		}  
				  
	}  
	
	$qmir="SELECT nombre from matriz_de_indicadorez where bandera=1";
	$rmir=$mysqli->query($qmir);
	$numMir=$rmir->num_rows;
	if($numMir>0)
	{
		for ($i=0; $i<$numMir;$i++)
		{
			$verMir=$rmir->fetch_assoc();
			$indicador[$i]= $verMir["nombre"];
		}

	}

?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Informes
		</title>
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
							<div class="1.5u 12u$(xsmall)">
								<input type="button" value="regresar" class="button special fit" onclick="location='reporte1ra.php'">
							</div>
							<div class="2u 12u$(xsmall)">
								<select name="mess" class="form-control" required>
									<option value="">Seleccione un mes</option>
									<?php foreach($meses as $key=>$value) {//itinerancia sobre el array	// Si coincide lo enviado por el formulario con tu valor (para que el select no se limpie al enviar)
										if($_POST["mess"]==$key) {
											echo "<option value='".$key."' selected>".$value."</option>";
										}else{
											echo "<option value='".$key."'>".$value."</option>";
										}	
									} ?>
								</select>
							</div>
							<div class="7u 12u$(xsmall)">
								<select name="indicador" class="form-control" >
									<option value="">Indicadores para resultados</option>
									<?php foreach($indicador as $key=>$value) {
										if($_POST["indicador"]==$key)
										{
											echo "<option value='".$key."' selected>".$value."</option>";
										}else{
											echo "<option value='".$key."'>".$value."</option>";
										}	
									} ?>
								</select>
							</div>
							<div class="1.5u 12u$(xsmall">
								<input type="submit" value="Consultar" name="Consulta" class="button special fit">
							</div>
						</div>
						<div class="row uniform">
							<div class="3u 12u$(xsmall)">
								<input type="submit" value="Municipios y localidades" name="ml" class="button fit">
							</div>
							<div class="3u 12u$(xsmall)">
								<input type="submit" class="button fit" name="poba" value="Población adulta">
							</div>			
							<div class="3u 12u$(xsmall)">
								<input type="submit" class="button fit" name="pobnv" value="Población nueva NNA">
							</div>
							<div class="3u 12u$(xsmall)">
								<input type="submit" class="button fit" name="pobns" value="NNA seguimientos">
							</div>
							<div class="3u 12u$(xsmall)">
								<input type="submit" class="button fit" name="nnadr" value="NNa D. Restituidos">
							</div>
							<div class="3u 12u$(xsmall)">
								<input type="submit" class="button fit" name="carpetasIni" value="Carpetas iniciadas">
							</div>
							<div class="3u 12u$(xsmall)">
								<input type="submit" class="button fit" name="carpetasTer" value="Carpetas terminadas">
							</div>
							<div class="3u 12u$(xsmall)">
								<input type="submit" class="button fit" name="delitos" value="Delitos">
							</div>
						</div>
					</form>
					<?php  if(!empty($_POST['ml'])){  //Boton municipios y loalidades
						$fec=$_POST['mess'];
						if (empty($fec)) {
							echo "Selecciona el mes";
						}else {
							$pf="SELECT fechai, fechac, fechas, mes from cortes where idXaño=$fec and año='2020'";
							$epf=$mysqli->query($pf);
							while ($row=$epf->fetch_assoc()) {
								$fff=$row['fechas'];
								$me=$row['mes'];
								$fechai=$row['fechai'];
								$fechac=$row['fechac'];
							}
							$sql="SELECT municipios.municipio, count(DISTINCT localidades.localidad) as total 
							from nna left join municipios on municipios.id=nna.municipio 
							left join localidades on localidades.id=nna.localidad
							where nna.activo=1 and nna.fecha_registro between '$fechai' and '$fechac'
							and (nna.curp!='0' and curp is not null) group by municipios.municipio";	
							$esql=$mysqli->query($sql);
							$sql1="SELECT distinct nna.localidad as total from nna
							where nna.fecha_registro between '$fechai' and '$fechac' 
							and (nna.curp!='0' and curp is not null)";	
							$esql1=$mysqli->query($sql1);	
							$tt=$esql1->num_rows; ?>
							<div class="box">
								<div class="row uniform">
									<div class="12u 12u$(xsmall)">	
										<table>
											<caption>Total <?php echo $tt; ?></caption>
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
						<?php } 
					} 
					if(!empty($_POST['poba'])){
						$fec=$_POST['mess'];
						
						if (empty($fec)) {
							echo "Selecciona el mes";
						}else {
							$pf="SELECT fechai, fechac, fechas, mes from cortes where idXaño=$fec and año='2020'";
							$epf=$mysqli->query($pf);
							while ($row=$epf->fetch_assoc()) {
								$fff=$row['fechas'];
								$me=$row['mes'];
								$fechai=$row['fechai'];
								$fechac=$row['fechac'];
							}
						$primera="SELECT municipios.municipio, localidades.localidad, usuarios.apellido_p, usuarios.apellido_m, usuarios.nombre, 
						date_format(usuarios.fecha_nacimiento, '%d/%m/%Y') as fecha_nac, usuarios.curp, 
						sexo.sexo, date_format(usuarios.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, if(month(fecha_nacimiento)= month(fecha_registro),if(day(fecha_nacimiento)>=day(fecha_registro) ,year(fecha_registro)-year(fecha_nacimiento)-1,year(fecha_registro)-year(fecha_nacimiento)), if(month(fecha_nacimiento)>=month(fecha_registro) ,year(fecha_registro)-year(fecha_nacimiento)-1,year(fecha_registro)-year(fecha_nacimiento))) as edad,
  if(indigena=1,'SI','NO') as indigena, if(afrodescendiente=1,'SI', 'NO') as afrodescendiente, if(llegoAlEdo=1, 'SI', 'NO') as llegoAlEstado,violentado,tipoviolencia 
						from usuarios left join municipios on municipios.id=usuarios.id_mun
						left join localidades on localidades.id=usuarios.id_loc
						left join sexo on sexo.id=usuarios.id_sexo
						where usuarios.activo=1 and (usuarios.curp!='0' and usuarios.curp is not null) and 
						usuarios.fecha_registro between '$fechai' and '$fechac'
						order by usuarios.id" ;

						$eprimera=$mysqli->query($primera);
						$contadorpobla=$eprimera->num_rows; ?>
						<div class="box">
							<h4><?php echo $me; ?></h4>
							<h3>Poblacion nueva adulta</h3>
							<table>	
								<?php echo 'Total de adultos nuevos: '.$contadorpobla; ?>		
								<tr>
									<td><b>Municipio</b></td>
									<td><b>Localidad</b></td>
									<td><b>Apellido paterno</b></td>
									<td><b>Apellido materno</b></td>
									<td><b>Nombre(s)</b></td>
									<td><b>Fecha de nac</b></td>
									<td><b>CURP</b></td>				
									<td><b>Sexo</b></td>
									<td><b>Edad</b></td>
									<td><b>Indígena</b></td>
									<td><b>Afrodescendiente</b></td>
									<td><b>Llego al estado</b></td>
									<td><b>Tipo de violencia sufrida</b></td>
									<td><b>Fecha registro</b></td>					
								</tr>
								<tbody>
									<?php while ($row=$eprimera->fetch_assoc()) { ?>
										<tr>
											<td><?php echo $row['municipio'];?></td>					
											<td><?php echo $row['localidad'];?></td>
																
											<td><?php echo $row['apellido_p'];?></td>	
											<td><?php echo $row['apellido_m'];?></td>	
											<td><?php echo $row['nombre'];?></td>	
											<td><?php if($row['fecha_nac']=='01/01/1900') echo ""; else echo $row['fecha_nac'];?></td></td>
											<td><?php echo $row['curp'];?></td>										
											<td><?php echo $row['sexo'];?></td>	
											<td><?php echo $row['edad'];?></td>
											<td><?php echo $row['indigena'];?></td>
											<td><?php echo $row['afrodescendiente'];?></td>
											<td><?php echo $row['llegoAlEstado'];?></td>
											<td><?php echo $row['tipoviolencia'];?></td>
											<td><?php echo $row['fecha_reg'];?></td>					
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					<?php } 
				} if(!empty($_POST['pobnv'])){
					$fec=$_POST['mess'];
					if (empty($fec)) {
						echo "Selecciona el mes";
					}else {
						$pf="SELECT fechai, fechac,fechas, mes from cortes where idXaño=$fec and año='2020'";
						$epf=$mysqli->query($pf);
						while ($row=$epf->fetch_assoc()) {
							$fff=$row['fechas'];
							$me=$row['mes'];
							$fechai=$row['fechai'];
							$fechac=$row['fechac'];
						}
						$primera="SELECT municipios.id as claveid, municipios.municipio, localidades.clave, localidades.localidad, nna.id, nna.apellido_p, 
						nna.apellido_m, nna.nombre, nna.curp, date_format(nna.fecha_nacimiento,'%d/%m/%Y') as fecha_nac, if(month(fecha_nacimiento)= month(fecha_registro),if(day(fecha_nacimiento)>=day(fecha_registro) ,year(fecha_registro)-year(fecha_nacimiento)-1,year(fecha_registro)-year(fecha_nacimiento)), if(month(fecha_nacimiento)>=month(fecha_registro) ,year(fecha_registro)-year(fecha_nacimiento)-1,year(fecha_registro)-year(fecha_nacimiento))) as edad, nna.sexo, 
						date_format(nna.fecha_registro, '%d/%m/%Y %H:%i:%i') as fecha_reg, if(indigena=1,'SI','NO') as indigena, if(afrodescendiente=1,'SI', 'NO') as afrodescendiente, if(llegoAlEstado=1, 'SI', 'NO') as llegoAlEstado,violentado,tipoviolencia 
						from nna left join municipios on municipios.id=nna.municipio
						left join localidades on localidades.id=nna.localidad
						where nna.activo=1 and nna.fecha_registro between '$fechai' and '$fechac' 
						  and (curp!='0' and curp is not null)";
						$eprimera=$mysqli->query($primera);
						$contadorniños=$eprimera->num_rows; ?>
						<div class="box">
							<h4><?php echo $me; ?></h4>
							<h3>Poblacion nueva</h3>
							<table>	
								<?php echo 'Total de nna nuevos: '.$contadorniños; ?>		
								<tr>
									<td><b>ID</b></td>
									<td><b>Municipio</b></td>					
									<td><b>Localidad</b></td>
									<td><b>Apellido paterno</b></td>
									<td><b>Apellido materno</b></td>
									<td><b>Nombre(s)</b></td>
									<td><b>Fecha de nac</b></td>
									<td><b>Edad</b></td>
									<td><b>CURP</b></td>				
									<td><b>Sexo</b></td>
									<td><b>Indígena</b></td>
									<td><b>Afrodescendiente</b></td>
									<td><b>Llego al estado</b></td>
									<td><b>Tipo de violencia sufrida</b></td>
									<td><b>fecha registro</b></td>					
								</tr>
								<tbody>
								<?php while ($row=$eprimera->fetch_assoc()) { ?>				
								<tr>
									<td><?php echo $row['id'];?></td>
									<td><?php echo $row['municipio'];?></td>
									<td><?php echo $row['localidad'];?></td>					
									<td><?php echo $row['apellido_p'];?></td>	
									<td><?php echo $row['apellido_m'];?></td>	
									<td><?php echo $row['nombre'];?></td>	
									<td><?php if($row['fecha_nac']=='01/01/1900') echo ""; else echo $row['fecha_nac'];?></td>
									<td><?php echo $row['edad'];?></td>
									<td><?php echo $row['curp'];?></td>									
									<td><?php echo $row['sexo'];?></td>									
									<td><?php echo $row['indigena'];?></td>
									<td><?php echo $row['afrodescendiente'];?></td>
									<td><?php echo $row['llegoAlEstado'];?></td>
									<td><?php echo $row['tipoviolencia'];?></td>
									<td><?php echo $row['fecha_reg'];?></td>					
								</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					<?php } 
				} if(!empty($_POST['pobns'])){
					$fec=$_POST['mess'];
					if (empty($fec)) {
						echo "Selecciona el mes";
					}else {
						$pf="SELECT fechas, mes, fechai,fechac from cortes where idXaño=$fec and año='2020'";
						$epf=$mysqli->query($pf);
						while ($row=$epf->fetch_assoc()) {
							$fff=$row['fechas'];
							$feci=$row['fechai'];
							$fecc=$row['fechac'];
							$me=$row['mes'];
						}
						$primera="SELECT municipios.municipio, localidades.localidad, nna.id, nna.apellido_p, nna.apellido_m, nna.nombre, nna.curp, nna.sexo, DATE_FORMAT(nna.fecha_nacimiento, '%d/%m/%Y') AS fecha_nac, if(month(fecha_nacimiento)= month('$fecc'),if(day(fecha_nacimiento)>=day('$fecc') ,year('$fecc')-year(fecha_nacimiento)-1,year('$fecc')-year(fecha_nacimiento)), if(month(fecha_nacimiento)>=month('$fecc') ,year('$fecc')-year(fecha_nacimiento)-1,year('$fecc')-year(fecha_nacimiento))) as edad,
if(indigena=1,'SI','NO') as indigena, if(afrodescendiente=1,'SI', 'NO') as afrodescendiente, if(llegoAlEstado=1, 'SI', 'NO') as llegoAlEstado,violentado,tipoviolencia 
						FROM nna LEFT JOIN localidades ON localidades.id = nna.localidad
						LEFT JOIN municipios ON municipios.id = nna.municipio
						WHERE nna.id IN (SELECT DISTINCT nna.id
						        FROM nna INNER JOIN benefmed ON benefmed.id_nna = nna.id
						        LEFT JOIN seguimientos ON seguimientos.id_med = benefmed.id_medida 
						        LEFT JOIN cuadro_guia ON cuadro_guia.id = benefmed.id_medida
						        WHERE ((seguimientos.activo = 1 AND seguimientos.fecha_registro BETWEEN '$feci'  and '$fecc')
						        OR (cuadro_guia.activo = 1 AND cuadro_guia.fecha_registro BETWEEN '$feci'  and '$fecc'))
						        AND (nna.fecha_registro NOT BETWEEN '$feci'  and '$fecc'))";				
						
						$eprimera=$mysqli->query($primera);
						$contador3=$eprimera->num_rows;
						$qnumSeg="SELECT benefmed.id_nna 
						from benefmed inner join seguimientos on seguimientos.id_med=benefmed.id_medida
						where seguimientos.activo=1 and seguimientos.fecha_registro BETWEEN '$feci' and '$fecc'";	
						$rnumSeg=$mysqli->query($qnumSeg);
						$qnumMed="SELECT benefmed.id_nna from benefmed inner join cuadro_guia on benefmed.id_medida=cuadro_guia.id 
						where (cuadro_guia.activo=1 
						and cuadro_guia.fecha_registro BETWEEN '$feci'  and '$fecc')";
						$rnumMed=$mysqli->query($qnumMed);
						$contador1=$rnumSeg->num_rows;
						$contador2=$rnumMed->num_rows; ?>
						<div class="box">
							<h4><?php echo $me; ?></h4>
							<h3>Poblacion subsecuente</h3>
							<table>	
								<?php echo 'Total de nna-seguimientos: '.$contador1."<br>"; 
								echo 'Total de nna-medidas: '.$contador2."<br>".'Total de NNA a los que se les dio seguimiento (con medidas y seguimientos):'.$contador3; ?>		
								<tr>
									<td><b>ID</b></td>
									<td><b>Municipio</b></td>
									<td><b>Localidad</b></td>
									<td><b>Apellido paterno</b></td>
									<td><b>Apellido materno</b></td>
									<td><b>Nombre(s)</b></td>
									<td><b>Fecha de nac</b></td>
									<td><b>CURP</b></td>				
									<td><b>Sexo</b></td>
									<td><b>Edad</b></td>
									<td><b>Indígena</b></td>
									<td><b>Afrodescendiente</b></td>
									<td><b>Llego al estado</b></td>
									<td><b>Tipo de violencia sufrida</b></td>					
								</tr>
								<tbody>
									<?php while ($row=$eprimera->fetch_assoc()) { ?>				
										<tr>				
											<td><?php echo $row['id'];?></td>
											<td><?php echo $row['municipio'];?></td>					
											<td><?php echo $row['localidad'];?></td>					
											<td><?php echo $row['apellido_p'];?></td>	
											<td><?php echo $row['apellido_m'];?></td>	
											<td><?php echo $row['nombre'];?></td>	
											<td><?php if($row['fecha_nac']=='01/01/1900') echo ""; else echo $row['fecha_nac'];?></td>
											<td><?php echo $row['curp'];?></td>						
											<td><?php echo $row['sexo'];?></td>	
											<td><?php echo $row['edad'];?></td>
											<td><?php echo $row['indigena'];?></td>
											<td><?php echo $row['afrodescendiente'];?></td>
											<td><?php echo $row['llegoAlEstado'];?></td>
											<td><?php echo $row['tipoviolencia'];?></td>				
										</tr>
									<?php } ?>
									
								</tbody>
							</table>
						</div>
					<?php } 
				} if(!empty($_POST['nnadr'])){
					$fec=$_POST['mess'];
					if (empty($fec)) {
						echo "Selecciona el mes";
					} else {
						$pf="SELECT fechas, fechai, fechac, mes from cortes where idXaño=$fec and año='2020'";
						$epf=$mysqli->query($pf);
						while ($row=$epf->fetch_assoc()) {
								$fff=$row['fechas'];
								$feci=$row['fechai'];
								$fecc=$row['fechac'];
								$me=$row['mes'];
							}
						$sql="SELECT municipios.id as claveid, localidades.clave, municipios.municipio, localidades.localidad, nna.id, nna.apellido_p, 
						nna.apellido_m, nna.nombre, nna.curp, date_format(nna.fecha_nacimiento, '%d/%m/%Y') as fecha_nac, nna.sexo , if(month(fecha_nacimiento)= month(nna_restituidos.fecha_registro),if(day(fecha_nacimiento)>=day(nna_restituidos.fecha_registro) ,year(nna_restituidos.fecha_registro)-year(fecha_nacimiento)-1,year(nna_restituidos.fecha_registro)-year(fecha_nacimiento)), if(month(fecha_nacimiento)>=month(nna_restituidos.fecha_registro) ,year(nna_restituidos.fecha_registro)-year(fecha_nacimiento)-1,year(nna_restituidos.fecha_registro)-year(fecha_nacimiento))) as edad,if(indigena=1,'SI','NO') as indigena, if(afrodescendiente=1,'SI', 'NO') as afrodescendiente, if(llegoAlEstado=1, 'SI', 'NO') as llegoAlEstado,violentado,tipoviolencia 
						from nna left join municipios on municipios.id=nna.municipio 
						left join localidades on localidades.id=nna.localidad
						inner join  nna_restituidos on nna_restituidos.id_nna=nna.id
						where nna_restituidos.fecha_registro BETWEEN '$feci'  and '$fecc'";	
						$esql=$mysqli->query($sql); 
						$contadorniños=$esql->num_rows; ?>
						<div class="box">
							<h4><?php echo $me; ?></h4>
							<h3><?php echo 'NNA que se restituyen sus derechos: '.$contadorniños?></h3>
							<table>
								<tr>
									<td><b>ID</b></td>
									<td><b>Clave M</b></td>
									<td><b>Municipio</b></td>
									<td><b>Clave L</b></td>
									<td><b>Localidad</b></td>
									<td><b>Apellido paterno</b></td>
									<td><b>Apellido materno</b></td>
									<td><b>Nombre(s)</b></td>
									<td><b>Fecha de nac</b></td>
									<td><b>CURP</b></td>				
									<td><b>Sexo</b></td>	
									<td><b>Edad</b></td>
									<td><b>Indígena</b></td>
									<td><b>Afrodescendiente</b></td>
									<td><b>Llego al estado</b></td>
									<td><b>Tipo de violencia sufrida</b></td>										
								</tr>
								<tbody>
									<?php while ($row=$esql->fetch_assoc()) { ?>				
										<tr>
											<td><?php echo $row['id'];?></td>
											<td><?php echo $row['claveid'];?></td>					
											<td><?php echo $row['municipio'];?></td>					
											<td><?php echo $row['clave'];?></td>				
											<td><?php echo $row['localidad'];?></td>				
											<td><?php echo $row['apellido_p'];?></td>	
											<td><?php echo $row['apellido_m'];?></td>	
											<td><?php echo $row['nombre'];?></td>	
											<td><?php echo $row['fecha_nac'];?></td>
											<td><?php echo $row['curp'];?></td>							
											<td><?php echo $row['sexo'];?></td>							
											<td><?php echo $row['edad'];?></td>
											<td><?php echo $row['indigena'];?></td>
											<td><?php echo $row['afrodescendiente'];?></td>
											<td><?php echo $row['llegoAlEstado'];?></td>
											<td><?php echo $row['tipoviolencia'];?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>	
					<?php } 
				} if(!empty($_POST['carpetasIni'])) {
					$fec=$_POST['mess'];
					$fec1=  date("Y-m-d H:i:s");
					if (empty($fec)) {
						echo "Selecciona el mes";
					} else {
						$pf="SELECT fechas, mes, fechai,fechac from cortes where idXaño=$fec and año='2020'";
						$epf=$mysqli->query($pf);
						while ($row=$epf->fetch_assoc()) {
							$fff=$row['fechas'];
							$feci=$row['fechai'];
							$fecc=$row['fechac'];
							$me=$row['mes'];
						}
						//Carpetas iniciadas por distrito
						$qnumCar="SELECT nuc, distritos.distrits, municipios.municipio,  date_format(fecha_inicio, '%d/%m/%Y') as fecha_ini 
						FROM carpeta_inv left join distritos on carpeta_inv.distrito=distritos.id 
						left join municipios on municipios.id=municipio_d 
						where fecha_inicio between '$feci' and '$fecc'
						order by municipio, nuc ASC";
						$rnumCap=$mysqli->query($qnumCar);
						$contadorCarpetas=$rnumCap->num_rows; 
						//Datos de nna por carpeta
						$sql="SELECT nuc, municipios.municipio, nna.nombre, nna.apellido_p, nna.apellido_m, 
						nna.fecha_nacimiento as fecha_nac 
						FROM carpeta_inv left join distritos on carpeta_inv.distrito=distritos.id 
						left join municipios on municipios.id=municipio_d 
						INNER JOIN nna_caso on nna_caso.id_caso=carpeta_inv.id_caso 
						INNER join nna on nna_caso.id_nna=nna.id
						where fecha_inicio between '$feci' and '$fecc'
						order by municipio, nuc asc" ;	
						$esql=$mysqli->query($sql); 
						$contadorNna=$esql->num_rows;?>
						<div class="box">
						<h4><?php echo $me; ?></h4>
						<div class="row">
							<div class="7u 12u$(xsmall)">
								<h3><?php echo 'Carpetas iniciadas: '.$contadorCarpetas?></h3>
								<table>
									<tr>
										<td><b>Distrito</b></td>
										<td><b>Municipio</b></td>
										<td><b>NUC</b></td>		
										<td><b>Fecha de inicio</b></td>					
									</tr>
									<tbody>
										<?php while ($row=$rnumCap->fetch_assoc()) { ?>	
											<tr>
												<td><?php echo $row['distrits'];?></td>	
												<td><?php echo $row['municipio'];?></td>
												<td><?php echo $row['nuc'];?></td>				
												<td><?php echo $row['fecha_ini'];?></td>					
														
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<div class="5u 12u$(xsmall)">
								<h3><?php echo 'Niños por NUC '.$contadorNna?></h3>
								<table>
									<tr>
										<td><b>NUC</b></td>		
										<td><b>Nombre</b></td>
										<td><b>Edad</b></td>					
									</tr>
									<tbody>
										<?php while ($row=$esql->fetch_assoc()) { ?>
											<tr>
												<td><?php echo $row['nuc'];?></td>		
												<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>	
												<td style="text-transform:uppercase;">
													<?php $fecha_nacimiento= $row['fecha_nac'];	
													if($fecha_nacimiento=='1900-01-01' or empty($fecha_nacimiento))	
														$edad="Sin registro"; 
													else {
														$anioN=date('Y', strtotime($fecha_nacimiento));  //calcular edad
							         					$anioA=date('Y', strtotime($fec1));
							         					$mesN=date('m', strtotime($fecha_nacimiento));
							         					$mesA=date('m', strtotime($fec1));
							         					$diaN=date('d', strtotime($fecha_nacimiento));
							         					$diaA=date('d', strtotime($fec1));
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
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					<?php } 
				} if(!empty($_POST['carpetasTer'])){
					$fec=$_POST['mess'];
					$fec1=  date("Y-m-d H:i:s");
					if (empty($fec)) {
						echo "Selecciona el mes";
					} else {
					$pf="SELECT fechas, mes, fechai,fechac from cortes where idXaño=$fec and año='2020'";
						$epf=$mysqli->query($pf);
						while ($row=$epf->fetch_assoc()) {
							$fff=$row['fechas'];
							$feci=$row['fechai'];
							$fecc=$row['fechac'];
							$me=$row['mes'];
						}
					//Carpetas terminadas por distrito
					$qnumCar="SELECT nuc, distritos.distrits, municipios.municipio, date_format(fecha_inicio, '%d/%m/%Y') as fecha_ini, fecha_estado, carpeta_inv.estado 
						FROM carpeta_inv inner join distritos 
						on carpeta_inv.distrito=distritos.id
						left join municipios 
						on municipios.id=municipio_d
						where fecha_estado in ($fff) 
						and (carpeta_inv.estado='80' or carpeta_inv.estado='100')
						order by municipio, nuc ASC";
					$rnumCap=$mysqli->query($qnumCar);
					$contadorCarpetas=$rnumCap->num_rows; 
					//Nna por carpeta
					$sql="SELECT nuc, municipios.municipio, nna.nombre, nna.apellido_p, nna.apellido_m, 
					nna.fecha_nacimiento as fecha_nac , fecha_estado 
					FROM carpeta_inv inner join distritos on carpeta_inv.distrito=distritos.id 
					left join municipios on municipios.id=municipio_d 
					INNER JOIN nna_caso on nna_caso.id_caso=carpeta_inv.id_caso 
					INNER join nna on nna_caso.id_nna=nna.id 
					where fecha_estado in ($fff) 
					and (carpeta_inv.estado='80' or carpeta_inv.estado='100')
					order by municipio, nuc asc" ;	
					$esql=$mysqli->query($sql); 
					$contadorNna=$esql->num_rows; ?>
					<div class="box">
						<h4><?php echo $me; ?></h4>
						<div class="row">
							<div class="7u 12u$(xsmall)">
								<h3><?php echo 'Carpetas terminadas: '.$contadorCarpetas?></h3>
								<table>
									<tr>
										<td><b>Distrito</b></td>
										<td><b>Municipio</b></td>
										<td><b>NUC</b></td>		
										<td><b>Fecha de inicio</b></td>	
										<td><b>Fecha de termino</b></td>	
										<td><b>Porcentaje</b></td>							
									</tr>
									<tbody>
										<?php while ($row=$rnumCap->fetch_assoc()) { ?>				
											<tr>
												<td><?php echo $row['distrits'];?></td>					
												<td><?php echo $row['municipio'];?></td>					
												<td><?php echo $row['nuc'];?></td>	
												<td><?php echo $row['fecha_ini'];?></td>					
												<td><?php echo $row['fecha_estado'];?></td>	
												<td><?php echo $row['estado'];?></td>				
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<div class="5u 12u$(xsmall)">
								<h3><?php echo 'Niños por carpeta: '.$contadorNna?></h3>
								<table>
									<tr>
										<td><b>NUC</b></td>		
										<td><b>Nombre</b></td>	
										<td><b>Edad</b></td>						
									</tr>
									<tbody>
										<?php while ($row=$esql->fetch_assoc()) { ?>				
											<tr>
												<td><?php echo $row['nuc'];?></td>		
												<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>	
												<td style="text-transform:uppercase;">
													<?php $fecha_nacimiento= $row['fecha_nac'];	
													if($fecha_nacimiento=='1900-01-01' or empty($fecha_nacimiento))	
														$edad="Sin registro"; 
													else {
														$anioN=date('Y', strtotime($fecha_nacimiento));  //calcular edad
							         					$anioA=date('Y', strtotime($fec1));
							         					$mesN=date('m', strtotime($fecha_nacimiento));
							         					$mesA=date('m', strtotime($fec1));
							         					$diaN=date('d', strtotime($fecha_nacimiento));
							         					$diaA=date('d', strtotime($fec1));
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
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php } 
			} if(!empty($_POST['delitos'])) {
				$fec=$_POST['mess'];
				$fec1=  date("Y-m-d H:i:s");
				if (empty($fec)) {
					echo "Selecciona el mes";
				}else {
					$pf="SELECT fechas, mes, fechai,fechac from cortes where idXaño=$fec and año='2020'";
						$epf=$mysqli->query($pf);
						while ($row=$epf->fetch_assoc()) {
							$fff=$row['fechas'];
							$feci=$row['fechai'];
							$fecc=$row['fechac'];
							$me=$row['mes'];
						}
					//Delitos por municipio 
					$qDelitos="SELECT distinct municipios.id, municipio, delito, delitos.id, 
					(SELECT count(nna_caso.id_nna) 
					from nna_caso inner join carpeta_inv on nna_caso.id_caso=carpeta_inv.id_caso
					where carpeta_inv.id_delito=delitos.id and carpeta_inv.fecha_inicio between '$feci' and '$fecc'
					and carpeta_inv.municipio_d=municipios.id) as total
					from municipios inner join carpeta_inv on municipios.id=carpeta_inv.municipio_d
					inner join delitos on delitos.id=carpeta_inv.id_delito
					where carpeta_inv.fecha_inicio between '$feci' and '$fecc' order by municipio, id_delito ;";			
					$rDelitos=$mysqli->query($qDelitos); 
					//NNA victimas de delitos
					$sql="SELECT municipios.municipio, nna.nombre, nna.apellido_p, nna.apellido_m, nna.fecha_nacimiento, delito 
					FROM carpeta_inv left join municipios on municipios.id=municipio_d 
					INNER JOIN nna_caso on nna_caso.id_caso=carpeta_inv.id_caso 
					INNER join nna on nna_caso.id_nna=nna.id 
					inner join delitos on carpeta_inv.id_delito=delitos.id 
					where fecha_inicio between '$feci' and '$fecc'
					order by municipio, id_delito" ;			
					$esql=$mysqli->query($sql); 
					$contadorNna=$esql->num_rows;?>
					<div class="box">
						<h4><?php echo $me; ?></h4>
						<div class="row">
							<div class="4u 12u$(xsmall)">
								Delitos por municipios: 
								<table>
									<tr>
										<td><b>Municipio</b></td>
										<td><b>Delito</b></td>
										<td><b>Total</b></td>							
									</tr>
									<tbody>
										<?php  while ($rowD=$rDelitos->fetch_assoc()) { ?>				
											<tr>					
												<td><?php echo $rowD['municipio'];?></td>					
												<td><?php echo $rowD['delito'];?></td>				
												<td><?php echo $rowD['total'];?></td>						
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<div class="8u 12u$(xsmall)">NNA victimas de delito: 
								<table>
									<tr>
										<td><b>Municipio</b></td>		
										<td><b>Nombre</b></td>	
										<td><b>Edad</b></td>
										<td><b>Delito</b></td>						
									</tr>
									<tbody>
										<?php while ($rowN=$esql->fetch_assoc()) { ?>				
											<tr>
												<td><?php echo $rowN['municipio'];?></td>		
												<td><?php echo $rowN['nombre']." ".$rowN['apellido_p']." ".$rowN['apellido_m'];?></td>	
												<td style="text-transform:uppercase;">
													<?php $fecha_nacimiento= $rowN['fecha_nacimiento'];	
													if($fecha_nacimiento=='1900-01-01' or empty($fecha_nacimiento))	
														$edad="Sin registro"; 
													else {
														$anioN=date('Y', strtotime($fecha_nacimiento));  //calcular edad
							         					$anioA=date('Y', strtotime($fec1));
							         					$mesN=date('m', strtotime($fecha_nacimiento));
							         					$mesA=date('m', strtotime($fec1));
							         					$diaN=date('d', strtotime($fecha_nacimiento));
							         					$diaA=date('d', strtotime($fec1));
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
												<td><?php echo $rowN['delito'];?></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				<?php } 
			} 
			//Consultas realizadas a  partir del ddl de la matriz de indicadores
			if(!empty($_POST['Consulta'])){
				$mir=$_POST['indicador'];
				switch ($mir){
					case 0:  //C1
						$fec=$_POST['mess'];
						if (empty($fec)) {
							echo "Selecciona el mes";
						}else {
							$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año=2020";
							$epf=$mysqli->query($pf);
							while ($row=$epf->fetch_assoc()) {
								$fff=$row['fechas'];
								$feci=$row['fechai'];
								$fecc=$row['fechac'];
								$me=$row['mes'];
							}
							$sql="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total 
							from nna inner join benefmed on nna.id=benefmed.id_nna 
							right join municipios on nna.municipio=municipios.id
							inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida 
							where (cuadro_guia.fecha_ejecucion BETWEEN '$feci' and '$fecc') 
							and cuadro_guia.estado='1' and cuadro_guia.activo=1
							group by municipios.id having count(benefmed.id_nna) order by municipios.id";
							$esql=$mysqli->query($sql); 
							$qToEje="SELECT benefmed.id_nna from benefmed inner join cuadro_guia  on cuadro_guia.id=benefmed.id_medida
							where  cuadro_guia.activo=1 and cuadro_guia.estado=1
							and (cuadro_guia.fecha_ejecucion BETWEEN '$feci' and '$fecc')";
							$rToEje=$mysqli->query($qToEje);
							$TotEje=$rToEje->num_rows;
							$sql2="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total 
							from  benefmed inner join nna on nna.id=benefmed.id_nna
							left join municipios on  nna.municipio=municipios.id 
							inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida 
							where cuadro_guia.activo=1 
							and (cuadro_guia.fecha_registro BETWEEN  '$feci' and '$fecc') 
							group by municipios.id having count(benefmed.id_nna) order by municipios.id";
							$esql2=$mysqli->query($sql2); 
							$qToDre="SELECT benefmed.id_nna from benefmed inner join cuadro_guia  on cuadro_guia.id=benefmed.id_medida
							where  cuadro_guia.activo=1 
							and (cuadro_guia.fecha_registro BETWEEN '$feci' and '$fecc')";
							$rToDre=$mysqli->query($qToDre);
							$ToDre=$rToDre->num_rows;
							$qLibres1="SELECT nna.folio, date_format(nna.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, nna.direccion, date_format(cuadro_guia.fecha_ejecucion, '%d/%m/%Y %H:%i:%s') as fecha_eje, departamentos.responsable from nna inner join benefmed on nna.id=benefmed.id_nna inner join cuadro_guia on benefmed.id_medida=cuadro_guia.id inner join departamentos on departamentos.id=cuadro_guia.id_sp_registro where nna.municipio=0 and cuadro_guia.activo=1 and cuadro_guia.estado=1 and (cuadro_guia.fecha_ejecucion BETWEEN '$feci' and '$fecc') order by responsable";
							$rLibres1=$mysqli->query($qLibres1);
							$ToLib1=$rLibres1->num_rows;
							$qLibres2="SELECT nna.folio, date_format(nna.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, nna.direccion, date_format(cuadro_guia.fecha_ejecucion, '%d/%m/%Y %H:%i:%s') as fecha_reg, nna.direccion, date_format(cuadro_guia.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, departamentos.responsable
							FROM nna inner join  benefmed on nna.id=benefmed.id_nna
							inner join cuadro_guia on benefmed.id_medida=cuadro_guia.id
							inner join departamentos on departamentos.id=cuadro_guia.id_sp_registro
							where nna.municipio=0 and cuadro_guia.activo=1
							and (cuadro_guia.fecha_registro BETWEEN '$feci' and '$fecc')  order by responsable";
							$rLibres2=$mysqli->query($qLibres2);
							$ToLib2=$rLibres2->num_rows;
							?>
			
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1.- Planes de restitución de derechos</h3>
			<div class="uniform row">
		<div class="6u 12u$(xsmall)">
			<table>
				<caption>Medidas decretadas ejecutadas: <?= $TotEje ?></caption>
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
				<caption>Medidas decretadas <?= $ToDre?></caption>
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
		<div class="uniform row">
		<div class="6u 12u$(xsmall)">
			<table>
				<caption>Medidas ejecutadas sin municipio <?= $ToLib1 ?></caption>
				<tr>
					<td><b>Folio</b></td>
					<td><b>Fecha de registro del NNA</b></td>
					<td><b>Direccion</b></td>
					<td><b>Fecha de ejecución</b></td>
					<td><b>Responsable</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$rLibres1->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['folio'];?></td>					
					<td><?php echo $row['fecha_reg'];?></td>	
					<td><?php echo $row['direccion'];?></td>					
					<td><?php echo $row['fecha_eje'];?></td>
					<td><?php echo $row['responsable'];?></td>						
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<div class="6u 12u$(xsmall)">
			<table>
				<caption>Medidas decretadas sin municipio <?= $ToLib2 ?></caption>
				<tr>
					<td><b>Folio</b></td>
					<td><b>Fecha de registro del NNA</b></td>
					<td><b>Direccion</b></td>
					<td><b>Fecha de registro medida</b></td>
					<td><b>Responsable</b></td>									
				</tr>
				<tbody>
				<?php while ($row=$rLibres2->fetch_assoc()) {
					 ?>				
				<tr>
					<td><?php echo $row['folio'];?></td>					
					<td><?php echo $row['fecha_reg'];?></td>	
					<td><?php echo $row['direccion'];?></td>					
					<td><?php echo $row['fecha'];?></td>
					<td><?php echo $row['responsable'];?></td>						
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		</div>
		</div>
				<?php  } 
				break;
				case 1: //c1 a1
					$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año=2020";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$feci=$row['fechai'];
				$fecc=$row['fechac'];
				$me=$row['mes'];
			}
			$sql="SELECT mn.municipio, count(r.id) as total 
			from reportes_vd r inner join  municipios mn on r.clm=mn.id
			inner join posible_caso pc on pc.id=r.id_posible_caso 
			left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion
			where r.activo=1 and (hpc.fechaAtencion between '$feci' and '$fecc')
			and hpc.estadoAtencion in('4') group by mn.municipio having count(r.id)";
			$qTotPos="SELECT r.id
			from reportes_vd r 
			inner join posible_caso pc on pc.id=r.id_posible_caso 
			left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion
			where r.activo=1 and (hpc.fechaAtencion between '$feci' and '$fecc')
			and hpc.estadoAtencion in('4')";
			$esql=$mysqli->query($sql);
			$rTotPos=$mysqli->query($qTotPos);
			$TotalPosit=$rTotPos->num_rows;
			$sql2="SELECT mn.municipio, count(r.id) as total 
			from reportes_vd r inner join  municipios mn on r.clm=mn.id
			where r.activo=1 and (r.fecha_registro between '$feci' and '$fecc')
			group by mn.municipio having count(r.id)";	
			$esql2=$mysqli->query($sql2);
			$qTotRep="SELECT r.id from reportes_vd r 
			where r.activo=1 and (r.fecha_registro between '$feci' and '$fecc')";
			$totRep=$mysqli->query($qTotRep)->num_rows;
			 ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A1.- Recibir reportes de posible vulneracion de derechos</h3>
			<div class="uniform row">
				<div class="6u 12u$(xsmall)">
				<table>
				<caption>Reportes positivos <?= $TotalPosit?></caption>
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
				<caption>Reportes recibidos <?= $totRep?></caption>
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
	<?php }
	break;
	case 2:			//c1 a2		
	$fec=$_POST['mess'];
			
		if (empty($fec)) {
			echo "Selecciona el mes";
		}else {
		$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año=2020";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$feci=$row['fechai'];
				$fecc=$row['fechac'];
				$me=$row['mes'];
			}
	$sql="SELECT m.id, m.municipio, sum(ac.inter) as total 
	from municipios m inner join reportes_vd r on r.clm=m.id
	inner join posible_caso pc on pc.id=r.id_posible_caso
	inner join acercamiento_familiar ac on ac.id_reporte=pc.id
	where ac.fecha_registro between '$feci' and '$fecc' 
	group by m.id having sum(ac.inter)";			
	$esql=$mysqli->query($sql);
	$qTotInt="SELECT sum(ac.inter) as total 
	from posible_caso pc
	inner join acercamiento_familiar ac on ac.id_reporte=pc.id
	where ac.fecha_registro between '$feci' and '$fecc'";
	$rTotInt=$mysqli->query($qTotInt);
	$totInter=implode($rTotInt->fetch_assoc());
	$sql2="SELECT mn.municipio, count(r.id) as total 
	from reportes_vd r inner join  municipios mn on r.clm=mn.id
	where r.activo=1 and r.id_recepcion!=1 and r.id_recepcion!=7
	and (r.fecha_registro between '$feci' and '$fecc')
	group by mn.municipio having count(r.id)";		
	$esql2=$mysqli->query($sql2); 
	$qTotRep="SELECT r.id
	from reportes_vd r where r.activo=1 and r.id_recepcion!=1 and r.id_recepcion!=7
	and (r.fecha_registro between '$feci' and '$fecc')";
	$totRep=$mysqli->query($qTotRep)->num_rows;
	?>
	<div class="box">
		<h4><?php echo $me; ?></h4>
		<h3>C1 A2.- Realizar intervención de trabajo social</h3>
		<div class="uniform row">
			<div class="6u 12u$(xsmall)">
			<table>
			<caption>Intervenciones de trabajo social <?= $totInter?></caption>
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
			<caption>Reportes recibidos <?=$totRep?></caption>
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
<?php }
	break;
	case 3:  //c1 a3
		
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año=2020";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$feci=$row['fechai'];
				$fecc=$row['fechac'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.id, municipios.municipio, count(ap.id) as total  
		FROM acercamiento_psic ap inner join posible_caso pc  on pc.id=ap.id_reporte 
		inner join reportes_vd r on r.id_posible_caso=pc.id
		inner join municipios on municipios.id=r.clm
		where ap.activo=1 and ap.fecha_registro between '$feci' and '$fecc'
		group by municipios.id having count(ap.id) ORDER BY `municipios`.`id` ASC";	
		$qTotAc="SELECT ap.id FROM acercamiento_psic ap 
		where ap.activo=1 and ap.fecha_registro between '$feci' and '$fecc'";	
		$totAc=$mysqli->query($qTotAc)->num_rows;
		$esql=$mysqli->query($sql);
		$sql2="SELECT municipios.municipio, count(nna.id) as total
		from nna left join municipios on municipios.id=nna.municipio 
		where nna.activo=1 and nna.fecha_registro 
		between '$feci' and '$fecc' and (curp!='0' and curp is not null) 
		group by municipios.id having count(nna.municipio) ORDER BY `municipios`.`id` ASC";			
		$esql2=$mysqli->query($sql2);
		$qtotNna="SELECT nna.id from nna 
		where nna.activo=1 and nna.fecha_registro 
		between '$feci' and '$fecc' and (curp!='0' and curp is not null)";
		$totNna=$mysqli->query($qtotNna)->num_rows;
		$qnnaLibres="SELECT nna.folio, date_format(nna.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, nna.direccion, 
		departamentos.responsable
		from nna inner join departamentos on nna.respo_regc=departamentos.id
		where nna.fecha_registro between '$feci' and '$fecc' and (nna.curp!='0' and nna.curp is not null)
		and nna.municipio=0 or nna.municipio is null order by responsable, folio";
		$rnnaLibres=$mysqli->query($qnnaLibres);
		$numlib=$rnnaLibres->num_rows;
		 ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A3.- Realizar intervención de psicologia</h3>
			<div class="uniform row">
				<div class="6u 12u$(xsmall)">
				<table>
				<caption>Intervenciones de psicologia <?= $totAc ?></caption>
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
				<caption>NNA con derechos vulnerados atendidos <?= $totNna?></caption>
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
			<?php if($numlib>0) {?>
				<div class="uniform row">
					<div class="12u 12u$(xsmall)">
						<table>
							<caption>NNA con derechos vulnerados atendidos sin municipio <?=$numlib ?></caption>
								<tr>
									<td><b>Folio</b></td>
									<td><b>Fecha de registro del NNA</b></td>
									<td><b>Fecha de registro CURP</b></td>
									<td><b>Responsable</b></td>
									<td><b>Direccion</b></td>									
								</tr>
							<tbody>
								<?php while ($row=$rnnaLibres->fetch_assoc()) {
									 ?>				
								<tr>
									<td><?php echo $row['folio'];?></td>		
									<td><?php echo $row['fecha_reg2'];?></td>				
									<td><?php echo $row['fecha_reg'];?></td>
									<td><?php echo $row['responsable'];?></td>
									<td><?php echo $row['direccion'];?></td>						
								</tr>
								<?php } ?>
							</tbody>
						</table>	
					</div>
				</div>
			<?php } ?>
		</div>
	<?php }
	break;
	case 4:  //c1 a4
		$fec=$_POST['mess'];
			
		if (empty($fec)) {
			echo "Selecciona el mes";
		}else {
		$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año=2020";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$feci=$row['fechai'];
				$fecc=$row['fechac'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.municipio, count(substring(historial.fecha_ingreso,1,LOCATE(' ', historial.fecha_ingreso)-1)) as total
		from usuarios inner join historial on usuarios.id=historial.id_usuario 
		left join municipios on usuarios.id_mun=municipios.id 
		where substring(historial.fecha_ingreso,1,LOCATE(' ', historial.fecha_ingreso)-1)  in($fff)
		and ( historial.atencion_brindada like '%ORIENTACION JURIDICA%') 
		and historial.asunto='INICIAL' 
		group by usuarios.id_mun having count(substring(historial.fecha_ingreso,1,LOCATE(' ', historial.fecha_ingreso)-1))";			
		$esql=$mysqli->query($sql); 
	$ev=$esql->num_rows; 
	$qjuridicasL="SELECT (substring(historial.fecha_ingreso,1,LOCATE(' ', historial.fecha_ingreso)-1)) as fecha,
		usuarios.id, direccion, departamentos.responsable
		from usuarios inner join historial on usuarios.id=historial.id_usuario
		inner join departamentos on departamentos.id=usuarios.respo_reg          
		and substring(historial.fecha_ingreso,1,LOCATE(' ', historial.fecha_ingreso)-1)  in($fff)
		and ( historial.atencion_brindada like '%ORIENTACION JURIDICA%') 
		and historial.asunto='INICIAL' and id_mun=0";
	$rjuridicasl=$mysqli->query($qjuridicasL);	
	?>

	<div class="box">
		<h4><?php echo $me; ?></h4>
		<h3>C1 A4.- Realizar orientaciones juridicas</h3>
		<div class="uniform row">
			<div class="12u 12u$(xsmall)">
			<?php if ($ev>0) {  ?>
				<table>
			<caption>Orientaciones juridicas realizadas <?php echo $ev?> </caption>
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
		<div class="uniform row">
			<div class="12u 12u$(xsmall)">
			<table>
			<caption>Orientaciones a usuarios sin registro de municipio </caption>
			<tr>
				<td><b>Usuario</b></td>
				<td><b>Fecha de visita</b></td>	
				<td><b>Dirección</b></td>
				<td><b>Responsable</b></td>										
			</tr>
			
			<tbody>
			<?php while ($row=$rjuridicasl->fetch_assoc()) { ?>				
			<tr>
				<td><?php echo $row['id'];?></td>					
				<td><?php echo $row['fecha'];?></td>
				<td><?php echo $row['direccion'];?></td>					
				<td><?php echo $row['responsable'];?></td>							
			</tr>
			<?php } ?>
			</tbody>
			</table>	
			</div>			
		</div>
	</div>
<?php }
break;
case 5:  //c1 a5
	$fec=$_POST['mess'];
			
	if (empty($fec)) {
		echo "Selecciona el mes";
	}else {
	$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año=2020";
		$epf=$mysqli->query($pf);
		while ($row=$epf->fetch_assoc()) {
			$fff=$row['fechas'];
			$feci=$row['fechai'];
			$fecc=$row['fechac'];
			$me=$row['mes'];
		}
	$sql="SELECT municipios.municipio, count(carpeta_inv.id) as total 
	from municipios inner join carpeta_inv on carpeta_inv.municipio_d=municipios.id 
	where  carpeta_inv.fecha_registro between '$feci' and '$fecc' 
	group by municipios.municipio having count(carpeta_inv.id)";
	$qTotCap="SELECT carpeta_inv.id from carpeta_inv 
	where  carpeta_inv.fecha_registro between '$feci' and '$fecc'";
	$totCap=$mysqli->query($qTotCap)->num_rows;
	$esql=$mysqli->query($sql);
	$esql2=$mysqli->query($sql); 
	$qCarpetas="SELECT carpeta_inv.nuc, date_format(fecha_inicio, '%d/%m/%Y') as fecha_ini, responsable from departamentos 
	inner join carpeta_inv on carpeta_inv.respo_reg=departamentos.id 
	where  carpeta_inv.fecha_registro between '$feci' and '$fecc' 
	and municipio_d=0";
	$rCarpetas=$mysqli->query($qCarpetas);
	$TotCapLib=$rCarpetas->num_rows;
	?>
	
<div class="box">
	<h4><?php echo $me; ?></h4>
	<h3>C1 A5.- Brindar Representación coadyuvante</h3>
	<div class="uniform row">
		<div class="6u 12u$(xsmall)">
	<table>
		<caption>Representaciones brindadas <?= $totCap ?></caption>
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
		<caption>Representaciones solicitadas <?=$totCap?></caption>
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
	<?php if($TotCapLib>0) { ?>
	<div class="uniform row">
			<div class="12u 12u$(xsmall)">
			<table>
			<caption>Carpetas sin registro de municipio de delito <?=$TotCapLib ?> </caption>
			<tr>
				<td><b>NUC</b></td>
				<td><b>Fecha de inicio</b></td>	
				<td><b>Responsable</b></td>										
			</tr>
			
			<tbody>
			<?php while ($row=$rCarpetas->fetch_assoc()) { ?>				
			<tr>
				<td><?php echo $row['nuc'];?></td>					
				<td><?php echo $row['fecha_ini'];?></td>					
				<td><?php echo $row['responsable'];?></td>							
			</tr>
			<?php } ?>
			</tbody>
			</table>	
			</div>			
		</div>
	<?php } ?>
</div>
<?php }
break;
case 6:  //c1 a6
	$fec=$_POST['mess'];
			
	if (empty($fec)) {
		echo "Selecciona el mes";
	}else {
	$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año='2020'";
	$epf=$mysqli->query($pf);
	while ($row=$epf->fetch_assoc()) {
		$fff=$row['fechas'];
		$feci=$row['fechai'];
		$fecc=$row['fechac'];
		$me=$row['mes'];
	}
	$sql="SELECT municipios.municipio, count(seguimientos.id) as total 
		from municipios inner join nna on municipios.id=nna.municipio
		left join benefmed on nna.id=benefmed.id_nna
		left join seguimientos on benefmed.id_medida=seguimientos.id_med 
		where seguimientos.activo=1
		and  (seguimientos.fecha_registro BETWEEN '$feci' and '$fecc')  
		group by municipios.municipio having count(seguimientos.id)";			
	$esql=$mysqli->query($sql); 

	$sql2="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total 
		from municipios left join nna on nna.municipio=municipios.id
		inner join benefmed on nna.id=benefmed.id_nna
		inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida 
		where cuadro_guia.id_medida in ('03','01') and cuadro_guia.estado in('1','0') and cuadro_guia.activo=1
		and (cuadro_guia.fecha_registro BETWEEN '$feci' and '$fecc') 
		group by municipios.id having count(benefmed.id_nna)";			
	$esql2=$mysqli->query($sql2);
	
	$qSeguiLibres="SELECT nna.folio, nna.direccion, count(seguimientos.id) as total, departamentos.responsable 
	from  nna inner join benefmed on nna.id=benefmed.id_nna
	inner join seguimientos on benefmed.id_medida=seguimientos.id_med
    inner join departamentos on departamentos.id=nna.respo_reg
	where seguimientos.activo=1		
	and nna.municipio=0 
	and (seguimientos.fecha_registro BETWEEN '$feci' and '$fecc')  
	group by nna.id having count(seguimientos.id) order by direccion";
	$rSeguiLibres=$mysqli->query($qSeguiLibres);

	$qMedidasLibres="SELECT nna.folio, nna.direccion, count(benefmed.id_nna) as total, departamentos.responsable
	from  nna inner join benefmed on nna.id=benefmed.id_nna 
	inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida 
	inner join departamentos on departamentos.id=nna.respo_reg where
	cuadro_guia.id_medida in ('03','01') and cuadro_guia.estado in('1','0') and cuadro_guia.activo=1
	and (cuadro_guia.fecha_registro BETWEEN '$feci' and '$fecc') 
	and nna.municipio=0
	group by nna.id having count(benefmed.id_nna) order by direccion";
	$rMedidasLibres=$mysqli->query($qMedidasLibres);
	?>	
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
	<div class="uniform row">
		<div class="6u 12u$(xsmall)">
	<table>
		<caption>Seguimientos sin registro demunicipio</caption>
		<tr>
			<td><b>Folio</b></td>
			<td><b>Dirección</b></td>
			<td><b>Seguimientos</b></td>
			<td><b>Responsable del resgistro del NNA</b></td>									
		</tr>
		<tbody>
		<?php while ($rowSL=$rSeguiLibres->fetch_assoc()) {
			 ?>				
		<tr>
			<td><?php echo $rowSL['folio'];?></td>	
			<td><?php echo $rowSL['direccion'];?></td>					
			<td><?php echo $rowSL['total'];?></td>					
			<td><?php echo $rowSL['responsable'];?></td>	
									
		</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
		<div class="6u 12u$(xsmall)">
	<table>
		<caption>Medidas sin registro de municipio</caption>
		<tr>
			<td><b>Folio</b></td>
			<td><b>Dirección</b></td>
			<td><b>Medidas</b></td>
			<td><b>Responsable del resgistro del NNA</b></td>									
		</tr>
		<tbody>
		<?php while ($rowML=$rMedidasLibres->fetch_assoc()) {
			 ?>				
		<tr>
			<td><?php echo $rowML['folio'];?></td>	
			<td><?php echo $rowML['direccion'];?></td>					
			<td><?php echo $rowML['total'];?></td>					
			<td><?php echo $rowML['responsable'];?></td>							
		</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
	</div>
</div>
<?php } 
break;
case 7:  //c1 a8
	$fec=$_POST['mess'];
			
		if (empty($fec)) {
			echo "Selecciona el mes";
		}else {
		$pf="SELECT  mes, fechai, fechac from cortes where idXaño=$fec and año='2020'";
		$epf=$mysqli->query($pf);
		while ($row=$epf->fetch_assoc()) {
			$feci=$row['fechai'];
			$fecc=$row['fechac'];
			$me=$row['mes'];
		}
		$sql="SELECT municipios.municipio, COUNT(supervisiones.id) as total
		from supervisiones INNER JOIN centros on id_centro=centros.id 
		INNER JOIN municipios on municipios.id=centros.id_mun
		where (fecha_sup BETWEEN '$feci' and '$fecc') 
		GROUP by municipios.municipio HAVING COUNT(supervisiones.id);";			
		$esql=$mysqli->query($sql); 
	$ev=$esql->num_rows; ?>

	<div class="box">
		<h4><?php echo $me; ?></h4>
		<h3>C1 A8.- Realizar supervisiones a centros</h3>
		<div class="uniform row">
			<div class="12u 12u$(xsmall)">
			<?php if ($ev>0) {  ?>
				<table>
			<caption>Supervisiones relizadas <?php echo $ev?></caption>
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
<?php } 
break;
case 8:  //c1 a10
	$fec=$_POST['mess'];
			
	if (empty($fec)) {
		echo "Selecciona el mes";
	}else {
	$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año='2020'";
	$epf=$mysqli->query($pf);
	while ($row=$epf->fetch_assoc()) {
		$fff=$row['fechas'];
		$feci=$row['fechai'];
		$fecc=$row['fechac'];
		$me=$row['mes'];
	}
	$sql="SELECT municipios.municipio, COUNT(nna_centros.id_centro) as subtotal 
	FROM nna_centros INNER JOIN centros on nna_centros.id_centro=centros.id 
	INNER JOIN municipios on municipios.id=centros.id_mun 
	WHERE centros.tipo LIKE '%PRIVADO%' and (nna_centros.fecha_ing BETWEEN '$feci' and '$fecc')
	GROUP by centros.id HAVING COUNT(nna_centros.id_centro)";			
	$esql=$mysqli->query($sql); 
	$ev=$esql->num_rows;		?>
	
<div class="box">
	<h4><?php echo $me; ?></h4>
	<h3>C1 A10.- Número de ingresos a los CAS privados</h3>
	<div class="uniform row">
		<div class="12u 12u$(xsmall)">
		<?php if ($ev>0) {  ?>
	<table>
		<caption>Ingresos a CAS privados <?php echo $ev?></caption>
		<tr>
			<td><b>Municipio</b></td>	
			<td><b>Ingresos</b></td>								
		</tr>
		<tbody>
		<?php while ($row=$esql->fetch_assoc()) {
			 ?>				
		<tr>
			<td><?php echo $row['municipio'];?></td>
			<td><?php echo $row['subtotal'];?></td>								
		</tr>
		<?php } ?>
		</tbody>
		<?php }else { echo $ev; }  ?>
	</table>
</div>

	</div>
</div>
<?php } 
break;
case 9:  //c1 a11 cuotas x cas privados
	$fec=$_POST['mess'];
			
	if (empty($fec)) {
		echo "Selecciona el mes";
	}else {
	$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año='2020'";
	$epf=$mysqli->query($pf);
	while ($row=$epf->fetch_assoc()) {
		$fff=$row['fechas'];
		$feci=$row['fechai'];
		$fecc=$row['fechac'];
		$me=$row['mes'];
	}
	$sql="SELECT municipios.municipio, count(nna_centros.id_nna) as subtotal
	FROM nna_centros inner join centros on nna_centros.id_centro=centros.id
	inner join municipios on centros.id_mun=municipios.id
	where nna_centros.apoyo='1' and centros.tipo like '%PRIVADO%'
	GROUP BY municipio having count(nna_centros.id_nna) ;";			
	$esql=$mysqli->query($sql); 
	$ev=$esql->num_rows; 
	$qNumNna="SELECT municipios.municipio, count(nna_centros.id_nna) as subtotal
		FROM nna_centros inner join centros on nna_centros.id_centro=centros.id
		inner join municipios on centros.id_mun=municipios.id
		where centros.tipo like '%PRIVADO%'
		GROUP BY municipio having count(nna_centros.id_nna)";
	$rNumNna=$mysqli->query($qNumNna);
	$nn=$esql->num_rows;?>	
<div class="box">
	<h4><?php echo $me; ?></h4>
	<h3>C1 A11.- Número de cuotas pagadas a CAS Privados</h3>
	<div class="uniform row">	
		<div class="6u 12u$(xsmall)">
		<?php if ($ev>0) {  ?>
	<table>
		<caption>Cuotas pagadas a CAS privados <?php echo $ev?></caption>
		<tr>
			<td><b>Municipio</b></td>	
			<td><b>Cuotas</b></td>								
		</tr>
		<tbody>
		<?php while ($row=$esql->fetch_assoc()) {
			 ?>				
		<tr>
			<td><?php echo $row['municipio'];?></td>
			<td><?php echo $row['subtotal'];?></td>								
		</tr>
		<?php } ?>
		</tbody>
		<?php }else { echo $ev; }  ?>
	</table>
</div>
<div class="6u 12u$(xsmall)">
		<?php if ($nn>0) {  ?>
	<table>
		<caption>Personas albergadas en CAS privados</caption>
		<tr>
			<td><b>Municipio</b></td>	
			<td><b>Personas albergadas</b></td>								
		</tr>
		<tbody>
		<?php while ($row=$rNumNna->fetch_assoc()) {
			 ?>				
		<tr>
			<td><?php echo $row['municipio'];?></td>
			<td><?php echo $row['subtotal'];?></td>								
		</tr>
		<?php } ?>
		</tbody>
		<?php }else { echo $nn; }  ?>
	</table>
</div>
	</div>
</div>
<?php } 
break;
case 10:  //c1 a25
	$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año='2020'";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$feci=$row['fechai'];
				$fecc=$row['fechac'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.municipio, count(benefmed.id_nna) as total 
		from cuadro_guia left join benefmed on benefmed.id_medida=cuadro_guia.id
		left join nna on nna.id=benefmed.id_nna 
		left join municipios on nna.municipio=municipios.id
		where cuadro_guia.id_mp='28' and (cuadro_guia.fecha_registro BETWEEN '$feci' and '$fecc')  
		and cuadro_guia.activo=1
		group by municipios.municipio having count(benefmed.id_nna)";			
		$esql=$mysqli->query($sql); ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A25.- Realizar retorno seguro de NNA hidalguenses</h3>
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
	<?php }
break;
case 11:  //c1 a26
	$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes, fechai, fechac from cortes where idXaño=$fec and año='2020'";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$feci=$row['fechai'];
				$fecc=$row['fechac'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.municipio, count(benefmed.id_nna) as total 
		from municipios left join nna on municipios.id=nna.municipio
		left join benefmed on benefmed.id_nna=nna.id  
		left join cuadro_guia on cuadro_guia.id=benefmed.id_medida 
		where  cuadro_guia.id_mp='30' and cuadro_guia.activo=1
		AND (cuadro_guia.fecha_registro BETWEEN '$feci' and '$fecc') 
		group by municipios.municipio";
		$esql=$mysqli->query($sql);

		$sql2="SELECT municipios.municipio, count(nna.id) as total from municipios inner join nna  on municipios.id=nna.municipio 
		where nna.fecha_reg in ($fff) and nna.curp='EXTRANJERO' group by municipios.municipio";
		$esql2=$mysqli->query($sql2); ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A26.- Brindar acompañamiento de atención especial a NNA extranjeros migrantes</h3>
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
	<?php }
break;
case 12: //c1 a27
	$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where idXaño=$fec and año='2020'";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.municipio, sum(ccpi.na+ccpi.ni+ccpi.adm+ccpi.adh+ccpi.am+ccpi.ah) as total from ccpi inner join municipios on ccpi.municipio=municipios.id where fecha_reg in ($fff) group by municipios.id";
		$esql=$mysqli->query($sql);

		$sql2="SELECT municipios.municipio, count(ccpi.id) as total from ccpi inner join municipios on ccpi.municipio=municipios.id where fecha_reg in ($fff) group by municipios.id";
		$esql2=$mysqli->query($sql2); ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A27.- Implementar actividades en materia de migración infantil</h3>
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
	<?php }
break;
case 13: //c1 a28
	$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where idXaño=$fec and año='2020'";
			$epf=$mysqli->query($pf);
			while ($row=$epf->fetch_assoc()) {
				$fff=$row['fechas'];
				$me=$row['mes'];
			}
		$sql="SELECT municipios.municipio, count(nna_centros.id_nna) as total
		FROM nna_centros inner join centros on nna_centros.id_centro=centros.id
		inner join municipios on centros.id_mun=municipios.id
		where nna_centros.apoyo='2' 
		GROUP BY municipio having count(nna_centros.id_nna) ;";
		$esql=$mysqli->query($sql);
 ?>
		<div class="box">
			<h4><?php echo $me; ?></h4>
			<h3>C1 A28.- Apoyo en especie a NNA representados por PPNNAyF</h3>
			<div class="uniform row">
				<div class="12u12u$(xsmall)">
			<table>
				<caption>Apoyo otorgado</caption>
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
	<?php }
break;
		
		} 
		?>

	 		
	<?php  } if(!empty($_POST['A1'])){
	 		 } if(!empty($_POST['A2'])){
} if(!empty($_POST['A3'])){ } if(!empty($_POST['A4'])){
	 } if(!empty($_POST['A5'])){
 } if(!empty($_POST['A6'])){
} if(!empty($_POST['A10'])){
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
	 } if(!empty($_POST['A23'])){
		 } if(!empty($_POST['A24'])){
		 } if(!empty($_POST['EXTRA'])){
		$fec=$_POST['mess'];
			
			if (empty($fec)) {
				echo "Selecciona el mes";
			}else {
			$pf="SELECT fechas, mes from cortes where idXaño=$fec and año='2020'";
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
		$sql="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total 
		from municipios, benefmed, nna, cuadro_guia 
		where nna.municipio=municipios.id and nna.id=benefmed.id_nna 
		and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida in ('01','02') and cuadro_guia.activo=1 
		and cuadro_guia.estado in('1','0') and (cuadro_guia.fecha_registro BETWEEN '$feci' and '$fecc') 
		group by municipios.id having count(benefmed.id_nna)";			
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
		$sql="SELECT municipios.id ,municipios.municipio, count(seguimientos.id) as total 
			from municipios, benefmed, nna, cuadro_guia, seguimientos 
			where nna.municipio=municipios.id and nna.id=benefmed.id_nna
			and cuadro_guia.id=benefmed.id_medida and (seguimientos.fecha_registro BETWEEN '$feci' and '$fecc') 
			and cuadro_guia.id_medida in ('01','02','03') and cuadro_guia.estado in('1','0')
			and cuadro_guia.activo=1 
			and seguimientos.id_med=cuadro_guia.id group by municipios.id having count(seguimientos.id)";			
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
			$sql="SELECT municipios.id ,municipios.municipio, count(benefmed.id_nna) as total 
			from municipios, benefmed, nna, cuadro_guia 
			where nna.municipio=municipios.id and nna.id=benefmed.id_nna 
			and cuadro_guia.id=benefmed.id_medida and cuadro_guia.id_medida in ('03') 
			and (cuadro_guia.fecha_ejecucion BETWEEN '$feci' and '$fecc')
			and cuadro_guia.estado='1' and cuadro_guia.activo=1
			group by municipios.id having count(benefmed.id_nna)";				
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