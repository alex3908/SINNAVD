<?php 
	$urlReportes ='http://localhost/procu/prueba_ws.php';
  $jsonReportes = file_get_contents($urlReportes); 
  $arrReportes = json_decode($jsonReportes);
   echo $arrReportes;

?>