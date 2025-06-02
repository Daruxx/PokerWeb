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
        <?php
        include("conexion.php");

        $conexion = conectarMysql();
        if(!isset($_REQUEST["nombre"])){
            echo "<h1 style=color:red>Error</h1>";
            echo "<p style=color:red>A esta página solo se puede acceder desde el envío del formulario</p>";
            echo "<a href='index.php'>Volver</a>";
            exit();
        }else{
            $nombre = $_REQUEST['nombre'];
            $dni = $_REQUEST['dni'];
            $apellido1 = $_REQUEST['primer_apellido'];
            $apellido2 = $_REQUEST['segundo_apellido'];
            $email = $_REQUEST['email'];
            $fecha = $_REQUEST['fecha_nacimiento'];
            $deporte = $_REQUEST['deporte'];
            $seleccion = $_REQUEST['pais'];


            $foto = $_FILES["foto"];
          
            $extension = explode('.', $foto['name'])[1];
            $archivo = "./img/".basename($dni).".".$extension; // Spliteo por . y cojo la extensión

            if (!move_uploaded_file($foto["tmp_name"], $archivo)) {
                echo "<h1 style=color:red>Error</h1>";
                echo "<p style=color:red>No se pudo guardar la foto.</p>";
            }


            $sql = "INSERT INTO deportistas (DNI,Nombre,Apellido_1,Apellido_2,Deporte,Fecha_Nac,Seleccion,Email,Foto) VALUES ('$dni','$nombre','$apellido1','$apellido2','$deporte','$fecha','$seleccion','$email','$archivo')";
            try{
                if(mysqli_query($conexion,$sql)){
                    echo "<h1> Alumno añadido!</h1>";
                    echo "<p> El alumno ha sido añadido correctamente</p>";
                }else{
                    echo "<h1 style=color:red>Error</h1>";
                    echo "<p>Error insertando el alumno</p>";
                }
            }catch(mysqli_sql_exception $e){
                echo "<h1 style=color:red>Error</h1>";
                echo "<p style=color:red>Ha habido un error insertando los datos: </p>";
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