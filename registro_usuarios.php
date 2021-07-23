<?php
	ob_start();
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$hoy= date("Y-m-d");
	
	$idDEPTO = $_SESSION['id'];
	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}

	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;

	$sql = "SELECT id, responsable FROM departamentos WHERE id= '$idDEPTO'";	
	$result=$mysqli->query($sql);
	$fecMax=date("Y-m-d");

	$row = $result->fetch_assoc();
	$query="SELECT * from municipios where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($row=$equery->fetch_object())	{ $countries[]=$row; }

	$sexo="SELECT id, sexo FROM sexo";
	$resu=$mysqli->query($sexo);

	$query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($rowEstados=$equery->fetch_object())	{ $countries[]=$rowEstados; }

	$qEdCivil="SELECT * from cat_estado_civil";
	$rEdoCivil=$mysqli->query($qEdCivil);

	$bandera = false;
	
	if(!empty($_POST))
	{

		$nombre = 	  mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$ap_paterno = mysqli_real_escape_string($mysqli,$_POST['ap_paterno']);
		$ap_materno = mysqli_real_escape_string($mysqli,$_POST['ap_materno']);
		$fecha_nac =  $_POST['fecha_nac'];
		if($fecha_nac=='')
			$fecha_nac='1900-01-01';
		$genero = $_POST['genero'];
		$curp =       mysqli_real_escape_string($mysqli,$_POST['curp']);
		$estCivil= mysqli_real_escape_string($mysqli,$_POST['edo_civil']);

		$direccion =  mysqli_real_escape_string($mysqli,$_POST['direccion']);
		$entidad = mysqli_real_escape_string($mysqli,$_POST['country_id']);
		$municipio = mysqli_real_escape_string($mysqli,$_POST['state_id']);
		$localidad =  mysqli_real_escape_string($mysqli,$_POST['city_id']);
		$fijo =       mysqli_real_escape_string($mysqli,$_POST['fijo']);
		$movil =      mysqli_real_escape_string($mysqli,$_POST['movil']);
		$correo= mysqli_real_escape_string($mysqli,$_POST['correo']);
		/*$fechaS= $_POST['fecha_registro'];
		$objfecha= date_create_from_format('Y-m-d', $fechaS);
		$fecha=date_format($objfecha, "j/n/Y");*/
		$fecha = date("Y-m-d H:i:s", time());

		$banIndigena =$_POST['ddlIndigena'];
		$banAfro = $_POST['ddlAfro'];
		$llegoAlEstado = $_POST['ddlMig'];
		$origen = $_POST['ddlOrigen'];
		$lugOrigen= $_POST['ddlLugOri'];
		if($llegoAlEstado==0)
			$lugOrigen = 'NO APLICA';
		$rViolencia = $_POST['ddlViolencia'];
		$tipoViolencia =  $_POST['ddlViolenciaTipo'];


		$est_civil=$_POST['edo_civil'];

		$sqlNino = "INSERT INTO usuarios (nombre, apellido_p, apellido_m, curp, fecha_nacimiento, id_sexo, direccion, 
			id_entidad, id_mun, id_loc, telefono_fijo, telefono_movil, fecha_registro, respo_reg, correo, id_estado_civil, 
			indigena, afrodescendiente, llegoAlEdo, origen, lugOrigen, violentado, tipoViolencia) 
			VALUES ('$nombre','$ap_paterno','$ap_materno','$curp','$fecha_nac','$genero','$direccion',
			'$entidad','$municipio', '$localidad', '$fijo','$movil', '$fecha', '$idDEPTO', '$correo', '$estCivil', 
			'$banIndigena', '$banAfro', '$llegoAlEstado', '$origen', '$lugOrigen', '$rViolencia', '$tipoViolencia' )";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino)
			{
				$qIdUsuario="SELECT max(id) from usuarios where nombre='$nombre' and apellido_p='$ap_paterno'";
				$rIdUsuario=$mysqli->query($qIdUsuario);
				$idUsuario=implode($rIdUsuario->fetch_assoc());
				header("Location: perfil_usuarios.php?id=$idUsuario");
			}
			else
			echo "ERROR: ".$sqlNino;
			
	}
	
	
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Registro de usuario</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
	
	</head>
	<body>
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
					</div><br>
					<h1>Registro de Usuarios</h1>
					<form id="familia"  enctype="multipart/form-data" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
						<div class="box" >
							<div class="row uniform">								
								<div class="4u 12u$(xsmall)">
									<label>Nombre(s)</label>
									<input id="nombre" name="nombre" type="text" class="nombre" maxlength="40" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required="required">
								</div>
								<div class="4u 12u$(xsmall)">
									<label>Primer apellido</label>
									<input id="ap_paterno" name="ap_paterno" type="text" class="ap_paterno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="40" >
								</div>
								<div class="4u 12u$(xsmall)">
									<label>Segundo apellido</label>
									<input id="ap_materno" name="ap_materno" type="text" class="ap_materno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="40" >
								</div>
							</div>
							<div class="row uniform">
								<div class="3u 12u$(xsmall)">
									<label>Fecha de nacimiento</label>
									<input id="fecha_nac" name="fecha_nac" value="" type="date"  max='<?php echo $fecMax?>' >
								</div>
								<div class="3u 12u$(xsmall)">
									<label>Sexo</label>
									<div class="select-wrapper">
										<select id="genero" name="genero" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >
											<option value="0">--Seleccione--</option>
											<?php while($row = $resu->fetch_assoc()){ ?>
												<option value="<?php echo $row['id']; ?>"><?php echo $row['sexo']; ?></option>
											<?php }?>
										</select>
									</div>
								</div>
								<div class="6u">
									<label>CURP</label>
									<input id="curp" pattern="[A-Z]{4}\d{6}[HM][A-Z]{2}[B-DF-HJ-NP-TV-Z]{3}[A-Z0-9][0-9]" name="curp" type="text"  style="text-transform:uppercase;" placeholder="(Si no cuenta con el dato dejar vacio)" onkeyup="this.value=this.value.toUpperCase();" >
								</div>
							</div>

							<div class="row uniform">
								<div class="12u"><h4>Otros datos</h4></div>
							</div>
							<div class="row uniform">
								<div class="6u">De acuerdo con sus costumbres y tradiciones, ¿se considera parte de una comunidad indígena?
									<div class="select-wrapper">
										<select id="ddlIndigena" class="form-control" name="ddlIndigena" required>
											<option value="0">NO</option>
											<option value="1">SI</option>
										</select>
									</div>
								</div>
								<div class="6u">De acuerdo con sus costumbres y tradiciones, ¿se considera  afroamericano(a) o afrodescendiente?
									<div class="select-wrapper">
										<select id="ddlAfro" class="form-control" name="ddlAfro" required>
											<option value="0">NO</option>
											<option value="1">SI</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="6u">¿Es persona que abandona el lugar en que nació y llega al Estado para establecerse en él de manera temporal o definitiva?
									<div class="select-wrapper">
										<select id="ddlMig" class="form-control" name="ddlMig" required>
											<option value="0">NO</option>
											<option value="1">SI</option>
										</select>
									</div>
								</div>
								<div class="3u">Especifique el origen
									<div class="select-wrapper">
										<select id="ddlOrigen" class="form-control" name="ddlOrigen" required>
											<option value="NO APLICA">NO APLICA</option>
										</select>
									</div>
								</div>
								<div class="3u">Lugar
									<div class="select-wrapper">
										<select id="ddlLugOri" class="form-control" name="ddlLugOri" required>
											<option value="NO APLICA">NO APLICA</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="4.5u">¿En los últimos tres meses sufrió algún tipo de violencia?
									<div class="select-wrapper">
										<select id="ddlViolencia" class="form-control" name="ddlViolencia" required>
											<option value="0">NO</option>
											<option value="1">SI</option>
										</select>
									</div>
								</div>
								<div class="4.5u">Especifique el tipo principal de violencia sufrida
									<div class="select-wrapper">
										<select id="ddlViolenciaTipo" class="form-control" name="ddlViolenciaTipo" required>
											<option value="NO APLICA">NO APLICA</option>
										</select>
									</div>
								</div>								
								<div class="3u">Estado civil y/o familiar
									<div class="select-wrapper">
										<select id="edo_civil" name="edo_civil" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >
											<option value="0">--Seleccione--</option>
											<?php while($rwc = $rEdoCivil->fetch_assoc()){ ?>
												<option value="<?php echo $rwc['id']; ?>"><?php echo $rwc['estado_civil']; ?></option>
											<?php }?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="box" > 
							<h4>Datos de localización</h4>
							<div class="row uniform">
								<div class="12u 12u$(xsmall)">
									<label>Dirección actual (calle y número)</label>
									<input id="direccion" name="direccion" type="text" maxlength="100" class="direccion" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="Dirección actual" >	
								</div>
							</div>
							<div class="row uniform">
								<div class="4u">
									<label>Estado</label>
									<div class="select-wrapper">
											<select id="country_id" class="form-control" name="country_id" >
      											<option value="">-- Estado --</option>
												<?php foreach($countries as $c):?>
      												<option value="<?php echo $c->id; ?>"><?php echo $c->estado; ?></option>
												<?php endforeach; ?>
    										</select>
											</div>
								</div>
								<div class="4u">
									<label>Municipio</label>
									<div class="select-wrapper">
										<select id="state_id" class="form-control" name="state_id" >
											<option value="">-- MUNICIPIO --</option>
   										</select>
									</div> 
										
								</div>
								<div class="4u">
									<label>Localidad</label>
									<div class="select-wrapper">
											<select id="city_id" class="form-control" name="city_id" >
												<option value="">-- LOCALIDAD --</option>
   											</select>
											</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="3u 12u$(xsmall)">
									<label>Teléfono fijo</label>
									<input id="fijo" name="fijo" type="text" maxlength="40" class="fijo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >	
								</div>
								<div class="3u 12u$(xsmall)">
									<label>Teléfono celular</label>
									<input id="movil" name="movil" type="text" maxlength="40" class="movil" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >	
								</div>
								<div class="6u">
									<label>Correo electrónico</label>
									<input id="correo" name="correo" type="email" maxlength="100" class="correo" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" placeholder="(Si no cuenta con correo dejar vacio)">
								</div>
							</div>
						</div>
						<div class="row uniform">
							<div class="12u 12u$(xsmall)">Fecha en la que se registro al usuario:
								<input type="date" name="fecha_registro" min="2020-04-01" max="<?php echo $hoy; ?>" value="<?php echo $hoy; ?>" disabled>
							</div>
						</div>
					
						<div class="row uniform">
							<div class="12u$">
								<ul class="actions">
									<input class="button special fit" name="registar" type="submit" value="Registrar" >
									<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='lista_usuarios.php'" >
								</ul>
							</div>
						</div>
					</form>	

					<?php if($bandera) {
						header("Location: lista_usuarios.php");
						?>
					<?php }else{ ?>
						<br />
						<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?>		
						</div>
					<?php } ?>
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

							$("#ddlMig").change(function(){
								$.get("get_origen.php", "ddlMig="+$("#ddlMig").val(), function(data){
									$("#ddlOrigen").html(data);
									console.log(data);
								});
							});

							$("#ddlOrigen").change(function(){
								$.get("get_lug_origen.php", "ddlOrigen="+$("#ddlOrigen").val(), function(data){
									$("#ddlLugOri").html(data);
									console.log(data);
								});
							});

							$("#ddlViolencia").change(function(){
								$.get("get_tipo_violencia.php", "ddlViolencia="+$("#ddlViolencia").val(), function(data){
									$("#ddlViolenciaTipo").html(data);
									console.log(data);
								});
							});
						});
					</script>
				</div> <!--final inner-->
			</div><!--final main-->
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
			</div>
		</div>  <!--final wrapper-->

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>