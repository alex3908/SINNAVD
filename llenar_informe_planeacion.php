<?php	
	session_start();
	require 'conexion.php';
	date_default_timezone_set('America/Mexico_City');
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idDEPTO = $_SESSION['id'];	
	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
	}
	if($idDepartamento!=16 or $idPersonal!=1){
		header("Location: informe_planeacion.php");
	}
	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo=$mysqli->query($reportesvd);
	$wow=$erepo->num_rows;
$anio =null;
$mes = null;
$Consulta = false;
if(!empty($_POST['Consulta'])){ 
	$anio = $_POST['anio'];
	$id_fecha = $_POST['meses'];
	$anioq = "SELECT distinct año from cortes where año!=2019 and año!=2020 and año!=$anio";
	$anioq = $mysqli->query($anioq);
	$mes = "SELECT mes from cortes where id_corte=$id_fecha";
	$mes = $mysqli->query($mes); 
	$mes = implode($mes->fetch_assoc());
	$mesesq = "SELECT id_corte, mes from cortes where año='$anio' and id_corte!=$id_fecha";
	$mesesq = $mysqli->query($mesesq);
	$Consulta = true;
	$datos = "SELECT * FROM datos_reportados where id_fecha_corte =$id_fecha";
	$datos =  $mysqli->query($datos);
	$registrado = $datos->num_rows;
	$idDatos = null;
	$p1_1= 0;
	$p1_2= 0;
	$a3= 0;
	$a7_1= 0;
	$a7_2= 0;
	$a8= 0;
	$a9= 0;
	$a10_1= 0;
	$a10_2= 0;
	$a11_1= 0;
	$a11_2= 0;
	$a12_1= 0;
	$a12_2= 0;
	$a13= 0;
	$a14_1= 0;
	$a14_2= 0;
	$a15= 0;
	$a16_1= 0;
	$a16_2= 0;
	$a17_1= 0;
	$a17_2= 0;
	$a18_1= 0;
	$a18_2= 0;
	$a19_1= 0;
	$a19_2= 0;
	$a20= 0;
	$a21= 0;
	$a22_1= 0;
	$a22_2= 0;
	$a23_1= 0;
	$a23_2= 0;
	$a24_1= 0;
	$a24_2= 0;
	$a28_1= 0;
	$a28_2= 0;
	if($registrado>0){
		while ($row=$datos->fetch_assoc()) {
			$idDatos = $row['id'];
			$p1_1=$row['p1_1'];
			$p1_2=$row['p1_2'];
			$a3=$row['a3'];
			$a7_1=$row['a7_1'];
			$a7_2=$row['a7_2'];
			$a8=$row['a8'];
			$a9=$row['a9'];
			$a10_1=$row['a10_1'];
			$a10_2=$row['a10_2'];
			$a11_1=$row['a11_1'];
			$a11_2=$row['a11_2'];
			$a12_1=$row['a12_1'];
			$a12_2=$row['a12_2'];
			$a13=$row['a13'];
			$a14_1=$row['a14_1'];
			$a14_2=$row['a14_2'];
			$a15=$row['a15'];
			$a16_1=$row['a16_1'];
			$a16_2=$row['a16_2'];
			$a17_1=$row['a17_1'];
			$a17_2=$row['a17_2'];
			$a18_1=$row['a18_1'];
			$a18_2=$row['a18_2'];
			$a19_1=$row['a19_1'];
			$a19_2=$row['a19_2'];
			$a20=$row['a20'];
			$a21=$row['a21'];
			$a22_1=$row['a22_1'];
			$a22_2=$row['a22_2'];
			$a23_1=$row['a23_1'];
			$a23_2=$row['a23_2'];
			$a24_1=$row['a24_1'];
			$a24_2=$row['a24_2'];
			$a28_1=$row['a28_1'];
			$a28_2=$row['a28_2'];
		}
	}
}

