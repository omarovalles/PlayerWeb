// Inicializar sonidos globales
const audioFondo = document.getElementById("miAudio");
const sonidoBoton = document.getElementById("sonidoBoton");
const sonidoSeleccion = document.getElementById("sonidoSeleccion");
const sonidoSave = document.getElementById("sonidoSave");
const sonidoRespawn = document.getElementById("sonidoRespawn");

// Reproducir música de fondo al cargar la página
window.addEventListener("load", function () {
    audioFondo.volume = 0.03; // Ajustar volumen de la música de fondo
    audioFondo.loop = true; // Repetir música de fondo
    audioFondo.play().catch(error => {
        console.error("Error al reproducir la música de fondo:", error);
        alert("No se pudo reproducir la música de fondo. Por favor, revisa la consola para más detalles.");
    });
}); 
// Reproducir sonido y redirigir tras 2 segundos
document.getElementById('reproducirSonido').addEventListener('click', () => {
    sonidoBoton.currentTime = 0;
    sonidoBoton.play();
    setTimeout(() => {
        window.location.href = '../web/users/login.php';
    }, 2000);
});

// Opciones del menú con URLs
const options = [
    { label: "Pagina WEB", url: "../web/users/login.php" },
    { label: "Twitch", url: "https://twitch.tv/sorenine_" },
    { label: "Discord", url: "https://discord.gg/https://discord.gg/FpEQD44kyC" },
    { label: "Volver", url: null }
];
let selectedIndex = 0;

// Actualiza el texto del menú según la opción seleccionada
function updateMenuText() {
    return `SORE   LV 6   2:00:48\nQueen's Mansion - Rooftop\n\n` +
        options.map((option, index) => {
            return (index === selectedIndex ? `💚 ${option.label}` : `  ${option.label}`);
        }).join("\n");
}

// Mostrar el menú de respawn
document.getElementById("respawnMenu").addEventListener("click", function () {
    const existing = document.getElementById("saveOverlay");
    sonidoRespawn.currentTime = 0;
    sonidoRespawn.play();

    if (existing) {
        existing.remove();
        return;
    }

    const overlay = document.createElement("div");
    overlay.id = "saveOverlay";
    overlay.className = "save-overlay";

    const container = document.createElement("div");
    container.className = "save-image-container";

    const imagen = document.createElement("img");
    imagen.src = "../resources/itemtexo.png";
    imagen.alt = "Save Menu";

    const texto = document.createElement("div");
    texto.className = "save-image-text";
    texto.innerHTML = updateMenuText();

    container.appendChild(imagen);
    container.appendChild(texto);
    overlay.appendChild(container);
    document.body.appendChild(overlay);

    // Manejo de teclas del menú
    function keyHandler(e) {
        if (e.key === 'w' || e.key === 'ArrowUp') {
            selectedIndex = (selectedIndex - 1 + options.length) % options.length;
            texto.innerHTML = updateMenuText();
            sonidoSeleccion.currentTime = 0;
            sonidoSeleccion.play();
        } else if (e.key === 's' || e.key === 'ArrowDown') {
            selectedIndex = (selectedIndex + 1) % options.length;
            texto.innerHTML = updateMenuText();
            sonidoSeleccion.currentTime = 0;
            sonidoSeleccion.play();
        } else if (e.key === 'Enter') {
            overlay.remove();
            document.removeEventListener('keydown', keyHandler);
            sonidoSave.currentTime = 0;
            sonidoSave.play();

            const selectedOption = options[selectedIndex];
            if (selectedOption.url) {
                setTimeout(() => {
                    window.open(selectedOption.url, '_blank');
                }, 1000); // Esperar 1 segundo por el sonido
            }
        } else if (e.key === 'Escape') {
            overlay.remove();
            document.removeEventListener('keydown', keyHandler);
        }
    }

    document.addEventListener('keydown', keyHandler);
});

// Mostrar imagen y texto de Starwalker al hacer clic
document.getElementById("mostrarFoto").addEventListener("click", function () {
    const img = document.createElement("img");

    const cantidad = document.querySelectorAll("img[src='../resources/starwalker.gif']").length;

    if (cantidad >= 20) {
        alert("Oye tampoco te pases");
        return;
    }
    img.src = "../resources/starwalker.gif";
    img.alt = "Imagen mostrada";
    img.style.width = "100px";
    img.style.height = "auto";
    img.style.cursor = "pointer";
    document.body.appendChild(img);

    img.addEventListener("click", function () {
        const cuadroExistente = document.getElementById("cuadroStalwalker");
        if (cuadroExistente) {
            cuadroExistente.remove();
            return;
        }

        const contenedor = document.createElement("div");
        contenedor.id = "cuadroStalwalker";
        contenedor.style.position = "fixed";
        contenedor.style.bottom = "20px";
        contenedor.style.left = "50%";
        contenedor.style.transform = "translateX(-50%)";
        contenedor.style.width = "500px";
        contenedor.style.height = "200px";
        contenedor.style.zIndex = "1000";
        contenedor.style.pointerEvents = "none";
        contenedor.style.overflow = "hidden";

        const fondo = document.createElement("img");
        fondo.src = "../resources/stalwalkerCuadrodeTexto.png";
        fondo.style.width = "100%";
        fondo.style.height = "100%";
        fondo.style.position = "absolute";
        fondo.style.left = "0";
        fondo.style.top = "0";

        const texto = document.createElement("p");
        texto.innerHTML = `I'm the original ㅤㅤ <span style="color: #ffff00;">Starwalker</span>`;
        texto.style.color = "white";
        texto.style.fontSize = "20px";
        texto.style.position = "absolute";
        texto.style.zIndex = "1001";
        texto.style.top = "10px";
        texto.style.left = "50%";
        texto.style.transform = "translateX(-50%)";
        texto.style.textAlign = "center";
        texto.style.width = "100%";
        texto.style.margin = "0";
        texto.style.padding = "20px";

        contenedor.appendChild(fondo);
        contenedor.appendChild(texto);
        document.body.appendChild(contenedor);
    });
});

// Mutear o reproducir música al hacer clic en el ícono
document.getElementById("mutearMusica").addEventListener("click", function () {
    if (audioFondo.paused) {
        audioFondo.play();
        this.src = "../resources/speaker.svg";
    } else {
        audioFondo.pause();
        this.src = "../resources/muted.svg";
    }
});

