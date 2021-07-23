<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	
	$idPc=$_GET['idPosibleCaso'];
	$idNna=$_GET['idNna'];
	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}
	$qNnaAc="SELECT id from nna_ac where id_nna_reportado=$idNna";
	$rNnaAc=$mysqli->query($qNnaAc);
	if($rNnaAc->num_rows>0){
		header("Location: registro_nna_ac.php?id=$idPc");
	}

	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;

	$qNna="SELECT nombre, apellido_p, apellido_m, sexo, fecha_nacimiento, lugar_nacimiento
		from nna_reportados where id=$idNna";
	$rNNA=$mysqli->query($qNna);

	if (isset($_POST['guardar_datos'])) {
		$fecha= date("Y-m-d H:i:s", time());
		$reg="SELECT id,num_nna from part1ac where id_reporte='$idPc'";
        $ereg=$mysqli->query($reg);
        $numNnaAc=$ereg->num_rows;
        if($numNnaAc>0){
        	while ($rw=$ereg->fetch_assoc()) {
            	$idAcerca=$rw['id'];           
            	$nnna=$rw['num_nna'];   
            }      
        } else {
        	$qpart1="INSERT INTO part1ac (id_reporte, fecha_registro, num_nna, respo_reg)
        	values ($idPc, '$fecha', 1, $idDEPTO)";
			$rpart1=$mysqli->query($qpart1);
			if($rpart1){
        	$reg1="SELECT id from part1ac where id_reporte='$idPc'";
        	$ereg1=$mysqli->query($reg1);
        	while ($rw1=$ereg1->fetch_assoc()) {
            	$idAcerca=$rw1['id'];   
            }  
			$nnna=1;
		} else echo $qpart1;
        }

       echo $idAcerca;		
		$nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$ape1= mysqli_real_escape_string($mysqli,$_POST['apellido_p']);
		$ape2= mysqli_real_escape_string($mysqli,$_POST['apellido_m']);
		$sexo= mysqli_real_escape_string($mysqli,$_POST['sexo']);
		$nacionalidad= mysqli_real_escape_string($mysqli,$_POST['nacionalidad']);
		$fec_nac= mysqli_real_escape_string($mysqli,$_POST['fecha_nac']);
		if($fec_nac=='')
			$fec_nac='1900-01-01';
		echo $fec_nac;
		$lugar_nac= mysqli_real_escape_string($mysqli,$_POST['lugar_nac']);
		$ocupa= mysqli_real_escape_string($mysqli,$_POST['ocupa']);
		$religion= mysqli_real_escape_string($mysqli,$_POST['religion']);

		$qhistorico="INSERT INTO historico_nna_reportados (id_nna,nombre,apellido_p,apellido_m,sexo,
		edad,fecha_nacimiento,lugar_nacimiento,lugar_registro,fecha_registro,responsable_registro)
		SELECT '$idNna', nombre, apellido_p, apellido_m, sexo, edad, fecha_nacimiento, lugar_nacimiento, lugar_registro, fecha_actualizacion, respo_actualizacion from nna_reportados where id=$idNna";
		$rhistorico=$mysqli->query($qhistorico);
		if($rhistorico){
			$qActulizar="UPDATE nna_reportados SET nombre='$nombre', apellido_p= '$ape1', apellido_m ='$ape2',
			sexo='$sexo', fecha_nacimiento = '$fec_nac', lugar_nacimiento = '$lugar_nac',
			fecha_actualizacion = '$fecha', respo_actualizacion = $idDEPTO
			WHERE id = $idNna";
			$rActualizar=$mysqli->query($qActulizar);
			if($rActualizar){
				$sql="INSERT INTO nna_ac (id_acerca, nombre, apellido_p, apellido_m, sexo, fecha_nac, 
				lugar_nac, nacionalidad, ocupacion, religion, fecha_registro, respo_reg, id_nna_reportado) 
				VALUES ('$idAcerca','$nombre','$ape1','$ape2','$sexo','$fec_nac','$lugar_nac',
				'$nacionalidad','$ocupa','$religion','$fecha','$idDEPTO', '$idNna')";
            	$esql=$mysqli->query($sql);
            	if($esql){
            		if($numNnaAc>0){         
            			$nnna=$nnna+1;
            			$qsumNna="UPDATE part1ac set num_nna=$nnna where id=$idAcerca" ;      
        			}
        			header("Location: registro_nna_ac.php?id=$idPc");
        		} else echo $sql;
			} else echo $qActulizar;
		} else echo $qhistorico;
	}

	

