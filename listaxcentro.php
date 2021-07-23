
<?php
ob_start();

	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	$zonahoraria = date_default_timezone_get();
	$fecha= date ("Y-m-d H:i:s", time());
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$id_centro=$_GET['id'];
	$cas="SELECT c.id, c.nombre, c.titular, c.rfc, c.tipo, c.telefono, c.celular, c.correo1, c.correo2, c.sup, c.const, c.numacta, date_format(c.fecha_acta, '%d/%m/%Y') as fecha_acta, c.notaria, c.repreL, c.calle, c.cp, e.estado, m.municipio, l.localidad, c.completo from centros c, estados e, municipios m, localidades l where c.id='$id_centro' and c.id_estado=e.id and c.id_mun=m.id and c.id_loc=l.id";
	$ecas=$mysqli->query($cas);

	$total="SELECT id from nna_centros where id_centro='$id_centro'";
	$etotal=$mysqli->query($total);	
	$rows=$etotal->num_rows;

	$servicio="SELECT id from cas2 where id_cas='$id_centro'";
	$eser=$mysqli->query($servicio);
	$sqb=$eser->num_rows;
	
	$pv2="SELECT id from prof_cas where id_cas='$id_centro'";
	$epv2=$mysqli->query($pv2);
	$rowpv2=$epv2->num_rows;


	if(!empty($_POST['primera'])){ 
				$prof = $_POST['prof'];
				$cantidad = mysqli_real_escape_string($mysqli,$_POST['cantidad']);
				$fecha= date ("Y-m-d H:i:s", time());
				$valta="SELECT id from cas3 where id_cas='$id_centro' and profesion='$prof'";
				$evalta=$mysqli->query($valta);
				$rowv=$evalta->num_rows;
				if ($rowv>0) {
					echo "Profesion ya registrada";
				}else {
					$inse="INSERT INTO cas3 (id_cas, fecha_reg, respo_reg, profesion, cantidad) VALUES ('$id_centro', '$fecha', '$idDEPTO', '$prof', '$cantidad')";
					$einse=$mysqli->query($inse);
				}

			}	
	$ww="SELECT sum(cantidad) as total from cas3 where id_cas='$id_centro'"; 
	$eww=$mysqli->query($ww);
	while ($row=$eww->fetch_assoc()) { 
		$ttot=$row['total'];	} //cantidad de profecionales en cada profesion
	if(!empty($_POST['segunda'])){ //Cerrar registro
		$fecha= date ("Y-m-d H:i:s", time());
		$upd="UPDATE cas2 SET fecha_cierre='$fecha', respo_cierre='$idDEPTO', personal_total='$ttot' where id_cas='$id_centro'";
		$eupd=$mysqli->query($upd);

	}		
	$pc="SELECT id from prof_cas where id_cas='$id_centro'";
	$epc=$mysqli->query($pc);
	$cona=$epc->num_rows;  //numero de profe registrados hasta el momento
	if ($ttot>0) { //ya hay mas de un profecional contado (no necesariamente registrado)
		if ($ttot==$cona) { //si ya completo el registro qe habia mencionado
		$pval="SELECT id from centros where id='$id_centro' and completo is null";
		$epval=$mysqli->query($pval);
		$roww=$epval->num_rows;
		if ($roww>0) {
		$ter="UPDATE centros set completo='1', fecha_com='$fecha' where id='$id_centro'";
		$eter=$mysqli->query($ter);
		}else {

		}
		
	}else {
		
	}
	}
	
	$nnaC="SELECT id from nna_centros where id_centro='$id_centro' and activo='1'";
	$enna=$mysqli->query($nnaC);
	$tnna=$enna->num_rows;

	$vald="SELECT id from departamentos where id='$idDEPTO' and casp='1' and (id_depto='13' OR id_depto='16' OR id_depto='10')";
	$evald=$mysqli->query($vald);
	$rows2=$evald->num_rows;
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Lista</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" 
		/>
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner"> 
		<section id="search" class="alt">
			<?php while ($row=$ecas->fetch_assoc()) { ?>

		<div class="box alt" align="center">
			<div class="row 10% uniform">
				<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
				<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
				<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
			</div>
		</div> 
		<div class="box alt" align="center">
			<div class="row uniform">
				<div class="1u" align="left"><input type="button" value="atras" class="fit small" onclick="location='cas.php'"></div>
				<div class="6u" align="left"><h2><?php echo $row['nombre']; ?></h2></div>
				<div class="2u"><?php $com=$row['completo']; if ($com=='1') { ?>
				<input type="image" src="images/ejecutada.png" height="50" width="50" disabled> <?php }else { echo "REGISTRO INCOMPLETO"; } ?>
				</div>
				<div class="3u">
					
				</div>
			</div>
		</div> 
		
		<div class="box alt">
			<div class="row uniform">
				<div class="4u 12u$(xsmall)" align="left">
					<ul class="alt">
						<li><b>Tipo:</b> <?php echo $row['tipo'];?></li>
						<li><b>Titular:</b> <?php echo $row['titular'];?></li>
						<li><b>RFC:</b> <?php echo $row['rfc'];?></li>		
						<li><b>Dirección:</b> <?php echo $row['calle'].', '.$row['cp'].', '.$row['localidad'].', '.$row['municipio'].', '.$row['estado'].'.';?></li>
						<li>Datos registrales del acta constitutiva:<br><b>Numero de acta:</b> <?php echo $row['numacta'];?><br><b>Fecha:</b> <?php if($row['fecha_acta']=='01/01/1900') echo "-"; else  echo $row['fecha_acta'];?></li>
					</ul>
				</div>
				<div class="4u 12u$(xsmall)" align="left">
					<ul class="alt">
						<li><b>Teléfono fijo:</b> <?php echo $row['telefono'];?></li>	
						<li><b>Teléfono celular:</b> <?php echo $row['celular'];?></li>
						<li><b>Superficie total:</b> <?php echo $row['sup'];?></li>
						<li><b>Construcción total:</b> <?php echo $row['const'];?></li>					
					</ul>
				</div>
				<div class="4u 12u$(xsmall)" align="left">
					<ul class="alt">
						<li><b>Correo 1:</b> <?php echo $row['correo1'];?></li>		
						<li><b>Correo 2:</b> <?php echo $row['correo2'];?></li>
						<li><b>Notaría pública:</b> <?php echo $row['notaria'];?></li>	
						<li><b>Representante legal:</b> <?php echo $row['repreL']; $comp=$row['completo'];?></li>	
					</ul>
				</div>	
			</div>	
		<div class="row uniform">
			<div class="11u 12u$(xsmall)"></div>
			<div class="1u 12u$(xsmall">
			<?php if ($rows2>0) { ?>		
			<a href="completarInfoCas.php?id=<?php echo $id_centro ?>">Editar</a>
			<?php } ?>
			</div>
		</div>
		</div><?php } ?> 
			
				<?php if (empty($sqb)) { ?>
				<div align="right" class="12u 12u$(xsmall)">
				<?php if ($rows2>0) { ?>
					<input type="button" value="completar registro" class="special small" onclick="location='reg_cas2.php?id=<?php echo $id_centro; ?>'">
				<?php } ?>	
				</div>	
				<?php }else{ ?>
		<div class="box alt">
			<?php $seg="SELECT edadmin, edadmax, sexo, capacidad, cantidad, pre1, pre2, pre3, pre4, pre5, pre6, medicina as a, nutricion as b, psicologia as c, ts as d, pedagogia as e, juridico as f, pericultura as g, fisioterapia as h, psiquiatria as i, administracion as j, servicios_g as k from cas2 where id_cas='$id_centro'";
				  $eseg=$mysqli->query($seg);  ?>
			<div class="row uniform">
				<div class="3u 12u$(small)" align="left">
					<h3>Servicios que brinda</h3>
				</div>
				<div class="9u 12u$(small)" align="left">
					<ul class="pagination">
				<?php while ($row=$eseg->fetch_assoc()) { 
						$a=$row['a'];$b=$row['b'];$c=$row['c'];$d=$row['d'];$e=$row['e'];$f=$row['f'];$g=$row['g'];$h=$row['h'];$i=$row['i'];$j=$row['j'];$k=$row['k'];
						$edadm=$row['edadmax']; $edadmin=$row['edadmin']; $sexo=$row['sexo']; $capacidad=$row['capacidad']; $cantidad=$row['cantidad']; $pre1=$row['pre1']; $pre2=$row['pre2']; $pre3=$row['pre3']; $pre4=$row['pre4']; $pre5=$row['pre5']; $pre6=$row['pre6']; 
					if (empty($a)) { }else{ $a="MEDICINA"; }
					if (empty($b)) { }else{ $b="NUTRICION"; }
					if (empty($c)) { }else{ $c="PSICOLOGIA"; }
					if (empty($d)) { }else{ $d="TRABAJO SOCIAL"; }
					if (empty($e)) { }else{ $e="PEDAGOGIA"; }
					if (empty($f)) { }else{ $f="JURIDICO"; }
					if (empty($g)) { }else{ $g="PUERICULTURA"; }
					if (empty($h)) { }else{ $h="FISIOTERAPIA"; }
					if (empty($i)) { }else{ $i="PSIQUIATRIA"; } 
					if (empty($j)) { }else{ $j="ADMINISTRACION"; } 
					if (empty($k)) { }else{ $k="SERVICIOS GENERALES"; } 
						
					for ($lol='a'; $lol <= 'k'; $lol++) { 
						if (empty($$lol)) {	}else { ?>
						<li><a class="page active"><?php echo $$lol; ?></a></li>
						<?php }} } ?>
					</ul>
				</div>
					
			</div>
			<div class="row uniform">
				<div class="12u 12u$(small)" align="left">
					<h3>Caracteristicas del CAS</h3>				
				<div class="row uniform">
				<div class="6u 12u$(xsmall)" >				
					<table class="alt">						
						<tbody>
							<tr>
								<td>A. Rango de edades de los NNA que aloja</td>
								<td><?php echo 'de '.$edadmin.' a '.$edadm.' años'; ?></td>								
							</tr>
							<tr>
								<td>B. Sexo de las NNA que aloja</td>
								<td><?php echo $sexo; ?></td>								
							</tr>
							<tr>
								<td>C. ¿Capacidad maxima de alojamiento?</td>
								<td><?php echo $capacidad; ?></td>								
							</tr>
							<tr>
								<td>D. ¿Cantidad de NNA en condiciones de atender de conformidad con la capacidad presupuestal?</td>
								<td><?php echo $cantidad; ?></td>								
							</tr>
							<tr>
								<td>E. ¿Brinda atencion a NNA con discapacidad?</td>
								<td><?php echo $pre1; ?></td>								
							</tr>
							
						</tbody>
					</table>			
				</div>
				<div class="6u 12u$(xsmall)" >				
					<table class="alt">						
						<tbody>
							<tr>
								<td>F. ¿Brinda atención a NNA victimas de algun delito?</td>
								<td><?php echo $pre2; ?></td>								
							</tr>
							<tr>
								<td>G. ¿Recibe a NNA de otras entidades federativas?</td>
								<td><?php echo $pre3; ?></td>								
							</tr>
							<tr>
								<td>H. ¿Brinda acogimiento a NNA migrantes no acompañados?</td>
								<td><?php echo $pre4; ?></td>								
							</tr>
							<tr>
								<td>I. ¿Cuenta con instalaciones para acogimiento residencial en otras entidades federativas?</td>
								<td><?php echo $pre5; ?></td>								
							</tr>
							<tr>
								<td>J. ¿Cuenta con servicios en modalidad de puerta abierta?</td>
								<td><?php echo $pre6; ?></td>								
							</tr>
						</tbody>
					</table>			
				</div>
			</div>
			</div>
		</div>
	</div>
	<div class="box">
		<div class="row uniform">
			<div class="3u 12u$(small)" align="left">
				<h3>Profesionales en el CAS</h3> 
			</div>
			
			<div class="8u 12u$(small)" align="left">
				Registrar la cantidad de profesionales que laboran dentro del Centro de Asistencia Social
			</div>
			<div class="8u 12u$(small)">
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
				<?php $xce="SELECT id from cas2 where fecha_cierre is null and id_cas='$id_centro'";
					  $exce=$mysqli->query($xce);
					  $sas=$exce->num_rows; 
					  if ($sas>0) { ?>
					<div class="box">
					<div class="row uniform">
						<div class="5u 12u$(small)">
							<div class="select-wrapper">
								<select id="prof" name="prof" required>
									<option value="">PROFESION</option>
									<option value="MEDICINA">MEDICINA</option>
									<option value="NUTRICION">NUTRICION</option>
									<option value="PSICOLOGIA">PSICOLOGIA</option>
									<option value="TRABAJO SOCIAL">TRABAJO SOCIAL</option>
									<option value="PEDAGOGIA">PEDAGOGIA</option>
									<option value="JURIDICO">JURIDICO</option>
									<option value="PUERICULTURA">PUERICULTURA</option>
									<option value="FISIOTERAPIA">FISIOTERAPIA</option>
									<option value="PSIQUIATRIA">PSIQUIATRIA</option>
									<option value="ADMINISTRACION">ADMINISTRACION</option>
									<option value="SERVICIOS GENERALES">SERVICIOS GENERALES</option>
								</select>
							</div>
						</div>
						<div class="3u 12u$(small)">
							<input type="text" name="cantidad" placeholder="cantidad" onkeypress="return justNumbers(event);">
						</div>
						<?php if ($rows2>0) { ?>
							<div class="4u 12u$(small)">
							<input type="submit" value="Guardar" name="primera">
						</div>
						<?php } ?>
					</div>
					</div>   	
					  <?php } else { } ?>
				

				<div class="12u 12u$(small)">
				<?php $ptabla="SELECT id, profesion, cantidad from cas3 where id_cas='$id_centro'";
						  $etabla=$mysqli->query($ptabla); 
						  $val=$etabla->num_rows;
						  if ($val>0) { ?> 
					<table class="alt">
						<thead>
							<tr>
								<th width="500">Profesion</th>
								<th>Cantidad</th>								
							</tr>
						</thead>
						<tbody>			
						<?php while ($row=$etabla->fetch_assoc()) {  $profn=$row['profesion'];
							$pv="SELECT id from prof_cas where id_cas='$id_centro' and tipo='$profn'";
							$epv=$mysqli->query($pv);
							$rowpv=$epv->num_rows;
							?>
						   	<tr>
						   		<td align="left"><?php echo $row['profesion']; ?></td>
						   		<td align="left"><?php echo $row['cantidad']; ?></td>
						   		<td align="left"><?php if ($sas>0) { }else{ 
						   	if ($rowpv>0) { ?>
						   		<input type="image" src="images/ejecutada.png" height="30" width="30" disabled>
						   	<?php	}else { ?>
						   		<?php if ($rows2>0) { ?>
						   			<input type="button" class="button special fit small" onclick="location='reg_personal_cas.php?id=<?php echo $id_centro; ?>&pro=<?php echo $row['profesion']; ?>'" value="Registrar personal">
						   		<?php } ?>
						   		
						   	<?php } } ?></td>
						   	</tr>
						  <?php } ?>
						</form>
						  	<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						  <tfoot>
						  	<tr>
						  		<td></td>
						  		<?php if ($rows2>0) { ?>
						  		<td><?php if ($sas>0) { ?>
						  		<input class="button special fit small" type="submit" value="CERRAR REGISTRO" name="segunda">
						  		<?php } ?>
						  		</td>
						  		<?php } ?>
						  		
						  	</tr>
						  </tfoot> 
						</tbody>
						<tfoot>
							<tr>
								<td></td>
								<td><?php echo $ttot; ?></td>
								<td><?php if ($rowpv2>0) { ?><input type="button" class="button fit small" value="VER personal" onclick="location='lista_personal_cas.php?id=<?php echo $id_centro; ?>'"><?php }?></td>
							</tr>
						</tfoot>
					</table>
				<?php } else{ ?>
					<strong>NO HAY REGISTROS</strong>
					<?php } ?>
				</div>
			</form>
			
			</div>
			
		</div>
		
	</div>
					<?php } ?>
	<script type="text/javascript">
	function justNumbers(event){		
		if (event.charCode>=48 && event.charCode<=57){
			return true;
		}
		return false;
	}
</script>
						
				
			
<br>
	<div class="row uniform">
		<div class="6u">
			<input type="button" class="button fit" name="" value="NNA ALBERGADOS: <?php echo $tnna; ?>" onclick="location='nnaENcas.php?id=<?php echo $id_centro; ?>'">
		</div>
	
		<div class="6u">
			<input type="button" class="button special fit" onclick="location='lista_supervisionCAS.php?id=<?php echo $id_centro; ?>'" value="supervisiones">	
		</div>
		
	</div>
			</section>
			

			<?php if(@$bandera) { 
			header("Location: welcome.php");

			?>	<?php }else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>

							</div>
							</div>
				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							<nav id="menu">
								<header class="major">
									<h2>Menú</h2>
								</header>
									<ul><li><a href="welcome.php">Inicio</a></li>				
										<li><a href="logout.php" >Cerrar sesión</a></li>
									</ul>
							</nav>	
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