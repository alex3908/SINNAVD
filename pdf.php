<?php 
include 'fpdf/fpdf.php';
require 'conexion.php';
$idCaso=$_GET['idCaso'];

date_default_timezone_set('America/Mexico_City');
    //preguntamos la zona horaria
    $zonahoraria = date_default_timezone_get();
$fecha= date ("j/n/Y");
$horaa= date ("h:i");

$query="SELECT casos.id, casos.folio_c, casos.nombre, casos.descripcion, departamentos.responsable, casos.fecha FROM departamentos, casos WHERE casos.funcionario_reg=departamentos.id and casos.id='$idCaso'";
	
	$resultado=$mysqli->query($query);

$sqlnna="SELECT nna.folio, nna.nombre, nna.apellido_p, nna.apellido_m, nna.fecha_nac, nna.sexo from nna, nna_caso where nna_caso.id_caso='$idCaso' and nna_caso.id_nna=nna.id";
	$esqlnna=$mysqli->query($sqlnna);


$pdf= new FPDF('L','mm','Legal');//creas el elemento
$pdf->AddPage();//agregar nueva hoja al documento
$pdf->Image('images/crece.jpg', 10,10,27);
$pdf->Image('images/dif.jpg', 100,10,10);
$pdf->Image('images/armas.jpg', 175,5,30);
$pdf->SetFillColor(232,232,232);
$pdf->SetFont('Arial','B',12);
$pdf->SetY(35);
$pdf->Cell(190,10,utf8_decode('Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia del Estado de Hidalgo'),0,1,'C',0);
$pdf->SetFont('Arial','I',8);
$pdf->SetX(110);
$pdf->Cell(130,10,utf8_decode('Pachuca Hidalgo, Hora: '.$horaa.', Fecha: '.$fecha),0,1,'C',0);
//especificas tipo de letra, negritaETC, tamaño
$pdf->MultiCell(40,10,utf8_decode('DERECHO VULNERADO O RESTRINGIDO'),1,'L',0);
$pdf->SetXY(50,55);
$pdf->MultiCell(40,20,utf8_decode('MARCO JURIDICO'),1,'L',0);
$pdf->SetXY(90,55);
$pdf->MultiCell(40,20,utf8_decode('TIPO DE MEDIDA'),1,'L',0);
$pdf->SetXY(130,55);
$pdf->MultiCell(40,10,utf8_decode('MEDIDA DE PROTECCIÓN ESPECIAL'),1,'L',0);
$pdf->SetXY(170,55);
$pdf->MultiCell(40,10,utf8_decode('BENEFICIARIO'),1,'L',0);
$pdf->SetXY(210,55);
$pdf->MultiCell(40,10,utf8_decode('INSTITUCIÓN O PERSONA RESPONSABLE'),1,'L',0);
$pdf->SetXY(250,55);
$pdf->MultiCell(40,10,utf8_decode('RESPONSABLE DE LLEVARLA A CABO'),1,'L',0);
$pdf->SetXY(290,55);
$pdf->MultiCell(40,10,utf8_decode('PERIODICIDAD'),1,'L',0);
$pdf->SetXY(330,55);
$pdf->MultiCell(40,10,utf8_decode('FECHA'),1,'L',0);
$pdf->SetXY(370,55);
$pdf->MultiCell(40,10,utf8_decode('EJECUCIÓN'),1,'L',0);
												
while ($row=$resultado->fetch_assoc()) {
	$pdf->SetFont('Arial','I',10);


}


$pdf->Line(55,170,100,170);//posicion en X, posicion en Y, largo, angulo
$pdf->SetXY(55,180);
$pdf->Cell(75,10,utf8_decode('Nombre y firma'),0,0,'C',0);

$pdf->Line(130,170,175,170);
$pdf->Output();//cierre del doc
?>