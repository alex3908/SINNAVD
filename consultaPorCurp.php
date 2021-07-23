<?php 
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
    }
    $idDEPTO = $_SESSION['id'];
    $curp=($_GET['c']);
    $idNna=($_GET['idNna']);
    $idCaso=($_GET['idCaso']);
    $cliente= new SoapClient('http://172.16.1.42/sistemas/wssieb/WebServiceBeneficiarios.asmx?wsdl'); //direccion del ws
	$param=array('CURP'=>$curp); //parametro que recibirá
	$response= $cliente->ConsultaPorCurp($param); //recibe el objeto
	$array = json_decode(json_encode($response), True);  //convierte el objeto devuelto a cadena
	$estatus=$array['ConsultaPorCurpResult']['StatusOper'];
	if($estatus=='EXITOSO')
	{
		$nombre=$array['ConsultaPorCurpResult']['Nombres'];
		$apellido_p=$array['ConsultaPorCurpResult']['Apellido1'];
		$apellido_m=$array['ConsultaPorCurpResult']['Apellido2'];
		if($array['ConsultaPorCurpResult']['Sexo']=='H')
			$sexo=1;
		else
			$sexo=2;
		$fechaNac=$array['ConsultaPorCurpResult']['FechNac'];
		$date = new DateTime($fechaNac);
		if($array['ConsultaPorCurpResult']['Nacionalidad']=='MEX')
			$nacionalidad='MEXICANA';
		else 
			$nacionalidad='EXTRANJERO';
		$lugNac=$array['ConsultaPorCurpResult']['EntidadFederativa'];
		$qEstadoNac="SELECT estadosMayus as edoNac FROM estados where clave='$lugNac'"; 
		$rEstadoNac=$mysqli->query($qEstadoNac);
		$arrEstadoNac=$rEstadoNac->fetch_assoc();
		$estadoNac=strtoupper(implode($arrEstadoNac));
		$DocProbatorio=$array['ConsultaPorCurpResult']['DocProbatorio'];
		$anioReg=$array['ConsultaPorCurpResult']['AnioReg'];
		$numActa=$array['ConsultaPorCurpResult']['NumActa'];
		$idEstadoReg=$array['ConsultaPorCurpResult']['EntidadRegistro'];
		$idMunReg=$array['ConsultaPorCurpResult']['MunicipioRegistro'];
		if($idEstadoReg!='13'){
			$qEstadoReg="SELECT estadosMayus from estados where id='$idEstadoReg'";
			$rEstadoReg=$mysqli->query($qEstadoReg);
			$arrEstadoReg=$rEstadoReg->fetch_assoc();
			$lugarReg=implode($arrEstadoReg);
		} else {
			$qMunReg="SELECT municipioMayus from municipios where id='$idMunReg'";
			$rMunReg=$mysqli->query($qMunReg);
			$arrMunReg=$rMunReg->fetch_assoc();
			$MunReg=implode($arrMunReg);
			$lugarReg=$MunReg.", HIDALGO";	
		}

		
	} else 
	 	echo "<script>
                alert('La CURP no se encuentra en la base de datos, por favor verifique que los datos sean correctos');   
                window.location= 'registro_nna_curp.php?idCaso=$idCaso&idNna=$idNna'
            </script>";

    if(!empty($_POST)){
    	$qverificarCurp="SELECT id, folio, nombre, apellido_p, apellido_m, curp, fecha_nac,  from nna where curp='$curp'";
        $rverificarCurp=$mysqli->query($qverificarCurp);
        $existeCurp=$rverificarCurp->num_rows;  //verifica que esa curp no este registrada ya
        if($existeCurp>0)  {
        	while ($rowNnaRegistrado=$rverificarCurp->fetch_assoc()) {  //si ya esta registrada toma el id y folio de la tabla nna
        		$idNnaReg=$rowNnaRegistrado['id'];
                $folioNnaReg=$rowNnaRegistrado['folio'];
            }
            $qrelacionNna="INSERT INTO relacion_nna_nnareportado (id_nna_reportado, id_nna) values ('$idNna','$idNnaReg')";
            $rrelacionNna=$mysqli->query($qrelacionNna); //relaciona al nna ya registrado con el nna reportado recientemente 
            echo "<script>
                alert('El(la) NNA ya se se encuentra registrado(a) con el folio $folioNnaReg');   
                window.location= 'verificar_datos_nna.php?idPc=$idPosibleCaso&idCaso=$idCaso'
                </script>"; //manda un mensaje de que ya se encuentra registrado y dice cual es el folio de su registro al mismo tiempo redirecciona a verificar los nna e el registro   
            } else { //si el curp no se a registrado inserta los datos en la tabla nna 
            	$sqlNino = "INSERT INTO nna (folio, nombre, apellido_p, apellido_m, curp, fecha_nac, sexo,  lugar_nac, lugar_reg, estado, respo_reg, nna_ex, fecha_reg) 
                	VALUES ('$folio', '$nombre', '$ap_paterno', '$ap_materno', '$curp', '$fecha_nac', '$sexo', '$lug_nac', '$lug_reg', 'P', '$idDEPTO', '0', '$fecha')";	 
                $resultNino=$mysqli->query($sqlNino);
                $contador="UPDATE nfolio set terminacion='$ter2' where id=1";
			    $econt=$mysqli->query($contador);   
                if ($resultNino){
                    $qidNna="SELECT max(id) from nna where folio='$folio'"; //selecciona el id de la tabla nna que se ha registrado
                    $ridNna=$mysqli->query($qidNna);
                    while ($rowidnna=$ridNna->fetch_assoc()) {
                        $idNnaReg=$rowidnna['max(id)'];
                    }
                    $qrelacion="INSERT INTO nna_caso (id_caso, id_nna, fecha_reg) VALUES ('$idCaso', '$idNnaReg', '$fecha')";
                    $rrelacion=$mysqli->query($qrelacion); //relaciona el caso con el nna
                    $qrelacionNna="INSERT INTO relacion_nna_nnareportado (id_nna_reportado, id_nna) values ($idNna, $idNnaReg)";
                    $rrelacionNna=$mysqli->query($qrelacionNna); //relaciona el registro recien del nna con el del reportado
                    if($rrelacion and $rrelacionNna)
                        header("Location: registro_nna.php?idCaso=$idCaso&idNna=$idNnaReg");
                    else 
                        echo "Error: ".$qrelacion."-".$rrelacionNna;
                }
                else
                echo "Error al Registrar: ".$sqlNino;
            }
    }


