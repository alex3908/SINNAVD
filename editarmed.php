<?php 
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	session_start();
	require 'conexion.php';
	$idDEPTO = $_SESSION['id'];
	$id=$_GET['id'];
	$idCaso=$_GET['idCaso'];
	$sqlNino = "SELECT cuadro_guia.id_derecho, derechos_nna.derecho, cuadro_guia.marco, cuadro_guia.id_medida,
		medidas.medida_p, cuadro_guia.id_mp, cuadro_guia.med_prot, catalogo_medidas.medidaC, cuadro_guia.descripcion,
		cuadro_guia.responsable_med, cuadro_guia.atp_encargada, cuadro_guia.periodicidad, cuadro_guia.institucion_names,
		cuadro_guia.estado, date_format(cuadro_guia.fecha_ejecucion, '%d/%m/%Y %H:%i:%s') as fecha_ejecucion, 
		date_format(cuadro_guia.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, departamentos.responsable
		from cuadro_guia inner join derechos_nna on derechos_nna.id=cuadro_guia.id_derecho
		inner join  departamentos on  departamentos.id=cuadro_guia.id_sp_registro 
		left join catalogo_medidas on cuadro_guia.id_mp=catalogo_medidas.id
		inner join medidas on cuadro_guia.id_medida=medidas.id
		where cuadro_guia.id='$id'";
	$resultNino = $mysqli->query($sqlNino);
	while ($rowDatos=$resultNino->fetch_assoc()) { 
		$idDerecho=$rowDatos['id_derecho'];
		$derec=$rowDatos['derecho'];
		$mar=$rowDatos['marco'];
		$tipiMedId=$rowDatos['id_medida'];
		$tipoMed=$rowDatos['medida_p'];
		$idMedPro=$rowDatos['id_mp'];
		$medProtCG=$rowDatos['med_prot']; //se obtiene del cuadro, de los primeros registro y algunos otros cuando no habia catalogo 
		$medProtCat=$rowDatos['medidaC']; //obtenida del catalogo
		$descri=$rowDatos['descripcion'];
		$instResponsable=$rowDatos['responsable_med'];
		$encagarda=$rowDatos['atp_encargada'];
		$periodo=$rowDatos['periodicidad'];
		$instNames=$rowDatos['institucion_names'];
		$estado=$rowDatos['estado'];
		$fechaEjecucion=$rowDatos['fecha_ejecucion'];
		$fechaRegistro=$rowDatos['fecha'];
		$registro=$rowDatos['responsable'];
	}
	$resultNino1 = $mysqli->query($sqlNino);
	$resultNino2 = $mysqli->query($sqlNino);
	$resultNino3 = $mysqli->query($sqlNino);
	$resultNino3 = $mysqli->query($sqlNino);

	$qEdiciones="SELECT departamentos.responsable, date_format(cuadro_guia.fechaEdicion, '%d/%m/%Y %H:%i:%s') as fechaEdicion
	FROM cuadro_guia inner join departamentos on cuadro_guia.idDepartamentosEdicion=departamentos.id
	where cuadro_guia.id='$id'";
	$rEdiciones= $mysqli->query($qEdiciones);
	$numEdiciones=$rEdiciones->num_rows;
	$qinstNames="SELECT id, institucion FROM cat_instituciones_names order by institucion";
	$rinstNames=$mysqli->query($qinstNames);

	$qnna="SELECT nna.id, nna.nombre, nna.apellido_p, nna.apellido_m, nna_caso.estado 
	from nna, nna_caso where nna_caso.id_caso='$idCaso' 
	and nna_caso.id_nna=nna.id and nna_caso.estado='NE'";
	$rnna=$mysqli->query($qnna);
	$qmujer="SELECT sexo FROM nna inner join nna_caso on nna_caso.id_nna=nna.id
	where  nna_caso.id_caso='$idCaso' and sexo='MUJER'";
	$rmujer=$mysqli->query($qmujer);
	$numMujer=$rmujer->num_rows; //verificar si algun beneficiaio puede ser name 

	$qverificarBenef="SELECT id  
	from benefmed where benefmed.id_medida='$id' ";
	$rnumBenef=$mysqli->query($qverificarBenef);
	$numNna=$rnumBenef->num_rows;

	$tdere="SELECT id, derecho from derechos_nna";
	$etdere=$mysqli->query($tdere);
	$trespo="SELECT id, responsable from departamentos";
	$etrespo=$mysqli->query($trespo);

	$urlInstNames ='http://172.16.1.37:8094/swNames/api/Instituciones'; //toma los valores del ws del catalogo instituciones para names
	$jsInstNames = file_get_contents($urlInstNames);
	$InstNames = json_decode($jsInstNames);
	$numIntNames= count($InstNames);
	
	if(!empty($_POST))
	{
		$fecha= date("Y-m-d H:i:s", time());
		$derecho = $_POST['derecho'];
		$marco = mysqli_real_escape_string($mysqli,$_POST['marco']);
		$tipoMed= $_POST['tipoMed'];
		$med_prot = $_POST['med_prot'];
		$descripcion = mysqli_real_escape_string($mysqli,$_POST['descripcion']);
		$responsable_med = mysqli_real_escape_string($mysqli,$_POST['responsable_med']);
		$atp_encargada = mysqli_real_escape_string($mysqli,$_POST['encargado']);
		$periodicidad = mysqli_real_escape_string($mysqli,$_POST['periodicidad']);
		if ($numMujer>0)
		$institucion_names=	$_POST['instNames'];
		else 
		$institucion_names=0;
		$update="UPDATE cuadro_guia set marco='$marco', id_derecho='$derecho', id_mp='$med_prot', 
		id_medida='$tipoMed', responsable_med='$responsable_med', atp_encargada='$atp_encargada',
		periodicidad='$periodicidad', descripcion='$descripcion', fechaEdicion='$fecha', idDepartamentosEdicion='$idDEPTO',
		institucion_names='$institucion_names' where id='$id'";
		$eupdate=$mysqli->query($update);
	
	header("Location: cuadro_guia.php?id=$idCaso");
	}
	
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Editar medida</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		
		
	</head>
	<body>

		
		<!-- Wrapper -->
			<div id="wrapper">
				<!-- Main -->
					<div id="main">
						<div class="inner">
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
					<div class="row uniform">
					<br>
                    <div class="5u 12u$(xsmall)">DERECHO VULNERADO O RESTRINGIDO
						<div class="select-wrapper">
							
								<select id="derecho" name="derecho" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
									<option value="<?php echo $idDerecho;?>"><?php echo $derec;  ?></option>
									<?php while ($row=$etdere->fetch_assoc()) { ?>
										<option value="<?php echo $row['id']; ?>"><?php echo $row['derecho'];  ?></option>
									<?php } ?>
								</select>
						</div>
					</div>
                    <div class="7u 12u$(xsmall)">MARCO JURIDICO
                    	<textarea id="marco" name="marco" maxlength="1000" cols="" rows="" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required><?php echo $mar ?></textarea>
                    </div>
                   
					</div>
					<div class="row uniform">
					<div class="4u 12u$(xsmall)">TIPO DE MEDIDA
						<div class="select-wrapper">
								<select id="tipoMed" name="tipoMed" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
									<option value="<?php echo $tipiMedId;?>"><?php echo $tipoMed;  ?></option>
								<?php 
								$qTipoMed="SELECT id, medida_p from medidas";
								$rTipoMed=$mysqli->query($qTipoMed);
								while ($row2=$rTipoMed->fetch_assoc()) { ?>
									<option value="<?php echo $row2['id']; ?>"><?php echo $row2['medida_p'];  ?></option>
								<?php } ?>
								</select>
						</div>
                    </div>                   
                    <div class="8u 12u$(xsmall)">MEDIDA DE PROTECCIÓN ESPECIAL
						<div class="select-wrapper">
							<select id="med_prot" name="med_prot" required>
								<option value="<?php echo $idMedPro;?>"><?php echo $medProtCat;  ?></option>
								<optgroup label="Medidas NNA">
								<option value="">-- Seleccione --</option>
									<?php $catMed="SELECT id, folio, medidaC from catalogo_medidas where tipo='1' order by id";
									$ecatMed=$mysqli->query($catMed);
									while ($row1=$ecatMed->fetch_assoc()) { ?>
										<option value="<?php echo $row1['id'];?>"><?php echo $row1['folio']."- ".$row1['medidaC'];?></option>
									<?php }  ?>
								</optgroup>
								<optgroup label="Medidas CNP">
									<?php $catMed="SELECT id, folio, medidaC from catalogo_medidas where tipo='2'";
									$ecatMed=$mysqli->query($catMed);
									while ($row1=$ecatMed->fetch_assoc()) { ?>
										<option value="<?php echo $row1['id'];?>"><?php echo $row1['folio']."- ".$row1['medidaC'];?></option>
									<?php }  ?>
								</optgroup>
								<optgroup label="Medidas NNA Migrantes">
									<?php $catMed="SELECT id, folio, medidaC from catalogo_medidas where tipo='3'";
									$ecatMed=$mysqli->query($catMed);
									while ($row1=$ecatMed->fetch_assoc()) { ?>
										<option value="<?php echo $row1['id'];?>"><?php echo $row1['folio']."- ".$row1['medidaC'];?></option>
									<?php }  ?>
								</optgroup>
								<optgroup label="Medidas Adultos">
									<?php $catMed="SELECT id, folio, medidaC from catalogo_medidas where tipo='4'";
									$ecatMed=$mysqli->query($catMed);
									while ($row1=$ecatMed->fetch_assoc()) { ?>
										<option value="<?php echo $row1['id'];?>"><?php echo $row1['folio']."- ".$row1['medidaC'];?></option>
									<?php }  ?>
								</optgroup>
							</select>
						</div>
                    </div>
					<div class="5u 12u$(xsmall)">
	
					</div>
					</div>
					<div class="row uniform">
					<?php ?>
                    <div class="6u 12u$(xsmall)">DESCRIPCIÓN DE LA MEDIDA
                    	<textarea id="descripcion" name="descripcion" maxlength="500" cols="5" rows="3" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required><?php echo $descri; ?></textarea>
					</div>
					<div class="6u 12u$(xsmall)"><div class="table-wrapper">BENEFICIARIOS
					<table class="alt"><tbody>
					<?php while($rowNna=$rnna->fetch_assoc()) {
						$qBeneficiarios="SELECT id_nna, id_medida  
						from benefmed inner join nna on benefmed.id_nna=nna.id 
						where benefmed.id_medida='$id' and nna.id='$rowNna[id]'";
						$rBeneficiarios=$mysqli->query($qBeneficiarios);
						$numBenef=$rBeneficiarios->num_rows; //verificar que el nna que esta mostrando sea beneficiario de esa medida
						?>				
							<tr>
								<td><?php echo $rowNna['nombre']." ".$rowNna['apellido_p']." ".$rowNna['apellido_m'];?></td>
								<td><?php if($numBenef==0){ ?>
								<img src="images/no_ejecutada.png" height="40" width="40" >
								<a href="agregarNnaMedida.php?idMed=<?php echo $id?>&idCaso=<?php echo $idCaso?>&idNna=<?php echo $rowNna['id']?>">Agregar</a>
								<?php } else{?>
								<img src="images/ejecutada.png" height="40" width="40">
								<?php if($numNna>1){ ?>
								<a href="eliminarNnaDeMedida.php?idMed=<?php echo $id?>&idNna=<?php echo $rowNna['id']?>&idCaso=<?php echo $idCaso?>">Eliminar</a>								
								<?php } } ?>
								</td]>
							</tr>
							<?php } ?>
							<tbody></table>
				    </div>
					</div>
					</div>
					<div class="row uniform">
					<div class="3u 12u$(xsmall)">INSTITUCIÓN O PERSONA RESPONSABLE
                    	<input id='responsable_med' maxlength="100" name='responsable_med' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $instResponsable; ?>" required>
                    </div>
                    <div class="4u 12u$(xsmall)">AREA, TITULAR O PERSONA ENCARGADA 
                    	<input id='encargado' maxlength="170" name='encargado' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $encagarda; ?>" required>
                    </div>
                    <div class="2u 12u$(xsmall)">PERIODICIDAD
                    	<input id='periodicidad' maxlength="50" name='periodicidad' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $periodo; ?>" required>
					</div>
					<?php if($numMujer>0) { ?>
						<div class="3u 12u$(xsmall)">INSTITUCIONES PARA LAS NAMES
                   		<div class="select-wrapper">
						   <select id="instNames" name="instNames">
						   	<?php if(is_null($instNames)) {?>
								<option value="0">NINGUNO</option>
							   <?php } else { ?>
								<option value="<?php echo $instNames; ?>"><?php print_r($InstNames[$instNames-1]->nombreCorto); ?></option> <!--Llena el select con la consulta del ws-->
							   <?php } for ($i=0; $i<$numIntNames;$i++){ ?>
									<option value="<?php  print_r($InstNames[$i]->id);?>"><?php print_r($InstNames[$i]->nombreCorto);?></option>
								<?php }  ?>
							</select>
						</div>
                    </div>
					<?php } ?>
					</div>
					<div class="row uniform">
                    <div class="2u 12u$(xsmall)">ESTADO
                    	<input id='estado' name='estado' type='text'  value="<?php echo $estado; ?>" disabled >
                    </div>
                    <div class="3u 12u$(xsmall)">FECHA DE EJECUCION
                    	<input name='fecha_eje' type='text'  value="<?php echo $fechaEjecucion; ?>" disabled>
                    </div>
                    <div class="3u 12u$(xsmall)">FECHA DE REGISTRO
                   		<input name='fecha' type='text'  value="<?php echo $fechaRegistro; ?>" disabled >
                    </div>
                    <div class="4u 12u$(xsmall)">RESPONSABLE DE REGISTRO
					<input name='registro' type='text'  value="<?php echo $registro; ?>" disabled >
					</div>
						    </div>  
					</div>
					<div class="row uniform"> 
					<?php if($numEdiciones!=0) {
						while ($rowE=$rEdiciones->fetch_assoc()) { ?>
						<div class="5u 12u$(xsmall)">FECHA DE ÚLTIMA EDICIÓN
                   		<input name='fechaEdi' type='text'  value="<?php echo $rowE['fechaEdicion']; ?>" disabled >
                    </div>
                    <div class="7u 12u$(xsmall)">RESPONSABLE DE ÚLTIMA EDICIÓN
						<input name='responsableEdicion' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $rowE['responsable']; }?>" disabled>
                    </div>   
					<?php }?>
				<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Actualizar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='cuadro_guia.php?id=<?php echo $idCaso;  ?>'" >
						</ul>
					</div>
				</div>	
				</form>		
				
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