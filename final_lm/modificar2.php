<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto final</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="js/modificar.js"></script>
</head>
<body>
    <div id="container">
        <h1 class="titulo">MODIFICACIÓN DE DEPORTISTAS</h1>
        <?php
            include("conexion.php");
            $conexion = conectarMysql();
            if(isset($_REQUEST["antDni"])){
                // Ejecutar la modificación
                $antDni = $_REQUEST["antDni"];
                $dni = $_REQUEST["dni"];
                $Ap1 = $_REQUEST["primer_apellido"];
                $Ap2 = $_REQUEST["segundo_apellido"];
                $nombre = $_REQUEST["nombre"];
                $email = $_REQUEST["email"];
                $fecha = $_REQUEST["fecha_nacimiento"];
                $deporte = $_REQUEST["deporte"];
                $seleccion = $_REQUEST["seleccion"];
                // Obtengo el antiguo DNI desde un hidden para poder saber que deportista modificar
                // Ejecuto un update de todo, aunque no los hayan tocado no afectará en nada
                $sql = "UPDATE deportistas SET DNI='$dni', Nombre='$nombre', Apellido_1='$Ap1', Apellido_2='$Ap2', Deporte='$deporte', Fecha_Nac='$fecha', Seleccion='$seleccion', Email='$email' WHERE DNI='$antDni'";
                $consulta = mysqli_stmt_init($conexion);
                try{
                    if(mysqli_query($conexion,$sql)){
                        echo "<h1> Deportista modificado!</h1>";
                        echo "<p> El deportista ha sido modificado correctamente</p>";
                    }else{
                        echo "<h1 style=color:red>Error</h1>";
                        echo "<p>Error modificando el deportista</p>";
                        echo "<p style=color:red>".mysqli_error($conexion).": </p>";
                    }
                }catch(mysqli_sql_exception $e){
                    echo "<h1 style=color:red>Error</h1>";
                    echo "<p style=color:red>Ha habido un error modificando los datos: </p>";
                    echo "<p style=color:red>".mysqli_error($conexion).": </p>";
                }
            }else if(isset($_REQUEST["deportista"])){
               // Hay que sacar el form de modificación 
               echo "<form action=".$_SERVER['PHP_SELF']." method=post id=formModificar>";
                $dni = $_REQUEST["deportista"];
                $consulta = "SELECT * FROM deportistas WHERE DNI='$dni'";
                $resultado = mysqli_query($conexion, $consulta);
                $fila = mysqli_fetch_array($resultado);
                if ($fila) {
                    echo "<h2 style=text-align:center> ".$fila["Nombre"]." ".$fila["Apellido_1"]." ".$fila["Apellido_2"]."</h2>";
                    echo "<div id=containerFlex>";
                    
                    echo "<div>";
                    echo "<label for='dni'>DNI:</label>";
                    echo "<input type='text' name='dni' id='dni' value='" . $fila['DNI'] . "' required>";
                    echo "</div>";
                    
                    echo "<input type='hidden' name='antDni' id='antDni' value='$dni'>";
                    
                    echo "<div>";
                    echo "<label for='nombre'>Nombre:</label>";
                    echo "<input type='text' name='nombre' id='nombre' value='" . $fila['Nombre'] . "' required>";
                    echo "</div>";
                    
                    echo "<div>";
                    echo "<label for='primer_apellido'>Primer Apellido:</label>";
                    echo "<input type='text' name='primer_apellido' id='primer_apellido' value='" . $fila['Apellido_1'] . "' required>";
                    echo "</div>";
                    
                    echo "<div>";
                    echo "<label for='segundo_apellido'>Segundo Apellido:</label>";
                    echo "<input type='text' name='segundo_apellido' id='segundo_apellido' value='" . $fila['Apellido_2'] . "' required>";
                    echo "</div>";
                    
                    echo "<div>";
                    echo "<label for='email'>Email:</label>";
                    echo "<input type='email' name='email' id='email' value='" . $fila['Email'] . "' required>";
                    echo "</div>";
                    
                    echo "<div>";
                    echo "<label for='fecha_nacimiento'>Fecha de Nacimiento:</label>";
                    echo "<input type='date' name='fecha_nacimiento' id='fecha_nacimiento' value='" . $fila['Fecha_Nac'] . "' required>";
                    echo "</div>";
                    
                    echo "<div>";
                    echo "<label for='deporte'>Deporte:</label>";
                    echo "<select name='deporte' id='deporte' required>";
                    $options = ["Fútbol", "Baloncesto", "Tenis", "Natación", "Atletismo", "Voleibol", "Ciclismo", "Boxeo", "Golf", "Voley Playa"]; // Copia de los deportes del anadir.js
                    foreach ($options as $option) {
                        $selected = ($fila['Deporte'] == $option) ? "selected" : "";
                        echo "<option value='$option' $selected>$option</option>";
                    }
                    echo "</select>";
                    echo "</div>";
                    
                    echo "<div>";
                    echo "<label for='seleccion'>Selección: </label>";
                    echo "<select name='seleccion' id='seleccion' required>";
                    $paises = [
                        "Afganistán", "Albania", "Alemania", "Andorra", "Angola", "Antigua y Barbuda", "Arabia Saudita",
                        "Argelia", "Argentina", "Armenia", "Australia", "Austria", "Azerbaiyán", "Bahamas", "Bangladés",
                        "Barbados", "Baréin", "Bélgica", "Belice", "Benín", "Bielorrusia", "Birmania", "Bolivia",
                        "Bosnia y Herzegovina", "Botsuana", "Brasil", "Brunéi", "Bulgaria", "Burkina Faso", "Burundi",
                        "Bután", "Cabo Verde", "Camboya", "Camerún", "Canadá", "Catar", "Chad", "Chile", "China",
                        "Chipre", "Colombia", "Comoras", "Corea del Norte", "Corea del Sur", "Costa de Marfil",
                        "Costa Rica", "Croacia", "Cuba", "Dinamarca", "Dominica", "Ecuador", "Egipto", "El Salvador",
                        "Emiratos Árabes Unidos", "Eritrea", "Eslovaquia", "Eslovenia", "España", "Estados Unidos",
                        "Estonia", "Esuatini", "Etiopía", "Filipinas", "Finlandia", "Fiyi", "Francia", "Gabón",
                        "Gambia", "Georgia", "Ghana", "Granada", "Grecia", "Guatemala", "Guyana", "Guinea",
                        "Guinea-Bisáu", "Guinea Ecuatorial", "Haití", "Honduras", "Hungría", "India", "Indonesia",
                        "Irak", "Irán", "Irlanda", "Islandia", "Islas Marshall", "Islas Salomón", "Israel", "Italia",
                        "Jamaica", "Japón", "Jordania", "Kazajistán", "Kenia", "Kirguistán", "Kiribati", "Kuwait",
                        "Laos", "Lesoto", "Letonia", "Líbano", "Liberia", "Libia", "Liechtenstein", "Lituania",
                        "Luxemburgo", "Madagascar", "Malasia", "Malaui", "Maldivas", "Malí", "Malta", "Marruecos",
                        "Mauricio", "Mauritania", "México", "Micronesia", "Moldavia", "Mónaco", "Mongolia", "Montenegro",
                        "Mozambique", "Namibia", "Nauru", "Nepal", "Nicaragua", "Níger", "Nigeria", "Noruega",
                        "Nueva Zelanda", "Omán", "Países Bajos", "Pakistán", "Palaos", "Panamá", "Papúa Nueva Guinea",
                        "Paraguay", "Perú", "Polonia", "Portugal", "Reino Unido", "República Centroafricana",
                        "República Checa", "República del Congo", "República Democrática del Congo", "República Dominicana",
                        "Ruanda", "Rumanía", "Rusia", "Samoa", "San Cristóbal y Nieves", "San Marino", "San Vicente y las Granadinas",
                        "Santa Lucía", "Santo Tomé y Príncipe", "Senegal", "Serbia", "Seychelles", "Sierra Leona",
                        "Singapur", "Siria", "Somalia", "Sri Lanka", "Sudáfrica", "Sudán", "Sudán del Sur", "Suecia",
                        "Suiza", "Surinam", "Tailandia", "Tanzania", "Tayikistán", "Timor Oriental", "Togo", "Tonga",
                        "Trinidad y Tobago", "Túnez", "Turkmenistán", "Turquía", "Tuvalu", "Ucrania", "Uganda",
                        "Uruguay", "Uzbekistán", "Vanuatu", "Vaticano", "Venezuela", "Vietnam", "Yemen", "Yibuti",
                        "Zambia", "Zimbabue"
                    ];
                    foreach ($paises as $pais) {
                        $selected = ($fila['Seleccion'] == $pais) ? "selected" : "";
                        echo "<option value='$pais' $selected>$pais</option>";
                    }
                    echo "</select>";
                    echo "</div>";
                    
               
                    
                    echo "<br>";
                    echo "<input type='submit' value='Modificar'>";
                    echo "</div>";
                    echo "</form>";
                } else {
                    echo "<p style='color:red;'>Error: No se encontró ningún deportista con el DNI proporcionado.</p>";
                }
            }else{
                // Se ha accedido a esta página sin pasar por el formulario
                echo "<h1 style=color:red>Error</h1>";
                echo "<p style=color:red>A esta página solo se puede acceder desde el envío del formulario</p>";
            }
            
        ?>
        <br>
        <a href="./index.php" id="volver">Volver</a>
        <footer>
            <hr>
            <p>&copy; Proyecto Darío Erades Jiménez aplicaciones web 1ºDAM</p>
        </footer>
    </div>
</body>
</html>