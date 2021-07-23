<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];   //depto: proteccion, representacion, vinculacion; personal: administrativo y todas las subprocus 
	$valida="SELECT id from departamentos where (id_depto in ('9','10','14') and id_personal='3'
    and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5'
    and id='$idDEPTO') or (id_personal='1' and id='$idDEPTO' and id_depto='16')"; //ayuda a validar la persona que puede mover 
	$evalida=$mysqli->query($valida);
    $reva=$evalida->num_rows;
    $folio=($_GET['folio']);

	$buscaf="SELECT responsable, id_maltrato, maltrato, id_recepcion, cat_recepcion_reporte.recepcion, 
    id_distrito, distrits, date_format(reportes_vd.fecha_registro, '%d/%m/%Y') as fecha_reg,
    clm, municipios.municipio, id_localidad, localidades.localidad, calle, ubicacion, narracion, otros_datos, persona_reporte
    from departamentos inner join reportes_vd on reportes_vd.respo_reg=departamentos.id
    inner join tipo_maltrato on reportes_vd.id_maltrato=tipo_maltrato.id
    inner join municipios on clm=municipios.id
    inner join localidades on id_localidad=localidades.id
    inner join cat_recepcion_reporte on id_recepcion=cat_recepcion_reporte.id
    inner join distritos on distritos.id=id_distrito
    where folio='$folio'";
    $ebf=$mysqli->query($buscaf);
    while ($rowRepor=$ebf->fetch_assoc()) {
        $fecha=$rowRepor['fecha_reg'];
        $responsable=$rowRepor['responsable'];
        $idMaltrato=$rowRepor['id_maltrato'];
        $maltrato=$rowRepor['maltrato'];
        $idRecepcion=$rowRepor['id_recepcion'];
        $recepcion=$rowRepor['recepcion'];
        $idDistrito=$rowRepor['id_distrito'];
        $distrito=$rowRepor['distrits'];
        $idMunicipio=$rowRepor['clm'];
        $municipio=$rowRepor['municipio'];
        $idLocalidad=$rowRepor['id_localidad'];
        $localidad=$rowRepor['localidad'];
        $calle=$rowRepor['calle'];
		$ubicacion=$rowRepor['ubicacion'];
		$descripcion=$rowRepor['narracion'];
        $otros=$rowRepor['otros_datos'];
        $persona=$rowRepor['persona_reporte'];
    }	
    
    $qNnas="SELECT nna_reportados.id as idNna, nombre, apellido_p, apellido_m, sexo.sexo, date_format(nna_reportados.fecha_nacimiento,'%d/%m/%Y') as fecha_nac, lugar_nacimiento, edad 
	from nna_reportados left join sexo on nna_reportados.sexo=sexo.id
	where folio_reporte='$folio'";
    $rNnas=$mysqli->query($qNnas);
    $numNna=$rNnas->num_rows;

	if(!empty($_POST))
	{	
        
			
			
			header("Location: reg_nna_reportados.php?folio=$folio");
			
	}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Registrar reporte</title>
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
							<div class="box alt" align="center">
								<div class="row 10% uniform">
									<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
									<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
									<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
								</div>
                            </div>
                                <h2>Por favor, verifique que la información registrada sea correcta</h2>
								<h3>Reporte con folio: <?php echo $folio?></h3>
								
			 					<div class="row uniform">
									<div class="5u 12u$(xsmall)">Fecha de registro:
										<input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $fecha ?>" disabled>	
									</div>
									<div class="7u 12u$(xsmall)">Responsable del registro:
                                    <input id="responsable" name="responsable" type="text" value="<?php echo $responsable ?>" disabled>	
									</div>									
                                </div><br>
                                <div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
							
                                <div class="row uniform">
                                <div class="4u 12u$(xsmall)">Forma de recepción de reporte:
										<div class="select-wrapper">
											<select id="recepcion" name="recepcion" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required >							
                                                <option value="<?php echo $idRecepcion?>"><?php echo $recepcion?></option>
                                                    <?php 
                                                        $opc_recepcion="SELECT id, recepcion from cat_recepcion_reporte";
                                                        $ropc_recepcion=$mysqli->query($opc_recepcion);
                                                        while ($rowRecep=$ropc_recepcion->fetch_assoc()) { ?>
                                                        <option value="<?php echo $rowRecep['id']; ?>"><?php echo $rowRecep['recepcion']; ?></option>													
													<?php } ?>
											</select>
										</div>
									</div>
									<div class="4u 12u$(xsmall)">Distrito:
										<div class="select-wrapper">
											<select id="distrito" name="distrito">
                                            <option value="<?php echo $idDistrito; ?>"><?php echo $distrito; ?></option>		
                                                <?php 
                                                    $qdistrito="SELECT id, distrits from distritos";
                                                    $rdistrito=$mysqli->query($qdistrito); 
                                                    while ($rowDis=$rdistrito->fetch_assoc()) { ?>
													<option value="<?php echo $rowDis['id']; ?>"><?php echo $rowDis['distrits']; ?></option>
													<?php } ?>
											</select>
										</div>
									</div>
									<div class="4u 12u$(xsmall)">Tipo de maltrato:
										<div class="select-wrapper">
											<select id="tiMAL" name="tiMAL">
                                                <option value="<?php echo $idMaltrato ?>"><?php echo $maltrato; ?></option>
                                                <?php 
                                                $qmaltrato="SELECT id, maltrato from tipo_maltrato";
                                                $rmaltrato=$mysqli->query($qmaltrato);
                                                while ($rowMal=$rmaltrato->fetch_assoc()) { ?>
													<option value="<?php echo $rowMal['id']; ?>"><?php echo $rowMal['maltrato']; ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
                                </div><br>
                                <div class="12u 12u$(small)">
										<div class="box">Datos de localizacion de la NNA:
										<div class="row uniform">
											<div class="3u 12u(xsmall)">Municipio
												<div class="select-wrapper">
													<select id="country_id" class="form-control" name="country_id" required>
      													<option value="<?php echo $idMunicipio ?>"><?php echo $municipio ?></option>
                                                            <?php 
                                                            $query="SELECT * from municipios where id!='0'";
                                                            $equery=$mysqli->query($query);
                                                            $countries = array();
                                                            while($row=$equery->fetch_object())	{ $countries[]=$row; }
                                                            foreach($countries as $c):?>
     															<option value="<?php echo $c->id; ?>"><?php echo $c->municipio; ?></option>
															<?php endforeach; ?>
    												</select>
												</div>
											</div>
											<div class="3u 12u(xsmall)">
												<div class="select-wrapper">Localidad
													<select id="state_id" class="form-control" name="state_id" required>
     													<option value="<?php echo $idLocalidad ?>"><?php echo $localidad?></option>
   													</select>
												</div> 
											</div>
											<div class="6u 12u$(small)">Calle y No.
												<input type="text" name="calle" maxlength="100" value="<?php echo $calle ?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
											</div>										
											<div class="12u 12u$(xsmall)">Referencias de domicilio
											<textarea name="ubicacion" rows="2" maxlength="300" cols="20" style="text-transform:uppercase;" value="<?php echo $ubicacion ?>" required><?php echo $ubicacion ?></textarea>
											</div>	
										</div>
										</div>
									</div>
									<div class="12u 12u$(small)">
										<div class="box">Suceso:
										<div class="row uniform">
									<div class="6u 12u$(xsmall)">Descripcion de la situacion:
										<textarea name="narracion" rows="3" cols="20" maxlength="1000" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $descripcion ?>" required><?php echo $descripcion ?></textarea>
									</div>
									
									<div class="6u 12u$(small)">Otros datos u observaciones relevantes:
										<textarea name="datosrelevates" rows="3" cols="20" maxlength="1000" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $otros ?>" required><?php echo $otros ?></textarea>
									</div>
																			
									<div class="12u 12u$(xsmall)">Persona que reporta:
										<input id="persona_reporte" name="persona_reporte" maxlength="100" value="<?php echo $persona ?>" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>   
									</div>
									</div>                         
                                <br>
								
								<div class="box alt" align="center">
								<?php if($numNna>0){?>
									<table>			
										<thead>NNA registrados:<tr>								
											<td><b>Nombre</b></td>
											<td><b>Sexo </b></td>
											<td><b>Fecha de nacimiento</b></td>
											<td><b>Lugar de nacimiento </b></td>
											<td><b>Edad</b></td>				
										</tr></thead>
								
										<body>
										<?php while ($row=$rNnas->fetch_assoc()){
											 $qName="SELECT id from relacion_names where id_nna='$row[idNna]' and activo=1";
											 $rName=$mysqli->query($qName);
											 $numName=$rName->num_rows; ?>
											
										<tr>
											<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
											<td><?php echo $row["sexo"]; ?></td>
											<td><?php echo $row["fecha_nac"]; ?> </td>
											<td><?php echo $row["lugar_nacimiento"]; ?></td>
											<td><?php echo $row["edad"]; ?> </td>
											<?php if($reva==1){ ?>
											<td><a href="perfil_nna_reportado.php?id=<?php echo $row['idNna'];?>&<?php echo $folio;?>">Editar</a></td>
											<td><a href="borrar_nna_reportado.php?id=<?php echo $row['idNna'];?>">Eliminar</a></td>
										<?php if ($row["sexo"]=='Mujer' && $row["edad"]<15) { if($numName==0){ ?>
										<td><a href="name.php?id=<?php echo $row['idNna'];?>">Registrar como NAME</a></td>
										<?php } else {?>
											<td><a href="name.php?id=<?php echo $row['idNna'];?>">NAME</a></td>
										<?php } } } } ?>
										</tr>
										</body>
									</table>
								<?php }?>
								</div>
			<div class="box alt" align="center">
            <div class="row uniform">
				<div class="6u 12u$(xsmall)">
					<input class="button fit" type="button" name="btnatras" value="Atrás" onclick="location='reg_nna_reportados.php?folio=<?php echo $folio?>'" <?php if($reva==0) {?> disabled <?php } ?>>
				</div>	
				<div class="6u 12u$(xsmall)">
					<input class="button special fit" name="btnterminar" id="btnterminar" type="submit" value="Aceptar" <?php if($reva==0) {?> disabled <?php } ?>>	
				</div>
			</div>
		</form>
		<script type="text/javascript">
	$(document).ready(function(){
		$("#country_id").change(function(){
			$.get("get_localidades.php","country_id="+$("#country_id").val(), function(data){
				$("#state_id").html(data);
				console.log(data);
			});
		});		
	});
</script>
	</div>
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