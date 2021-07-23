<?php
require 'conexion.php';

$query="SELECT * from departamentos where id_depto=$_GET[sp]";
$equery=$mysqli->query($query);
$states = array();
while($row=$equery->fetch_object()){ $states[]=$row; }
if(count($states)>0){
print "<option value='Todos'>-- PERSONAL --</option>";
foreach ($states as $s) {
	print "<option value='$s->id'>$s->responsable</option>";
}
}else{
print "<option value=''>-- NO HAY DATOS --</option>";
}
?>