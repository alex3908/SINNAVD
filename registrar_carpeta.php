<?php
	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}	
	$idDEPTO = $_SESSION['id'];
	$idCaso=$_GET['id'];

	$fecha= date ("j/n/Y");
	$dis="SELECT id, distrits from distritos";
	$edis=$mysqli->query($dis);
	$del="SELECT id, delito from delitos order by delito";
	$edel=$mysqli->query($del);

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

	$qPc="SELECT id_posible_caso from relacion_pc_caso where id_caso=$idCaso";
	$rPc=$mysqli->query($qPc);
	$error = '';
	$sqlbene="SELECT nna.id, nna.nombre, nna.apellido_p, nna.apellido_m, nna_caso.estado 
	from nna INNER JOIN nna_caso ON nna_caso.id_nna=nna.id
	where nna_caso.id_caso='$idCaso'  and nna_caso.estado='NE' AND nna.activo=1";
	$esqlbene=$mysqli->query($sqlbene);

	$qreportes="SELECT r.id, r.folio from reportes_vd r inner join posible_caso pc on pc.id=r.id_posible_caso
	inner join relacion_pc_caso rel on rel.id_posible_caso=pc.id
	inner join casos c on c.id=rel.id_caso where c.id='$idCaso' and r.activo=1";
	$rReportes=$mysqli->query($qreportes);
	$bandera = false;
	if(!empty($_POST['registar']))
	{
		$nuc = mysqli_real_escape_string($mysqli,$_POST['nuc']);
		$distrits = $_POST['distrits'];
		$municipio = $_POST['municipio'];
		$fecha_ini = $_POST['fecha_ini'];
		if($fecha_ini=='') $fecha_ini='1900-01-01';
		$nnas = $_POST['beneficiario'];
		$reportes = $_POST['reportes'];
		$delito = mysqli_real_escape_string($mysqli,$_POST['delito']);
			
		$mesa = mysqli_real_escape_string($mysqli,$_POST['mesa']);
		$estado = $_POST['estado'];
		$fecha= date("Y-m-d H:i:s", time()); 
		//$fecha= 'Error';
		$error = '';

		
		$sqlUser = "SELECT id FROM carpeta_inv WHERE nuc = '$nuc'";
		$resultUser=$mysqli->query($sqlUser);
		$rows = $resultUser->num_rows;

		if($rows > 0) {
			?>
			<script type="text/javascript">alert('Carpeta ya registrada');</script>
			
			<?php } else {
				$qRegCarp = "";
				$qRegCarpeta = "CALL registar_carpeta_inv($idCaso, '$nuc', $distrits, $municipio, '$fecha_ini', $delito, '$mesa', $estado,  '$fecha', $idDEPTO)";
				$rRegCarpeta = $mysqli->query($qRegCarpeta);

			/*$sqlNino = "INSERT INTO carpeta_inv (id_caso,nuc,fecha_ini,distrito,municipio_d,id_delito,imputado,relacion,mesa,estado,respo_estado,fecha_estado,fecha_act,fecha_reg,respo_reg,tipo_pross,fecha_tipo,respo_tipo,asignado,respo_asig,fecha_asig) VALUES ('$idCaso', '$nuc', '$fecha_ini', '$distrits', '$municipio', '$delito', '$imputado', '$relacion_i', '$mesa', '$estado', '$idDEPTO', '$fecha', '0', '$fecha', '$idDEPTO', '0', '0', '$idDEPTO','0','0','0')";
			$resultNino = $mysqli->query($sqlNino);
			echo $sqlNino;*/
			//$stmt->execute();
			if(!$rRegCarpeta){
				$errnov= mysqli_real_escape_string($mysqli,$mysqli->errno);
				$errorv= mysqli_real_escape_string($mysqli,$mysqli->error);
				$url= $_SERVER["REQUEST_URI"];
				$qError = "INSERT INTO historico_errores (archivo, var_errno, var_error, usuario) values ('$url','$errnov', '$errorv', '$idDEPTO')";
				$rError=$mysqli->query($qError);
				$qConError="SELECT max(id) from historico_errores where usuario=$idDEPTO and archivo='$url'";
				$rConError=$mysqli->query($qConError);
				$idError=implode($rConError->fetch_assoc());
				$error= "Error al registrar, identificador: ".$idError;
			}
			else {
				$qIdCarpeta="SELECT id from carpeta_inv where nuc='$nuc'";
				$rIdCarpeta=$mysqli->query($qIdCarpeta);
				$idCarpeta=implode($rIdCarpeta->fetch_assoc());
				if(!empty($nnas)){
					for($i=0; $i<count($nnas); $i++){ //relacionar con victimas si las hay
						$idNna=$nnas[$i];
						$relCarpVict="INSERT INTO victimas_c_inv ( id_carp, id_nna, estado, res_reg) 
						values ($idCarpeta, $idNna, 'NE', $idDEPTO)";
						$qRelCarpVict=$mysqli->query($relCarpVict);
					}					
				}
				if(!empty($reportes)){ //relaciona con los reportes del cual deriva si los hay
					for($i=0; $i<count($reportes); $i++){ 
						$idRep=$reportes[$i];
						$relCarpRep="INSERT INTO relacion_carpeta_reporte ( id_carpeta, id_reporte) 
						values ($idCarpeta, $idRep)";
						$qRelCarpRep=$mysqli->query($relCarpRep);
					}		
				}	
				header("Location: perfil_carpeta.php?id=$idCarpeta");		
			}
		
			
		}
}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Registrar carpeta</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		
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
					<h1>Carpeta de investigación</h1>
					<br />
					<div style = "font-size:16px; color:#cc0000;"><?php echo $error; ?></div>
					<div class="box">
						<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
							<div class="row uniform">
								<div class="3u 12u$(xsmall)">
									<label for="nuc">NUC:</label>
									<input id="nuc" name="nuc" pattern="[0-9A-Z-:]{6,42}" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
								</div>
								<div class="3u 12u$(xsmall)">
									<label for="distrits">Distrito de seguimiento: </label>
									<div class="select-wrapper">
										<select id="distrits" name="distrits" onchange="red(this);" required>
											<option value="">--Seleccione--</option>
											<?php while ($row=$edis->fetch_assoc()) { ?>
												<option value="<?php echo $row['id']; ?>"><?php echo $row['distrits']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="3u 12u$(xsmall)">
									<label>Municipio del delito</label>
									<div class="select-wrapper">
										<select id="municipio" name="municipio"  required>
											<option value="">--Seleccione--</option>
											<?php 
											$mun="SELECT id, municipio from municipios";
											$emun=$mysqli->query($mun);
											while ($row=$emun->fetch_assoc()) { ?>
												<option value="<?php echo $row['id']; ?>"><?php echo $row['municipio']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="3u 12u$(xsmall)">
									<label for="fecha_ini">Fecha de inicio: </label>
									<input id="fecha_ini" name="fecha_ini"  type="date" >	
								</div>
							</div>
							<div class="row uniform">
								<div class="4u">
									<label for="nnas">NNA vinculados</label>
									<div class="select-wrapper">
										<select name="beneficiario[]" multiple="multiple" size="5" style="height:90px; width:100%"  >
											<?php while ($rw=$esqlbene->fetch_assoc()) { ?>
												<option value="<?php echo $rw['id'];?>"><?php echo $rw['nombre']." ".$rw['apellido_p']." ".$rw['apellido_m'];?></option>
											<?php }  ?>
										</select>
									</div>
								</div>
								<div class="3u">
									<label for="reporte">Reporte del que deriva</label>
									<div class="select-wrapper">
										<select name="reportes[]" multiple="multiple" size="5" style="height:90px; width:100%"  >
											<?php while ($rwRep=$rReportes->fetch_assoc()) { ?>
												<option value="<?php echo $rwRep['id'];?>"><?php echo $rwRep['folio'];?></option>
											<?php }  ?>
										</select>
									</div>
								</div>
								<div class="5u 12u$(xsmall)">
									<label for="delito">Delito: </label>
									<div class="select-wrapper">
										<select id="delito" name="delito" required>
											<option value="">--Seleccione--</option>
											<?php while ($row=$edel->fetch_assoc()) { ?>
												<option value="<?php echo $row['id']; ?>"><?php echo $row['delito']; ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
						
							<div class="row uniform">
								<div class="4u 12u$(xsmall)">
								<label for="mesa">Mesa: </label>
									<input id="mesa" name="mesa" maxlength="40" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
								</div>
								<div class="4u 12u$(xsmall)">
									<label for="estado">Estado actual</label>
									<div class="select-wrapper">
										<select id="estado" name="estado" required>
											<option value="">--Seleccione--</option>
											<option value="20">INVESTIGACION INICIAL</option>
											<option value="40">INVESTIGACION COMPLEMENTARIA</option>
											<option value="60">INTERMEDIA</option>
											<option value="80">JUICIO</option>
											<option value="100">EJECUCION</option>
										</select>
									</div>
								</div>
								<div class="4u 12u$(xsmall)">
									<label for="fecha_reg">Fecha de registro</label>
									<input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $fecha; ?>" placeholder="fecha_reg"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
								</div>
							</div>
							<div class="row uniform">
								<div class="12u$">
									<ul class="actions">
										<input class="button special fit" name="registar" type="submit" value="Registrar" >
										<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_caso.php?id=<?php echo $idCaso; ?>'" >
									</ul>
								</div>
							</div>
						</form>
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