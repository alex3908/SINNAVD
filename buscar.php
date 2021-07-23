<?php 
	session_start();
	require 'conexion.php';

	$salida "";
	$query="SELECT id, folio, nombre, apellido_p, apellido_m, 
			concat(nombre,' ',apellido_p,' ',apellido_m) as nom, fecha_nac, 
			sexo, curp from nna
			order by fecha_reg desc limit 20";
	if(isset($_POST['consulta'])){
		$q =$mysqli->cubrid_real_escape_string($_POST['consulta']);
		$query="SELECT id, folio, nombre, apellido_p, apellido_m, 
			concat(nombre,' ',apellido_p,' ',apellido_m) as nom, fecha_nac, 
			sexo, curp from nna where folio like '%".$q."%' or nombre like '%".$q."%' or apellido_p like '%".$q."%'
			or apellido_m like '%".$q."%'
			order by fecha_reg desc limit 20";

	}
	$resultado=$mysqli->query($query);

	if($resultado->num_rows > 0){
		$salida.="<table class='tabla_datos'>
			<thead>
				<tr>
					<td>Folio</td>
					<td>Nombre</td>
					<td>Apellido paterno</td>
					<td>Apellido materno</td>
				</tr>
			</thead>
			<tbody>";
		while ($fila=$resultado->fetch_assoc()) {
			$salida.=
				"<tr>
					<td>".$fila['folio']."</td>
					<td>".$fila['nombre']."</td>
					<td>".$fila['apellido_p']."</td>
					<td>".$fila['apellido_m']."</td>
				</tr>"
		}

		$salida.="</tbody></table>"
	} else {
		$salida.="No hay datos :(";
	}
	echo $salida;
?>