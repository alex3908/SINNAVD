<?php 
session_start();
//WS637250188
//187.188.236.198

//RP_cveEntidadEmisora" value="1"
//RP_tipoTransaccionPorCurp" value="5"
//RP_tipoTransaccionPorDatos" value="6"
//RP_tipoTransaccionSpecified" value="True"	
//https://webs.curp.gob.mx:-1/WebServicesConsulta/services/ConsultaPorCurpService.ConsultaPorCurpServiceHttpSoap11Endpoint/
require 'conexion.php';
	//require_once(‘vendor/econea/nusoap/src/nusoap.php’);
	$parameters=array('cveCurp'=>'FONI950306HHGLVV09', 'cveEntidadEmisora'=>'1','direccionIp'=>'187.188.236.198','password'=>'RA893aj7','tipoTransaccion'=>'5','usuario'=>'WS637250188');
	$client = new soapclient("https://webs.curp.gob.mx/WebServicesConsulta/services/ConsultaPorCurpService?wsdl");
	$client->__setLocation('https://webs.curp.gob.mx/WebServicesConsulta/services/ConsultaPorCurpService');
		
	var_dump($client->__getFunctions());

	$result = $client->getConfirm('consultarPorCurpResponse', array('consultarPorCurp' => $parameters));

	var_dump($result);
?>
