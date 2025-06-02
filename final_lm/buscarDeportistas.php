<?php
header("Content-Type: application/json");
include("conexion.php");
$conexion = conectarMysql();

$consulta = "SELECT * FROM deportistas ORDER BY Apellido_1 ASC";
$resultado = mysqli_query($conexion, $consulta);

$deportistas = [];
while ($deportista = mysqli_fetch_assoc($resultado)) {
    $dni = $deportista['DNI'];
    $deportistas[$dni] = $deportista;
}
echo json_encode($deportistas);
?>
