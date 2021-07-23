<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	$valida="SELECT id from departamentos where (id_depto in ('9','10') and id_personal='3' and id='$idDEPTO') or (id_depto>='18' and id_depto<='33' and id_personal='5' and id='$idDEPTO') or (id_personal='1' and id='$idDEPTO' and id_depto='16')"; //ayuda a validar la persona que puede mover 
	$evalida=$mysqli->query($valida);
	$reva=$evalida->num_rows;

	$fecha= date ("j/n/Y");
	$buscaf="SELECT responsable  from departamentos where id='$idDEPTO'";  //consulta e servidor publico
	$ebf=$mysqli->query($buscaf);


	$query="SELECT * from municipios where id!='0'"; //select municipios al final
	$equery=$mysqli->query($query);
	$countries = array();
	while($row=$equery->fetch_object())	{ $countries[]=$row; }

	$sqlf="SELECT max(id) from reportes_migrantes";
		$esqlf=$mysqli->query($sqlf);
		while ($row=$esqlf->fetch_assoc()) {
			$num=$row['max(id)'];
		}
		$num2=$num+1;
		$folio="RP0".$num2;		

	if(!empty($_POST))
	{
		$recepcion = $_POST['recepcion'];
		$distrito = $_POST['distrito'];
		$persona_reporte = mysqli_real_escape_string($mysqli,$_POST['persona_reporte']);
		$municipio=$_POST['country_id'];
		$localidad=$_POST['state_id'];
		$calle=mysqli_real_escape_string($mysqli,$_POST['calle']);
		$narracion = mysqli_real_escape_string($mysqli,$_POST['narracion']);
		$referencias = mysqli_real_escape_string($mysqli,$_POST['referencias']);
		$datosrelevates = mysqli_real_escape_string($mysqli,$_POST['datosrelevates']);
		$sqlNino = "INSERT INTO reportes_migrantes (folio, fecha_reg, recepcion_rep, id_distrito, id_municipio, id_localidad,calle, referencias, descripcion, otros_datos,  persona_reporta, respo_reg) values ('$folio','$fecha','$recepcion','$distrito', '$municipio', '$localidad', '$calle', '$referencias', '$narracion', '$datosrelevates', '$persona_reporte', '$idDEPTO')";
		$resultNino = $mysqli->query($sqlNino);
	
			

		foreach ($_POST['nombre'] as $num => $val) {
               $perso = $num + 1;
               
            $nombre= $val;
            $ap_paterno=$_POST['ap_paterno'][$num];
            $ap_materno=$_POST['ap_materno'][$num];
            $fecha_nac=$_POST['fecha_nac'][$num];
            $nacionalidad=$_POST['nacionalidad'][$num];
            $calidad_migratoria=$_POST['calidad_migratoria'][$num];
            $sexo=$_POST['sexo'][$num];
            $lug_nac=$_POST['lug_nac'][$num];
            $pais_origen=$_POST['pais_origen'][$num];
            $condicion=$_POST['condicion'][$num];
            $acompanamiento=$_POST['acompanamiento'][$num];

            $lnom=substr($nombre, 0,1);
			$lap=substr($ap_paterno, 0,1);
			$lam=substr($ap_materno, 0,1);
		
			$snum='SELECT max(id) from nna';
			$esnum=$mysqli->query($snum);
			while ($row=$esnum->fetch_assoc()) {
			$ter=$row['max(id)'];
			}
			$ter2=$ter+1;
			$folionna=$lnom.$lap.$lam.$ter2;
            
            $sqlNino = "INSERT INTO nna (folio, nombre, apellido_p, apellido_m, fecha_nac, sexo, calidad_migratoria, pais_origen, condicion, acompanamiento, lugar_nac, der_vulne, respo_reg, fecha_reg) VALUES ('$folionna', '$nombre', '$ap_paterno', '$ap_materno', '$fecha_nac', '$sexo', '$calidad_migratoria', '$pais_origen', '$condicion', '$acompanamiento',  '$lug_nac', '0', '$idDEPTO', '$fecha')";			
			$resultNino = $mysqli->query($sqlNino);
				echo $sqlNino;
			$relRN="INSERT INTO nna_reporte (id_reporte, id_nna, tipo_reporte) VALUES ('$num2','$ter2', 'M')";
			$erelRN=$mysqli->query($relRN);
        	}

			if($resultNino>0)
			header("Location: lista_reporte.php?estRep=0");
			else
			$error = "Error al Registrar";		
	}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Inicio</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
   				$("#add").click(function(){
        			var contador = 1;
					var condicion ='cond'+ contador;
					//document.getElementByID("condicion").innerHTML = rbCondicion;
        		$(this).before('<div class="box"> <div class="row uniform"> <div class="4u 12u$(xsmall)"> <input name="nombre[]" type="text" placeholder="Nombre(s)"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required> </div> <div class="3u 12u$(xsmall)"> <input name="ap_paterno[]" type="text"  placeholder="Apellido Paterno" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required> </div> <div class="3u 12u$(xsmall)"> <input name="ap_materno[]" type="text"  placeholder="Apellido materno"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required> </div> <div class="2u 12u$(xsmall)"> <select name="sexo[]" required> <option value="">SEXO</option> <option value="HOMBRE">HOMBRE</option> <option value="MUJER">MUJER</option> </select> </div> <div class="3u 12u$(xsmall)"> <select name="calidad_migratoria[]" required> <option value="">CALIDAD MIGRATORIA</option> <option value="REGULAR">REGULAR</option> <option value="IRREGULAR">IRREGULAR</option></select> </div> <div class="3u 12u$(xsmall)"> <input name="fecha_nac[]" type="text" placeholder="Fecha nac. dd/mm/aaaa"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required> </div> <div class="3u 12u$(xsmall)"> <input name="lug_nac[]" type="text" placeholder="Lugar de nacimiento"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required> </div> <div class="3u 12u$(xsmall)"> <input name="nacionalidad[]" type="text" placeholder="Nacionalidad"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required> </div>  <div class="4u 12u$(xsmall)"> <input name="pais_origen[]" type="text" placeholder="Pais de origen"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required> </div> <div class="4u 12u$(xsmall)"> <select name="condicion[]" required> <option value="">CONDICIÓN</option> <option value="MIGRANTE NACIONAL">MIGRANTE NACIONAL</option> <option value="MIGRANTE EXTRANJERO">MIGRANTE EXTRANJERO</option> <option value="REPATRIADO NACIONAL">REPATRIADO NACIONAL</option> </select> </div> <div class="4u 12u$(xsmall)"> <select name="acompanamiento[]" required> <option value="SOLO">SOLO</option> <option value="ACOMPAÑADO">ACOMPAÑADO</option> </select> </div>');
        		contador++;
    		});
   			
			});
		</script>
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
								<h1>Reporte</h1>
							<div class="box" >
								<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
								
			 					<div class="row uniform">
									<div class="3u 12u$(xsmall)">Fecha de registro:
										<input id="fecha_reg" name="fecha_reg" type="text" value="<?php echo $fecha; ?>" disabled>	
									</div>
									<div class="5u 12u$(xsmall)">Forma de recepción de reporte:
										<div class="select-wrapper">
											<select id="recepcion" name="recepcion">							
											<option value="MINISTERIO PUBLICO">MINISTERIO PUBLICO (NUC)</option>
													<option value="OFICIO">OFICIO</option>
													<option value="REDES SOCIALES">REDES SOCIALES</option>
													<option value="CORREO ELECTRONICO">CORREO ELECTRONICO</option>
													<option value="PRESENCIAL">PRESENCIAL</option>
													<option value="TELEFONO">TELEFONO</option>
											</select>
										</div>
									</div>
									<div class="4u 12u$(xsmall)">Distrito:
										<div class="select-wrapper">
											<select id="distrito" name="distrito">		
												<?php $mun="SELECT id, distrits from distritos"; //llena el selct de distritos
													$emun=$mysqli->query($mun); ?>					
													<?php while ($row=$emun->fetch_assoc()) { ?>
													<option value="<?php echo $row['id']; ?>"><?php echo $row['distrits']; ?></option>
													<?php } ?>
											</select>
										</div>
									</div>
									
									<div align="right"><button type="button" id="add">+</button></div>

									<div class="12u 12u$(small)">
										<div class="box">Datos de localizacion de la NNA:
											
										<div class="row uniform">
											<div class="3u 12u(xsmall)">
												<div class="select-wrapper">
													<select id="country_id" class="form-control" name="country_id" required>
      													<option value="">-- MUNICIPIO --</option>
															<?php foreach($countries as $c):?>
     															<option value="<?php echo $c->id; ?>"><?php echo $c->municipio; ?></option>
															<?php endforeach; ?>
    												</select>
												</div>
											</div>
											<div class="3u 12u(xsmall)">
												<div class="select-wrapper">
													<select id="state_id" class="form-control" name="state_id" required> <!--llena select de la localidad-->
     													<option value="">-- SELECCIONE --</option>
   													</select>
												</div> 
											</div>
											<div class="6u 12u$(small)">
												<input type="text" name="calle" placeholder="CALLE Y NUMERO" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
											</div>										
											<div class="12u 12u$(xsmall)">
											<textarea name="referencias" rows="2" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="referencia sobre el domicilio" required></textarea>
											</div>	
										</div>
										</div>
									</div>
									
									<div class="6u 12u$(xsmall)">Descripcion de la situacion:
										<textarea name="narracion" rows="3" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
									</div>
									
									<div class="6u 12u$(small)">Otros datos u observaciones relevantes:
										<textarea name="datosrelevates" rows="3" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
									</div>
																			
									<div class="6u 12u$(xsmall)">Persona que reporta:
										<input id="persona_reporte" name="persona_reporte" type="text" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
									</div>
									<?php  while ($row=$ebf->fetch_assoc()) { ?>
									<div class="6u 12u$(xsmall)">Servidor publico:
										<input id="fp_encargado" style="text-transform:uppercase;" name="fp_encargado" type="text"  disabled value="<?php echo $row['responsable']; }?>">
									</div>		
			<?php if ($reva=='1') { ?>
				<div class="6u 12u$(xsmall)">
					<input class="button special fit" name="registar" type="submit" value="Registrar">	
				</div>
			<?php }else{ ?>
				<div class="6u 12u$(xsmall)">
					<input class="button special fit" name="registar" type="submit" value="Registrar" disabled>	
				</div>
			<?php } ?>
				
				<div class="6u 12u$(xsmall)">
					<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='lista_reporte.php?estRep=0'" >
				</div>
			</div>
		</form>
		<script type="text/javascript">
	$(document).ready(function(){
		$("#country_id").change(function(){
			$.get("get_localidades.php","country_id="+$("#country_id").val(), function(data){
				$("#state_id").html(data);
				console.log(data);
			});
		});		
	});
</script>
	</div>
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
										<li><a href="logout.php">Cerrar sesión</a></li>
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
									<p class="copyright">&copy; Ing. Ivan Flores Navarro. </p>
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