<?php 
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$estado_civil1=$_GET['estado_c'];
	if($estado_civil1=="Casado(a)" or $estado_civil1=="Unión libre"){
		$qDatosUsuario2="SELECT id_usuario, nombre, apellido_p, apellido_m FROM historial inner join usuarios u on historial.id_usuario=u.id inner join departamentos d on historial.responsable=d.id where historial.responsable=$idDEPTO and fecha_salida is null";
		$DatosUsuario2= $mysqli->query($qDatosUsuario2); 
		$usuarios = array();
		while($r=$DatosUsuario2->fetch_object()){ $usuarios[]=$r; }
		if(count($usuarios)>0){
			print "<option value=''>-- SELECCIONE --</option>";
			foreach ($usuarios as $u) {
				print "<option value='$u->id_usuario'>$u->nombre.' '.$u->apellido_p.' '.$u->apellido_m</option>";
			}
		} else {
			print "<option value=''>-- No hay mas usuarios --</option>";
		}
	} else print "<option value='NO APLICA'>NO APLICA</option>";
?>