?>
<!DOCTYPE HTML>
<html>
	<head lang="es-ES">
		<title>Registrar acercamiento</title>
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
					<form id="registro" name="registro" action="#" method="POST" >
					<div class="box">
						<?php while ($row=$rNNA->fetch_assoc()){ 
						$sex=$row['sexo']; 
						if($row['fecha_nacimiento']!='1900-01-01' and !empty($row['fecha_nacimiento']))
							$fecha_nac=$row['fecha_nacimiento'];
						else $fecha_nac=null; ?>
                    	<div class="row uniform">                    	 
                    		<div class="4u 12u$(xsmall)">
                    			<label for="nombre">Nombre(s)</label>
                    			<input name='nombre' type='text' maxlength="50" value="<?= $row['nombre']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    		</div>
                    		<div class="4u 12u$(xsmall)">
                    			<label for="apellido_p">Primer apellido</label>
                    			<input name='apellido_p' type='text' maxlength="40" value="<?= $row['apellido_p']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                   			</div>
                   			<div class="4u 12u$(xsmall)">
                   				<label for="apellido_m">Segundo apellido</label>
                    			<input name='apellido_m' type='text' maxlength="40" value="<?= $row['apellido_m']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    		</div>
                    	</div>
                    	<div class="row uniform">
                    		<div class="3u 12u$(xsmall)">
                    			<label for="sexo">Sexo</label>
                        		<div class="select-wrapper">
                            		<select id="sexo" name="sexo" required>
                            			<?php if(empty($sex)){ ?>
                                		<option value="">SEXO...</option>
                                		<option value="1">HOMBRE</option>
                                		<option value="2">MUJER</option>
                                		<?php } else if($sex==1) { ?>
                                		<option value="1">HOMBRE</option>
                                		<option value="2">MUJER</option>
                                		<?php } else if($sex==2){ ?>
                                		<option value="2">MUJER</option>
                                		<option value="1">HOMBRE</option>
                                		<?php } ?>
                            		</select>
                        		</div>
                   	 		</div>
                   	 		<div class="4u 12u$(xsmall)">
                   	 			<label for="nacionalidad">Nacionalidad</label>
                    			<input type="text" name="nacionalidad" maxlength="30" placeholder="Nacionalidad" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    		</div>
                    		<div class="5u 12u$(xsmall)">
                    			<label for="fecha_nac">Fecha de nacimiento</label>
                    			<input name='fecha_nac' type='date' value="<?= $fecha_nac?>">
                    		</div>
                    	</div>
                    	<div class="row uniform">
                    		<div class="4u 12u$(xsmall)">
                    			<label for="lugar_nac">Lugar de nacimiento</label>
                    			<input name='lugar_nac' type='text' maxlength="100" value="<?= $row['lugar_nacimiento']?>" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    		</div>
                    		<div class="4u 12u$(xsmall)">
                    			<label for="ocupa">Ocupación</label>
                        		<div class="select-wrapper">
                            		<select id="ocupa" name="ocupa" required>
                                		<option value="">Ocupación...</option>
                                		<option value="ESTUDIA">Estudia</option>
                                		<option value="TRABAJA">Trabaja</option>
                                		<option class="ESTUDIA Y TRABAJA">Estudia y Trabaja</option>
                                		<option value="NINGUNA">Ninguna</option>
                            		</select>
                        		</div>
                    		</div>
                    		<div class="4u 12u$(xsmall)">
                    			<label for="religion">Religión</label>
                    			<input type="text" maxlength="30" name="religion" placeholder="Religion" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    		</div>
                    	</div>
                    <?php } ?>
                	
                    </div><br>
                    <input name="guardar_datos" class="button special fit" type="submit" value="Guardar datos">
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
			</div>
		</div>  <!--cierre de wrapper-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>