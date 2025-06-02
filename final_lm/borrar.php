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
            <h1 class="titulo">BORRAR DEPORTISTA</h1>
            <?php 
            include("conexion.php");
            include("obtenerDivLogin.php"); ?>
            <hr>
            
        </header>
        <?php
            
            if(!isset($_REQUEST["deportista"])){
                echo "<form action=".$_SERVER['PHP_SELF']." method=post>";
                echo "<label for=deportista>Selecciona el deportista a eliminar:   </label>";
                include("obtenerSelectDeportistas.php");
                echo "<br>";

                echo "<input type=checkbox name=confirmar id=confirmar required>";
                echo "<label for=confirmar>Confirmar que se quiere borrar el deportista</label>";
                echo "<br>";
                echo "<input type=submit value=Borrar>";
                echo "</form>";
            }else{
                $conexion = conectarMysql();
                $dni = $_REQUEST["deportista"];
                $sql = "DELETE FROM deportistas WHERE DNI='$dni'";
                try{
                    if(mysqli_query($conexion,$sql)){
                        echo "<h1> Deportista borrado!</h1>";
                        echo "<p> El deportista ha sido borrado correctamente</p>";
                    }else{
                        echo "<h1 style=color:red>Error</h1>";
                        echo "<p>Error borrando el deportista</p>";
                        echo "<p style=color:red>".mysqli_error($conexion).": </p>";
                    }
                }catch(mysqli_sql_exception $e){
                    echo "<h1 style=color:red>Error</h1>";
                    echo "<p style=color:red>Ha habido un error borrando los datos: </p>";
                    echo "<p style=color:red>".mysqli_error($conexion).": </p>";
                }
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