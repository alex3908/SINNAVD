<?php 
	function validar_fecha($fecha){
	$valores = null;
	$valores = explode('/', $fecha);
	if(!empty($valores)){
		if(count($valores) == 3 && checkdate($valores[1], $valores[0], $valores[2])) {
			$anio = $valores[2];
			if (strlen($anio)==2 or strlen($anio)==4 ) { 
				$fecha2 = $valores[2]."-".$valores[1]."-".$valores[0];
				$fecha_bien = date('Y-m-d',strtotime($fecha));
				return $fecha_bien;
			}
			else
				return "0";
	    }
		return "0";
	} else return "0";
}


?>