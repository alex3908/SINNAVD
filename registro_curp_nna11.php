<?php	
	//variable registrado usada para verificar en que partes la curp esta registrada, 
	//simbologia: 0->no esta registrado ni en renapo ni en la BD (registro)
	//1-> registrada en la base de datos solamente (al buscar x curp), (actualiza)
	// 2-> solo en renapo, (registro)
	//3->en renapo y en la BD (actualiza solo responsable y direccion)
	//4-> datos incompletos (registro)
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	
	try {
	$cliente= new SoapClient('http://187.188.236.198:8090/sistemas/wssieb/WebServiceBeneficiarios.asmx?wsdl'); //crea un nuevo cliente

	$persona = $_SESSION['persona']; //toma los datos del nna
	$curp=$_GET['curp'];
	$idPc=$_GET['idPc'];
	$idNna=$_GET['idNna'];
	$qEdCivil="SELECT * from cat_estado_civil";
	$rEdoCivil=$mysqli->query($qEdCivil);
	$qName="SELECT id, id_name from relacion_names where id_nna_reportado='$idNna' and activo=1";
	$rName=$mysqli->query($qName);
    $numName= $rName->num_rows;
	if($numName>0){
        while ($ridName=$rName->fetch_assoc()) {
            $idName=$ridName['id_name'];
        }
        $urlReporte ='https://name-pruebas.inftelapps.com/api/reportes'; // si es name cra el cliente y toma datos de ws
        $jsonReporte = file_get_contents($urlReporte);        
        $arrReporte = json_decode($jsonReporte);
        $numReportes= count($arrReporte);  //obtiene el numero de reportes en el siname
        for($i=0; $i<$numReportes; $i++) //recorre las posiciones del json para encontrar a cual le correponde al nna que se quiere vincular
        {
            $idArr=$arrReporte[$i]->id;
            if($idName==$idArr){
              $num=$i; //obtiene la posicion del Json 
             $i=$numReportes;
            }
		}
		$lugaNac=($arrReporte[$num]->municipioNacimiento->nombre);
		$lugaReg=($arrReporte[$num]->municipioRegistroNacimiento->nombre);
		$calle=($arrReporte[$num]->domicilioCalleInfante);
		if(empty($calle)){ //es domicilio extendido
			$idEntidad=($arrReporte[$num]->domicilioExtendidoInfante->domicilioEstado->clave);
			$Entidad=($arrReporte[$num]->domicilioExtendidoInfante->domicilioEstado->nombre);
	      	$idmunicipio=($arrReporte[$num]->domicilioExtendidoInfante->domicilioMunicipio->clave);
	      	$Municipio=($arrReporte[$num]->domicilioExtendidoInfante->domicilioMunicipio->nombre);
	      	$idlocalidad=($arrReporte[$num]->domicilioExtendidoInfante->domicilioLocalidad->clave);
	      	$localidad=($arrReporte[$num]->domicilioExtendidoInfante->domicilioLocalidad->nombre);
	      	$calle=($arrReporte[$num]->domicilioExtendidoInfante->domicilioNombreVialidad);
		} else {
			$idEntidad=($arrReporte[$num]->domicilioEstadoInfante->clave);
			$Entidad=($arrReporte[$num]->domicilioEstadoInfante->nombre);
			$idmunicipio=($arrReporte[$num]->domicilioMunicipioInfante->clave);
			$Municipio=($arrReporte[$num]->domicilioMunicipioInfante->nombre);
			$idlocalidad=($arrReporte[$num]->domicilioLocalidadInfante->clave);
			$localidad=($arrReporte[$num]->domicilioLocalidadInfante->nombre);
			$calle=($arrReporte[$num]->domicilioCalleInfante);
		}
		$nombreRespo=($arrReporte[$num]->nombresMadre);
		if(!empty($nombreRespo)){
			$ape1Respo=($arrReporte[$num]->primerApellidoMadre);
			$ape2Respo=($arrReporte[$num]->segundoApellidoMadre);
			$paren="MADRE";
		} else {
			$nombreRespo=($arrReporte[$num]->nombresPadre);
			if(!empty($nombreRespo)){
				$ape1Respo=($arrReporte[$num]->primerApellidoPadre);
				$ape2Respo=($arrReporte[$num]->segundoApellidoPadre);
				$paren="PADRE";
			} else {
				$nombreRespo=($arrReporte[$num]->nombresOtro);
				$ape1Respo=($arrReporte[$num]->primerApellidoOtro);
				$ape2Respo=($arrReporte[$num]->segundoApellidoOtro);
				$paren=($arrReporte[$num]->parentesco);
			}
		}
		$tel=($arrReporte[$num]->telefono);

    }
	$_SESSION['Renapo'] = ""; //pasa respuesta a la pagina de relacion en caso de que exista nna en la bd
	$consulta=0;// si la consulta fue exitosa pasa el valor: 1 x curp, 2 x datos, o 0 si no fue existosa
	if(empty($curp)) {// no hay alguna curp
		$nombre=$persona['Nombres'];
            $apellido_p=$persona['Apellido1'];
            $apellido_m=$persona['Apellido2'];
            $sexo=$persona['Sexo'];
            $fechaNac=$persona['FechNac'];
			$lugNac=$persona['EntidadRegistro']; 

			 //verifica que no haya ningun dato vacio
			if(($fechaNac=='1900-01-01') or empty($lugNac)) {
				$PerCompleta=false;  //si hay datos vacios le pido que regrese a completarlos para proceder a buscar x datos 
				/*echo "<script>
                	alert('La CURP $curp no se encuentra en la base de datos, por favor, complete la información del NNA');   
                	window.location= 'registro_nna_curp.php?idPc=$idPc&idNnaR=$idNnaR'
            	</script>";*/
            	$registrado=4;//datos incompletos
            } else { 

		$parametros = new stdClass();
		$parametros->Persona=$persona;
		$respuesta= $cliente->ConsultaPorDatos($parametros);
		$respuesta= json_decode(json_encode($respuesta), True);
		$estatus=$respuesta['ConsultaPorDatosResult']['StatusOper']; //consulta x datos
		if($estatus=='EXITOSO') //es exitoso 
		{
			$_SESSION['Renapo'] = $respuesta;
			$consulta=2;
			$registrado=2;//registro en renapo
			$curp=$respuesta['ConsultaPorDatosResult']['CURP'];
			$nombre=$respuesta['ConsultaPorDatosResult']['Nombres'];
			$apellido_p=$respuesta['ConsultaPorDatosResult']['Apellido1'];
			$apellido_m=$respuesta['ConsultaPorDatosResult']['Apellido2'];
			$sexo=$respuesta['ConsultaPorDatosResult']['Sexo'];
			$fechaNac=$respuesta['ConsultaPorDatosResult']['FechNac'];
			$date = new DateTime($fechaNac);
			if($respuesta['ConsultaPorDatosResult']['Nacionalidad']=='MEX')
				$nacionalidad='MEXICANA';
			else 
				$nacionalidad='EXTRANJERO';
			$lugNac=$respuesta['ConsultaPorDatosResult']['EntidadFederativa'];
			/*$qEstadoNac="SELECT estadosMayus as edoNac FROM estados where clave='$lugNac'"; 
			$rEstadoNac=$mysqli->query($qEstadoNac);
			$arrEstadoNac=$rEstadoNac->fetch_assoc();
			$estadoNac=strtoupper(implode($arrEstadoNac));*/
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
			
			$qverificarCurp="SELECT id, folio, validacionRenapo, fecha_nac, fecha_nacimiento from nna where curp like '%$curp%'"; //busca que este registrada en la tabla nna
            $rverificarCurp=$mysqli->query($qverificarCurp);
            $existeCurp=$rverificarCurp->num_rows;  //verifica que esa curp no este registrada ya
            if ($existeCurp>0){
            	for($i=0; $i<$existeCurp; $i++){
                			$rowNnaRegistrado=$rverificarCurp->fetch_assoc();
							$idNnaReg=$rowNnaRegistrado['id'];
							$folioNnaReg=$rowNnaRegistrado['folio'];
							$validada=$rowNnaRegistrado['validacionRenapo'];
							if(empty($rowNnaRegistrado['fecha_nacimiento']))
                    			$fechaNacA=$rowNnaRegistrado['fecha_nac'];
                  			else
                    			$fechaNacA=$rowNnaRegistrado['fecha_nacimiento'];
							$qCaso="SELECT id_caso from nna_caso where id_nna=$idNnaReg";
							$rCaso=$mysqli->query($qCaso);
							$hayCaso=$rCaso->num_rows;
							if($hayCaso>0){
								$vCaso=$rCaso->fetch_assoc();
								$idCaso=implode($vCaso);
								$i=$existeCurp;
							} 
                		}
            	$registrado=3; // si ya esta registrado cambia a 3, esta en renapo y en la BD
            }
		} else { //sino esta en renapo, buscamos en la bd x nombre y fecha de nacimiento
			$nombre=$persona['Nombres'];
            $apellido_p=$persona['Apellido1'];
            $apellido_m=$persona['Apellido2'];
            $sexo=$persona['Sexo'];
			$fechaNac=$persona['FechNac'];
			$date = new DateTime($fechaNac);
			$lugNac=$persona['EntidadRegistro'];
			$registrado=0;
			/*$qverificarNNA="SELECT nna.id as id_nna, folio, curp, nna.nombre, apellido_m, apellido_p, apellido_m, casos.id as idC, folio_c  from nna inner join nna_caso nc on nc.id_nna=nna.id
				inner join casos on casos.id=nc.id_caso
				where nna.nombre like '%nombre%' and apellido_p like '%$apellido_p%' and apellido_m like '%$apellido_m' and fecha_nacimiento='$fechaNac'"; //busca registros similares
            $rverificarNNA=$mysqli->query($qverificarNNA);
            $existeReg=$rverificarNNA->num_rows;  //verifica que esa curp no este registrada ya
            if ($existeReg>0){
            	$registrado=1; // si ya esta registrado cambia la 1 , nombre en la BD sin curp gistrado en la BD
            } else {
            	
            }*/
		}
	}

	} else {  //hay una curp 
		$parametros=array('CURP'=>"$curp");
		$respuesta= $cliente->ConsultaPorCurp($parametros);
		$respuesta= json_decode(json_encode($respuesta), True);
		$estatus=$respuesta['ConsultaPorCurpResult']['StatusOper'];  //consulta x curp
		if($estatus=='EXITOSO') //devuelve valores
		{
			$_SESSION['Renapo'] = $respuesta;
			$consulta=1;
			$registrado=2;
			$nombre=$respuesta['ConsultaPorCurpResult']['Nombres'];
			$apellido_p=$respuesta['ConsultaPorCurpResult']['Apellido1'];
			$apellido_m=$respuesta['ConsultaPorCurpResult']['Apellido2'];
			$sexo=$respuesta['ConsultaPorCurpResult']['Sexo'];
			$fechaNac=$respuesta['ConsultaPorCurpResult']['FechNac'];
			$date = new DateTime($fechaNac);
			if($respuesta['ConsultaPorCurpResult']['Nacionalidad']=='MEX')
				$nacionalidad='MEXICANA';
			else 
				$nacionalidad='EXTRANJERO';
			$lugNac=$respuesta['ConsultaPorCurpResult']['EntidadFederativa'];
			/*$qEstadoNac="SELECT estadosMayus as edoNac FROM estados where clave='$lugNac'"; 
			$rEstadoNac=$mysqli->query($qEstadoNac);
			$arrEstadoNac=$rEstadoNac->fetch_assoc();
			$estadoNac=strtoupper(implode($arrEstadoNac));*/
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
						$qEstadoReg="SELECT estadosMayus from estados where id=$idEstadoReg";
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
			$qverificarCurp="SELECT id, folio, validacionRenapo, fecha_nac, fecha_nacimiento from nna where curp like '%$curp%'"; //busca que este registrada en la tabla nna
			$rverificarCurp=$mysqli->query($qverificarCurp);
            $existeCurp=$rverificarCurp->num_rows;  //verifica que esa curp no este registrada ya
            if ($existeCurp>0){
            	for($i=0; $i<$existeCurp; $i++){ //hay registro toma el nna que tenga un caso 
                			$rowNnaRegistrado=$rverificarCurp->fetch_assoc();
							$idNnaReg=$rowNnaRegistrado['id'];
							$folioNnaReg=$rowNnaRegistrado['folio'];
							$validada=$rowNnaRegistrado['validacionRenapo'];
							if(empty($rowNnaRegistrado['fecha_nacimiento']))
                    			$fechaNacA=$rowNnaRegistrado['fecha_nac'];
                  			else
                    			$fechaNacA=$rowNnaRegistrado['fecha_nacimiento'];
							$qCaso="SELECT id_caso from nna_caso where id_nna=$idNnaReg";
							$rCaso=$mysqli->query($qCaso);
							$hayCaso=$rCaso->num_rows;
							if($hayCaso>0){
								$vCaso=$rCaso->fetch_assoc();
								$idCaso=implode($vCaso);
								$i=$existeCurp;
							} 
                		}
            	$registrado=3; // si ya esta registrado cambia a 3, esta en renapo y en la BD
            }
		} else { 
            //al no encontrar datos x curp  recoge los de persona
        	$nombre=$persona['Nombres'];
            $apellido_p=$persona['Apellido1'];
            $apellido_m=$persona['Apellido2'];
            $sexo=$persona['Sexo'];
            $fechaNac=$persona['FechNac'];
			$lugNac=$persona['EntidadRegistro']; 

			 //verifica que no haya ningun dato vacio
			if(($fechaNac=='1900-01-01') or empty($lugNac)) {
				$PerCompleta=false;  //si hay datos vacios le pido que regrese a completarlos para proceder a buscar x datos 
				/*echo "<script>
                	alert('La CURP $curp no se encuentra en la base de datos, por favor, complete la información del NNA');   
                	window.location= 'registro_nna_curp.php?idPc=$idPc&idNnaR=$idNnaR'
            	</script>";*/
            	$registrado=4;//datos incompletos
            } else {  //si los datos estan completos, busca por datos
            	$PerCompleta=True; 
				$parametros = new stdClass();
				$parametros->Persona=$persona;
				$respuesta= $cliente->ConsultaPorDatos($parametros);
				$respuesta= json_decode(json_encode($respuesta), True);
				$estatus=$respuesta['ConsultaPorDatosResult']['StatusOper'];
				if($estatus=='EXITOSO')  //Si regresa los datos se los asigna a las variable
				{	
					$_SESSION['Renapo'] = $respuesta;	
					$consulta=2;			
					$registrado=2;
					$curp=$respuesta['ConsultaPorDatosResult']['CURP'];
					$nombre=$respuesta['ConsultaPorDatosResult']['Nombres'];
					$apellido_p=$respuesta['ConsultaPorDatosResult']['Apellido1'];
					$apellido_m=$respuesta['ConsultaPorDatosResult']['Apellido2'];
					$sexo=$respuesta['ConsultaPorDatosResult']['Sexo'];
					$fechaNac=$respuesta['ConsultaPorDatosResult']['FechNac'];
					$date = new DateTime($fechaNac);
					if($respuesta['ConsultaPorDatosResult']['Nacionalidad']=='MEX')
						$nacionalidad='MEXICANA';
					else 
						$nacionalidad='EXTRANJERO';
					$lugNac=$respuesta['ConsultaPorDatosResult']['EntidadFederativa'];
					/*$qEstadoNac="SELECT estadosMayus as edoNac FROM estados where clave='$lugNac'"; 
					$rEstadoNac=$mysqli->query($qEstadoNac);
					$arrEstadoNac=$rEstadoNac->fetch_assoc();
					$estadoNac=strtoupper(implode($arrEstadoNac));*/
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
					$qverificarCurp="SELECT id, folio, validacionRenapo, fecha_nac, fecha_nacimiento  from nna where curp like '%$curp%'"; //busca que este registrada en la tabla nna
           			$rverificarCurp=$mysqli->query($qverificarCurp);
            		$existeCurp=$rverificarCurp->num_rows;  //virifica que esa curp no este registrada ya
            		if($existeCurp>0)  {        //curp registrada      
                		for($i=0; $i<$existeCurp; $i++){
                			$rowNnaRegistrado=$rverificarCurp->fetch_assoc();
							$idNnaReg=$rowNnaRegistrado['id'];
							$folioNnaReg=$rowNnaRegistrado['folio'];
							if(empty($rowNnaRegistrado['fecha_nacimiento']))
                    			$fechaNacA=$rowNnaRegistrado['fecha_nac'];
                  			else
                    			$fechaNacA=$rowNnaRegistrado['fecha_nacimiento'];
							$validada=$rowNnaRegistrado['validacionRenapo'];
							$qCaso="SELECT id_caso from nna_caso where id_nna=$idNnaReg";
							$rCaso=$mysqli->query($qCaso);
							$hayCaso=$rCaso->num_rows;
							if($hayCaso>0){
								$vCaso=$rCaso->fetch_assoc();
								$idCaso=implode($vCaso);
								$i=$existeCurp;
							} 
                		}
                		/*while ($rowNnaRegistrado=$rverificarCurp->fetch_assoc()) {  //si ya esta registrada toma el id y folio de la tabla nna
                    	$idNnaReg=$rowNnaRegistrado['id'];
						$folioNnaReg=$rowNnaRegistrado['folio'];
						$validada=$rowNnaRegistrado['validacionRenapo'];
                		}*/
                		$registrado=3;
            		} 
				} else { //no encontro en renapo x datos
					
						$qverificarCurp="SELECT id, folio, fecha_nac, fecha_nacimiento  from nna where curp like '%$curp%'"; //busca que este registrada en la tabla nna
           				$rverificarCurp=$mysqli->query($qverificarCurp);
            			$existeCurp=$rverificarCurp->num_rows; 
						if($existeCurp>0)  {        //curp registrada      
							for($i=0; $i<$existeCurp; $i++){
								$rowNnaRegistrado=$rverificarCurp->fetch_assoc();
								$idNnaReg=$rowNnaRegistrado['id'];
								$folioNnaReg=$rowNnaRegistrado['folio'];
								if(empty($rowNnaRegistrado['fecha_nacimiento']))
									$fechaNacA=$rowNnaRegistrado['fecha_nac'];
								  else
									$fechaNacA=$rowNnaRegistrado['fecha_nacimiento'];
								$qCaso="SELECT id_caso from nna_caso where id_nna=$idNnaReg";
								$rCaso=$mysqli->query($qCaso);
								$hayCaso=$rCaso->num_rows;
								if($hayCaso>0){
									$vCaso=$rCaso->fetch_assoc();
									$idCaso=implode($vCaso);
									$i=$existeCurp;
								} 
							}
							$registrado=1;
						}
						else  $registrado=0;
				}
			}
		} // no se encontro curp en renapo
	}  //fin else hay curp
	if($sexo=='M')
		$sexo='MUJER';
	else 
		$sexo='HOMBRE';
	$qEstadoNac="SELECT estadosMayus as edoNac, id FROM estados where clave='$lugNac'"; 
	$rEstadoNac=$mysqli->query($qEstadoNac);
	while($rlugNac = $rEstadoNac->fetch_assoc()){
		$idEdoNac=$rlugNac['id'];
		$edoNac=$rlugNac['edoNac'];
	}

	$query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($rowEstados=$equery->fetch_object())	{ $countries[]=$rowEstados; }
	if($fechaNac=='1900-01-01'){
		$fechaNacM='dd/mm/yyyy';
	} else {
		$fechaNacM = date_create($fechaNac);
	
		$fechaNacM=(date_format($fechaNacM, 'd/m/Y')); 
	}
	$existeReg=0;
	if($registrado!=3 and $registrado!=4){
		$qverificarNNA="SELECT nna.id as id_nna, folio, curp, nna.nombre, apellido_p, apellido_m, 
		casos.id as idC, folio_c  from nna left join nna_caso nc on nc.id_nna=nna.id
				left join casos on casos.id=nc.id_caso
				where (nna.nombre like '%$nombre%' and apellido_p like '%$apellido_p%')"; //busca registros similares
            $rverificarNNA=$mysqli->query($qverificarNNA);
            $existeReg=$rverificarNNA->num_rows;  //verifica que existan
	}
} catch(Exeption $el){
	echo $e->getMessage();
}


	if(isset($_POST['btnRegistar'])) {  
		echo $registrado;
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
		if($sexo=='M' or $sexo=='Mujer')
		$sexo='MUJER';
		else if($sexo=='H' or $sexo=='Hombre')
		$sexo='HOMBRE';
		if($registrado==0 or $registrado==4){ //sin registros en renapo ni en bd, o con datos incompletos 
			$nombre=$persona['Nombres'];
            $apellido_p=$persona['Apellido1'];
            $apellido_m=$persona['Apellido2'];
            $fechaNac=$persona['FechNac'];
            if(empty($fechaNac))
            	$fechaNac='1900-01-01';
			$lugNac=$persona['EntidadRegistro'];
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
			$rRegistroNna=$mysqli->query($qRegistroNna);//inserto todos los datos en la tabla nna incluyendo la direccion ya que será de aqui donde se tome para el conteo de los informes, esta tabla no debe cambiar en direccion .
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
                	$qRelacion="INSERT INTO relacion_nna_nnareportado (id_nna, id_nna_reportado) values ('$idNnaReg', '$idNna')";
                	$rRelacion=$mysqli->query($qRelacion);
                	if($rRelacion && $rHstDir && $ractDirec){
                		header("Location: verificar_datos_nna.php?idPc=$idPc");
                	} else echo $qRelacion."; ".$qHstDir."; ".$qActDire;
               
            } else echo $qRegistroNna;

		} else if($registrado==3){	//curp validad pero duplicada
			if(empty($idCaso)){ //si el nna no tiene caso registrado solo le da el valor true para que siga 
				$qRelacionCaso="No hay Caso";
				$rRelacionCaso=true;
			} else {
				$qexistRelacion="SELECT id from relacion_pc_caso where id_caso='$idCaso' and id_posible_caso='$idPc'";
				$rexistRelacion=$mysqli->query($qexistRelacion);
				$existeRelacion=$rexistRelacion->num_rows;
				if($existeRelacion==0){
					$qRelacionCaso="INSERT INTO relacion_pc_caso (id_posible_caso, id_caso) values ('$idPc', '$idCaso')";
					$rRelacionCaso=$mysqli->query($qRelacionCaso); 
				} else  {
					$qRelacionCaso="Ya existe relacion PC-Caso";
					$rRelacionCaso=true;
				}
			}
			$qRelacion="INSERT INTO relacion_nna_nnareportado (id_nna, id_nna_reportado) values ('$idNnaReg', '$idNna')";
			$rRelacion=$mysqli->query($qRelacion); //relaciona el nna con el nna reportado			 
            if($rRelacion and $rRelacionCaso){		//si se relaaciono bien crea historicos 
				$regHstDireccion="INSERT INTO `historico_direcciones_nna` (`id_nna`,`id_estado`,`municipio`,`localidad`,`direccion`,`fecha_reg`,`respo_registro`, id_estado_civil)
				Select '$idNnaReg' , $id_estado, $id_mun, $id_loc, '$direccion', '$fecha' , '$idDEPTO', 
				id_estado_civil from nna where id=$idNnaReg";
				$rHstDirecciones=$mysqli->query($regHstDireccion); //resgistrar historico en direcciones
				$qidDireccion="SELECT max(id) from  historico_direcciones_nna where id_nna=$idNnaReg";
				$ridDireccion=$mysqli->query($qidDireccion);
				while ($rwDir=$ridDireccion->fetch_assoc()) {
					$idHisDirec=$rwDir['max(id)'];
                }
				if($rHstDirecciones){
					$qactDirer="UPDATE nna SET id_direccion ='$idHisDirec', 
					id_estado_civil='$est_civil' WHERE id = '$idNnaReg'";
					$ractDirec=$mysqli->query($qactDirer); //actualiza id_direccion
					if($ractDirec){
						if($validada==0){
							$regHstNna="INSERT INTO historico_datos_nna (id_nna, nombre, apellido_p, apellido_m, 
							curp, fecha_nac, sexo, lugar_nac, lugar_registro, fecha_registro, id_respo_registro, 
							indigena, afrodescendiente, migrante) 
							SELECT '$idNnaReg', nombre, apellido_p, apellido_m, curp, '$fechaNacA', sexo, 
							lugar_nac, lugar_reg, '$fecha', '$idDEPTO', indigena, afrodescendiente, migrante
							from nna where id=$idNnaReg";
							$rRegHstNna=$mysqli->query($regHstNna);		
							$regHiRespo="INSERT INTO historico_responsables_nna (id_nna, nombre_persona, 
							parentesco, direccion, telefono, fecha_registro, id_respo_reg, observaciones) 
							SELECT '$idNnaReg', responna, parentesco, direccion_respo, telefono, '$fecha', '$idDEPTO', observaciones
							from nna where id=$idNnaReg";
							$rRegHisRes=$mysqli->query($regHiRespo);		
							if($rRegHstNna and $$rRegHisRes){
								if($DocProbatorio=='1'){
									$actNna="UPDATE nna SET nombre='$nombre', apellido_p='$apellido_p', 
									apellido_m = '$apellido_m', curp= '$curp', fecha_nacimiento= '$fechaNac',
									sexo = '$sexo', id_estado_nacimiento = '$idEdoNac', 
									lugar_nac = '$txtLugarNacimiento', lugar_reg = '$lugarReg', 
									nacionalidad= '$nacionalidad', docProbatorio = '$ADocProbatorio', 
									NumActa= '$numActa', anioReg = '$anioReg', idMunReg= '$idMunReg',
									idEstadoReg = '$idEstadoReg', statusCurp = '$stCurp', 
									validacionRenapo = '1', id_estado_civil='$est_civil', 
									indigena = '$banIndigena', afrodescendiente = '$banAfro', migrante='$banMigra',
									responna='$personaRes', parentesco='$parentesco', 
									direccion_respo='$dirRespo', telefono='$telefono', 
									observaciones='$observa' 
									WHERE id = '$idNnaReg'";
								} else {
									$actNna="UPDATE nna SET nombre='$nombre', apellido_p='$apellido_p',
									apellido_m = '$apellido_m', curp= '$curp',fecha_nacimiento = '$fechaNac',
									sexo = '$sexo', id_estado_nacimiento = '$idEdoNac', 
									lugar_nac = '$txtLugarNacimiento', lugar_reg = '$lugarReg', 
									nacionalidad= '$nacionalidad', docProbatorio = '$ADocProbatorio', 
									statusCurp = '$stCurp',validacionRenapo = '1',indigena = '$banIndigena',
									afrodescendiente = '$banAfro',  migrante='$banMigra', id_estado_civil='$est_civil',
									responna='$personaRes', parentesco='$parentesco', 
									direccion_respo='$dirRespo', telefono='$telefono', 
									observaciones='$observa' WHERE id = '$idNnaReg'";
								}
								$rActNna=$mysqli->query($actNna);
								if($rActNna)
									header("Location: verificar_datos_nna.php?idPc=$idPc");
								else echo $actNna;
							} else echo $regHstNna."; ".$regHiRespo;
						} 
						header("Location: verificar_datos_nna.php?idPc=$idPc");
					} else echo $qactDirer;
				} echo $regHstDireccion;				
			} else echo $qRelacionCaso.";".$qRelacion;
            
            
				/*$qRelacion="INSERT INTO relacion_nna_nnareportado (id_nna, id_nna_reportado) values ('$idNnaReg', '$idNna')";
                $rRelacion=$mysqli->query($qRelacion);
                if($qRelacion){
                	if($hayCaso==0){
                		header("Location: verificar_datos_nna.php?idPc=$idPc&idNna=$idNnaReg");
                	} else {
                		$qRelacionCaso="INSERT INTO relacion_pc_caso (id_posible_caso, id_caso) values ('$idPc', '$idCaso')";
						$rRelacionCaso=$mysqli->query($qRelacionCaso);
						if($rRelacionCaso){
							header("Location: verificar_datos_nna.php?idPc=$idPc&idNna=$idNnaReg&idC=$idCaso");
						} else echo $qRelacionCaso;
                	}
                	} else echo $qRelacion;*/
		} else if($registrado==1){	//registrada en la base de datos solamente (al buscar x curp)
			if(empty($idCaso)){ //sino hay un caso solo le dar el valor true para que siga 
				$qRelacionCaso="No hay Caso";
				$rRelacionCaso=true;
			} else {
				$qexistRelacion="SELECT id from relacion_pc_caso where id_caso='$idCaso' and id_posible_caso='$idPc'";
				$rexistRelacion=$mysqli->query($qexistRelacion);
				$existeRelacion=$rexistRelacion->num_rows;
				if($existeRelacion==0){
					$qRelacionCaso="INSERT INTO relacion_pc_caso (id_posible_caso, id_caso) values ('$idPc', '$idCaso')";
					$rRelacionCaso=$mysqli->query($qRelacionCaso); 
				} else  {
					$qRelacionCaso="Ya existe relacion PC-Caso";
					$rRelacionCaso=true;
				}
			}
			$qRelacion="INSERT INTO relacion_nna_nnareportado (id_nna, id_nna_reportado) values ('$idNnaReg', '$idNna')";
			$rRelacion=$mysqli->query($qRelacion); //relaciona el nna con el nna reportado			 
            if($rRelacion and $rRelacionCaso){		//si se relaaciono bien crea historicos 
				$regHstDireccion="INSERT INTO `historico_direcciones_nna` (`id_nna`,`id_estado`,
				`municipio`,`localidad`,`direccion`,`fecha_reg`,`respo_registro`, id_estado_civil)
				Select '$idNnaReg' , $id_estado, $id_mun, $id_loc, '$direccion', '$fecha' , '$idDEPTO',
				id_estado_civil from nna  where id=$idNnaReg";
				$rHstDirecciones=$mysqli->query($regHstDireccion); //resgistrar historico en direcciones
				$qidDireccion="SELECT max(id) from  historico_direcciones_nna where id_nna=$idNnaReg";
				$ridDireccion=$mysqli->query($qidDireccion);
				while ($rwDir=$ridDireccion->fetch_assoc()) {
					$idHisDirec=$rwDir['max(id)'];
                }
                //historico responsable
                $regHiRespo="INSERT INTO historico_responsables_nna (id_nna, nombre_persona, parentesco,
                direccion, telefono, fecha_registro, id_respo_reg, observaciones) 
					SELECT '$idNnaReg', responna, parentesco, direccion_respo, telefono, '$fecha', 
					'$idDEPTO', observaciones
					from nna where id=$idNnaReg";
					$rRegHisRes=$mysqli->query($regHiRespo);                
				if($rHstDirecciones and $rRegHisRes){
					$qactDirer="UPDATE nna SET id_direccion = '$idHisDirec', responna='$personaRes',
					parentesco='$parentesco', direccion_respo='$dirRespo', telefono='$telefono',
					observaciones='$observa', id_estado_civil='$est_civil' 
					WHERE id = '$idNnaReg'";
					$ractDirec=$mysqli->query($qactDirer); //actualiza direccion y responsable	
					if($ractDirec){
						if($validada==0){
							$regHstNna="INSERT INTO historico_datos_nna (id_nna, nombre, apellido_p, apellido_m, 
							curp, fecha_nac, sexo, lugar_nac, lugar_registro, fecha_registro, id_respo_registro, 
							indigena, afrodescendiente, migrante) 
							SELECT '$idNnaReg', nombre, apellido_p, apellido_m, curp, '$fechaNacA', sexo, 
							lugar_nac, lugar_reg, '$fecha', '$idDEPTO', indigena, afrodescendiente, migrante
							from nna where id=$idNnaReg";
							$rRegHstNna=$mysqli->query($regHstNna);					
							if($rRegHstNna){
									$actNna="UPDATE nna SET nombre='$nombre', apellido_p='$apellido_p', 
									apellido_m='$apellido_m', curp= '$curp', fecha_nacimiento = '$fechaNac',
									sexo='$sexo',id_estado_nacimiento='$idEdoNac',indigena= '$banIndigena',
									afrodescendiente = '$banAfro', migrante='$banMigra'
									WHERE id = '$idNnaReg'";
								
								$rActNna=$mysqli->query($actNna);
								if($rActNna)
									header("Location: verificar_datos_nna.php?idPc=$idPc");
								else echo $actNna;
							}							
						} 
						header("Location: verificar_datos_nna.php?idPc=$idPc");
					} else echo $qactDirer;
				} echo $regHstDireccion,"; ".$regHiRespo;				
			} else echo $qRelacionCaso.";".$qRelacion; 
		} else if($registrado==2){ //solo en renapo
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
			   VALUES ('$folio', '$nombre', '$apellido_p', '$apellido_m', '$curp', '$fechaNac', '$sexo', 
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
				  VALUES ('$folio', '$nombre', '$apellido_p', '$apellido_m', '$curp', '$fechaNac', '$sexo', 
				  '$entiNac', '$txtLugarNacimiento', '$lugarReg', 'P', '$idDEPTO', '0', '$fecha',
				  '$nacionalidad', '$stCurp', '$ADocProbatorio', '1', '$id_estado', '$id_mun', '$id_loc', 
				  '$direccion', '$est_civil', '$banIndigena', '$banAfro', '$banMigra', '$personaRes', '$parentesco', '$dirRespo', 
				  '$observa', '$telefono')"; 
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
			  
				   $qRelacion="INSERT INTO relacion_nna_nnareportado (id_nna, id_nna_reportado) values ('$idNnaReg', '$idNna')";
				   $rRelacion=$mysqli->query($qRelacion);
				   if($rRelacion && $rHstDir && $ractDirec){
					   header("Location: verificar_datos_nna.php?idPc=$idPc");
				   } else echo $qRelacion."; ".$qHstDir."; ".$qActDire;
		   } else echo $qRegistroNna;
	   } 

	}

	
