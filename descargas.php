<?php 

	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
	$id= $_GET['id'];

	if ($id==1) {
	header("Content-disposition: attachment; filename=ACTA CONVENIO.docx");
	header("Content-type: application/msword");
	readfile("ACTA CONVENIO.docx");
	}else if ($id==2) {
	header("Content-disposition: attachment; filename=ACTA ENTREGA.docx");
	header("Content-type: application/msword");
	readfile("ACTA ENTREGA.docx");
	}else if ($id==3) {
	header("Content-disposition: attachment; filename=Antecedentes perinatales.xlsx");
	header("Content-type: application/vnd.ms-excel");
	readfile("Antecedentes perinatales.xlsx");
	}else if ($id==4) {
	header("Content-disposition: attachment; filename=ATENCION MIGRANTES.xlsx");
	header("Content-type: application/vnd.ms-excel");
	readfile("ATENCION MIGRANTES.xlsx");
	}else if ($id==5) {
	header("Content-disposition: attachment; filename=KIT GUARDIA.docx");
	header("Content-type: application/msword");
	readfile("KIT GUARDIA.docx");
	}else if ($id==6) {
	header("Content-disposition: attachment; filename=Formato inicio de carpeta.xlsx");
	header("Content-type: application/vnd.ms-excel");
	readfile("Formato inicio de carpeta.xlsx");
	}else if ($id==7) {
	header("Content-disposition: attachment; filename=Oficio INGRESO NNA MIGRANTE.docx");
	header("Content-type: application/msword");
	readfile("Oficio INGRESO NNA MIGRANTE.docx");
	}else if ($id==8) {
	header("Content-disposition: attachment; filename=OFICIO MIGRACION NNA ACOMPANADOS.docx");
	header("Content-type: application/msword");
	readfile("OFICIO MIGRACION NNA ACOMPANADOS.docx");
	}else if ($id==9) {
	header("Content-disposition: attachment; filename=OFICIO MIGRACION NNA SOLOS QUE INGRESAN.docx");
	header("Content-type: application/msword");
	readfile("OFICIO MIGRACION NNA SOLOS QUE INGRESAN.docx");
	}else if ($id==10) {
	header("Content-disposition: attachment; filename=Oficio PPNNAyF (vacaciones).docx");
	header("Content-type: application/msword");
	readfile("Oficio PPNNAyF (vacaciones).docx");
	}else if ($id==11) {
	header("Content-disposition: attachment; filename=Protocolo.pdf");
	header("Content-type: application/pdf");
	readfile("Protocolo.pdf");
	}else if ($id==12) {
	header("Content-disposition: attachment; filename=Ficha Trabajo Infantil.docx");
	header("Content-type: application/msword");
	readfile("Ficha Trabajo Infantil.docx");
	}else if ($id==13) {
	header("Content-disposition: attachment; filename=Ficha Trabajo Infantil Seguimiento.docx");
	header("Content-type: application/msword");
	readfile("Ficha Trabajo Infantil Seguimiento.docx");
	}else if ($id==14) {
	header("Content-disposition: attachment; filename=Ley General de Transparencia.pdf");
	header("Content-type: application/pdf");
	readfile("Ley General de Transparencia.pdf");
	}else if ($id==15) {
	header("Content-disposition: attachment; filename=Ley General de Archivos.pdf");
	header("Content-type: application/pdf");
	readfile("Ley General de Archivos.pdf");
	}else if ($id==16) {
	header("Content-disposition: attachment; filename=Ley General de Anticorrupcion.pdf");
	header("Content-type: application/pdf");
	readfile("Ley General de Anticorrupcion.pdf");
	}else if ($id==17) {
	header("Content-disposition: attachment; filename=Ley General de Responsabilidades Administrativas.pdf");
	header("Content-type: application/pdf");
	readfile("Ley General de Responsabilidades Administrativas.pdf");
	} else if ($id==18) {
	header("Content-disposition: attachment; filename=FORMATO BANAVIM.xlsx");
	header("Content-type: application/vnd.ms-excel");
	readfile("FORMATO BANAVIM.xlsx");
	} else if ($id==19) {
	header("Content-disposition: attachment; filename=Formato de  Deteccion de Casos.docx");
	header("Content-type: application/msword");
	readfile("Formato de  Deteccion de Casos.docx");
	} else if ($id==21) {
	header("Content-disposition: attachment; filename=Formato de registro del acercamiento con la familia.docx");
	header("Content-type: application/msword");
	readfile("Formato de registro del acercamiento con la familia.docx");
	} else if ($id==20) {
	header("Content-disposition: attachment; filename=Registro de la información del acercamiento con la NNA.docx");
	header("Content-type: application/msword");
	readfile("Registro de la información del acercamiento con la NNA.docx");
	} else if ($id==22) {
	header("Content-disposition: attachment; filename=FICHA VAR con variables HGO.docx");
	header("Content-type: application/msword");
	readfile("FICHA VAR con variables HGO.docx");
	}




?>
