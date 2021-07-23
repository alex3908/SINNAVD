<?php	
error_reporting(E_ALL ^ E_NOTICE);
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
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
	$existeCurp=null;
	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;
	$persona1 = $_SESSION['persona'];
	$curp= trim($persona1['curp']);
	$nombre= trim($persona1['nombre']);
	$ape1= trim($persona1['ape1']);
	$ape2= trim($persona1['ape2']);
	$sexo = $persona1['sexo'];
	if($sexo==1)
		$sex="H";
	elseif($sexo==2)
		$sex="M";
	$fecNac = $persona1['fecNac'];
	$edoNac = $persona1['edoNac'];
	$estatus=null;
	$qEdCivil="SELECT * from cat_estado_civil";
	$rEdoCivil=$mysqli->query($qEdCivil);
	$query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($rowEstados=$equery->fetch_object())	{ $countries[]=$rowEstados; }

	if(!empty($curp)){ //hay una curp
		$cliente= new SoapClient('http://172.16.1.42/sistemas/wssieb/WebServiceBeneficiarios.asmx?wsdl');  //crea un nuevo cliente
		$parametros=array('CURP'=>"$curp");
		$respuesta= $cliente->ConsultaPorCurp($parametros); //consulta por curp
		$respuesta= json_decode(json_encode($respuesta), True);
		$estatus=$respuesta['ConsultaPorCurpResult']['StatusOper']; 
		if($estatus=='EXITOSO') //devuelve valores
		{
			$nombre=$respuesta['ConsultaPorCurpResult']['Nombres'];
			$ape1=$respuesta['ConsultaPorCurpResult']['Apellido1'];
			$ape2=$respuesta['ConsultaPorCurpResult']['Apellido2'];
			$sexo=$respuesta['ConsultaPorCurpResult']['Sexo'];
			if($sexo=='M')
				$sexo='MUJER';
			else if($sexo=='H')
				$sexo='HOMBRE';
			$fechaHorNac=$respuesta['ConsultaPorCurpResult']['FechNac'];	
			$ArfechaHorNac = explode("T",$fechaHorNac);
			$fecNac=$ArfechaHorNac[0];		
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

		} else if(!empty($nombre) and !empty($ape1) and !empty($sexo) and !empty($fecNac) and !empty($edoNac)) { //no se pudo consultar x curp
			$persona= [ 
			    "Nombres" => "$nombre",
			    "Apellido1" => "$ape1",
			    "Apellido2" => "$ape2",
			    "FechNac" => "$fecNac",
			    "Sexo" => "$sex",
				"EntidadRegistro" => "$edoNac",
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
				$ape1=$respuesta['ConsultaPorDatosResult']['Apellido1'];
				$ape2=$respuesta['ConsultaPorDatosResult']['Apellido2'];
				$sexo=$respuesta['ConsultaPorDatosResult']['Sexo'];
				if($sexo=='M')
					$sexo='MUJER';
				else if($sexo=='H')
					$sexo='HOMBRE';
				$fechaHorNac=$respuesta['ConsultaPorDatosResult']['FechNac'];
				$ArfechaHorNac = explode("T",$fechaHorNac);
				$fecNac=$ArfechaHorNac[0];		
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
			}	
		}
	} else if(!empty($nombre) and !empty($ape1) and !empty($sexo) and !empty($fecNac) and !empty($edoNac)){ // no hay curp checa que los demas datos esten completos
		$persona= [ 
			"Nombres" => "$nombre",
			"Apellido1" => "$ape1",
			"Apellido2" => "$ape2",
			"FechNac" => "$fecNac",
			"Sexo" => "$sex",
			"EntidadRegistro" => "$edoNac",
		]; //si estan completos hace la consulta
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
			$ape1=$respuesta['ConsultaPorDatosResult']['Apellido1'];
			$ape2=$respuesta['ConsultaPorDatosResult']['Apellido2'];
			$sexo=$respuesta['ConsultaPorDatosResult']['Sexo'];
			if($sexo=='M')
				$sexo='MUJER';
			else if($sexo=='H')
				$sexo='HOMBRE';
			$fechaHorNac=$respuesta['ConsultaPorDatosResult']['FechNac'];
			$ArfechaHorNac = explode("T",$fechaHorNac);
			$fecNac=$ArfechaHorNac[0];		
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
		}
	}
	if($sex=='M')
		$sexo1='MUJER';
	elseif($sex=='H')
		$sexo1='HOMBRE';
	$qEstado="SELECT estado from estados where clave='$edoNac'";
	$rEstado=$mysqli->query($qEstado);
	while ($rwEdo=$rEstado->fetch_assoc()) {  
		$edoNac=$rwEdo['estado'];
	}
	if(!empty($curp)) {
		$qverificarCurp="SELECT id, folio from nna where curp='$curp'";
        $rverificarCurp=$mysqli->query($qverificarCurp);
        $existeCurp=$rverificarCurp->num_rows;  //virifica que esa curp no este registrada ya
        if($existeCurp>0)  {              
	        while ($rowNnaRegistrado=$rverificarCurp->fetch_assoc()) {  //si ya esta registrada toma el id y folio de la tabla nna
	            $idNnaReg=$rowNnaRegistrado['id'];
	            $folioNnaReg=$rowNnaRegistrado['folio'];
	        }
	    }
	}

	if(isset($_POST['btnRegistar'])) {  
		$fecha = date("Y-m-d H:i:s", time());
		if (isset($_POST['indigena']) && $_POST['indigena'] == '1')
			$banIndigena="1";
		else 
			$banIndigena="0";

		if (isset($_POST['afrodescendiente']) && $_POST['afrodescendiente'] == '1')
			$banAfro="1";
		else 
			$banAfro="0";

		if (isset($_POST['migrante']) && $_POST['migrante'] == '1')
			$banMigra="1";
		else 
			$banMigra="0";

		$est_civil=$_POST['edo_civil'];
		$txtLugarNacimiento = mysqli_real_escape_string($mysqli,$_POST['lugarNacimiento']);
		if(empty($lugarReg))
			$lugarReg= mysqli_real_escape_string($mysqli,$_POST['LugarRegistro']);
		$id_estado = $_POST['country_id'];
		$id_mun = $_POST['state_id'];
		$id_loc = $_POST['city_id'];
		$direccion = mysqli_real_escape_string($mysqli,$_POST['txtDireccion']);
		$personaRes= mysqli_real_escape_string($mysqli,$_POST['txtPersona']);
		$parentesco= mysqli_real_escape_string($mysqli,$_POST['txtParentesco']);
		$telefono= mysqli_real_escape_string($mysqli,$_POST['txtTelefono']);
		$dirRespo= mysqli_real_escape_string($mysqli,$_POST['txtDirPersona']);
		$observa= mysqli_real_escape_string($mysqli,$_POST['txtDirPersona']);
		if($sexo=='M' or $sexo=='2')
		$sexo='MUJER';
		else if($sexo=='H' or $sexo=='1')
		$sexo='HOMBRE';
		if($estatus!='EXITOSO'){
			$persona1 = $_SESSION['persona'];
			$nombre=$persona1['nombre'];
            $apellido_p=$persona1['ape1'];
            $apellido_m=$persona1['ape2'];
            $fechaNac=$persona1['fecNac'];
            if(empty($fechaNac))
            	$fechaNac='1900-01-01';
			$lugNac=$persona1['edoNac'];
			if(!empty($lug)){
				$qidEstadoNac="SELECT id from estados where clave='$lugNac'";
				$ridEstadoNac=$mysqli->query($qidEstadoNac);
				while ($filaIdEdo=$ridEstadoNac->fetch_assoc()) {
            		$entiNac=$filaIdEdo['id'];
        		}
        	} else $entiNac=0;
			 //folio
        	$lnom=substr($nombre, 0,1);
	        $lap=substr($apellido_p, 0,1);
    	    $lam=substr($apellido_m, 0,1);
    	    $snum='SELECT terminacion from nfolio where id=1';
    	    $esnum=$mysqli->query($snum);
        	while ($row=$esnum->fetch_assoc()) {
            	$ter=$row['terminacion'];
        	}
	        $ter2=$ter+1;
			$folio=$lnom.$lap.$lam.$ter2; 
			$contador="UPDATE nfolio set terminacion='$ter2' where id=1";
			$econt=$mysqli->query($contador);
			if(empty($curp)){
			$qRegistroNna="INSERT INTO nna(folio, nombre, apellido_p, apellido_m,  fecha_nacimiento, sexo, 
                id_estado_nacimiento, lugar_nac, lugar_reg, estado, respo_reg, nna_ex, fecha_registro, id_estado, municipio, localidad, direccion, indigena, afrodescendiente, migrante, id_estado_civil, responna, parentesco, direccion_respo, telefono, observaciones) 
                VALUES ('$folio', '$nombre', '$apellido_p', '$apellido_m',  '$fechaNac', '$sexo', 
                '$entiNac', '$txtLugarNacimiento', '$lugarReg', 'P', '$idDEPTO', '0', '$fecha', '$id_estado', '$id_mun', '$id_loc', '$direccion', '$banIndigena', '$banAfro','$banMigra', '$est_civil', '$personaRes', '$parentesco', '$dirRespo', '$telefono', '$observa')"; 
			} else {
				$qRegistroNna="INSERT INTO nna(folio, nombre, apellido_p, apellido_m, curp, fecha_nacimiento, sexo, 
                id_estado_nacimiento, lugar_nac, lugar_reg, estado, respo_reg, nna_ex, fecha_registro, id_estado, municipio, localidad, direccion, indigena, afrodescendiente, migrante, id_estado_civil, responna,
                parentesco, direccion_respo, telefono, observaciones) 
                VALUES ('$folio', '$nombre', '$apellido_p', '$apellido_m',  '$curp', '$fechaNac', '$sexo', 
                '$entiNac', '$txtLugarNacimiento', '$lugarReg', 'P', '$idDEPTO', '0', '$fecha', '$id_estado', '$id_mun', '$id_loc', '$direccion', '$banIndigena', '$banAfro','$banMigra', '$est_civil',
                 '$personaRes', '$parentesco', '$dirRespo', '$telefono', '$observa')"; 
			}
			
		} else {
			$lnom=substr($nombre, 0,1);
		   	$lap=substr($ape1, 0,1);
		  	$lam=substr($ape2, 0,1);
		   	$snum='SELECT terminacion from nfolio where id=1';
		   	$esnum=$mysqli->query($snum);
		   	while ($row=$esnum->fetch_assoc()) {
			   $ter=$row['terminacion'];
			}
			   $ter2=$ter+1;
			   $contador="UPDATE nfolio set terminacion='$ter2' where id=1";
			$econt=$mysqli->query($contador);
		   	$folio=$lnom.$lap.$lam.$ter2; 
		   	$qidEstadoNac="SELECT id from estados where clave='$lugNac'"; //obtener id del estado de nacimiento
		   	$ridEstadoNac=$mysqli->query($qidEstadoNac);
		   	while ($filaIdEdo=$ridEstadoNac->fetch_assoc()) {
			   $entiNac=$filaIdEdo['id'];
		   	}
		   	if($DocProbatorio=='1'){ //acta de nacimiento
			   $qRegistroNna="INSERT INTO nna(folio, nombre, apellido_p, apellido_m, curp, fecha_nacimiento,
			   sexo, id_estado_nacimiento, lugar_nac, lugar_reg, estado, respo_reg, nna_ex, fecha_registro,
			   nacionalidad, statusCurp, DocProbatorio, NumActa, anioReg, idMunReg, idEstadoReg, 
			   validacionRenapo, id_estado, municipio, localidad, direccion, id_estado_civil,
			   indigena, afrodescendiente, migrante, responna, parentesco, direccion_respo, observaciones, telefono ) 
			   VALUES ('$folio', '$nombre', '$ape1', '$ape2', '$curp', '$fecNac', '$sexo', 
			   '$entiNac','$txtLugarNacimiento','$lugarReg','P', '$idDEPTO', '0', '$fecha', '$nacionalidad',
			   '$stCurp', '$ADocProbatorio', '$numActa', '$anioReg', '$idMunReg', '$idEstadoReg', '1', 
			   '$id_estado', '$id_mun', '$id_loc', '$direccion', '$est_civil','$banIndigena', '$banAfro', 
			   '$banMigra', '$personaRes', '$parentesco', '$dirRespo', '$observa', '$telefono')";
		   	} else {
				$qRegistroNna="INSERT INTO nna(folio, nombre, apellido_p, apellido_m, curp, 
				fecha_nacimiento, sexo,id_estado_nacimiento, lugar_nac, lugar_reg, estado, respo_reg,
				nna_ex, fecha_registro, nacionalidad, statusCurp, DocProbatorio, validacionRenapo,
				id_estado, municipio, localidad, direccion, id_estado_civil, indigena, afrodescendiente, 
				migrante, responna, parentesco, direccion_respo, observaciones, telefono) 
				VALUES ('$folio', '$nombre', '$ape1', '$ape2', '$curp', '$fecNac', '$sexo', 
				'$entiNac', '$txtLugarNacimiento', '$lugarReg', 'P', '$idDEPTO', '0', '$fecha',
				'$nacionalidad', '$stCurp', '$ADocProbatorio', '1', '$id_estado', '$id_mun', '$id_loc', 
				'$direccion', '$est_civil', '$banIndigena', '$banAfro', '$banMigra', '$personaRes', '$parentesco', '$dirRespo', 
				'$observa', '$telefono')"; 
		   	}
		} 
		$rRegistroNna=$mysqli->query($qRegistroNna);
		if($rRegistroNna){
		   	$qidNna="SELECT max(id) from nna where folio='$folio'"; //selecciona el id de la tabla nna que se ha registrado
		   	$ridNna=$mysqli->query($qidNna);
		   	while ($rowidnna=$ridNna->fetch_assoc()) {
			   $idNnaReg=$rowidnna['max(id)'];
		   	}
		   	$qHstDir="INSERT INTO `historico_direcciones_nna` (`id_nna`,`id_estado`,`municipio`,`localidad`,`direccion`,`fecha_reg`,`respo_registro`) values 
           	('$idNnaReg', '$id_estado', '$id_mun', '$id_loc', '$direccion', '$fecha', '$idDEPTO')";
           	$rHstDir=$mysqli->query($qHstDir);//inserto la dirección en el historial, si se llega a tener una actualiacion será en historial donde se realice 
          	$qidDireccion="SELECT max(id) from  historico_direcciones_nna where id_nna=$idNnaReg";
			$ridDireccion=$mysqli->query($qidDireccion);
			while ($rwDir=$ridDireccion->fetch_assoc()) {
				$idHisDirec=$rwDir['max(id)'];
            }
            $qActDire="UPDATE nna set id_direccion=$idHisDirec where id=$idNnaReg";
            $ractDirec=$mysqli->query($qActDire);
            if($rHstDir and $ractDirec){
            	header("Location: perfil_nna.php?id=$idNnaReg");
            } else echo $qHstDir."-".$qActDire;
        } else echo $qRegistroNna;
	}


