document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("atras").addEventListener("click",botonAtras);
    document.getElementById("delante").addEventListener("click",botonDelante);
    document.getElementById("buscardni").addEventListener("click",buscarDni);
    document.getElementById("buscarnombre").addEventListener("click",buscarNombre);

})
function botonAtras(evento){
    let indice = document.getElementById("indice").value;
    if(indice == 1){
        alert("No puedes ir más para detrás");
    }else{
        let nuevoindice = indice-1;
        window.location.href = "./consultar.php?indice="+nuevoindice;
    }
}

function botonDelante(evento){
    let indice = document.getElementById("indice").value;
    let total = document.getElementById("total").value;
    if(indice == total){
        alert("No puedes ir más para delante");
    }else{
        let nuevoindice = parseInt(indice)+1;
        window.location.href = "./consultar.php?indice="+nuevoindice;
    }
}

function buscarDni(evento){
    let dni = prompt("Por favor, introduce el DNI a buscar:"); 
    if (dni) {
        // Redirigir a la página de consulta con el DNI como parámetro
        window.location.href = "./consultar.php?dni=" + dni;
    }
}


function buscarNombre(evento){
    let dni = prompt("Por favor, introduce el Nombre a buscar:"); 
    if (dni) {
        window.location.href = "./buscarNombre.php?nombre=" + dni;
    }
}