if(!empty($_POST['Guardar'])){
	$fecha_reg =  date("Y-m-d H:i:s", time());
	$registrado = $_POST['registrado'];
	$anio = $_POST['anio'];
	$id_fecha = $_POST['meses'];
	$anioq = "SELECT distinct año from cortes where año!=2019 and año!=2020 and año!=$anio";
	$anioq = $mysqli->query($anioq);
	$mes = "SELECT mes from cortes where id_corte=$id_fecha";
	$mes = $mysqli->query($mes); 
	$mes = implode($mes->fetch_assoc());
	$mesesq = "SELECT id_corte, mes from cortes where año='$anio' and id_corte!=$id_fecha";
	$mesesq = $mysqli->query($mesesq);
	$Consulta = true;
	$idDatos = $_POST['idDatos'];
	$p1_1= mysqli_real_escape_string($mysqli, $_POST['num1']);
	$p1_2= mysqli_real_escape_string($mysqli, $_POST['num2']);
	$a3= mysqli_real_escape_string($mysqli, $_POST['num3']);
	$a7_1= mysqli_real_escape_string($mysqli, $_POST['num4']);
	$a7_2= mysqli_real_escape_string($mysqli, $_POST['num5']);
	$a8= mysqli_real_escape_string($mysqli, $_POST['num6']);
	$a9= mysqli_real_escape_string($mysqli, $_POST['num7']);
	$a10_1= mysqli_real_escape_string($mysqli, $_POST['num8']);
	$a10_2= mysqli_real_escape_string($mysqli, $_POST['num9']);
	$a11_1= mysqli_real_escape_string($mysqli, $_POST['num10']);
	$a11_2= mysqli_real_escape_string($mysqli, $_POST['num11']);
	$a12_1= mysqli_real_escape_string($mysqli, $_POST['num12']);
	$a12_2= mysqli_real_escape_string($mysqli, $_POST['num13']);
	$a13= mysqli_real_escape_string($mysqli, $_POST['num14']);
	$a14_1= mysqli_real_escape_string($mysqli, $_POST['num15']);
	$a14_2= mysqli_real_escape_string($mysqli, $_POST['num16']);
	$a15= mysqli_real_escape_string($mysqli, $_POST['num17']);
	$a16_1= mysqli_real_escape_string($mysqli, $_POST['num18']);
	$a16_2= mysqli_real_escape_string($mysqli, $_POST['num19']);
	$a17_1= mysqli_real_escape_string($mysqli, $_POST['num20']);
	$a17_2= mysqli_real_escape_string($mysqli, $_POST['num21']);
	$a18_1= mysqli_real_escape_string($mysqli, $_POST['num22']);
	$a18_2= mysqli_real_escape_string($mysqli, $_POST['num23']);
	$a19_1= mysqli_real_escape_string($mysqli, $_POST['num24']);
	$a19_2= mysqli_real_escape_string($mysqli, $_POST['num25']);
	$a20= mysqli_real_escape_string($mysqli, $_POST['num26']);
	$a21= mysqli_real_escape_string($mysqli, $_POST['num27']);
	$a22_1= mysqli_real_escape_string($mysqli, $_POST['num28']);
	$a22_2= mysqli_real_escape_string($mysqli, $_POST['num29']);
	$a23_1= mysqli_real_escape_string($mysqli, $_POST['num30']);
	$a23_2= mysqli_real_escape_string($mysqli, $_POST['num31']);
	$a24_1= mysqli_real_escape_string($mysqli, $_POST['num32']);
	$a24_2= mysqli_real_escape_string($mysqli, $_POST['num33']);
	$a28_1= mysqli_real_escape_string($mysqli, $_POST['num34']);
	$a28_2= mysqli_real_escape_string($mysqli, $_POST['num35']);
	if($registrado==0){
		$query = "INSERT INTO datos_reportados (id_fecha_corte, p1_1, p1_2, a3, a7_1, a7_2, a8, a9, a10_1, a10_2, a11_1, a11_2, a12_1, a12_2, a13, a14_1, a14_2, a15, a16_1, a16_2, a17_1, a17_2, a18_1, a18_2, a19_1, a19_2, a20, a21, a22_1, a22_2, a23_1, a23_2, a24_1, a24_2, a28_1, a28_2, respo_reg, feca_reg) VALUES ($id_fecha, $p1_1, $p1_2, $a3, $a7_1, $a7_2, $a8, $a9, $a10_1, $a10_2, $a11_1, $a11_2, $a12_1, $a12_2, $a13, $a14_1, $a14_2, $a15, $a16_1, $a16_2, $a17_1, $a17_2, $a18_1, $a18_2, $a19_1, $a19_2, $a20, $a21, $a22_1, $a22_2, $a23_1, $a23_2, $a24_1, $a24_2, $a28_1, $a28_2, $idDEPTO, '$fecha_reg')";
	} else {
		$query= "UPDATE datos_reportados SET id_fecha_corte = $id_fecha, p1_1 = $p1_1, p1_2 = $p1_2, a3 = $a3, a7_1 = $a7_1, a7_2 = $a7_2, a8 = $a8, a9 = $a9, a10_1 = $a10_1, a10_2 = $a10_2, a11_1 = $a11_1, a11_2 = $a11_2, a12_1 = $a12_1, a12_2 = $a12_2, a13 = $a13, a14_1 = $a14_1, a14_2 = $a14_2, a15 = $a15, a16_1 = $a16_1, a16_2 = $a16_2, a17_1 = $a17_1, a17_2 = $a17_2, a18_1 = $a18_1, a18_2 = $a18_2, a19_1 = $a19_1, a19_2 = $a19_2, a20 = $a20, a21 = $a21, a22_1 = $a22_1, a22_2 = $a22_2, a23_1 = $a23_1, a23_2 = $a23_2, a24_1 = $a24_1, a24_2 = $a24_2, a28_1 = $a28_1, a28_2 = $a28_2, respo_reg = $idDEPTO, feca_reg = '$fecha_reg' WHERE id = $idDatos";
	}

	$qquery = $mysqli->query($query);
	if($qquery){
		echo "<script>
				alert('Se ha actualizado la información correctamente');
				window.location= 'informe_planeacion.php'
				</script>";
	} else echo $query;
}
	

