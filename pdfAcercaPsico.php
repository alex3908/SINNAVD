	<?php
	session_start();
	require 'fpdf/fpdf.php';
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	$idReporte= $_GET['idReporte'];
	$idNNA = $_GET['idNNA'];
	$fechaHoy= date("d/m/Y H:i:s", time());
	$qidAc = "SELECT id from acercamiento_psic where id_reporte=$idReporte and id_nna = $idNNA";
	$qidAc = $mysqli->query($qidAc);
	$idAc = implode($qidAc->fetch_assoc());

    $datosAcer = "SELECT ac.tipo, pc.folio, d.responsable, if(ac.ase_virtual, 'Virtual', 'Presencial') as acVirtual, date_format(fecha_acercamiento,'%d/%m/%Y') as fecha_acps, 
    date_format(ac.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, nna_ac.nombre, nna_ac.apellido_p, nna_ac.apellido_m,
    nna_ac.sexo, if(nna_ac.fecha_nac='1900-01-01', 'SIN DATO', nna_ac.fecha_nac) as fecha_nac, nna_ac.lugar_nac, nna_ac.ocupacion, nna_ac.nacionalidad,
    ac.apodo, ac.idioma, ac.adulto, ac.numero, ac.narrativa_libre, ac.molestias, ac.temor, ac.adulto_signi, ac.af1, ac.af2, ac.af3, ac.af4,
    ac.af5, ac.af6, ac.dinamica_familiar, ac.area_salud, ac.area_escolar, ac.actividades_diarias, ac.area_social,
    ac.mecanismos, ac.d_vulne, ac.otros 
    FROM posible_caso pc inner join historico_asignaciones_psicologia ps on ps.id_posible_caso=pc.id
    inner join departamentos d on d.id=ps.id_departamentos_asignado
    inner join acercamiento_psic ac on ac.id_reporte=pc.id
    inner join part1ac on part1ac.id_reporte=pc.id
    inner join nna_ac on nna_ac.id_acerca = part1ac.id
    where ac.id=$idAc and ac.activo=1 and nna_ac.id=$idNNA";
$datosAcer = $mysqli->query($datosAcer);

	foreach ($datosAcer as $acer) {
		$tipo = $acer['tipo'];
		$folio =  $acer['folio'];
		$responsable =  $acer['responsable'];
		$acVirtual =  $acer['acVirtual'];
		$fecha_acps =  $acer['fecha_acps'];
		$fecha_reg =  $acer['fecha_reg'];
		$nombre =  $acer['nombre'];
		$apellido_p = $acer['apellido_p'];
		$apellido_m =  $acer['apellido_m'];
		$sexo =  $acer['sexo'];
		$fecha_nac = $acer['fecha_nac'];
		$lugar_nac =  $acer['lugar_nac'];
		$ocupacion =  $acer['ocupacion'];
		$nacionalidad = $acer['nacionalidad'];

		$apodo = $acer['apodo'];
		$idioma = $acer['idioma'];
		$adulto = $acer['adulto'];
		$numero = $acer['numero'];
		$narrativa_libre = $acer['narrativa_libre'];
		$molestias = $acer['molestias'];
		$temor = $acer['temor'];
		$adulto_signi = $acer['adulto_signi'];
		$af1 = $acer['af1'];
		$af2 = $acer['af2'];
		$af3 = $acer['af3'];
		$af4 = $acer['af4'];
		$af5 = $acer['af5'];
		$af6 = $acer['af6'];
		$dinamica_familiar = $acer['dinamica_familiar'];
		$area_salud = $acer['area_salud'];
		$area_escolar = $acer['area_escolar'];
		$actividades_diarias = $acer['actividades_diarias'];
		$area_social = $acer['area_social'];
		$mecanismos = $acer['mecanismos'];
		$d_vulne = $acer['d_vulne'];
		$otros = $acer['otros'];
	 }

	 if(empty($tipo))
	 	$tipo = 'ACERCAMIENTO';
	 if(!empty($d_vulne)){
	 	$d_vulne.='0';
	 	$qDerechos="SELECT derecho from derechos_nna where id in ($d_vulne)";
	 	$qDerechos = $mysqli->query($qDerechos);
	 	$numDer = $qDerechos->num_rows;
	 	if($qDerechos){
	 		$d_vulne='';
	 		$i=0;
	 		foreach($qDerechos as $derechos) {
	 			$d_vulne.=$derechos['derecho'];
	 			$i++;
	 			if($i<$numDer)
	 				$d_vulne.='; ';
	 		}
	 	
	 	}
	 }
	 else $d_vulne='NINGUNO';

    
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
	$pdf->Cell(30);
	$pdf->Cell(50,10,utf8_decode('Intervención psicológica: '.$tipo.' del posible caso: '.$folio));
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(65);
	$pdf->Cell(50,10,utf8_decode('A cargo de: '.$responsable));
	$pdf->Ln(10);
	$pdf->Cell(5);
	$pdf->SetWidths(array(60,60,60));
	$pdf->SetFont('Arial','',9);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Tipo: '.$acVirtual, 'Fecha de acercamiento: '.$fecha_acps, 'Fecha de registro: '.$fecha_reg));



	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(85);
	$pdf->Cell(200,10,utf8_decode('NNA: '));
	$pdf->Ln(10);
	$pdf->Cell(5);
	$pdf->SetWidths(array(80,40,60));
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Nombre                                                      ', 'Sexo               ', 'Fecha de nacimiento'));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($nombre.' '.$apellido_p.' '.$apellido_m, $sexo, $fecha_nac));
	$pdf->Cell(5);
	$pdf->SetWidths(array(70,55,55));
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Lugar de nacimiento', 'Ocupación                ', 'Nacionalidad                '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(256);
	$pdf->SetTextColor(0);
	$pdf->Row(array($lugar_nac	, $ocupacion	, $nacionalidad));

	$pdf->Cell(5);
	$pdf->SetWidths(array(90,90));
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('¿Cómo te gusta que te llamen?       ', 'Idioma                                            '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($apodo, $idioma));
	$pdf->Cell(5);
		$pdf->SetWidths(array(90,90));
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Aduto responsable        ', 'Número de teléfono                                '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($adulto, $numero));

	
	$pdf->Ln(5);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Registro textual de la narrativa libre o proyeccion a traves del juego y/o dibujo'));
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(5);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($narrativa_libre));
	
	$pdf->Ln(5);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Registro textual de lo que dijo al preguntarle si hay algo que le molesta o lastima'));
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(5);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($molestias));

	$pdf->Ln(5);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('¿A quien dice (o muestra) temer?                                                   '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($temor));

	$pdf->Ln(5);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('¿Que adulto le resulta significativo y quiere tener cerca de si?(Hay adultos que sean de su confianza)'));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($adulto_signi));

	$pdf->Ln(5);
	$pdf->SetFont('Arial','B',9);
	$pdf->Cell(75);
	$pdf->Cell(200,10,utf8_decode('Area Familiar: '));

	$pdf->Ln(10);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('¿Ha dejado de ver a alguien que quiere mucho? ¿Quien (es)? ¿Porque? '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($af1));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('¿Que pasa en casa cuando opina sobre algo? '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($af2));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('¿Quien lo cuida la mayor parte del tiempo? '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($af3));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('¿Que sucede cuando se porta mal? '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($af4));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('¿Ha visto peleas o cualquier otro tipo de violencia? '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($af5));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('¿Ha recibido golpes o insultos? ¿De quien? '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($af6));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Dinamica Familiar '));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($dinamica_familiar));

	$pdf->Ln(10);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Area de salud (Asistencia medica/psicologica/otros)'));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($area_salud));

	$pdf->Ln(10);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Area escolar'));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($area_escolar));

	$pdf->Ln(10);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Actividades diarias'));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($actividades_diarias));

	$pdf->Ln(10);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Area social (Juego/actividades recreativas)'));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($area_social));

	$pdf->Ln(10);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Mecanismos de defensa identificados'));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($mecanismos));

	$pdf->Ln(10);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Derechos vulnerados identificados'));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($d_vulne));

	$pdf->Ln(10);
	$pdf->SetWidths(array(180));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','B',8);
	$pdf->SetFillColor(245, 245, 245);
	$pdf->SetTextColor(0);
	$pdf->Row(array('Otros datos u observaciones relevantes'));
	$pdf->Cell(5);
	$pdf->SetFont('Arial','',8);
	$pdf->SetFillColor(255, 255, 255);
	$pdf->SetTextColor(0);
	$pdf->Row(array($otros));


	$pdf->SetFont('Arial','',7);
	$pdf->Cell(10);
	$pdf->Cell(10,10,utf8_decode('Fecha de impresión: '.$fechaHoy),'l');
	$pdf->Ln(10);
	$pdf->Output('I','AcercamientoPsicológico'.$folio);


?>