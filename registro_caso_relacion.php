<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	
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

	$bandera = false;
	$idPC=($_GET['idPc']);
	$fechaMostrar= date ("j/n/Y");
	$buscaf="SELECT responsable  from departamentos where id='$idDEPTO'";
	$ebf=$mysqli->query($buscaf);


	if(!empty($_POST))
	{
		$fecha= date("Y-m-d H:i:s", time());
		$nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$descripcion = mysqli_real_escape_string($mysqli,$_POST['descripcion']);		
		$sqlUser = "SELECT id FROM casos WHERE nombre = '$nombre'";
		$resultUser=$mysqli->query($sqlUser);
		$rows = $resultUser->num_rows;		
		$foli="SELECT terminacion from nfolio where id=3";
		$efoli=$mysqli->query($foli);
		while ($row=$efoli->fetch_assoc()) {
			$ter=$row['terminacion'];
		}
		$ter2=$ter+1;
		$folio='CAP0'.$ter2.'P';
		if($rows > 0) {
			?>
			<script type="text/javascript">alert('Ya existe un caso con ese nombre');</script>
			
			<?php } else {
			$qCaso = "INSERT INTO casos(folio_c,nombre, descripcion, funcionario_reg, fecha_registro) 
			VALUES ('$folio','$nombre', '$descripcion', '$idDEPTO', '$fecha')";
			$rCaso = $mysqli->query($qCaso);
			$upd="UPDATE nfolio set terminacion=$ter2 where id=3";
			$eudp=$mysqli->query($upd);
			if($rCaso>0)
			{
				$qidCaso="SELECT id from casos where nombre='$nombre'";
				$ridCaso=$mysqli->query($qidCaso);
				while ($rowidCaso=$ridCaso->fetch_assoc()) {
					$idCaso=$rowidCaso['id'];
				}
				$qRelacion="INSERT INTO relacion_pc_caso (id_posible_caso, id_caso) values ('$idPC', '$idCaso')";
				$rRelacion=$mysqli->query($qRelacion);
				if ($rRelacion){
					$exito=true;
					$sidNna="SELECT r.id_nna, r.id_nna_reportado from relacion_nna_nnareportado r
					inner join nna_reportados nr on nr.id=r.id_nna_reportado
					where nr.id_posible_caso='$idPC'"; //va selecconando el id del niño vinculado
					$qidNna=$mysqli->query($sidNna);
					while ($rowRelNna=$qidNna->fetch_assoc()) {
						$idNna=$rowRelNna['id_nna']; //id de la tabla nna
						$idNnaRep=$rowRelNna['id_nna_reportado'];
						$NReg="SELECT * from nna_caso where id_nna='$idNna'"; //verifica q este nna no este ya relacionado a al caso
						$rNReg=$mysqli->query($NReg);
						if($rNReg->num_rows==0){// verifica que no este ya el registro si no esta registra
						$relCasoN="INSERT INTO nna_caso (id_nna, id_caso, estado, fecha_registro)
						values ('$idNna','$idCaso','NE','$fecha')"; //relaciona a los nna que no estan
						$rRelCasoN=$mysqli->query($relCasoN);
						$qName="SELECT id from relacion_names where id_nna_reportado='$idNnaRep' and activo=1";
						$rName=$mysqli->query($qName);
						$numName= $rName->num_rows;
						
						
						if($numName>0){
							while($rowIdName=$rName->fetch_assoc()){
								$idName=$rowIdName['id'];
							}
							$qRelaName="UPDATE relacion_names set id_caso='$idCaso', id_nna='$idNna' where id='$idName'";
							$rRelName=$mysqli->query($qRelaName);
						}
						if(!$rRelCasoN){
							echo $relCasoN;
							$exito=false; }
						}
					}
					if($exito)
					header("Location: perfil_caso.php?id=$idCaso");
				}
				else echo "Error: ".$qRelacion;
			}			
			else
			$error = "Error: ".$qCaso;
			
		}
	}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Registrar caso</title>
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
					<br> <br> <div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div>
					<h1>Registro de caso</h1>
					<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						<div class="box" >
							<div class="row uniform">
								<div class="12u$">Nombre del caso:
									<input id="nombre" maxlength="100" name="nombre" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
								</div>
								<?php  while ($row=$ebf->fetch_assoc()) { ?>
									<div class="4u 12u$(xsmall)">Servidor publico:
										<input id="fp_encargado" style="text-transform:uppercase;" name="fp_encargado" type="text"  disabled value="<?php echo $row['responsable']; ?>">
									</div>
								<?php } ?>
								<div class="4u 12u$(xsmall)">Fecha de registro:
									<input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $fechaMostrar; ?>" disabled>	
								</div>
								<div class="12u$">Detección:
									<textarea name="descripcion" maxlength="800" rows="3" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
								</div>
							</div>
						</div>
						<div class="12u$">
							<ul class="actions">
								<input class="button special fit" name="registar" type="submit" value="Registrar" >
								<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?php echo $idPC;?>'" >
							</ul>
						</div>
					</form>
					<?php if($bandera) { 
						header("Location: lista_casos.php");
					} else { ?>
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