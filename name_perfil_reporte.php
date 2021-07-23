<?php
session_start();
require 'conexion.php';

if(!isset($_SESSION["id"])){
  header("Location: welcome.php");
}
$idDEPTO = $_SESSION['id'];
  date_default_timezone_set('America/Mexico_City');
  $zonahoraria = date_default_timezone_get();
  $num= $_GET['num'];
  $urlReporte ='http://172.16.1.37:8094/swNames/api/consumoNames';
  $jsonReporte = file_get_contents($urlReporte);
  $arrReporte = json_decode($jsonReporte);
  $fec=date("Y-m-d");
  $idReporteName=($arrReporte[$num]->id);
  $qExisteReg="SELECT id from relacion_names where id_name='$idReporteName'";
  $rExisteReg=$mysqli->query($qExisteReg);
  $ExisteReg=$rExisteReg->num_rows;
  $numReporte=($arrReporte[$num]->numeroReporte);


  if(!empty($_POST['registrar']))
	{
    $nombre=($arrReporte[$num]->nombres);
    $apellido_p=($arrReporte[$num]->primerApellido);
    $apellido_m=($arrReporte[$num]->segundoApellido);       
    $fecha_nacimiento=($arrReporte[$num]->fechaNacimiento);
    $anioN=date('Y', strtotime($fecha_nacimiento));
    $anioA=date('Y', strtotime($fec));
    $mesN=date('m', strtotime($fecha_nacimiento));
    $mesA=date('m', strtotime($fec));
    $diaN=date('d', strtotime($fecha_nacimiento));
    $diaA=date('d', strtotime($fec));
    if(($mesN<$mesA) or ($mesN==$mesA and $diaN<=$diaA)){
      $edad=$anioA-$anioN;
    } else {
      $edad=$anioA-$anioN-1; }
    $lugar_nacimiento=($arrReporte[$num]->municipioNacimiento->nombre);
    $lugar_registro=($arrReporte[$num]->municipioRegistroNacimiento->nombre);
    $fechaRegSinnavd=date("Y-m-d H:i:s", time());
    $nombreCaso=$arrReporte[$num]->nombreCaso;
    $estado_coespo=$arrReporte[$num]->estado;
    $numControl=($arrReporte[$num]->numeroControl);
    $numReporte=($arrReporte[$num]->numeroReporte);
    $fechaRegName=($arrReporte[$num]->fechaRegistro);
    $idEntidad=($arrReporte[$num]->domicilioEstadoInfante);
    $entidadRegistroNacimiento=($arrReporte[$num]->entidadRegistroNacimiento->nombre);
    $name=[
      "nombre"=>$nombre,
      "apellido_p"=>$apellido_p,
      "apellido_m"=>$apellido_m,
      "fecha_nacimiento"=>$fecha_nacimiento,
      "edad"=>$edad,
      "lugar_nacimiento"=>$lugar_nacimiento,
      "lugar_registro"=>$lugar_registro,
        "num"=>$num,
        "idReporteName"=> $idReporteName,
        "numControl"=>$numControl,
        "numReporte"=>$numReporte,
        "fechaRegName"=>$fechaRegName,
        "nombreCaso"=> $nombreCaso,
        "estado_coespo"=>$estado_coespo,
        "entidadReg"=>$entidadRegistroNacimiento
      ];
    if(empty($idEntidad)){ //es domicilio extendido
      $idEntidad=($arrReporte[$num]->domicilioExtendidoInfante->domicilioEstado->clave);
      $idmunicipio=($arrReporte[$num]->domicilioExtendidoInfante->domicilioMunicipio->clave);
      $localidad=($arrReporte[$num]->domicilioExtendidoInfante->domicilioLocalidad->clave);
      $codPostal=($arrReporte[$num]->domicilioExtendidoInfante->domicilioCodigoPostal->dCodigo);
      $tipoVialidadA=($arrReporte[$num]->domicilioExtendidoInfante->domicilioTipoVialidad);
      $qidVia="SELECT id FROM cat_asentamientos where vialidad='$tipoVialidadA'";
      $ridVia=$mysqli->query($qidVia);
      if($ridVia->num_rows>0){
      $aidVia=$ridVia->fetch_assoc();
      $tipoVialidad=implode($aidAsen);}
      else $tipoVialidad=5;
      $tipoAsentamientoA=($arrReporte[$num]->domicilioExtendidoInfante->domicilioTipoAsentamientoHumano);
      $qidAsen="SELECT id FROM cat_asentamientos where asentamiento='$tipoAsentamientoA'";
      $ridAsen=$mysqli->query($qidAsen);
      var_dump($ridAsen);
      if($ridAsen->num_rows>0){
      $aidAsen=$ridAsen->fetch_assoc();
      $tipoAsentamiento=implode($aidAsen);}
      else $tipoAsentamiento=7;
      $asentamiento=($arrReporte[$num]->domicilioExtendidoInfante->domicilioNombreAsentamientoHumano);
      $calle=($arrReporte[$num]->domicilioExtendidoInfante->domicilioNombreVialidad);
    } else { //es domicilio corto
      $idEntidad=($arrReporte[$num]->domicilioEstadoInfante->clave);
      $idmunicipio=($arrReporte[$num]->domicilioMunicipioInfante->clave);
      $localidad=($arrReporte[$num]->domicilioLocalidadInfante->clave);
      $codPostal=($arrReporte[$num]->domicilioLocalidadInfante->codigoPostal);
      $tipoVialidad=5;
      $tipoAsentamiento=7;
      $asentamiento=($arrReporte[$num]->domicilioColoniaInfante);
      $calle=($arrReporte[$num]->domicilioCalleInfante);
    }
    if($codPostal=='')
    $codPostal=1;  
    
    $qidLocalidad="SELECT id from localidades where id_mun=$idmunicipio and clave=$localidad";
    $ridLocalidad=$mysqli->query($qidLocalidad);
    $AidLocalidad=$ridLocalidad->fetch_assoc();
    $idLocalidad= implode($AidLocalidad);
        
    
    
    $numExt=($arrReporte[$num]->domicilioNumeroInfante);  
    $persona_reporte=($arrReporte[$num]->responsableRegistra->nombreCompleto);
    $descripcion=($arrReporte[$num]->descripcionSituacion);
    $otrosDatos=($arrReporte[$num]->observaciones);
    $numInt=mysqli_real_escape_string($mysqli,$_POST['txtNumInt']);    
    $reporte= [ 
        "fecha_registro" => "$fechaRegSinnavd", 
        "id_recepcion" => "7", 
        "id_distrito" => "0", 
        "id_maltrato" => "NAMES, ",
        "persona_reporte" => "$persona_reporte", 
        "narracion" => "$descripcion", 
        "clm" => "$idmunicipio", 
        "id_localidad" => "$idLocalidad", 
        "calle" => "$calle", 
        "ubicacion " => " ", 
        "otros_datos" => "$otrosDatos", 
        "entidad" => "$idEntidad", 
        "codigo_postal" => "$codPostal",
        "id_tipo_asentamiento" => "$tipoAsentamiento", 
        "nombre_asentamiento" => "$asentamiento",
        "id_tipo_calle" => "$tipoVialidad", 
        "num_ext" => "$numExt", 
        "num_interior" => "$numInt"
      ];

      $_SESSION['reporte'] = $reporte;
      $_SESSION['name']= $name;
    /*$qInsertarRepote_vd="INSERT INTO `reportes_vd`(`folio`, `fecha_registro`, `id_recepcion`, `persona_reporte`, 
    `narracion`, `codigo_postal`, `entidad`, `clm`, `id_localidad`, `id_tipo_asentamiento`, `nombre_asentamiento`,
    `id_tipo_calle`, `calle`, `num_ext`, `num_interior`, `otros_datos`, `respo_reg`, ubicacion)
     VALUES ('$numReporte','$fechaRegSinnavd', '7','$persona_reporte', '$descripcion', '$codPostal', '$idEntidad', 
     '$idmunicipio', '$idLocalidad', '7', '$asentamiento', '5', '$calle', '$numExt', '$numInt', '$otrosDatos',
     '$idDEPTO', ' ')";
    $rInsertarReporte_vd=$mysqli->query($qInsertarRepote_vd);
    if($rInsertarReporte_vd){
    $qIdReporte="SELECT id from reportes_vd where '$numReporte'=folio";
    $rIdReporte=$mysqli->query($qIdReporte);
    $AidReporte=$rIdReporte->fetch_assoc();
	  $idReporte= implode($AidReporte);
    $InsertarRelacion="INSERT INTO `relacion_names`(`id_name`, `num_control_coespo`, `numero_reporte_coespo`,
     `id_reporte_sinnavd`, `fecha_registro`, `id_persona_reg`, `fecha_registro_si_names`, nombre_caso_coespo, estado_coespo) 
     VALUES ('$idReporteName', '$numControl', '$numReporte', '$idReporte', '$fechaRegSinnavd', '$idDEPTO', '$fechaRegName', '$nombreCaso', '$estado_coespo')";
    $rInsertarRelacion=$mysqli->query($InsertarRelacion);
    if($rInsertarRelacion)*/
    header("Location: busqueda_reportes_similares.php");
   /* else echo "Error: ".$InsertarRelacion;
    } else echo "Error: ".$qInsertarRepote_vd;*/
  }


