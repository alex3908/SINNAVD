	<?php
	session_start();
require 'fpdf/fpdf.php';
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
$idCaso= $_GET['idCaso'];
$fechaHoy= date("d/m/Y H:i:s", time());
$caso = "SELECT nombre from casos where id=$idCaso";
$ecaso= $mysqli->query($caso);
$nombreCaso = implode($ecaso->fetch_assoc());
$folio_c = "SELECT folio_c from casos where id=$idCaso";
$efolio_c= $mysqli->query($folio_c);
$folio_c = implode($efolio_c->fetch_assoc());
$cuadro="SELECT derechos_nna.derecho, cuadro_guia.id, medidas.medida_p, cuadro_guia.marco, date_format(cuadro_guia.fecha_ejecucion, '%d/%m/%Y %H:%i:%s') as fecha_ejecucion,
cuadro_guia.med_prot, cat.medidaC, cuadro_guia.beneficiario, 
group_concat(' ',concat(nna.nombre, ' ', nna.apellido_p, ' ', nna.apellido_m)) as beneficiarios,
cuadro_guia.responsable_med, 
cuadro_guia.atp_encargada, cuadro_guia.periodicidad, cuadro_guia.estado, cuadro_guia.observaciones,
cuadro_guia.descripcion, date_format(cuadro_guia.fecha_registro, '%d/%m/%Y') as fecha, 
cuadro_guia.id_sp_registro, departamentos.responsable, cuadro_guia.institucion_names
from cuadro_guia inner join derechos_nna on derechos_nna.id=cuadro_guia.id_derecho
inner join departamentos on cuadro_guia.id_sp_registro=departamentos.id 
inner join medidas on cuadro_guia.id_medida=medidas.id 
 join casos on cuadro_guia.id_caso=casos.id
 left join catalogo_medidas cat on cat.id=cuadro_guia.id_mp
 left join benefmed b on b.id_medida=cuadro_guia.id
 left join nna on nna.id=b.id_nna
where cuadro_guia.id_caso='$idCaso' and cuadro_guia.activo=1 group by cuadro_guia.id, derechos_nna.derecho,  medidas.medida_p, cuadro_guia.marco, cuadro_guia.fecha_ejecucion,
cuadro_guia.med_prot, cat.medidaC, cuadro_guia.beneficiario,
cuadro_guia.responsable_med, 
cuadro_guia.atp_encargada, cuadro_guia.periodicidad, cuadro_guia.estado, cuadro_guia.observaciones,
cuadro_guia.descripcion,fecha, 
cuadro_guia.id_sp_registro, departamentos.responsable, cuadro_guia.institucion_names";
	$ecuadro=$mysqli->query($cuadro);
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
	while ($row=$ecuadro->fetch_assoc()) { 
$data[] = $row;
	}
$pdf = new pdf('L','mm','A4');
$pdf->AddPage();
$pdf->Image('images/crece.jpg', 32,10,22);
$pdf->Image('images/dif.jpg', 130,10,8);
$pdf->Image('images/armas.jpg', 205,5,20);
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',11);
$pdf->SetY(23);
$pdf->Ln(5);
$pdf->Cell(215,10,utf8_decode('Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia del Estado de Hidalgo'),0,1,'C',0);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(20);
$pdf->Cell(190,10,utf8_decode('CASO: '.$nombreCaso));
$pdf->Ln(5);
$pdf->Cell(20);
$pdf->Cell(190,10,utf8_decode('CUADRO GUÍA PARA LA ELABORACIÓN DEL PLAN DE RESTITUCIÓN DE DERECHOS'));
$pdf->Ln(10);
$pdf->SetWidths(array(25, 20, 20, 50, 25,25,25,23,18,20,25));
$pdf->SetFont('Arial','',8);
$pdf->SetFillColor(245, 245, 245);
$pdf->SetTextColor(0);
$pdf->Row(array('DERECHO VULNERADO O RESTRINGIDO','MARCO JURIDICO           ','TIPO DE MEDIDA                        ','MEDIDA DE PROTECCIÓN ESPECIAL                                                                            ','BENEFICIARIO                                    ','INSTITUCIÓN O PERSONA RESPONSABLE','RESPONSABLE DE LLEVARLA A CABO','PERIODICIDAD                                                ','FECHA                                        ','EJECUCIÓN                                                ','RESPONSABLE                                    '));
$pdf->SetFont('Arial','',8);
$pdf->SetFillColor(256);
$pdf->SetTextColor(0);
foreach ($data as $dat) {
if(empty($dat['medidaC'])) 
$dat['medidaC']=$dat['med_prot'];
if(empty($dat['beneficiarios']))
$dat['beneficiarios']=$dat['beneficiario'];
if(empty($dat['fecha_ejecucion']))
$dat['fecha_ejecucion'] = 'NO EJECUTADA';
$pdf->Row(array($dat['derecho'],$dat['marco'],$dat['medida_p'],$dat['medidaC'].': '.$dat['descripcion'],$dat['beneficiarios'],$dat['responsable_med'],$dat['atp_encargada'],$dat['periodicidad'],$dat['fecha'],$dat['fecha_ejecucion'],$dat['responsable']));
}
$pdf->SetFont('Arial','',7);
$pdf->Cell(20);
$pdf->Cell(40,10,utf8_decode('Fecha de impresión: '.$fechaHoy),'l');
$pdf->Ln(10);
$pdf->Output('I','Cuadro_guia_'.$folio_c);
?>