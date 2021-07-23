<?php	
	session_start();
	require 'conexion.php';
	require 'validar_fecha.php';

	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];
	$fecha_reg=date("Y-m-d");
	$error='';
	$query="SELECT id, municipio from municipios where id!='0'";
	$equery=$mysqli->query($query);

	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}

	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
	$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;

	if(!empty($_POST))
	{
		$error='';
		$hoy=date("Y-m-d H:i:s");
		$nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$fecha=mysqli_real_escape_string($mysqli,$_POST['fecha']);
		if(substr($fecha,4,1) !='-' or substr($fecha,7,1) !='-') //valida que la fecha este en el formato aaaa-mm-dd 
		{
				$fecha = validar_fecha($fecha);
		}
		$mun=$_POST['mun'];
		$na=$_POST['na'];
		$ni=$_POST['ni'];
		$adm=$_POST['adm'];
		$adh=$_POST['adh'];
		$am=$_POST['am'];
		$ah=$_POST['ah'];
		if($fecha!='0') {
			$update="INSERT into ccpi (actividad, fecha, na, ni, adm, adh, am, ah, municipio, fecha_reg, respo_reg) values ('$nombre','$fecha','$na','$ni','$adm','$adh','$am','$ah','$mun','$hoy','$idDEPTO')";
			$eupdate=$mysqli->query($update);
			if($eupdate) {
				echo "<script>
				alert('Actividad registrada');   
				window.location= 'reg_actccpi.php'
				</script>";
				$error ='';
			} else {
				$error="Error al registrar, vuelva a intentarlo";
			}
		} else $error= "Ingrese una fecha valida (dd/mm/aaaa)";
	} 
	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>CCPI</title>
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
						<div class="inner"><br> <br> 	
			<div class="uniform row">
				
			<div class="12u 12u$(xsmall)">
				<div class="box">
					<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
					<h2>Registro de actividad</h2>
					<div style = "font-size:16px; color:#cc0000;"><?= $error ?></div>
					<table class="alt">
						
							<tbody>
								<tr>
									<td colspan="4">Nombre del actividad<input type="text" name="nombre" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></td>
									<td>Municipio<select id="mun" class="form-control" name="mun" required>
												<?php while ($row=$equery->fetch_assoc()) { ?>
     											<option value="<?php echo $row['id']; ?>"><?php echo $row['municipio']; ?></option>
												<?php } ?>
    										</select></td>
									<td>Fecha<input max="<?=$fecha_reg?>" type="date" value="<?=$fecha_reg?>" name="fecha" placeholder="DD/MM/AAAA"></td>

								</tr>
							</tbody>				
							<tbody>
								<tr>
									<td colspan="6">Numero de asistentes</td>
								</tr>
								<tr>
									<td colspan="2">de 0 a 11 años</td>
									<td colspan="2">de 12 a 17 años</td>
									<td colspan="2">de 18 en adelante</td>
									
								</tr>
								<tr>
									<td>Niñas<input type="number" value="0" name="ni"></td>
									<td>Niños<input type="number"  value="0" name="na"></td>
									<td>Adolescentes mujeres<input type="number"  value="0" name="adm"></td>
									<td>Adolescentes hombres<input type="number"  value="0" name="adh"></td>
									<td>Mujeres<input type="number"  value="0" name="am"></td>
									<td>Hombres<input type="number"  value="0" name="ah"></td>
								</tr>
								<tr>
									<td colspan="6">
									<input class="button special fit" name="registar" type="submit" value="Guardar" ></td>
								</tr>
								
							</tbody>
						</table>
					
				</form>
			</div>
			</div>
			
			<div class="12u 12u$(xsmall)">				
					<div class="table-wrapper">
						<?php $mos="SELECT ccpi.id, ccpi.actividad, municipios.municipio ,date_format(ccpi.fecha,'%d/%m/%Y') as fecha, ccpi.na, ccpi.ni, ccpi.adm, ccpi.adh, ccpi.am, ccpi.ah, ccpi.na+ccpi.ni+ccpi.adm+ccpi.adh+ccpi.am+ccpi.ah as total from ccpi, municipios where ccpi.municipio=municipios.id order by ccpi.fecha desc";
								$emos=$mysqli->query($mos);
								$totalact=$emos->num_rows; ?>
						<table class="alt">
						<thead>
							<tr>
								<td colspan="6"><h3>Historial de actividades: <?php echo $totalact; ?></h3></td>							
							</tr>
						</thead>
							<thead>
								<tr>
									<th>Fecha</th>
									<th>Actividad</th>
									<th>Municipio</th>
									<th>Niñas</th>
									<th>Niños</th>
									<th>Adolescentes mujeres</th>
									<th>Adolescentes hombres</th>
									<th>Mujeres</th>
									<th>Hombres</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<?php while ($row=$emos->fetch_assoc()) { ?>
								<tr>
									<td><?php echo $row['fecha']; ?></td>
									<td><?php echo $row['actividad']; ?></td>
									<td><?php echo $row['municipio']; ?></td>
									<td><?php echo $row['na']; ?></td>
									<td><?php echo $row['ni']; ?></td>
									<td><?php echo $row['adm']; ?></td>
									<td><?php echo $row['adh']; ?></td>
									<td><?php echo $row['am']; ?></td>
									<td><?php echo $row['ah']; ?></td>
									<td><?php echo $row['total'] ?></td>
								</tr>
								<?php } ?>
							</tbody>
						
						</table>
					</div>
			</div>
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

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>