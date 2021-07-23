<?php
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$res=$_GET['ddlMig'];
	if($res=='1'){
		print "<option value=''>SELECCIONE</option>";
		print "<option value='NACIONAL'>NACIONAL</option>";
		print "<option value='INTERNACIONAL'>INTERNACIONAL</option>";}
		else print "<option value='NO APLICA'>NO APLICA</option>";

?>