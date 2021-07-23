<?php 
ob_start();

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
require 'Classes/PHPExcel.php';

$idCaso= $_GET['idCaso'];
$idDEPTO = $_SESSION['id'];
$fol="SELECT folio_c from casos where id='$idCaso'";
$efol=$mysqli->query($fol);
while ($row=$efol->fetch_assoc()) {
	$folioCaso= $row['folio_c'];
}
$fecha= date ("j/n/Y");
$cuadro="SELECT derechos_nna.derecho, cuadro_guia.id, medidas.medida_p, cuadro_guia.marco, cuadro_guia.med_prot, cuadro_guia.id_mp, cuadro_guia.beneficiario, cuadro_guia.responsable_med, cuadro_guia.atp_encargada, cuadro_guia.periodicidad, cuadro_guia.estado, cuadro_guia.observaciones, cuadro_guia.fecha, cuadro_guia.id_sp_registro, departamentos.responsable, casos.folio_c from cuadro_guia, derechos_nna, departamentos, medidas, casos where cuadro_guia.id_caso='$idCaso' AND cuadro_guia.id_caso=casos.id AND cuadro_guia.id_medida=medidas.id and derechos_nna.id=cuadro_guia.id_derecho and cuadro_guia.id_sp_registro=departamentos.id";
		 
	$ecuadro=$mysqli->query($cuadro);

$descarga="INSERT into descargas_plan (plan,fecha,respo_reg) values ('$idCaso','$fecha','$idDEPTO')";
$edesc=$mysqli->query($descarga);
$fila=13;


$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Ing. Ivan Flores")->setDescription("Plan de restitución de derechos");



$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle("Plan Restitución");
$objActSheet = $objPHPExcel->getActiveSheet();

$estiloTituloReporte = array(
    'font' => array(
	'name'      => 'Arial',
	'bold'      => true,
	'italic'    => false,
	'strike'    => false,
	'size' =>13
    ),
    'fill' => array(
	'type'  => PHPExcel_Style_Fill::FILL_SOLID
	),
    'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_NONE
	)
    ),
    'alignment' => array(
	'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
	);

$estiloTituloReporte2 = array(
    'font' => array(
	'name'      => 'Arial',
	'bold'      => false,
	'italic'    => false,
	'strike'    => false,
	'size' =>12
    ),
    'fill' => array(
	'type'  => PHPExcel_Style_Fill::FILL_SOLID
	),
    'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_NONE
	)
    ),
    'alignment' => array(
	'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
	);
$estilo2doPiecito = array(
    'font' => array(
	'name'      => 'Arial',
	'bold'      => false,
	'italic'    => false,
	'strike'    => false,
	'size' =>10
    ),
    'fill' => array(
	'type'  => PHPExcel_Style_Fill::FILL_SOLID
	),
    'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_NONE
	)
    ),
    'alignment' => array(
	'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
	);

	$estiloTituloColumnas = array(
    'font' => array(
	'name'  => 'Arial',
	'bold'  => true,
	'size' =>11,
	'color' => array(
	'rgb' => 'FFFFFF'
	)
    ),
    'fill' => array(
	'type' => PHPExcel_Style_Fill::FILL_SOLID,
	'color' => array('rgb' => '538DD5')
    ),
    'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN
	)
    ),
    'alignment' =>  array(
	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
	);
$estiloTituloColumnas2 = array(
    'font' => array(
	'name'  => 'Arial',
	
	'size' =>10,
	
    ),
    'fill' => array(
	'type' => PHPExcel_Style_Fill::FILL_SOLID,
	
    ),
    'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN
	)
    ),
    'alignment' =>  array(
	'horizontal'=> PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	'vertical'  => PHPExcel_Style_Alignment::VERTICAL_CENTER
    )
	);
$objPHPExcel->getActiveSheet()->getStyle('B6:K10')->applyFromArray($estiloTituloReporte2);	
$objPHPExcel->getActiveSheet()->getStyle('K5')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('B4:K4')->applyFromArray($estiloTituloReporte);
$objPHPExcel->getActiveSheet()->getStyle('B12:K12')->applyFromArray($estiloTituloColumnas);

$objPHPExcel->getActiveSheet()->setCellValue('B4', 'PLAN DE RESTITUCIÓN DE DERECHOS');
$objActSheet->getStyle('B4')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objActSheet->mergeCells('B4:K4');

$objPHPExcel->getActiveSheet()->setCellValue('K5', 'Fecha:'.$fecha);

$objActSheet->mergeCells('B6:K10');
$objPHPExcel->getActiveSheet()->setCellValue('B6', 'El siguiente plan versa sobre la restitución de derechos de ________________, quien se encuentra a cargo de _______________________, en el domicilio _____________________ y con numero telefónico ____________________________');
$objActSheet->getStyle('B6')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objPHPExcel->getActiveSheet()->getStyle('B6')->getAlignment()->setWrapText(true);



