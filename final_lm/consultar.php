<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto final</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="js/consultar.js"></script>
</head>
<body>
    <div id="container">
    <header>
            <h1 class="titulo">CONSULTAR DEPORTISTAS</h1>
            <?php 
            include("conexion.php");
            include("obtenerDivLogin.php"); 
            ?>
            <hr>
            
        </header>
        <?php
        
            $conexion = conectarMysql();
            if(isset($_REQUEST["dni"])){
                $dni = $_REQUEST["dni"];
                $consultaDni = "SELECT Apellido_1 FROM deportistas WHERE DNI = '$dni'";
                $resDni = mysqli_query($conexion, $consultaDni);
                if ($filaDni = mysqli_fetch_array($resDni)) {
                    $apellido1 = $filaDni["Apellido_1"];
                
                    // Cuento cuántos hay antes en orden
                    $posicionQuery = "SELECT COUNT(*) AS posicion FROM deportistas WHERE Apellido_1 < '$apellido1'";
                    $resPos = mysqli_query($conexion, $posicionQuery);
                    $filaPos = mysqli_fetch_array($resPos);
                
                    $indice = $filaPos["posicion"] + 1;
                } else {
                    echo "<p style='color:red;'>DNI no encontrado</p>";
                    $indice = 1;
                }
            }
            else if(!isset($_REQUEST["indice"])){
                $indice = 1;
            } 
            else{
                $indice = $_REQUEST["indice"];
            }
            $consulta = "SELECT * FROM deportistas ORDER BY Apellido_1 ASC";
            $resultado = mysqli_query($conexion, $consulta); 
            $total = mysqli_num_rows($resultado);
            echo "<p>Viendo al deportista ".$indice."/".$total;
            echo "<input id=total type=hidden value=$total>"; // Creo un input para acceder desde el js más tarde
            echo "<input id=indice type=hidden value=$indice>"; // Creo un input para acceder desde el js más tarde
            echo "<hr>";
            
            

            
            $i = 1;
            while($fila = mysqli_fetch_array($resultado)){
                if($i == $indice){
                    echo "<h2 style=text-align:center> ".$fila["Nombre"]." ".$fila["Apellido_1"]." ".$fila["Apellido_2"]."</h2>";
                    echo "<ul id=listaComprobar>";
                    echo "<li><b>DNI: </b> ".$fila["DNI"]."</li>";
                    echo "<li><b>Nombre completo: </b> ".$fila["Apellido_1"]." ".$fila["Apellido_2"].", ".$fila["Nombre"]."</li>";
                    echo "<li><b>Fecha de nacimiento: </b> ".$fila["Fecha_Nac"]."</li>";
                    echo "<li><b>Deporte: </b> ".$fila["Deporte"]."</li>";
                    echo "<li><b>Seleccion: </b> ".$fila["Seleccion"]."</li>";
                    echo "<li><b>Email: </b> ".$fila["Email"]."</li>";
                    echo "<li><b>Foto: </b> </li>";
                    echo "<li><img src=./".$fila["Foto"]." alt='Foto de ".$fila["Nombre"]."' width=200px height=200px></li>";
                    echo "</ul>";
                }
                $i++;

            }

            echo "<br>";
            echo "<div id=botones>";
            echo "<button id=atras>◀</button>";
            echo "<button id=delante>▶</button>";
            echo "<br>";
            echo "</div>";
            echo "<button id=buscardni class=botonesbuscar>Buscar por dni</button>";
            echo "<button id=buscarnombre class=botonesbuscar>Buscar por Nombre</button>";

            echo "<a id=volver href='index.php'>Volver</a>";
            
        ?>
        
        <footer>
            <hr>
            <p>&copy; Proyecto Darío Erades Jiménez aplicaciones web 1ºDAM</p>
        </footer>
    </div>
</body>
</html>