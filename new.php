<?php 
session_start();
require __DIR__ . "/vendor/autoload.php";
require 'conexion.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
$idDEPTO = $_SESSION['id'];
$idCaso= $_GET['idCaso'];



$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setCreator('SINNAVD')  
    ->setSubject('Plan de Restitucion de Derechos');

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Hola");

$hoja->setCellValue('B12', 'DERECHO VULNERADO O RESTRINGIDO');
$hoja->setCellValue('C12', 'MARCO JURIDICO');
$hoja->setCellValue('D12', 'TIPO DE MEDIDA');
$hoja->setCellValue('E12', 'MEDIDA DE PROTECCIÓN ESPECIAL');
$hoja->setCellValue('F12', 'BENEFICIARIO');	
$hoja->setCellValue('G12', 'INSTITUCION O PERSONA RESPONSABLE');
$hoja->setCellValue('H12', 'AREA, TITULAR O PERSONA ENCARGADA DE LLEVARLA A CABO');
$hoja->setCellValue('I12', 'PERIODICIDAD');
$hoja->setCellValue('J12', 'ESTADO');
$hoja->setCellValue('K12', 'FECHA');	


$nombreDelDocumento = "Plan.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');
 
$writer = IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;
?>