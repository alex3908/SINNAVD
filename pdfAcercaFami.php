	<?php
	session_start();
	require 'fpdf/fpdf.php';
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	$idReporte= $_GET['idReporte'];
	$idAcerca = $_GET['idAc'];
	$fechaHoy= date("d/m/Y H:i:s", time());
	$fechas="SELECT date_format(ac.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, date_format(fecha_acercamiento, '%d/%m%/%Y') as fecha_acerca,
	d.responsable, pc.folio
	from acercamiento_familiar ac inner join posible_caso pc on ac.id_reporte=pc.id
	inner join historico_asignaciones_trabajo_social ts on pc.id=ts.id_posible_caso
     inner join departamentos d on d.id=ts.id_departamentos_asignado where id_reporte='$idReporte'";
    $efechas=$mysqli->query($fechas);
    while ($row=$efechas->fetch_assoc()) {  
		$responsableTS=$row['responsable'];
		$folioPC = $row['folio'];
		$fechaAc= $row['fecha_acerca'];
		$fechaReg = $row['fecha_reg'];
	}
	
$nnaen="SELECT nna_ac.id, nna_ac.nombre, nna_ac.apellido_p, nna_ac.apellido_m, nna_ac.sexo, nna_ac.fecha_nac, nna_ac.lugar_nac, nna_ac.nacionalidad, nna_ac.ocupacion, nna_ac.religion, nna_ac.fecha_reg, nna_ac.respo_reg FROM nna_ac inner join part1ac on nna_ac.id_acerca=part1ac.id WHERE part1ac.id_reporte='$idReporte'";
$rNnas=$mysqli->query($nnaen);

$responsables="SELECT id, nombre, edad, ocupacion , datos_economicos, telefono, direccion, estado_civil,
    escolaridad, religion from respon_nna where id_acerca_fam=$idAcerca and activo=1";
    $responsables = $mysqli->query($responsables);
	
	$redFam="SELECT redes_familiares.id, redes_familiares.parentesco, redes_familiares.nombre, redes_familiares.edad, redes_familiares.direccion, redes_familiares.telefono, redes_familiares.observa from redes_familiares inner join acercamiento_familiar on redes_familiares.id_acerca=acercamiento_familiar.id where acercamiento_familiar.id_reporte='$idReporte' and redes_familiares.activo=1";
    $eredFam=$mysqli->query($redFam);

    $datosAcer = "SELECT info_fam, num_redes, observaciones_afecta, dialogo_experimental, observaciones, inter, registro_fam, acta_nac,hijo_sin_res, hijo_sin, hijo_nna,hijo_nna_res, opinion_nna, cuidado_nna,cuidado_nna_res, vivienda_nna,
