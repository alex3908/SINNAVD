	<?php
	session_start();
	require 'fpdf/fpdf.php';
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	$idPosibleCaso= $_GET['idPosibleCaso'];
	$fechaHoy= date("d/m/Y H:i:s", time());

	$qPosibleCaso="SELECT folio, tabj.id_departamentos_asignado AS juridico, tabts.id_departamentos_asignado AS ts,
    	tabps.id_departamentos_asignado AS ps, poscaso.estadoAtencion, poscaso.fechaAtencion, 
		poscaso.id_departamentos as responsable_atencion, posible_caso.responsable_registro
		FROM posible_caso LEFT JOIN historico_asignaciones_juridico tabj ON id_asignado_juridico = tabj.id
    	LEFT JOIN historico_asignaciones_trabajo_social tabts ON id_asignado_ts = tabts.id
    	LEFT JOIN historico_asignaciones_psicologia tabps ON id_asignado_ps = tabps.id
    	left join historico_atenciones_pos_casos poscaso on id_estado_atencion = poscaso.id
		WHERE posible_caso.id ='$idPosibleCaso' and posible_caso.activo=1";
	$rPosibleCaso=$mysqli->query($qPosibleCaso);
	$rPosibleCaso2=$mysqli->query($qPosibleCaso);
	while ($rowPC=$rPosibleCaso->fetch_assoc()) {
		$folioPC=$rowPC['folio'];
		$asignadoJ=$rowPC['juridico'];
		$asignadoTS=$rowPC['ts'];
		$asignadoPS=$rowPC['ps'];
		$estadoAtencion=$rowPC['estadoAtencion'];
		$fechaAtencion=$rowPC['fechaAtencion'];
		$responAtencion=$rowPC['responsable_atencion'];	
		$ResponRegistro=$rowPC['responsable_registro'];
	}
	$qresPS="SELECT responsable from departamentos where id='$asignadoPS'";
	$rresPS=$mysqli->query($qresPS);
	while ($rowps=$rresPS->fetch_assoc()) {  //obtiene el nombre del asignado de trabajo social (TS)
		$responsablePS=$rowps['responsable'];
	}
	$qresTS="SELECT responsable from departamentos where id='$asignadoTS'";
    $rresTS=$mysqli->query($qresTS);
    while ($rowts=$rresTS->fetch_assoc()) {  //obtiene el nombre del asignado de trabajo social (TS)
        $responsableTS=$rowts['responsable'];
	}
	$qresJ="SELECT responsable from departamentos where id='$asignadoJ'";
    $rresJ=$mysqli->query($qresJ);
    while ($rowj=$rresJ->fetch_assoc()) {  //obtiene el nombre del asignado de juridico (j)
        $responsableJ=$rowj['responsable'];
    }
    switch ($estadoAtencion) {
    	case '4':
		    $qCaso="SELECT group_concat(' ',casos.folio_c) as casos from casos inner join relacion_pc_caso on id_caso=casos.id
		    where id_posible_caso='$idPosibleCaso' group by id_posible_caso;";
			$rCaso=$mysqli->query($qCaso);
			$TieneCaso= implode($rCaso->fetch_assoc());
    		$estadoAtencion1='Atendido positivo '.$TieneCaso;
    		break;
    	case '3':
    		$estadoAtencion1='Atendido negativo';
    		break;
    	case '2':
    		$estadoAtencion1='En proceso';
    		break;	
    	default:
    		$estadoAtencion1='No atendido';
    		break;
    }

    $qNnas="SELECT nombre, apellido_p, apellido_m, sexo.sexo, date_format(nna_reportados.fecha_nacimiento,'%d/%m/%Y') as fecha_nac, lugar_nacimiento, edad, padre_fallecido_covid, madre_fallecida_covid
	from nna_reportados left join sexo on nna_reportados.sexo=sexo.id
	where id_posible_caso='$idPosibleCaso' and activo=1";
	$rNnas=$mysqli->query($qNnas);
	
		$qReportes="SELECT reportes_vd.folio, date_format(reportes_vd.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_registro, 
    	cat_recepcion_reporte.recepcion, distritos.distrits, maltratos as maltrato, reportes_vd.otros_datos,
    	reportes_vd.persona_reporte, reportes_vd.narracion, municipios.municipio, 
    	localidades.localidad, reportes_vd.calle, reportes_vd.ubicacion, departamentos.responsable,
    	reportes_vd.respo_reg 
    	from reportes_vd inner join municipios on clm=municipios.id
    	inner join departamentos on departamentos.id=reportes_vd.respo_reg
   		inner join  localidades on localidades.id=reportes_vd.id_localidad 
   	 	left join distritos on distritos.id=reportes_vd.id_distrito
    	inner join cat_recepcion_reporte on id_recepcion=cat_recepcion_reporte.id 
    	where reportes_vd.id_posible_caso='$idPosibleCaso' and reportes_vd.activo=1"; 
    $rReportes=$mysqli->query($qReportes);
    
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
	$data = array();
	while ($row=$rNnas->fetch_assoc()) { 
		$data[] = $row;
	}
	$data1 = array();
	while ($row1=$rReportes->fetch_assoc()) { 
		$data1[] = $row1;
	}
	$pdf = new pdf('P','mm','A4');
	$pdf->AddPage();
	$pdf->Image('images/crece.jpg', 15,10,22);
	$pdf->Image('images/dif.jpg', 100,10,8);
	$pdf->Image('images/armas.jpg', 170,5,20);
	$pdf->SetFillColor(232,232,232);
	$pdf->SetFont('Arial','B',11);
	$pdf->SetY(23);
	$pdf->Ln(5);
	$pdf->Cell(180,10,utf8_decode('Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia del Estado de Hidalgo'),0,1,'C',0);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(60);
	$pdf->Cell(250,10,utf8_decode('Folio posible caso: '.$folioPC));
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(75);
	$pdf->Cell(200,10,utf8_decode('Personal asignado'));
	$pdf->Ln(10);
	$pdf->Cell(5);
	$pdf->SetWidths(array(60,60,60));
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Juridico: '.$responsableJ, 'Trabajo Social: '.$responsableTS, 'Psicología: '.$responsablePS));

	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(5);
	$pdf->Cell(200,10,utf8_decode('Estado actual: '.$estadoAtencion1));

	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(75);
	$pdf->Cell(200,10,utf8_decode('NNA registrados: '));
	$pdf->Ln(10);
	$pdf->Cell(5);
	$pdf->SetWidths(array(50,20,30,30,30,20));
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Nombre                                                      ', 'Sexo               ', 'Fecha de nacimiento', 'Lugar de nacimiento', 'Orfandad por COVID-19', 'Edad                '));
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(256);
	$pdf->SetTextColor(0);
	foreach ($data as $dat) {
		if($dat['padre_fallecido_covid']==1 and $dat['madre_fallecida_covid']==0) 
			$dat['padre_fallecido_covid'] = "Padre"; 
		elseif($dat['padre_fallecido_covid']==0 and $dat['madre_fallecida_covid']==1) 
			$dat['padre_fallecido_covid'] = "Madre";
		elseif($dat['padre_fallecido_covid']==1 and $dat['madre_fallecida_covid']==1) 
			$dat['padre_fallecido_covid'] = "Ambos"; 
		else $dat['padre_fallecido_covid'] = 'No';
		if($dat['fecha_nac']=='01/01/1900')
			$dat['fecha_nac']='';
		$pdf->Cell(5);
		$pdf->Row(array($dat['nombre'].' '.$dat['apellido_p'].' '.$dat['apellido_m'],$dat['sexo'],$dat['fecha_nac'],$dat['lugar_nacimiento'],$dat['padre_fallecido_covid'],$dat['edad']));
	}



	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(75);
	$pdf->Cell(200,10,utf8_decode('Reportes recibidos: '));
	
	foreach ($data1 as $dat1) {
		$pdf->Ln(10);
		$pdf->Cell(5);
		$pdf->SetWidths(array(50,50,80));
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(245, 245, 245);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Folio del reporte: '.$dat1['folio'], 'Fecha: '.$dat1['fecha_registro'], 'Forma de recepcion: '.$dat1['recepcion']));
		$pdf->Cell(5);
		$pdf->SetWidths(array(60,60,60));
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Persona que reporto: '.$dat1['persona_reporte'], 'Tipo de maltrato: '.$dat1['maltrato'], 'Distrito: '.$dat1['distrits']));
		$pdf->Cell(5);
		$pdf->SetWidths(array(90,90));
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Narración de lo sucedido: '.$dat1['narracion'], 'Otros datos u observaciones relevantes: '.$dat1['otros_datos']));
		$pdf->Cell(5);
		$pdf->SetWidths(array(120,60));
		$pdf->SetFont('Arial','',9);
		$pdf->SetFillColor(255, 255, 255);
		$pdf->SetTextColor(0);
		$pdf->Row(array('Ubicacion: Municipio '.$dat1['municipio'].', Localidad '.$dat1['localidad'].', Calle '.$dat1['calle'].', Referencias '.$dat1['ubicacion'], 'Responsable de registro: '.$dat1['responsable']));

	}
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(20);
	$pdf->Cell(40,10,utf8_decode('Fecha de impresión: '.$fechaHoy),'l');
	$pdf->Ln(10);
	$pdf->Output('I','Posible_caso'.$folioPC);
?>