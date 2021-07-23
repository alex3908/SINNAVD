<?php
	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idNNA = $_GET['id'];

	$qcarpe="SELECT cp.nuc, cp.id FROM victimas_c_inv v 
left join carpeta_inv cp on cp.id=v.id_carp
where v.id_nna=$idNNA";
$rcarp=$mysqli->query($qcarpe);
	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}

	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT id from reportes_vd where atendido='1' 
	and (asignado='$idDEPTO' or asignado_psic='$idDEPTO')";
	$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;
	$vald="SELECT id from departamentos where id='$idDEPTO' and (id_depto='13' OR id_depto='16') 
	or (id_depto='10' and casp='1')";
	$evald=$mysqli->query($vald);
	$rows3=$evald->num_rows;

	$de="SELECT id_depto from departamentos where id='$idDEPTO'";
	$ede=$mysqli->query($de);
	while ($row=$ede->fetch_assoc()) {
		$id_ddd=$row['id_depto'];
	}
	$sqlnna="SELECT nna.folio, nna.indigena, nna.afrodescendiente, nna.migrante, nna.llegoAlEstado, nna.origen, nna.lugOrigen, nna.violentado, nna.tipoViolencia, nna.nombre, nna.apellido_p, nna.curp, nna.apellido_m, 
	date_format(nna.fecha_nacimiento, '%d/%m/%Y') as fecha_nac, nna.sexo, nna.responna, 
	nna.parentesco, nna.direccion, nna.telefono, nna.observaciones, nna.lugar_nac, nna.lugar_reg, 
	departamentos.responsable, nna.nna_ex, municipios.municipio, localidades.localidad, 
	date_format(nna.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, 
	nna.validacionRenapo, nacionalidad, docProbatorio, statusCurp, estado_civil, padre_fallecido_covid, madre_fallecida_covid
	from  nna left join  localidades on localidades.id=nna.localidad
	left join  municipios on municipios.id=nna.municipio 
	left join departamentos on departamentos.id=nna.respo_reg
	left join cat_estado_civil on cat_estado_civil.id=nna.id_estado_civil
	where nna.id='$idNNA'";
	$esqlnna=$mysqli->query($sqlnna);
	$qDireccion="SELECT e.estado, m.municipio, l.localidad, h.direccion
	from historico_direcciones_nna h 
	left join nna on nna.id_direccion=h.id
	left join estados e on e.id=h.id_estado
	left join municipios m on m.id=h.municipio
	left join localidades l on l.id=h.localidad
	where nna.id=$idNNA";
	$rDireccion=$mysqli->query($qDireccion);

	$val="SELECT nna_centros.id_centro, centros.nombre, nna_centros.motivo, nna_centros.fecha_ing 
	from centros, nna_centros where centros.id=nna_centros.id_centro and nna_centros.id_nna='$idNNA'";
	$eval=$mysqli->query($val);
	$rows2=$eval->num_rows;

	while ($row=$eval->fetch_assoc()) {
		$nomC=$row['nombre'];
		$idC=$row['id_centro'];
		$motivo=$row['motivo'];
		$fechaI=$row['fecha_ing'];
	}

	if (isset($_POST['padresCovid'])) {
		$fecha=date("Y-m-d H:i:s", time());
		$estado = $_POST['padresCovid']; 
		if($estado==1)
		echo "<script>if(confirm('¿Desea realmente agregar orfandad de PADRE?')){
        document.location='padres_fallecidos_covid.php?idNNA=$idNNA&estado=$estado';}
        </script>"; 
        elseif($estado==2)
        	echo "<script>if(confirm('¿Desea realmente agregar orfandad de MADRE?')){
        document.location='padres_fallecidos_covid.php?idNNA=$idNNA&estado=$estado';}
        </script>"; 
        else echo "<script>if(confirm('¿Desea realmente agregar orfandad de AMBOS PADRES?')){
        document.location='padres_fallecidos_covid.php?idNNA=$idNNA&estado=$estado';}
        </script>"; 
	}