vivienda_nna_res, violencia_nna, maltrato_nna, alimentacion_nna,violencia_nna_res, maltrato_nna_res, alimentacion_nna_res, 
doctor_nna, doctor_nna_res, cartilla_vacunacion, cartilla_completa, enfermo_nna_res, enfermo_nna, asistencia_medica, servicio_medico_res,
servicio_medico, alguna_discapacidad, discapacidad_res, aditamentos,aditamentos_res, nna_escuela,nna_escuela_res, nna_asiste,nna_asiste_res, 
desempeño,desempeño_res, act_recreativas_res, actividades_recreativas, grado_negacion, reconoce_paso, tiene_responsabilidad, necesita_ayuda, 
esta_dispuesto, descripcion from acercamiento_familiar where id_reporte='$idReporte' and id='$idAcerca'";
$datosAcer = $mysqli->query($datosAcer);

	foreach ($datosAcer as $acer) {
		$info_fam = $acer['info_fam'];
		$num_redes =  $acer['num_redes'];
		$observaciones_afecta =  $acer['observaciones_afecta'];
		$dialogo_experimental =  $acer['dialogo_experimental'];
		$observaciones =  $acer['observaciones'];
		$inter =  $acer['inter'];
		$registro_fam =  $acer['registro_fam'];
		$acta_nac = $acer['acta_nac'];
		$hijo_sin_res =  $acer['hijo_sin_res'];
		$hijo_sin =  $acer['hijo_sin'];
		$hijo_nna = $acer['hijo_nna'];
		$hijo_nna_res =  $acer['hijo_nna_res'];
		$opinion_nna =  $acer['opinion_nna'];
		$cuidado_nna = $acer['cuidado_nna'];
		$cuidado_nna_res =  $acer['cuidado_nna_res'];
		$vivienda_nna = $acer['vivienda_nna'];
		$vivienda_nna_res =$acer['vivienda_nna_res'];
		$violencia_nna =$acer['violencia_nna'];
		$maltrato_nna = $acer['maltrato_nna'];
		$alimentacion_nna = $acer['alimentacion_nna'];
		$violencia_nna_res =$acer['violencia_nna_res'];
		$maltrato_nna_res =$acer['maltrato_nna_res'];
		$alimentacion_nna_res =$acer['alimentacion_nna_res'];
		$doctor_nna = $acer['doctor_nna'];
		$doctor_nna_res =$acer['doctor_nna_res'];
		$cartilla_vacunacion = $acer['cartilla_vacunacion'];
		$cartilla_completa = $acer['cartilla_completa'];
		$enfermo_nna_res = $acer['enfermo_nna_res'];
		$enfermo_nna = $acer['enfermo_nna'];
		$asistencia_medica = $acer['asistencia_medica'];
		$servicio_medico_res = $acer['servicio_medico_res'];
		$servicio_medico =  $acer['servicio_medico'];
		$alguna_discapacidad = $acer['alguna_discapacidad'];
		$discapacidad_res = $acer['discapacidad_res'];
		$aditamentos = $acer['aditamentos'];
		$aditamentos_res = $acer['aditamentos_res'];
		$nna_escuela = $acer['nna_escuela'];
		$nna_escuela_res = $acer['nna_escuela_res'];
		$nna_asiste = $acer['nna_asiste'];
		$nna_asiste_res = $acer['nna_asiste_res'];
		$desempeño =  $acer['desempeño'];
		$desempeño_res = $acer['desempeño_res'];
		$act_recreativas_res = $acer['act_recreativas_res'];
		$actividades_recreativas = $acer['actividades_recreativas'];
		$grado_negacion = $acer['grado_negacion'];
		$reconoce_paso = $acer['reconoce_paso'];
		$tiene_responsabilidad = $acer['tiene_responsabilidad'];
		$necesita_ayuda = $acer['necesita_ayuda'];
		$esta_dispuesto = $acer['esta_dispuesto'];
		$descripcion = $acer['descripcion'];
	 }
    $data = array();
	while ($row=$rNnas->fetch_assoc()) { 
		$data[] = $row;
	}
	$data1 = array();
	while ($row1=$responsables->fetch_assoc()) { 
		$data1[] = $row1;
	}

    
	class pdf extends FPDF{
		var $widths;
		var $aligns;

		function SetWidths($w)
		{
			$this->widths=$w;
		}
		function SetAligns($a)
		{
			$this->aligns=$a;
		}
		function Row($data)
		{
			$nb=0;
			for($i=0;$i<count($data);$i++)
				$nb=max($nb,$this->NbLines($this->widths[$i],utf8_decode($data[$i])));
			$h=8*$nb;
			$this->CheckPageBreak($h);
			for($i=0;$i<count($data);$i++)
			{
				$w=$this->widths[$i];
				$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
				$x=$this->GetX();
				$y=$this->GetY();
				$this->Rect($x,$y,$w,$h);
				$this->MultiCell($w,8,utf8_decode($data[$i]),0,$a,'true');
				$this->SetXY($x+$w,$y);
			}
			$this->Ln($h);
		}
		function CheckPageBreak($h)
		{
			if($this->GetY()+$h>$this->PageBreakTrigger)
				$this->AddPage($this->CurOrientation);
		}
		function NbLines($w,$txt)
		{
			$cw=&$this->CurrentFont['cw'];
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			if($nb>0 and $s[$nb-1]=="\n")
				$nb--;
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$nl=1;
			while($i<$nb)
			{
				$c=$s[$i];
				if($c=="\n")
				{
					$i++;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
					continue;
				}
				if($c==' ')
					$sep=$i;
				$l+=$cw[$c];
				if($l>$wmax)
				{
					if($sep==-1)
					{
						if($i==$j)
							$i++;
					}
					else
						$i=$sep+1;
					$sep=-1;
					$j=$i;
					$l=0;
					$nl++;
				}
				else
					$i++;
			}
			return $nl;
		}
	}
	
	$pdf = new pdf('P','mm','A4');
	$pdf->AddPage();
	$pdf->Image('images/crece.jpg', 15,10,22);
	$pdf->Image('images/dif.jpg', 100,10,8);
	$pdf->Image('images/armas.jpg', 170,5,20);
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetY(23);
	$pdf->Ln(5);
	$pdf->Cell(180,10,utf8_decode('Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia del Estado de Hidalgo'),0,1,'C',0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(5);
	$pdf->Cell(50,10,utf8_decode('Acercamiento familiar del posible caso: '.$folioPC));
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(5);
	$pdf->Cell(50,10,utf8_decode('A cargo de: '.$responsableTS));
	$pdf->Ln(10);
	$pdf->Cell(5);
	$pdf->SetWidths(array(90,90));
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Fecha de acercamiento: '.$fechaAc, 'Fecha de registro: '.$fechaReg));



	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(75);
	$pdf->Cell(200,10,utf8_decode('NNA registrados: '));
	$pdf->Ln(10);
	$pdf->Cell(5);
	$pdf->SetWidths(array(55,20,25,25,30,25));
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Nombre                                                      ', 'Sexo               ', 'Fecha de nacimiento', 'Lugar de nacimiento', 'Ocupación                ', 'Nacionalidad                '));
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(256);
	$pdf->SetTextColor(0);
	foreach ($data as $dat) {
		if($dat['fecha_nac']=='01/01/1900')
			$dat['fecha_nac']='';
		$pdf->Cell(5);
		$pdf->Row(array($dat['nombre'].' '.$dat['apellido_p'].' '.$dat['apellido_m'],$dat['sexo'],$dat['fecha_nac'],$dat['lugar_nac'],$dat['ocupacion'],$dat['nacionalidad']));
	}



	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(60);
	$pdf->Cell(200,10,utf8_decode('Personas adultas responsables del NNA: '));
	$pdf->Ln(10);
	$pdf->Cell(5);
	$pdf->SetWidths(array(25, 15, 18,20,20,17,30,20,20));
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Nombre', 'Edad', 'Teléfono', 'Ocupación', 'Datos económicos','Religión', 'Dirección','Estado cívil','Escolaridad'));
	foreach ($data1 as $dat1) {	
	
		$pdf->Cell(5);
		$pdf->SetWidths(array(25, 15, 18,20,20,17,30,20,20));
		$pdf->SetFont('Arial','',8);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array($dat1['nombre'], $dat1['edad'],$dat1['telefono'],$dat1['ocupacion'], $dat1['datos_economicos'], $dat1['religion'],$dat1['direccion'],$dat1['estado_civil'],$dat1['escolaridad']));

	}

	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(30);
	$pdf->Cell(200,10,utf8_decode('Información aportada por la familia sobre redes familiares y comunitarias'));
	foreach ($eredFam as $red) {
		//$pdf->Cell(5);
		$pdf->SetWidths(array(25, 15, 20,20,30,80));
		$pdf->SetFont('Arial','',8);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array($red['nombre'], $red['edad'],$red['telefono'],$red['parentesco'],$red['direccion'],$red['observa']));
	}
		
	
		$pdf->Ln(10);
		$pdf->SetWidths(array(180));
		$pdf->SetFont('Arial','B',9);
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Información aportada por la familia sobre la situación de la NNA'));
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array($info_fam));
	
		$pdf->Ln(10);
		$pdf->SetWidths(array(180));
		$pdf->SetFont('Arial','B',9);
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Elementos por preguntar a la familia'));
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array('1. ¿Cuenta con registro en el estado familiar? '.$registro_fam));
		$pdf->Row(array('2. ¿Tiene acta de nacimiento? '.$acta_nac));
		$pdf->Row(array('3. ¿Hay algún hijo o hija que no viva con la familia? '.$hijo_sin_res.', '.$hijo_sin));
		$pdf->Row(array('4. En caso de que algún hijo o hija no viva con la familia ¿Tiene convivencia con la NNA? '.$hijo_nna_res.', '.$hijo_nna));
		$pdf->Row(array('5. ¿La opinión de la NNA es considerada y tomada en cuenta? '.$opinion_nna));
		$pdf->Row(array('6. ¿Alguien lo cuida la mayor parte del tiempo? ¿Quién? '.$cuidado_nna_res.', '.$cuidado_nna));
		$pdf->Row(array('7. ¿La NNA vive en una vivienda adecuada para su desarrollo? '.$vivienda_nna_res.', '.$vivienda_nna));
		$pdf->Row(array('8. ¿Ha visto peleas o cualquier otro tipo de violencia?¿Cómo fue? '.$violencia_nna_res.', '.$violencia_nna));
		$pdf->Row(array('9. ¿Ha recibido golpes o insultos? ¿Por parte de quién? '.$maltrato_nna_res.', '.$maltrato_nna));
		$pdf->Row(array('10. ¿Qué come normalmente? ¿Cuántas veces al día consume alimentos? '.$alimentacion_nna_res.', '.$alimentacion_nna));
		$pdf->Row(array('11. ¿Cuándo fue la última vez que lo llevaron al doctor? '.$doctor_nna_res.', '.$doctor_nna));
		$pdf->Row(array('12. ¿Tiene cartilla de vacunación? '.$cartilla_vacunacion));
		$pdf->Row(array('13. ¿Está completa? '.$cartilla_completa));
		$pdf->Row(array('14. ¿Ha estado enfermo? ¿De qué? '.$enfermo_nna_res.', '.$enfermo_nna));
		$pdf->Row(array('15. ¿Recibió asistencia médica? '.$asistencia_medica));
		$pdf->Row(array('16. ¿Tiene servicio médico de seguro social, seguro popular, ISSSTE, PEMEX o SEDENA? '.$servicio_medico_res.', '.$servicio_medico));
		$pdf->Row(array('17. ¿Alguno de sus hijos o hijas tiene alguna discapacidad? '.$discapacidad_res.', '.$alguna_discapacidad));
		$pdf->Row(array('18. Si requiere aditamentos (silla de ruedas, muleta, lentes, etc.) ¿Cuenta con ellos? '.$aditamentos_res.', '.$aditamentos));
		$pdf->Row(array('19. ¿La NNA se encuentra inscrito en la escuela? '.$nna_escuela_res.', '.$nna_escuela));
		$pdf->Row(array('20. ¿La NNA asiste regularmente a la escuela? '.$nna_asiste_res.', '.$nna_asiste));
		$pdf->Row(array('21. ¿Se da algun seguimiento a su desempeño escolar? ¿Quién? '.$desempeño_res.', '.$desempeño));
		$pdf->Row(array('22. ¿Realiza actividades recreativas? ¿Con quién y de qué forma socializa? '.$act_recreativas_res.', '.$actividades_recreativas));

		$pdf->Ln(10);
		$pdf->SetWidths(array(150,30));
		$pdf->SetFont('Arial','B',9);
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Grado de negación: ',$grado_negacion));
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array('¿La persona a cargo del cuidado de la NNA reconoce que pasó algo que le resulta a este perjudicial o lo pone en riesgo? ',$reconoce_paso));
		$pdf->Row(array('¿Reconoce que, como persona adulta, tiene la responsabilidad de que las NNA tengan todo lo que necesitan para crecer bien y no sufrir vulneración de derechos?',$tiene_responsabilidad));
		$pdf->Row(array('¿Reconoce que necesita ayuda para que la NNA tenga todo lo que necesita?',$necesita_ayuda));
		$pdf->Row(array('¿Está dispuesto a hacer esfurzos/compromisos para lograr lo necesario para que las NNA estén bien?', $esta_dispuesto));
		$pdf->SetWidths(array(180));
		$pdf->Row(array('Descripción sobre el grado de negación de las personas adultas a cargo de la NNA '));
		$pdf->Row(array($descripcion));

		$pdf->Ln(10);
		$pdf->SetWidths(array(180));
		$pdf->SetFont('Arial','B',9);
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Observaciones sobre el grado de afectación emocional (actitud, disposición y estado de ánimo) y/o física (enfermedades, adicciones, discapacidad) de las personas adultas a cargo de la NNA'));
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array($observaciones_afecta));

		$pdf->Ln(10);
		$pdf->SetWidths(array(180));
		$pdf->SetFont('Arial','B',9);
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Información aportada por la familia durante el dialogo experimental (que manifiestan necesitar para proteger mejor a la NNA)'));
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array($dialogo_experimental));

		$pdf->Ln(10);
		$pdf->SetWidths(array(180));
		$pdf->SetFont('Arial','B',9);
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Otros datos u observaciones (información aportada por el entorno escolar, comunitario, institucional, etc.)'));
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array($observaciones));

		$pdf->Ln(10);
		$pdf->SetWidths(array(180));
		$pdf->SetFont('Arial','B',9);
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Numero de intervenciones realizadas: '.$inter));
		$pdf->Ln(10);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(10);
	$pdf->Cell(10,10,utf8_decode('Fecha de impresión: '.$fechaHoy),'l');
	$pdf->Ln(10);
	$pdf->Output('I','AcercamientoFamiiar'.$folioPC);
?>