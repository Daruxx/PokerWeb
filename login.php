<?php
    include("utils.php");
    arriba("LOGIN");
    if(!isset($_REQUEST["usuario"])){
        ?>
            <form action="<?=$_SERVER["PHP_SELF"]?>" method="post">
                <label for="usuario">Usuario: </label>
                <input type="text" name="usuario" id="usuario" required>
                <br>
                <label for="contra">Contraseña: </label>
                <input type="password" name="contra" id="contra" required>
                <br>
                <input type="submit" value="Enviar">
            </form>
        <?php
    }else{
        $usuario = $_REQUEST["usuario"];
        $contraIngresada = $_REQUEST["contra"];
        $conn = conectarMySql();
        $sql = "SELECT contraseña,id FROM usuariosweb WHERE usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $fila = $resultado->fetch_assoc();
            $hashAlmacenado = $fila['contraseña'];
            $id = $fila['id'];

            // Verificamos la contraseña usando password_verify
            if (password_verify($contraIngresada, $hashAlmacenado)) {
                echo "Inicio de sesión correcto!";
                setcookie("login", $id, time() + (30 * 24 * 60 * 60), "/");
            } else {
                echo "La contraseña es incorrecta.";
            }
        } else {
            echo "El usuario no existe.";
        }
    }
    
    abajo();

?>