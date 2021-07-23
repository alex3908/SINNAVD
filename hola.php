<?php

require __DIR__ . "/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spread = new Spreadsheet();

$sheet = $spread->getActiveSheet();
$sheet->setTitle("Hoja 1");
$sheet->setCellValueByColumnAndRow(1, 1, "Valor en la posición 1, 1");
$sheet->setCellValue("B2", "Valor en celda B2");
$writer = new Xlsx($spread);
$writer->save('php://output/reporte.xlsx');

?>