?>
<!DOCTYPE HTML>
<html> 
	<head lang="es-ES">
		<title>Perfil</title>
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
					<div class="box alt" align="center">
						<div class="row 10% uniform">
							<div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
							<div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
							<div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
						</div>
					</div>
					<form id="datos" name="datos" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
						<div class="row uniform">
							<div class="12u">
								<b>Seleccione el año y mes</b>
							</div>
						</div>
						<div class="row uniform">
							<div class="3u">
								<div class="select-wrapper">
									<select id="anio" name="anio" required>
										<?php if(empty($anio)) { ?>
											<option value="">Seleccione</option>
											<option value="2021">2021</option>
										<?php } else { ?>
											<option value="<?=$anio?>"><?=$anio?></option>
											<?php while ($rwa=$anioq->fetch_assoc()) { ?>
												<option value="<?=$rwa['año']?>"><?=$rwa['año']?></option>
											<?php }
										} ?>
									</select>
								</div>
							</div>
							<div class="3u">
								<div class="select-wrapper">
									<select id="meses" name="meses" required>
										<?php if(empty($mes)) { ?>
											<option value="">Seleccione</option>
										<?php } else { ?>
											<option value="<?=$id_fecha?>"><?=$mes?></option>
											<?php while ($rwm=$mesesq->fetch_assoc()) { ?>
												<option value="<?=$rwm['id_corte']?>"><?=$rwm['mes']?></option>
											<?php } 
										} ?>
									</select>
								</div>
							</div>
							<div class="3u">
								<input type="submit" value="Consultar" name="Consulta" class="button special fit">
							</div>
							<div class="3u">
								<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='informe_planeacion.php'" >
							</div>
						</div>
						<?php if($Consulta) { ?>
							<input type="hidden" name="registrado" value="<?=$registrado?>">
							<input type="hidden" name="idDatos" value="<?=$idDatos?>">
							<br><div class="box">
								<div class="row uniform">
									<div class="2u">
										<b>NIVEL DE LA MIR</b>
									</div>
									<div class="4u">
										<b>NOMBRE DEL INDICADOR</b>
									</div>
									<div class="4u">
										<b>UNIDAD DE MEDIDA</b>
									</div>
									<div class="2u">
										<b>TOTAL</b>
									</div>
								</div>
								<hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										PROPOSITO
									</div>
									<div class="4u">
										<br>
										Porcentaje de niñas, niños y adolescentes  que se les restituyen sus derechos respecto  a niñas, niños y adolescentes  con derechos vulnerados atendidos por la Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												No. de  niñas, niños y adolescentes  que se restituyen sus derechos 
											</div>
											<div class="4u">
												<input type="number" name="num1" id="num1" value="<?=$p1_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Niñas, niños y adolescentes con derechos vulnerados atendidos por la Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia
											</div>
											<div class="4u">
												<input type="number" name="num2" id="num2" value="<?=$p1_2?>" required >
											</div>
										</div>
									</div>
								</div>
								<hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 3
									</div>
									<div class="4u">
										Promedio de intervenciones psicológicas a niñas, niños y adolescentes en situacion de vulneracion de derechos brindadas respecto al total de niñas, niños y adolescentes con derechos vulnerados atendidos por primera vez en la Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia
									</div>
									<div class="4u"><br>
										Niñas, niños y adolescentes con derechos vulnerados atendidos por primera vez en  la Procuraduría de Protección de Niñas, Niños, Adolescentes y la Familia
									</div>
									<div class="2u"><br>
										<input type="number" name="num3" id="num3" value="<?=$a3?>" required>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 7
									</div>
									<div class="4u">
										<br>
										Porcentaje de procedimientos familiares tramitados respecto al total de procedimientos familiares requeridos
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Procedimientos familiares tramitados  
											</div>
											<div class="4u">
												<input type="number" name="num4" id="num4" value="<?=$a7_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Procedimientos familiares requeridos
											</div>
											<div class="4u">
												<input type="number" name="num5" id="num5" value="<?=$a7_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 8
									</div>
									<div class="4u">
										Porcentaje de supervisión de Centros de Asistencia Social realizada respecto al total supervisión de Centros de Asistencia Social programada
									</div>
									<div class="4u"><br>
										Supervisión de Centros de Asistencia Social realizada
									</div>
									<div class="2u"><br>
										<input type="number" name="num6" id="num6" value="<?=$a8?>" required>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 9
									</div>
									<div class="4u">
										Porcentaje de autorización de funcionamiento a los Centros de Asistencia Social realizada respecto al total de autorizaciones de funcionamiento a los Centros de Asistencia Social programada
									</div>
									<div class="4u"><br>
										Autorización de funcionamiento a los Centros de Asistencia Social realizada
									</div>
									<div class="2u"><br>
										<input type="number" name="num7" id="num7" value="<?=$a9?>" required>
									</div>
								</div><hr>

								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 10
									</div>
									<div class="4u">
										<br>
										Porcentaje de  ingresos de personas a los centros de asistencia social Privados realizadas respecto al total de ingresos de  personas en los centros de Asistencia Social Privados requeridos
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Ingresos de personas a los centros de asistencia social Privados realizados 
											</div>
											<div class="4u">
												<input type="number" name="num8" id="num8" value="<?=$a10_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Ingresos de personas en los centros de asistencia social  Privados requeridos
											</div>
											<div class="4u">
												<input type="number" name="num9" id="num9" value="<?=$a10_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 11
									</div>
									<div class="4u">
										<br>
										Promedio anual de gasto en cuota economica en los Centros de Asistencia Social Privados pagada respecto a las personas albergadas en los Centros de Asistencia Social Privados
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Cuotas económicas a los Centros de Asistencia Social Privados pagadas
											</div>
											<div class="4u">
												<input type="number" name="num10" id="num10" value="<?=$a11_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Personas albergadas en los Centros de Asistencia Social Privados
											</div>
											<div class="4u">
												<input type="number" name="num11" id="num11" value="<?=$a11_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 12
									</div>
									<div class="4u">
										<br>
										Porcentaje de solicitantes de adopción procedentes respecto al total de solicitantes de adopción iniciales
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Solicitantes de adopción procedentes
											</div>
											<div class="4u">
												<input type="number" name="num12" id="num12" value="<?=$a12_1?>"required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Solicitantes de adopción iniciales
											</div>
											<div class="4u">
												<input type="number" name="num13" id="num13" value="<?=$a12_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 13
									</div>
									<div class="4u">
										Porcentaje de sesiones de psicología realizado respecto al total de  sesiones de psicología programado
									</div>
									<div class="4u"><br>
										Sesiones de psicología realizado
									</div>
									<div class="2u"><br>
										<input type="number" name="num14" id="num14" value="<?=$a13?>" required>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 14
									</div>
									<div class="4u">
										<br>
										Porcentaje de valoraciones psicológicas de solicitantes de adopción idoneas respecto al total  valoraciones psicológicas de adopción aplicadas
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Valoraciones psicológicas de solicitantes de adopción idóneas
											</div>
											<div class="4u">
												<input type="number" name="num15" id="num15" value="<?=$a14_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Valoraciones psicológicas de adopción aplicadas
											</div>
											<div class="4u">
												<input type="number" name="num16" id="num16" value="<?=$a14_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 15
									</div>
									<div class="4u">
										Porcentaje de intervenciones de trabajo social realizadas respecto al total de intervenciones de trabajo social programadas
									</div>
									<div class="4u"><br>
										Intervenciones de trabajo social realizadas
									</div>
									<div class="2u"><br>
										<input type="number" name="num17" id="num17" value="<?=$a15?>" required>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 16
									</div>
									<div class="4u">
										<br>
										Porcentaje de estudios socioeconómicos a solicitantes de adopción idoneo respecto al total de estudios socioeconómicos de adopción aplicados
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Estudios socioeconómico a solicitantes de adopción idoneo
											</div>
											<div class="4u">
												<input type="number" name="num18" id="num18" value="<?=$a16_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Estudios socioeconómicos de adopción aplicados
											</div>
											<div class="4u">
												<input type="number" name="num19" id="num19" value="<?=$a16_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 17
									</div>
									<div class="4u">
										<br>
										Porcentaje de informes de  solicitantes de adopcion idoneos  respecto al total de informes de solicitantes de adopcion emitidos
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Informes de  solicitantes de adopción idóneos
											</div>
											<div class="4u">
												<input type="number" name="num20" id="num20" value="<?=$a17_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Informes de solicitantes de adopción emitidos
											</div>
											<div class="4u">
												<input type="number" name="num21" id="num21" value="<?=$a17_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 18
									</div>
									<div class="4u">
										<br>
										Costo promedio del taller para futuros padres y madres adoptivos realizado respecto al total de  asistentes al curso taller para futuros padres y madres adoptivos 
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Costo total del taller  para futuros padres y madres adoptivos realizado
											</div>
											<div class="4u"> 
												<input type="number" name="num22" id="num22" value="<?=$a18_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Asistentes al curso taller para futuros padres y madres adoptivos 
											</div>
											<div class="4u">
												<input type="number" name="num23" id="num23" value="<?=$a18_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 19
									</div>
									<div class="4u">
										<br>
										Porcentaje de niñas, niños, adolescentes integrados respecto al total de niñas, niños y adolescentes suceptibles de adopción
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Niñas, niños, adolescentes adoptados 
											</div>
											<div class="4u">
												<input type="number" name="num24" id="num24" value="<?=$a19_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Niñas, niños y adolescentes suceptibles de adopción
											</div>
											<div class="4u">
												<input type="number" name="num25" id="num25" value="<?=$a19_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 20
									</div>
									<div class="4u">
										Porcentaje de entrevistas de Trabajo Social post adoptivo realizadas, respecto al total de entrevistas de Trabajo Social post adoptivo programadas
									</div>
									<div class="4u"><br>
										Entrevistas de Trabajo Social post adoptivo realizadas
									</div>
									<div class="2u"><br>
										<input type="number" name="num26" id="num26" value="<?= $a20 ?>" required>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 21
									</div>
									<div class="4u">
										Porcentaje de visitas domiciliarias  de trabajo social post adoptivo realizadas respecto al total de visitas domiciliarias de seguimientos de trabajo social postadoptivo programadas
									</div>
									<div class="4u"><br>
										Visitas domiciliarias de trabajo social postadoptivo realizadas
									</div>
									<div class="2u"><br>
										<input type="number" name="num27" id="num27" value="<?=$a21?>" required>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 22
									</div>
									<div class="4u">
										<br>
										Promedio de acciones para certificar familias de acogidas realizadas respecto al total de solicitantes para acogimiento familiar iniciales
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Acciones para certificar familias de acogidas realizadas
											</div>
											<div class="4u">
												<input type="number" name="num28" id="num28" value="<?=$a22_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Solicitantes para acogimiento familiar iniciales
											</div>
											<div class="4u">
												<input type="number" name="num29" id="num29" value="<?=$a22_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 23
									</div>
									<div class="4u">
										<br>
										Promedio de niñas, niños y adolescentes en acogimiento familiar integrados respecto al total de familias de acogida certificadas
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Niñas, niños y adolescentes en acogimiento familiar integrados
											</div>
											<div class="4u">
												<input type="number" name="num30" id="num30" value="<?=$a23_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Familias de acogida certificadas
											</div>
											<div class="4u">
												<input type="number" name="num31" id="num31" value="<?=$a23_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 24
									</div>
									<div class="4u">
										<br>
										Promedio de visitas de supervisión de niñas, niños y adolescentes en acogimiento familiar realizadas respecto al total de niñas, niños y adolescentes en acogimiento familiar integrados
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Visitas de supervisión de niñas, niños y adolescentes en acogimiento familiar realizadas
											</div>
											<div class="4u">
												<input type="number" name="num32" id="num32" value="<?=$a24_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Niñas, niños y adolescentes en acogimiento familiar integrados
											</div>
											<div class="4u">
												<input type="number" name="num33" id="num33" value="<?=$a24_2?>" required>
											</div>
										</div>
									</div>
								</div><hr>
								
								<div class="row uniform">
									<div class="2u">
										<br><br>
										ACTIVIDAD 28
									</div>
									<div class="4u">
										<br>
										Porcentaje de apoyo en especie a niñas, niños y adolescentes en situación de vulneración de derechos representados por la Procuraduría Protección de Niñas, Niños, Adolescentes y la Familia otorgado respecto al total de apoyos en especie a niñas, niños y adolescentes en situación de vulneración de derechos representados por la Procuraduría Protección de Niñas, Niños, Adolescentes y la Familia solicitado
									</div>
									<div class="6u">
										<div class="row uniform">
											<div class="8u">
												Apoyo en especie a niñas, niños y adolescentes en situación de vulneración de derechos representados por la Procuraduría Protección de Niñas, Niños, Adolescentes y la Familia otorgado
											</div>
											<div class="4u">
												<input type="number" name="num34" id="num34" value="<?=$a28_1?>" required>
											</div>
										</div><hr>
										<div class="row uniform">
											<div class="8u">
												Apoyos en especie a niñas, niños y adolescentes en situación de vulneración de derechos representados por la Procuraduría Protección de Niñas, Niños, Adolescentes y la Familia solicitado
											</div>
											<div class="4u">
												<input type="number" name="num35" id="num35" value="<?=$a28_2?>" required>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row uniform">
								<div class="6u">
									<input type="submit" value="Guardar" name="Guardar" class="button special fit">
								</div>
								<div class="6u">
									<input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='informe_planeacion.php'" >
								</div>
							</div>
						<?php } ?>
					</form>
					<script type="text/javascript">
						$(document).ready(function(){
							$("#anio").change(function(){
								$.get("get_meses.php","anio="+$("#anio").val(), function(data){
									$("#meses").html(data);
									console.log(data);
								});
							});
						});
					</script>
				</div>
			</div>	
					
		
			<div id="sidebar">
				<div class="inner">
					<?php $_SESSION['spcargo'] = $idPersonal; ?>
					<?php if($idPersonal==6) { //UIENNAVD?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Menú</h2>
							</header>
							<ul>
								<li><a href="welcome.php">Inicio</a></li>	
								<li><a href="lista_unidad.php">UIENNAVD</a></li>
								<li><a href="logout.php">Cerrar sesión</a></li>
							</ul>
						</nav>	
					<?php }else if($idPersonal==5) { //Subprocu ?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Menú</h2>
							</header>
							<ul>
								<li><a href="welcome.php">Inicio</a></li>	
								<li><a href="lista_personal.php">Personal</a></li>
								<li><a href="lista_usuarios.php">Usuarios</a></li>			
								<li><a href="lista_reportes_nueva.php?estRep=0">Reportes VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a></li>
								<li><a href="lista_casos.php">Casos</a></li>
								<li><a href="lista_nna.php">NNA</a></li>
								<li><span class="opener">Carpetas</span>
									<ul>
										<li><a href="lista_carpeta.php">Carpetas</a></li>
										<li><a href="lista_imputados.php">Imputados</a></li>			
									</ul>
								</li>		
								<li><a href="cas.php">CAS</a></li>							
								<li><span class="opener">Pendientes</span>
									<ul>
										<li><a href="carpetas_sasignar.php">Carpetas por asignar</a></li>
										<li><a href="nna_pendientes.php">NNA sin curp</a></li>
										<li><a href="visitas_fecha.php">Buscador</a></li>			
									</ul>
								</li>
								<li><a href="lista_documentos.php">Descarga de oficios</a></li>
								<li><a href="alta_medida.php">Catalogo de medidas</a></li>
								<li><a href="logout.php">Cerrar sesión</a></li>
							</ul>
						</nav>	
					<?php }else { ?>
						<!-- Menu -->
						<nav id="menu">
							<header class="major">
								<h2>Menú</h2>
							</header>
							<ul>
								<li><a href="welcome.php">Inicio</a></li>	
								<li><a href="lista_personal.php">Personal</a></li>
								<li><a href="lista_usuarios.php">Usuarios</a></li>
								<?php if ($_SESSION['departamento']==7) { ?>
									<li><a href="canalizar.php">Canalizar visita</a></li>	
								<?php } ?>												
								<li><a href="lista_reportes_nueva.php?estRep=0">Reportes VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a></li>
								<li><a href="lista_casos.php">Casos</a></li>
								<li><a href="lista_nna.php">NNA</a></li>		
								<li><a href="reg_reporte_migrantes.php">Migrantes</a></li>
								<li><span class="opener">Carpetas</span>
									<ul>
										<li><a href="lista_carpeta.php">Carpetas</a></li>
										<li><a href="lista_imputados.php">Imputados</a></li>			
									</ul>
								</li>
								<li><a href="cas.php">CAS</a></li>
								<li><span class="opener">UIENNAVD</span>
									<ul>
										<li><a href="lista_unidad.php">Beneficiarios</a></li>
										<li><a href="visitas_gen_unidad.php">Historial de visitas</a></li>
									</ul>
								</li>						
								<?php if (($_SESSION['departamento']==16) or ($_SESSION['departamento']==7)) { ?>
									<li><span class="opener">Visitas</span>
										<ul>
											<li><a href="editar_visitadepto.php">Editar departamento</a></li>
											<li><a href="editar_visitarespo.php">Editar responsable</a></li>
											<li><a href="eliminar_visita.php">Eliminar</a></li>
										</ul>
									</li>
								<?php } ?>									
								<li><span class="opener">Pendientes</span>
									<ul>
										<li><a href="carpetas_sasignar.php">Carpetas por asignar</a></li>
										<li><a href="nna_pendientes.php">NNA sin curp</a></li>			
										<li><a href="visitas_fecha.php">Buscador</a></li>				
									</ul>
								</li>									
								<li>
									<span class="opener">Adopciones</span>
									<ul>
										<li><a href="reg_expAdop.php">Generar expediente</a></li>
										<li><a href="">Expedientes</a></li>
									</ul>
								</li>
								<?php if ($_SESSION['departamento']==16 or $_SESSION['departamento']==14) {  ?>
									<li><a href="reg_actccpi.php">CCPI</a></li>
								<?php } ?>
								<li><a href="numoficio.php">Numero de oficio</a></li>
								 
								<li><a href="lista_documentos.php">Descarga de oficios</a></li>
								<li><a href="alta_medida.php">Catalogo de medidas</a></li>
								<li><a href="logout.php">Cerrar sesión</a></li>
							</ul>
						</nav>	
					<?php }?>
					<section>
						<header class="major">
							<h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
						</header>
						<p></p>
						<ul class="contact">
							<li class="fa-envelope-o">laura.ramirez@hidalgo.gob.mx</li>
							<li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
							<li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
							<li class="fa-phone"><a href="directorio.php">Directorio interno</a></li>
							<li class="fa-home">Plaza Juarez #118<br />
								Col. Centro <br> Pachuca Hidalgo</li>
						</ul>
					</section>
					<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; Sistema DIF Hidalgo </p>
					</footer>
				</div>
			</div><!--cierre de menu-->
		</div>  <!--cierre de wrapper-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>