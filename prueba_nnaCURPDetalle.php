<?php 
require_once 'lib/nusoap.php';
session_start();
$client = new soapclient("https://webs.curp.gob.mx/WebServicesConsulta/services/ConsultaPorCurpService?wsdl");
/*$usuario='WS637250188';
$direccionIP='187.188.236.198';
$cveEntidadEmisora= '1';
$pass='RA893aj7';
$TransaccionPorCurp='5';
$curp='MOFD950315MHGRRN03'; */
//https://webs.curp.gob.mx:-1/WebServicesConsulta/services/ConsultaPorCurpService.ConsultaPorCurpServiceHttpSoap11Endpoint/
require 'conexion.php';
	//require_once(‘vendor/econea/nusoap/src/nusoap.php’);
	$parameters = array('usuario' => 'WS637250188', 'direccion' => '187.188.236.198','cveEntidadEmisora'=> '1', 'password'=>'RA893aj7', 'transaccion'=> '5', 'curp'=>'MOFD950315MHGRRN03');
	
	
	var_dump($client->__getFunctions());

	$result = $clientcall('consultarPorCurp', $parameters);
	echo $result;

	//var_dump($result);
?>
