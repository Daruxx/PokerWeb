<?php
require_once("utils.php");

arriba("Estadísticas Generales");

$mysqli = conectarMySql();

// Procesar formulario de pago
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['emisor_id'], $_POST['receptor_id'], $_POST['cantidad'])) {
    $emisor_id = intval($_POST['emisor_id']);
    $receptor_id = intval($_POST['receptor_id']);
    $cantidad = floatval(str_replace(',', '.', $_POST['cantidad'])); // soporte para coma

    if ($emisor_id !== $receptor_id && $cantidad > 0) {
        // Insertar el pago de quien ha pagado
        $stmt1 = $mysqli->prepare("INSERT INTO pagos_generales (jugador_id, cantidad, tipo) VALUES (?, ?, 'ha_pagado')");
        $stmt1->bind_param("id", $emisor_id, $cantidad);
        $stmt1->execute();
        $stmt1->close();

        // Insertar el cobro de quien ha recibido el pago
        $stmt2 = $mysqli->prepare("INSERT INTO pagos_generales (jugador_id, cantidad, tipo) VALUES (?, ?, 'le_han_pagado')");
        $stmt2->bind_param("id", $receptor_id, $cantidad);
        $stmt2->execute();
        $stmt2->close();

        // Redirigir para evitar reenvío con F5
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        echo "<p style='color:red;text-align:center;'>❌ Error: el emisor y el receptor deben ser diferentes y el monto debe ser mayor a 0.</p>";
    }
}


// Obtener balances totales de las partidas
$sql = "SELECT balances FROM partida";
$result = $mysqli->query($sql);

$balances_totales = [];

while ($row = $result->fetch_assoc()) {
    $balances_str = $row['balances'];
    if (!$balances_str) continue;
    $pares = explode(";", $balances_str);
    foreach ($pares as $par) {
        if (empty($par)) continue;
        list($jugador_id, $balance) = explode("=", $par);
        $balance = floatval($balance);
        if (!isset($balances_totales[$jugador_id])) $balances_totales[$jugador_id] = 0;
        $balances_totales[$jugador_id] += $balance;
    }
}

// Sacar nombres
if (count($balances_totales) > 0) {
    $ids = implode(",", array_map('intval', array_keys($balances_totales)));
    $sql = "SELECT id, nombre FROM persona WHERE id IN ($ids)";
    $result = $mysqli->query($sql);
    $jugadores = [];
    while ($fila = $result->fetch_assoc()) {
        $jugadores[$fila['id']] = $fila['nombre'];
    }
} else {
    $jugadores = [];
}

// Obtener pagos generales
$pagos_ha_pagado = [];
$pagos_le_han_pagado = [];

if (!empty($ids)) {
    $sql = "SELECT jugador_id, tipo, SUM(cantidad) as total FROM pagos_generales WHERE jugador_id IN ($ids) GROUP BY jugador_id, tipo";
    $result = $mysqli->query($sql);
    while ($fila = $result->fetch_assoc()) {
        $jugador_id = $fila['jugador_id'];
        $tipo = $fila['tipo'];
        $total = floatval($fila['total']);
        if ($tipo === 'ha_pagado') {
            $pagos_ha_pagado[$jugador_id] = $total;
        } else {
            $pagos_le_han_pagado[$jugador_id] = $total;
        }
    }
}
?>

<style>
    table {
        border-collapse: collapse;
        width: 80%;
        margin: 20px auto;
    }
    th, td {
        border: 1px solid #ccc;
        padding: 8px 12px;
        text-align: center;
    }
    .positivo {
        color: green;
        font-weight: bold;
    }
    .negativo {
        color: red;
        font-weight: bold;
    }
    form {
        width: 50%;
        margin: 30px auto;
        text-align: center;
    }
    form input, form select {
        padding: 6px;
        margin: 5px;
    }
    form button {
        padding: 6px 12px;
        cursor: pointer;
    }
</style>

<h2 style="text-align:center;">Estadísticas Generales</h2>

<table>
    <thead>
        <tr>
            <th>Concepto</th>
            <?php
            foreach ($balances_totales as $id => $balance) {
                $nombre = isset($jugadores[$id]) ? htmlspecialchars($jugadores[$id]) : "Jugador #$id";
                echo "<th>$nombre</th>";
            }
            ?>
        </tr>
    </thead>
    <tbody>
        
        <tr>
            <td>YA HA PAGADO (€)</td>
            <?php
            foreach ($balances_totales as $id => $balance) {
                $monto = isset($pagos_ha_pagado[$id]) ? $pagos_ha_pagado[$id] : 0;
                echo "<td>" . number_format($monto, 2, ',', '.') . " €</td>";
            }
            ?>
        </tr>
        <tr>
            <td>YA LE HAN PAGADO (€)</td>
            <?php
            foreach ($balances_totales as $id => $balance) {
                $monto = isset($pagos_le_han_pagado[$id]) ? $pagos_le_han_pagado[$id] : 0;
                echo "<td>" . number_format($monto, 2, ',', '.') . " €</td>";
            }
            ?>
        </tr>
        <tr>
            <td>BALANCE ACTUAL (€)</td>
            <?php
            foreach ($balances_totales as $id => $balance) {
                $ha_pagado = isset($pagos_ha_pagado[$id]) ? $pagos_ha_pagado[$id] : 0;
                $le_han_pagado = isset($pagos_le_han_pagado[$id]) ? $pagos_le_han_pagado[$id] : 0;
                $balance_actual = $balance - $le_han_pagado + $ha_pagado;
                $clase = ($balance_actual < 0) ? "negativo" : "positivo";
                echo "<td>" . number_format($balance_actual, 2, ',', '.') . " €</td>";
            }
            ?>
        </tr>
        <tr>
            <td>Balance Total (€)</td>
            <?php
            foreach ($balances_totales as $id => $balance) {
                $clase = ($balance < 0) ? "negativo" : "positivo";
                echo "<td class='$clase'>" . number_format($balance, 2, ',', '.') . " €</td>";
            }
            ?>
        </tr>
    </tbody>
</table>

<h2 style="text-align:center;">Añadir Pago General</h2>
<?php
if(isset($_COOKIE["login"])){
?>

<form method="POST">
    <label for="jugador_id">Jugador:</label>
    <select name="jugador_id" required>
        <?php
        foreach ($jugadores as $id => $nombre) {
            echo "<option value='$id'>" . htmlspecialchars($nombre) . "</option>";
        }
        ?>
    </select>
    <label for="cantidad">Cantidad (€):</label>
    <input type="number" step="0.01" name="cantidad" required>
    <label for="tipo">Tipo:</label>
    <select name="tipo" required>
        <option value="ha_pagado">Ha Pagado</option>
        <option value="le_han_pagado">Le Han Pagado</option>
    </select>
    <button type="submit">Añadir Pago</button>
</form>
<?php
}
// Calcular la suma de los balances actuales
$suma_balances_actuales = 0;
foreach ($balances_totales as $id => $balance) {
    $ha_pagado = isset($pagos_ha_pagado[$id]) ? $pagos_ha_pagado[$id] : 0;
    $le_han_pagado = isset($pagos_le_han_pagado[$id]) ? $pagos_le_han_pagado[$id] : 0;
    $balance_actual = $balance - $le_han_pagado + $ha_pagado;
    $suma_balances_actuales += $balance_actual;
}

// Mostrar la suma
echo "<p style='text-align: center; font-weight: bold;'>Suma total de balances actuales: " . number_format($suma_balances_actuales, 2, ',', '.') . " €</p>";
?>

<?php
abajo();
$mysqli->close();
?>
