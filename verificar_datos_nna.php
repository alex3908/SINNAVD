<?php
ob_start();
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
$zonahoraria = date_default_timezone_get();

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

    //$idCaso=$_GET['idCaso'];
    $idPc=$_GET['idPc'];
	$query="SELECT id, municipio from municipios";
	$equery=$mysqli->query($query);

	$qNna="SELECT nna_reportados.id as idNna, nombre, apellido_p, apellido_m, sexo.id as sexId, sexo.sexo, 
    fecha_nacimiento as fecha_nac, lugar_nacimiento, lugar_registro, fecha_actualizacion
	from nna_reportados left join sexo on nna_reportados.sexo=sexo.id
    where nna_reportados.id_posible_caso='$idPc' and activo=1";
	$rNna=$mysqli->query($qNna);
	$numNnaRep=$rNna->num_rows;
   /* $qcaso="SELECT folio_c, nombre from casos where id='$idCaso'";
    $rcaso=$mysqli->query($qcaso);
    while ($rowCaso=$rcaso->fetch_assoc()) {
        $folioCaso=$rowCaso['folio_c'];
        $nombreCaso=$rowCaso['nombre'];
	}*/
	$numNnaV=0;  //numero de nna ya registrados
	$numCasos=0; //numero de nna con caso
	$qnumCasos="SELECT distinct id_caso from relacion_pc_caso where id_posible_caso='$idPc'";
	$rnumCasos=$mysqli->query($qnumCasos); 
	$CasosR=$rnumCasos->num_rows; //cuantos casos estan vinculados a este posible caso
	$idC=null;

	$bandera = false;
	if(isset($_POST['relCaso'])) {
		$fecha = date("Y-m-d H:i:s", time());
		while ($rCasoR=$rnumCasos->fetch_assoc()) {
			$idCasoR=$rCasoR['id_caso'];
		}
		$exito=true;
		$sidNna="SELECT r.id_nna from relacion_nna_nnareportado r
		inner join nna_reportados nr on nr.id=r.id_nna_reportado
		where nr.id_posible_caso='$idPc'"; //va selecconando el id del niño vinculado
		$qidNna=$mysqli->query($sidNna);
		while ($rowRelNna=$qidNna->fetch_assoc()) {
			$idNna=$rowRelNna['id_nna']; //id de la tabla nna
			$NReg="SELECT * from nna_caso where id_nna='$idNna'"; //verifica q este nna no este ya relacionado a al caso
			$rNReg=$mysqli->query($NReg);
			if($rNReg->num_rows==0){// verifica que no este ya el registro si no esta registra
			$relCasoN="INSERT INTO nna_caso (id_nna, id_caso, estado, fecha_registro)
			values ('$idNna','$idCasoR','NE','$fecha')"; //relaciona a los nna que no estan
			$rRelCasoN=$mysqli->query($relCasoN);
			if(!$rRelCasoN){
			echo $relCasoN;
			$exito=false; }}
		}
		if($exito)
		header("Location: perfil_caso.php?id=$idCasoR");

	}

	if(isset($_POST['casoExistente'])){
		$_SESSION['idPc'] = $idPc;
		header("Location: relacionar_a_caso.php");
	}

?>

<!DOCTYPE HTML>

<html>
	<head lang="es-ES">
		<title>Añadir NNA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
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
		</div></div> <br>	
			
		<div class="box" >
			<h3>Por favor, primero añada a los NNA al caso</h3>	
			<form id="familia"  enctype="multipart/form-data" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
                <table>
                    <thead>NNA registrados:<tr>								
						<td><b>Nombre</b></td>
						<td><b>Sexo </b></td>	
						</tr>
                    </thead>
                    <body>
                        <?php while ($row=$rNna->fetch_assoc()){ 
                            $idNnaR=$row['idNna']; //id tabla nna_reportado
                            ?> <!-- muestra los datos de los nna vinculados al posible caso de la tabla nna_reportados-->
                        
						<?php //verifica que el nna_reportado no este vinculado ya a un nna 
                                $qverificarNnaReportado="SELECT id_nna, folio, 
								concat(nna.nombre, ' ',  apellido_p,' ', apellido_m) as nombre,
								sexo 
								from relacion_nna_nnareportado inner join nna on nna.id=relacion_nna_nnareportado.id_nna
								where id_nna_reportado='$idNnaR'";
								$rverificarNna=$mysqli->query($qverificarNnaReportado);
                                $NnaVinculado=$rverificarNna->num_rows;
								if($NnaVinculado>0){ //si ya esta vinculado a un nna
									$numNnaV++;
                                    while ($rowNnaR=$rverificarNna->fetch_assoc()) {
										$idNna=$rowNnaR['id_nna']; //id de la tabla nna
										$folioNna=$rowNnaR['folio'];
										$nom=$rowNnaR['nombre'];
										$sex=$rowNnaR["sexo"];
                                    }
                                    $qdatosNna="SELECT casos.id, folio_c
									from nna left join nna_caso on nna.id=nna_caso.id_nna
                                    left join casos on nna_caso.id_caso=casos.id
									left join relacion_pc_caso on relacion_pc_caso.id_caso=casos.id
                                     where nna.id='$idNna' and id_posible_caso='$idPc'";
									 $rdatosNna=$mysqli->query($qdatosNna);
									 $num_caso=$rdatosNna->num_rows;
									 if($num_caso>0){
										 $numCasos++;
                                     while ($rowmostrar=$rdatosNna->fetch_assoc()) {
										$folioC=$rowmostrar['folio_c'];
										$idC=$rowmostrar['id']; 
									}} else $folioC=null; ?>
									<td><?php echo $nom?></td>
									<td><?php echo  $sex?></td>
									<td><?php echo "El NNA se registró con el folio ".$folioNna;
									if(!empty($folioC)) { echo " en el caso ".$folioC; } ?></td>
                               <?php } else {
                            ?><tr>
							<td><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></td>
							<td><?php echo $row["sexo"]; ?></td>
                            
                            <td><input class="button special fit" name="agregar" id="agregar" type="button" value="Añadir al caso" onclick="location='registro_nna_curp.php?idPc=<?= $idPc?>&idNnaR=<?= $idNnaR; ?>'"></td>
                                <?php } ?></tr> 
                        <?php } ?>
					</body>
                </table>
               <?php if($idDEPTO==220)// echo $numNnaRep."-".$numNnaV."-".$numCasos."-".$CasosR; ?>
				<?php if(($numNnaRep==$numNnaV) and ($numCasos>0) and $CasosR==1) {?>
					<input type="submit" value="Usar caso existente" class="button " name="relCaso">
				<?php } elseif( ($numNnaRep==$numNnaV) and ($numCasos==0) and $CasosR==0) { ?>
					<input type="submit" value="Vincular a caso existente" class="button " name="casoExistente">
				<?php } ?>		
			</form>	
			<?php if(($numNnaRep==$numNnaV)and ($numCasos<$numNnaV)) {?>
			<input type="button" value="Crear Caso " class="button special" onclick="location='registro_caso_relacion.php?idPc=<?= $idPc?>'">
			<?php } ?>
		</div>	
		<input type="button" value="Cancelar " class="button special" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?= $idPc?>'">
		
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