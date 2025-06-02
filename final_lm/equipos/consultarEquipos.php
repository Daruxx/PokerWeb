<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto final</title>
    <link rel="stylesheet" href="../estilos.css">
    <script src="../js/consultarEquipos.js"></script>
</head>
<body>
    <div id="container">
        <h1 class="titulo">CONSULTAR DEPORTISTAS</h1>
        <?php
            include("../conexion.php");

            $conexion = conectarMysql();
            if(!isset($_REQUEST["indice"])){
                $indice = 1;
            }else{
                $indice = $_REQUEST["indice"];
            }
            if(isset($_REQUEST["eliminarDelEquipo"])){
                $sql = "UPDATE deportistas SET Nombre_Equipo = NULL WHERE DNI = '".$_REQUEST["eliminarDelEquipo"]."'";
                try{
                    if(mysqli_query($conexion, $sql)){
                        echo "<script>alert('Jugador eliminado correctamente del equipo');</script>";
                    }else{
                        echo "<script>alert('Error al eliminar el jugador del equipo: " . mysqli_error($conexion) . "');</script>";
                    }
                }catch(mysqli_sql_exception $e){
                    echo "<script>alert('Error al eliminar el jugador del equipo: " . mysqli_error($conexion) . "');</script>";
                }
            }
            $consulta = "SELECT * FROM equipos ORDER BY Nombre ASC";
            $resultado = mysqli_query($conexion, $consulta); 
            $total = mysqli_num_rows($resultado);
            echo "<p>Viendo al equipo ".$indice."/".$total;
            echo "<input id=total type=hidden value=$total>"; // Creo un input para acceder desde el js más tarde
            echo "<input id=indice type=hidden value=$indice>"; // Creo un input para acceder desde el js más tarde
            echo "<hr>";
            
            $i = 1;
            while($fila = mysqli_fetch_array($resultado)){
                if($i == $indice){
                    echo "<h2 id=nombreEquipo style=text-align:center>".$fila["Nombre"]."</h2>";
                    echo "<table>";
                    $sql = "SELECT * FROM deportistas WHERE Nombre_Equipo='".$fila["Nombre"]."' ORDER BY Apellido_1 ASC";
                    $resultado2 = mysqli_query($conexion, $sql);
                    ?>
                    <!-- Tabla un poco larga pero para cumplir con los requerimientos del proyecto y demostrar que se usarla-->
                    <tr>
                        <th colspan="6">JUGADORES</th>
                        <th rowspan="3">ACCIONES</th>
                    </tr>
                    <tr>
                        <th colspan="4">DATOS PERSONALES</th>
                        <th colspan="2">DATOS DEPORTIVOS</th>
                    </tr>
                    <tr>
                        <th>DNI</th>
                        <th>Nombre completo</th>
                        <th>Fecha nacimiento</th>
                        <th>Email</th>
                        <th>Selección</th>
                        <th>Deporte</th>
                    </tr>
                    <?php
                    $tieneJugadores = false;
                    while ($fila2 = mysqli_fetch_array($resultado2)) {
                        $tieneJugadores = true;
                        echo "<tr>";
                        echo "<td>".$fila2["DNI"]."</td>";
                        echo "<td>".$fila2["Nombre"]." ".$fila2["Apellido_1"]." ".$fila2["Apellido_2"]."</td>";
                        echo "<td>".$fila2["Fecha_Nac"]."</td>";
                        echo "<td>".$fila2["Email"]."</td>";
                        echo "<td>".$fila2["Seleccion"]."</td>";
                        echo "<td>".$fila2["Deporte"]."</td>";
                        echo "<td><button class=eliminar value=".$fila2["DNI"].">Eliminar</button></td>";
                        echo "</tr>";
                    }
                    if(!$tieneJugadores){
                        echo "<tr>";
                        echo "<td colspan=6>Este equipo no tiene jugadores</td>";
                        echo "</tr>";
                    } 
                    echo "<tr><td colspan=6><button id=anadir>Añadir Jugador al equipo</button><a href=../anadidoMasivo.php?nombreEquipo=".$fila["Nombre"].">Añadido masivo de jugadores</a></td></tr>";
                    echo "</table>";
                   
                }
                $i++;

            }

            echo "<br>";
            echo "<div id=botones>";
            echo "<button id=atras>◀</button>";
            echo "<button id=delante>▶</button>";
            echo "</div>";
            echo "<a id=volver href='../index.php'>Volver</a>";
            
        ?>
        
        <footer>
            <hr>
            <p>&copy; Proyecto Darío Erades Jiménez  aplicaciones web 1ºDAM</p>
        </footer>
    </div>
</body>
</html>