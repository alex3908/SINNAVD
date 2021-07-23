<?php	
	session_start();
	require 'conexion.php';
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	
	$pv="SELECT responsable, id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
		$persona=$row['responsable'];
	}

	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;

	$fecha_fin = date("Y-m-d");
	$fecha_ini = date("Y-m-d", strtotime($fecha_fin."- 1 month"));
	$año= date("Y");
	$mesesq = "SELECT id_corte, mes, date_format(fechai, '%Y-%m-%d') as fechai, date_format(fechac, '%Y-%m-%d') as fechac from cortes where año=$año order by idXaño";
	$qmeses = $mysqli->query($mesesq);
	$qmeses2 = $mysqli->query($mesesq);
	$numMeses=$qmeses->num_rows;
	for ($i=1; $i<=$numMeses; $i++)  
		{  
			$vermes=$qmeses2->fetch_assoc();  
			$meses[$i] = $vermes["mes"];  
		}  
			 
	$vecMeses = array();
	while($r=$qmeses->fetch_assoc()){
		$vecMeses['1'][] = $r;
	}
	/*$vecMeses = mysqli_fetch_all($meses);
	var_dump($vecMeses);*/
	$Consulta = false;
	$error ="";
	if(!empty($_POST['btnConsulta'])){
		$anio = $_POST['anio'];
		$anioq = "SELECT distinct año from cortes where año!=2019 and año!=2020 and año!=$anio";
		$anioq = $mysqli->query($anioq);
		
		$fechasi = "SELECT fechai from cortes where año=$anio and idXaño=1";
		$fechasi= $mysqli->query($fechasi);
		while ($rf1=$fechasi->fetch_assoc()) {
			$fecha_ini1=$rf1['fechai'];
		}
		
		$fechas = "SELECT MAX(fechac) as fechac FROM cortes where año=$anio";
		$fechas= $mysqli->query($fechas);
		while ($rf2=$fechas->fetch_assoc()) {
			$fecha_fin1=$rf2['fechac'];
		}
		$Consulta = true;
		$datos = "SELECT sum(p1_1) as p1_1, sum(p1_2) as p1_2, sum(a3) as a3, sum(a7_1) as a7_1, sum(a7_2) as a7_2, sum(a8) as a8, sum(a9) as a9, sum(a10_1) as a10_1, sum(a10_2) as a10_2, sum(a11_1) as a11_1, sum(a11_2) as a11_2, sum(a12_1) as a12_1, sum(a12_2) as a12_2, sum(a13) as a13, sum(a14_1) as a14_1, sum(a14_2) as a14_2, sum(a15) as a15, sum(a16_1) as a16_1, sum(a16_2) as a16_2, sum(a17_1) as a17_1 , sum(a17_2) as a17_2, sum(a18_1) as a18_1, sum(a18_2) as a18_2, sum(a19_1) as a19_1, sum(a19_2) as a19_2, sum(a20) as a20, sum(a21) as a21, sum(a22_1) as a22_1, sum(a22_2) as a22_2, sum(a23_1) as a23_1, sum(a23_2) as a23_2, sum(a24_1) as a24_1, sum(a24_2) as a24_2, sum(a28_1) as a28_1, sum(a28_2) as a28_2 from datos_reportados inner join cortes on cortes.id_corte=datos_reportados.id_fecha_corte where fechai between '$fecha_ini1' and '$fecha_fin1'";
		$datos =  $mysqli->query($datos);
		$registrado = $datos->num_rows;

			
		$idDatos = null;
		$nnaRest= 'SIN DATO';
		$p1_2= 'SIN DATO';
		$totAct3_2= 'SIN DATO';
		$a7_1= 'SIN DATO';
		$a7_2= 'SIN DATO';
		$totAct8= 'SIN DATO';
		$a9= 'SIN DATO';
		$totAct10_1= 'SIN DATO';
		$a10_2= 'SIN DATO';
		$a11_1= 'SIN DATO';
		$a11_2= 'SIN DATO';
		$a12_1= 'SIN DATO';
		$a12_2= 'SIN DATO';
		$a13= 'SIN DATO';
		$a14_1= 'SIN DATO';
		$a14_2= 'SIN DATO';
		$a15= 'SIN DATO';
		$a16_1= 'SIN DATO';
		$a16_2= 'SIN DATO';
		$a17_1= 'SIN DATO';
		$a17_2= 'SIN DATO';
		$a18_1= 'SIN DATO';
		$a18_2= 'SIN DATO';
		$a19_1= 'SIN DATO';
		$a19_2= 'SIN DATO';
		$a20= 'SIN DATO';
		$a21= 'SIN DATO';
		$a22_1= 'SIN DATO';
		$a22_2= 'SIN DATO';
		$a23_1= 'SIN DATO';
		$a23_2= 'SIN DATO';
		$a24_1= 'SIN DATO';
		$a24_2= 'SIN DATO';
		$a28_1= 'SIN DATO';
		$a28_2='SIN DATO';

		$qtotComp1 = "SELECT count(benefmed.id_medida) from benefmed inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida inner join nna on nna.id=benefmed.id_nna where  cuadro_guia.fecha_ejecucion BETWEEN '$fecha_ini1' and '$fecha_fin1' and cuadro_guia.activo=1 and cuadro_guia.estado=1 and nna.activo=1";
		$totComp1 = $mysqli->query($qtotComp1);
		$totComp1=implode($totComp1->fetch_assoc());
		$qtotComp2 = "SELECT count(benefmed.id_medida) from benefmed inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida inner join nna on nna.id=benefmed.id_nna where  cuadro_guia.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1' and cuadro_guia.activo=1 and nna.activo=1";
		$totComp2=$mysqli->query($qtotComp2);
		$totComp2 = implode($totComp2->fetch_assoc());
		$qact1_1 = "SELECT pc.id as idPc, pc.folio as poscaso, r.folio, cr.recepcion, date_format(hpc.fechaAtencion, '%d/%m/%Y %H:%i:%s') as fecha, r.narracion, hpc.estadoAtencion as atendido	from reportes_vd r inner join posible_caso pc on pc.id=r.id_posible_caso inner join cat_recepcion_reporte cr on r.id_recepcion=cr.id left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion left join historico_asignaciones_juridico hj on hj.id=pc.id_asignado_juridico left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps where r.activo=1 and (hpc.fechaAtencion BETWEEN '$fecha_ini1' and '$fecha_fin1') and hpc.estadoAtencion in('4')";
		$act1_1 = $mysqli->query($qact1_1);
		$totAct1_1 = $act1_1->num_rows;

		$qact1_2 ="SELECT pc.id as idPc,  pc.folio as poscaso, r.id, r.folio, cr.recepcion, r.narracion, date_format(r.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, hpc.estadoAtencion as atendido from reportes_vd r inner join cat_recepcion_reporte cr on r.id_recepcion=cr.id inner join posible_caso pc on pc.id=r.id_posible_caso left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion where r.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1' and r.activo=1";
		$act1_2 = $mysqli->query($qact1_2);
		$totAct1_2 = $act1_2->num_rows;

		$qact2_1="SELECT count(af.id) as total
			from acercamiento_familiar af 
			where af.activo=1 and af.fecha_registro between '$fecha_ini1' and '$fecha_fin1'			
			and af.inter is not null";
		$act2_1 = $mysqli->query($qact2_1);
		$totAct2_1= "SELECT sum(ac.inter) as total from posible_caso pc inner join acercamiento_familiar ac on ac.id_reporte=pc.id where ac.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1'";
		$totAct2_1  = $mysqli->query($totAct2_1);
		$totAct2_1 = implode($totAct2_1->fetch_assoc());

		$qact2_2 = "SELECT count(r.id) as total from reportes_vd r  where r.activo=1 and r.id_recepcion!=1 and r.id_recepcion!=7 and (r.fecha_registro between '$fecha_ini1' and '$fecha_fin1')";
		$act2_2 = $mysqli->query($qact2_2);
		$totAct2_2= $act2_2->num_rows;

		$qact3_1="SELECT pc.folio , date_format(ap.fecha_registro, '%d/%m/%Y') as fecha, ap.tipo, if(ase_virtual=1, 'SI', 'NO') as acVirtual, nna_ac.nombre, nna_ac.apellido_p, nna_ac.apellido_m FROM acercamiento_psic ap inner join posible_caso pc  on pc.id=ap.id_reporte inner join nna_ac on nna_ac.id=ap.id_nna where ap.activo=1 and ap.fecha_registro between '$fecha_ini1' and '$fecha_fin1' and pc.activo=1";
		$act3_1 = $mysqli->query($qact3_1);
		$totAct3_1 = $act3_1->num_rows;

			
		$qact4 = "SELECT count(historial.id) as total from  historial where historial.fecha_ingreso between '$fecha_ini1' and '$fecha_fin1' and ( historial.atencion_brindada like '%ORIENTACION JURIDICA%') and historial.asunto='INICIAL'";
		$act4 = $mysqli->query($qact4);
		$totAct4 = $act4->num_rows;

		$qact5 = "SELECT count(nuc) as total from  carpeta_inv c where c.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1'";
		$act5_1 = $mysqli->query($qact5);
		$totAct5 = implode($act5_1->fetch_assoc());

		$qact6_1 ="SELECT casos.id as idC, cuadro_guia.id as idM, casos.folio_c, nna.nombre, nna.apellido_p, nna.apellido_m, catalogo_medidas.medidaC, seguimientos.area, seguimientos.seguimiento, date_format(seguimientos.fecha_registro, '%d/%m/%Y') as fecha from seguimientos left join cuadro_guia on seguimientos.id_med=cuadro_guia.id left join catalogo_medidas on catalogo_medidas.id=cuadro_guia.id_mp left join casos on casos.id=cuadro_guia.id_caso left join benefmed on benefmed.id_medida=cuadro_guia.id	left join nna on nna.id=benefmed.id_nna where (seguimientos.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1') and seguimientos.activo=1";
		$act6_1 = $mysqli->query($qact6_1);
		$totAct6 = $act6_1->num_rows;

		$qact6_2 = "SELECT casos.id, casos.folio_c, derechos_nna.derecho, medidas.medida_p, catalogo_medidas.medidaC, nna.nombre, nna.apellido_p, nna.apellido_m, cuadro_guia.responsable_med, date_format(cuadro_guia.fecha_registro, '%d/%m/%Y') as fecha, cuadro_guia.estado from  casos inner join cuadro_guia  on cuadro_guia.id_caso=casos.id inner join benefmed on benefmed.id_medida = cuadro_guia.id inner join nna on nna.id=benefmed.id_nna inner join derechos_nna on derechos_nna.id=cuadro_guia.id_derecho inner join medidas on medidas.id=cuadro_guia.id_medida inner join catalogo_medidas on catalogo_medidas.id=cuadro_guia.id_mp where cuadro_guia.activo=1 and (cuadro_guia.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1') and casos.activo=1 and nna.activo=1";
		$act6_2 = $mysqli->query($qact6_2);
		$totAct6_2 = $act6_2->num_rows;

			
			
		$qact25 = "SELECT cuadro_guia.id, nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, if(fecha_nacimiento, 'SIN DATO', date_format(fecha_nacimiento, '%d/%m/%Y')) as fecha_nacimiento, sexo, date_format(cuadro_guia.fecha_registro,'%d/%m/%Y') as fecha_registro from cuadro_guia left join benefmed on benefmed.id_medida=cuadro_guia.id left join nna on nna.id=benefmed.id_nna where cuadro_guia.id_mp='28' and (cuadro_guia.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1') and cuadro_guia.activo=1";
		$act25 = $mysqli->query($qact25);
		$totAct25= $act25->num_rows;

		$qact26_1 = "SELECT cuadro_guia.id, nna.folio, nna.lugar_reg, nna.nombre, nna.apellido_p, nna.apellido_m, if(fecha_nacimiento, 'SIN DATO', date_format(fecha_nacimiento, '%d/%m/%Y')) as fecha_nacimiento, sexo, date_format(cuadro_guia.fecha_registro, '%d/%m/%Y') as fecha_registro from cuadro_guia left join benefmed on benefmed.id_medida=cuadro_guia.id left join nna on nna.id=benefmed.id_nna where cuadro_guia.id_mp='30' and (cuadro_guia.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1') and cuadro_guia.activo=1";
		$act26_1= $mysqli->query($qact26_1);
		$totAct26_1 = $act26_1->num_rows;

		$qact26_2 = "SELECT count(nna.id) as total from nna where nna.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1' and migrante=1 and nna.activo=1";
		$act26_2 = $mysqli->query($qact26_2);
		$totAct26_2 = implode($act26_2->fetch_assoc());
		$qact27_1 ="SELECT  sum(na+ni+adm+adh+am+ah) as total FROM ccpi where fecha_reg BETWEEN '$fecha_ini1' and '$fecha_fin1'";
		$act27_1 = $mysqli->query($qact27_1);
		$tot27_1 = implode($act27_1->fetch_assoc());
		$qact27_2 = "SELECT count(ccpi.id) as total from ccpi  where fecha_reg BETWEEN '$fecha_ini1' and '$fecha_fin1'";
		$act27_2 = $mysqli->query($qact27_2);
		$totAct27_2 = implode($act27_2->fetch_assoc());


		if($registrado>0){
			while ($row=$datos->fetch_assoc()) {
				$nnaRest=$row['p1_1'];
				$p1_2=$row['p1_2'];
				$totAct3_2=$row['a3'];
				$a7_1=$row['a7_1'];
				$a7_2=$row['a7_2'];
				$totAct8=$row['a8'];
				$a9=$row['a9'];
				$totAct10_1=$row['a10_1'];
				$a10_2=$row['a10_2'];
				$a11_1=$row['a11_1'];
				$a11_2=$row['a11_2'];
				$a12_1=$row['a12_1'];
				$a12_2=$row['a12_2'];
				$a13=$row['a13'];
				$a14_1=$row['a14_1'];
				$a14_2=$row['a14_2'];
				$a15=$row['a15'];
				$a16_1=$row['a16_1'];
				$a16_2=$row['a16_2'];
				$a17_1=$row['a17_1'];
				$a17_2=$row['a17_2'];
				$a18_1=$row['a18_1'];
				$a18_2=$row['a18_2'];
				$a19_1=$row['a19_1'];
				$a19_2=$row['a19_2'];
				$a20=$row['a20'];
				$a21=$row['a21'];
				$a22_1=$row['a22_1'];
				$a22_2=$row['a22_2'];
				$a23_1=$row['a23_1'];
				$a23_2=$row['a23_2'];
				$a24_1=$row['a24_1'];
				$a24_2=$row['a24_2'];
				$a28_1=$row['a28_1'];
				$a28_2=$row['a28_2'];
			}
		}
		else 
		{
			$qProp1 = "SELECT nna.id FROM nna_restituidos r inner join nna on nna.id=r.id_nna where r.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1'";
			$Prop1=$mysqli->query($qProp1);
			$nnaRest = $Prop1->num_rows;

			$qact3_2 = "SELECT nna.folio from nna where nna.activo=1 and nna.fecha_registro between '$fecha_ini1' and '$fecha_fin1' order by curp asc";
			$act3_2 = $mysqli->query($qact3_2);
			$totAct3_2 = $act3_2->num_rows;
			$qact8 = "SELECT s.id FROM supervisiones s  where (fecha_sup BETWEEN '$fecha_ini1' and '$fecha_fin1')";
			$act8 = $mysqli->query($qact8);
			$totAct8 = $act8->num_rows;
			$qact10_1 = "SELECT nna.id FROM nna_centros INNER JOIN centros on nna_centros.id_centro=centros.id INNER JOIN nna on nna.id=nna_centros.id_nna WHERE centros.tipo LIKE '%PRIVADO%' and (nna_centros.fecha_ing BETWEEN '$fecha_ini1' and '$fecha_fin1')";
			$act10_1 = $mysqli->query ($qact10_1);
			$totAct10_1 = $act10_1->num_rows;
		}
	
	}


