<?php
ob_start();

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
	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;

	$fecMax=date("Y-m-d");

	$sexo="SELECT id, sexo FROM sexo";
	$resu=$mysqli->query($sexo);

	$query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($rowEstados=$equery->fetch_object())	{ $countries[]=$rowEstados; }

	$qEdCivil="SELECT * from cat_estado_civil";
	$rEdoCivil=$mysqli->query($qEdCivil);

	$idUsuario=$_GET['id'];   
	$lleno="SELECT  usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, usuarios.curp, 
	usuarios.fecha_nacimiento as fecha_nac,
	sexo.sexo, usuarios.direccion, estados.estado, usuarios.id_mun, 
	usuarios.id_loc, usuarios.telefono_fijo, usuarios.telefono_movil, municipios.municipio, 
	localidades.localidad, usuarios.id_entidad, usuarios.id_sexo, usuarios.afrodescendiente, 
	usuarios.indigena, usuarios.id_estado_civil, cat.estado_civil, usuarios.correo
	FROM usuarios
	left join sexo on sexo.id=usuarios.id_sexo
	left join municipios on municipios.id=usuarios.id_mun 
	left join localidades on localidades.id=usuarios.id_loc
	left join estados on estados.id=usuarios.id_entidad
	left join cat_estado_civil cat on cat.id=usuarios.id_estado_civil
	WHERE usuarios.id='$idUsuario'";
	$rUsuario=$mysqli->query($lleno);
	while ($rwUsuario=$rUsuario->fetch_assoc()) {
		$nombre=$rwUsuario['nombre'];
		$ape1=$rwUsuario['apellido_p'];
		$ape2=$rwUsuario['apellido_m'];
		$curp=$rwUsuario['curp'];
		$fechaNac=$rwUsuario['fecha_nac'];
		$sexo=$rwUsuario['sexo'];
		$direccion=$rwUsuario['direccion'];
		$estado=$rwUsuario['estado'];
		$idMun=$rwUsuario['id_mun'];
		$idLoc=$rwUsuario['id_loc'];
		$telFijo=$rwUsuario['telefono_fijo'];
		$telMovil=$rwUsuario['telefono_movil'];
		$municipio=$rwUsuario['municipio'];
		$localidad=$rwUsuario['localidad'];
		$idEdo=$rwUsuario['id_entidad'];
		$idSex=$rwUsuario['id_sexo'];
		$afro=$rwUsuario['afrodescendiente'];
		$indigena=$rwUsuario['indigena'];
		$idEsCivil=$rwUsuario['id_estado_civil'];
		$estCivil=$rwUsuario['estado_civil'];
		$correo=$rwUsuario['correo'];
	}
	$rlleno=$mysqli->query($lleno); //llenar datos del formulario
	$sexoU=$mysqli->query($lleno);	//llenar el select de sexo
	$munU=$mysqli->query($lleno);	//llenar el select de municipio
	$bandera = false;
	
	if(!empty($_POST))
	{
		$nombre = 	  mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$ap_paterno = mysqli_real_escape_string($mysqli,$_POST['ap_paterno']);
		$ap_materno = mysqli_real_escape_string($mysqli,$_POST['ap_materno']);
		$fecha_nac =  $_POST['fecha_nac'];
		$sexo = $_POST['genero'];
		$curp =       mysqli_real_escape_string($mysqli,$_POST['curp']);
		$estCivil= mysqli_real_escape_string($mysqli,$_POST['edo_civil']);
		if (isset($_POST['indigena']) && $_POST['indigena'] == '1')
			$banIndigena="1";
		else 
			$banIndigena="0";

		if (isset($_POST['afrodescendiente']) && $_POST['afrodescendiente'] == '1')
			$banAfro="1";
		else 
			$banAfro="0";		
		$direccion =  mysqli_real_escape_string($mysqli,$_POST['direccion']);
		$entidad = mysqli_real_escape_string($mysqli,$_POST['country_id']);
		$municipio = mysqli_real_escape_string($mysqli,$_POST['state_id']);
		$localidad =  mysqli_real_escape_string($mysqli,$_POST['city_id']);
		$fijo =       mysqli_real_escape_string($mysqli,$_POST['fijo']);
		$movil =      mysqli_real_escape_string($mysqli,$_POST['movil']);
		$correo= mysqli_real_escape_string($mysqli,$_POST['correo']);
		

		$sqlActualiza = "UPDATE usuarios SET nombre='$nombre', apellido_p='$ap_paterno', 
		apellido_m='$ap_materno', fecha_nacimiento='$fecha_nac', id_sexo='$sexo', curp='$curp', 
		id_estado_civil='$estCivil', indigena='$banIndigena', afrodescendiente='$banAfro', 
		direccion='$direccion', id_entidad='$entidad', id_mun='$municipio', id_loc='$localidad',
		telefono_fijo='$fijo', telefono_movil='$movil', correo='$correo'
		WHERE id='$idUsuario'";
		$resultActualiza = $mysqli->query($sqlActualiza);
		if($resultActualiza)
			header("Location: perfil_usuarios.php?id=$idUsuario");
		else
			echo "Error al Registrar: ".$sqlActualiza;
			
		}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Editar usuario</title>
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
					</div> <br>
					<h1>Editar</h1>
					<form id="familia"  enctype="multipart/form-data" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						<div class="box" >
							<div class="row uniform">								
								<div class="4u 12u$(xsmall)">
									<label>Nombre(s)</label>
									<input id="nombre" name="nombre" type="text" class="nombre" maxlength="40" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required="required" value="<?= $nombre ?>">
								</div>
								<div class="4u 12u$(xsmall)">
									<label>Primer apellido</label>
									<input id="ap_paterno" name="ap_paterno" type="text" class="ap_paterno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?= $ape1?>" maxlength="40" required="required">
								</div>
								<div class="4u 12u$(xsmall)">
									<label>Segundo apellido</label>
									<input id="ap_materno" name="ap_materno" type="text" class="ap_materno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$ape2?>" maxlength="40" >
								</div>
							</div>
							<div class="row uniform">
								<div class="3u 12u$(xsmall)">
									<label>Fecha de nacimiento</label>
									<input id="fecha_nac" name="fecha_nac" value="<?=$fechaNac ?>" type="date"  max='<?php echo $fecMax?>' >
								</div>
								<div class="3u 12u$(xsmall)">
									<label>Sexo</label>
									<div class="select-wrapper">
										<select id="genero" name="genero" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required >
											<option value="<?= $idSex?>"><?= $sexo ?></option>
											<?php while($row = $resu->fetch_assoc()){ ?>
												<option value="<?php echo $row['id']; ?>"><?php echo $row['sexo']; ?></option>
											<?php }?>
										</select>
									</div>
								</div>
								<div class="6u">
									<label>CURP</label>
									<input id="curp" pattern="[A-Z]{4}\d{6}[HM][A-Z]{2}[B-DF-HJ-NP-TV-Z]{3}[A-Z0-9][0-9]" name="curp" type="text"  style="text-transform:uppercase;" placeholder="(Si no cuenta con el dato dejar vacio)" onkeyup="this.value=this.value.toUpperCase();" value="<?= $curp ?>" >
								</div>
							</div>
							<div class="row uniform">
								
								<div class="6u">
									<label>Estado civil y/o familiar</label>
									<div class="select-wrapper">
										<select id="edo_civil" name="edo_civil" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required >
											<option value="<?= $idEsCivil ?>"><?= $estCivil ?></option>
											<?php while($rwc = $rEdoCivil->fetch_assoc()){ ?>
												<option value="<?php echo $rwc['id']; ?>"><?php echo $rwc['estado_civil']; ?></option>
											<?php }?>
										</select>
									</div>
								</div>
								<div class="6"><label>Origen</label>
									<div class="row uniform">
										<div class="8u 12u$(xmall">
											<input type="checkbox" id="indigena" name="indigena" value="1" <?php if ($indigena==1) { ?> checked <?php } ?>>
											<label for="indigena">Origen indigena</label>
										</div>
										<div class="4u 12u$(xmall">
											<input type="checkbox" id="afrodescendiente" name="afrodescendiente" value="1" <?php if ($afro==1) { ?> checked <?php } ?>>
											<label for="afrodescendiente">Afrodescendiente</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="box" > 
							<h4>Datos de localización</h4>
							<div class="row uniform">
								<div class="12u 12u$(xsmall)">
									<label>Dirección actual (calle y número)</label>
									<input id="direccion" name="direccion" type="text" maxlength="100" class="direccion" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?= $direccion ?>" required="required">	
								</div>
							</div>
							<div class="row uniform">
								<div class="4u">
									<label>Estado</label>
									<div class="select-wrapper">
											<select id="country_id" class="form-control" name="country_id" required>
												<option value="<?= $idEdo?>"><?= $estado ?></option>
												<?php foreach($countries as $c):?>
      												<option value="<?php echo $c->id; ?>"><?php echo $c->estado; ?></option>
												<?php endforeach; ?>
    										</select>
											</div>
								</div>
								<div class="4u">
									<label>Municipio</label>
									<div class="select-wrapper">
										<select id="state_id" class="form-control" name="state_id" required>
											<option value="<?= $idMun ?>"><?= $municipio ?></option>
										</select>
									</div> 
										
								</div>
								<div class="4u">
									<label>Localidad</label>
									<div class="select-wrapper">
											<select id="city_id" class="form-control" name="city_id" required>
												<option value="<?= $idLoc ?>"><?= $localidad ?></option>
											</select>
											</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="3u 12u$(xsmall)">
									<label>Teléfono fijo</label>
									<input id="fijo" name="fijo" type="text" maxlength="40" class="fijo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$telFijo ?>" >	
								</div>
								<div class="3u 12u$(xsmall)">
									<label>Teléfono celular</label>
									<input id="movil" name="movil" type="text" maxlength="40" class="movil" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$telMovil ?>">	
								</div>
								<div class="6u">
									<label>Correo electrónico</label>
									<input id="correo" name="correo" type="email" maxlength="100" class="correo" pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{1,5}" value="<?= $correo ?>">
								</div>
							</div>
						</div>

						<div class="row uniform">
							<div class="12u$">
								<ul class="actions">
									<input class="button special fit" name="registar" type="submit" value="Actualizar" >
									<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='welcome.php'" >
								</ul>
							</div>
						</div>
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
	
						
	<?php if($bandera) { 
			header("Location: lista_usuarios.php");

			?>
						
			<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>

	
		
		
</div>
					</div>

						

				<!-- Sidebar -->
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
			</div> <!--cierre menu-->

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>