<?php

	$cliente= new SoapClient('http://172.16.1.42/sistemas/wssieb/WebServiceBeneficiarios.asmx?wsdl');
	$param=array('CURP'=>'EIRE101227HNESMDA0');
  var_dump($param);
  $response= $cliente->ConsultaPorCurp($param);

	var_dump($response);
  var_export($response);
  
  $cl= new SoapClient('http://172.16.1.42/sistemas/wssieb/WebServiceBeneficiarios.asmx?wsdl');
 /* $xmlr= new SimpleXMLElement("<Persona></Persona>");
 
  $xmlr->addChild('CURP',' ');
  $xmlr->addChild('Nombres', 'SAUL RODRIGO');
  $xmlr->addChild('Apellido1', 'GONZALEZ');
  $xmlr->addChild('Apellido2', 'ANGELES');
  $xmlr->addChild('Mensaje', ' ');
  $xmlr->addChild('Sexo', 'H');
  $xmlr->addChild('FechNac','1998-05-30');
  $xmlr->addChild('Nacionalidad', ' ');
  $xmlr->addChild('EntidadFederativa', 'HG');
  $xmlr->addChild('StatusCURP', ' ');
  $xmlr->addChild('StatusOper', ' ');
  $xmlr->addChild('DocProbatorio', ' ');
  $xmlr->addChild('AnioReg',' ');
  $xmlr->addChild('Foja',' ');
  $xmlr->addChild('Tomo',' ');
  $xmlr->addChild('Libro',' ');
  $xmlr->addChild('NumActa',' ');
  $xmlr->addChild('CRIP',' ');
  $xmlr->addChild('EntidadRegistro',' ');
  $xmlr->addChild('MunicipioRegistro',' ');
  $xmlr->addChild('NumRegExtranjeros',' ');
  $xmlr->addChild('FolioCarta',' ');*/
  $param=array('Nombres'=>'SAUL RODRIGO','Apellido1'=>'GONZALES','Apellido2'=>'ANGELES','FechNac'=>'1998-05-30','Sexo'=>'H','EntidadRegistro'=>'HG');
  var_dump($param);
  $par= new stdClass();
  $par->Persona=$param;
  $resultado= $cl->ConsultaPorDatos($par);

  
  var_dump($par);
  var_dump($resultado);
/*
	$array = json_decode(json_encode($response), True);
	//var_dump($array['ConsultaPorCurpResult']['Nombres']);
	print $array['ConsultaPorCurpResult']['Nombres'];
	print $array['ConsultaPorCurpResult']['StatusOper'];

$equipo_futbol = array
(
array("Rooney","Chicharito","Gigs"),
array("Suarez"),
array("Torres","Terry","Etoo")
);
var_dump ($equipo_futbol);


class User
{
  public $name = 'John';
  public $age = 34;
  private $salary = 4200.00;
  protected $identifier = 'ABC';

  public static function __set_state($properties)
  {
    $user = new User();

    $user->name = $properties['name'];
    $user->age = $properties['age'];
    $user->salary = $properties['salary'];
    $user->identifier = $properties['identifier'];

    return $user;
  }
}

$user = new User();
$user->name = 'Mariya';
$user->age = 32;

eval('$obj = ' . var_export($user, true) . ';');
 var_export($user);
var_dump($obj);
echo $obj->name;*/

$prue=false;
if(!$prue)
var_dump($prue);
?>