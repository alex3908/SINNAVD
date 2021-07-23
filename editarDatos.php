<?php
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$idNNA = $_GET['id'];

	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}
	$reportesvd="SELECT id from reportes_vd where atendido='1' 
	and (asignado='$idDEPTO' or asignado_psic='$idDEPTO')";
	$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;


	$sqlnna="SELECT nna.indigena, nna.afrodescendiente, nna.llegoAlEstado, nna.origen, nna.lugOrigen, nna.violentado, nna.tipoViolencia, nna.nombre, nna.apellido_p, nna.apellido_m, e.estado_civil, nna.id_estado_civil from nna left join cat_estado_civil e on nna.id_estado_civil=e.id where nna.id=$idNNA";
	$qnna = $mysqli->query($sqlnna);
	while ($rwNna=$qnna->fetch_assoc()) {
		$nombre= $rwNna['nombre'];
		$ape1= $rwNna['apellido_p'];
		$ape2 = $rwNna['apellido_m'];
		$indigena = $rwNna['indigena'];
		$afro = $rwNna['afrodescendiente'];
		$llegoAlEstado = $rwNna['llegoAlEstado'];
		$origen = $rwNna['origen'];
		$lugOrigen = $rwNna['lugOrigen'];
		$violentado = $rwNna['violentado'];
		$tipoViolencia = $rwNna['tipoViolencia'];
		$estadoCivil = $rwNna['estado_civil'];
		$idEstadoCivil = $rwNna['id_estado_civil'];
	}

	if(!empty($_POST['actualizar'])){
		$fecha = date("Y-m-d H:i:s", time());
		$indigena= $_POST['ddlIndigena'];
		$afro = $_POST['ddlAfro'];
		$llegoAlEstado = $_POST['ddlMig'];
		$origen= $_POST['ddlOrigen'];
		$lugOrigen = $_POST['ddlLugOri'];
		if($llegoAlEstado==0)
			$lugOrigen = 'NO APLICA';
		$violentado = $_POST['ddlViolencia'];
		$tipoViolencia = $_POST['ddlViolenciaTipo'];
		$estadoCivil = $_POST['ddlestadoCivil'];
		$qActualizar = "CALL actualizarDatosNNAP($idNNA, $indigena, $afro, $llegoAlEstado, '$origen', '$lugOrigen', $violentado, '$tipoViolencia', $estadoCivil, $idDEPTO, '$fecha')";
		$rActualizar = $mysqli->query($qActualizar);

		if($rActualizar){
			echo "<script>
					alert('Se ha actualizado la información correctamente');
					window.location= 'perfil_nna.php?id=$idNNA'
				</script>";
			} else {
				$errnov= mysqli_real_escape_string($mysqli,$mysqli->errno);
				$errorv= mysqli_real_escape_string($mysqli,$mysqli->error);
				echo $errnov."--".$errorv;
			}

	}


?>

