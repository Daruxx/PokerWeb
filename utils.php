<?php
    include("conexion.php");

    function obtenerDivLogin(){
        echo "<div class=login>";
        if(!isset($_COOKIE["login"])){
            echo "<p>Ninguna sesión iniciada</p>";
            echo "<button id=login>Iniciar sesión</button>";
        }else{
            $conexion = conectarMysql();
            $sql = "SELECT * FROM usuariosweb WHERE id='".$_COOKIE["login"]."'";
            $resultado = mysqli_query($conexion, $sql);
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_array($resultado);
                echo "<p>Bienvenido <b>" . $fila['usuario']. "</b></p>";
                echo "<button id=login>Cambiar usuario</button>";
            } else {
                echo "<p>Error: Usuario no encontrado</p>";
            }
            
        }
        ?>
        <script>
        document.getElementById("login").addEventListener("click",function(evento){
            window.location.href = "./login.php";
        })
        
        </script>
        <?php

        echo "</div>";
    }

    function arriba($titulo){
        ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?=$titulo?></title>
                <link rel="stylesheet" href="estilos.css">
                <link rel="icon" type="image/png" href="icono.png">
            </head>
            <body>
                <div id="container">
                    <header>
                        <h1 class="titulo"><?=$titulo?></h1>
                        <?php 
                        obtenerDivLogin();
                         ?>
                        <hr>
                    </header>

        <?php
    }

    function abajo(){
        ?>
        <footer>
                    <hr>
                    <p>&copy; POKEEER, Darío goat</p>
                    <a id=volver href="/">Volver al inicio</a>
                </footer>
            </div>
        </body>
        </html>

        <?php
    }


    function hashear($password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }

    function alert($texto){
        echo "<script>alert(".$texto.");</script>";
    }
    function obtenerNombrePersona($idPersona,$conexion){
        $sql123 = "select nombre from persona where id=?";
        $stmt123 = $conexion->prepare($sql123);
        $stmt123->bind_param("i", $idPersona);
        $stmt123->execute();
        $result123 = $stmt123->get_result();
        if ($result123->num_rows > 0) {
            $fila123 = $result123->fetch_assoc();
            return $fila123["nombre"];
        }else return "";
    }

    function obtenerBalancesProvisionales($id_partida, $conexion) {
    $balances = [];

    // Preparamos la consulta para obtener todos los pagos de la partida
    $sql = "SELECT * FROM pago WHERE id_partida = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_partida);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($pago = $result->fetch_assoc()) {
        $importe = $pago['importe_en_euros'];
        $tipo = $pago['tipo'];

        if ($tipo != 'banca') {
            $paga = $pago['jugador_paga'];
            $recibe = $pago['jugador_recibe'];

            // Resta al que paga
            if (!isset($balances[$paga])) $balances[$paga] = 0;
            $balances[$paga] -= $importe;

            // Suma al que recibe
            if (!isset($balances[$recibe])) $balances[$recibe] = 0;
            $balances[$recibe] += $importe;
        } else {
            // Pago a la banca: solo resta al jugador que paga
            $paga = $pago['jugador_paga'];
            if (!isset($balances[$paga])) $balances[$paga] = 0;
            $balances[$paga] -= $importe;
        }
    }

    return $balances;
}



?>