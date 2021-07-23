<?php 	
	session_start();
    require 'conexion.php';
    if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];
	$Reporte=$_SESSION['reporte'];
	$idPC=$_GET['idPC'];
	$name=$_SESSION['name'];
	$qNna="SELECT concat(n.nombre,' ', n.apellido_p,' ', n.apellido_m) as nom_nna, p.folio, date_format(p.fecha_registro, '%d/%m/%Y') as fecha
	from nna_reportados n inner join posible_caso p on p.id=n.id_posible_caso
	where p.id=$idPC";
	$rNna=$mysqli->query($qNna);
	$numNna=$rNna->num_rows;
	if($numNna==0){
		$qNna="SELECT n.nom_nna, p.folio, date_format(p.fecha_registro, '%d/%m/%Y') as fecha
		from reportes_vd n inner join posible_caso p on p.id=n.id_posible_caso
		where p.id=$idPC";
		$rNna=$mysqli->query($qNna);
	}
	$rfolio=$mysqli->query($qNna);
	while($rowfolio=$rfolio->fetch_assoc()){
		$folio=$rowfolio['folio'];
	}
	if(!empty($_POST)){
		//obtener datos a traves de un array que pase desde la pagina anterior, en esta pagina se hara el registro
		$fechaReg=$Reporte["fecha_registro"];
		$recepcion=$Reporte["id_recepcion"]; 
		$distrito=$Reporte["id_distrito"];
		$maltrato=$Reporte["id_maltrato"];
		$persona_reporte=$Reporte["persona_reporte"];
		$narracion=$Reporte["narracion"];
		$idmunicipio=$Reporte["clm"];
		$idLocalidad=$Reporte["id_localidad"]; 
		$calle=$Reporte["calle"]; 
		$ubicacion=$Reporte["ubicacion "]; 
		$otros=$Reporte["otros_datos"];
		$entidad=$Reporte["entidad"]; 
		$cp=$Reporte["codigo_postal"];
		$idAsentamiento=$Reporte["id_tipo_asentamiento"]; 
		$nombreAsentamiento=$Reporte["nombre_asentamiento"];
		$idCalle=$Reporte["id_tipo_calle"]; 
		$numExt=$Reporte["num_ext"];
		$numInt=$Reporte["num_interior"];
		$sqlf="SELECT max(id) from reportes_vd";
	  	$esqlf=$mysqli->query($sqlf);
	  	while ($row=$esqlf->fetch_assoc()) {
		  $num=$row['max(id)'];	}
    	$idReporte=$num+1;
	  	$folioRep="RP0".$idReporte;	
    	$qrelacion="INSERT INTO reportes_vd (folio, fecha_registro, id_recepcion, id_distrito, maltratos,
    	persona_reporte, narracion, clm, id_localidad, calle, ubicacion, otros_datos, respo_reg, entidad, codigo_postal,
    	id_tipo_asentamiento, nombre_asentamiento, id_tipo_calle, num_ext, num_interior, id_posible_caso)
    	values ('$folioRep','$fechaReg','$recepcion','$distrito', '$maltrato', '$persona_reporte','$narracion',
    	'$idmunicipio', '$idLocalidad', '$calle', '$ubicacion','$otros','$idDEPTO', '$entidad' , '$cp',
		'$idAsentamiento', '$nombreAsentamiento', '$idCalle', '$numExt', '$numInt','$idPC')";
		$resultado=$mysqli->query($qrelacion);
		if ($resultado){
			//$_SESSION['reporte']=null;
			

			if(!empty($name)){
				header("Location: vincular_name-pc.php?idPC=$idPC&idReporte=$idReporte"); }
			 else {
		header("Location: perfil_posible_caso.php?idPosibleCaso=$idPC"); }
		} else echo "Error: ".$qrelacion;	
	}
?>

<html>
	<html lang="es-ES" class="no-js">
	<head>
		<title>Vincular reporte</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/login.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />

	</head>
	
	<body>
		 <div class="page-container">			
			<h2>¿Seguro desea vincular este reporte al posible caso con folio <?=$folio?>?</h2>
				<h4>NNA vinculados al posible caso</h4>
				<div class="row uniform">
					<div class="4u 12$(xsmall">
					</div>
					<div class="4u 12$(xsmall">
						<table>
							<tr><td>
							<?php 
								while($row=$rNna->fetch_assoc()){
									echo $row['nom_nna'].",  ";
								}
							?>
							</td></tr>
						</table>
					</div>
					<div class="4u 12$(xsmall">
					</div>
				</div>		
				<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >	<div class="12u$">
						<ul class="actions">
							<input class="button special fit" name="registar" type="submit" value="Aceptar" >
							<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='busqueda_reportes_similares.php'" >
						</ul>
					</div>
				</form>				
		</div>
	<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>
	</body>
</html>		