?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Perfil NNA</title>
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
					<div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div> <br>
					<?php while($row=$esqlnna->fetch_assoc()){ ?>
						<div class="row uniform">
							<div class="12u">
								<h1><?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m'];  ?> </h1>
							</div>
						</div>
						<div class="row uniform">
							<table>
								<tr>
									<td colspan="4"><h3>Curp: <?php echo $row['curp']; ?></h3></td>
									<td>
										<?PHP if(empty($row['validacionRenapo'])) { ?>
											<button onclick="location='validarCurp.php?id=<?= $idNNA?>&T=1'">Validar CURP</button>
										<?php } else { ?>
											<button disabled="true">CURP validada</button>
										<?php } ?>
									</td>
								</tr>
								<?php if(!empty($row['validacionRenapo'])) { ?>
									<tr>
										<td colspan="3">
											<strong>Estatus CURP: </strong><?= $row['statusCurp']?>
										</td>
										<td>
											<strong>Nacionalidad: </strong><?= $row['nacionalidad']?>
										</td>
										<td>
											<strong>Doc. probatorio: </strong><?= $row['docProbatorio']?>
										</td>
									</tr>
								<?php } ?>
							</table>
						</div>
						<div class="row uniform">
							<div class="12u">
								<h2>FECHA DE REGISTRO (del nna en el sistema): <?php echo $row['fecha_reg']; ?></h2>
							</div>
						</div>
						<div class="row uniform">
							<div class="12u">
								<STRONG>Responsable de registro: </STRONG><?php echo $row['responsable'];  ?>
							</div>
						</div>
						<div class="row uniform">
							<div class="6u 12u$(xsmall)">
								<div class="box">
									<ul class="alt">
										<li><h4>Folio: </h4><?php echo $row['folio'];  ?> </li>
										<li><h4>Número identificador: </h4><?php echo $idNNA;  ?> </li>
										<li><h4>Sexo: </h4><?php echo $row['sexo'];  ?> </li>
										<li><h4>Fecha de nacimiento: </h4><?php if($row['fecha_nac']=='01/01/1900') echo "Sin registro"; else if(empty($row['fecha_nac'])) 
										echo "Sin dato valido"; else echo $row['fecha_nac'];  ?> </li>		
										<li><h4>Lugar de nacimiento: </h4><?php echo $row['lugar_nac'];  ?> </li>
										<li><h4>Lugar de registro: </h4><?php echo $row['lugar_reg'];  ?> </li>
									</ul>
								</div>
							</div>
							<div class="6u 12u$(xsmall)">
								<div class="box">
									<ul class="alt">
										<li><h4>Responsable actual del NNA: </h4><?php echo $row['responna'];  ?> </li>
										<li><h4>Parentesco: </h4><?php echo $row['parentesco'];  ?> </li>
										<li><h4>Dirección: </h4><?php echo $row['direccion'];  ?> </li>
										<li><h4>Telefono: </h4><?php echo $row['telefono'];  ?> </li>
										<li><h4>Observaciones: </h4><?php echo $row['observaciones'];  ?> </li>
										<li> <button onclick="location='editarRespoNna.php?id=<?= $idNNA?>'">Editar</button></li>
										<li>
											 <form id="padres" name="padres" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
											 <h4>Fallecidos por COVID-19: </h4>
											<?php if($row['padre_fallecido_covid']==1 and $row['madre_fallecida_covid']==0) { ?>
												<input type="radio" id="padreC" name="padresCovid" value="1" onchange="this.form.submit()" checked>
												<label for="padreC">Padre</label>
												<input type="radio" id="madreC" name="padresCovid" value="2" onchange="this.form.submit()" disabled>
												<label for="madreC">Madre</label>
												<input type="radio" id="AmbosPC" name="padresCovid" value="3" onchange="this.form.submit()">
												<label for="AmbosPC">Ambos</label>
											<?php } elseif($row['padre_fallecido_covid']==0 and $row['madre_fallecida_covid']==1) { ?>
												<input type="radio" id="padreC" name="padresCovid" value="1" onchange="this.form.submit()" disabled>
												<label for="padreC">Padre</label>
												<input type="radio" id="madreC" name="padresCovid" value="2" onchange="this.form.submit()" checked >
												<label for="madreC">Madre</label>
												<input type="radio" id="AmbosPC" name="padresCovid" value="3" onchange="this.form.submit()">
												<label for="AmbosPC">Ambos</label>
											<?php } elseif($row['padre_fallecido_covid']==1 and $row['madre_fallecida_covid']==1) { ?>
												<input type="radio" id="padreC" name="padresCovid" value="1" onchange="this.form.submit()" disabled>
												<label for="padreC">Padre</label>
												<input type="radio" id="madreC" name="padresCovid" value="2" onchange="this.form.submit()" disabled>
												<label for="madreC">Madre</label>
												<input type="radio" id="AmbosPC" name="padresCovid" value="3" onchange="this.form.submit()" disabled checked>
												<label for="AmbosPC">Ambos</label>
											<?php } else { ?>
												<input type="radio" id="padreC" name="padresCovid" value="1" onchange="this.form.submit()" >
												<label for="padreC">Padre</label>
												<input type="radio" id="madreC" name="padresCovid" value="2" onchange="this.form.submit()" >
												<label for="madreC">Madre</label>
												<input type="radio" id="AmbosPC" name="padresCovid" value="3" onchange="this.form.submit()"  >
												<label for="AmbosPC">Ambos</label>
											<?php } ?>
										</form>
										</li>
									</ul>
								</div>
							</div>
						</div>
						<br>
						<div class="row uniform">
							<div class="12u">
								<div class="box">
									<div class="row uniform">
										<div class="6u">
											<b>De acuerdo con sus costumbres y tradiciones, ¿se considera parte de una comunidad indígena?</b> <br>
											<?php  if($row['indigena']==0) echo "NO"; else echo "SI" ?>
										</div>
										<div class="6u">
											<b>De acuerdo con sus costumbres y tradiciones, ¿se considera  afroamericano(a) o afrodescendiente?</b> <br>
											<?php  if($row['afrodescendiente']==0) echo "NO"; else echo "SI" ?>
										</div>
									</div>
									<div class="row uniform">
										<div class="6u">
											<b>¿Es persona que abandona el lugar en que nació y llega al Estado para establecerse en él de manera temporal o definitiva?</b> <br>
											<?php  if($row['llegoAlEstado']==0) echo "NO"; else echo "SI" ?>
										</div>
										<div class="3u"><b>Especifique el origen</b>
											<br>
											<?php  if(empty($row['origen']) and $row['migrante']==1) echo "SIN INFORMACIÓN"; elseif($row['migrante']==0) echo "NO APLICA"; else echo $row['origen']; ?>
										</div>
										<div class="3u"><b>Lugar</b>
											<br>
											<?php  if(empty($row['lugOrigen']) and $row['migrante']==1) echo "SIN INFORMACIÓN"; elseif($row['migrante']==0) echo "NO APLICA"; else echo $row['lugOrigen']; ?>
										</div>
									</div>
									<div class="row uniform">
										<div class="4u">
											<b>¿En los últimos tres meses sufrió algún tipo de violencia?</b><br>
											<?php  if($row['violentado']==1) echo "SI";elseif(empty($row['tipoViolencia'])) echo "SIN INFORMACIÓN"; elseif($row['violentado']==0) echo 'NO';  ?>
										</div>
										<div class="3u">
											<b>Tipo</b><br>
											<?php  if($row['violentado']==1 and empty($row['tipoViolencia'])) echo 'SIN INFORMACIÓN'; else echo $row['tipoViolencia'] ?>
										</div>
										<div class="3u">
											<b>Estado cívil</b><br>
											<?= $row['estado_civil'] ?>
										</div>
										<div class="2u">
											<button onclick="location='editarDatos.php?id=<?= $idNNA?>'">Editar</button>	
										</div>
									</div>
								</div>
							</div>
						</div>
						<br>
					<?php } while($rwDir=$rDireccion->fetch_assoc()){ ?>
						<div class="box">
							<div class="row uniform">							
								<div class="12u$">
									<strong> Direccion actual del nna:</strong><br>
									<div class="uniform row"> 
										<div class="4u 12u$(xsmall)">
											<strong>Estado:</strong> <?php echo $rwDir['estado']; ?>
										</div>
										<div class="4u 12u$(xsmall)">
											<strong>Municipio:</strong> <?php echo $rwDir['municipio']; ?>
										</div>
										<div class="4u 12u$(xsmall)">
											<strong>Localidad:</strong> <?php echo $rwDir['localidad'];  ?>
										</div>
									</div>
									<div class="row uniform">
										<div class="12u">
											<strong>Calle y número:</strong> <?php echo $rwDir['direccion']; ?>
										</div>
									</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="4u"></div>
								<div class="4u"><ul><button onclick="location='editarDireccionNna.php?id=<?= $idNNA?>'">Editar</button></ul>
								</div>
							</div>
						</div>
					<?php } ?>
						<br>
						<?php $nex=$row['nna_ex'];
							 
  						   $consulta="SELECT id_caso from nna_caso where id_nna='$idNNA'";
						   $econsulta=$mysqli->query($consulta);

						   while ($row=$econsulta->fetch_assoc()) {
						   	 $idCas=$row['id_caso'];
						   }
						   

						   if (empty($idCas)) {
						   	
						   }else{ $nomcaso="SELECT casos.id, casos.folio_c, casos.nombre, casos.fecha from casos, nna_caso where nna_caso.id_nna='$idNNA' and casos.id=nna_caso.id_caso";
						   $enomcaso=$mysqli->query($nomcaso);?>
						   <?php while ($row=$enomcaso->fetch_assoc()) { ?>
						   <div class="box">						  
								<ul class="alt">																
								<li><h4>Folio del caso: <?php echo $row['folio_c'];  ?></h4> </li>
								<li><h4>Nombre: <?php echo $row['nombre'];  ?></h4> </li>
								<li><h4>Fecha de registro: <?php echo $row['fecha'];  ?></h4> </li>
								<button onclick="location='perfil_caso.php?id=<?php echo $row['id'];?>'">ver caso</button>
													
								</ul>

						   </div>
						   <div class="box">
							   	<ul class="alt">
							   		<li><h4>Carpetas vinculadas</h4> </li>
							   		<?php while($rw=$rcarp->fetch_assoc()){  ?>
							   			<li> <a href="perfil_carpeta.php?id=<?=$row['id']?>"><?=$rw['nuc']?></a></li>
							   		<?php } ?>
							   	</ul>
							</div>

						   <?php } ?>	
						<?php } ?>	
						<?php if (empty($rows2)) { ?>
						<?php if ($rows3>0) { ?>
						
						<?php }else {} ?>
						<?php }else { ?>
							<div class="box" onclick="location='listaxcentro.php?id=<?php echo $idC; ?>'">
									NNA en <?php echo $nomC; ?><br>
									<strong>Motivo:</strong> <?php echo $motivo; ?><br>
									<strong>Fecha de ingreso:</strong> <?php echo $fechaI; ?>
							</div>							
						<?php } ?>
						 
						<br>
						
						<?php if ($id_ddd=='16') { ?>
							<input type="button" name="asignar_curso" class="button fit" value="eliminar NNA" onclick="location='eliminar_nna.php<?php $_SESSION['idNNA']=$idNNA;?>'"> 	
							
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