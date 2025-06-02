
let partida;
document.addEventListener("DOMContentLoaded",function(){
    partida = document.getElementById("partida").value;
    document.getElementById("anadirJugador").onsubmit = anadirJugador;


      const inputs = document.querySelectorAll(".fichasFinales");
    inputs.forEach(function(input) {
        input.value = "";
        input.addEventListener("change", fichasFinales);
    });
})


function fichasFinales(ev){
    let todosRellenos = true;
    const inputs = document.querySelectorAll(".fichasFinales");
    let fichasTotales=0;
    inputs.forEach(function(campo) {
        if (campo.value.trim() === "") {
            todosRellenos = false;
        }else{
            let padre = campo.parentElement.parentElement;
            let span = padre.querySelector("p>span");
            let balanceSinFichas = Number(padre.querySelector(".balanceSinFichas").value);
            let fichasPorEuro = Number(document.getElementById("fichasPorEur").value);
            let balanceFinal = balanceSinFichas + (campo.value / fichasPorEuro);
            span.textContent = balanceFinal.toFixed(2) + " €";

            if(balanceFinal > 0){
                span.className = "verde";
            }else {
                span.className = "rojo";
            }
            


            fichasTotales += Number(campo.value);
        }
    });
    if (todosRellenos) {

        let fichasEsperadas = Number(document.getElementById("fichasEsperadas").value);
        let fichasPorEur = Number(document.getElementById("fichasPorEur").value);
        eurosMetidos = fichasEsperadas / fichasPorEur;
        nuevoFichas = fichasTotales / eurosMetidos;
        if(fichasTotales == fichasEsperadas){
            document.getElementById("resultado").textContent = "El conteo da exacto!";
            document.getElementById("resultado").style.color = "green";
            document.getElementById("nuevoFichas").value = nuevoFichas;
        }else if(fichasTotales > fichasEsperadas){
            sobran = fichasTotales-fichasEsperadas;
            document.getElementById("resultado").innerHTML = "El conteo da mal, alguien se ha sumado fichas!<br>Si se termina la partida se balanceará solo y se quitará el dinero a partes iguales"
            +"<br>Sobran "+sobran+" fichas<br>Se cambiará fichas por euro a "+nuevoFichas;
            document.getElementById("nuevoFichas").value = nuevoFichas;
            
            document.getElementById("resultado").style.color = "red";
        }else{
            faltan = fichasEsperadas-fichasTotales;
            document.getElementById("resultado").innerHTML = "El conteo da mal, alguien se ha restado fichas!<br>Si se termina la partida se balanceará solo y se dará el dinero a partes iguales"
            +"<br>Faltan "+faltan+" fichas<br>Se cambiará fichas por euro a "+nuevoFichas;
            document.getElementById("nuevoFichas").value = nuevoFichas;
            document.getElementById("resultado").style.color = "brown";
        }
    }
}

function anadirJugador(ev){
    let jugador = document.getElementById("jugadorAAanadir").value;
    window.location.href = "/editar_partida_directo.php?partida="+partida+"&anadirJugador="+jugador;
    ev.preventDefault();
}