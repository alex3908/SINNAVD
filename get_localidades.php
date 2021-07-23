<?php
require 'conexion.php';

$query="SELECT * from localidades where id_mun=$_GET[country_id] and id!='0' order by clave asc";
$equery=$mysqli->query($query);
$states = array();
while($row=$equery->fetch_object()){ $states[]=$row; }
if(count($states)>0){
print "<option value=''>-- LOCALIDAD --</option>";
foreach ($states as $s) {
	print "<option value='$s->id'>$s->clave.- $s->localidad</option>";
}
}else{
print "<option value=''>-- NO HAY DATOS --</option>";
}
?>