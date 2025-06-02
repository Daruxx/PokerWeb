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
        <h1 class="titulo">AÑADIR DEPORTISTA A</h1>
        <?php
            include("../conexion.php");
            $conexion = conectarMysql();
            if(!isset($_REQUEST["deportista"])){
                $consulta = "SELECT * FROM deportistas ORDER BY Apellido_1 ASC";
                $resultado = mysqli_query($conexion, $consulta); 
            
                echo "<form action=anadirAEquipo.php method=post>";
                echo "<label for=deportista>Selecciona el deportista a añadir a <b>".$_REQUEST["nombreEquipo"]."</b>:   </label>";
                echo "<select name='deportista' id='deportista'>";
                while($fila = mysqli_fetch_array($resultado)){
                    if(!$fila["Nombre_Equipo"] == $_REQUEST["nombreEquipo"]){
                        echo "<option value='".$fila["DNI"]."'>".$fila["Nombre"]." ".$fila["Apellido_1"]." ".$fila["Apellido_2"]."</option>";
                    }else{
                        echo "<option value='".$fila["DNI"]."'>".$fila["Nombre"]." ".$fila["Apellido_1"]." ".$fila["Apellido_2"]."(Pertenece a ".$fila["Nombre_Equipo"].")"."</option>";
                    }
                }
                echo "</select>";
                echo "<br>";
                echo "<input type=hidden name=equipo value='".$_REQUEST["nombreEquipo"]."'>";
                echo "<input type=submit value=Añadir>";
                echo "</form>";
            }else{
                $dni = $_REQUEST["deportista"];
                $sql = "UPDATE deportistas SET Nombre_Equipo='".$_REQUEST["equipo"]."' WHERE DNI='$dni'";
                try{
                    if(mysqli_query($conexion,$sql)){
                        echo "<h1> Deportista añadido!</h1>";
                        echo "<p> El deportista ha sido añadido correctamente</p>";
                    }else{
                        echo "<h1 style=color:red>Error</h1>";
                        echo "<p>Error añadiendo el deportista</p>";
                        echo "<p style=color:red>".mysqli_error($conexion).": </p>";
                    }
                }catch(mysqli_sql_exception $e){
                    echo "<h1 style=color:red>Error</h1>";
                    echo "<p style=color:red>Ha habido un error añadiendo los datos: </p>";
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