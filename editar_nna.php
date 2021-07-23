<?php
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];
	
	$idNNA=$_GET['id'];

	$query="SELECT id, municipio from municipios";
	$equery=$mysqli->query($query);

	$sqlnna="SELECT nna.folio, nna.nombre, nna.apellido_p, nna.curp, nna.apellido_m, nna.fecha_nac, nna.sexo, nna.responna, 
	nna.parentesco, nna.direccion, nna.telefono, nna.lugar_nac, nna.lugar_reg, nna.fecha_reg, nna.respo_reg, 
	localidades.localidad, municipios.municipio, nna.localidad as id_localidad, nna.municipio as id_municipio, validacionRenapo 
	from nna inner join  municipios on nna.municipio=municipios.id
	inner join  localidades on nna.localidad=localidades.id
	where nna.id='$idNNA'";
	$esqlnna=$mysqli->query($sqlnna);
	while ($row=$esqlnna->fetch_assoc()) {
		$folio=$row['folio'];
		$nom=$row['nombre'];
		$ape1=$row['apellido_p'];
		$ape2=$row['apellido_m'];
		$fechaNa=$row['fecha_nac'];
		$sex=$row['sexo'];
		$resp=$row['responna'];
		$parent=$row['parentesco'];
		$dir=$row['direccion'];
		$tel=$row['telefono'];
		$lugNac=$row['lugar_nac'];
		$lugReg=$row['lugar_reg'];
		$local=$row['localidad'];
		$idLocal=$row['id_localidad'];
		$mun=$row['municipio'];
		$idMun=$row['id_municipio'];
		$valiCurp=$row['validacionRenapo'];
	}

	    $qName="SELECT id, id_name from relacion_names where id_nna_reportado='$idNNA' and activo=1";
	$rName=$mysqli->query($qName);
    $numName= $rName->num_rows;
    if($numName>0){
        while ($ridName=$rName->fetch_assoc()) {
            $idName=$ridName['id_name'];
        }
	}
	$bandera = false;

	$query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($rowedo=$equery->fetch_object())	{ $countries[]=$rowedo; }
	
	if(!empty($_POST))
	{
		$nombre = 	  mysqli_real_escape_string($mysqli,$_POST['nombre']);
		$ap_paterno = mysqli_real_escape_string($mysqli,$_POST['ap_paterno']);
		$ap_materno = mysqli_real_escape_string($mysqli,$_POST['ap_materno']);		
		$fecha_nac =  mysqli_real_escape_string($mysqli,$_POST['fecha_nac']);
		$genero = $_POST['genero'];
		$lugar_nac =  mysqli_real_escape_string($mysqli,$_POST['lugar_nac']);
		$lugar_reg =  mysqli_real_escape_string($mysqli,$_POST['lugar_reg']);
		$responna =  mysqli_real_escape_string($mysqli,$_POST['responna']);
		$parentesco =  mysqli_real_escape_string($mysqli,$_POST['parentesco']);
		$direccion =  mysqli_real_escape_string($mysqli,$_POST['direccion']);
		$telefono =       mysqli_real_escape_string($mysqli,$_POST['telefono']);
		$municipio = $_POST['country_id'];
		$localidad = $_POST['state_id'];
		$error = '';
		

			@$sqlNino = "UPDATE nna SET nombre='$nombre', apellido_p='$ap_paterno', apellido_m='$ap_materno', fecha_nac='$fecha_nac', sexo='$genero', lugar_nac='$lugar_nac', lugar_reg='$lugar_reg', responna='$responna', parentesco='$parentesco', direccion='$direccion', telefono='$telefono', municipio='$municipio', localidad='$localidad' WHERE id='$idNNA'";
			$resultNino = $mysqli->query($sqlNino);
			
			if($resultNino>0)
			$bandera = true;
			else
			$error = "Error al Registrar";
			
		}
?>

<!DOCTYPE HTML>

