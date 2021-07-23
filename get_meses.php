<?php 
	require 'conexion.php';
	$query="SELECT id_corte, mes, idXaño from cortes where año=$_GET[anio] order by idXaño";
	$equery=$mysqli->query($query);
	$meses = array();
	while($row=$equery->fetch_object()){ $meses[]=$row; }
	if(count($meses)>0){
		print "<option value=''>-- Mes --</option>";
		foreach ($meses as $s) {
			print "<option value='$s->id_corte'>$s->mes</option>";
		}
	} else {
		print "<option value=''>-- NO HAY DATOS --</option>";
	}
?>