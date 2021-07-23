<?php
session_start();
require 'conexion.php';

if(!isset($_SESSION["id"])){
  header("Location: welcome.php");
}
$idDEPTO = $_SESSION['id'];
require 'vendor/autoload.php';
use GuzzleHttp\Client;
$client = new Client([
    'base_uri' => 'https://name-pruebas.inftelapps.com/api/login',
    'timeout'  => 5.0,
]);


// =============================================
//Hago la llamada al servicio rest, para insertar un articulo
$usuario = ['username'=>'dianamf',
             'password'=>'sCMvwRpC'
            ];
$res = $client->request('POST', '', ['form_params' => $usuario]);
if ($res->getStatusCode() == '200') //Verifico que me retorne 200 = OK
{
  echo $res->getBody();
}
  /*$urlReportes ='https://name-pruebas.inftelapps.com/api/reportes';
  $jsonReportes = file_get_contents($urlReportes); 
	
  $arrReportes = json_decode($jsonReportes);
  $numReportes= count($arrReportes); */

?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Lista NAMES</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>		
	</head>
	<body>		
		<div id="wrapper">
			<div id="main">
				<div class="inner">
				    <br> <br> 
					<div class="box alt" align="center">
					    <div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
                    </div>
					<h2>Reportes de NAMES</h2>
					<div class="box alt" align="center">
						<table>
							<thead>
								<tr>
									<td>ID</td>
									<td>Nombre del caso</td>
									<td>Número de control</td>
									<td>Número de reporte</td>
									<td>Estatus</td>
								</tr>
							</thead>
							<body>
								<?php for($i=0; $i<$numReportes;$i++) { ?>
									<tr>
										<td><?php  print_r($arrReportes[$i]->id); ?> </td>
										<td><?php  print_r($arrReportes[$i]->nombreCaso); ?> </td>
										<td><a href="name_perfil_reporte.php?num=<?php echo $i; ?>" ><?php  print_r($arrReportes[$i]->numeroControl); ?></a></td>  <!--cambiar siguientes tres campos al los campos del ws real-->
										<td><?php  print_r($arrReportes[$i]->numeroReporte); ?> </td>
										<td><?php  print_r($arrReportes[$i]->estado); ?> </td>
										<td><?php 
											$idReporteName=($arrReportes[$i]->id);
											$qExisteReg="SELECT id from relacion_names where id_name='$idReporteName'";
											$rExisteReg=$mysqli->query($qExisteReg);
											$ExisteReg=$rExisteReg->num_rows; 
											if ($ExisteReg>0) 
												Echo "Ya registrado";											
										?></td>
									</tr>
								<?php } ?>
							</body>	
						</table>
					</div>
                </div>
			</div>
		</div>
		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>
