<?php 
	
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://name-pruebas.inftelapps.com/api/login",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
 CURLOPT_POSTFIELDS =>"{\r\n    \"username\": \"dianamf\", \r\n    \"password\": \"sCMvwRpC\"\r\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json"
  ),
));

$response = curl_exec($curl);
var_dump($response);

curl_close($curl);
echo $response;
echo "jofj";
?>