$objActSheet->getStyle('B12')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objActSheet->getStyle('C12')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objActSheet->getStyle('D12')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objActSheet->getStyle('E12')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objActSheet->getStyle('F12')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objActSheet->getStyle('G12')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objActSheet->getStyle('H12')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objActSheet->getStyle('I12')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objActSheet->getStyle('J12')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objActSheet->getStyle('K12')->getAlignment()->applyFromArray( array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,) );
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$objPHPExcel->getActiveSheet()->getStyle('B12')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('C12')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('D12')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('E12')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('F12')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('G12')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('H12')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('I12')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('J12')->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('K12')->getAlignment()->setWrapText(true);

	$objPHPExcel->getActiveSheet()->setCellValue('B12', 'DERECHO VULNERADO O RESTRINGIDO');
	$objPHPExcel->getActiveSheet()->setCellValue('C12', 'MARCO JURIDICO');
	$objPHPExcel->getActiveSheet()->setCellValue('D12', 'TIPO DE MEDIDA');
	$objPHPExcel->getActiveSheet()->setCellValue('E12', 'MEDIDA DE PROTECCIÓN ESPECIAL');
	$objPHPExcel->getActiveSheet()->setCellValue('F12', 'BENEFICIARIO');	
	$objPHPExcel->getActiveSheet()->setCellValue('G12', 'INSTITUCION O PERSONA RESPONSABLE');
	$objPHPExcel->getActiveSheet()->setCellValue('H12', 'AREA, TITULAR O PERSONA ENCARGADA DE LLEVARLA A CABO');
	$objPHPExcel->getActiveSheet()->setCellValue('I12', 'PERIODICIDAD');
	$objPHPExcel->getActiveSheet()->setCellValue('J12', 'ESTADO');
	$objPHPExcel->getActiveSheet()->setCellValue('K12', 'FECHA');


	while($row = $ecuadro->fetch_assoc())
	{
		$objPHPExcel->getActiveSheet()->getStyle('B'.$fila)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila, $row['derecho']);
		$objPHPExcel->getActiveSheet()->getStyle('C'.$fila)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila, $row['marco']);
		$objPHPExcel->getActiveSheet()->getStyle('D'.$fila)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$fila, $row['medida_p']);
		
		$responsable_med=$row['responsable_med'];    
		$atp_encargada=$row['atp_encargada'];  
		$periodicidad=$row['periodicidad'];    
		$fecha=$row['fecha'];   
		$responsable=$row['responsable'];
		$id_CG=$row['id'];
		$es=$row['estado']; 
		$porevaluar=$row['id_sp_registro'];
		$observaciones=$row['observaciones'];
		$lamed=$row['med_prot']; 
		$lamedc=$row['id_mp'];
		$bene=$row['beneficiario'];

		if (empty($lamed)) {
			$me="SELECT medidaC from catalogo_medidas where id='$lamedc'";
			$eme=$mysqli->query($me);

			while ($row=$eme->fetch_assoc()) {
				$objPHPExcel->getActiveSheet()->getStyle('E'.$fila)->getAlignment()->setWrapText(true);
				$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $row['medidaC']);
			}
		}else {
			$objPHPExcel->getActiveSheet()->getStyle('E'.$fila)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila, $lamed);
		} 
		$bene2='';
		if (empty($bene)) {
			$nnaa="SELECT nna.nombre, nna.apellido_p, nna.apellido_m from benefmed, nna where benefmed.id_medida='$id_CG' and benefmed.id_nna=nna.id";
			$ennaa=$mysqli->query($nnaa);
									
			while ($row=$ennaa->fetch_assoc()) {		
				$bene1=$row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];
			} 
		
			$bene2=$bene1.", ".$bene2;
			$bene2=trim($bene2, ', ');

			$objPHPExcel->getActiveSheet()->getStyle('F'.$fila)->getAlignment()->setWrapText(true);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $bene2);

		}else {

		$Cadabene=explode(':', $bene);		
		
		for ($i=0; $i <count($Cadabene) ; $i++) {

		@list($idMorro, $tipoMorro)=explode(' ', $Cadabene[$i]);
														
		if ($tipoMorro=='NE') {
			$sql="SELECT nombre, apellido_p, apellido_m from nna where id='$idMorro'";
			$esql=$mysqli->query($sql);
		
			while ($row=$esql->fetch_assoc()) {
		
		$bene1= $row['nombre']." ".$row['apellido_p']." ".$row['apellido_m'];
				
			}
		}else if ($tipoMorro=='E') {
			$sql="SELECT folio, sexo from nna_exposito where id='$idMorro'";
			$esql=$mysqli->query($sql);
			while ($row=$esql->fetch_assoc()) {
		$bene1=$row['folio']." ".$row['sexo'];
				
			} 
		}
		$bene2=$bene1.", ".$bene2;
		$bene2=trim($bene2, ', ');
	}	
		$objPHPExcel->getActiveSheet()->getStyle('F'.$fila)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$fila, $bene2);
	}
		$objPHPExcel->getActiveSheet()->getStyle('G'.$fila)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$fila, $responsable_med);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$fila)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$fila, $atp_encargada);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$fila)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$fila, $periodicidad);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$fila)->getAlignment()->setWrapText(true);
		if ($es==0) {
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, 'PENDIENTE');
		}else if ($es==1) {
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$fila, 'EJECUTADA');
		}
		$objPHPExcel->getActiveSheet()->getStyle('K'.$fila)->getAlignment()->setWrapText(true);
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$fila, $fecha);

		
		$fila++;
		
	}
	$fila--;
$objPHPExcel->getActiveSheet()->getStyle('B13:K'.$fila)->applyFromArray($estiloTituloColumnas2);
	$espacio=$fila+1;
	$segunda=$espacio+2;
	$objActSheet->mergeCells('B'.$espacio.':K'.$segunda);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$espacio.':K'.$segunda)->applyFromArray($estilo2doPiecito);
	$objPHPExcel->getActiveSheet()->setCellValue('B'.$espacio, 'De no dar cumplimiento y seguimiento a las Medidas de Protección Especial decretadas por esta Procuraduría de Protección en sus facultades de la Ley General de Niñas, Niños y Adolescentes en el Artículo 123, se dará aviso a la autoridad competente para su debido conocimiento y acciones necesarias para la protección y restitución de los derechos de ______________________');
	$objPHPExcel->getActiveSheet()->getStyle('B'.$espacio)->getAlignment()->setWrapText(true);

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Disposition: attachment;filename="Plan'.$folioCaso.'.xlsx"');
	header('Cache-Control: max-age=0');
	
	$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	$objWriter->save('php://output');


?>