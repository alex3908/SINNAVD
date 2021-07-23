<?php
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$res=$_GET['ddlViolencia'];

	if($res=='1'){
		print "<option value=''>SELECCIONE</option>";
		print "<option value='PSICOLOGICA'>PSICOLÓGICA</option>";
		print "<option value='FISICA'>FÍSICA</option>";
		print "<option value='PATRIMONIAL'>PATRIMONIAL</option>";
		print "<option value='ECONOMICA'>ECONÓMICA</option>";
		print "<option value='SEXUAL'>SEXUAL</option>";
	} else print "<option value='NO APLICA'>NO APLICA</option>";
?> 