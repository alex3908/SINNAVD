<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
    }
    $idDEPTO = $_SESSION['id'];
    $idNna=($_GET['idNna']);
    $idCaso=($_GET['idCaso']);
    date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	$qNna="SELECT curp, nombre, apellido_p, apellido_m, nna.sexo as sex, sexo.sexo, fecha_nac, lugar_nac, lugar_reg 
    FROM nna inner join sexo on nna.sexo=sexo.id where nna.id='$idNna'"; //toma los daos del nna de la taba nna
    $rNna=$mysqli->query($qNna);
    while ($row=$rNna->fetch_assoc()) {
        $curp=$row['nombre'];
        $nombre=$row['nombre'];
        $apellido_p=$row['apellido_p'];
		$apellido_m=$row['apellido_m'];
		$sexId=$row['sex'];
		$sexo=$row['sexo'];
        $fecha_nac=$row['fecha_nac'];
        $lugar_nac=$row['lugar_nac'];
        $lugar_reg=$row['lugar_reg'];
    }

    $qdireccion="SELECT r.id as nnaRepId, e.id as edoId, e.estado, m.id as munId, m.municipio, l.id as locId, 
    l.localidad, r.calle, r.num_ext, asentamiento, nombre_asentamiento, vialidad, num_control_coespo
    FROM reportes_vd r inner join estados e on entidad=e.id
    inner join municipios m on m.id=clm
    inner join localidades l on l.id=id_localidad
    inner join cat_vialidades v on v.id=r.id_tipo_calle
    inner join cat_asentamientos a on r.id_tipo_asentamiento=a.id
    inner join posible_caso pc on pc.id=r.id_posible_caso
    inner join nna_reportados nr on nr.id_posible_caso=pc.id
    inner join relacion_nna_nnareportado rnn on rnn.id_nna_reportado=nr.id 
    left join relacion_names nam on rnn.id_nna_reportado=nam.id_nna_reportado
    where rnn.id_nna='$idNna' order by r.id desc limit 1";
    $rdireccion=$mysqli->query($qdireccion);
    while ($rowDir=$rdireccion->fetch_assoc()) {
        $idNnaRep=$rowDir['nnaRepId'];
        $idEdo=$rowDir['edoId'];
        $estado=$rowDir['estado'];
        $idMun=$rowDir['munId'];
        $municipio=$rowDir['municipio'];
        $idLoc=$rowDir['locId'];
        $loc=$rowDir['localidad'];
        $asentamiento=$rowDir['asentamiento'];
        $nombre_asentamiento=$rowDir['nombre_asentamiento'];
        $vialidad=$rowDir['vialidad'];
        $calle=$rowDir['calle'];
        $num_ext=$rowDir['num_ext'];
        $name=$rowDir['num_control_coespo'];
    }

    if(empty($name)){ //si no hay registro en names entonces no hay registro 
        $responsable=null;
        $idParentesco=null;
        $telefono=null;
        $correo=null;
    }
    else{ //si hay registro toma los datos del ws
        $urlReportes ='http://172.16.1.37:8094/swNames/api/consumoNames';
        $jsonReportes = file_get_contents($urlReportes); 
        $arrReportes = json_decode($jsonReportes);
        $numReportes= count($arrReportes);
        for($i=0; $i<$numReportes;$i++){
            if($arrReportes[$i]->numeroControl==$name){
                $num=$i; //identifica el numero de registro al q corresponde el registro
                $i=$numReportes;
            }
        }
        if(empty($arrReportes[$num]->nombresMadre)){ //si no hay registros de madre
            if(empty($arrReportes[$num]->nombresPadre)) {  //verifica si no registro del padre
                $responsable=$arrReportes[$num]->nombresOtro." ".$arrReportes[$num]->primerApellidoOtro." ".$arrReportes[$num]->segundoApellidoOtro; //si no hay de ninguno tomo los datos de otro
                $parentesco=$arrReportes[$num]->parentesco;
                $qBusqParentesco="SELECT id FROM cat_parentesco where parentesco='$parentesco'";
                $rBusqParentesco=$mysqli->query($qBusqParentesco);
                $vecParentesco=$rBusqParentesco->fetch_assoc();
                $idParentesco=implode($vecParentesco);
            } else {  //hay datos del padre entonces los toma
                $responsable=$arrReportes[$num]->nombresPadre." ".$arrReportes[$num]->primerApellidoPadre." ".$arrReportes[$num]->segundoApellidoPadre;
                $idParentesco=2;
                $parentesco="Papá";
            }

        } else { // hay datos de la madre asi que los toma 
            $responsable=$arrReportes[$num]->nombresMadre." ".$arrReportes[$num]->primerApellidoMadre." ".$arrReportes[$num]->segundoApellidoMadre; 
            $idParentesco=1;
            $parentesco="Mamá";
        }   
        $telefono=$arrReportes[$num]->telefono;
        $correo=$arrReportes[$num]->correoElectronico;
    }   
    $query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($rowEdo=$equery->fetch_object())	{ $countries[]=$rowEdo; }

    $qparentescos="SELECT id, parentesco from cat_parentesco";
    $rparentesco=$mysqli->query($qparentescos);

    if(!empty($_POST))
	{	
        $fecha= date("Y-m-d H:i:s", time());
        $id_estado = $_POST['country_id'];
		$id_mun = $_POST['state_id'];
        $id_loc = $_POST['city_id'];
        $direccion = mysqli_real_escape_string($mysqli,$_POST['direccion']);
        $responna = mysqli_real_escape_string($mysqli,$_POST['responna']);
		$parentesco = mysqli_real_escape_string($mysqli,$_POST['parentesco']);		
        $telefono = mysqli_real_escape_string($mysqli,$_POST['telefono']);	
        $correo= mysqli_real_escape_string($mysqli, $_POST['correo']);
		        
	/*	$qnna = "INSERT INTO historico_direcciones_nna (id_nna, id_estado, id_municipio, id_localidad, 
        direccion, ver, fecha_reg, respo_registro ) 
            VALUES ('$idNna', '$id_estado', '$id_mun', '$id_loc', '$direccion', '1', '$fecha', 
            '$idDEPTO')";	
        $rnna=$mysqli->query($qnna);
        $qDireRespo = "INSERT INTO historico_direcciones_responsables (id_responsable, id_estado, id_municipio, id_localidad, 
        direccion, telefono, correo,  fecha_reg, respo_registro ) 
            VALUES ('$idNna', '$id_estado', '$id_mun', '$id_loc', '$direccion', '1', '$fecha', 
            '$idDEPTO')";	
        $rnna=$mysqli->query($qnna);		
		if($rnna)
			$qrespo="INSERT INTO responsable_nna (nombre, id_respo_reg, fecha_actualizacion)"
			else
            $error = "Error al Registrar: ".$sqlNino;*/
       
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Registrar NNA</title>
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
                    <div class="row uniform">
                        <div class="3u 12u$(xsmall)">CURP:
                            <input id="curp" name="curp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
                        </div>
                        <div class="3u 12u$(xsmall)">Nombre (s):
                            <input id="nombre" name="nombre" type="text" value="<?php echo $nombre?>" disabled>
                        </div>
                        <div class="2u 12u$(xsmall)">Apellido paterno:
                            <input id="apellido_p" name="apellido_p" type="text" value="<?php echo $apellido_p?>" disabled>
                        </div>
                        <div class="2u 12u$(xsmall)">Apellido materno:
                            <input id="apellido_m" name="apellido_m" type="text" value="<?php echo $apellido_m?>" disabled>
                        </div>
                        <div class="2u 12u$(xsmall)">Sexo
                            <input id="sexo" name="sexo" type="text" value="<?php echo $sexo?>" disabled>
                        </div>
                    </div>
                    <div class="row uniform">
                        <div class="4u 12u$(xsmall)">Fecha de nacimiento:
                            <input id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $fecha_nac?>" type="date"  disabled>
                        </div>
                        <div class="4u 12u$(xsmall)">Lugar de nacimietno:
                            <input id="lugar_nacimiento" name="lugar_nacimiento"  value="<?php echo $lugar_nac?>" type="text" disabled>
                        </div>
                        <div class="4u 12u$(xsmall)">Lugar de registro:
                            <input id="lugar_registro" name="lugar_registro" value="<?php echo $lugar_reg?>" type="text" disabled>
                        </div>
                    </div><br>
                    <h5>Por favor, verifique y complete la siguiente información</h5>
                    <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >                                 
                                                    
                        <div class="box">
                            <div class="row">
                                <div class="12u 12u(xsmall)">
                                    Dirección actual: 
							 </div>
                            </div><br>
                            <div class="row">
                                <div class="4u 12u(xsmall)">
                                    <label for="country_id">Estado</label>
                                    <div class="select-wrapper">
                                        <select id="country_id" class="form-control" name="country_id" required>
                                            <option value="<?php echo $idEdo; ?>"><?php echo $estado; ?></option>
                                            <?php foreach($countries as $c):?>
                                                <option value="<?php echo $c->id; ?>"><?php echo $c->estado; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="4u 12u(xsmall)">
                                    <label for="state_id">Municipio</label>
                                    <div class="select-wrapper">
						    		    <select id="state_id" class="form-control" name="state_id" required>
      						    		  <option value="<?php echo $idMun; ?>"><?php echo $municipio; ?></option>
   								     </select>
		    					 </div> 
			    			  </div>
				    		  <div class="4u 12u(xsmall)">
                              <label for="city_id">Localidad</label> 
					    		 <div class="select-wrapper">
						    		    <select id="city_id" class="form-control" name="city_id" required>
      						    		  <option value="<?php echo $idLoc; ?>"><?php echo $loc; ?></option>
   								     </select>
			    				 </div> 
				    		  </div>
					       </div>
                            <div class="row uniform">
                                <div class="12u 12u(xsmall)">
                                    <label for="direccion">Domicilio: </label>
                                    <input id="direccion" name="direccion" type="text" value="<?php echo $asentamiento." ".$nombre_asentamiento.", ".$vialidad." ".$calle.", ".$num_ext; ?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                            </div>
                        </div>
                        <div class="box">(Datos sobre el responsable actual del menor)
                            <div class="row uniform">
                        	   <div class="6u 12u$(xsmall)">
                        		  <label for="responna">Responsable del NNA:</label>
								    <input id="responna" name="responna" type="text" value="<?php echo $responsable; ?>"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
							 </div>	
							 <div class="6u 12u$(xsmall)">
								<label for="parentesco">Parentesco:</label>
                                <div class="select-wrapper">
                                    <select id="parentesco" class="form-control" name="parentesco" required>
                                        <option value="<?php if(empty($responsable)) echo ""; else echo $idParentesco ?>"><?php if(empty($responsable)) echo "--Seleccione--"; else echo $parentesco ?></option>
                                        <?php while ($rowPa=$rparentesco->fetch_assoc()) { ?>
                                            <option value="<?php echo $rowPa['id']; ?>"><?php echo $rowPa['parentesco']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
							 </div>
                            </div>
                            <div class="row uniform">
                        	   <div class="6u 12u$(xsmall)">
                        		  <label for="telefono">Teléfono</label>
                        		  <input id="telefono" name="telefono" type="text" value="<?php echo $telefono; ?>" pattern="[0-9 ,]{0,50}$" >
                        	   </div>
                        	   <div class="6u 12u$(xsmall)">
                        		  <label for="correo">Correo electrónico</label>
                        		  <input id="correo" name="correo" type="text" value="<?php echo $correo; ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" >
                        	   </div>
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="12u 12u$(xsmall)">
                                <input class="button special fit" name="aceptar" id="aceptar" type="submit" value="Aceptar"><br>	
                            </div>
                        </div>
                    </form>
                </div>
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
            </div>
        </div>
        <!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
    <footer>
        <p class="copyright">&copy; Sistema DIF Hidalgo </p>
    </footer>
</html>

    
