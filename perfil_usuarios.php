<?php
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$idUsuario = $_GET['id'];

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

	$sql = "SELECT id_personal FROM departamentos WHERE id= '$idDEPTO'";	
	$result=$mysqli->query($sql);
	
	while($row=$result->fetch_assoc()){
		$mi=$row['id_personal'];
	}	
	
	$query="SELECT  usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, usuarios.curp, 
	date_format(usuarios.fecha_nacimiento,'%d/%m/%Y') as fecha_nac, sexo.sexo, usuarios.direccion, estados.estado, usuarios.telefono_fijo, 
	usuarios.telefono_movil, municipios.municipio, localidades.localidad, usuarios.afrodescendiente,
	usuarios.indigena, cat.estado_civil, usuarios.correo, usuarios.llegoAlEdo, usuarios.origen, usuarios.lugOrigen, violentado, tipoViolencia, date_format(usuarios.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_registro
	FROM usuarios
	left join sexo on sexo.id=usuarios.id_sexo
	left join municipios on municipios.id=usuarios.id_mun 
	left join localidades on localidades.id=usuarios.id_loc
	left join estados on estados.id=usuarios.id_entidad
	left join cat_estado_civil cat on cat.id=usuarios.id_estado_civil
	WHERE usuarios.id='$idUsuario'";
	$rUsuario=$mysqli->query($query);
	while ($rwUsuario=$rUsuario->fetch_assoc()) {
		$nombre=$rwUsuario['nombre'];
		$ape1=$rwUsuario['apellido_p'];
		$ape2=$rwUsuario['apellido_m'];
		$curp=$rwUsuario['curp'];
		$fechaNac=$rwUsuario['fecha_nac'];
		$sexo=$rwUsuario['sexo'];
		$direccion=$rwUsuario['direccion'];
		$estado=$rwUsuario['estado'];
		$telFijo=$rwUsuario['telefono_fijo'];
		$telMovil=$rwUsuario['telefono_movil'];
		$municipio=$rwUsuario['municipio'];
		$localidad=$rwUsuario['localidad'];
		$afro=$rwUsuario['afrodescendiente'];
		$indigena=$rwUsuario['indigena'];
		$llegoAlEdo = $rwUsuario['llegoAlEdo'];
		$origen = $rwUsuario['origen'];
		$lugOrigen = $rwUsuario['lugOrigen'];
		$violentado = $rwUsuario['violentado'];
		$tipoViolencia = $rwUsuario['tipoViolencia'];
		$estCivil=$rwUsuario['estado_civil'];
		$correo=$rwUsuario['correo'];
		$fecha_registro = $rwUsuario['fecha_registro'];
	}
	$resultado=$mysqli->query($query);
	$resultado2=$mysqli->query($query);

	if($afro==1)
		$afro="SI";
	elseif($afro==0)
		$afro="NO";
	if($indigena==1)
		$indigena="SI";
	else $indigena="NO";
	if($llegoAlEdo==1)
		$llegoAlEdo="SI";
	elseif($llegoAlEdo===0)
		$llegoAlEdo="NO";
	else $llegoAlEdo="Se desconoce";
	if($violentado==1)
		$violentado="SI";
	elseif($violentado===0) 
		$violentado="NO";
	else $violentado="Se desconoce"
	// investigar substr para comprobrar el formato de la fecha de nacimiento 

?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Perfil usuario</title>
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
						</div>
					</div> <br>	
					<h1><?= $nombre." ".$ape1." ".$ape2  ?> </h1>
					<br><br>
					<ul class="alt">
						<li><strong>CURP: </strong><?= $curp ?></li>					
						<li><strong>Fecha de nacimiento: </strong><?php if($fechaNac=='01/01/1900') echo "SIN DATO"; else echo $fechaNac; ?></li>
						<li><strong>Sexo: </strong><?= $sexo ?> </li>
						<li><strong>Estado civil y/o familiar: </strong><?= $estCivil ?></li>
						<li><strong>Dirección: </strong><?= $direccion ?> </li>			
						<li><strong>Estado: </strong><?= $estado ?> </li>
						<li><strong>Municipio:</strong> <?= $municipio ?></li>
						<li><strong>Localidad:</strong> <?= $localidad ?></li>
						<li><strong>Teléfono fijo: </strong><?= $telFijo?> &nbsp <strong>Teléfono movil: </strong><?= $telMovil ?> </li>
						<li><strong>Email: </strong><?= $correo ?></li>
						<li><strong>¿Se considera parte de una comunidad indígena?: </strong><?= $indigena ?></li>
						<li><strong>¿Se considera afroamericano o afrodescendiente?: </strong><?= $afro ?></li>
						<li><strong>¿Es persona que abandona el lugar en que nació y llega al Estado para establecerse en él de manera temporal o definitiva?: </strong><?= $llegoAlEdo ?></li>
						<li> <strong>Origen: </strong><?=$origen?>&nbsp;&nbsp;&nbsp; <strong>  Lugar: </strong><?= $lugOrigen ?></li>
						<li><strong>¿En los últimos tres meses sufrió algún tipo de violencia?: </strong><?= $violentado ?>&nbsp;&nbsp;&nbsp;<strong>¿De que tipo?: </strong><?= $tipoViolencia ?></li>
						<li><strong>Fecha de registro: </strong><?=$fecha_registro?>
					</ul>
					<input class="button fit" type="button" name="cancelar" value="Historial"  onclick="location='historial_usuario.php?id=<?php echo $idUsuario; ?>'">
					<?php
						if ($mi==2 or $mi==5 or $mi==1) {
							$conV="SELECT id_usuario FROM historial WHERE id_usuario='$idUsuario' AND fecha_salida is null";
							$dd=$mysqli->query($conV);
							$rows = $dd->num_rows;
							if($rows > 0) {	?>
								<input type="button" name="asignar_curso" class="button special fit" value="usuario en visita" onclick="location='visitas.php?id=<?php echo $idUsuario; ?>'" disabled>
							<?php } else { ?>
								<input type="button" name="asignar_curso" class="button special fit" value="registrar visita" onclick="location='visitas.php?id=<?php echo $idUsuario;?>'">
							<?php } ?>
							<input class="button fit" type="button" name="cancelar" value="Actualizar"  onclick="location='editar_usuario.php?id=<?php echo $idUsuario;?>'">
						<?php } if ($_SESSION['departamento']==16) { ?>
							<input class="button special fit" type="button" name="cancelar" value="Eliminar"  onclick="location='eliminar_usuario.php?id=<?php echo $idUsuario;?>'">
						<?php }
					 ?>
				</div>
			</div> <!--cierre del main-->

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
			</div><!--cierre menu-->

		</div> <!--cierre del wrapper-->

		<!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>

	</body>
</html>