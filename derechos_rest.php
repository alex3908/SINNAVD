<?php 
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
	}
	$idCaso=$_GET['idC'];
	$idDEPTO = $_SESSION['id'];
	$idnna=$_GET['id'];
	$curp="SELECT validacionRenapo from nna where id=$idnna";
	$qcurp = $mysqli->query($curp);
	$valCurp = implode($qcurp->fetch_assoc());
	
	
	$fecha = date("Y-m-d H:i:s", time());
	$fecha2 = date ("j/n/Y");
	$sql="INSERT into nna_restituidos (id_nna, fecha_reg, fecha_registro, respo_reg) values ('$idnna', '$fecha2', '$fecha','$idDEPTO')";
	$esql=$mysqli->query($sql);
	if($esql)
			header("Location: perfil_caso.php?id=$idCaso");
			else
			echo "<script>
                alert('¡Error! intentelo mas tarde');   
                window.location= 'perfil_caso.php?id=$idCaso'
             </script>";
	
?>