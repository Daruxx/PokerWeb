function crearTextInput(labelText, id) {
    let label = document.createElement("label");
    label.setAttribute("for", id);
    label.appendChild(document.createTextNode(labelText + ": ")); // Le añado : y espacio sin usar innerHTML
    let input = document.createElement("input");
    input.setAttribute("type", "text");
    input.setAttribute("name", id);
    input.setAttribute("id", id);
    label.appendChild(input);
    label.appendChild(document.createElement("br"));
    return label;
}

document.addEventListener("DOMContentLoaded", function() {
    let container = document.getElementById("container");

    let form = document.createElement("form");
    form.setAttribute("action", "insertar.php");
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data"); // para que funcione la foto


    let fieldsetPersonal = document.createElement("fieldset");
    let legendPersonal = document.createElement("legend");
    legendPersonal.appendChild(document.createTextNode("Datos personales"));
    fieldsetPersonal.appendChild(legendPersonal);


    // DNI
    fieldsetPersonal.appendChild(crearTextInput("DNI", "dni"));

    // Nombre
    fieldsetPersonal.appendChild(crearTextInput("Nombre", "nombre"));

    // Primer Apellido
    fieldsetPersonal.appendChild(crearTextInput("Primer Apellido", "primer_apellido"));

    // Segundo Apellido
    fieldsetPersonal.appendChild(crearTextInput("Segundo Apellido", "segundo_apellido"));

    // Fecha de Nacimiento
    let labelFechaNacimiento = document.createElement("label");
    labelFechaNacimiento.setAttribute("for", "fecha_nacimiento");
    labelFechaNacimiento.appendChild(document.createTextNode("Fecha de Nacimiento: "));
    let inputFechaNacimiento = document.createElement("input");
    inputFechaNacimiento.setAttribute("type", "date");
    inputFechaNacimiento.setAttribute("name", "fecha_nacimiento");
    inputFechaNacimiento.setAttribute("id", "fecha_nacimiento");
    labelFechaNacimiento.appendChild(inputFechaNacimiento);
    labelFechaNacimiento.appendChild(document.createElement("br"));
    fieldsetPersonal.appendChild(labelFechaNacimiento);

    
    // Email
    let labelEmail = document.createElement("label");
    labelEmail.setAttribute("for", "email");
    labelEmail.appendChild(document.createTextNode("Email: "));
    let inputEmail = document.createElement("input");
    inputEmail.setAttribute("type", "email");
    inputEmail.setAttribute("name", "email");
    inputEmail.setAttribute("id", "email");
    labelEmail.appendChild(inputEmail);
    labelEmail.appendChild(document.createElement("br"));
    fieldsetPersonal.appendChild(labelEmail);

    form.appendChild(fieldsetPersonal);

    let fieldsetDeportivos = document.createElement("fieldset");
    let legendDeportivos = document.createElement("legend");
    legendDeportivos.appendChild(document.createTextNode("Datos deportivos"));
    fieldsetDeportivos.appendChild(legendDeportivos);

    form.appendChild(fieldsetDeportivos);

    container.insertBefore(form, container.querySelector("footer"));

    // Selección
    let labelPais = document.createElement("label");
    labelPais.setAttribute("for", "pais");
    labelPais.appendChild(document.createTextNode("Selección: "));
    let selectPais = document.createElement("select");
    selectPais.setAttribute("name", "pais");
    selectPais.setAttribute("id", "pais");

    // Input de búsqueda
    let inputBuscar = document.createElement("input");
    inputBuscar.setAttribute("type", "text");
    inputBuscar.setAttribute("placeholder", "Buscar país...");
    inputBuscar.setAttribute("id", "buscar_pais");

    // Lista de países (sacada de internet)
    let paises = [
        "Afganistán", "Albania", "Alemania", "Andorra", "Angola", "Antigua y Barbuda", "Arabia Saudita", 
        "Argelia", "Argentina", "Armenia", "Australia", "Austria", "Azerbaiyán", "Bahamas", "Bangladés", 
        "Barbados", "Baréin", "Bélgica", "Belice", "Benín", "Bielorrusia", "Birmania", "Bolivia", 
        "Bosnia y Herzegovina", "Botsuana", "Brasil", "Brunéi", "Bulgaria", "Burkina Faso", "Burundi", 
        "Bután", "Cabo Verde", "Camboya", "Camerún", "Canadá", "Catar", "Chad", "Chile", "China", 
        "Chipre", "Colombia", "Comoras", "Corea del Norte", "Corea del Sur", "Costa de Marfil", 
        "Costa Rica", "Croacia", "Cuba", "Dinamarca", "Dominica", "Ecuador", "Egipto", "El Salvador", 
        "Emiratos Árabes Unidos", "Eritrea", "Eslovaquia", "Eslovenia", "España", "Estados Unidos", 
        "Estonia", "Esuatini", "Etiopía", "Filipinas", "Finlandia", "Fiyi", "Francia", "Gabón", 
        "Gambia", "Georgia", "Ghana", "Granada", "Grecia", "Guatemala", "Guyana", "Guinea", 
        "Guinea-Bisáu", "Guinea Ecuatorial", "Haití", "Honduras", "Hungría", "India", "Indonesia", 
        "Irak", "Irán", "Irlanda", "Islandia", "Islas Marshall", "Islas Salomón", "Israel", "Italia", 
        "Jamaica", "Japón", "Jordania", "Kazajistán", "Kenia", "Kirguistán", "Kiribati", "Kuwait", 
        "Laos", "Lesoto", "Letonia", "Líbano", "Liberia", "Libia", "Liechtenstein", "Lituania", 
        "Luxemburgo", "Madagascar", "Malasia", "Malaui", "Maldivas", "Malí", "Malta", "Marruecos", 
        "Mauricio", "Mauritania", "México", "Micronesia", "Moldavia", "Mónaco", "Mongolia", "Montenegro", 
        "Mozambique", "Namibia", "Nauru", "Nepal", "Nicaragua", "Níger", "Nigeria", "Noruega", 
        "Nueva Zelanda", "Omán", "Países Bajos", "Pakistán", "Palaos", "Panamá", "Papúa Nueva Guinea", 
        "Paraguay", "Perú", "Polonia", "Portugal", "Reino Unido", "República Centroafricana", 
        "República Checa", "República del Congo", "República Democrática del Congo", "República Dominicana", 
        "Ruanda", "Rumanía", "Rusia", "Samoa", "San Cristóbal y Nieves", "San Marino", "San Vicente y las Granadinas", 
        "Santa Lucía", "Santo Tomé y Príncipe", "Senegal", "Serbia", "Seychelles", "Sierra Leona", 
        "Singapur", "Siria", "Somalia", "Sri Lanka", "Sudáfrica", "Sudán", "Sudán del Sur", "Suecia", 
        "Suiza", "Surinam", "Tailandia", "Tanzania", "Tayikistán", "Timor Oriental", "Togo", "Tonga", 
        "Trinidad y Tobago", "Túnez", "Turkmenistán", "Turquía", "Tuvalu", "Ucrania", "Uganda", 
        "Uruguay", "Uzbekistán", "Vanuatu", "Vaticano", "Venezuela", "Vietnam", "Yemen", "Yibuti", 
        "Zambia", "Zimbabue"
    ];

    // Función para actualizar las opciones del select
    function actualizarOpciones(filtro) {
        while (selectPais.firstChild) {
            selectPais.removeChild(selectPais.firstChild);
        }
        paises
            .filter(pais => pais.toLowerCase().includes(filtro.toLowerCase()))
            .forEach(function(pais) {
                let option = document.createElement("option");
                option.setAttribute("value", pais);
                option.appendChild(document.createTextNode(pais));
                selectPais.appendChild(option);
            });
    }

    // Inicializar el select con todas las opciones, sino no sale
    actualizarOpciones("");

    // Evento para filtrar opciones al escribir en el input
    inputBuscar.addEventListener("input", function() {
        actualizarOpciones(inputBuscar.value);
    });

    fieldsetDeportivos.appendChild(labelPais);
    fieldsetDeportivos.appendChild(inputBuscar);
    fieldsetDeportivos.appendChild(selectPais);
    fieldsetDeportivos.appendChild(document.createElement("br"));


    // Select de deportes
    let labelDeporte = document.createElement("label");
    labelDeporte.setAttribute("for", "deporte");
    labelDeporte.appendChild(document.createTextNode("Deporte: "));
    let selectDeporte = document.createElement("select");
    selectDeporte.setAttribute("name", "deporte");
    selectDeporte.setAttribute("id", "deporte");

    // Lista de deportes
    let deportes = [
        "Fútbol", "Baloncesto", "Tenis", "Natación", "Atletismo", 
        "Voleibol", "Ciclismo", "Boxeo", "Golf", "Voley"
    ];

    // Añadir opciones al select
    deportes.forEach(function(deporte) {
        let option = document.createElement("option");
        option.setAttribute("value", deporte);
        option.appendChild(document.createTextNode(deporte));
        selectDeporte.appendChild(option);
    });

    fieldsetDeportivos.appendChild(labelDeporte);
    fieldsetDeportivos.appendChild(selectDeporte);

    fieldsetDeportivos.appendChild(document.createElement("br"));

    // Input de Foto
    let labelFoto = document.createElement("label");
    labelFoto.setAttribute("for", "foto");
    labelFoto.appendChild(document.createTextNode("Foto: "));
    let inputFoto = document.createElement("input");
    inputFoto.setAttribute("type", "file");
    inputFoto.setAttribute("name", "foto");
    inputFoto.setAttribute("required", "required");
    inputFoto.setAttribute("id", "foto");
    labelFoto.appendChild(inputFoto);
    fieldsetDeportivos.appendChild(labelFoto);


    fieldsetDeportivos.appendChild(document.createElement("br"));
    
    // Botón para añadir deportista
    let btnAnadirDeportista = document.createElement("button");
    btnAnadirDeportista.setAttribute("type", "submit"); // Cambiado a "submit"
    btnAnadirDeportista.setAttribute("id", "btn_anadir_deportista");
    btnAnadirDeportista.appendChild(document.createTextNode("Añadir Deportista"));


    form.appendChild(btnAnadirDeportista);

    form.addEventListener("submit", comprobarFormulario);
    /*
        Ejecuto un for que se ejecuta cada elemento del formulario,
        y compruebo si está vacío, después compruebo que el DNI
        tenga el formato correcto.
    */
    function comprobarFormulario(event) {
        for (let i = 0; i < form.elements.length; i++) {
            let element = form.elements[i];
            if (element.tagName.toLowerCase() === "input" && !element.value.trim()) {
                if(element.id !== "buscar_pais"){ // El buscar pais si puede estar vacio
                    event.preventDefault();
                    alert(`El campo ${element.id} no puede estar vacío.`);
                    return;
                }
                
            }
        }

        let dni = form.querySelector("#dni").value.trim();
        let regexDNI = /^\d{8}[A-Za-z]$/;
        if (!regexDNI.test(dni)) {
            event.preventDefault();
            alert("El DNI debe tener 8 números seguidos de una letra.");
            return;
        }
    }

});

