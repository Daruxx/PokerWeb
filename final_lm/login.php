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
            <h1 class="titulo">LOGIN</h1>
        </header>
        <?php 
        include("conexion.php");
        if(!isset($_REQUEST["deportista"])){
            echo '<p>Por que deportista quieres hacer login?</p>';
            echo '<form action="login.php">';
            include("obtenerSelectDeportistas.php");
            echo '<button type="submit">Login</button>';
            echo '</form>';
        }else{
            setcookie("login", $_REQUEST["deportista"], time() + 3600); // Una hora de cookie
            echo "<p>Login correcto</p>";
        }
        
        ?>
        <a id=volver href='index.php'>Volver</a>
        
        
        <footer>
            <hr>
            <p>&copy; Proyecto Darío Erades Jiménez aplicaciones web 1ºDAM</p>
        </footer>
    </div>
</body>
</html>