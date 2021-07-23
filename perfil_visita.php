<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	$hoy = date('Y-m-d');
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
 $idVisita= $_GET['id'];
	
$qVisita = "SELECT historial.responsable as id_responsable, historial.id_departamento, nombre, apellido_p, apellido_m, departamento, date_format(fecha_ingreso, '%d/%m/%Y %H:%i:%s') as fecha, date_format(fecha_ingreso, '%Y-%m-%d') as fecha_ingreso, u.responsable, asunto FROM historial inner join usuarios on usuarios.id=historial.id_usuario inner join depto on depto.id=historial.id_departamento left join departamentos u on u.id=historial.responsable where historial.id=$idVisita";
$qVisita = $mysqli->query($qVisita);
	while ($rw=$qVisita->fetch_assoc()) {
		$usuario=$rw['nombre']." ".$rw['apellido_p']." ".$rw['apellido_m'];
		$id_departamento = $rw['id_departamento'];
		$departamento=$rw['departamento'];
		$fecha = $rw['fecha'];
		$fecha_ingreso = $rw['fecha_ingreso'];
		$responsable = $rw['responsable'];
		$id_responsable = $rw['id_responsable'];
		$asunto = $rw['asunto'];
	}


	
	$sql = "SELECT id, departamento FROM depto WHERE id!=16 && id!=7 and id!=12"; // control de informacion, recepcion, cordinacion de subprocus
	$result=$mysqli->query($sql);
	$canalizar ="SELECT id, responsable FROM departamentos WHERE id!=16 && id!=7 and id!=12 and activo=1";

	$qCanalizar = $mysqli->query($canalizar);
//echo "$idPersonal <br> $hoy <br> $fecha_ingreso <br> $responsable";


?>
<!DOCTYPE HTML>
<html> 
	<head lang="es-ES">
		<title>Perfil</title>
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
					<form id="DatosVisita" name="DatosVisita" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						<div class="box">
							<h3><?=$usuario?> EN VISITA</h3>
							<?php if($idPersonal==1 and $idDepartamento==16) { //cuando es control de informacion y admin puede cambiar todo ?>
								<div class="row uniform">
									<div class="4u $12u(xsmall)">
										<label>Fecha de entrada</label>
										<input type="text" name="fecha" value="<?=$fecha?>" disabled>
									</div>
									<div class="5u $12u(xsmall)">
										<label>Departamento</label>
										<div class="select-wrapper">
											<select id="departamento" name="departamento" required="true">
												<option value="<?=$id_departamento?>"><?=$departamento ?></option>
												<?php while($row = $result->fetch_assoc()){ ?>
													<option value="<?php echo $row['id']; ?>"><?php echo $row['departamento']; ?></option>
												<?php }?>
											</select>
										</div>
									</div>
									<div class="3u $12u(xsmall)">
										<label>Tipo</label>
										<div class="select-wrapper">
											<select id="tipo" name="tipo" required="true">
												<option value="<?=$asunto?>"><?=$asunto ?></option>
												<option value="INICIAL">INICIAL</option>
												<option value="SUBSECUENTE">SUBSECUENTE</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row uniform">
									<div class="12u $12u(xsmall)">
										<label>Responsable</label>
										<div class="select-wrapper">
											<select id="responsable" name="responsable" required="true">
												<?php if(empty($responsable)) { ?>
													<option value="0">Seleccione</option>
												<?php } else { ?>
													<option value="<?=$id_responsable?>"><?=$responsable ?></option>
												<?php } while($row = $qCanalizar->fetch_assoc()){ ?>
													<option value="<?php echo $row['id']; ?>"><?php echo $row['responsable']; ?></option>
												<?php }?>
											</select>
										</div>
									</div>
								</div>
							
							<?php } elseif(($idPersonal==2 or $idPersonal==5) and $hoy==$fecha_ingreso and empty($responsable)) { // cuando es recepcionista o subprocu solo puede cambiar si se acaba de registrar la visita y si aun no ha sido canalizado ?>
								<div class="row uniform">
									<div class="4u $12u(xsmall)">
										<label>Fecha de entrada</label>
										<input type="text" name="fecha" value="<?=$fecha?>" disabled>
									</div>
									<div class="5u $12u(xsmall)">
										<label>Departamento</label>
										<div class="select-wrapper">
											<select id="departamento" name="departamento" required="true">
												<option value="<?=$id_departamento?>"><?=$departamento ?></option>
												<?php while($row = $result->fetch_assoc()){ ?>
													<option value="<?php echo $row['id']; ?>"><?php echo $row['departamento']; ?></option>
												<?php }?>
											</select>
										</div>
									</div>
									<div class="3u $12u(xsmall)">
										<label>Tipo</label>
										<div class="select-wrapper">
											<select id="tipo" name="tipo" required="true">
												<option value="<?=$asunto?>"><?=$asunto ?></option>
												<option value="INICIAL">INICIAL</option>
												<option value="SUBSECUENTE">SUBSECUENTE</option>
											</select>
										</div>
									</div>
								</div>
								<div class="row uniform">
									<div class="12u $12u(xsmall)">
										<label>Responsable</label>
										<input type="text" name="res" value="No canalizado" disabled="true">
										<input type="hidden" name="responsable" value="<?=$id_responsable?>" disabled="true">
									</div>
								</div>
							<?php } elseif($idPersonal==3 or $idPersonal==5) { ?>
								<div class="row uniform">
									<div class="4u $12u(xsmall)">
										<label>Fecha de entrada</label>
										<input type="text" name="fecha" value="<?=$fecha?>" disabled>
									</div>
									<div class="5u $12u(xsmall)">
										<label>Departamento</label>
										<div class="select-wrapper">
											<select id="departamento" name="departamento" required="true">
												<option value="<?=$id_departamento?>"><?=$departamento ?></option>
												
											</select>
										</div>
									</div>
									<div class="3u $12u(xsmall)">
										<label>Tipo</label>
										<div class="select-wrapper">
											<select id="tipo" name="tipo" required="true">
												<option value="<?=$asunto?>"><?=$asunto ?></option>
												
											</select>
										</div>
									</div>
								</div>
								<div class="row uniform">
									<div class="12u $12u(xsmall)">
										<label>Responsable</label>
										<div class="select-wrapper">
											<select id="responsable" name="responsable" required="true">
												<?php if(empty($responsable)) { ?>
													<option value="0">Seleccione</option>
												<?php } else { ?>
													<option value="<?=$id_responsable?>"><?=$responsable ?></option>
												<?php } while($row = $qCanalizar->fetch_assoc()){ ?>
													<option value="<?php echo $row['id']; ?>"><?php echo $row['responsable']; ?></option>
												<?php }?>
											</select>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
						<div class="row uniform">
						<div class="6u 12u$(xsmall)">
									<input type="button" name="asignar_curso" class="button fit" value="Aceptar" >
							</div>
													
								<div class="6u 12u$(xsmall)">
									<input type="button" name="eliminar" value="Cancelar" class="button special fit">
								</div>
							</div>
					</form>
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