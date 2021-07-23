<?php
	ob_start();
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	
	$idDEPTO = $_SESSION['id'];	
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Lista</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner"><br> <br> 
			<div class="box alt" align="center">
				<div class="row 10% uniform">
					<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
				</div>
			</div> 
		
			<table class="alt">
				<thead>
					<tr>
						<td><h1>Documentos para descarga</h1></td>						
					</tr>
				</thead>
			</table>
			
			<table class="alt">	
				<thead>	
					<tr>
						<td><b></b></td>				
						<td><b>Nombre</b></td>
						<td><b></b></td>
					</tr>
				</thead>	
				<tbody>				
					<tr>
						<td>1</td>
						<td>ACTA CONVENIO</td>
						<td><a href="descargas.php?id=1">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>2</td>
						<td>ACTA ENTREGA</td>
						<td><a href="descargas.php?id=2">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>3</td>
						<td>ANTECEDENTES PERINATALES</td>
						<td><a href="descargas.php?id=3">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>4</td>
						<td>ATENCION MIGRANTES</td>
						<td><a href="descargas.php?id=4">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>5</td>
						<td>KIT GUARDIA</td>
						<td><a href="descargas.php?id=5">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>6</td>
						<td>FORMATO INICIO DE CARPETA</td>
						<td><a href="descargas.php?id=6">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>7</td>
						<td>OFICIO INGRESO NNA MIGRANTE</td>
						<td><a href="descargas.php?id=7">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>8</td>
						<td>OFICIO MIGRACION, NNA ACOMPAÑADO</td>
						<td><a href="descargas.php?id=8">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>9</td>
						<td>OFICIO MIGRACION, NNA SOLOS QUE INGRESAN</td>
						<td><a href="descargas.php?id=9">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>10</td>
						<td>OFICIO PPNNAyF (vacaciones)</td>
						<td><a href="descargas.php?id=10">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>11</td>
						<td>PROTOCOLO DE TRABAJO INFANTIL</td>
						<td><a href="descargas.php?id=11">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>12</td>
						<td>FICHA INFORMATIVA, TRABAJO INFANTIL</td>
						<td><a href="descargas.php?id=12">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>13</td>
						<td>FICHA SEGUIMIENTO, TRABAJO INFANTIL</td>
						<td><a href="descargas.php?id=13">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>14</td>
						<td>LEY GENERAL DE TRANSPARENCIA</td>
						<td><a href="descargas.php?id=14">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>15</td>
						<td>LEY GENERAL DE ARCHIVOS</td>
						<td><a href="descargas.php?id=15">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>16</td>
						<td>LEY GENERAL DE ANTICORRUPCION</td>
						<td><a href="descargas.php?id=16">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>17</td>
						<td>LEY GENERAL DE RESPONSABILIDADES ADMINISTRATIVAS</td>
						<td><a href="descargas.php?id=17">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>18</td>
						<td>FORMATO BANAVIM</td>
						<td><a href="descargas.php?id=18">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>19</td>
						<td>FORMATO DE DETECCIÓN DE CASOS</td>
						<td><a href="descargas.php?id=19">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>20 </td>
						<td>REGISTRO DE LA INFORMACIÓN DEL ACERCAMIENTO CON LA NIÑA, NIÑO O ADOLESCENTE PARA CONOCER LA SITUACIÓN DE SUS DERECHOS</td>
						<td><a href="descargas.php?id=20">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>21</td>
						<td>FORMATO DE REGISTRO DE ACERCAMIENTO CON LA FAMILIA PARA OBTENER INFORMACIÓN SOBRE LA SITUACIÓN DE DERECHOS DE NNyA</td>
						<td><a href="descargas.php?id=21">descarga aqui...</a></td>
					</tr>
					<tr>
						<td>22</td>
						<td>FICHA VAR</td>
						<td><a href="descargas.php?id=22">descarga aqui...</a></td>
					</tr>
				</tbody>
			</table>	




			<?php if(@$bandera) { 
			header("Location: welcome.php");

			?>	<?php }else{ ?>
			
			<div style = "font-size:16px; color:#cc0000;"><?php echo isset($error) ? utf8_decode($error) : '' ; ?></div>
			
		<?php } ?>

							</div>
							</div>
				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							<nav id="menu">
								<header class="major">
									<h2>Menú</h2>
								</header>
									<ul><li><a href="welcome.php">Inicio</a></li>				
										<li><a href="logout.php" >Cerrar sesión</a></li>
									</ul>
							</nav>		
							<section>
								<header class="major">
									<h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
								</header>
								<p></p>
									<ul class="contact">
										<li class="fa-envelope-o"><a href="#">laura.ramirez@hidalgo.gob.mx</a></li>
										<li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
										<li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
										<li class="fa-home">Plaza Juarez #118<br />
										Col. Centro <br> Pachuca Hidalgo</li>
									</ul>
							</section>
							<!-- Footer -->
								<footer id="footer">
									<p class="copyright">&copy; Sistema DIF Hidalgo </p>
								</footer>

						</div>
					</div>
					<!--cierre menu-->

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>