?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Reporte NAMES</title>
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
          <?php 
            $nombreCasss=$arrReporte[$num]->nombreCaso;
             if(empty($nombreCasss)) {
              echo "Este reporte aun no ha sido aceptado por el SINAMES";}
              else {
          if($ExisteReg>0){ ?>
          <h2>¡Este reporte ya esta registrado en en SINNAVD!</h2> <?php } ?>
					<h2>Nombre del caso: <?php print_r($arrReporte[$num]->nombreCaso); } ?></h2>
					<div class="box alt" align="center">
          <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
            <div class="box">
					  	<div class="row uniform">
                            <div class="1u 12u$(xsmall)">
                             <label for="txtId">ID:</label>
                            <input type="text" id="txtId" name="txtId" value="<?php print_r($arrReporte[$num]->id); ?>" disabled >
                            </div>
                            <div class="1u 12u$(xsmall)">
                             <label for="txtVersion">Versión:</label>
                             <input type="text" id="txtVersion" name="txtVersion" value="<?php print_r($arrReporte[$num]->version); ?>" disabled>
                            </div>
                            <div class="3u 12u$(xsmall)">
                             <label for="txtNumControl">Número de control:</label>
                             <input type="text" id="txtNumControl" name="txtNumControl" value="<?php print_r($arrReporte[$num]->numeroControl); ?>" disabled>
                            </div>
                            <div class="5u 12u$(xsmall)">
                             <label for="txtNumReporte">Número de reporte:</label>
                             <input type="text" id="txtNumReporte" name="txtNumReporte" value="<?php print_r($arrReporte[$num]->numeroReporte); ?>" disabled>
                            </div>
                            <div class="2u 12u$(xsmall)">
                             <label for="txtFechaReg">Fecha de registro:</label>
                            <input type="text" id="txtFechaReg" name="txtFechaReg" value="<?php print_r($arrReporte[$num]->fechaRegistro); ?>" disabled >
                            </div>
                        </div>
                        <?php 
                        $domSimple=$arrReporte[$num]->domicilioCalleInfante;
                        if(empty($domSimple)){ ?>
                          <div class="row uniform">
                            <div class="2u 12u$(xsmall)">
                             <label for="txtEntidad">Entidad:</label>
                             <input type="text" id="txtEntidad" name="txtEntidad" value=" <?php print_r($arrReporte[$num]->domicilioExtendidoInfante->domicilioEstado->nombre);  ?>" disabled> 
                            </div>
                            <div class="4u 12u$(xsmall)">
                             <label for="txtMunicipio">Municipio:</label>
                             <input type="text" id="txtMunicipio" name="txtMunicipio" value="<?php print_r($arrReporte[$num]->domicilioExtendidoInfante->domicilioMunicipio->nombre);  ?>" disabled >  
                            </div>
                            <div class="4u 12u$(xsmall)">
                             <label for="txtLocalidad">Localidad:</label>
                             <input type="text" id="txtLocalidad" name="txtLocalidad" value="<?php print_r($arrReporte[$num]->domicilioExtendidoInfante->domicilioLocalidad->nombre);  ?>" disabled >  
                            </div>
                            <div class="2u 12u$(xsmall)">
                             <label for="txtCP">CP:</label>
                             <input type="text" id="txtCP" name="txtCP" value="<?php print_r($arrReporte[$num]->domicilioExtendidoInfante->domicilioCodigoPostal->dCodigo);  ?>" disabled >   
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="4u 12u$(xsmall)">
                             <label for="txtAsentamiento">Asentamiento:</label>
                             <input type="text" id="txtAsentamiento" name="txtAsentamiento" value=" <?php print_r($arrReporte[$num]->domicilioExtendidoInfante->domicilioNombreAsentamientoHumano);  ?>" disabled > 
                            </div>
                            <div class="4u 12u$(xsmall)">
                             <label for="txtCalle">Calle:</label>
                             <input type="text" id="txtCalle" name="txtCalle" value="<?php print_r($arrReporte[$num]->domicilioExtendidoInfante->domicilioNombreVialidad);  ?>" disabled>  
                            </div>
                            <div class="2u 12u$(xsmall)">
                             <label for="txtNumExt">Número exterior:</label>
                             <input type="text" id="txtNumExt" name="txtNumExt" value="<?php print_r($arrReporte[$num]->domicilioExtendidoInfante->domicilioNumeroExterior1);  ?>" disabled>  
                            </div>
                            <div class="2u 12u$(xsmall)">
                             <label for="txtNumInt">Número interior:</label>
                             <input type="text" id="txtNumInt" name="txtNumInt" value="<?php print_r($arrReporte[$num]->domicilioExtendidoInfante->domicilioNumeroInterior);  ?>" >   
                            </div>
                          </div>
                       <?php } else { ?>
                        <div class="row uniform">
                            <div class="2u 12u$(xsmall)">
                             <label for="txtEntidad">Entidad:</label>
                             <input type="text" id="txtEntidad" name="txtEntidad" value=" <?php print_r($arrReporte[$num]->domicilioEstadoInfante->nombre);  ?>" disabled> 
                            </div>
                            <div class="4u 12u$(xsmall)">
                             <label for="txtMunicipio">Municipio:</label>
                             <input type="text" id="txtMunicipio" name="txtMunicipio" value="<?php print_r($arrReporte[$num]->domicilioMunicipioInfante->nombre);  ?>" disabled >  
                            </div>
                            <div class="4u 12u$(xsmall)">
                             <label for="txtLocalidad">Localidad:</label>
                             <input type="text" id="txtLocalidad" name="txtLocalidad" value="<?php print_r($arrReporte[$num]->domicilioLocalidadInfante->nombre);  ?>" disabled >  
                            </div>
                            <div class="2u 12u$(xsmall)">
                             <label for="txtCP">CP:</label>
                             <input type="text" id="txtCP" name="txtCP" value="<?php print_r($arrReporte[$num]->domicilioLocalidadInfante->codigoPostal);  ?>" disabled >   
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="4u 12u$(xsmall)">
                             <label for="txtAsentamiento">Asentamiento:</label>
                             <input type="text" id="txtAsentamiento" name="txtAsentamiento" value=" <?php print_r($arrReporte[$num]->domicilioColoniaInfante);  ?>" disabled > 
                            </div>
                            <div class="4u 12u$(xsmall)">
                             <label for="txtCalle">Calle:</label>
                             <input type="text" id="txtCalle" name="txtCalle" value="<?php print_r($arrReporte[$num]->domicilioCalleInfante);  ?>" disabled>  
                            </div>
                            <div class="2u 12u$(xsmall)">
                             <label for="txtNumExt">Número exterior:</label>
                             <input type="text" id="txtNumExt" name="txtNumExt" value="<?php print_r($arrReporte[$num]->domicilioNumeroInfante);  ?>" disabled>  
                            </div>
                            <div class="2u 12u$(xsmall)">
                             <label for="txtNumInt">Número interior:</label>
                             <input type="text" id="txtNumInt" name="txtNumInt" value="" >   
                            </div>
                          </div>
                        <?php } ?>
                          <br>
                          <h3>Descripción de la situación</h3>
                          <div class="row uniform">   
                          <div class="6u 12u$(xsmall)">
                            <label for="txtNarracion">Descripción de la situacion:</label>
                            <textarea name="txtNarracion" id="txtNarracion" rows="6" cols="20" maxlength="1000" disabled style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" ><?php print_r($arrReporte[$num]->descripcionSituacion);  ?></textarea>
                          </div>
                          <div class="6u 12u$(xsmall)">
                             <label for="txtDatosRelevates">Otros datos u observaciones relevantes:</label>
                             <textarea name="txtDatosRelevates" id="txtDatosRelevates" rows="6" cols="20" disabled maxlength="1000" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" ><?php print_r($arrReporte[$num]->observaciones);  ?></textarea>
                          </div>
                          </div><br>
                          <h3>Persona que reporta</h3>
                          <div class="row uniform">   
                          <div class="3u 12u$(xsmall)">
                            <label for="txtNombrePersonaRegistro">Nombre:</label>
                            <input type="text" id="txtNombrePersonaRegistro" name="txtNombrePersonaRegistro" value="<?php print_r($arrReporte[$num]->responsableRegistra->nombreCompleto); ?>" disabled>  
                          </div>
                          <div class="3u 12u$(xsmall)">
                             <label for="txtCorreoPersonaRegistro">Correo electrónico:</label>
                             <input type="text" id="txtCorreoPersonaRegistro"  name="txtCorreoPersonaRegistro" value="<?php print_r($arrReporte[$num]->responsableRegistra->correoElectronico);  ?>" disabled>  
                          </div>
                          <div class="2u 12u$(xsmall)">
                             <label for="txtTelPersonaRegistro">Telefóno:</label>
                             <input type="text" id="txtTelPersonaRegistro"   name="txtTelPersonaRegistro" value="<?php print_r($arrReporte[$num]->responsableRegistra->telefono);  ?>" disabled>  
                          </div>   
                          <div class="4u 12u$(xsmall)">
                             <label for="txtCargoPersRegistro">Cargo:</label>
                             <input type="text" id="txtCargoPersRegistro"  name="txtCargoPersRegistro" value="<?php print_r($arrReporte[$num]->responsableRegistra->cargo);  ?>" disabled>  
                          </div> 
                          </div>
                        <br>
                        <?php if(empty($nombreCasss)) { }
                        else { ?>
                        <input class="button special fit" name="registrar" type="submit" value="Añadir reporte al SINNAVD" <?php if($ExisteReg>0) {  ?> disabled="true" <?php } ?> >
                        <?php } ?>
                      </div>
                      
                     </form>
                        <div class="box">
                        <div class="row uniform">
                          <div class="6u 12u$(xsmall)">
                            <label for="txtNombreCaso">Nombre del caso:</label>
                            <input type="text" id="txtNombreCaso" disabled name="txtNombreCaso" value="<?php print_r($arrReporte[$num]->nombreCaso);  ?>" > 
                          </div>
                          <div class="6u 12u$(xsmall)">
                             <label for="txtInstRegistro">Institución que registra:</label>
                             <input type="text" id="txtInstRegistro" disabled name="txtInstRegistro" value="<?php print_r($arrReporte[$num]->institucionRegistra->nombre);  ?>" >  
                          </div>
                        </div>
                        </div>
                      <div class="box">
                        <h3>Datos de la NAME</h3>
                        <div class="row uniform">
                        <div class="8u 12u$(xsmall)">
                          <div class="box"><h4>Lugar de nacimiento</h4>
                          <div class="row uniform">
                          <div class="4u 12u$(xsmall)">
                             <label for="txtEntidadNacimiento">Entidad::</label>
                             <input type="text" id="txtEntidadNacimiento" disabled name="txtEntidadNacimiento" value="<?php print_r($arrReporte[$num]->entidadNacimiento->nombre);  ?>" >  
                          </div>
                          <div class="4u 12u$(xsmall)">
                             <label for="txtMunicipioNacimiento">Municipio:</label>
                             <input type="text" id="txtMunicipioNacimiento" disabled name="txtMunicipioNacimiento" value="<?php print_r($arrReporte[$num]->municipioNacimiento->nombre);  ?>" >  
                          </div>   
                          <div class="4u 12u$(xsmall)">
                             <label for="txtLocalidadNacimiento">Localidad:</label>
                             <input type="text" id="txtLocalidadNacimiento" disabled name="txtLocalidadNacimiento" value="<?php print_r($arrReporte[$num]->localidadNacimiento->nombre);  ?>" >  
                          </div>
                          </div>  
                          </div>   
                          </div>
                        <div class="4u 12u$(xsmall)">
                          <div class="box">  <h4>Lugar de registro</h4>
                          <div class="row uniform">
                          <div class="6u 12u$(xsmall)">
                             <label for="txtEntidadNacimiento">Entidad:</label>
                             <input type="text" id="txtEntidadNacimiento" disabled name="txtEntidadNacimiento" value="<?php print_r($arrReporte[$num]->entidadRegistroNacimiento->nombre);  ?>" >  
                          </div>
                          <div class="6u 12u$(xsmall)">
                             <label for="txtMunicipioNacimiento">Municipio:</label>
                             <input type="text" id="txtMunicipioNacimiento" disabled name="txtMunicipioNacimiento" value="<?php print_r($arrReporte[$num]->municipioRegistroNacimiento->nombre);  ?>" >  
                          </div> <!--col 6 -->  
                          </div>     <!--row -->    
                          </div>  <!--box lug registro -->  
                          </div>      <!--col 4 -->            
                        </div>  <!--row -->  
                        <div class="row uniform">
                          <div class="6u 12u$(xsmall)">
                          <div class="box">  <h4>Datos personales</h4>
                          <div class="row uniform">
                          <div class="12u 12u$(xsmall)">
                             <label for="txtNombreName">Nombre:</label>
                             <input type="text" id="txtNombreName" disabled name="txtNombreName" value="<?php print_r($arrReporte[$num]->nombres); echo " "; print_r($arrReporte[$num]->primerApellido); echo " "; print_r($arrReporte[$num]->segundoApellido); ?>" >  
                          </div>
                          </div>     <!--row --> 
                          <div class="row uniform">
                          <div class="4u 12u$(xsmall)">
                             <label for="txtFechaNacName">Fecha de nacimiento:</label>
                             <input type="text" id="txtFechaNacName" disabled name="txtFechaNacName" value="<?php print_r($arrReporte[$num]->fechaNacimiento); ?>" >  
                          </div>
                          <div class="8u 12u$(xsmall)">
                             <label for="txtCurpName">CURP:</label>
                             <input type="text" id="txtCurpName" disabled name="txtCurpName" value="<?php print_r($arrReporte[$num]->curp); ?>" >  
                          </div>
                          </div>     <!--row -->   
                          </div>  <!--box lug registro -->  
                          </div>
                        </div> <!--row -->  
                      </div> <!--box datos name -->  
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
