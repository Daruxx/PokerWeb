document.addEventListener("DOMContentLoaded", function() {
    ajaxJSON();
});

function ajaxJSON() {
    let nombreABuscar = document.getElementById("nombre").value;
    var resultado = document.getElementById("divResultado");
    httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "../buscarDeportistas.php", true); 
    httpRequest.setRequestHeader("Content-type", "application/json");
    console.log(httpRequest.responseText);
    httpRequest.onreadystatechange = function () {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            var datosJSON = JSON.parse(httpRequest.responseText);
            resultado.innerHTML = ""; 
            let nombres = [];
            for (var dni in datosJSON){
                let nombreCompleto = datosJSON[dni]["Nombre"] +" " +datosJSON[dni]["Apellido_1"] + " " + datosJSON[dni]["Apellido_2"];
                if(nombreCompleto.toLocaleLowerCase().indexOf(nombreABuscar.toLocaleLowerCase()) != -1){
                    nombres.push("<a href=../consultar.php?dni="+dni+">"+ nombreCompleto + "</a>");
                }
            }
            if(nombres.length == 0){
                resultado.innerHTML = "No se han encontrado resultados para el nombre";
            }else{
                let contenido = "<h2>Se han encontrado los siguientes resultados:</h2>";
                contenido += "<ol class=listaNombres>";
                nombres.forEach(function(nombre) {
                    contenido += "<li>" + nombre + "<br></li>";
                });
                contenido += "</ol>";
                resultado.innerHTML = contenido;
            }
            
        }
    }
    httpRequest.send(null);
}