?>
<!DOCTYPE HTML>
<html lang="es-ES">
	<head>
		<title>Datos del NNA</title>
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
					<br> <br> 
					<div class="box alt" align="center">
					    <div class="row 10% uniform">
					        <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					        <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
				        </div>
			        </div>
			        <h3>¡Consulta exitosa!</h3>
			        <h4>Verifique que los datos correspondan al NNA</h4>
			        <div class="box">
			        	<div class="row uniform">
			        		<div class="5u 12u$(xsmall)">
			        			<label for="txtNombre">Nombre Completo</label>
			        			<input type="text" name="txtNombre" value="<?php echo $nombre.' '.$apellido_p.' '.$apellido_m?>" disabled>
			        		</div>
			        		<div class="2u 12u$(xsmall">
			        			<label for="txtSexo">Sexo</label>
			        			<input type="text" name="txtSexo" value="<?php if($sexo==1 ) echo "HOMBRE"; else echo "MUJER"; ?>" disabled>
			        		</div>
			        		<div class="3u 12u$(xsmall">
			        			<label for="txtFechaNac">Fecha de nacimiento</label>
			        			<input type="text" name="txtFechaNac" value="<?php echo $date->format('m/d/Y'); ?>" disabled>
			        		</div>
			        		<div class="2u 12u$(xsmall">
			        			<label for="txtNacionalidad">Nacionalidad</label>
			        			<input type="text" name="txtNacionalidad" value="<?php echo $nacionalidad; ?>" disabled>
			        		</div>
			        	</div>
			        	<div class="row uniform">			        		
			        		<div class="2u 12$(xsmall)">
			        			<label for="txtEdoNac">Entidad de nacimiento</label>
			        			<input type="text" name="txtEdoNac" value="<?php echo $estadoNac; ?>">
			        		</div>
			        		<div class="2u 12$(xsmall)">
			        			<label for="txtDocPro">Doc. probatorio</label>
			        			<input type="text" name="txtDocPro" value="<?php echo $DocProbatorio; ?>">
			        		</div>
			        		<div class="2u 12$(xsmall)">
			        			<label for="txtAnioReg">Año de registro</label>
			        			<input type="text" name="txtAnioReg" value="<?php echo $anioReg; ?>">
			        		</div>
			        		<div class="2u 12$(xsmall)">
			        			<label for="txtNumActa">Número de acta</label>
			        			<input type="text" name="txtNumActa" value="<?php echo $numActa; ?>">
			        		</div>
			        		<div class="4u 12$(xsmall)">
			        			<label for="txtLugReg">Lugar de registro</label>
			        			<input type="text" name="txtLugReg" value="<?php echo $lugarReg; ?>">
			        		</div>
			        	</div>
			        </div>
			        <div class="row uniform">
			        	<div class="6u 12$(xsmall)">
			        		<input class="button fit" type="button" name="regresar" value="regresar" onclick="location='registro_nna_curp.php?idCaso=<?php echo $idCaso; ?>&idNna=<?php echo $idNna;?>'" >
			        	</div>
			        	<div class="6u 12$(xsmall)">
			        		<input class="button special fit" type="submit" name="registrar" value="Registrar" >
			        	</div>
			        </div>
			    </div>
			</div>
		</div>
	</body>


</html>
