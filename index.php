<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POKEEEER</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div id="container">
        <header>
            <h1 class="titulo">POKEEEER</h1>
            <?php 
            include("utils.php");
            obtenerDivLogin();
             ?>
            <hr>
        
        </header>
        <ul id="menuPrincipal">
            <li><a href="editar_partida_directo.php">Administrar partida</a></li>
            <li><a href="ver_estadisticas.php">Ver estadísticas</a></li>
            <li><a href="ver_estadisticas_generales.php">Ver estadísticas generales</a></li>

        </ul>
        <footer>
            <hr>
            <p>&copy; POKEEER, Darío goat</p>
        </footer>
    </div>
</body>
</html>