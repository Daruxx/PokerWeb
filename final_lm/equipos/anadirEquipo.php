<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto final</title>
    <link rel="stylesheet" href="../estilos.css">
</head>
<body>
    <div id="container">
        <h1 class="titulo">GESTIÓN DE DEPORTISTAS</h1>
        <?php
            if(!isset($_REQUEST["nombre"])){
                echo "<h1 style=color:red>Error</h1>";
                echo "<p style=color:red>A esta página solo se puede acceder desde el envío del formulario</p>";
                echo "<a href='index.php'>Volver</a>";
            }else{
                include("../conexion.php");
                $conexion = conectarMysql();
                $sql = "INSERT INTO equipos(Nombre) vALUES ('".$_REQUEST['nombre']."')";
                try{
                    if(mysqli_query($conexion,$sql)){
                        echo "<h1> Equipo añadido!</h1>";
                        echo "<p> El equipo ha sido añadido correctamente</p>";
                    }else{
                        echo "<h1 style=color:red>Error</h1>";
                        echo "<p>Error insertando el equipo</p>";
                        echo "<p style=color:red>".mysqli_error($conexion).": </p>";
                    }
                }catch(mysqli_sql_exception $e){
                    echo "<h1 style=color:red>Error</h1>";
                    echo "<p style=color:red>Ha habido un error insertando los datos: </p>";
                    echo "<p style=color:red>".mysqli_error($conexion).": </p>";
                }
            }
            
        ?>
        <a id=volver href='../index.php'>Volver</a>
        <footer>
            <hr>
            <p>&copy; Proyecto Darío Erades Jiménez aplicaciones web 1ºDAM</p>
        </footer>
    </div>
</body>
</html>