<html>
	<head>
		<title>Editar NNA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>
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
			<h1>Editar</h1>
			
			<form id="familia"  enctype="multipart/form-data" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 
			<?php if($valiCurp!=1) { ?>
				
			<h2>Folio: <?= $folio ?></h2>
			<div class="box">
			<div class="row uniform">
			<div class="4u 12u$(xsmall)">
			<label for="nombre" name="lblnombre">Nombre(s)</label>
			<input id="nombre" name="nombre" type="text" class="nombre"  value="<?= $nom?>"  placeholder="Nombre(s)" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
			<div class="3u 12u$(xsmall)">
			<label for="ap_paterno" name="lblap_paterno">Primer apellido</label>
			<input id="ap_paterno" name="ap_paterno" type="text" value="<?= $ape1?>" placeholder="Apellido Paterno" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
			<div class="3u 12u$(xsmall)">
			<label for="ap_materno" name="lblap_materno">Segundo apellido</label>
			<input id="ap_materno" name="ap_materno" type="text" value="<?= $ape2 ?>" placeholder="Apellido Materno" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>
			<div class="2u 12u$(xsmall)">
			<label for="genero" name="lblgenero">Sexo</label>
			<div class="select-wrapper">
			<select id="genero" name="genero" >
					<option value="<?= $sex ?>"><?= $sex ?></option>
					<option value="MUJER">MUJER</option>
					<option value="HOMBRE">HOMBRE</option>					
			</select>
			</div></div>
			</div>
			<div class="row uniform">					
			<div class="4u 12u$(xsmall)">
			<label for="fecha_nac" name="lblfecha_nac">Fecha de nacimiento</label>
			<input id="fecha_nac" name="fecha_nac" type="text" value="<?= $fechaNa ?>" placeholder="Fecha de nacimiento   dd/mm/aaaa" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
			</div>						
			<div class="4u 12u$(xsmall)">
			<label for="lugar_nac" name="lbllugar_nac">Lugar de nacimiento</label>
			<input id="lugar_nac" name="lugar_nac" type="text" value="<?= $lugNac ?>" placeholder="Lugar de nacimiento" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
			<div class="4u 12u$(xsmall)">
			<label for="lugar_reg" name="lbllugar_reg">Lugar de registro</label>
			<input id="lugar_reg" name="lugar_reg" type="text" value="<?= $lugReg ?>" placeholder="Lugar de registro" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
		</div> 
		</div>
		<div class="row uniform">
			<div class="6u">
				<div class="select-wrapper">
					<select id="country_id" class="form-control" name="country_id">
                        <?php if($numName==0) {?>
      						<option value="">-- Seleccione --</option>
                        <?php } else { ?> 
                            <option value="<?=$claveEdo?>"><?=$esNac?></option>
                        <?php } ?>
						<?php foreach($countries as $c):?>
      					<option value="<?php echo $c->clave; ?>"><?php echo $c->estado; ?></option>
						<?php endforeach; ?>
    				</select>
				</div>
			</div>
		</div>
			<?php } ?>
			<br>
			<div class="box">
			<div class="row uniform">
			<div class="6u 12u$(xsmall)">
			<input id="responna" name="responna" type="text" value="<?php echo $row['responna']; ?>" placeholder="responsable del nna" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
			<div class="6u 12u$(xsmall)">
			<input id="parentesco" name="parentesco" type="text" value="<?php echo $row['parentesco']; ?>" placeholder="Parentesco" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
			<div class="6u 12u$(xsmall)">
			<input id="direccion" name="direccion" type="text" value="<?php echo $row['direccion']; ?>" placeholder="Dirección" required="required" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">	
			</div>
			<div class="6u 12u$(xsmall)">
			<input id="telefono" name="telefono" type="text" value="<?php echo $row['telefono']; ?>" placeholder="Teléfono" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"> 
			<?php $idloc=$row['id_localidad']; $loc=$row['localidad']; ?>
			
			</div>
			</div>
			</div>
			<div class="box">
			<div class="row uniform">
			<div class="6u 12u$(xsmall)">Municipio
			<div class="select-wrapper">
			<select id="country_id" class="form-control" name="country_id" required>
					<option value="<?php echo $row['id_municipio']; ?>"><?php echo $row['municipio'];  ?></option>
					<?php while ($row=$equery->fetch_assoc()) { ?>					
     					<option value="<?php echo $row['id']; ?>"><?php echo $row['municipio']; ?></option>
					<?php } ?>					
			</select>
			</div>
			</div>
			<div class="6u 12u$(xsmall)">Localidad
			<div class="select-wrapper">
			<select id="state_id" class="form-control" name="state_id" required>
					<option value="<?php echo $idloc; ?>"><?php echo $loc; ?></option>
			</select>
			</div>
			</div>
		</div>
	</div>
			<div class="12u$">
			<ul class="actions">
			<input class="button special fit" name="registar" type="submit" value="Actualizar" >
			<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='perfil_nna.php?id=<?php echo $idNNA; ?>'" >
			</ul></div>

			
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
						
	<?php if($bandera) { 
			header("Location: perfil_nna.php?id=$idNNA");
			}else{ ?>
			<br />
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>			
		<?php } ?>
		
			</div>					
		</div>						

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">

							
								<?php if($_SESSION['departamento']==7) { ?> 
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												<li><a href="lista_personal.php">Ver</a></li>
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												<li><a href="registro_usuarios.php">Alta</a></li>
												
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>
														
								<?php }elseif ($_SESSION['departamento']==16) { ?>
							
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												<li><a href="registro_personal.php">Alta</a></li>
												<li><a href="atenciones_area.php">Atenciones</a></li>
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>						
								
								<?php }else { ?>
								<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menú</h2>
									</header>
									<ul><li><a href="welcome.php">Inicio</a></li>
										<li><span class="opener">Departamentos</span>
											<ul>
												
												
												
											</ul>
										</li>
										<li><span class="opener">Usuarios</span>
											<ul>
												
												<li><a href="lista_usuarios.php">Ver</a></li>
												
											</ul>
										</li>
										<li><a href="logout.php" ">Cerrar sesión</a></li>
									</ul>
								</nav>		
							
								<?php }
	
								?>
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