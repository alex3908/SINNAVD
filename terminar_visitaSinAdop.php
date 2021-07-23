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
	
	$sql = "SELECT responsable, id_depto, id_personal FROM departamentos WHERE id= '$idDEPTO'";
	$result=$mysqli->query($sql);
	$idHisto=$_GET['id'];

	$qperfil = "SELECT perfil FROM departamentos WHERE id= '$idDEPTO'";
	$rperfil=$mysqli->query($qperfil);
	$Apersona=$rperfil->fetch_assoc();
	$perfil= implode($Apersona);
	
	$qTipoV = "SELECT asunto FROM historial WHERE id= '$idHisto'";
	$rTipoV=$mysqli->query($qTipoV);
	$Tipo=$rTipoV->fetch_assoc();
	$mostrarTipo= implode($Tipo);
	$ahora= date ("h:i");
$hoy= date("Y-m-d");
$fecha_salida = date("Y-m-d H:i:s", time());	


	while ($row=$result->fetch_assoc()) {
		
		$listaUsuarios="SELECT historial.id, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida,
		historial.asunto_aud, historial.respuesta_aud, departamentos.responsable 
		FROM usuarios, depto, historial inner join departamentos on historial.responsable=departamentos.id
		WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.id='$idHisto'";
		$idd=$row['id_depto'];
		$rUsuarios=$mysqli->query($listaUsuarios);
	}

	if ($idd>=18 && $idd<=33) {
		$idd='10';
	}else {}

	$consulta="SELECT atencion FROM atenciones order by atencion";
	$econsulta=$mysqli->query($consulta);



$bandera = false;
if(!empty($_POST))
	{
		$requisitosAdp = false;
		/*$fechaS= $_POST['fecha'];
		$horaa= $_POST['hora'];
		$objfecha= date_create_from_format('Y-m-d', $fechaS);
		$fechaa=date_format($objfecha, "j/n/Y");
		$horaa= $_POST['hora'];*/
		$error = '';
		if($mostrarTipo=='INICIAL' and $perfil=='1'){
		$asunto_aud= mysqli_real_escape_string($mysqli,$_POST['Asunto']);	
		$respuesta_aud= mysqli_real_escape_string($mysqli,$_POST['Respuesta']);	}
		else {
			$asunto_aud=null;
			$respuesta_aud=null;
		}
		$llamada= $_POST['llamada'];


		foreach((array)@$_POST["atenci"] as $valor){ 
			@$ate_brindada=$valor.", ".$ate_brindada;
			if($valor == 'REQUISITOS DE ADOPCION')
				$requisitosAdp = true;

		}
		if (empty($ate_brindada)) {
			$error="Seleccione al menos una atenci&oacute;n";
		}else{
		
			$sqlup = "UPDATE historial SET fecha_salida='$fecha_salida', atencion_brindada='$ate_brindada', asunto_aud='$asunto_aud', respuesta_aud='$respuesta_aud', atencionTelefonica='$llamada'  WHERE id='$idHisto'";
			$resultUp = $mysqli->query($sqlup);
			
			if($resultUp)
			$bandera = true;
			else
			$error = "Error Terminar: ".$sqlup;
			}
		}
	
	
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Finalizar visita</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript">
	function showContent() {
		element = document.getElementById("content");
		check = document.getElementById("check");
		if (check.checked) {
			element.style.display='block';
		}
		else {
			element.style.display='none';
		}
	}
	</script>
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
							<?php while($row=$rUsuarios->fetch_assoc()){ ?>
								<form id="registro" enctype="multipart/form-data" name="registro" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" > 			
								<h2><?php echo $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];?></h2>
											<ul class="alt">
												<li><strong>Departamento: </strong><?php echo $row['departamento'];?></li>		
												<li><strong>Responsable: </strong><?php echo $row['responsable'];?></li>
												<li><strong>Asunto: </strong><?php echo $row['asunto'];?></li>
												
												<li><strong>Fecha de entrada: </strong><?php echo $row['fecha_ingreso'];?></li><?php } ?>
												<li><strong>Atención brindada: </strong><br>
												<div class="row uniform">


				<?php $i=0;
			while($row= $econsulta->fetch_assoc())
		{	
			?>
				
					<div class="6u 12u$(xsmall)">

					<input type="checkbox" id="<?php echo 'demo-'.$i; ?>" name="atenci[]" value="<?php echo $row['atencion'];?>" >
							<label for="<?php echo 'demo-'.$i; ?>"><?php echo $row['atencion'];?></label>	</div> 
    <?php 
		$i++;	}
		if($mostrarTipo=='INICIAL' and $perfil=='1'){  ?> </div>
		<div class=row>
			<div class="6u 12u$(xsmall)">Asunto: 
				<textarea name="Asunto" maxlength="500" rows="5" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
			</div>
		
			<div class="6u 12u$(xsmall)">Respuesta:
			<textarea name="Respuesta" maxlength="500" rows="5" cols="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>				
			</div>
		</div>
	<?php 	}
  	?>
	  
			</li></ul>
			<div class="box">
			<div class="row uniform">
	  			<div class="4u 12u$(xsmall)">¿Fue una asesoria telefónica?  <br>
	  				<input type="radio" id="Si" name="llamada" value="1"  >
							<label for="Si">Si</label></th> 								
						<th><input type="radio" id="No" name="llamada" value="0" checked>
							<label for="No">No</label></th>
	  			</div>
	  			<div class="4u 12u$(xsmall)">Fecha salida
	  				<input id="fecha" name="fecha" type="date" min="2020-04-01" max="<?php echo $hoy; ?>" value="<?php echo $hoy; ?>" disabled>

	  			</div>
	  			<div class="4u 12u$(xsmall)">Hora de salida
	  				<input id="hora" name="hora" type="text" pattern="[0-9:]{5,5}$"  value="<?php echo $ahora; ?>" disabled>

	  			</div>
			</div>
		</div><br>

												<input type="submit" class="button special small" name="Terminar visita" value="Terminar visita" >
									</form>			
									
									<?php if($bandera) { 
										
											header("Location:welcome.php");

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