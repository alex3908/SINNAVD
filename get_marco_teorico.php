<?php
require 'conexion.php';
$query="SELECT * from cat_marco_juridico where  id_derecho=$_GET[derecho] order by id";
$equery=$mysqli->query($query);
$marcos = array();
while($r=$equery->fetch_object()){ $marcos[]=$r; }
if(count($marcos)>0){
print "<option value=''>-- SELECCIONE --</option>";
foreach ($marcos as $s) {
	print "<option value='$s->marco_juridico'>$s->marco_juridico</option>";
}
}else{
print "<option value=''>-- NO HAY DATOS --</option>";
}
?>