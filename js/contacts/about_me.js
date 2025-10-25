const audioFondo = document.getElementById("miAudio");

window.addEventListener("load", function () {
    audioFondo.volume = 0.03;
    audioFondo.loop = true;
    audioFondo.play().catch(error => {
        console.error("Error al reproducir la música de fondo:", error);
        alert("No se pudo reproducir la música de fondo. Por favor, revisa la consola para más detalles.");
    });
}); 

document.getElementById("mutearMusica").addEventListener("click", function () {
    if (audioFondo.paused) {
        audioFondo.play();
        this.src = "../resources/speaker.svg";
    } else {
        audioFondo.pause();
        this.src = "../resources/muted.svg";
    }
});