<?php
require 'conexion.php';


$fecha= date ("j/n/Y");
if(!empty($_POST))
	{
		$password = mysqli_real_escape_string($mysqli,$_POST['password']);
		$sha1_pass = sha1($password);
		$sql2 = "SELECT id FROM departamentos WHERE password = '$sha1_pass' and id_depto='16' and id_personal='1'";
		$result=$mysqli->query($sql2);
		$rows = $result->num_rows;
	if($rows > 0) {
		$equipo=mysqli_real_escape_string($mysqli,$_POST['equipo']);
		$n_inv=mysqli_real_escape_string($mysqli,$_POST['n_inv']);
		$marca=mysqli_real_escape_string($mysqli,$_POST['marca']);
		$modelo=mysqli_real_escape_string($mysqli,$_POST['modelo']);
		$serie=mysqli_real_escape_string($mysqli,$_POST['serie']);
		$mac=mysqli_real_escape_string($mysqli,$_POST['mac']);
		$so=mysqli_real_escape_string($mysqli,$_POST['so']);
		$procesador=mysqli_real_escape_string($mysqli,$_POST['procesador']);
		$valocidad=mysqli_real_escape_string($mysqli,$_POST['valocidad']);
		$ram=mysqli_real_escape_string($mysqli,$_POST['ram']);
		$dd=mysqli_real_escape_string($mysqli,$_POST['dd']);
		$antivirus=mysqli_real_escape_string($mysqli,$_POST['antivirus']);
		$office=mysqli_real_escape_string($mysqli,$_POST['office']);
		$usuario=$_POST['usuario'];
		$adscripcion=$_POST['adscripcion'];
		$ip=mysqli_real_escape_string($mysqli,$_POST['ip']);
		$internet=mysqli_real_escape_string($mysqli,$_POST['internet']);

		$pvalida="SELECT id from inventario where n_inv='$n_inv'";
		$epvalida=$mysqli->query($pvalida);
		$rowss=$epvalida->num_rows;
		if ($rowss>0) {
			$error="Error al Registrar";
		}else {
			$ins="INSERT into inventario (equipo, n_inv, marca, modelo, serie, mac, so, procesador, valocidad, ram, dd, antivirus, office, usuario, adscripcion, ip, internet, fecha) values ('$equipo','$n_inv','$marca','$modelo','$serie','$mac','$so','$procesador','$valocidad','$ram','$dd','$antivirus','$office','$usuario','$adscripcion','$ip','$internet','$fecha')";
			$eins=$mysqli->query($ins);
			
			if($eins>0)
			header("Location: inventarioInfo.php");
			else
			$error = "Error al Registrar";
		}
		
		
	}else {
		$error="Contraseña incorrecta";
	}
	}
?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Lista</title>
		<meta charset="utf-8" />
		
		</head>
	<body >
	<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
		<table border="3" align="center">
		
				<tr>
					<td><b>EQUIPO</b></td>
					<td><b>INVENTARIO</b></td>
					<td><b>MARCA</b></td>
					<td><b>MODELO</b></td>
					<td><b>SERIE</b></td>
					<td><b>MAC</b></td>					
				</tr>
				<tbody>				
				<tr>
					<td><input type="text" name="equipo" ></td>	
					<td><input type="text" name="n_inv" ></td>
					<td><input type="text" name="marca" ></td>
					<td><input type="text" name="modelo"></td>
					<td><input type="text" name="serie" ></td>
					<td><input type="text" name="mac" ></td>
				</tr>				
				</tbody>

				<tr>
					<td><b>S.O.</b></td>
					<td><b>PROCESADOR</b></td>
					<td><b>VELOCIDAD</b></td>
					<td><b>RAM</b></td>
					<td><b>DISCO DURO</b></td>
					<td><b>ANTIVIRUS</b></td>					
				</tr>
				<tbody>					
				<tr>
					<td><input type="text" name="so" ></td>
				<td><input type="text" name="procesador" ></td>
					<td><input type="text" name="valocidad" ></td>
					<td><input type="text" name="ram" ></td>
					<td><input type="text" name="dd" ></td>
					<td><input type="text" name="antivirus" ></td>
				</tr>			
				</tbody>

				<tr>
					<td><b>OFFICE</b></td>
					<td><b>USUARIO</b></td>
					<td><b>ADSCRIPCION</b></td>
					<td><b>IP</b></td>
					<td><b>INTERNET</b></td>
					<td><b></b></td>					
				</tr>
				<tbody>					
				<tr>
					<td><input type="text" name="office" ></td>
					<td><textarea name="usuario" rows="3" cols="40" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"></textarea></td>
				<td><textarea name="adscripcion" rows="3" cols="40" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"></textarea></td>
					<td><input type="text" name="ip" ></td>
					<td><input type="text" name="internet" ></td>
					<td><input type="password" name="password"><input type="submit" name="guardar" value="GUARDAR"></td>						
				</tr>			
				</tbody>
					
			</table>	
			<br>
			<br>
			<input type="button" name="c" onclick="location='inventarioInfo.php'">
		</form>
	</body>
</html>