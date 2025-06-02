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
            <h1 class="titulo">Añadido masivo de deportistas</h1>
            <?php 
            include("conexion.php");
            include("obtenerDivLogin.php");
             ?>
            <hr>
        </header>
        <?php
            if(isset($_REQUEST["anadir"])){
                $num = $_REQUEST["num"];
                $anadidos[] = array();
                for($i=1;$i<=$num;$i++){
                    $conexion = conectarMysql();
                    $deportista = $_REQUEST["deportista".$i];
                    $yaEstaba = false;
                    foreach($anadidos as $anadido){
                        if($anadido == $deportista){
                            echo "<p style=color:red>El deportista ".$deportista." ya había sido añadido previamente</p>";
                            $yaEstaba = true;
                        }
                    }
                    
                    if(!$yaEstaba){
                        $anadidos[$i] = $deportista;
                        $consulta = "UPDATE deportistas SET Nombre_Equipo='".$_REQUEST["nombreEquipo"]."' WHERE DNI='".$deportista."'";
                        $res = mysqli_query($conexion, $consulta);
                        if(!$res){
                            echo "<p style=color:red>El deportista ".$deportista." no se ha podido añadir</p>";
                        }else{
                            echo "<p style=color:green>El deportista ".$deportista." se ha añadido correctamente</p>";
                        }
                    }
                    
                }
            }else if(isset($_REQUEST["enviar"])){
                $error = false;
                $errorNum = "";
                $num = $_REQUEST["num"];
                if(trim($num) == ""){
                    $error = true;
                    $errorNum = "El campo no puede estar vacío";
                }else if(!is_numeric($num)){
                    $error = true;
                    $errorNum = "El campo tiene que ser un número";
                }
            }
            if(isset($_REQUEST["enviar"]) && !$error ){
                /*
                    Ejecutar código, hay que hacer $num selects

                    $num -> Número de deportistas/selects

                    1 solo form

                    Usar obtenerSelectDeportistas.php 
                */

                echo "<form action=".$_SERVER['PHP_SELF']." method=post>";
                echo "<input type=hidden name=num value=".$num.">";
                echo "<ol id=olAnadidoMasivo>";
                $conexion = conectarMysql();
                $consulta = "SELECT * FROM deportistas ORDER BY Apellido_1 ASC";
                $resultado = mysqli_query($conexion, $consulta);
                $opciones = "";
                while($fila = mysqli_fetch_array($resultado)){
                    $opciones = $opciones."<option value='".$fila["DNI"]."'>".$fila["Nombre"]." ".$fila["Apellido_1"]." ".$fila["Apellido_2"]."</option>";
                }
                for($i=1;$i<=$num;$i++){
                    echo "<li>";
                    echo "<label for=deportista".$i.">Selecciona el deportista: </label>";
                    $id = "deportista".$i;
                    echo "<select name='$id' id='$id'>";
                    echo $opciones;
                    echo "</select>";
                    echo "</li>";
                    echo "<input type=hidden name=nombreEquipo value=".$_REQUEST["nombreEquipo"].">";

                }
                echo "</ol>";
                echo "<input type=submit name=anadir value=Añadir>";
                echo "</form>";
            }else if(!isset($_REQUEST["anadir"])){ // Para que no se muestre al final ya que son 3 páginas en una
                echo "<form action=".$_SERVER['PHP_SELF']." method=post>";
                echo "<label for=num>Numero de deportistas a añadir:   </label>";
                $valor = isset($_REQUEST["num"]) ? $_REQUEST["num"] : ""; // Visto en programación y entornos
                echo "<input type=text name=num id=num value=".$valor.">";
                if(isset($error)){
                    echo "<p style=color:red>".$errorNum."</p>";
                }
                echo "<input type=hidden name=nombreEquipo value=".$_REQUEST["nombreEquipo"].">";

                echo "<input type=submit name=enviar value=Enviar>";
                echo "</form>";
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