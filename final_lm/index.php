<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto final</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div id="container">
        <header>
            <h1 class="titulo">GESTIÓN DE DEPORTISTAS</h1>
            <?php 
            include("conexion.php");
            include("obtenerDivLogin.php");
             ?>
            <hr>
            
        </header>
        <ul id="menuPrincipal">
            <li><a href="anadir.php">Añadir deportista</a></li>
            <li><a href="modificar1.php">Modificar deportista</a></li>
            <li><a href="borrar.php">Borrar deportista</a></li>
            <li><a href="consultar.php">Consultar deportistas</a></li>
            <hr>
            <li><a href="./equipos/anadirEquipo.html">Añadir equipos</a></li>
            <li><a href="./equipos/consultarEquipos.php">Consultar equipos</a></li>
        </ul>
        <footer>
            <hr>
            <p>&copy; Proyecto Darío Erades Jiménez aplicaciones web 1ºDAM</p>
        </footer>
    </div>
</body>
</html>