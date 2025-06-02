<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto final</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="js/buscarnombre.js"></script>
</head>
<body>
    <div id="container">
        <header>
            <h1 class="titulo">BUSCAR POR NOMBRE</h1>
            <?php 
            include("conexion.php");
            include("obtenerDivLogin.php");
             ?>
            <hr>
            
        </header>
        <!-- Se genera con js-->
         <?php
            echo "<input type=hidden id=nombre value='".$_REQUEST["nombre"]."'>";
            echo "<div id=divResultado></div>";
            
         ?>
         <a href="../index.php" id=volver>Volver</a>
        <footer>
            <hr>
            <p>&copy; Proyecto Darío Erades Jiménez aplicaciones web 1ºDAM</p>
        </footer>
    </div>
</body>
</html>