?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Registrar NNA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
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
					<!--cod -->
					<?php if(empty($estatus)) { ?>
						<h3>Datos incompletos, no fue posible la validacion ante RENAPO</h3>
					<?php } elseif($estatus=='NO EXITOSO') { ?>
						<h3>No se encontraron los datos en renapo</h3>
					<?php } elseif($estatus=='EXITOSO') { ?>
						<h3>¡Consulta exitosa! CURP validada</h3>
					<?php } ?>
					<div class="box">
						<div class="row uniform">				    				
				    		<div class="4u">
				    			<label>Nombre(s):</label>
				    			<input type="text" name="txtNombre" value="<?= $nombre ?>" disabled>
				    		</div>
				    		<div class="4u">
				    			<label>Primer apellido:</label>
				    			<input type="text" name="txtApe1" value="<?= $ape1 ?>" disabled>
				    		</div>
				    		<div class="4u">
				    			<label>Segundo apellido:</label>
				    			<input type="text" name="txtApe2" value="<?= $ape2 ?>" disabled>
				    		</div>
				    	</div>
				    	<div class="row uniform">
				    		<div class="4u">
				    			<label>CURP:</label>
				    			<input type="text" name="txtCurp" id="txtCurp" value="<?= $curp ?>" disabled >
				    		</div>
				    		<div class="2u">
				    			<label>Sexo:</label>
				    			<input type="text" name="txtSexo" value="<?= $sexo1 ?>" disabled>
				    		</div>
				    		<div class="3u">
				    			<label>Fecha de nacimiento:</label>
				    			<input type="date" name="txtFecNac" value="<?= $fecNac ?>" disabled>
				    		</div>
				    		<div class="3u">
				    			<label>Entidad de nacimiento:</label>
				    			<input type="text" name="txtEdoNac" style="text-transform:uppercase;" value="<?= $edoNac?>" disabled>
				    		</div>
				    	</div>
				    	<?php if($estatus=='EXITOSO') { ?>
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
							<?php } 
						} ?>
					</div>
					<?php if($existeCurp>0) { ?>
						<div class="row uniform">
							<div class="12u">
								<input type="button" name="nnaExistente" class="button special fit" value="Esta CURP ya esta registrada con el folio <?= $folioNnaReg?>" onclick="location='perfil_nna.php?id=<?= $idNnaReg ?>'">
							</div>
						</div>	
					<?php } else { ?>
						<h4>Por favor, completa la siguiente información para continuar</h4>
						<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
							<div class="box">
								<div class="row uniform">
									<?php if(empty($lugarReg)) { ?>
										<div class="2u">Lugar de nacimiento
											<input id="lugarNacimiento" name="lugarNacimiento" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >
										</div>
										<div class="2u">Lugar de registro
											<input id="LugarRegistro" name="LugarRegistro" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
										</div>
										<div class="2u">Estado civil y/o familiar
											<div class="select-wrapper">
												<select id="edo_civil" name="edo_civil" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required >
													<option value="">--Seleccione--</option>
													<?php while($rwc = $rEdoCivil->fetch_assoc()){ ?>
														<option value="<?php echo $rwc['id']; ?>"><?php echo $rwc['estado_civil']; ?></option>
													<?php }?>
												</select>
											</div>
										</div>
										<div class="4"><label>Origen</label>
											<div class="row uniform">
												<div class="5u 12u$(xmall">
													<input type="checkbox" id="indigena" name="indigena" value="1">
													<label for="indigena">Origen indigena</label>
												</div>
												<div class="5u 12u$(xmall">
													<input type="checkbox" id="afrodescendiente" name="afrodescendiente" value="1">
													<label for="afrodescendiente">Afrodescendiente</label>
												</div>
												<div class="2u 12u$(xmall">
													<input type="checkbox" id="migrante" name="migrante" value="1">
													<label for="migrante">Migrante</label>
												</div>
											</div>
										</div>
										<br>
									<?php } else { ?>
										<div class="3u">Lugar de nacimiento
											<input id="lugarNacimiento" name="lugarNacimiento" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >
										</div>
										<div class="3u">Estado civil y/o familiar
											<div class="select-wrapper">
												<select id="edo_civil" name="edo_civil" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required >
													<option value="">--Seleccione--</option>
													<?php while($rwc = $rEdoCivil->fetch_assoc()){ ?>
														<option value="<?php echo $rwc['id']; ?>"><?php echo $rwc['estado_civil']; ?></option>
													<?php }?>
												</select>
											</div>
										</div>
										<div class="6"><label>Origen</label>
											<div class="row uniform">
												<div class="5u 12u$(xmall">
													<input type="checkbox" id="indigena" name="indigena" value="1">
													<label for="indigena">Origen indigena</label>
												</div>
												<div class="5u 12u$(xmall">
													<input type="checkbox" id="afrodescendiente" name="afrodescendiente" value="1">
													<label for="afrodescendiente">Afrodescendiente</label>
												</div>
												<div class="2u 12u$(xmall">
													<input type="checkbox" id="migrante" name="migrante" value="1">
													<label for="migrante">Migrante</label>
												</div>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="row uniform">
									<div class="12u"><h4>Direccion del NNA</h4></div>
								</div>
								<div class="row uniform">
									<div class="4u">Estado
										<div class="select-wrapper">
											<select id="country_id" class="form-control" name="country_id" required>
												<option value="">-- Estado --</option>
												<?php foreach($countries as $c):?>
	      											<option value="<?php echo $c->id; ?>"><?php echo $c->estado; ?></option>
												<?php endforeach; ?>
	    									</select>
	    								</div>
									</div>
									<div class="4u">Municipio
										<div class="select-wrapper">
											<select id="state_id" class="form-control" name="state_id" required>
												<option value="">-- MUNICIPIO --</option>
											</select>
										</div> 
									</div>
									<div class="4u">Localidad
										<div class="select-wrapper">
											<select id="city_id" class="form-control" name="city_id" required>
												<option value="">-- LOCALIDAD --</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row uniform">
									<div class="12u">
										<input type="text" name="txtDireccion" id="txtDireccion" tyle="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="CALLE Y NÚMERO" >
									</div>
								</div>
							</div>
							<div class="box">
								<h4>Datos del responsable actual del menor</h4>
								<div class="row uniform">
									<div class="4u">Persona Responsable del NNA
										<input id="txtPersona" name="txtPersona" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="100">
									</div>
									<div class="4u">Parentesco
										<input id="txtParentesco" name="txtParentesco" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="50">
									</div>
									<div class="4u">Teléfono
										<input id="txtTelefono" name="txtTelefono" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="50">
									</div>
								</div>
								<div class="row uniform">
									<div class="6u">Dirección
										<input id="txtDirPersona" name="txtDirPersona" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="150">
									</div>
									<div class="6u">Observaciones
										<input id="txtObservaciones" name="txtObservaciones" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="150">
									</div>
								</div>
							</div>
							<div class="row uniform"> 
								<div class="12u">
									<input class="button special fit" name="btnRegistar" type="submit" value="Registrar" >
								</div>
							</div>
							<br>						
						</form>
						<script type="text/javascript">
							$(document).ready(function(){
								$("#country_id").change(function(){
									$.get("get_states.php","country_id="+$("#country_id").val(), function(data){
										$("#state_id").html(data);
										console.log(data);
									});
								});
								$("#state_id").change(function(){
									$.get("get_cities.php","state_id="+$("#state_id").val(), function(data){
										$("#city_id").html(data);
										console.log(data);
									});
								});
							});
						</script>						
					<?php } ?>
					<br>
					<div class="12u 12u$(xsmall)">
						<input type="button" name="cancelar" value="carcelar" class="button fit" onclick="location='lista_nna.php'">
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