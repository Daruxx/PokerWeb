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
            <h1 class="titulo">MODIFICAR DEPORTISTAS</h1>
            <?php
            include("conexion.php");
            include("obtenerDivLogin.php"); ?>
            <hr>
            
        </header>
        <?php
          
            
            echo "<form action=modificar2.php method=post>";
            echo "<label for=deportista>Selecciona el deportista a modificar:   </label>";
            include("obtenerSelectDeportistas.php");
            echo "<br>";
            echo "<input type=submit value=Modificar>";
            echo "</form>";
            
        ?>
        <a id=volver href='index.php'>Volver</a>
        <footer>
            <hr>
            <p>&copy; Proyecto Darío Erades Jiménez aplicaciones web 1ºDAM</p>
        </footer>
    </div>
</body>
</html>