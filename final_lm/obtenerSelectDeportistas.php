<?php
  $conexion = conectarMysql();
  $consulta = "SELECT * FROM deportistas ORDER BY Apellido_1 ASC";
  $resultado = mysqli_query($conexion, $consulta); 
  $id = "deportista";
  if(isset($_REQUEST["idSelect"])){
      $id = $_REQUEST["idSelect"];
  }
  echo "<select name='$id' id='$id'>";
  while($fila = mysqli_fetch_array($resultado)){
      echo "<option value='".$fila["DNI"]."'>".$fila["Nombre"]." ".$fila["Apellido_1"]." ".$fila["Apellido_2"]."</option>";
  }
  echo "</select>";

?>