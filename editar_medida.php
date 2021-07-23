<?php 
	
	session_start();
	require 'conexion.php';
	$idDEPTO = $_SESSION['id'];
	$id=$_GET['id'];
	$idCaso=$_GET['idCaso'];
	
	$sqlNino = "SELECT derechos_nna.derecho, cuadro_guia.marco, cuadro_guia.med_prot, cuadro_guia.beneficiario, cuadro_guia.responsable_med, cuadro_guia.atp_encargada, cuadro_guia.periodicidad, cuadro_guia.fecha, departamentos.responsable, cuadro_guia.id_derecho from derechos_nna, departamentos, cuadro_guia where cuadro_guia.id='$id' and derechos_nna.id=cuadro_guia.id_derecho and departamentos.id=cuadro_guia.id_sp_registro";
	$resultNino = $mysqli->query($sqlNino);

	
	if(!empty($_POST))
	{

		$marco = mysqli_real_escape_string($mysqli,$_POST['marco']);
		$med_prot = mysqli_real_escape_string($mysqli,$_POST['med_prot']);
		
		$responsable_med = mysqli_real_escape_string($mysqli,$_POST['responsable_med']);
		$atp_encargada = mysqli_real_escape_string($mysqli,$_POST['atp_encargada']);
		$periodicidad = mysqli_real_escape_string($mysqli,$_POST['periodicidad']);

		$update="UPDATE cuadro_guia set marco='$marco', med_prot='$med_prot', responsable_med='$responsable_med', atp_encargada='$atp_encargada', periodicidad='$periodicidad' where id='$id'";
		$eupdate=$mysqli->query($update);
	
	header("Location: cuadro_guia.php?id=$idCaso");
	}
	
?>

<html>
	<html lang="en" class="no-js">
	<head>
		<title>Eliminar</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />

	</head>
	
	<body>
		 <div class="page-container">			
						
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="row uniform">
                    <div class="12u$">DERECHO VULNERADO O RESTRINGIDO
										<div class="select-wrapper">
										<?php while ($row=$resultNino->fetch_assoc()) { ?>
											<select id="derecho" name="derecho" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled>
											<option value="<?php echo $row['id_derecho'];?>"><?php echo $row['derecho'];  ?></option>
												
											</select>
										</div>
									</div>
                    <div class="12u$">MARCO JURIDICO
                    <textarea name="marco" cols="" rows="" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required><?php echo $row['marco']; ?></textarea>
                    </div>
                    <div class="12u$">MEDIDA DE PROTECCIÓN ESPECIAL
                    <textarea name="med_prot" cols="" rows="" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required><?php echo $row['med_prot']; ?></textarea>
                    </div>

                    <div class="12u$">INSTITUCIÓN O PERSONA RESPONSABLE
                    <input name='responsable_med' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $row['responsable_med']; ?>" required>
                    </div>
                    <div class="12u$">AREA, TITULAR O PERSONA ENCARGADA DE LLEVARLA ACABO
                    <input name='atp_encargada' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $row['atp_encargada']; ?>" required>
                    </div>
                    <div class="12u$">PERIODICIDAD
                    <input name='periodicidad' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $row['periodicidad']; ?>" required>
                    </div>
                    <div class="12u$">FECHA DE REGISTRO
                    <input name='fecha' type='text'  value="<?php echo $row['fecha']; ?>" disabled >
                    </div>
                    <div class="12u$">RESPONSABLE DE REGISTRO
                    <input name='id_sp_registro' type='text' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?php echo $row['responsable']; }?>" disabled>
                    </div>                    
				<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Actualizar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='cuadro_guia.php?id=<?php echo $idCaso; ?>'" >
						</ul>
					</div></div>
				</form>				
		</div>
	<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		