<?php
echo "<div class=login>";
if(!isset($_COOKIE["login"])){
    echo "<p>Ninguna sesión iniciada</p>";
    echo "<button id=login>Iniciar sesión</button>";
}else{
    $conexion = conectarMysql();
    $sql = "SELECT * FROM deportistas WHERE DNI='".$_COOKIE["login"]."'";
    $resultado = mysqli_query($conexion, $sql);
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_array($resultado);
        echo "<p>Bienvenido <b>" . $fila['Nombre'] . " " . $fila['Apellido_1'] . " " . $fila['Apellido_2'] . "</b></p>";
        echo "<button id=login>Cambiar usuario</button>";
    } else {
        echo "<p>Error: Usuario no encontrado</p>";
    }
    
}
?>
<script>
document.getElementById("login").addEventListener("click",function(evento){
    window.location.href = "./login.php";
})

</script>
<?php

echo "</div>";


?>