?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Registro NNA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
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
				
					<?php if($registrado==0){ //no se encontro en renapo, ni se encontro la curp en la BD?>
						<h3>¡No se encuentraron los datos!</h3>
						<h5>Es probable que el NNA no se encuentre registrado ante RENAPO. Por favor verifique y complete la información</h5>
						<div class="box">
							<div class="row uniform">
								<div class="4u">Nombre(s)
									<input id="nombre" name="nombre" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$nombre?>" disabled>
								</div>
								<div class="4u">Apellido paterno
									<input id="apellido_p" name="apellido_p" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$apellido_p?>" disabled>
								</div>
								<div class="4u">Apellido materno
									<input id="apellido_m" name="apellido_m" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$apellido_m?>" disabled>
								</div>
							</div>
							<div class="row uniform">
								<div class="3u">CURP
									<input id="curp" name="curp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php if(!empty($curp)) echo $curp?>" disabled>
								</div>
								<div class="2u">Sexo
									<input id="sexo" name="sexo" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$sexo?>" disabled>
								</div>
								<div class="3u">Fecha de nacimiento
									<input id="fechaNac" name="fechaNac" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$fechaNacM?>" disabled>
								</div>
								<div class="4u">Estado de nacimiento
									<input id="entidadNac" name="entidadNac" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$edoNac?>" disabled>
								</div>
							</div>
						</div>
					<?php } else if($registrado==4){ //datos incompletos?>
						<h3>¡Datos incompletos!</h3>
						<h5>Faltó el registro de algunos datos de NNA, por lo que la validación ante renapo no fue posible</h5>
						<div class="box">
							<div class="row uniform">
								<div class="4u">Nombre(s)
									<input id="nombre" name="nombre" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$nombre?>" disabled>
								</div>
								<div class="4u">Apellido paterno
									<input id="apellido_p" name="apellido_p" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$apellido_p?>" disabled>
								</div>
								<div class="4u">Apellido materno
									<input id="apellido_m" name="apellido_m" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$apellido_m?>" disabled>
								</div>
							</div>
							<div class="row uniform">
								<div class="3u">CURP
									<input id="curp" name="curp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php if(!empty($curp)) echo $curp?>" disabled>
								</div>
								<div class="2u">Sexo
									<input id="sexo" name="sexo" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$sexo?>" disabled>
								</div>
								<div class="3u">Fecha de nacimiento
									<input id="fechaNac" name="fechaNac" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$fechaNacM?>"disabled>
								</div>
								<div class="4u">Estado de nacimiento
									<input id="entidadNac" name="entidadNac" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php if(!empty($edoNac)) echo $edoNac?>" disabled>
								</div>
							</div>
						</div>
					<?php } else if($registrado==1) { // Existe un registro similar en la BD pero no esta validado?>
						<h3>¡No se encontraron los datos</h3>
						<h5>Sin embargo, se encontro un registro no validado en el sistema</h5>
						<div class="box">
							<div class="row uniform">
								<div class="4u">Nombre(s)
									<input id="nombre" name="nombre" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$nombre?>" disabled>
								</div>
								<div class="4u">Apellido paterno
									<input id="apellido_p" name="apellido_p" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$apellido_p?>" disabled>
								</div>
								<div class="4u">Apellido materno
									<input id="apellido_m" name="apellido_m" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$apellido_m?>" disabled>
								</div>
							</div>
							<div class="row uniform">
								<div class="3u">CURP
									<input id="curp" name="curp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php if(!empty($curp)) echo $curp?>" disabled>
								</div>
								<div class="2u">Sexo
									<input id="sexo" name="sexo" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$sexo?>" disabled>
								</div>
								<div class="3u">Fecha de nacimiento
									<input id="fechaNac" name="fechaNac" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$fechaNacM?>" disabled>
								</div>
								<div class="4u">Estado de nacimiento
									<input id="entidadNac" name="entidadNac" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$edoNac?>" disabled>
								</div>
							</div>
						</div>
					<?php } else if($registrado==2) { // No esta en la Base de datos, y la consulta x renapo fue existosa ?> 	
						<h3>¡Consulta exitosa! por favor verifique y complete la información</h3>
						<div class="box">
							<div class="row uniform">
								<div class="4u">Nombre(s)
									<input id="nombre" name="nombre" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$nombre?>" disabled>
								</div>
								<div class="4u">Apellido paterno
									<input id="apellido_p" name="apellido_p" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$apellido_p?>" disabled>
								</div>
								<div class="4u">Apellido materno
									<input id="apellido_m" name="apellido_m" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$apellido_m?>" disabled>
								</div>
							</div>
							<div class="row uniform">
								<div class="4u">Sexo
									<input id="sexo" name="sexo" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$sexo?>" disabled>
								</div>
								<div class="4u">Fecha de nacimiento
									<input id="fechaNac" name="fechaNac" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$fechaNacM?>" disabled>
								</div>
								<div class="4u">Estado de nacimiento
									<input id="entidadNac" name="entidadNac" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$edoNac?>" disabled>
								</div>
							</div>
							<div class="row uniform">
								<div class="3u">Nacionalidad
									<input id="txtnacionalidad" name="txtnacionalidad" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$nacionalidad?>" disabled>
								</div>
								<div class="3u">CURP
									<input id="txtCurp" name="txtCurp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$curp?>" disabled>
								</div>
								<div class="6u">Estatus CURP
									<input id="txtStaCurp" name="txtStaCurp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$stCurp?>" disabled>
								</div>
							</div>
							<?php if($DocProbatorio==1){ ?>
								<div class="row uniform">
									<div class="3u">Documento probatorio
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
									<div class="2u">Num. Acta
										<input id="txtNumAct" name="txtNumAct" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$numActa?>" disabled>
									</div>
									<div class="2u">Año de registro
										<input id="txtAnioReg" name="txtAnioReg" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$anioReg?>" disabled>
									</div>
									<div class="5u">Lugar de Registro
										<input id="txtLugReg" name="txtLugReg" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$lugarReg?>" disabled>
									</div>
								</div>
							<?php } else if($DocProbatorio==3) { ?> 
								<div class="row uniform">
									<div class="6u">Documento probatorio
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
									<div class="6u">Num. de registro nacional de extrajero
										<input id="txtNumRegExt" name="txtNumRegExt" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$numRegExtrajero?>" disabled>
									</div>
								</div>
							<?php } else if($DocProbatorio==4) { ?> 
								<div class="row uniform">
									<div class="5u">Documento probatorio
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
									<div class="3u">Año de registro
										<input id="txtAnioReg	" name="txtAnioReg	" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$anioReg	?>" disabled>
									</div>
									<div class="4u">Folio de la carta
										<input id="txtFolCar	" name="txtFolCar	" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$folioCarta ?>" disabled>
									</div>
								</div>
							<?php } else if($DocProbatorio==7) { ?> 
								<div class="row uniform">
									<div class="5u">Documento probatorio
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
									<div class="3u">Año de registro
										<input id="txtAnioReg	" name="txtAnioReg	" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$anioReg	?>" disabled>
									</div>
									<div class="4u">Folio de la carta
										<input id="txtFolCar	" name="txtFolCar	" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$folioCarta ?>" disabled>
									</div>
								</div>
							<?php } else  { ?> 
								<div class="row uniform">
									<div class="6u">Documento probatorio
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
									<div class="6u">Folio de la carta
										<input id="txtFolCar	" name="txtFolCar	" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$folio ?>" disabled>
									</div>
								</div>
							<?php } ?>
						</div>
					<?php } else if($registrado==3) { ?>
						<h3>¡Consulta exitosa! CURP validada por RENAPO</h3>
						<h5>Sin embargo, esta CURP ya esta registrada con el folio <?= $folioNnaReg ?>, se vinculara este reporte al caso, por favor verifique la informacion </h5>
						<div class="box">
							<div class="row uniform">
								<div class="4u">Nombre(s)
									<input id="nombre" name="nombre" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$nombre?>" disabled>
								</div>
								<div class="4u">Apellido paterno
									<input id="apellido_p" name="apellido_p" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$apellido_p?>" disabled>
								</div>
								<div class="4u">Apellido materno
									<input id="apellido_m" name="apellido_m" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$apellido_m?>" disabled>
								</div>
							</div>
							<div class="row uniform">
								<div class="4u">Sexo
									<input id="sexo" name="sexo" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$sexo?>" disabled>
								</div>
								<div class="4u">Fecha de nacimiento
									<input id="fechaNac" name="fechaNac" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$fechaNacM?>" disabled>
								</div>
								<div class="4u">Estado de nacimiento
									<input id="entidadNac" name="entidadNac" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$edoNac?>" disabled>
								</div>
							</div>
							<div class="row uniform">
								<div class="3u">Nacionalidad
									<input id="txtnacionalidad" name="txtnacionalidad" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$nacionalidad?>" disabled>
								</div>
								<div class="3u">CURP
									<input id="txtCurp" name="txtCurp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$curp?>" disabled>
								</div>
								<div class="6u">Estatus CURP
									<input id="txtStaCurp" name="txtStaCurp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$stCurp?>" disabled>
								</div>
							</div>
							<?php if($DocProbatorio==1){ ?>
								<div class="row uniform">
									<div class="3u">Documento probatorio
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
									<div class="2u">Num. Acta
										<input id="txtNumAct" name="txtNumAct" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$numActa?>" disabled>
									</div>
									<div class="2u">Año de registro
										<input id="txtAnioReg" name="txtAnioReg" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$anioReg?>" disabled>
									</div>
									<div class="5u">Lugar de Registro
										<input id="txtLugReg" name="txtLugReg" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$lugarReg?>" disabled>
									</div>
								</div>
							<?php } else if($DocProbatorio==3) { ?> 
								<div class="row uniform">
									<div class="6u">Documento probatorio
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
									<div class="6u">Num. de registro nacional de extrajero
										<input id="txtNumRegExt" name="txtNumRegExt" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$numRegExtrajero?>" disabled>
									</div>
								</div>
							<?php } else if($DocProbatorio==4) { ?> 
								<div class="row uniform">
									<div class="5u">Documento probatorio
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
									<div class="3u">Año de registro
										<input id="txtAnioReg	" name="txtAnioReg	" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$anioReg	?>" disabled>
									</div>
									<div class="4u">Folio de la carta
										<input id="txtFolCar	" name="txtFolCar	" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$folioCarta ?>" disabled>
									</div>
								</div>
							<?php } else if($DocProbatorio==7) { ?> 
								<div class="row uniform">
									<div class="5u">Documento probatorio
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
									<div class="3u">Año de registro
										<input id="txtAnioReg	" name="txtAnioReg	" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$anioReg	?>" disabled>
									</div>
									<div class="4u">Folio de la carta
										<input id="txtFolCar	" name="txtFolCar	" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$folioCarta ?>" disabled>
									</div>
								</div>
							<?php } else  { ?> 
								<div class="row uniform">
									<div class="6u">Documento probatorio
										<input id="txtDoc" name="txtDoc" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ADocProbatorio?>" disabled>
									</div>
									<div class="6u">Folio de la carta
										<input id="txtFolCar	" name="txtFolCar	" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$folio ?>" disabled>
									</div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
					<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						<div class="box">
							<div class="row uniform">
								<?php if(empty($lugarReg)) { ?>
									<div class="2u">Lugar de nacimiento
										<input id="lugarNacimiento" name="lugarNacimiento" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" <?php if($numName==0) { ?> value="" <?php } else { ?>value="<?=$lugaNac ?>" <?php } ?> >
									</div>
									<div class="2u">Lugar de registro
										<input id="LugarRegistro" name="LugarRegistro" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" <?php if($numName==0) { ?> value="" <?php } else { ?>value="<?=$lugaReg ?>" <?php } ?> >
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
									<input id="lugarNacimiento" name="lugarNacimiento" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" <?php if($numName==0) { ?> value="" <?php } else { ?>value="<?=$lugaNac ?>" <?php } ?> >
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
											<?php if($numName==0) {?>
      											<option value="">-- Estado --</option>
                                       		<?php } else { ?> 
                                            	<option value="<?=$idEntidad?>"><?=$Entidad?></option>
                                        	<?php } ?>
												<?php foreach($countries as $c):?>
      												<option value="<?php echo $c->id; ?>"><?php echo $c->estado; ?></option>
												<?php endforeach; ?>
    										</select>
											</div>
								</div>
								<div class="4u">Municipio
									<div class="select-wrapper">
										<select id="state_id" class="form-control" name="state_id" required>
										<?php if($numName==0) {?>
											<option value="">-- MUNICIPIO --</option>
                                       		<?php } else { ?> 
                                            	<option value="<?=$idmunicipio?>"><?=$Municipio?></option>
                                        	<?php } ?>
											  
   										</select>
									</div> 
										
								</div>
								<div class="4u">Localidad
									<div class="select-wrapper">
											<select id="city_id" class="form-control" name="city_id" required>
      											
												  <?php if($numName==0) {?>
													<option value="">-- LOCALIDAD --</option>
                                       		<?php } else { ?> 
                                            	<option value="<?=$idlocalidad?>"><?=$localidad?></option>
                                        	<?php } ?>
   											</select>
											</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="12u">
									<input type="text" name="txtDireccion" id="txtDireccion" tyle="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" <?php if($numName==0) { ?> value="" <?php } else { ?>value="<?=$lugaNac ?>" <?php } ?> placeholder="CALLE Y NÚMERO" >
								</div>
							</div>
						</div>
						<div class="box">
							<h4>Datos del responsable actual del menor</h4>
							<div class="row uniform">
								<div class="4u">Persona Responsable del NNA
									<input id="txtPersona" name="txtPersona" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" <?php if($numName==0) { ?> value="" <?php } else { ?>value="<?=$nombreRespo.' '.$ape1Respo.' ',$ape2Respo?>" <?php } ?> maxlength="100">
								</div>
								<div class="4u">Parentesco
									<input id="txtParentesco" name="txtParentesco" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" <?php if($numName==0) { ?> value="" <?php } else { ?>value="<?=$paren ?>" <?php } ?> maxlength="50">
								</div>
								<div class="4u">Teléfono
									<input id="txtTelefono" name="txtTelefono" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" <?php if($numName==0) { ?> value="" <?php } else { ?>value="<?=$tel ?>" <?php } ?> maxlength="50">
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
						<?php  if($registrado!=3 and $existeReg>0) { ?>
							<div class="box">
								<h3>Se han encontrado estos registros la Base de datos que podrian ser el mismo NNA, por favor, antes de registrar verificalos</h3>
								<div class="row uniform">	
									<div class="12u">
										<table class="alt">
											<thead>
												<tr>
													<th>Folio NNA</th>
													<th>Nombre</th>
													<th>CURP</th>
													<th>Folio Caso</th>
													<th></th>
												</tr>
											</thead>
											<body>
												<?php while ($rowReg=$rverificarNNA->fetch_assoc()) { ?>
													<tr>
														<td><?=$rowReg['folio'] ?></td>	
														<td><?=$rowReg['nombre']." ".$rowReg['apellido_p']." ".$rowReg['apellido_m'] ?></td>
														<td><?=$rowReg['curp'] ?></td>	
														<td><?=$rowReg['folio_c'] ?></td>
														<?php $idNNaBD=$rowReg['id_nna'];
														$idCasoBd=$rowReg['idC']; ?>
														<td><input class="button special fit" name="agregar" type="button" value="Relacionar" onclick="location='relacionarNnaRegistrados.php?idPc=<?=$idPc?>&idNnaR=<?=$idNna?>&idNna=<?=$idNNaBD?>&c=<?=$idCasoBd?>&curp=<?=$curp?>'"></td>
													</tr>						
												<?php }	?>
											</body>
										</table>
									</div>
								</div>

							</div>
						<?php } ?>	
						<div class="row uniform"> 
							<div class="6u">
								<input class="button special fit" name="btnRegistar" type="submit" value="Registrar" >
							</div>
							<div class="6u">
								<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='registro_nna_curp.php?idPc=<?=$idPc?>&idNnaR=<?=$idNna?>'" >
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
					<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; Sistema DIF Hidalgo </p>
					</footer>
				</div> <!--cierrre inner-->
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
