<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
	}
	
	
	$idDEPTO = $_SESSION['id'];	

	$idReporte = $_GET['id'];	
	$_SESSION['idRep'] = $idReporte;
	$fecha=date("j/n/Y");

	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idD=$row['id_depto'];
		$idP=$row['id_personal'];
	}
	$sql="SELECT reportes_vd.folio, reportes_vd.fecha, reportes_vd.recepcion, distritos.distrits, tipo_maltrato.maltrato, reportes_vd.nom_nna, reportes_vd.edad_nna, reportes_vd.fn_nna, reportes_vd.lugarnac_nna, reportes_vd.lugarreg_nna, reportes_vd.otros_datos, reportes_vd.persona_reporte, reportes_vd.narracion, municipios.municipio, localidades.localidad, reportes_vd.calle, reportes_vd.ubicacion, departamentos.responsable, reportes_vd.asignado, reportes_vd.asignado_psic, reportes_vd.asignado_j, reportes_vd.atendido, reportes_vd.respo_reg from reportes_vd, municipios, departamentos, tipo_maltrato, localidades, distritos where reportes_vd.id='$idReporte' and departamentos.id=reportes_vd.respo_reg and tipo_maltrato.id=reportes_vd.id_maltrato and municipios.id=reportes_vd.clm and distritos.id=reportes_vd.id_distrito and localidades.id=reportes_vd.id_localidad";
	$esql=$mysqli->query($sql);
	$esql3=$mysqli->query($sql);
	$esql2=$mysqli->query($sql);
	while ($row=$esql2->fetch_assoc()) {
		$asig=$row['asignado'];
		$asigp=$row['asignado_psic'];
		$asigj=$row['asignado_j'];
	}
	$aa="SELECT responsable from departamentos where id='$asig'";
	$eaa=$mysqli->query($aa);
	while ($row=$eaa->fetch_assoc()) {
		$res=$row['responsable'];
	}
	$ap="SELECT responsable from departamentos where id='$asigp'";
	$eap=$mysqli->query($ap);
	while ($row=$eap->fetch_assoc()) {
		$resp=$row['responsable'];
	}
	$aj="SELECT responsable from departamentos where id='$asigj'";
	$eaj=$mysqli->query($aj);
	while ($row=$eaj->fetch_assoc()) {
		$resj=$row['responsable'];
	}


	if (isset($_POST['regresar'])) { 		
		header("Location: lista_reporte.php");
	}

	if (isset($_POST['demo-priority'])) {
	$estado = $_POST['demo-priority'];
		if ($idD=='16' and $idP=='1') {
			$NA="UPDATE reportes_vd set atendido='$estado', fecha_ate='$fecha' where id='$idReporte'";
				$eNA=$mysqli->query($NA);
				header("Location: perfil_reporte.php?id=$idReporte");

		}else if ($estado=='3' or $estado=='4') {
			if (empty($asig) and empty($asigp)) {

				$NA="UPDATE reportes_vd set atendido='$estado', fecha_ate='$fecha' where id='$idReporte'";
				$eNA=$mysqli->query($NA);
				header("Location: perfil_reporte.php?id=$idReporte");
			}else {
			$peva="SELECT reportes_vd.id from reportes_vd, acercamiento_familiar, acercamiento_psic where reportes_vd.id='$idReporte' and (acercamiento_psic.id_reporte=reportes_vd.id or acercamiento_familiar.id_reporte=reportes_vd.id)";
			$epeva=$mysqli->query($peva);
			$rev=$epeva->num_rows;
			if ($rev>0) {
				$NA="UPDATE reportes_vd set atendido='$estado', fecha_ate='$fecha' where id='$idReporte'";
				$eNA=$mysqli->query($NA);
				header("Location: perfil_reporte.php?id=$idReporte");
			} 
		} } else {
			$NA="UPDATE reportes_vd set atendido='$estado', fecha_ate='$fecha' where id='$idReporte'";
				$eNA=$mysqli->query($NA);
				header("Location: perfil_reporte.php?id=$idReporte");	
		}
		
	}
	if (isset($_POST['desj'])) {	
		$NA="UPDATE reportes_vd set asignado_j='0' where id='$idReporte'";
		$eNA=$mysqli->query($NA);
		header("Location: perfil_reporte.php?id=$idReporte");
	}
	if (isset($_POST['dests'])) {	
		$NA="UPDATE reportes_vd set asignado='0' where id='$idReporte'";
		$eNA=$mysqli->query($NA);
		header("Location: perfil_reporte.php?id=$idReporte");
	}
	if (isset($_POST['desps'])) {	
		$NA="UPDATE reportes_vd set asignado_psic='0' where id='$idReporte'";
		$eNA=$mysqli->query($NA);
		header("Location: perfil_reporte.php?id=$idReporte");
	}
	
	$valida="SELECT id from departamentos where (id_depto in ('9','10') and id_personal='3' and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5' and id='$idDEPTO') or (id_personal='1' and id='$idDEPTO' and id_depto='16')"; //ayuda a validar la persona que puede mover 
	$evalida=$mysqli->query($valida);
	$rows2=$evalida->num_rows;
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Perfil</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">
				<!-- Main -->
					<div id="main">

						<div class="inner"><br> <br> <div class="box alt" align="center">
			<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
		</div></div>
	<?php while ($row=$esql->fetch_assoc()) { ?>	
						<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						<div class="row uniform">
							<div class="12u$">
							<input type="submit" class="button special small" name="regresar" value="regresar">
							</div>
							<div class="12u$">
								<div class="box">
								
									<ul class="alt">

	<table>
		<caption>Asignados</caption>
			<thead>
				<tr>
					<td>Juridico: <?php if (is_null($resj)) { if ($rows2=='0') { ?> sin asignar
							<?php }else{ ?><a href="asignar_reporteJ.php?id=<?php echo $idReporte;?>">Asignar</a>
							<?php } }else{ echo $resj; if($_SESSION['departamento']==16){ ?>
								<input type="submit" class="button small" name="desj" value="BYE" width="30" height="30">
							<?php } }?> </td>
					<td>Trabajo Social: <?php if (is_null($res)) { if ($rows2=='0') { ?> sin asignar
							<?php }else{ ?><a href="asignar_reporte.php?id=<?php echo $idReporte;?>">Asignar</a>
							<?php } }else{ echo $res; if($_SESSION['departamento']==16){ ?>
								<input type="submit" class="button small" name="dests" value="BYE" width="30" height="30">
							<?php } }?> </td>
					<td>Psicologia: <?php if (is_null($resp)) { if ($rows2=='0') { ?> sin asignar
							<?php }else{ ?><a href="asignar_reporteP.php?id=<?php echo $idReporte;?>">Asignar</a>
							<?php } }else{ echo $resp;if($_SESSION['departamento']==16){ ?>
								<input type="submit" class="button small" name="desps" value="BYE" width="30" height="30">
							<?php } }?></td>
				</tr>
			</thead>
	</table>
			<?php $atendido=$row['atendido'];	  $respoR=$row['respo_reg'];
										//$idD : su depto  $idP : su puesto  $idAsig : quien tiene el reporte
										//$respoR : respo de registro
										if ($idD=='16' and $idP=='1') { //administrador ?>
						<table class="alt">									
							<thead>
								<tr>
									<?php if ($atendido==1) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" onchange="this.form.submit()" checked>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority" value="2" onchange="this.form.submit()">
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" onchange="this.form.submit()">
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4" onchange="this.form.submit()">
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==2) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" onchange="this.form.submit()">
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" onchange="this.form.submit()" checked>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" onchange="this.form.submit()">
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4" onchange="this.form.submit()">
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==3) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" onchange="this.form.submit()">
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" onchange="this.form.submit()">
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" onchange="this.form.submit()" checked>
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4" onchange="this.form.submit()">
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==4) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" onchange="this.form.submit()">
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" onchange="this.form.submit()">
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" onchange="this.form.submit()">
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority" value="4" onchange="this.form.submit()" checked>
										<label for="AP">Atendido positivo</label></th>		
									<?php } ?>																	
								</tr>
							</thead>
						</table>
				<?php }else if ($respoR=$idDEPTO) { //respo de reg reporte ?>
						<table class="alt">									
							<thead>
								<tr>
									<?php if ($atendido==1) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" onchange="this.form.submit()" checked disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority" value="2" onchange="this.form.submit()">
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" onchange="this.form.submit()">
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4" onchange="this.form.submit()">
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==2) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" checked disabled>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3">
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4">
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==3) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" disabled>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" checked disabled>
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4" >
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==4) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" disabled>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" disabled>
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority" value="4" checked disabled>
										<label for="AP">Atendido positivo</label></th>		
									<?php } ?>																					
								</tr>
							</thead>
						</table>
				<?php }else if ($asig==$idDEPTO or $asigp==$idDEPTO or $asigj=$idDEPTO) { //si es tu reporte ?>
						<table class="alt">									
							<thead>
								<tr>
									<?php if ($atendido==1) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" onchange="this.form.submit()" checked disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority" value="2" onchange="this.form.submit()">
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" onchange="this.form.submit()">
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4" onchange="this.form.submit()">
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==2) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" onchange="this.form.submit()" disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" onchange="this.form.submit()" checked disabled>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" onchange="this.form.submit()">
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4" onchange="this.form.submit()">
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==3) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" onchange="this.form.submit()" disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" onchange="this.form.submit()" disabled>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" onchange="this.form.submit()" checked disabled>
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4" onchange="this.form.submit()">
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==4) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" onchange="this.form.submit()" disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" onchange="this.form.submit()" disabled>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" onchange="this.form.submit()" disabled>
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority" value="4" onchange="this.form.submit()" checked disabled>
										<label for="AP">Atendido positivo</label></th>		
									<?php } ?>																					
								</tr>
							</thead>
						</table>																 
										<?php }else { //no es tu reporte ?>
						<table class="alt">									
							<thead>
								<tr>
									<?php if ($atendido==1) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1"  checked disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority" value="2"  disabled>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3"  disabled>
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4"  disabled>
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==2) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" checked disabled>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" disabled>
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4" disabled>
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==3) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" disabled>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" checked disabled>
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority"value="4" disabled>
										<label for="AP">Atendido positivo</label></th>		
									<?php }elseif ($atendido==4) { ?>
<th><input type="radio" id="NA" name="demo-priority" value="1" disabled>
										<label for="NA">No atendido</label></th>
<th><input type="radio" id="EP" name="demo-priority"value="2" disabled>
										<label for="EP">En proceso</label></th>
<th><input type="radio" id="AN" name="demo-priority"value="3" disabled>
										<label for="AN">Atendido negativo</label></th>
<th><input type="radio" id="AP" name="demo-priority" value="4" checked disabled>
										<label for="AP">Atendido positivo</label></th>		
									<?php } ?>																
								</tr>
							</thead>
						</table>

										<?php } ?>	 			
								
									</ul>
						<?php if ($asig>0 or $asigp>0) { 
							$xv="SELECT id from part1ac where id_reporte='$idReporte'";
							$exv=$mysqli->query($xv);							
							$can=$exv->num_rows;
							
							if ($can>0) { ?>							
								<input class="button special fit small" type="button" name="" value="acercamientos" onclick="location='acercamiento.php?id=<?php echo $idReporte; ?>'">							 	
							<?php  }else { 
								if ($asig==$idDEPTO or $asigp==$idDEPTO or $_SESSION['departamento']==16) { ?>
								<input class="button special fit small" type="button" name="" value="Registrar acercamientos" onclick="location='num_nna_ac.php?id=<?php echo $idReporte; ?>'">
						<?php } }  } ?>
						<?php if($atendido==4){ 
								$fcap="SELECT id, folio_c from casos where id_reporte='$idReporte'";
								$efcap=$mysqli->query($fcap);
								$cro=$efcap->num_rows;
								while ($row=$efcap->fetch_assoc()) {
									$cas=$row['folio_c'];
									$idCaso=$row['id'];
								}
							if ($cro>0) { ?>
								<div class="12u$">
								<h4><?php echo 'Folio de Caso: '?><a href="perfil_caso.php?id=<?php $idCaso ?>"><?php echo $cas; ?></a></h4>
								</div>
							<?php }else{ ?>
						
						<div class="12u$">
							<input type="button" value="generar caso" onclick="location='reg_caso.php?idR=<?php echo $idReporte; ?>'" class="button fit small">
						</div>
						<?php }  } ?>
								</form>
								</div>
							</div>
								<?php  }  while ($row=$esql3->fetch_assoc()) { ?>
									
									
					
							<div class="12u$">
								<div class="box">
									<ul class="alt">
									<div class="uniform row">
										<div class="12u 12u$(small)">
										<li><h4>Folio del reporte: <?php echo $row['folio'];  ?></h4> </li>
										</div>
										<div class="6u 12u$(small)">
										<li><h4>Fecha: <?php echo $row['fecha'];  ?> </h4></li>
										<li><h4>Tipo de maltrato: <?php echo $row['maltrato'];  ?> </h4></li>
										</div>
										<div class="6u 12u$(small)">
										<li><h4>Forma de recepcion: <?php echo $row['recepcion'];  ?> </h4></li>
										<li><h4>Distrito: <?php echo $row['distrits'];  ?> </h4></li>
										</div>
									</div>
									</ul>
								</div>
							</div>
							<div class="12u$">
								<div class="box">
									<ul class="alt">
										<li><h4>NNA identificados: </h4><?php echo $row['nom_nna'];  ?> </li>
										<li><h4>Edad: </h4><?php echo $row['edad_nna'];  ?> </li>
										<li><h4>Fecha de nacimiento: </h4><?php echo $row['fn_nna'];  ?> </li>
										<li><h4>Lugar de nacimiento: </h4><?php echo $row['lugarnac_nna'];  ?> </li>
										<li><h4>Lugar de registro: </h4><?php echo $row['lugarreg_nna'];  ?> </li>
									</ul>
								</div>
							</div>
							<div class="12u$">
								<div class="box">
									<ul class="alt">
										<li><h4>Persona que reporto: </h4><?php echo $row['persona_reporte'];  ?> </li>
										<li><h4>Narración de lo sucedido: </h4><?php echo $row['narracion'];  ?> </li>
										<li><h4>Ubicación: </h4><?php echo 'Municipio '.$row['municipio'].', Localidad '.$row['localidad'].', Calle '.$row['calle'].', Referencias '.$row['ubicacion'];  ?> </li>
										<li><h4>Otros datos u observaciones relevantes: </h4><?php echo $row['otros_datos'];  ?> </li>
										<li><h4>Responsable de registro: </h4><?php echo $row['responsable'];  ?> </li>	
									</ul>
								</div>
							</div>

						</div> <br>
							<?php } ?>
						
						
						</div>

					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">

						<?php if($_SESSION['departamento']==7) { ?> 
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
										
										<li><span class="opener">Usuarios</span>
											<ul>
												<li><a href="registro_usuarios.php">Alta</a></li>
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" >Cerrar sesión</a></li>
									</ul>
								</nav>
														
								<?php }elseif ($_SESSION['departamento']==16) { ?>
							
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												<li><a href="registro_personal.php">Alta</a></li>
												
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" >Cerrar sesión</a></li>
									</ul>
								</nav>						
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
										
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" >Cerrar sesión</a></li>
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
									<p class="copyright">&copy; Ing. Ivan Flores Navarro. </p>
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