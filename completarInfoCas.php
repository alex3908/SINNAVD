<?php
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
    $idDEPTO = $_SESSION['id'];
	$id_centro=$_GET['id'];
    $qDatosCas="SELECT nombre, centros.titular, centros.rfc, centros.tipo, centros.telefono, 
    centros.celular, centros.correo1, centros.correo2, centros.sup, centros.const, centros.numacta, fecha_acta,
     centros.notaria, centros.repreL, centros.calle, centros.cp, centros.id_estado, estados.estado, 
	 centros.id_mun, municipios.municipio, centros.id_loc, localidades.localidad
    from centros inner join  estados on centros.id_estado=estados.id
    inner join municipios on centros.id_mun=municipios.id
    inner join  localidades on centros.id_loc=localidades.id
    where centros.id='$id_centro'";
	$rDatosCas=$mysqli->query($qDatosCas);
	$query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($row=$equery->fetch_object())	{ $countries[]=$row; }
	while ($rowCas=$rDatosCas->fetch_assoc()) {		
		$nombre=$rowCas['nombre'];
		$titular=$rowCas['titular'];
		$rfc=$rowCas['rfc'];
		$tipo=$rowCas['tipo'];
		$telefono=$rowCas['telefono'];
		$celular=$rowCas['celular'];
		$correo1=$rowCas['correo1'];
		$correo2=$rowCas['correo2'];
		$superficie=$rowCas['sup'];
		$construccion=$rowCas['const'];
		$numacta=$rowCas['numacta'];
		$fechaActa=$rowCas['fecha_acta'];
		$notaria=$rowCas['notaria'];
		$repreL=$rowCas['repreL'];
		$calle=$rowCas['calle'];
		$codPos=$rowCas['cp'];
		$idEstado=$rowCas['id_estado'];
		$estado=$rowCas['estado'];
		$idMun=$rowCas['id_mun'];
		$municipio=$rowCas['municipio'];
		$idLoc=$rowCas['id_loc'];
		$localidad=$rowCas['localidad'];
	}
	$query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($row=$equery->fetch_object())	{ $countries[]=$row; }
	$fecha= date ("j/n/Y");
	$bandera = false;
	if(!empty($_POST['registrar']))
	{
		if($rfc=="" or $rfc=="-")
		$rfc = mysqli_real_escape_string($mysqli,$_POST['rfc']);
		$telefono = mysqli_real_escape_string($mysqli,$_POST['telefono']);
		$celular = mysqli_real_escape_string($mysqli,$_POST['celular']);
		$correo1 = mysqli_real_escape_string($mysqli,$_POST['correo1']);
		$correo2 = mysqli_real_escape_string($mysqli,$_POST['correo2']);
		$sup = mysqli_real_escape_string($mysqli,$_POST['superficie']);
		$const = mysqli_real_escape_string($mysqli,$_POST['construccion']);
		if($numacta=="" or $numacta=="-")
		$numacta = mysqli_real_escape_string($mysqli,$_POST['numacta']);
		if($fechaActa=="1900-01-01")
		$fechaActa = mysqli_real_escape_string($mysqli,$_POST['fechaActa']);
		$notaria = mysqli_real_escape_string($mysqli,$_POST['notaria']);
		$repreL = mysqli_real_escape_string($mysqli,$_POST['repreL']);
		$idEstado = $_POST['country_id'];
		$idMun = $_POST['state_id'];
		$idLoc = $_POST['city_id'];
		$qactCas="UPDATE centros set rfc='$rfc', telefono='$telefono', celular='$celular', correo1='$correo1', correo2='$correo2',
		sup='$sup', const='$const', numacta='$numacta', fecha_acta='$fechaActa', notaria='$notaria', repreL='$repreL', 
		id_estado='$idEstado', id_mun='$idMun', id_loc='$idLoc'
		where id='$id_centro'";
		echo $qactCas;
		$ractCas=$mysqli->query($qactCas);
		if($ractCas){ 
			echo "<script>
				alert('Se ha actualizado la información correctamente');
				window.location= 'listaxcentro.php?id=$id_centro'
				</script>";
		 } else {
			 echo "Error: ".$qactCas;
		 }

	
	}

	$vald="SELECT id from departamentos where id='$idDEPTO' and casp='1' and (id_depto='13' OR id_depto='16' OR id_depto='10')";
	$evald=$mysqli->query($vald);
	$rows2=$evald->num_rows;
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Editar CAS</title>
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
				<div class="inner"><br>					
					<div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div>
					<h2><?php echo $nombre ?></h2>
					<div class="box" >
						<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						<div class="row uniform">
							<div class="6u 12u$(xsmall)" align="left">
								<b>Titular:</b><br> <?php echo $titular;?></li>
							</div>
							<div class="3u 12u$(xsmall)" align="left">
								<b>Tipo:<br></b> 
								<div class="select-wrapper">
									<select id="tipo" name="tipo" required>
										<option value="<?php echo $tipo;?>"><?php echo $tipo;?></option>
										<?php if($tipo=="PRIVADO SIN CUOTA" or $tipo=="PRIVADO CON CUOTA"){ ?>
										<option value="PRIVADO CON CUOTA FUERA DEL ESTADO">PRIVADO CON CUOTA FUERA DEL ESTADO</option>
										<option value="PRIVADO SIN CUOTA FUERA DEL ESTADO">PRIVADO SIN CUOTA FUERA DEL ESTADO</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="3u 12u$(xsmall)" align="left">
								<b>RFC:<br></b> 
								<?php if($rfc=="" or $rfc=="-") {?> 
									<input id="rfc" name="rfc" type="text" style="text-transform:uppercase;" maxlength="20" onkeyup="this.value=this.value.toUpperCase();" placeholder="<?php echo $rfc;?>">	
								<?php } else { ?>
									<input id="rfc" name="rfc" type="text" style="text-transform:uppercase;" maxlength="20" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $rfc;?>" disabled >									
								<?php } ?>							
							</div>
						</div><br><b>Dirección:</b>
						<div class="row uniform">
							<div class="2u 12u$(xsmall)" align="left">
								<b>Estado:<br></b> 
								<div class="select-wrapper">
									<select id="country_id" class="form-control" name="country_id" required>
      									<option value="<?php echo $idEstado; ?>"><?php echo $estado; ?></option>
										<?php foreach($countries as $c):?>
      										<option value="<?php echo $c->id; ?>"><?php echo $c->estado; ?></option>
										<?php endforeach; ?>
    								</select>
								</div>
							</div>
							<div class="2u 12u$(xsmall)" align="left">
								<b>Municipio:<br></b> 
								<div class="select-wrapper">
									<select id="state_id" class="form-control" name="state_id" required>
      									<option value="<?php echo $idMun; ?>"><?php echo $municipio ?></option>
   									</select>
								</div> 
							</div>
							<div class="2u 12u$(xsmall)" align="left">
								<b>Localidad:<br></b> 
									<div class="select-wrapper">
										<select id="city_id" class="form-control" name="city_id" required>
      										<option value="<?php echo $idLoc; ?>"><?php echo $localidad; ?></option>
   										</select>
									</div> 
							</div>
							<div class="2u 12u$(xsmall)" align="left">
								<b>Código postal:<br></b> 
									<input id="codpos" name="codpos" type="text" maxlength="5" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $codPos;?>">	
							</div>
							<div class="4u 12u$(xsmall)" align="left">
								<b>Calle:<br></b> 
									<input id="calle" name="calle" maxlength="100" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $calle;?>">	
							</div>
						</div>
						<div class="row uniform">
							<div class="3u 12u$(xsmall)" align="left">
								<b>Teléfono fijo:<br></b> 
									<input id="telefono" name="telefono" type="text" style="text-transform:uppercase;" maxlength="30" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $telefono;?>">	
							</div>
							<div class="3u 12u$(xsmall)" align="left">
								<b>Teléfono celular:<br></b> 
									<input id="celular" name="celular" type="text" style="text-transform:uppercase;" maxlength="30" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $celular;?>">									
							</div>
							<div class="3u 12u$(xsmall)" align="left">
								<b>Superficie total:<br></b> 
									<input id="superficie" name="superficie" type="text" maxlength="50" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $superficie;?>" >									
							</div>
							<div class="3u 12u$(xsmall)" align="left">
								<b>Construcción total:<br></b> 
									<input id="construccion" name="construccion" type="text" maxlength="50" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $construccion;?>" >									
							</div>
							<div class="5u 12u$(xsmall)" align="left">
								<b>Correo 1:<br></b> 
									<input id="correo1" name="correo1" type="text" style="text-transform:uppercase;" maxlength="50" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $correo1;?>" >									
							</div>
							<div class="5u 12u$(xsmall)" align="left">
								<b>Correo 2:<br></b> 
									<input id="correo2" name="correo2" type="text" style="text-transform:uppercase;" maxlength="50" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $correo2;?>" >									
							</div>
							<div class="2u 12u$(xsmall)" align="left">
								<b>Notaría pública:<br></b> 
									<input id="notaria" name="notaria" type="text" maxlength="100" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $notaria;?>" >									
							</div>
							<div class="6u 12u$(xsmall)" align="left">
								<b>Representante legal:<br></b> 
									<input id="repreL" name="repreL" type="text" maxlength="150" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $repreL	;?>">	
							</div>
							<div class="2u 12u$(xsmall)" align="left">
								<b>Número de acta:<br></b> 
								<?php if($numacta=="" or $numacta=="-") {?> 
									<input id="numacta" name="numacta" type="text" maxlength="30" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="<?php echo $numacta;?>">	
								<?php } else { ?>
									<input id="numacta" name="numacta" type="text" maxlength="30" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $numacta	;?>" disabled >									
								<?php } ?>							</div><div class="4u 12u$(xsmall)" align="left">
								<b>Fecha de registro de acta:<br></b> 
								<?php if($fechaActa=="1900-01-01") {?> 
									<input id="fechaActa" name="fechaActa" type="date"  value="<?php echo $fechaActa;?>">	
								<?php } else { ?>
									<input id="fechaActa" name="fechaActa" type="date" value="<?php echo $fechaActa;?>" disabled >									
								<?php } ?>
							</div>
							
						</div>
						<div class="12u$">
							<ul class="actions">
									<br>
								<?php if ($rows2>0) { ?><input class="button special fit" name="registrar" type="submit" value="Registrar"  >	<?php } else {?>
								<input class="button special fit" name="registrar" type="submit" value="Registrar" disabled >
								<?php } ?>
								<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='listaxcentro.php?id=<?php echo $id_centro; ?>'" >
							</ul>
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
					<?php if($bandera) { 
						header("Location: welcome.php");
					} else { ?>
						<br/>
						<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
					<?php } ?>
				</div>
			</div>
			<div id="sidebar">
				<div class="inner">
					<?php if($_SESSION['departamento']==7) { ?> 
						<nav id="menu">
							<header class="major">
								<h2>Menú</h2>
							</header>
							<ul>
								<li><a href="welcome.php" ">Inicio</a></li>
								<li><span class="opener">Departamentos</span>
									<ul>
									<li><a href="lista_personal.php">Ver</a></li>
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												<li><a href="registro_usuarios.php">Alta</a></li>
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>

									</ul>
								</nav>
														
								<?php }elseif ($_SESSION['departamento']==16) { ?>
							
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
										
										<li><a href="welcome.php" ">Inicio</a></li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>						
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul>
									<li><a href="welcome.php" ">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>		
							
								<?php }
	
								?>
								<section>
									<header class="major">
										<h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
									</header>
									<p></p>
									<ul class="contact">
										<li class="fa-envelope-o"><a href="#">laura.ramirez@hidalgo.gob.mx</a></li>
										<li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
										<li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
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
					<!--cierre menu-->

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>