?>
<!DOCTYPE HTML>
<html> 
	<head lang="es-MX">
		<title>Perfil</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>

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
					<!--cod -->
					<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
						<div class="row uniform">
							<div class="8u">
								<h3>Informes reportados previamente</h3>
							</div>
							<div class="4u">
								<?php if($idDepartamento==16 and $idPersonal==1) { ?>
									<input class="button fit" type="button" name="editar" value="Editar/Llenar datos" onclick="location='llenar_informe_planeacion.php'" >
								<?php } ?>
							</div>
						</div>
						<div class="row uniform">
							<div class="12u">
								<h4>Seleccione el periodo</h4>
							</div>
						</div>
						<div class="row uniform">
							<div class="4u">
								<div class="select-wrapper">
									<select id="anio" name="anio" required>
										<?php if(empty($anio)) { ?>
											<option value="2021">2021</option>
										<?php } else { ?>
											<option value="<?=$anio?>"><?=$anio?></option>
											<?php while ($rwa=$anioq->fetch_assoc()) { ?>
												<option value="<?=$rwa['año']?>"><?=$rwa['año']?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>
							
							<div class="4u">
								<input type="submit" value="Consultar" name="btnConsulta" class="button special fit">
							</div>
							<div class="4u">
								<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='reporte1ra.php'" >
							</div>
						</div>
						<div class="row">
							<div class="12u">
								<?= $error?>
							</div>
						</div>
						<?php if($Consulta) { ?>
							<br>
							<div class="row">
								<div class="2u">
									<b>NIVEL DE LA MIR</b>
								</div>
								<div class="4u">
									<b>NOMBRE DEL INDICADOR</b>
								</div>
								<div class="4u">
									<b>UNIDAD DE MEDIDA</b>
								</div>
								<div class="2u">
									<b>TOTAL</b>
								</div>
							</div><br>
							<div class="row">
								<div class="2u">
									<br><br>PROPOSITO
								</div>
								<div class="4u">
									<br>Porcentaje de niñas, niños y adolescentes  que se les restituyen sus derechos respecto  a niñas, niños y adolescentes  con derechos vulnerados atendidos por la PPNNAyF
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											No. de  niñas, niños y adolescentes  que se restituyen sus derechos 
										</div>
										<div class="4u">
											<?= $nnaRest ?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Niñas, niños y adolescentes con derechos vulnerados atendidos por la Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia 
										</div>
										<div class="4u">
											<?= $p1_2 ?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row">
								<div class="2u">
									<br><br>COMPONENTE 1
								</div>
								<div class="4u">
									Porcentaje de acciones  en el plan de restituciòn de derechos a Niñas, Niños y Adolescentes realizadas respecto al total de acciones  en el plan de restituciòn de derechos a Niñas, Niños y Adolescentes contenidas
								</div>
								<div class="6u">
									<div class="row">
										<div class="8u">
											Acciones  en el plan de restituciòn de derechos a Niñas, Niños y Adolescentes realizadas
										</div>
										<div class="4u">
											<?= $totComp1 ?>
										</div>
									</div><hr>
									<div class="row">
										<div class="8u">
											Acciones  en el plan de restituciòn de derechos a Niñas, Niños y Adolescentes contenidas
										</div>
										<div class="4u">
											<?= $totComp2 ?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row">
								<div class="2u">
									<br><br>	ACTIVIDAD 1
								</div>
								<div class="4u">
									<br>	Porcentaje de reportes de casos de vulneración de derechos de  niñas, niños y adolescentes  que resultaron positivos en vulneración de derechos respecto a numero total de reportes de  casos de vulneración de derechos de niñas, niños y adolescentes  recibidos
								</div>
								<div class="6u">
									<div class="row">
										<div class="8u">
											Reportes de casos de vulneración de derechos de niñas, niños y adolescentes  que resultaron positivos en vulneración de derechos
										</div>
										<div class="4u">
											<?= $totAct1_1 ?>
										</div>
									</div><hr>
									<div class="row">
										<div class="8u">
											Reportes de  casos de vulneración de derechos de niñas, niños y adolescentes recibidos
										</div>
										<div class="4u">
											<?php if(empty($totAct1_2)) echo "0"; else echo $totAct1_2 ?>
										</div>
									</div>
								</div>
							</div><hr>
							
							<div class="row">
								<div class="2u">
									<br><br>ACTIVIDAD 2
								</div>
								<div class="4u">
									Promedio de intervenciones de trabajo social a niñas, niños y adolescentes en situación de vulneracion de derechos brindadas respecto al total de reportes de  casos de  posible  vulneración de derechos de niñas, niños y adolescentes recibidos
								</div>
								<div class="6u">
									<div class="row">
										<div class="8u">
											Intervenciones de trabajo social a niñas, niños y adolescentes en situacion de vulneracion de derechos realizadas
										</div>
										<div class="4u">
											<?php if(empty($totAct2_1)) echo "0"; else echo $totAct2_1 ?>
										</div>
									</div><hr>
									<div class="row">
										<div class="8u">
											Reportes de  casos de  posible  vulneración de derechos de niñas, niños y adolescentes recibidos
										</div>
										<div class="4u">
											<?= $totAct2_2 ?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row">
								<div class="2u">
									<br><br>ACTIVIDAD 3
								</div>
								<div class="4u">
									<br>Promedio de intervenciones psicológicas a niñas, niños y adolescentes en situacion de vulneracion de derechos brindadas respecto al total de niñas, niños y adolescentes con derechos vulnerados atendidos por primera vez en la PPNNAyF
								</div>
								<div class="6u">
									<div class="row">
										<div class="8u">
											Intervenciones psicológicas a niñas, niños y adolescentes en situación de vulneración de derechos brindadas
										</div>
										<div class="4u">
											<?= $totAct3_1 ?>
										</div>
									</div><hr>
									<div class="row">
										<div class="8u">
											Niñas, niños y adolescentes con derechos vulnerados atendidos por primera vez en  la Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia
										</div>
										<div class="4u">
											<?= $totAct3_2 ?>
										</div>
									</div>
								</div>
							</div><hr>
							
							<div class="row">
								<div class="2u">
									<br>ACTIVIDAD 4
								</div>
								<div class="4u">
									Porcentaje de orientaciones jurídicas para la protección y/o restitución de derechos realizadas respecto total de orientaciones jurídicas para la protección y/o restitución de derechos  programadas 
								</div>
								<div class="4u">
									<br>Orientaciones jurídicas para la protección y/o restitución de derechos  realizadas
								</div>
								<div class="2u">
									<?= $totAct4 ?>
								</div>
							</div><hr>
							
							<div class="row">
								<div class="2u">
									<br>ACTIVIDAD 5
								</div>
								<div class="4u">
									Porcentaje de representación coadyuvante  a niñas, niños y adolescentes brindada respecto al total de representaciones coadyuvante solicitadas
								</div>
								<div class="6u">
									<div class="row">
										<div class="8u">
											Representación coadyuvante  a niñas, niños y adolescentes brindada
										</div>
										<div class="4u">
											<?= $totAct5 ?>
										</div>
									</div><hr>
									<div class="row">
										<div class="8u">
											Representaciones coadyuvante  solicitadas
										</div>
										<div class="4u">
											<?= $totAct5 ?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row">
								<div class="2u"><br>
									ACTIVIDAD 6
								</div>
								<div class="4u"><br>
									Promedio de seguimientos realizados respecto a total de medidas  de proteccion decretas
								</div>
								<div class="6u">
									<div class="row">
										<div class="8u">
											Seguimientos realizados
										</div>
										<div class="4u">
											<?= $totAct6 ?>
										</div>
									</div><hr>
									<div class="row">
										<div class="8u">
											Medidas de protección en el plan de restitución de derechos a niñas, niños y adolescentes decretadas
										</div>
										<div class="4u">
											<?= $totAct6_2 ?>
										</div>
									</div>
								</div>
							</div><hr>

							<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 7
									</div>
									<div class="4u">
										<br>
										Porcentaje de procedimientos familiares tramitados respecto al total de procedimientos familiares requeridos
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Procedimientos familiares tramitados  
											</div>
											<div class="4u">
												<?=$a7_1?>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Procedimientos familiares requeridos
											</div>
											<div class="4u">
												<?=$a7_2?>
											</div>
										</div>
									</div>
								</div><hr>
							
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 8
								</div>
								<div class="4u">
									Porcentaje de supervisión de Centros de Asistencia Social realizada respecto al total supervisión de Centros de Asistencia Social programada
								</div>
								<div class="4u"><br>
									Supervisión de Centros de Asistencia Social realizada
								</div>
								<div class="2u"><br>
									<?=$totAct8 ?>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 9
								</div>
								<div class="4u">
									Porcentaje de autorización de funcionamiento a los Centros de Asistencia Social realizada respecto al total de autorizaciones de funcionamiento a los Centros de Asistencia Social programada
								</div>
								<div class="4u"><br>
									Autorización de funcionamiento a los Centros de Asistencia Social realizada
								</div>
								<div class="2u"><br>
									<?=$a9?>
								</div>
							</div><hr>

							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 10
								</div>
								<div class="4u">
									<br>
									Porcentaje de  ingresos de personas a los centros de asistencia social Privados realizadas respecto al total de ingresos de  personas en los centros de Asistencia Social Privados requeridos
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Ingresos de personas a los centros de asistencia social Privados realizados 
										</div>
										<div class="4u">
											<?=$totAct10_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Ingresos de personas en los centros de asistencia social  Privados requeridos
										</div>
										<div class="4u">
											<?=$a10_2?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 11
								</div>
								<div class="4u">
									<br>
									Promedio anual de gasto en cuota economica en los Centros de Asistencia Social Privados pagada respecto a las personas albergadas en los Centros de Asistencia Social Privados
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Cuotas económicas a los Centros de Asistencia Social Privados pagadas
										</div>
										<div class="4u">
											<?=$a11_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Personas albergadas en los Centros de Asistencia Social Privados
										</div>
										<div class="4u">
											<?=$a11_2?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 12
								</div>
								<div class="4u">
									<br>
									Porcentaje de solicitantes de adopción procedentes respecto al total de solicitantes de adopción iniciales
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Solicitantes de adopción procedentes
										</div>
										<div class="4u">
											<?=$a12_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Solicitantes de adopción iniciales
										</div>
										<div class="4u">
											<?=$a12_2?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 13
								</div>
								<div class="4u">
									Porcentaje de sesiones de psicología realizado respecto al total de  sesiones de psicología programado
								</div>
								<div class="4u"><br>
									Sesiones de psicología realizado
								</div>
								<div class="2u"><br>
									<?=$a13?>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 14
								</div>
								<div class="4u">
									<br>
									Porcentaje de valoraciones psicológicas de solicitantes de adopción idoneas respecto al total  valoraciones psicológicas de adopción aplicadas
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Valoraciones psicológicas de solicitantes de adopción idóneas
										</div>
										<div class="4u">
											<?=$a14_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Valoraciones psicológicas de adopción aplicadas
										</div>
										<div class="4u">
											<?=$a14_2?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 15
								</div>
								<div class="4u">
									Porcentaje de intervenciones de trabajo social realizadas respecto al total de intervenciones de trabajo social programadas
								</div>
								<div class="4u"><br>
									Intervenciones de trabajo social realizadas
								</div>
								<div class="2u"><br>
									<?=$a15?>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 16
								</div>
								<div class="4u">
									<br>
									Porcentaje de estudios socioeconómicos a solicitantes de adopción idoneo respecto al total de estudios socioeconómicos de adopción aplicados
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Estudios socioeconómico a solicitantes de adopción idoneo
										</div>
										<div class="4u">
											<?=$a16_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Estudios socioeconómicos de adopción aplicados
										</div>
										<div class="4u">
											<?=$a16_2?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 17
								</div>
								<div class="4u">
									<br>
									Porcentaje de informes de  solicitantes de adopcion idoneos  respecto al total de informes de solicitantes de adopcion emitidos
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Informes de  solicitantes de adopción idóneos
										</div>
										<div class="4u">
											<?=$a17_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Informes de solicitantes de adopción emitidos
										</div>
										<div class="4u">
											<?=$a17_2?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 18
								</div>
								<div class="4u">
									<br>
									Costo promedio del taller para futuros padres y madres adoptivos realizado respecto al total de  asistentes al curso taller para futuros padres y madres adoptivos 
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Costo total del taller  para futuros padres y madres adoptivos realizado
										</div>
										<div class="4u"> 
											<?=$a18_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Asistentes al curso taller para futuros padres y madres adoptivos 
										</div>
										<div class="4u">
											<?=$a18_2?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 19
								</div>
								<div class="4u">
									<br>
									Porcentaje de niñas, niños, adolescentes integrados respecto al total de niñas, niños y adolescentes suceptibles de adopción
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Niñas, niños, adolescentes adoptados 
										</div>
										<div class="4u">
											<?=$a19_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Niñas, niños y adolescentes suceptibles de adopción
										</div>
										<div class="4u">
											<?=$a19_2?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 20
								</div>
								<div class="4u">
									Porcentaje de entrevistas de Trabajo Social post adoptivo realizadas, respecto al total de entrevistas de Trabajo Social post adoptivo programadas
								</div>
								<div class="4u"><br>
									Entrevistas de Trabajo Social post adoptivo realizadas
								</div>
								<div class="2u"><br>
										<?= $a20 ?>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 21
								</div>
								<div class="4u">
									Porcentaje de visitas domiciliarias  de trabajo social post adoptivo realizadas respecto al total de visitas domiciliarias de seguimientos de trabajo social postadoptivo programadas
								</div>
								<div class="4u"><br>
									Visitas domiciliarias de trabajo social postadoptivo realizadas
								</div>
								<div class="2u"><br>
									<?=$a21?>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 22
								</div>
								<div class="4u">
									<br>
									Promedio de acciones para certificar familias de acogidas realizadas respecto al total de solicitantes para acogimiento familiar iniciales
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Acciones para certificar familias de acogidas realizadas
										</div>
										<div class="4u">
											<?=$a22_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Solicitantes para acogimiento familiar iniciales
										</div>
										<div class="4u">
											<?=$a22_2?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 23
								</div>
								<div class="4u">
									<br>
									Promedio de niñas, niños y adolescentes en acogimiento familiar integrados respecto al total de familias de acogida certificadas
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Niñas, niños y adolescentes en acogimiento familiar integrados
										</div>
										<div class="4u">
											<?=$a23_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Familias de acogida certificadas
										</div>
										<div class="4u">
											<?=$a23_2?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
								<div class="2u">
									<br><br>
									ACTIVIDAD 24
								</div>
								<div class="4u">
									<br>
									Promedio de visitas de supervisión de niñas, niños y adolescentes en acogimiento familiar realizadas respecto al total de niñas, niños y adolescentes en acogimiento familiar integrados
								</div>
								<div class="6u">
									<div class="row uniform">
										<div class="8u">
											Visitas de supervisión de niñas, niños y adolescentes en acogimiento familiar realizadas
										</div>
										<div class="4u">
											<?=$a24_1?>
										</div>
									</div><hr>
									<div class="row uniform">
										<div class="8u">
											Niñas, niños y adolescentes en acogimiento familiar integrados
										</div>
										<div class="4u">
											<?=$a24_2?>
										</div>
									</div>
								</div>
							</div><hr>
							
							<div class="row">
								<div class="2u">
									ACTIVIDAD 25
								</div>
								<div class="4u">
									Porcentaje de retornos seguros de niñas, niños y adolescentes hidalguenses repatriados realizado respecto a total de retornos seguros de niñas, niños y adolescentes hidalguenses repatriados programado
								</div>
								<div class="4u">
									No. de retorno seguro de niñas, niños y adolescentes hidalguenses repatriados realizado
								</div>
								<div class="2u">
									<?= $totAct25 ?>
								</div>
								
							</div><hr>
							<div class="row">
								<div class="2u"><br><br>
									ACTIVIDAD 26
								</div>
								<div class="4u"><br>
									Promedio de acompañamiento de atención especial a niñas, niños, adolescentes migrantes extranjeros brindado respecto al total de niñas, niños, adolescentes migrantes extranjeros detectados
								</div>
								<div class="6u">
									<div class="row">
										<div class="8u">
											No. de  acompañamiento de atención especial a niñas, niños, adolescentes migrantes extranjeros brindado
										</div>
										<div class="4u">
											<?= $totAct26_1 ?>
										</div>
									</div><hr>
									<div class="row">
										<div class="8u">
											No. total de niñas, niños, adolescentes migrantes detectados
										</div>
										<div class="4u">
											<?= $totAct26_2 ?>
										</div>
									</div>
								</div>
							</div><hr>

							<div class="row">
								<div class="2u"><br><br>
									ACTIVIDAD 27
								</div>
								<div class="4u">
									Promedio de asistentes a actividades  lúdicas, formativas y educativas en materia de migración infantil respecto al total de actividades  lúdicas, formativas y educativas en materia de migración infantil realizadas
								</div>
								<div class="6u">
									<div class="row">
										<div class="8u">
											Asistentes  a actividades  lúdicas, formativas y educativas en materia de migración infantil
										</div>
										<div class="4u">
											<?php while($row22=$act27_1->fetch_assoc()){ if(empty($row22['total'])) echo '0'; else echo $row22['total']; } ?>
										</div>
									</div><hr>
									<div class="row">
										<div class="8u">
											Actividades  lúdicas, formativas y educativas en materia de migración infantil realizadas
										</div>
										<div class="4u">
											<?= $totAct27_2 ?>
										</div>
									</div>
								</div>
							</div><hr>
							<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 28
									</div>
									<div class="4u">
										<br>
										Porcentaje de apoyo en especie a niñas, niños y adolescentes en situación de vulneración de derechos representados por la Procuraduría Protección de Niñas, Niños, Adolescentes y la Familia otorgado respecto al total de apoyos en especie a niñas, niños y adolescentes en situación de vulneración de derechos representados por la Procuraduría Protección de Niñas, Niños, Adolescentes y la Familia solicitado
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Apoyo en especie a niñas, niños y adolescentes en situación de vulneración de derechos representados por la Procuraduría Protección de Niñas, Niños, Adolescentes y la Familia otorgado
											</div>
											<div class="4u">
												<?=$a28_1?>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Apoyos en especie a niñas, niños y adolescentes en situación de vulneración de derechos representados por la Procuraduría Protección de Niñas, Niños, Adolescentes y la Familia solicitado
											</div>
											<div class="4u">
												<?=$a28_2?>
											</div>
										</div>
									</div>
								</div>
							
						<?php } ?>
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
			</div><!--cierre de menu-->
		</div>  <!--cierre de wrapper-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>