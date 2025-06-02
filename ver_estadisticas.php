<?php
include("utils.php");
arriba("ESTADÍSTICAS");

$conexion = conectarMysql();

// Obtener todas las partidas (activa o no)
$sql = "SELECT * FROM partida ORDER BY activa DESC, id DESC";
$stmt = $conexion->prepare($sql);
$stmt->execute();
$resultPartidas = $stmt->get_result();

while ($partida = $resultPartidas->fetch_assoc()) {
    if($partida["activa"] == true){
        continue;
    }
    $idPartida = $partida['id'];
    $nombrePartida = htmlspecialchars($partida['nombre']);
    $fichasPorEur = $partida['fichas_por_eur'];
    $fichasFinalesStr = $partida['fichas_finales']; // formato: "idJugador=fichas;..."

    echo "<h2 style='text-align:center;'>$nombrePartida</h2>";

    // Obtener jugadores de esta partida
    $sqlJugadores = "SELECT pj.id_jugador, p.nombre FROM partida_jugador pj JOIN persona p ON pj.id_jugador = p.id WHERE pj.id_partida = ?";
    $stmtJugadores = $conexion->prepare($sqlJugadores);
    $stmtJugadores->bind_param("i", $idPartida);
    $stmtJugadores->execute();
    $resultJugadores = $stmtJugadores->get_result();

    $jugadores = [];
    while ($row = $resultJugadores->fetch_assoc()) {
        $jugadores[$row['id_jugador']] = $row['nombre'];
    }

    if (count($jugadores) === 0) {
        echo "<p>No hay jugadores para esta partida.</p>";
        continue;
    }

    // Inicializar arrays para los datos
    $haPagado = [];
    $lePagan = [];
    $eurosBanca = [];
    $fichasFinales = [];

    // Parsear fichas finales si existen
    if (!empty($fichasFinalesStr)) {
        $pares = explode(";", $fichasFinalesStr);
        foreach ($pares as $par) {
            if (strpos($par, "=") !== false) {
                list($idJugadorFF, $fichas) = explode("=", $par);
                $fichasFinales[intval($idJugadorFF)] = floatval($fichas);
            }
        }
    }

    // Inicializar contadores a 0
    foreach ($jugadores as $idJ => $nombreJ) {
        $haPagado[$idJ] = 0;
        $lePagan[$idJ] = 0;
        $eurosBanca[$idJ] = 0;
        if (!isset($fichasFinales[$idJ])) $fichasFinales[$idJ] = 0;
    }

    // Obtener pagos de la partida
    $sqlPagos = "SELECT * FROM pago WHERE id_partida = ?";
    $stmtPagos = $conexion->prepare($sqlPagos);
    $stmtPagos->bind_param("i", $idPartida);
    $stmtPagos->execute();
    $resultPagos = $stmtPagos->get_result();

    while ($pago = $resultPagos->fetch_assoc()) {
        $tipo = $pago['tipo'];
        $importe = floatval($pago['importe_en_euros']);
        $jugadorPaga = $pago['jugador_paga'];

        if ($tipo !== 'banca') {
            // Pago a otro jugador
            $jugadorRecibe = $pago['jugador_recibe'];

            // Sumar a haPagado del que paga
            if (isset($haPagado[$jugadorPaga])) {
                $haPagado[$jugadorPaga] += $importe;
            }

            // Sumar a lePagan del que recibe
            if (isset($lePagan[$jugadorRecibe])) {
                $lePagan[$jugadorRecibe] += $importe;
            }
        } else {
            // Pago a banca: solo suma en eurosBanca del que paga
            if (isset($eurosBanca[$jugadorPaga])) {
                $eurosBanca[$jugadorPaga] += $importe;
            }
        }
    }

    // Ahora mostrar la tabla
    echo "<table border='1' cellspacing='0' cellpadding='5' style='width:100%; text-align:center;'>";
    echo "<thead><tr><th>Categoría</th>";
    foreach ($jugadores as $nombreJ) {
        echo "<th>$nombreJ</th>";
    }
    echo "</tr></thead>";

    // Fila HA PAGADO
    echo "<tr><td><b>HA PAGADO</b></td>";
    foreach ($jugadores as $idJ => $_) {
        echo "<td>" . number_format($haPagado[$idJ], 2) . " €</td>";
    }
    echo "</tr>";

    // Fila LE PAGAN
    echo "<tr><td><b>LE PAGAN</b></td>";
    foreach ($jugadores as $idJ => $_) {
        echo "<td>" . number_format($lePagan[$idJ], 2) . " €</td>";
    }
    echo "</tr>";

    // Fila EUROS BANCA
    echo "<tr><td><b>EUROS BANCA</b></td>";
    foreach ($jugadores as $idJ => $_) {
        echo "<td>" . number_format($eurosBanca[$idJ], 2) . " €</td>";
    }
    echo "</tr>";

    // Fila FICHAS FINALES
    echo "<tr><td><b>FICHAS FINALES</b></td>";
    foreach ($jugadores as $idJ => $_) {
        echo "<td>" . number_format($fichasFinales[$idJ], 2) . "</td>";
    }
    echo "</tr>";

    // Fila BALANCE TOTAL
    // balancePersona = dineroRecibido - dineroPagado + (fichasFinales / fichasPorEur)
    echo "<tr><td><b>BALANCE TOTAL</b></td>";
    foreach ($jugadores as $idJ => $_) {
    $balance = $lePagan[$idJ] - $haPagado[$idJ] - $eurosBanca[$idJ] + ($fichasFinales[$idJ] / $fichasPorEur);
    $color = $balance >= 0 ? "green" : "red";
    echo "<td style='color:$color; font-weight:bold;'>" . number_format($balance, 2) . " €</td>";
}
    echo "</tr>";

    echo "</table><br><br>";
}

abajo();
?>
