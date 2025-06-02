<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto final</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="js/anadir.js"></script>
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
        <!-- 
            El formulario se ha creado usando el DOM con js
        -->


        <footer>
            <hr>
            <p>&copy; Proyecto Darío Erades Jiménez aplicaciones web 1ºDAM</p>
            <a href="index.php" id="volver">Volver al inicio</a>
        </footer>
    </div>

</body>
</html>