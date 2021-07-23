<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	
	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}
	$fecha= date("Y-m-d H:i:s", time());

	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;

	$query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($rowedo=$equery->fetch_object())	{ $countries[]=$rowedo; }
	$tipo=$_GET['T'];
	$id=$_GET['id'];
	$qDatos="SELECT nombre, apellido_p, apellido_m, fecha_nacimiento, sexo, id_estado_nacimiento,
	e.estado, e.clave, curp 
	FROM nna 
	left join estados e on e.id=nna.id_estado_nacimiento where nna.id=$id";
	$rDatos=$mysqli->query($qDatos);
	while ($rw=$rDatos->fetch_assoc()) {
		$cnombre=trim($rw['nombre']);
		$cape1=trim($rw['apellido_p']);
		$cape2=trim($rw['apellido_m']);
		$cfechaNac=$rw['fecha_nacimiento'];
		if(empty($cfechaNac) or $cfechaNac=='1900-01-01')
			$ComFechaNac = 'X';
		else {
			$newDate = new DateTime($cfechaNac);
			$ComFechaNac= date_format($newDate, 'd/m/Y');
		}
		$cArSex=$rw['sexo'];
		if($rw['sexo']=='MUJER')
			$csexo='M';
		else if($rw['sexo']=='HOMBRE')
			$csexo='H';
		$cestadoNac=$rw['estado'];
		if(empty($cestadoNac))
			$cestadoNac='X';
		$cclaveEdo=$rw['clave'];
		$ccurp=$rw['curp'];
		if(empty($ccurp))
			$ccurp='X';
	}


	if(!empty($_POST['btnCurp'])){
		$curp = mysqli_real_escape_string($mysqli,$_POST['curp']);
		try{
		$cliente= new SoapClient('http://172.16.1.42/sistemas/wssieb/WebServiceBeneficiarios.asmx?wsdl'); //crea un nuevo cliente
		$parametros=array('CURP'=>"$curp");
		$respuesta= $cliente->ConsultaPorCurp($parametros);
		$respuesta= json_decode(json_encode($respuesta), True);
		$estatus=$respuesta['ConsultaPorCurpResult']['StatusOper'];  //consulta x curp
		if($estatus=='EXITOSO') //devuelve valores
		{
			$nombre=$respuesta['ConsultaPorCurpResult']['Nombres'];
			$apellido_p=$respuesta['ConsultaPorCurpResult']['Apellido1'];
			$apellido_m=$respuesta['ConsultaPorCurpResult']['Apellido2'];
			$sexo=$respuesta['ConsultaPorCurpResult']['Sexo'];
			if($sexo=='M')
				$sexo='MUJER';
			else if($sexo=='H')
				$sexo='HOMBRE';
			$fechaHorNac=$respuesta['ConsultaPorCurpResult']['FechNac'];	
			$ArfechaHorNac = explode("T",$fechaHorNac);
			$fechaM=$ArfechaHorNac[0];		
			$date = new DateTime($fechaHorNac);
			$fechaNac= date_format($date, 'd/m/Y');
			if($respuesta['ConsultaPorCurpResult']['Nacionalidad']=='MEX')
				$nacionalidad='MEXICANA';
			else 
				$nacionalidad='EXTRANJERO';
			$lugNac=$respuesta['ConsultaPorCurpResult']['EntidadFederativa'];
			$qEstadoNac="SELECT estado as edoNac, id FROM estados where clave='$lugNac'"; 
			$rEstadoNac=$mysqli->query($qEstadoNac);
			while($rlugNac = $rEstadoNac->fetch_assoc()){
				$idEdoNac=$rlugNac['id'];
				$edoNac=$rlugNac['edoNac'];
			}
			$statusCurp=$respuesta['ConsultaPorCurpResult']['StatusCURP'];
			switch ($statusCurp) {
				case 'AN':
					$stCurp="ACTIVA: ALTA NORMAL";
					break;
				case 'AH':
					$stCurp="ACTIVA: ALTA CON HOMONIMIA";
					break;
				case 'CRA':
					$stCurp="ACTIVA: CURP REACTIVADA";
					break;
				case 'RCN':
					$stCurp="ACTIVA: REGISTRO DE CAMBIO NO AFECTANDO A CURP";
					break;
				case 'RCC':
					$stCurp="ACTIVA: REGISTRO DE CAMBIO AFECTANDO A CURP";
					break;
				case 'BD':
					$stCurp="BAJA POR DEFUNCION";
					break;
				case 'BDA':
					$stCurp="BAJA POR DUPLICIDAD";
					break;
				case 'BCC':
					$stCurp="BAJA POR CAMBIO EN CURP";
					break;
				case 'BCN':
					$stCurp="BAJA NO AFECTANDO A CURP";
					break;
				default:
					$stCurp="NINGUNO";
					break;
			}
			$DocProbatorio=$respuesta['ConsultaPorCurpResult']['DocProbatorio'];
			switch ($DocProbatorio) {
				case '1':
					$ADocProbatorio="ACTA DE NACIMIENTO";
					$anioReg=$respuesta['ConsultaPorCurpResult']['AnioReg'];
					$numActa=$respuesta['ConsultaPorCurpResult']['NumActa'];
					$idEstadoReg=$respuesta['ConsultaPorCurpResult']['EntidadRegistro'];
					$idMunReg=$respuesta['ConsultaPorCurpResult']['MunicipioRegistro'];
					if($idEstadoReg!='13'){
						$qEstadoReg="SELECT estadosMayus from estados where id='$idEstadoReg'";
						$rEstadoReg=$mysqli->query($qEstadoReg);
						if($rEstadoReg->num_rows==0)
							$lugarReg="";
						else {
						$arrEstadoReg=$rEstadoReg->fetch_assoc();
						$lugarReg=implode($arrEstadoReg); }
					} else {
						$qMunReg="SELECT municipioMayus from municipios where id='$idMunReg'";
						$rMunReg=$mysqli->query($qMunReg);
						$arrMunReg=$rMunReg->fetch_assoc();
						$MunReg=implode($arrMunReg);
						$lugarReg=$MunReg.", HIDALGO";	
					}
					break;
				case '3':
					$ADocProbatorio="DOCUMENTO MIGRATORIO";
					$numRegExtrajero=['ConsultaPorCurpResult']['NumRegExtranjeros'];
					break;
				case '4':
					$ADocProbatorio="CARTA DE NATURALIZACIÓN";
					$anioReg=$respuesta['ConsultaPorCurpResult']['AnioReg'];
					$folioCarta=$respuesta['ConsultaPorCurpResult']['FolioCarta'];
					break;
				case '7':
					$ADocProbatorio="CERTIFICADO DE NACIONALIDAD MEXICANA";
					$anioReg=$respuesta['ConsultaPorCurpResult']['AnioReg'];
					$folioCarta=$respuesta['ConsultaPorCurpResult']['FolioCarta'];
					break;
				default:
					$ADocProbatorio="TRAMITE ANTE SEGOB";
					$folio=$respuesta['ConsultaPorCurpResult']['CRIP'];
					break;
			}
			if($DocProbatorio!=1){
				$anioReg=null;
				$numActa=null;
				$lugarReg=null;
			}			
			$tipo=2;
			$persona= [
				"nombre" => "$nombre", 
				"curp" => "$curp",
				"ape1" => "$apellido_p",
				"ape2" => "$apellido_m",
				"sexo" => "$sexo",
				"fechaNac" => "$fechaM",
				"nacionalidad" =>   "$nacionalidad",
				"edoNac" => "$idEdoNac",
				"stCurp" => "$stCurp",
				"docPro" => "$ADocProbatorio",
				"anioReg" => "$anioReg",
				"numActa" => "$numActa",
				"lugReg" => "$lugarReg",
			];
			$_SESSION['persona'] = $persona;
		} else header("Location: validarCurp.php?id=$id&T=3");
		} catch (Exception $e){
		var_dump($e);}
	}

	if(!empty($_POST['datos'])){
		$nombre= trim(mysqli_real_escape_string($mysqli,$_POST['nombre']));
		$ape1= trim(mysqli_real_escape_string($mysqli,$_POST['apellido_p']));
		$ape2 = trim(mysqli_real_escape_string($mysqli,$_POST['apellido_m']));
		$sexo = $_POST['sexo'];
		$fechaNac = $_POST['fecha_nacimiento'];
		$lugNac = $_POST['country_id'];
		$persona= [ 
		    "Nombres" => "$nombre",
		    "Apellido1" => "$ape1",
		    "Apellido2" => "$ape2",
		    "FechNac" => "$fechaNac",
		    "Sexo" => "$sexo",
			"EntidadRegistro" => "$lugNac",
		];
		$cliente= new SoapClient('http://172.16.1.42/sistemas/wssieb/WebServiceBeneficiarios.asmx?wsdl'); //crea un nuevo cliente
		$parametros = new stdClass();
		$parametros->Persona=$persona;
		$respuesta= $cliente->ConsultaPorDatos($parametros);
		$respuesta= json_decode(json_encode($respuesta), True);
		$estatus=$respuesta['ConsultaPorDatosResult']['StatusOper'];
		if($estatus=='EXITOSO') //devuelve valores
		{
			$curp= $respuesta['ConsultaPorDatosResult']['CURP'];
			$nombre=$respuesta['ConsultaPorDatosResult']['Nombres'];
			$apellido_p=$respuesta['ConsultaPorDatosResult']['Apellido1'];
			$apellido_m=$respuesta['ConsultaPorDatosResult']['Apellido2'];
			$sexo=$respuesta['ConsultaPorDatosResult']['Sexo'];
			if($sexo=='M')
				$sexo='MUJER';
			else if($sexo=='H')
				$sexo='HOMBRE';
			$fechaHorNac=$respuesta['ConsultaPorDatosResult']['FechNac'];
			$ArfechaHorNac = explode("T",$fechaHorNac);
			$fechaM=$ArfechaHorNac[0];		
			$date = new DateTime($fechaHorNac);
			$fechaNac= date_format($date, 'd/m/Y');
			if($respuesta['ConsultaPorDatosResult']['Nacionalidad']=='MEX')
				$nacionalidad='MEXICANA';
			else 
				$nacionalidad='EXTRANJERO';
			$lugNac=$respuesta['ConsultaPorDatosResult']['EntidadFederativa'];
			$qEstadoNac="SELECT estado as edoNac, id FROM estados where clave='$lugNac'"; 
			$rEstadoNac=$mysqli->query($qEstadoNac);
			while($rlugNac = $rEstadoNac->fetch_assoc()){
				$idEdoNac=$rlugNac['id'];
				$edoNac=$rlugNac['edoNac'];
			}
			$statusCurp=$respuesta['ConsultaPorDatosResult']['StatusCURP'];
			switch ($statusCurp) {
				case 'AN':
					$stCurp="ACTIVA: ALTA NORMAL";
					break;
				case 'AH':
					$stCurp="ACTIVA: ALTA CON HOMONIMIA";
					break;
				case 'CRA':
					$stCurp="ACTIVA: CURP REACTIVADA";
					break;
				case 'RCN':
					$stCurp="ACTIVA: REGISTRO DE CAMBIO NO AFECTANDO A CURP";
					break;
				case 'RCC':
					$stCurp="ACTIVA: REGISTRO DE CAMBIO AFECTANDO A CURP";
					break;
				case 'BD':
					$stCurp="BAJA POR DEFUNCION";
					break;
				case 'BDA':
					$stCurp="BAJA POR DUPLICIDAD";
					break;
				case 'BCC':
					$stCurp="BAJA POR CAMBIO EN CURP";
					break;
				case 'BCN':
					$stCurp="BAJA NO AFECTANDO A CURP";
					break;
				default:
					$stCurp="NINGUNO";
					break;
			}
			$DocProbatorio=$respuesta['ConsultaPorDatosResult']['DocProbatorio'];
			switch ($DocProbatorio) {
				case '1':
					$ADocProbatorio="ACTA DE NACIMIENTO";
					$anioReg=$respuesta['ConsultaPorDatosResult']['AnioReg'];
					$numActa=$respuesta['ConsultaPorDatosResult']['NumActa'];
					$idEstadoReg=$respuesta['ConsultaPorDatosResult']['EntidadRegistro'];
					$idMunReg=$respuesta['ConsultaPorDatosResult']['MunicipioRegistro'];
					if($idEstadoReg!='13'){
						$qEstadoReg="SELECT estadosMayus from estados where id='$idEstadoReg'";
						$rEstadoReg=$mysqli->query($qEstadoReg);
						if($rEstadoReg->num_rows==0)
							$lugarReg="";
						else {
						$arrEstadoReg=$rEstadoReg->fetch_assoc();
						$lugarReg=implode($arrEstadoReg); }
					} else {
						$qMunReg="SELECT municipioMayus from municipios where id='$idMunReg'";
						$rMunReg=$mysqli->query($qMunReg);
						$arrMunReg=$rMunReg->fetch_assoc();
						$MunReg=implode($arrMunReg);
						$lugarReg=$MunReg.", HIDALGO";	
					}
					break;
				case '3':
					$ADocProbatorio="DOCUMENTO MIGRATORIO";
					$numRegExtrajero=['ConsultaPorDatosResult']['NumRegExtranjeros'];
					break;
				case '4':
					$ADocProbatorio="CARTA DE NATURALIZACIÓN";
					$anioReg=$respuesta['ConsultaPorDatosResult']['AnioReg'];
					$folioCarta=$respuesta['ConsultaPorDatosResult']['FolioCarta'];
					break;
				case '7':
					$ADocProbatorio="CERTIFICADO DE NACIONALIDAD MEXICANA";
					$anioReg=$respuesta['ConsultaPorDatosResult']['AnioReg'];
					$folioCarta=$respuesta['ConsultaPorDatosResult']['FolioCarta'];
					break;
				default:
					$ADocProbatorio="TRAMITE ANTE SEGOB";
					$folio=$respuesta['ConsultaPorDatosResult']['CRIP'];
					break;
			}		
			if($DocProbatorio!=1){
				$anioReg=null;
				$numActa=null;
				$lugarReg=null;
			}			
			$tipo=2;
			$persona= [
				"nombre" => "$nombre", 
				"curp" => "$curp",
				"ape1" => "$apellido_p",
				"ape2" => "$apellido_m",
				"sexo" => "$sexo",
				"fechaNac" => "$fechaM",
				"nacionalidad" =>   "$nacionalidad",
				"edoNac" => "$idEdoNac",
				"stCurp" => "$stCurp",
				"docPro" => "$ADocProbatorio",
				"anioReg" => "$anioReg",
				"numActa" => "$numActa",
				"lugReg" => "$lugarReg",
			];
			$_SESSION['persona'] = $persona;
		} else header("Location: validarCurp.php?id=$id&T=4");
	}

	if(!empty($_POST['btnActualizar'])){
		$persona = $_SESSION['persona']; 
		$curp= $persona['curp'];
		$nombre= $persona['nombre'];
		$ape1= $persona['ape1'];
		$ape2 = $persona['ape2'];
		$sexo = $persona['sexo'];
		$fechaNac = $persona['fechaNac']; 
		$idEdoNac = $persona['edoNac'];		
		$nacionalidad = $persona['nacionalidad'];
		$stCurp = $persona['stCurp'];
		$ADocProbatorio = $persona['docPro'];
		$qHst="INSERT INTO historico_datos_nna (id_nna, nombre, apellido_p, apellido_m, curp, fecha_nac, 
		sexo, lugar_nac, lugar_registro, fecha_registro, id_respo_registro, id_estado_nacimiento)
		SELECT $id, nombre, apellido_p, apellido_m, curp, fecha_nac, sexo, lugar_nac, lugar_reg, '$fecha', 
		$idDEPTO, id_estado_nacimiento from nna where id=$id";
		$rHst=$mysqli->query($qHst);
		if($rHst){
			if($ADocProbatorio=='ACTA DE NACIMIENTO'){
				$numActa = $persona['numActa'];
				$anioReg = $persona['anioReg'];
				$lugarReg= $persona['lugReg'];
				$actNna="UPDATE nna SET nombre='$nombre', apellido_p='$ape1', 
				apellido_m = '$ape2', curp= '$curp', fecha_nacimiento= '$fechaNac',
				sexo = '$sexo', id_estado_nacimiento = '$idEdoNac', 
				nacionalidad= '$nacionalidad', statusCurp = '$stCurp', docProbatorio = '$ADocProbatorio', 
				NumActa= '$numActa', anioReg = '$anioReg', lugar_reg= '$lugarReg',
				validacionRenapo = '1' 
				WHERE id = '$id'";
			} else {
				$actNna="UPDATE nna SET nombre='$nombre', apellido_p='$ape1',
				apellido_m = '$ape2', curp= '$curp',fecha_nacimiento = '$fechaNac',
				sexo = '$sexo', id_estado_nacimiento = '$idEdoNac', 
				nacionalidad= '$nacionalidad', statusCurp = '$stCurp', docProbatorio = '$ADocProbatorio', 
				validacionRenapo = '1' WHERE id = '$id'";
			}
			$ractNna=$mysqli->query($actNna);
			if($ractNna)
				echo "<script>
					alert('Se ha validado la CURP correctamente');
					window.location= 'perfil_nna.php?id=$id'
				</script>";
			else echo $actNna;			
		} else echo $qHst;
	}
