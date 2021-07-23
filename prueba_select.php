<?php
$valores=array(1=>"opcion 1", "opcion 2", "opcion 3");
?>
 <!DOCTYPE HTML>
<html>
<body>
<form action="" method="POST">
<select name="miSelect">
    <option value='0'>Selecciona una opcion</option>
    <?php
    foreach($valores as $key=>$value)
    {
        // Si coincide lo enviado por el formulario con tu valor...
        if($_POST["miSelect"]==$key)
        {
            echo "<option value='".$key."' selected>".$value."</option>";
        }else{
            echo "<option value='".$key."'>".$value."</option>";
        }
    }
    ?>
</select>
<input type="submit" value="enviar">
</form>
</body>
</html>