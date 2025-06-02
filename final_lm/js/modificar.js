document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("formModificar").addEventListener("submit", comprobarFormulario);
});


function comprobarFormulario(evento){
    // Como todos tienen el campo required, no hace falta comprobarlo
    // Como el email y la fecha tienen tipo, tampoco hace falta comprobarlo



    // Comprobar que el DNI tiene 8 caracteres y el último es una letra
    let dni = document.getElementById("dni").value;
    let regexDNI = /^\d{8}[A-Za-z]$/;
    if (!regexDNI.test(dni)) {
        evento.preventDefault();
        alert("El DNI debe tener 8 números seguidos de una letra.");
        evento.preventDefault();
    }
}