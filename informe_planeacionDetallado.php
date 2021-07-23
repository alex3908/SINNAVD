<?php	
	session_start();
	require 'conexion.php';
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	require 'validar_fecha.php';
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
		$id_fecha = $_POST['meses'];
		$anioq = "SELECT distinct año from cortes where año!=2019 and año!=$anio";
		$anioq = $mysqli->query($anioq);
		$mes = "SELECT mes from cortes where id_corte=$id_fecha";
		$mes = $mysqli->query($mes); 
		$mes = implode($mes->fetch_assoc());
		$mesesq = "SELECT id_corte, mes from cortes where año='$anio' and id_corte!=$id_fecha";
		$mesesq = $mysqli->query($mesesq);
		$fechas = "SELECT fechai, fechac from cortes where id_corte=$id_fecha";
		$fechas= $mysqli->query($fechas);
		while($rf=$fechas->fetch_assoc()){
			$fecha_ini1 = $rf['fechai'];
			$fecha_fin1 = $rf['fechac'];
		}
			$Consulta = true;
			$datos = "SELECT * FROM datos_reportados where id_fecha_corte =$id_fecha";
			$datos =  $mysqli->query($datos);
			$registrado = $datos->num_rows;
			$idDatos = null;
			$p1_1= 0;
			$p1_2= 0;
			$a3= 0;
			$a7_1= 0;
			$a7_2= 0;
			$a8= 0;
			$a9= 0;
			$a10_1= 0;
			$a10_2= 0;
			$a11_1= 0;
			$a11_2= 0;
			$a12_1= 0;
			$a12_2= 0;
			$a13= 0;
			$a14_1= 0;
			$a14_2= 0;
			$a15= 0;
			$a16_1= 0;
			$a16_2= 0;
			$a17_1= 0;
			$a17_2= 0;
			$a18_1= 0;
			$a18_2= 0;
			$a19_1= 0;
			$a19_2= 0;
			$a20= 0;
			$a21= 0;
			$a22_1= 0;
			$a22_2= 0;
			$a23_1= 0;
			$a23_2= 0;
			$a24_1= 0;
			$a24_2= 0;
			$a28_1= 0;
			$a28_2= 0;
			
			$qProp1 = "SELECT nna.id, folio, nombre, apellido_p, apellido_m, date_format(fecha_nacimiento, '%d/%m/%Y') as fecha_nacimiento, sexo, if(migrante=1, 'SI', 'NO') as migrante, if(indigena=1, 'SI', 'NO') as indigena, if(afrodescendiente=1, 'SI', 'NO') as afrodescendiente, date_format(r.fecha_registro, '%d/%m/%Y') as fecha_registro FROM nna_restituidos r inner join nna on nna.id=r.id_nna where r.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1'";
			$Prop1=$mysqli->query($qProp1);
			$nnaRest = $Prop1->num_rows;
			$qComp1="SELECT distinct ca.folio_c, ca.nombre as nombre_caso, nna.nombre, nna.apellido_p, nna.apellido_m, nna.id, (select count(benefmed.id_medida) from benefmed inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida where id_nna=nna.id and cuadro_guia.fecha_ejecucion BETWEEN '$fecha_ini1' and '$fecha_fin1' and cuadro_guia.activo=1 and id_sp_registro=$idDEPTO)  as medidas from  benefmed inner join nna on nna.id=benefmed.id_nna inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida inner join casos ca on ca.id=benefmed.id_caso where cuadro_guia.id_medida in ('03','01') and (cuadro_guia.fecha_ejecucion BETWEEN '$fecha_ini1' and '$fecha_fin1') and cuadro_guia.estado='1' and cuadro_guia.activo=1 and nna.activo=1 and ca.activo=1";
			$comp1=$mysqli->query($qComp1);
			$qtotComp1 = "SELECT count(benefmed.id_medida) from benefmed inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida inner join nna on nna.id=benefmed.id_nna where  cuadro_guia.fecha_ejecucion BETWEEN '$fecha_ini1' and '$fecha_fin1' and cuadro_guia.activo=1 and cuadro_guia.estado=1 and nna.activo=1";
			$totComp1 = $mysqli->query($qtotComp1);
			$totComp1=implode($totComp1->fetch_assoc());
			$qcomp2="SELECT distinct ca.folio_c, ca.nombre as nombre_caso, nna.nombre, nna.apellido_p, nna.apellido_m, nna.id, (select count(benefmed.id_medida) from benefmed inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida where id_nna=nna.id and cuadro_guia.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1' and cuadro_guia.activo=1 and id_sp_registro=$idDEPTO) as medidas from benefmed inner join nna on nna.id=benefmed.id_nna inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida inner join casos ca on ca.id=benefmed.id_caso where cuadro_guia.id_medida in ('03','01') and (cuadro_guia.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1') and cuadro_guia.estado in('1','0') and cuadro_guia.activo=1 and nna.activo=1 and ca.activo=1";
			$comp2=$mysqli->query($qcomp2);
			$qtotComp2 = "SELECT count(benefmed.id_medida) from benefmed inner join cuadro_guia on cuadro_guia.id=benefmed.id_medida inner join nna on nna.id=benefmed.id_nna where  cuadro_guia.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1' and cuadro_guia.activo=1 and nna.activo=1";
			$totComp2=$mysqli->query($qtotComp2);
			$totComp2 = implode($totComp2->fetch_assoc());
			$qact1_1 = "SELECT pc.id as idPc, pc.folio as poscaso, r.folio, cr.recepcion, date_format(hpc.fechaAtencion, '%d/%m/%Y %H:%i:%s') as fecha, r.narracion, hpc.estadoAtencion as atendido	from reportes_vd r inner join posible_caso pc on pc.id=r.id_posible_caso inner join cat_recepcion_reporte cr on r.id_recepcion=cr.id left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion left join historico_asignaciones_juridico hj on hj.id=pc.id_asignado_juridico left join historico_asignaciones_trabajo_social hts on hts.id=pc.id_asignado_ts left join historico_asignaciones_psicologia hps on hps.id=pc.id_asignado_ps where r.activo=1 and (hpc.fechaAtencion BETWEEN '$fecha_ini1' and '$fecha_fin1') and hpc.estadoAtencion in('4')";
			$act1_1 = $mysqli->query($qact1_1);
			$totAct1_1 = $act1_1->num_rows;

			$qact1_2 ="SELECT pc.id as idPc,  pc.folio as poscaso, r.id, r.folio, cr.recepcion, r.narracion, date_format(r.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha, hpc.estadoAtencion as atendido from reportes_vd r inner join cat_recepcion_reporte cr on r.id_recepcion=cr.id inner join posible_caso pc on pc.id=r.id_posible_caso left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion where r.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1' and r.activo=1";
			$act1_2 = $mysqli->query($qact1_2);
			$totAct1_2 = $act1_2->num_rows;

			$qact2_1="SELECT af.id, pc.id as rep, pc.folio, af.observaciones, 
				date_format(af.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, 
				date_format(af.fecha_acercamiento,'%d/%m/%Y') as fecha_acerca, af.inter 
				from acercamiento_familiar af left join posible_caso pc on pc.id=af.id_reporte
				where af.activo=1 and af.fecha_registro between '$fecha_ini1' and '$fecha_fin1'			
				and af.inter is not null";
			$act2_1 = $mysqli->query($qact2_1);
			$totAct2_1= "SELECT sum(ac.inter) as total from posible_caso pc inner join acercamiento_familiar ac on ac.id_reporte=pc.id where ac.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1'";
			$totAct2_1  = $mysqli->query($totAct2_1);
			$totAct2_1 = implode($totAct2_1->fetch_assoc());

			$qact2_2 = "SELECT pc.folio as poscaso, r.folio as folio, cr.recepcion, date_format(r.fecha_registro, '%d/%m/%Y') as fecha_reg, narracion, hpc.estadoAtencion as atendido from reportes_vd r inner join  posible_caso pc on r.id_posible_caso=pc.id inner join cat_recepcion_reporte cr on r.id_recepcion=cr.id left join historico_atenciones_pos_casos hpc on hpc.id=pc.id_estado_atencion where r.activo=1 and r.id_recepcion!=1 and r.id_recepcion!=7 and (r.fecha_registro between '$fecha_ini1' and '$fecha_fin1')";
			$act2_2 = $mysqli->query($qact2_2);
			$totAct2_2= $act2_2->num_rows;

			$qact3_1="SELECT pc.folio , date_format(ap.fecha_registro, '%d/%m/%Y') as fecha, ap.tipo, if(ase_virtual=1, 'SI', 'NO') as acVirtual, nna_ac.nombre, nna_ac.apellido_p, nna_ac.apellido_m FROM acercamiento_psic ap inner join posible_caso pc  on pc.id=ap.id_reporte inner join nna_ac on nna_ac.id=ap.id_nna where ap.activo=1 and ap.fecha_registro between '$fecha_ini1' and '$fecha_fin1' and pc.activo=1";
			$act3_1 = $mysqli->query($qact3_1);
			$totAct3_1 = $act3_1->num_rows;

			$qact3_2 = "SELECT nna.folio, nombre, apellido_p, apellido_m, curp, if(fecha_nacimiento='1900-01-01', 'SIN DATO', date_format(fecha_nacimiento, '%d/%m/%Y')) as fecha_nac, sexo, if(migrante=1, 'SI', 'NO') as migrante, if(indigena=1, 'SI', 'NO') as indigena, if(afrodescendiente=1, 'SI', 'NO') as afrodescendiente, date_format(nna.fecha_registro, '%d/%m/%Y') as fecha_registro from nna where nna.activo=1 and nna.fecha_registro between '$fecha_ini1' and '$fecha_fin1' order by curp asc";
			$act3_2 = $mysqli->query($qact3_2);
			$totAct3_2 = $act3_2->num_rows;
			$qact4 = "SELECT date_format(fecha_ingreso, '%d/%m/%Y') as fecha, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, if(atencionTelefonica=1, 'SI', 'NO') as telefono from usuarios inner join historial on usuarios.id=historial.id_usuario where historial.fecha_ingreso between '$fecha_ini1' and '$fecha_fin1' and ( historial.atencion_brindada like '%ORIENTACION JURIDICA%') and historial.asunto='INICIAL'";
			$act4 = $mysqli->query($qact4);
			$totAct4 = $act4->num_rows;

			$qact5 = "SELECT nuc, if(fecha_inicio='1900-01-01', 'SIN DATO', date_format(fecha_inicio, '%d/%m/%Y')) as fecha_inicio, date_format(c.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_registro, distrits, municipio, delito from  carpeta_inv c inner join distritos di on c.distrito=di.id inner join municipios m on m.id=c.municipio_d inner join delitos de on de.id=c.id_delito where c.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1'";
			$act5_1 = $mysqli->query($qact5);
			$act5_2 = $mysqli->query($qact5);
			$totAct5 = $act5_1->num_rows;

			$qact6_1 ="SELECT casos.id as idC, cuadro_guia.id as idM, casos.folio_c, nna.nombre, nna.apellido_p, nna.apellido_m, catalogo_medidas.medidaC, seguimientos.area, seguimientos.seguimiento, date_format(seguimientos.fecha_registro, '%d/%m/%Y') as fecha from seguimientos left join cuadro_guia on seguimientos.id_med=cuadro_guia.id left join catalogo_medidas on catalogo_medidas.id=cuadro_guia.id_mp left join casos on casos.id=cuadro_guia.id_caso left join benefmed on benefmed.id_medida=cuadro_guia.id	left join nna on nna.id=benefmed.id_nna where (seguimientos.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1') and seguimientos.activo=1";
			$act6_1 = $mysqli->query($qact6_1);
			$totAct6 = $act6_1->num_rows;

			$qact6_2 = "SELECT casos.id, casos.folio_c, derechos_nna.derecho, medidas.medida_p, catalogo_medidas.medidaC, nna.nombre, nna.apellido_p, nna.apellido_m, cuadro_guia.responsable_med, date_format(cuadro_guia.fecha_registro, '%d/%m/%Y') as fecha, cuadro_guia.estado from  casos inner join cuadro_guia  on cuadro_guia.id_caso=casos.id inner join benefmed on benefmed.id_medida = cuadro_guia.id inner join nna on nna.id=benefmed.id_nna inner join derechos_nna on derechos_nna.id=cuadro_guia.id_derecho inner join medidas on medidas.id=cuadro_guia.id_medida inner join catalogo_medidas on catalogo_medidas.id=cuadro_guia.id_mp where cuadro_guia.activo=1 and (cuadro_guia.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1') and casos.activo=1 and nna.activo=1";
			$act6_2 = $mysqli->query($qact6_2);
			$totAct6_2 = $act6_2->num_rows;

			$qact8 = "SELECT c.nombre, CONCAT(s.nombre,' ', s.ap_paterno,' ', s.ap_materno) AS atendio, m.motivo, date_format(s.fecha_sup, '%d/%m/%Y') as fecha FROM supervisiones s inner join centros c on c.id=s.id_centro inner join motivoscas m on m.id=s.id_motivo where (fecha_sup BETWEEN '$fecha_ini1' and '$fecha_fin1')";
			$act8 = $mysqli->query($qact8);
			$totAct8 = $act8->num_rows;
			$qact10_1 = "SELECT nna.nombre, nna.apellido_p, nna.apellido_m, centros.nombre as centro, nna_centros.motivo, date_format(fecha_ing, '%d/%m/%Y') as fecha_ing FROM nna_centros INNER JOIN centros on nna_centros.id_centro=centros.id INNER JOIN nna on nna.id=nna_centros.id_nna WHERE centros.tipo LIKE '%PRIVADO%' and (nna_centros.fecha_ing BETWEEN '$fecha_ini1' and '$fecha_fin1')";
			$act10_1 = $mysqli->query ($qact10_1);
			$totAct10_1 = $act10_1->num_rows;
			$qact25 = "SELECT cuadro_guia.id, nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, if(fecha_nacimiento, 'SIN DATO', date_format(fecha_nacimiento, '%d/%m/%Y')) as fecha_nacimiento, sexo, date_format(cuadro_guia.fecha_registro,'%d/%m/%Y') as fecha_registro from cuadro_guia left join benefmed on benefmed.id_medida=cuadro_guia.id left join nna on nna.id=benefmed.id_nna where cuadro_guia.id_mp='28' and (cuadro_guia.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1') and cuadro_guia.activo=1";
			$act25 = $mysqli->query($qact25);
			$totAct25= $act25->num_rows;

			$qact26_1 = "SELECT cuadro_guia.id, nna.folio, nna.lugar_reg, nna.nombre, nna.apellido_p, nna.apellido_m, if(fecha_nacimiento, 'SIN DATO', date_format(fecha_nacimiento, '%d/%m/%Y')) as fecha_nacimiento, sexo, date_format(cuadro_guia.fecha_registro, '%d/%m/%Y') as fecha_registro from cuadro_guia left join benefmed on benefmed.id_medida=cuadro_guia.id left join nna on nna.id=benefmed.id_nna where cuadro_guia.id_mp='30' and (cuadro_guia.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1') and cuadro_guia.activo=1";
			$act26_1= $mysqli->query($qact26_1);
			$totAct26_1 = $act26_1->num_rows;

			$qact26_2 = "SELECT nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, if(fecha_nacimiento, 'SIN DATO', date_format(fecha_nacimiento, '%d/%m/%Y')) as fecha_nacimiento, sexo, lugar_reg, date_format(fecha_registro, '%d/%m/%Y') as fecha_registro from nna where nna.fecha_registro BETWEEN '$fecha_ini1' and '$fecha_fin1' and migrante=1 and nna.activo=1";
			$act26_2 = $mysqli->query($qact26_2);
			$totAct26_2 = $act26_2->num_rows;

			$qact27_1 ="SELECT sum(ccpi.na) as ninios, sum(ni) as ninias, sum(adm) as adolMuj, sum(adh) as adolHom, sum(am) as mujeres, sum(ah) as hombres, sum(na+ni+adm+adh+am+ah) as total FROM ccpi where fecha_reg BETWEEN '$fecha_ini1' and '$fecha_fin1'";
			$act27_1 = $mysqli->query($qact27_1);

			$qact27_2 = "SELECT ccpi.id, actividad, sum(ccpi.na+ccpi.ni+ccpi.adm+ccpi.adh+ccpi.am+ccpi.ah) as total, date_format(fecha, '%d/%m/%Y') as fecha, date_format(fecha_reg, '%d/%m/%Y') as fecha_reg, m.municipio from ccpi inner join municipios m on ccpi.municipio=m.id where fecha_reg BETWEEN '$fecha_ini1' and '$fecha_fin1' group by ccpi.id";
			$act27_2 = $mysqli->query($qact27_2);
			$totAct27_2 = $act27_2->num_rows;


			if($registrado>0){
				while ($row=$datos->fetch_assoc()) {
					$idDatos = $row['id'];
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
		<script languague="javascript">
			var band = true;
	        function mostrar(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante');
	        		div.style.display = '';
	        		band= false
	        	}
	        	else {
	        		div = document.getElementById('flotante');
	        		div.style.display = 'none';
	        		band = true
	        	}
	   		}
			var band2 = true;
	        function mostrar2(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante2');
	        		div.style.display = '';
	        		band2= false
	        	}
	        	else {
	        		div = document.getElementById('flotante2');
	        		div.style.display = 'none';
	        		band2 = true
	        	}
	        }
			var band3 = true;
	        function mostrar3(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante3');
	        		div.style.display = '';
	        		band3= false
	        	}
	        	else {
	        		div = document.getElementById('flotante3');
	        		div.style.display = 'none';
	        		band3 = true
	        	}
	        }
			var band4 = true;
	        function mostrar4(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante4');
	        		div.style.display = '';
	        		band4= false
	        	}
	        	else {
	        		div = document.getElementById('flotante4');
	        		div.style.display = 'none';
	        		band4 = true
	        	}
	        }
			var band5 = true;
	        function mostrar5(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante5');
	        		div.style.display = '';
	        		band5= false
	        	}
	        	else {
	        		div = document.getElementById('flotante5');
	        		div.style.display = 'none';
	        		band5 = true
	        	}
	        }
	        var band6 = true;
	        function mostrar6(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante6');
	        		div.style.display = '';
	        		band6= false
	        	}
	        	else {
	        		div = document.getElementById('flotante6');
	        		div.style.display = 'none';
	        		band6 = true
	        	}
	        }
	        var band7 = true;
	        function mostrar7(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante7');
	        		div.style.display = '';
	        		band7= false
	        	}
	        	else {
	        		div = document.getElementById('flotante7');
	        		div.style.display = 'none';
	        		band7 = true
	        	}
	        }
	        var band8 = true;
	        function mostrar8(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante8');
	        		div.style.display = '';
	        		band8= false
	        	}
	        	else {
	        		div = document.getElementById('flotante8');
	        		div.style.display = 'none';
	        		band8 = true
	        	}
	        }
	        var band9 = true;
	        function mostrar9(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante9');
	        		div.style.display = '';
	        		band9= false
	        	}
	        	else {
	        		div = document.getElementById('flotante9');
	        		div.style.display = 'none';
	        		band9 = true
	        	}
	        }
	        var band10 = true;
	        function mostrar10(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante10');
	        		div.style.display = '';
	        		band10= false
	        	}
	        	else {
	        		div = document.getElementById('flotante10');
	        		div.style.display = 'none';
	        		band10 = true
	        	}
	        }
	        var band11 = true;
	        function mostrar11(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante11');
	        		div.style.display = '';
	        		band11= false
	        	}
	        	else {
	        		div = document.getElementById('flotante11');
	        		div.style.display = 'none';
	        		band11 = true
	        	}
	        }
	        var band12 = true;
	        function mostrar12(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante12');
	        		div.style.display = '';
	        		band12= false
	        	}
	        	else {
	        		div = document.getElementById('flotante12');
	        		div.style.display = 'none';
	        		band12 = true
	        	}
	        }
	        var band13 = true;
	        function mostrar13(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante13');
	        		div.style.display = '';
	        		band13= false
	        	}
	        	else {
	        		div = document.getElementById('flotante13');
	        		div.style.display = 'none';
	        		band13 = true
	        	}
	        }
	        var band14 = true;
	        function mostrar14(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante14');
	        		div.style.display = '';
	        		band14= false
	        	}
	        	else {
	        		div = document.getElementById('flotante14');
	        		div.style.display = 'none';
	        		band14 = true
	        	}
	        }
	        var band15 = true;
	        function mostrar15(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante15');
	        		div.style.display = '';
	        		band15= false
	        	}
	        	else {
	        		div = document.getElementById('flotante15');
	        		div.style.display = 'none';
	        		band15 = true
	        	}
	        }
	        var band16 = true;
	        function mostrar16(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante16');
	        		div.style.display = '';
	        		band16= false
	        	}
	        	else {
	        		div = document.getElementById('flotante16');
	        		div.style.display = 'none';
	        		band16 = true
	        	}
	        }
	        var band19 = true;
	        function mostrar19(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante19');
	        		div.style.display = '';
	        		band19= false
	        	}
	        	else {
	        		div = document.getElementById('flotante19');
	        		div.style.display = 'none';
	        		band19 = true
	        	}
	        }
	        var band20 = true;
	        function mostrar20(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante20');
	        		div.style.display = '';
	        		band20= false
	        	}
	        	else {
	        		div = document.getElementById('flotante20');
	        		div.style.display = 'none';
	        		band20 = true
	        	}
	        }
	        var band21 = true;
	        function mostrar21(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante21');
	        		div.style.display = '';
	        		band21= false
	        	}
	        	else {
	        		div = document.getElementById('flotante21');
	        		div.style.display = 'none';
	        		band21 = true
	        	}
	        }
	        var band22 = true;
	        function mostrar22(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante22');
	        		div.style.display = '';
	        		band22= false
	        	}
	        	else {
	        		div = document.getElementById('flotante22');
	        		div.style.display = 'none';
	        		band22 = true
	        	}
	        }
	        var band23 = true;
	        function mostrar23(bandera) {
	        	if(bandera === true){
	        		div = document.getElementById('flotante23');
	        		div.style.display = '';
	        		band23= false
	        	}
	        	else {
	        		div = document.getElementById('flotante23');
	        		div.style.display = 'none';
	        		band23 = true
	        	}
	        }


	    </script>
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
							<div class="3u">
								<div class="select-wrapper">
									<select id="anio" name="anio" required>
										<?php if(empty($anio)) { ?>
											<option value="">Seleccione</option>
											<option value="2021">2021</option>
											<option value="2020">2020</option>
										<?php } else { ?>
											<option value="<?=$anio?>"><?=$anio?></option>
											<?php while ($rwa=$anioq->fetch_assoc()) { ?>
												<option value="<?=$rwa['año']?>"><?=$rwa['año']?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>
							<div class="3u">
								<div class="select-wrapper">
									<select id="meses" name="meses" required>
										<?php if(empty($mes)) { ?>
											<option value="">Seleccione</option>
										<?php } else { ?>
											<option value="<?=$id_fecha?>"><?=$mes?></option>
											<?php while ($rwm=$mesesq->fetch_assoc()) { ?>
												<option value="<?=$rwm['id_corte']?>"><?=$rwm['mes']?></option>
											<?php } 
										} ?>
									</select>
								</div>
							</div>
							<div class="3u">
								<input type="submit" value="Consultar" name="btnConsulta" class="button special fit">
							</div>
							<div class="3u">
								<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='reporte1ra.php.php'" >
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
									<b>Nivel de la MIR</b>
								</div>
								<div class="6u">
									<b>Unidad de medida</b>
								</div>
								<div class="2u">
									<b>Total</b>
								</div>
								<div class="2u">
									<b>Datos</b>
								</div>
							</div>
							<div class="row">
								<div class="2u">
									PROPOSITO
								</div>
								<div class="6u">
									No. de  niñas, niños y adolescentes  que se restituyen sus derechos 
								</div>
								<div class="2u">
									<?= $nnaRest ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar(band);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>FOLIO</th>
													<th>NOMBRE</th>

													<th>FECHA DE NACIMIENTO</th>
													<th>SEXO</th>
													<th>MIGRANTE</th>
													
													<th>INDIGENA</th>
													<th>AFRODESCENDIENTE</th>
													<th>FECHA DE RESTITUCION</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row=$Prop1->fetch_assoc()){ ?>
													<tr>
														<td><?=$row['folio']?></td>
														<td><?=$row['nombre']." ".$row['apellido_p']." ".$row['apellido_m']?></td>
														<td><?=$row['fecha_nacimiento']?></td>
														<td><?=$row['sexo']?></td>
														<td><?=$row['migrante']?></td>
														<td><?=$row['indigena']?></td>
														<td><?=$row['afrodescendiente']?></td>
														<td><?=$row['fecha_registro']?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									COMPONENTE 1
								</div>
								<div class="6u">
									Acciones  en el plan de restituciòn de derechos a Niñas, Niños y Adolescentes realizadas
								</div>
								<div class="2u">
									<?= $totComp1 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar2(band2);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante2" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>FOLIO CASO</th>
													<th>NOMBRE DEL CASO</th>
													<th>NOMBRE DEL NNA</th>
													<th>NÚMERO DE MEDIDAS</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row2=$comp1->fetch_assoc()){ ?>
													<tr>
														<td><?=$row2['folio_c']?></td>
														<td><?=$row2['nombre_caso']?></td>
														<td><?=$row2['nombre']." ".$row2['apellido_p']." ".$row2['apellido_m']?></td>
														<td><?=$row2['medidas']?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									COMPONENTE 1
								</div>
								<div class="6u">
									Acciones  en el plan de restituciòn de derechos a Niñas, Niños y Adolescentes contenidas
								</div>
								<div class="2u">
									<?= $totComp2 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar3(band3);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante3" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>FOLIO CASO</th>
													<th>NOMBRE DEL CASO</th>
													<th>NOMBRE DEL NNA</th>
													<th>NÚMERO DE MEDIDAS</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row3=$comp2->fetch_assoc()){ ?>
													<tr>
														<td><?=$row3['folio_c']?></td>
														<td><?=$row3['nombre_caso']?></td>
														<td><?=$row3['nombre']." ".$row3['apellido_p']." ".$row3['apellido_m']?></td>
														<td><?=$row3['medidas']?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 1
								</div>
								<div class="6u">
									Reportes de casos de vulneración de derechos de niñas, niños y adolescentes  que resultaron positivos en vulneración de derechos
								</div>
								<div class="2u">
									<?= $totAct1_1 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar4(band4);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante4" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>FOLIO POSIBLE-CASO</th>
													<th>FOLIO REPORTE</th>
													<th>RECEPCION</th>
													<th>FECHA DE ATENCIÓN</th>
													<th>NARRACIÓN</th>
													<th>ESTADO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row4=$act1_1->fetch_assoc()){ ?>
													<tr>
														<td><?=$row4['poscaso']?></td>
														<td><?=$row4['folio']?></td>
														<td><?=$row4['recepcion']?></td>
														<td><?=$row4['fecha']?></td>
														<th><?=$row4['narracion']?></th>
														<th><img src="images/Apositivo.png" width="65px" height="65px"	></th>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 1
								</div>
								<div class="6u">
									Reportes de  casos de vulneración de derechos de niñas, niños y adolescentes recibidos
								</div>
								<div class="2u">
									<?php if(empty($totAct1_2)) echo "0"; else echo $totAct1_2 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar5(band5);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante5" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>FOLIO POSIBLE-CASO</th>
													<th>FOLIO REPORTE</th>
													<th>RECEPCION</th>
													<th>FECHA DE ATENCIÓN</th>
													<th>NARRACIÓN</th>
													<th>ESTADO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row5=$act1_2->fetch_assoc()){ ?>
													<tr>
														<td><?=$row5['poscaso']?></td>
														<td><?=$row5['folio']?></td>
														<td><?=$row5['recepcion']?></td>
														<td><?=$row5['fecha']?></td>
														<th><?=$row5['narracion']?></th>
														<td align="center" valign="middle">
															<?php $atend=$row5['atendido'];
																if ($atend=='1' or empty($atend)) { ?>
															<img src="images/advertencia.png" width="50px" height="50px">
															<?php }else if ($atend=='2') { ?>
															<img src="images/proceso.png" width="65px" height="65px">
															<?php }else if ($atend=='3') { ?>
															<img src="images/Anegativo.png" width="65px" height="65px">
															<?php }else if ($atend=='4') { ?>
															<img src="images/Apositivo.png" width="65px" height="65px"	>
															<?php } ?>
														</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 2
								</div>
								<div class="6u">
									Intervenciones de trabajo social a niñas, niños y adolescentes en situacion de vulneracion de derechos realizadas
								</div>
								<div class="2u">
									<?php if(empty($totAct2_1)) echo "0"; else echo $totAct2_1 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar6(band6);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante6" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>FOLIO POSIBLE-CASO</th>
													<th>OBSERVACIONES</th>
													<th>FECHA DE REGISTRO</th>
													<th>FECHA DEL ACERCAMIENTO</th>
													<th>NÚMERO DE INTERVENCIONES</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row6=$act2_1->fetch_assoc()){ ?>
													<tr>
														<td><?=$row6['folio']?></td>
														<td><?=$row6['observaciones']?></td>
														<td><?=$row6['fecha_reg']?></td>
														<td><?=$row6['fecha_acerca']?></td>
														<th><?=$row6['inter']?></th>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 2
								</div>
								<div class="6u">
									Reportes de  casos de  posible  vulneración de derechos de niñas, niños y adolescentes recibidos
								</div>
								<div class="2u">
									<?= $totAct2_2 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar7(band7);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante7" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>FOLIO POSIBLE-CASO</th>
													<th>FOLIO REPORTE</th>
													<th>RECEPCION</th>
													<th>FECHA DE REGISTRO</th>
													<th>NARRACIÓN</th>
													<th>ESTADO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row7=$act2_2 ->fetch_assoc()){ ?>
													<tr>
														<td><?=$row7['poscaso']?></td>
														<td><?=$row7['folio']?></td>
														<td><?=$row7['recepcion']?></td>
														<td><?=$row7['fecha_reg']?></td>
														<th><?=$row7['narracion']?></th>
														<td align="center" valign="middle">
															<?php $atend=$row7['atendido'];
																if ($atend=='1' or empty($atend)) { ?>
															<img src="images/advertencia.png" width="50px" height="50px">
															<?php }else if ($atend=='2') { ?>
															<img src="images/proceso.png" width="65px" height="65px">
															<?php }else if ($atend=='3') { ?>
															<img src="images/Anegativo.png" width="65px" height="65px">
															<?php }else if ($atend=='4') { ?>
															<img src="images/Apositivo.png" width="65px" height="65px"	>
															<?php } ?>
														</th>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 3
								</div>
								<div class="6u">
									Intervenciones psicológicas a niñas, niños y adolescentes en situación de vulneración de derechos brindadas
								</div>
								<div class="2u">
									<?= $totAct3_1 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar8(band8);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante8" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>FOLIO</th>
													<th>FECHA REGISTRO DEL ACERCAMIENTO</th>
													<th>TIPO</th>
													<th>VIRTUAL</th>
													<th>NOMBRE DEL NNA</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row8=$act3_1->fetch_assoc()){ ?>
													<tr>
														<td><?=$row8['folio']?></td>
														<td><?=$row8['fecha']?></td>
														<td><?=$row8['tipo']?></td>
														<td><?=$row8['acVirtual']?></td>
														<td><?=$row8['nombre']." ".$row8['apellido_p']." ".$row8['apellido_m']?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 3
								</div>
								<div class="6u">
									Niñas, niños y adolescentes con derechos vulnerados atendidos por primera vez en  la Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia
								</div>
								<div class="2u">
									<?= $totAct3_2 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar9(band9);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante9" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>FOLIO</th>
													<th>NOMBRE</th>
													<th>CURP</th>
													<th>FECHA DE NACIMIENTO</th>
													<th>SEXO</th>
													<th>MIGRANTE</th>
													
													<th>INDIGENA</th>
													<th>AFRODESCENDIENTE</th>
													<th>FECHA DE REGISTRO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row9=$act3_2->fetch_assoc()){ ?>
													<tr>
														<td><?=$row9['folio']?></td>
														<td><?=$row9['nombre']." ".$row9['apellido_p']." ".$row9['apellido_m']?></td>
														<td><?=$row9['curp']?></td>
														<td><?=$row9['fecha_nac']?></td>
														<td><?=$row9['sexo']?></td>
														<td><?=$row9['migrante']?></td>
														<td><?=$row9['indigena']?></td>
														<td><?=$row9['afrodescendiente']?></td>
														<td><?=$row9['fecha_registro']?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 4
								</div>
								<div class="6u">
									Orientaciones jurídicas para la protección y/o restitución de derechos  realizadas
								</div>
								<div class="2u">
									<?= $totAct4 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar10(band10);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante10" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													<th>FECHA DE LA ASESORIA</th>
													<th>NOMBRE DEL USUARIO</th>
													<th>VÍA TELEFÓNICA</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row10=$act4->fetch_assoc()){ ?>
													<tr>
														<td><?=$row10['fecha']?></td>
														<td><?=$row10['nombre']." ".$row10['apellido_p']." ".$row10['apellido_m']?></td>
														<td><?=$row10['telefono']?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 5
								</div>
								<div class="6u">
									Representación coadyuvante  a niñas, niños y adolescentes brindada
								</div>
								<div class="2u">
									<?= $totAct5 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar11(band11);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante11" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>NUC</th>
													<th>FECHA DE INICIO</th>
													<th>DISTRITO</th>
													<th>MUNICIPIO</th>
													<th>DELITO</th>
													<th>FECHA DE REGISTRO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row11=$act5_1->fetch_assoc()){ ?>
													<tr>
														<td><?=$row11['nuc']?></td>
														<td><?=$row11['fecha_inicio']?></td>
														<td><?=$row11['distrits']?></td>
														<td><?=$row11['municipio']?></td>
														<td><?=$row11['delito']?></td>
														<td><?=$row11['fecha_registro']?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 5
								</div>
								<div class="6u">
									Representaciones coadyuvante  solicitadas
								</div>
								<div class="2u">
									<?= $totAct5 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar12(band12);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante12" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>NUC</th>
													<th>FECHA DE INICIO</th>
													<th>DISTRITO</th>
													<th>MUNICIPIO</th>
													<th>DELITO</th>
													<th>FECHA DE REGISTRO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row12=$act5_2->fetch_assoc()){ ?>
													<tr>
														<td><?=$row12['nuc']?></td>
														<td><?=$row12['fecha_inicio']?></td>
														<td><?=$row12['distrits']?></td>
														<td><?=$row12['municipio']?></td>
														<td><?=$row12['delito']?></td>
														<td><?=$row12['fecha_registro']?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 6
								</div>
								<div class="6u">
									Seguimientos realizados
								</div>
								<div class="2u">
									<?= $totAct6 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar13(band13);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante13" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>FOLIO DEL CASO</th>
													<th>NNA</th>
													<th>MEDIDA DE PROTECCION</th>
													<th>AREA</th>
													<th>SEGUIMIENTO</th>
													<th>FECHA DE REGISTRO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row13=$act6_1->fetch_assoc()){ ?>
													<tr>
														<td><?=$row13['folio_c']?></td>
														<td><?=$row13['nombre']." ".$row13['apellido_p']." ".$row13['apellido_m']?></td>
														<td><?=$row13['medidaC']?></td>
														<td><?=$row13['area']?></td>
														<td><?=$row13['seguimiento']?></td>
														<td><?=$row13['fecha']?></td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 6
								</div>
								<div class="6u">
									Medidas de protección en el plan de restitución de derechos a niñas, niños y adolescentes decretadas
								</div>
								<div class="2u">
									<?= $totAct6_2 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar14(band14);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante14" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													<th>FOLIO DEL CASO</th>
													<th>DERECHO</th>
													<th>TIPO</th>
													<th>MEDIDA</th>
													<th>NOMBRE DEL NNA</th>
													<th>INSTITUCION</th>
													<th>FECHA DE REGISTRO</th>
													<th>ESTADO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row14=$act6_2->fetch_assoc()){ ?>
													<tr>
														<td><?=$row14['folio_c']?></td>
														<td><?=$row14['derecho']?></td>
														<td><?=$row14['medida_p']?></td>
														<td><?=$row14['medidaC']?></td>		
														<td><?=$row14['nombre']." ".$row14['apellido_p']." ".$row14['apellido_m']?></td>
														<td><?=$row14['responsable_med']?>
														<td><?=$row14['fecha']?></td>
														<td> <?php $es=$row14['estado'];
															if ($es==0) { ?>
															<input type="image" src="images/no_ejecutada.png" height="40" width="40">
																
															<?php }else if($es==1 ){ ?>
															<input type="image" src="images/ejecutada.png" height="40" width="40">
																
															<?php } ?>
														</td>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 8
								</div>
								<div class="6u">
									Supervisión de Centros  de Asistencia Social realizada
								</div>
								<div class="2u">
									<?= $totAct8 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar15(band15);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante15" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													<th>NOMBRE DEL CENTRO</th>
													<th>PERSONAL QUE ATENDIO LA VISITA</th>
													<th>MOTIVO</th>
													<th>FECHA DE SUPERVISION</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row15=$act8->fetch_assoc()){ ?>
													<tr>
														<td><?=$row15['nombre']?></td>
														<td><?=$row15['atendio']?></td>
														<td><?=$row15['motivo']?></td>
														<td><?=$row15['fecha']?></td>		
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 10
								</div>
								<div class="6u">
									Ingresos de personas a los centros de asistencia social Privados realizados
								</div>
								<div class="2u">
									<?= $totAct10_1 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar16(band16);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante16" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													<th>NOMBRE DEL NNA</th>
													<th>NOMBRE DEL CENTRO</th>
													<th>MOTIVO</th>
													<th>FECHA DE INGRESO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row16=$act10_1->fetch_assoc()){ ?>
													<tr>
														<td><?=$row16['nombre']." ".$row16['apellido_p']." ".$row16['apellido_m']?></td>
														<td><?=$row16['centro']?></td>
														<td><?=$row16['motivo']?></td>
														<td><?=$row16['fecha_ing']?></td>		
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							
							<div class="row">
								<div class="2u">
									ACTIVIDAD 25
								</div>
								<div class="6u">
									No. de retorno seguro de niñas, niños y adolescentes hidalguenses repatriados realizado
								</div>
								<div class="2u">
									<?= $totAct25 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar19(band19);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante19" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													<th>FOLIO</th>
													<th>NOMBRE DEL NNA</th>
													<th>SEXO</th>
													<th>FECHA DE NACIMIENTO</th>
													<th>FECHA DE REGISTRO DE LA MEDIDA</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row19=$act25->fetch_assoc()){ ?>
													<tr>
														<td><?=$row19['folio']?></td>
														<td><?=$row19['nombre']." ".$row19['apellido_p']." ".$row19['apellido_m']?></td>
														<td><?=$row19['sexo']?></td>
														<td><?=$row19['fecha_nacimiento']?></td>
														<td><?=$row19['fecha_registro']?></td>		
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 26
								</div>
								<div class="6u">
									No. de  acompañamiento de atención especial a niñas, niños, adolescentes migrantes extranjeros brindado
								</div>
								<div class="2u">
									<?= $totAct26_1 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar20(band20);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante20" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr><th>FOLIO</th>
													<th>NOMBRE DEL NNA</th>
													<th>SEXO</th>
													<th>FECHA DE NACIMIENTO</th>
													<th>LUGAR DE ORIGEN</th>
													<th>FECHA DE REGISTRO DE LA MEDIDA</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row20=$act26_1->fetch_assoc()){ ?>
													<tr>
														<td><?=$row20['folio']?></td>
														<td><?=$row20['nombre']." ".$row20['apellido_p']." ".$row20['apellido_m']?></td>
														<td><?=$row20['sexo']?></td>
														<td><?=$row20['fecha_nacimiento']?></td>
														<td><?=$row20['lugar_reg']?></td>
														<td><?=$row20['fecha_registro']?></td>		
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 26
								</div>
								<div class="6u">
									No. total de niñas, niños, adolescentes migrantes detectados
								</div>
								<div class="2u">
									<?= $totAct26_2 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar21(band21);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante21" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead1
												<tr>
													<th>FOLIO</th>
													<th>NOMBRE DEL NNA</th>
													<th>SEXO</th>
													<th>FECHA DE NACIMIENTO</th>
													<th>LUGAR DE ORIGEN</th>
													<th>FECHA DE REGISTRO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row21=$act26_2->fetch_assoc()){ ?>
													<tr>
														<td><?=$row21['folio']?>
														<td><?=$row21['nombre']." ".$row21['apellido_p']." ".$row21['apellido_m']?></td>
														<td><?=$row21['sexo']?></td>
														<td><?=$row21['fecha_nacimiento']?></td>
														<td><?=$row21['lugar_reg']?></td>	
														<td><?=$row21['fecha_registro']?></td>			
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<?php while($row22=$act27_1->fetch_assoc()){ ?>
									<div class="2u">
										ACTIVIDAD 27
									</div>
									<div class="6u">
										Asistentes  a actividades  lúdicas, formativas y educativas en materia de migración infantil
									</div>
									<div class="2u">
										<?php if(empty($row22['total'])) echo '0'; else echo $row22['total'];  ?>
									</div>
									<div class="2u">
										<p><a href="javascript:mostrar22(band22);">Mostrar/Ocultar</a></p>
									</div>

									<div id="flotante22" style="display:none;">
										<div class="table-wrapper">
											<table>
												<thead1
													<tr>
														<th>NIÑAS</th>
														<th>NIÑOS</th>
														<th>ADOLESCENTES MUJERES</th>
														<th>ADOLESCENTES HOMBRES</th>
														<th>ADULTOS MUJERES</th>
														<th>ADULTOS HOMBRES</th>

													</tr>
												</thead>
												<tbody>
												
													<tr>
														<td><?=$row22['ninias']?>
														<td><?=$row22['ninios']?></td>
														<td><?=$row22['adolMuj']?></td>
														<td><?=$row22['adolHom']?></td>
														<td><?=$row22['mujeres']?></td>	
														<td><?=$row22['hombres']?></td>			
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							<div class="row">
								<div class="2u">
									ACTIVIDAD 27
								</div>
								<div class="6u">
									Actividades  lúdicas, formativas y educativas en materia de migración infantil realizadas
								</div>
								<div class="2u">
									<?= $totAct27_2 ?>
								</div>
								<div class="2u">
									<p><a href="javascript:mostrar23(band23);">Mostrar/Ocultar</a></p>
								</div>

								<div id="flotante23" style="display:none;">
									<div class="table-wrapper">
										<table>
											<thead>
												<tr>
													
													<th>NOMBRE DE LA ACTIVIDAD</th>
													<th>NO. DE ASISTENTES</th>
													<th>FECHA DE ACTIVIDAD</th>
													<th>MUNICIPIO</th>
													<th>FECHA DE REGISTRO</th>
												</tr>
											</thead>
											<tbody>
												<?php while($row2=$comp1->fetch_assoc()){ ?>
													<tr>
														<td><?=$row23['actividad']?></td>
														<td><?=$row23['total']?></td>
														<td><?=$row23['fecha']?></td>
														<td><?=$row23['municipio']?></td>
														<td><?=$row23['fecha_reg']?>
													</tr>
												<?php } ?>
											</tbody>
										</table>
									</div>									
								</div>
							</div>
							
						<?php } ?>
					</form>
					<script type="text/javascript">
						$(document).ready(function(){
							$("#anio").change(function(){
								$.get("get_meses.php","anio="+$("#anio").val(), function(data){
									$("#meses").html(data);
									console.log(data);
								});
							});
						});
					</script>
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