?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Validar CURP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
	</head>
	<body>
		<div id="wrapper">
			<div id="main">
				<div class="inner">
					<div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div>
					<h3></h3>
					<?php if($tipo==1 or $tipo==3 or $tipo==4) { ?>
						<h3>Por favor complete alguna de las opciones: </h3>
						<div class="row uniform">
							<div class="4u">
								<form method="POST" name="frmValida" id="frmValida">
									<div class="box">
										<div class="row uniform">
											<?php if ($tipo==3) {?>
												<div class="12u">
													<div style = "font-size:16px; color:#FF0000; font-weight: bold;"><?php echo "CURP INVALIDA" ?></div>
												</div>
											<?php } ?>
											<div class="12u">
												<h4>Opcion A</h4>
											</div>
										</div><br>
										<div class="row uniform">
											<div class="12u">
												<label for="curp">CURP:</label>
												<input id="curp" pattern="[A-Z]{4}\d{6}[HM][A-Z]{2}[B-DF-HJ-NP-TV-Z]{3}[A-Z0-9][0-9]" name="curp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" <?php if($ccurp!='X') { ?> value="<?=$ccurp ?>" <?php } ?> required="true">
											</div><br>
										</div>
										<div class="row uniform">
											<div class="12u">
												<input type="submit" name="btnCurp" class="button special fit" value="Validar CURP"> 
											</div>
										</div>
									</div>
								</form>
							</div>
							<div class="8u">
								<form method="POST" name="frmDatos" id="frmDatos">
									<div class="box">
										<div class="row uniform">
											<?php if ($tipo==4) {?>
												<div class="12u">
													<div style = "font-size:16px; color:#FF0000; font-weight: bold;"><?php echo "DATOS INVALIDOS" ?></div>
												</div>
											<?php } ?>
											<div class="12u">
												<h4>Opcion B</h4>
											</div>
										</div>
										<div class="row unifor">
											<div class="4u 12u$(xsmall)">
												<label for="nombre">Nombre (s):</label>
												<input id="nombre" name="nombre" type="text" value="<?=$cnombre?>" pattern="[A-ZÑ./-‘ ]{0,50}" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required="true">
											</div>
											<div class="4u 12u$(xsmall)">
												<label for="apellido_p">Primer apellido:</label>
												<input id="apellido_p" name="apellido_p" type="text" value="<?=$cape1?>" pattern="[A-Z./-‘ ]{0,50}" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required="true">
											</div>
											<div class="4u 12u$(xsmall)">
												<label for="apellido_m">Segundo apellido:</label>
					                            <input id="apellido_m" name="apellido_m" type="text" value="<?=$cape2?>" pattern="[A-ZÑ./-‘ ]{0,50}" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >
					                        </div>
					                    </div>
				                        <div class="row uniform">
				                            <div class="4u 12u$(xsmall)">
				                             	<label for="sexo">Sexo:</label>
							                    <div class="select-wrapper">
							                        <select id="sexo" name="sexo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >
							                           	<option value="<?=$csexo?>"><?=$cArSex ?></option>
							                           	<option value="M">MUJER</option>
							                           	<option value="H">HOMBRE</option>	
							                        </select>
							                    </div>
							                </div>
				                               
				                            <div class="4u 12u$(xsmall)">
				                              	<label for="fecha_nacimiento">Fecha de nacimiento:</label>
				                                <input id="fecha_nacimiento" name="fecha_nacimiento" <?php if($cfechaNac!='1900-01-01') { ?> value="<?=$cfechaNac ?>" <?php } ?> type="date" required="true" >
				                            </div>
				                            
				                            <div class="4u 12u$(xsmall)">
				                              	<label for="country_id">Lugar de nacimiento:</label>
				                              	<div class="select-wrapper">
				                              		<select id="country_id" class="form-control" name="country_id" required="true">
				                              			<?php if($cestadoNac=='X') { ?>
				                              				<option value="">-- Seleccione --</option>
				                              			<?php } else { ?>
				                              				<option value="<?= $cclaveEdo ?>"><?= $cestadoNac ?></option>
				                              			<?php } 
				                              			foreach($countries as $c):?>
				                              				<option value="<?php echo $c->clave; ?>"><?php echo $c->estado; ?></option>
				                              			<?php endforeach; ?>
				                              		</select>
				                              	</div>
				                            </div> 
				                        </div>
				                        <div class="row uniform">
				                        	<div class="12u">
				                        		<input type="submit" name="datos" class="button special fit" value="Validar datos">
				                        	</div>
				                        </div>
				                    </div>
				                </form>
				            </div>
				        </div>
				    <?php } else if($tipo==2) { ?>
				    	<h3>¡Consulta exitosa!</h3>
				    	<form method="POST" name="frmAct" id="frmAct">
				    		<div class="box">
				    			<div class="row uniform">				    				
				    				<div class="4u">
				    					<label>Nombre(s): <?php if($nombre!=$cnombre) { ?> <div style = "font-size:11px; color:#CC0000; font-weight: bold;"> <?= $cnombre?> </div> <?php } else { ?> <div style = "font-size:12px; color:#00cc00; font-weight: bold;"> ✓</div> <?php }?></label>
				    					<input type="text" name="txtNombre" value="<?= $nombre ?>" disabled>
				    				</div>
				    				<div class="4u">
				    					<label>Primer apellido: <?php if($apellido_p!=$cape1 ) { ?> <div style = "font-size:11px; color:#CC0000; font-weight: bold;"> <?= $cape1?> </div> <?php } else { ?> <div style = "font-size:12px; color:#00cc00; font-weight: bold;"> ✓</div> <?php }?></label>
				    					<input type="text" name="txtApe1" value="<?= $apellido_p ?>" disabled>
				    				</div>
				    				<div class="4u">
				    					<label>Segundo apellido: <?php if($apellido_m!=$cape2) { ?> <div style = "font-size:11px; color:#CC0000; font-weight: bold;"> <?= $cape2?> </div> <?php } else { ?> <div style = "font-size:12px; color:#00cc00; font-weight: bold;"> ✓</div> <?php }?></label>
				    					<input type="text" name="txtApe2" value="<?= $apellido_m ?>" disabled>
				    				</div>
				    			</div>
				    			<div class="row uniform">
				    				<div class="4u">
				    					<label>CURP: <?php if($curp!=$ccurp) { ?> <div style = "font-size:11px; color:#CC0000; font-weight: bold;"> <?= $ccurp?> </div> <?php } else { ?> <div style = "font-size:12px; color:#00cc00; font-weight: bold;"> ✓</div> <?php }?></label>
				    					<input type="text" name="txtCurp" id="txtCurp" value="<?= $curp ?>" disabled >
				    				</div>
				    				<div class="2u">
				    					<label>Sexo: <?php if($sexo!=$cArSex) { ?>  <div style = "font-size:11px; color:#CC0000; font-weight: bold;"> <?= $cArSex ?> </div><?php } else { ?> <div style = "font-size:12px; color:#00cc00; font-weight: bold;"> ✓</div> <?php }?></label>
				    					<input type="text" name="txtSexo" value="<?= $sexo ?>" disabled>
				    				</div>
				    				<div class="3u">
				    					<label>Fecha de nacimiento: <?php if($ComFechaNac!=$fechaNac) { ?>  <div style = "font-size:11px; color:#CC0000; font-weight: bold;"> <?= $ComFechaNac ?> </div><?php } else { ?> <div style = "font-size:12px; color:#00cc00; font-weight: bold;"> ✓</div> <?php }?></label>
				    					<input type="date" name="txtFecNac" value="<?= $fechaM ?>" disabled>
				    				</div>
				    				<div class="3u">
				    					<label>Entidad de nacimiento: <?php if($cestadoNac!=$edoNac) { ?> <div style = "font-size:11px; color:#CC0000; font-weight: bold;"> <?= $cestadoNac ?> </div><?php } else { ?> <div style = "font-size:12px; color:#00cc00; font-weight: bold;"> ✓</div> <?php }?></label>
				    					<input type="text" name="txtEdoNac" value="<?= $edoNac ?>" disabled>
				    				</div>
				    			</div>
				    			<br>
				    			<ul class="alt"><li></li><li></li></ul>
				    			<div class="row uniform">
				    				<div class="2u">
				    					<label for="txtnacionalidad">Nacionalidad</label>
										<input id="txtnacionalidad" name="txtnacionalidad" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$nacionalidad?>" disabled>
									</div>
									<div class="7u">
										<label for="txtStaCurp">Estatus CURP</label>
										<input id="txtStaCurp" name="txtStaCurp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$stCurp?>" disabled>
									</div>
									<div class="3u">
										<label for="txtDoc">Documento probatorio</label>
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
				    			</div>
				    			<?php if($DocProbatorio==1){ ?>
									<div class="row uniform">									
										<div class="3u">
											<label>Num. Acta</label>
											<input id="txtNumAct" name="txtNumAct" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$numActa?>" disabled>
										</div>
										<div class="3u">
											<label>Año de registro</label>
											<input id="txtAnioReg" name="txtAnioReg" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$anioReg?>" disabled>
										</div>
										<div class="6u">
											<label>Lugar de Registro</label>
											<input id="txtLugReg" name="txtLugReg" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$lugarReg?>" disabled>
										</div>
									</div>
								<?php } ?> 									
							</div>
							<div class="row uniform">
								<div class="12u">
									<input type="submit" name="btnActualizar" class="button special fit" value="Actualizar datos">
								</div>
							</div>	
						</form>
				    <?php } ?>
				    <div class="12u 12u$(xsmall)">
						<input type="button" name="cancelar" value="carcelar" class="button fit" onclick="history.go(-1);">
					</div>
				</div>				
			</div>	
					
		
			<div id="sidebar">
				<div class="inner">
					<?php $_SESSION['spcargo'] = $idPersonal; ?>
					<?php if($idPersonal==6) { //UIENNAVD?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Menú</h2>
							</header>
							<ul>
								<li><a href="welcome.php">Inicio</a></li>	
								<li><a href="lista_unidad.php">UIENNAVD</a></li>
								<li><a href="logout.php">Cerrar sesión</a></li>
							</ul>
						</nav>	
					<?php }else if($idPersonal==5) { //Subprocu ?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Menú</h2>
							</header>
							<ul>
								<li><a href="welcome.php">Inicio</a></li>	
								<li><a href="lista_personal.php">Personal</a></li>
								<li><a href="lista_usuarios.php">Usuarios</a></li>			
								<li><a href="lista_reportes_nueva.php?estRep=0">Reportes VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a></li>
								<li><a href="lista_casos.php">Casos</a></li>
								<li><a href="lista_nna.php">NNA</a></li>
								<li><span class="opener">Carpetas</span>
									<ul>
										<li><a href="lista_carpeta.php">Carpetas</a></li>
										<li><a href="lista_imputados.php">Imputados</a></li>			
									</ul>
								</li>		
								<li><a href="cas.php">CAS</a></li>							
								<li><span class="opener">Pendientes</span>
									<ul>
										<li><a href="carpetas_sasignar.php">Carpetas por asignar</a></li>
										<li><a href="nna_pendientes.php">NNA sin curp</a></li>
										<li><a href="visitas_fecha.php">Buscador</a></li>			
									</ul>
								</li>
								<li><a href="lista_documentos.php">Descarga de oficios</a></li>
								<li><a href="alta_medida.php">Catalogo de medidas</a></li>
								<li><a href="logout.php">Cerrar sesión</a></li>
							</ul>
						</nav>	
					<?php }else { ?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Menú</h2>
							</header>
							<ul>
								<li><a href="welcome.php">Inicio</a></li>	
								<li><a href="lista_personal.php">Personal</a></li>
								<li><a href="lista_usuarios.php">Usuarios</a></li>
								<?php if ($_SESSION['departamento']==7) { ?>
									<li><a href="canalizar.php">Canalizar visita</a></li>	
								<?php } ?>												
								<li><a href="lista_reportes_nueva.php?estRep=0">Reportes VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a></li>
								<li><a href="lista_casos.php">Casos</a></li>
								<li><a href="lista_nna.php">NNA</a></li>		
								<li><a href="reg_reporte_migrantes.php">Migrantes</a></li>
								<li><span class="opener">Carpetas</span>
									<ul>
										<li><a href="lista_carpeta.php">Carpetas</a></li>
										<li><a href="lista_imputados.php">Imputados</a></li>			
									</ul>
								</li>
								<li><a href="cas.php">CAS</a></li>
								<li><span class="opener">UIENNAVD</span>
									<ul>
										<li><a href="lista_unidad.php">Beneficiarios</a></li>
										<li><a href="visitas_gen_unidad.php">Historial de visitas</a></li>
									</ul>
								</li>						
								<?php if (($_SESSION['departamento']==16) or ($_SESSION['departamento']==7)) { ?>
									<li><span class="opener">Visitas</span>
										<ul>
											<li><a href="editar_visitadepto.php">Editar departamento</a></li>
											<li><a href="editar_visitarespo.php">Editar responsable</a></li>
											<li><a href="eliminar_visita.php">Eliminar</a></li>
										</ul>
									</li>
								<?php } ?>									
								<li><span class="opener">Pendientes</span>
									<ul>
										<li><a href="carpetas_sasignar.php">Carpetas por asignar</a></li>
										<li><a href="nna_pendientes.php">NNA sin curp</a></li>			
										<li><a href="visitas_fecha.php">Buscador</a></li>				
									</ul>
								</li>									
								<li>
									<span class="opener">Adopciones</span>
									<ul>
										<li><a href="reg_expAdop.php">Generar expediente</a></li>
										<li><a href="">Expedientes</a></li>
									</ul>
								</li>
								<?php if ($_SESSION['departamento']==16 or $_SESSION['departamento']==14) {  ?>
									<li><a href="reg_actccpi.php">CCPI</a></li>
								<?php } ?>
								<li><a href="numoficio.php">Numero de oficio</a></li>
								 
								<li><a href="lista_documentos.php">Descarga de oficios</a></li>
								<li><a href="alta_medida.php">Catalogo de medidas</a></li>
								<li><a href="logout.php">Cerrar sesión</a></li>
							</ul>
						</nav>	
					<?php }?>
					<section>
						<header class="major">
							<h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
						</header>
						<p></p>
						<ul class="contact">
							<li class="fa-envelope-o">laura.ramirez@hidalgo.gob.mx</li>
							<li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
							<li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
							<li class="fa-phone"><a href="directorio.php">Directorio interno</a></li>
							<li class="fa-home">Plaza Juarez #118<br />
								Col. Centro <br> Pachuca Hidalgo</li>
						</ul>
					</section>
					<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; Sistema DIF Hidalgo </p>
					</footer>
				</div>
			</div><!--cierre de menu-->
		</div>  <!--cierre de wrapper-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>