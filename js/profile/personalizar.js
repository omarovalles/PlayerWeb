const section = document.querySelector('.perfil-container');
const colorInput = document.getElementById('color-carta');
const backgroundFileInput = document.querySelector('input[name="background_file"]');

// Cambiar color de carta en vivo
colorInput.addEventListener('input', () => {
    section.style.backgroundColor = colorInput.value;
});

// Cambiar fondo en vivo si se sube archivo
backgroundFileInput.addEventListener('change', () => {
    const file = backgroundFileInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            section.style.backgroundImage = `url('${e.target.result}')`;
        };
        reader.readAsDataURL(file);
    }
})

document.addEventListener("DOMContentLoaded", function() {
    const colorCartaInput = document.getElementById('color-carta');
    const perfilContainer = document.querySelector('.perfil-container');
    const labels = perfilContainer.querySelectorAll('label, input, textarea, button');

    // Cambiar color de carta en tiempo real
    colorCartaInput.addEventListener('input', function() {
        perfilContainer.style.backgroundColor = colorCartaInput.value;

        // Cambiar color del texto de labels, inputs, textarea y botón
        labels.forEach(el => {
            el.style.color = colorCartaInput.value;
        });
    });
});



;

