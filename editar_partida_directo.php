<?php
    include("utils.php");
    arriba("PARTIDA ACTIVA");
    echo "<script src=js/editar.js></script>";
    $conexion = conectarMysql();
    if(!isset($_COOKIE["login"])){
        echo "No puedes acceder aquí sin iniciar sesión";
        abajo();
        exit();
    }
    if(isset($_REQUEST["nombreNuevaPartida"])){
        $sql = "insert into partida(fichas_por_eur,nombre,activa) values (?,?,?)";
        $stmt = $conexion->prepare($sql);
        $activa = 1;
        $stmt->bind_param("dsi", $_REQUEST["fichasPorEur"],$_REQUEST["nombreNuevaPartida"],$activa);
        $stmt->execute();
        echo "<script>alert('creada');</script>";
        echo "<script>window.location.href='editar_partida_directo.php'</script>";
        abajo();
        exit();
    }elseif(isset($_POST['eliminarPago'])) {
    $idPago = intval($_POST['eliminarPago']);
    
    $sql = "DELETE FROM pago WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idPago);
    $stmt->execute();
    
    echo "<script>alert('DELETE FROM pago WHERE id = $idPago');</script>";
    echo "<script>window.location.href='editar_partida_directo.php?partida=" . $_POST['partida'] . "'</script>";
    abajo();
    exit();
}elseif(!isset($_REQUEST["partida"])){
        $sql = "select * from partida where activa=1";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows > 0){
            echo "<script>window.location.href='editar_partida_directo.php?partida=".$res->fetch_assoc()["id"]."'</script>";
            exit();
        }else{
            echo "No hay ninguna partida activa, si quieres crearla: ";
            echo "<form action=editar_partida_directo.php method=get>";
                echo "<label>Nombre: <input type=text name=nombreNuevaPartida></label>";
                echo "<label>Fichas por euro: <input type=number name=fichasPorEur></label>";
                echo " <input type=submit value=Crear>";
            echo "</form>";
            abajo();
            exit();
        }
    } 
    $idPartida = $_REQUEST["partida"];

    if(isset($_REQUEST["nuevoFichas"])){
            // Finalizando una partida
            $sql = "update partida set fichas_por_eur=? where id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt ->bind_param("di", $_REQUEST["nuevoFichas"],$idPartida);
            $stmt->execute();
            $strBalances = "";
            $sql = "select * from partida_jugador where id_partida = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $idPartida);
            $stmt->execute();
            $res = $stmt->get_result();
            while($row = $res->fetch_assoc()){
                if($strBalances != ""){
                    $strBalances .= ";";
                }
                $strBalances .= $row["id_jugador"]."=".$_REQUEST["finales".$row["id_jugador"]];
            }
            $sql = "update partida set fichas_finales=? where id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt ->bind_param("si", $strBalances,$idPartida);
            $stmt->execute();
            $sql = "update partida set activa=0 where id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt ->bind_param("i", $idPartida);
            $stmt->execute();




            

$strBalancesJugadores = ""; // para guardar balances

// Obtén fichasPorEur de la partida
$sql = "SELECT fichas_por_eur FROM partida WHERE id=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idPartida);
$stmt->execute();
$result = $stmt->get_result();
$fila = $result->fetch_assoc();
$fichasPorEur = $fila['fichas_por_eur'];

// Obtén todos los jugadores
$sql = "SELECT id_jugador FROM partida_jugador WHERE id_partida=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $idPartida);
$stmt->execute();
$res = $stmt->get_result();

while($jugador = $res->fetch_assoc()){
    $idJugador = $jugador['id_jugador'];

    // Suma dinero recibido de otros jugadores (tipo != banca)
    $sqlRecibido = "SELECT COALESCE(SUM(importe_en_euros),0) as totalRecibido FROM pago WHERE id_partida=? AND jugador_recibe=? AND tipo != 'banca'";
    $stmtRecibido = $conexion->prepare($sqlRecibido);
    $stmtRecibido->bind_param("ii", $idPartida, $idJugador);
    $stmtRecibido->execute();
    $resRecibido = $stmtRecibido->get_result();
    $totalRecibido = $resRecibido->fetch_assoc()['totalRecibido'];

    // Suma dinero pagado a otros jugadores (tipo != banca)
    $sqlPagado = "SELECT COALESCE(SUM(importe_en_euros),0) as totalPagado FROM pago WHERE id_partida=? AND jugador_paga=? AND tipo != 'banca'";
    $stmtPagado = $conexion->prepare($sqlPagado);
    $stmtPagado->bind_param("ii", $idPartida, $idJugador);
    $stmtPagado->execute();
    $resPagado = $stmtPagado->get_result();
    $totalPagado = $resPagado->fetch_assoc()['totalPagado'];

    // Suma dinero pagado a banca (tipo = banca)
    $sqlPagadoBanca = "SELECT COALESCE(SUM(importe_en_euros),0) as totalPagadoBanca FROM pago WHERE id_partida=? AND jugador_paga=? AND tipo = 'banca'";
    $stmtPagadoBanca = $conexion->prepare($sqlPagadoBanca);
    $stmtPagadoBanca->bind_param("ii", $idPartida, $idJugador);
    $stmtPagadoBanca->execute();
    $resPagadoBanca = $stmtPagadoBanca->get_result();
    $totalPagadoBanca = $resPagadoBanca->fetch_assoc()['totalPagadoBanca'];

    // Fichas finales desde el formulario
    $fichasFinales = floatval($_REQUEST["finales".$idJugador]);

    // Calcula balance con resta de lo pagado a banca
    $balance = $totalRecibido - $totalPagado - $totalPagadoBanca + ($fichasFinales / $fichasPorEur);
    $balance2 = $totalRecibido - $totalPagado + ($fichasFinales / $fichasPorEur);

    // Construye la cadena balances
    if($strBalancesJugadores != ""){
        $strBalancesJugadores .= ";";
    }
    $strBalancesJugadores .= $idJugador . "=" . $balance;
}

// Guarda en la tabla partida en un campo nuevo llamado "balances"
$sqlUpdateBalance = "UPDATE partida SET balances=? WHERE id=?";
$stmtUpdateBalance = $conexion->prepare($sqlUpdateBalance);
$stmtUpdateBalance->bind_param("si", $strBalancesJugadores, $idPartida);
$stmtUpdateBalance->execute();

            











            echo "Se ha finalizado la partida!";
        }else if(!isset($_REQUEST['partida'])){
        echo "Error, no hay partida ";
    }else{
        $sql = "select * from partida where id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_Param("s", $_REQUEST["partida"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $fila = $result->fetch_assoc();
            if($fila['activa'] == false){
                echo "Esta partida ya ha finalizado";
            }else{
                // Comprobaciones de acciones previas
                if(isset($_REQUEST['anadirJugador'])){
                    try{
                        $sql = "insert into partida_jugador values(?,?)";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bind_param("ii", $_REQUEST["partida"],$_REQUEST["anadirJugador"]);
                        $stmt->execute();
                        echo "<script>alert('Jugador añadido correctamente');</script>";
                        header("Location: ".$_SERVER['PHP_SELF']."?partida=".$_REQUEST["partida"]);

                    }catch(mysqli_sql_exception $e){
                    }
                }if (isset($_REQUEST['jugadorPaga'])) {
                    $importe = $_REQUEST["euros"];
                    $jugadorRecibe = $_REQUEST["jugadorRecibe"];
                    $tipo = ($jugadorRecibe == "banca") ? "banca" : "jugador";
                                
                    if ($_REQUEST["jugadorPaga"] === "TODOS" && $jugadorRecibe == "banca") {
                        // Obtener todos los jugadores de la partida
                        $sql = "SELECT id_jugador FROM partida_jugador WHERE id_partida = ?";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bind_param("i", $idPartida);
                        $stmt->execute();
                        $res = $stmt->get_result();
                    
                        while ($fila = $res->fetch_assoc()) {
                            $idJugador = $fila["id_jugador"];
                            $sqlInsert = "INSERT INTO pago(tipo, importe_en_euros, jugador_paga, id_partida) VALUES (?, ?, ?, ?)";
                            $stmtInsert = $conexion->prepare($sqlInsert);
                            $stmtInsert->bind_param("sdii", $tipo, $importe, $idJugador, $idPartida);
                            $stmtInsert->execute();
                        }
                        alert("Pagos añadidos para todos los jugadores a la banca");
                    } elseif ($_REQUEST["jugadorPaga"] === "TODOS") {
                        alert("No puedes usar TODOS si el receptor no es la banca");
                    } elseif ($jugadorRecibe != "banca") {
                        $sql = "INSERT INTO pago(tipo, importe_en_euros, jugador_paga, jugador_recibe, id_partida) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bind_param("sdiii", $tipo, $importe, $_REQUEST["jugadorPaga"], $jugadorRecibe, $idPartida);
                        $stmt->execute();
                        alert("Pago añadido correctamente");
                    } else {
                        $sql = "INSERT INTO pago(tipo, importe_en_euros, jugador_paga, id_partida) VALUES (?, ?, ?, ?)";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bind_param("sdii", $tipo, $importe, $_REQUEST["jugadorPaga"], $idPartida);
                        $stmt->execute();
                        alert("Pago añadido correctamente");
                    }
                
                    header("Location: " . $_SERVER['PHP_SELF'] . "?partida=" . $_REQUEST["partida"]);
                    exit();
}


                

                echo "<h1>Edición de la partida ".$fila["nombre"]."</h1>";
                echo "<hr>";
                $fichasPorEur = $fila["fichas_por_eur"];
                echo "<input type=hidden id=fichasPorEur value=$fichasPorEur>";
                echo "<p>Fichas por cada euro: <b>".$fichasPorEur."</b></p>";
                echo "<input id=partida type=hidden value=".$fila["id"].">";

                echo "<h3>Jugadores: </h3>";
                $sql = "select * from partida_jugador where id_partida = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("s", $fila["id"]);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    echo "<ul>";
                    while($fila = $result->fetch_assoc()){
                        $id = $fila["id_jugador"];
                        $sql = "select nombre from persona where id=?";
                        $stmt_jugadores = $conexion->prepare($sql);
                        $stmt_jugadores->bind_param("s", $id);
                        $stmt_jugadores->execute();
                        $resultado = $stmt_jugadores->get_result();
                        $jugador = $resultado->fetch_assoc();
                        echo "<li>".$jugador['nombre']."</li>";
                    }

                    echo "</ul>";
                }else{
                    echo "No hay jugadores todavía";
                }
                echo "<form id=anadirJugador>";
                echo "<select id=jugadorAAanadir>";
                $sql = "select * from persona";
                $stmt = $conexion->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                while($jugador = $result->fetch_assoc()){
                    echo "<option value=".$jugador['id'].">".$jugador["nombre"]."</option>";
                }
                echo "</select>";
                echo "<input type=submit id=anadirJugador value='Añadir jugador'>";
                echo "</form>";


                echo "<h3>Pagos: </h3>";
                
                $sql = "select * from pago where id_partida = ?";
                $stmtPago = $conexion->prepare($sql);
                $stmtPago->bind_param("s", $_REQUEST['partida']);
                $stmtPago->execute();
                $resultPago = $stmtPago->get_result();
                $fichasEsperadas = 0;
                if($resultPago->num_rows > 0){
                    echo "<ul>";
                    while ($pago = $resultPago->fetch_assoc()) {
                        if($pago["tipo"] != "banca"){
                            $idPaga = $pago["jugador_paga"];
                            $idRecibe = $pago["jugador_recibe"];
                            $importe = $pago["importe_en_euros"];
                        
                            $sqlNombre = "SELECT nombre FROM persona WHERE id = ?";
                        
                            // Obtener nombre que paga
                            $stmtNombre = $conexion->prepare($sqlNombre);
                            $stmtNombre->bind_param("i", $idPaga);
                            $stmtNombre->execute();
                            $resNombre = $stmtNombre->get_result();
                            $nombrePaga = ($resNombre->num_rows > 0) ? $resNombre->fetch_assoc()["nombre"] : "Desconocido";
                            $stmtNombre->close();
                        
                            // Obtener nombre que recibe
                            $stmtNombre = $conexion->prepare($sqlNombre);
                            $stmtNombre->bind_param("i", $idRecibe);
                            $stmtNombre->execute();
                            $resNombre = $stmtNombre->get_result();
                            $nombreRecibe = ($resNombre->num_rows > 0) ? $resNombre->fetch_assoc()["nombre"] : "Desconocido";
                            $stmtNombre->close();
                        
                            echo "<li><p><b>$nombrePaga</b> paga <b>$importe €</b> a <b>$nombreRecibe</b></p>";
                            $idPago = $pago['id']; // ID del pago

                            // Al final del <li> de cada pago:
                            echo "<form method='post' style='display:inline' onsubmit='return confirm(\"¿Eliminar este pago?\")'>";
                            echo "<input type='hidden' name='eliminarPago' value='$idPago'>";
                            echo "<input type='hidden' name='partida' value='$idPartida'>";
                            echo "<button type='submit'>❌</button>";
                            echo "</form></li>";

                        
                        } else {
                            $fichasEsperadas += $pago["importe_en_euros"]*$fichasPorEur;
                            $idPaga = $pago["jugador_paga"];
                            $importe = $pago["importe_en_euros"];
                        
                            $sqlNombre = "SELECT nombre FROM persona WHERE id = ?";
                            $stmtNombre = $conexion->prepare($sqlNombre);
                            $stmtNombre->bind_param("i", $idPaga);
                            $stmtNombre->execute();
                            $resNombre = $stmtNombre->get_result();
                            $nombrePaga = ($resNombre->num_rows > 0) ? $resNombre->fetch_assoc()["nombre"] : "Desconocido";
                            $stmtNombre->close();
                        
                            echo "<li><p><b>$nombrePaga</b> compra <b>$importe €</b> a la banca</p>";
                            echo "<form method='post' style='display:inline' onsubmit='return confirm(\"¿Eliminar este pago?\")'>";
                            echo "<input type='hidden' name='eliminarPago' value='$idPago'>";
                            echo "<input type='hidden' name='partida' value='$idPartida'>";
                            echo "<button type='submit'>❌</button>";
                            echo "</form></li>";
                        }
                    }
                    echo "<input type=hidden value=$fichasEsperadas id=fichasEsperadas>";
                    echo "</ul>";
                } else {
                    echo "No hay pagos todavía";
                }

                echo "<form id=anadirPago action=/editar_partida_directo.php>";
                echo "<input type=hidden value=".$idPartida." name=partida>";
                echo "<p>";
                echo "<select name=jugadorPaga>";
                    echo "<option value=TODOS>TODOS</option>"; 
                    $sql = "select * from partida_jugador where id_partida = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("i", $idPartida);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($jugador = $result->fetch_assoc()){
                        $sql = "select * from persona where id = ?";
                        $stmtJugador = $conexion->prepare($sql);
                        $stmtJugador->bind_param("i",$jugador["id_jugador"]);
                        $stmtJugador->execute();
                        $resultado = $stmtJugador->get_result();
                        $nombreJugador = $resultado->fetch_assoc();
                        echo "<option value=".$nombreJugador['id'].">".$nombreJugador["nombre"]."</option>";
                    }
                echo "</select>";
                echo " paga ";
                echo "<input type=number id=euros name=euros step=0.01>";
                echo " euros a ";
                echo "<select name=jugadorRecibe>";
                    echo "<option value=banca>Banca</option>";
                    $sql = "select * from partida_jugador where id_partida = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("i", $idPartida);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($jugador = $result->fetch_assoc()){
                        $sql = "select * from persona where id = ?";
                        $stmtJugador = $conexion->prepare($sql);
                        $stmtJugador->bind_param("i",$jugador["id_jugador"]);
                        $stmtJugador->execute();
                        $resultado = $stmtJugador->get_result();
                        $nombreJugador = $resultado->fetch_assoc();
                        echo "<option value=".$nombreJugador['id'].">".$nombreJugador["nombre"]."</option>";
                    }
                echo "</select>";

                echo "  <input type=submit value='Añadir pago'>";
                echo "</p>";
                echo "</form>";
                
                $sql = "select * from partida_jugador where id_partida = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("i", $idPartida);
                $stmt->execute();
                $result = $stmt->get_result();
                echo "<h3>BALANCES: </h3>";
                $balances = obtenerBalancesProvisionales($idPartida,$conexion);
                echo "<form id=fichasFinales action=editar_partida_directo.php>";
                while($jugador = $result->fetch_assoc()){
                    echo "<div class=jugador>";
                    echo "<h4>".obtenerNombrePersona($jugador["id_jugador"],$conexion)."</h4>";   
                    if(isset($balances[$jugador['id_jugador']])){
                        $balance = $balances[$jugador['id_jugador']];

                    }else{
                        $balance = 0;
                    }
                    
                    $necesarias = $balance * -1 * $fichasPorEur;
                    echo "<input type=hidden class=balanceSinFichas value=$balance>";

                    if($balance > 0 ){
                        echo "<p>Balance: <span class=verde>".$balance." €</span></p>";
                        $necesarias = $necesarias * -1;
                        echo "<p>Fichas que puede perder para estar en neutro:  $necesarias";

                    }else{
                        echo "<p>Balance: <span class=rojo>".$balance." €</span></p>";
                        echo "<p>Fichas necesarias para estar en neutro: $necesarias";
                    }
                    echo "<p id=conteo></p>";
                    echo "<label>Fichas finales: <input type=number class=fichasFinales name=finales".$jugador['id_jugador']." required></label>";


                    echo "</div>";

                }
                echo "<p id=resultado></p>";
                echo "<input type=submit value='Terminar partida'>";
                echo "<input type=hidden id=nuevoFichas name=nuevoFichas value=$fichasPorEur>";
                echo "<input type=hidden name=partida value=$idPartida>";
                echo "</form>";
            }
        }else{
            echo "Error encontrando la partida";
        }
    }
    abajo();

?>