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
	$idReporte=$_GET['idRep'];
	$idRespo=$_GET['id'];
	$qResponsable="SELECT id, nombre, edad, ocupacion , datos_economicos, telefono, direccion, estado_civil,
    escolaridad, religion from respon_nna where id=$idRespo";
    $rResponsable=$mysqli->query($qResponsable);
	$qIdAsig="SELECT id_departamentos_asignado 
    from historico_asignaciones_trabajo_social h inner join posible_caso pc 
    on pc.id_asignado_ts=h.id where pc.id=$idReporte";
    $rIdAsig=$mysqli->query($qIdAsig);
    $IdAsig=implode($rIdAsig->fetch_assoc());
    if (isset($_POST['guardar_datos'])) { //al presionar boton
        $fecha= date("Y-m-d H:i:s", time());
        $nombre= $_POST['nombre'];
        $edad=$_POST['edad'];
        $ocupacion=$_POST['ocupa'];
        $datos_economicos=$_POST['datosE'];
        $telefono=$_POST['telefono'];
        $direccion=$_POST['direccion'];
        $estado_civil=$_POST['estado'];            
        $religion=$_POST['religion'];
        $escolaridad=$_POST['escolaridad'];

        $qHst="INSERT INTO historico_respon_nna (id_respon_nna,nombre,edad,ocupacion,datos_economicos,
        telefono,direccion,estado_civil,religion,escolaridad,fecha_registro,respo_reg) SELECT 
        $idRespo, nombre, edad,ocupacion, datos_economicos, telefono, direccion,
		estado_civil, religion, escolaridad,'$fecha','$idDEPTO' from respon_nna where id=$idRespo";
		$rHst=$mysqli->query($qHst);
		if($rHst){
			$qActualizar="UPDATE respon_nna SET nombre='$nombre',edad='$edad',ocupacion='$ocupacion',
			datos_economicos='$datos_economicos', telefono='$telefono',direccion='$direccion', 
			estado_civil= '$estado_civil', escolaridad='$escolaridad', religion='$religion'
			WHERE id=$idRespo";
			$rActualizar=$mysqli->query($qActualizar);
			if($rActualizar){
				echo "<script>
				alert('Se ha actualizado la información correctamente');
				window.location= 'reg_per_respo.php?id=$idReporte'
				</script>";
			} else echo $qActualizar;
		} else echo $qHst;

    }
?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Editar responsable</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
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
					<div class="row uniform">
                        <div class="2u 12u$(xsmall)">
                            <input type="button" name="" class="button special small" value="Atras" onclick="location='reg_per_respo.php?id=<?= $idReporte ?>'">
                        </div>
                        <div class="8u">
                            <h3>Editar al responsable:</h3>
                        </div>
                        <div class="2"></div>
                    </div>
                    <form id="primer" name="primer" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                    	<?php while($rwRes=$rResponsable->fetch_assoc()) { ?>
                            <div class="row uniform">
                                <div class="6u 12u$(xsmall)">Nombre
                                    <input name='nombre' type='text' maxlength="100" value="<?=$rwRes['nombre']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Edad
                                    <input name='edad' type='text' maxlength="20" value="<?=$rwRes['edad']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Teléfono
                                    <input name='telefono' type='text' maxlength="30" value="<?=$rwRes['telefono']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="4u 12u$(xsmall)">Ocupación
                                    <input name='ocupa' type='text' maxlength="100" value="<?=$rwRes['ocupacion']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="5u 12u$(xsmall)">Datos economicos (concepto, cantidad)
                                    <input name='datosE' type='text' maxlength="300" value="<?=$rwRes['datos_economicos']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Religión
                                    <input name='religion' type='text' maxlength="20" value="<?=$rwRes['religion']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="7u 12u$(xsmall)">Dirección
                                    <input name='direccion' type='text' maxlength="300" value="<?=$rwRes['direccion']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Estado civil
                                    <input name='estado' type='text' maxlength="50" value="<?=$rwRes['estado_civil']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="2u 12u$(xsmall)">Escolaridad
                                    <input name='escolaridad' type='text' maxlength="50" value="<?=$rwRes['escolaridad']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                            </div>
                            <?php if($IdAsig==$idDEPTO or ($idDepartamento==16 and $idPersonal==1)) { ?>
                                <div class="row uniform">
                                    <div class="12u">
                                        <input name="guardar_datos" class="button special fit" type="submit" value="Actualizar datos">
                                    </div>
                                </div>
                            <?php } } ?>
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
			</div> <!--cierre de menu-->
		</div>  <!--cierre de wrapper-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>