<!DOCTYPE HTML>
<html> 
	<head lang="es-MX">
		<title>Editar NNA</title>
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
					<h2>EDITAR DATOS DE <?= $nombre." ".$ape1." ".$ape2 ?></h2>
					<div class="box">
						<form id="registro" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
							<div class="row uniform">
								<div class="6u">De acuerdo con sus costumbres y tradiciones, ¿se considera parte de una comunidad indígena?
									<div class="select-wrapper">
										<select id="ddlIndigena" class="form-control" name="ddlIndigena" required>
											<?php if($indigena==1) 
												echo "<option value='1' selected>SI</option>
												<option value='0'>NO</option>"; 
											else 
												echo "<option value='0' selected>NO</option>
												<option value='1'>SI</option>"; ?>
											
										</select>
									</div>
								</div>
								<div class="6u">De acuerdo con sus costumbres y tradiciones, ¿se considera  afroamericano(a) o afrodescendiente?
									<div class="select-wrapper">
										<select id="ddlAfro" class="form-control" name="ddlAfro" required>
											<?php if($afro==1) 
												echo "<option value='1' selected>SI</option>
												<option value='0'>NO</option>"; 
											else 
												echo "<option value='0' selected>NO</option>
												<option value='1'>SI</option>"; ?>
										</select>
									</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="6u">¿Es persona que abandona el lugar en que nació y llega al Estado para establecerse en él de manera temporal o definitiva?
									<div class="select-wrapper">
										<select id="ddlMig" class="form-control" name="ddlMig" required>
											<?php if($llegoAlEstado==1) 
												echo "<option value='1' selected>SI</option>
												<option value='0'>NO</option>"; 
											else 
												echo "<option value='0' selected>NO</option>
												<option value='1'>SI</option>"; ?>
										</select>
									</div>
								</div>
								<div class="3u">Especifique el origen
									<div class="select-wrapper">
										<select id="ddlOrigen" class="form-control" name="ddlOrigen" required>
											<?php if($llegoAlEstado==1) {
												if($origen=='NACIONAL')
													echo "<option value='NACIONAL' selected>NACIONAL</option>
													<option value='INTERNACIONAL'>INTERNACIONAL</option>"; 
												elseif($origen=='INTERNACIONAL')
													echo "<option value='INTERNACIONAL' selected>INTERNACIONAL</option>
													<option value='NACIONAL'>NACIONAL</option>"; 
											}else 
												echo "<option value='NO APLICA' selected>NO APLICA</option>"; ?>								
										</select>
									</div>
								</div>
								<div class="3u">Lugar
									<div class="select-wrapper">
										<select id="ddlLugOri" class="form-control" name="ddlLugOri" >
											<?php if($llegoAlEstado==1) {
												echo "<option value='".$lugOrigen."' selected>".$lugOrigen."</option>";
												if($origen=='NACIONAL'){
													$qEstados = "SELECT estado FROM estados where id!=13";
													$rEstados = $mysqli->query($qEstados);
													$estados = array();
													while($row=$rEstados->fetch_object()){ $estados[]=$row; }
													foreach ($estados as $s) {
														print "<option value='$s->estado'>$s->estado</option>";
													}
												}													 
												elseif($origen=='INTERNACIONAL'){
													$qPaises= "SELECT id_pais, pais  FROM cat_paises where id_pais!=303 and id_pais!=1 order by pais";
													$rPaises= $mysqli->query($qPaises);
													$paises= array();
													while ($row= $rPaises->fetch_object()) {
														$paises[]=$row;
													}
													foreach ($paises as $p) {
														print "<option value='$p->pais'>$p->pais</option>";
													}
												}													
											}else 
												echo "<option value='NO APLICA' selected>NO APLICA</option>"; ?>
												}
										</select>
									</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="5u">¿En los últimos tres meses sufrió algún tipo de violencia?
									<div class="select-wrapper">
										<select id="ddlViolencia" class="form-control" name="ddlViolencia" required>
											<?php if($violentado==1) 
												echo "<option value='1' selected>SI</option>
												<option value='0'>NO</option>"; 
											else 
												echo "<option value='0' selected>NO</option>
												<option value='1'>SI</option>"; ?>
										</select>
									</div>
								</div>
								<div class="5u">Especifique el tipo principal de violencia sufrida
									<div class="select-wrapper">
										<select id="ddlViolenciaTipo" class="form-control" name="ddlViolenciaTipo" required>
											<?php if($violentado==0) echo "<option value='NO APLICA'>NO APLICA</option>";
											else {
												echo "<option value='".$tipoViolencia."'>".$tipoViolencia."</option>";
												echo "<option value='PSICOLOGICA'>PSICOLÓGICA</option>
												<option value='FISICA'>FÍSICA</option>
												<option value='PATRIMONIAL'>PATRIMONIAL</option>
												<option value='ECONOMICA'>ECONÓMICA</option>
												<option value='SEXUAL'>SEXUAL</option>"; }?>
										</select>
									</div>
								</div>
								<div class="2u">
									<div class="select-wrapper">Estado civil
										<select id="ddlestadoCivil" class="form-control" name="ddlestadoCivil">
											<option value="<?=$idEstadoCivil?>"><?=$estadoCivil ?></option>	
											<?php 
											$qEdCivil="SELECT * from cat_estado_civil where id!=$idEstadoCivil";
											$rEdoCivil=$mysqli->query($qEdCivil);
											while($rwc = $rEdoCivil->fetch_assoc()){ ?>
												<option value="<?php echo $rwc['id']; ?>"><?php echo $rwc['estado_civil']; ?></option>
											<?php }?>									
										</select>
									</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="6u">
									<input class="button special fit" type="submit" name="actualizar" value="Actualizar">
								</div>
								<div class="6u">
									<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_nna.php?id=<?=$idNNA?>'">
								</div>
							</div>
						</form>
						<script type="text/javascript">
							$(document).ready(function(){
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