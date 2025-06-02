document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("atras").addEventListener("click",botonAtras);
    document.getElementById("delante").addEventListener("click",botonDelante);

    document.getElementById("anadir").addEventListener("click",anadirJugador);
    document.querySelectorAll(".eliminar").forEach(function(element) {
        element.addEventListener("click", eliminarJugador);
    });

})
function botonAtras(evento){
    let indice = document.getElementById("indice").value;
    if(indice == 1){
        alert("No puedes ir más para detrás");
    }else{
        let nuevoindice = indice-1;
        window.location.href = "./consultarEquipos.php?indice="+nuevoindice;
    }
}

function botonDelante(evento){
    let indice = document.getElementById("indice").value;
    let total = document.getElementById("total").value;
    if(indice == total){
        alert("No puedes ir más para delante");
    }else{
        let nuevoindice = parseInt(indice)+1;
        window.location.href = "./consultarEquipos.php?indice="+nuevoindice;
    }
}
function anadirJugador(evento){
    let nombreEquipo = document.getElementById("nombreEquipo").textContent;
    window.location.href = "../equipos/anadirAEquipo.php?nombreEquipo="+nombreEquipo;
}
function eliminarJugador(evento){
    window.location.href = "../equipos/consultarEquipos.php?indice="+document.getElementById("indice").value+"&eliminarDelEquipo="+evento.target.value;
    alert("Eliminado correctamente");
}