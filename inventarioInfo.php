<?php
require 'conexion.php';

$sql="SELECT id from inventario";
$esql=$mysqli->query($sql);
$rows=$esql->num_rows;

$check="SELECT * from inventario where fecha=''";
$ec=$mysqli->query($check);
$rows3=$ec->num_rows;


?>
<!DOCTYPE HTML>

<html>
	<head>
		<title>Lista</title>
		<meta charset="utf-8" />
		
		</head>
	<body >
<section id="search" class="alt">
				<form id="buscador" name="buscador" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" onSubmit="return validarForm(this)">
						<input type="search" style="text-transform:uppercase;" name="palabra" id="query" placeholder="BUSCAR..."/>
						<input type="button" name="x" onclick="location='logout.php'">
						<input type="button" name="reg" value="R" onclick="location='reg_inventario.php'">
						<input type="submit" name="sinfecha" value="SF">
				</form>
			</section>
						Total: <?php echo $rows; ?>
						Faltas: <?php echo $rows3; ?>
				
				<table border="3" align="center">			
				<tr>
					<td><b>EQUIPO</b></td>
					<td><b>INVENTARIO</b></td>
					<td><b>MARCA</b></td>
					<td><b>MODELO</b></td>
					<td><b>SERIE</b></td>
					<td><b>USUARIO</b></td>
					<td><b>ADSCRIPCION</b></td>
					<td><b>IP</b></td>
					<td><b>INTERNET</b></td>
					<td><b></b></td>
					
				</tr>
				<tbody>
				<?php	@$buscar = $_POST["palabra"];
					if (empty($buscar)) {
						$query="SELECT id, equipo, n_inv, marca, modelo, serie, mac, so, procesador, valocidad, ram, dd, antivirus, office, usuario, adscripcion, ip, internet, fecha from inventario having (equipo like '%$buscar%' OR n_inv like '%$buscar%' OR marca like '%$buscar%' OR serie like '%$buscar%' OR usuario like '%$buscar%' OR adscripcion like '%$buscar%') limit 20";
					}else if ($buscar=='*') {
						$query="SELECT id, equipo, n_inv, marca, modelo, serie, mac, so, procesador, valocidad, ram, dd, antivirus, office, usuario, adscripcion, ip, internet, fecha from inventario having fecha=''";
					
					}else {
						$query="SELECT id, equipo, n_inv, marca, modelo, serie, mac, so, procesador, valocidad, ram, dd, antivirus, office, usuario, adscripcion, ip, internet, fecha from inventario having (equipo like '%$buscar%' OR n_inv like '%$buscar%' OR marca like '%$buscar%' OR serie like '%$buscar%' OR usuario like '%$buscar%' OR adscripcion like '%$buscar%')";
					}
					
						$resultado=$mysqli->query($query);
						$rows2=$resultado->num_rows;
						echo "Resultados: ".$rows2;
 					while($row=$resultado->fetch_assoc()){ ?>
					
				<tr>
					<td><?php echo $row['equipo'];?></td>
					<td><a href="reasignar.php?id=<?php echo $row['id']; ?>"><?php echo $row['n_inv'];?></a></td>					
					<td><?php echo $row['marca'];?></td>
					<td><?php echo $row['modelo'];?></td>
					<td><?php echo $row['serie'];?></td>
					<td><?php echo $row['usuario'];?></td>
					<td><?php echo $row['adscripcion'];?></td>
					<td><?php echo $row['ip'];?></td>
					<td><?php echo $row['internet'];?></td>
					<td><?php echo $row['fecha'];?></td>
				</tr>
					<?php } ?>
				</tbody>
			</table>	
</body>
</html>