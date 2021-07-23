<?php 
    session_start();
    require 'conexion.php';
    if(!isset($_SESSION["id"])){
     header("Location: index.php");
    }
    $idDEPTO = $_SESSION['id'];
    $id=$_GET['id'];
	$idCaso=$_SESSION['idCaso'];
	$idM=$_SESSION['idM'];

    $qSeg="SELECT area, respo_reg from seguimientos where id=$id";
    $esegui=$mysqli->query($qSeg);
    while ($row=$esegui->fetch_assoc())  { 
       echo $row['area'];
        if($row['area']=='PSICOLOGIA' and $id>17260 and $row['respo_reg']==$idDEPTO){
            $qActSeg="UPDATE seguimientos set seg_virtual=1 where id=$id";
            $rActSeg=$mysqli->query($qActSeg);
            if($rActSeg){
                header("Location: ag_comment.php?id=$idM&idCaso=$idCaso");
            } else {
                echo "Error: ".$qActSeg;
            }
        }
    }

?>