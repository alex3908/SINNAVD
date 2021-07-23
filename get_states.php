<?php
require 'conexion.php';
$query="SELECT * from municipios where  id_estado=$_GET[country_id] and id!='0'";
$equery=$mysqli->query($query);
$states = array();
while($r=$equery->fetch_object()){ $states[]=$r; }
if(count($states)>0){
print "<option value=''>-- SELECCIONE --</option>";
foreach ($states as $s) {
	print "<option value='$s->id'>$s->municipio</option>";
}
}else{
print "<option value=''>-- NO HAY DATOS --</option>";
}
?>