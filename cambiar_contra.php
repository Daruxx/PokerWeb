<?php
    include("utils.php");
    arriba("Cambiar Contraseña");

    if (!isset($_COOKIE["login"])) {
        echo "<p>No estás autenticado. Inicia sesión primero.</p>";
        abajo();
        exit;
    }

    $idUsuario = $_COOKIE["login"];

    if (!isset($_POST["actual"]) || !isset($_POST["nueva"])) {
        // Mostrar el formulario
        ?>
        <form action="<?= $_SERVER["PHP_SELF"] ?>" method="post">
            <label for="actual">Contraseña actual:</label>
            <input type="password" name="actual" id="actual" required>
            <br>
            <label for="nueva">Nueva contraseña:</label>
            <input type="password" name="nueva" id="nueva" required>
            <br>
            <input type="submit" value="Cambiar contraseña">
        </form>
        <?php
    } else {
        // Procesar el cambio de contraseña
        $actual = $_POST["actual"];
        $nueva = $_POST["nueva"];

        $conn = conectarMySql();
        $sql = "SELECT contraseña FROM usuariosweb WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $fila = $resultado->fetch_assoc();
            $hashAlmacenado = $fila['contraseña'];

            if (password_verify($actual, $hashAlmacenado)) {
                // La contraseña actual es correcta, actualizamos
                $nuevoHash = password_hash($nueva, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE usuariosweb SET contraseña = ? WHERE id = ?");
                $update->bind_param("si", $nuevoHash, $idUsuario);
                if ($update->execute()) {
                    echo "<p>¡Contraseña actualizada correctamente!</p>";
                } else {
                    echo "<p>Error al actualizar la contraseña.</p>";
                }
            } else {
                echo "<p>La contraseña actual no es correcta.</p>";
            }
        } else {
            echo "<p>Usuario no encontrado.</p>";
        }
    }

    abajo();
?>
