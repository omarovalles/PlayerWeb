<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Diálogo Deltarune</title>
  <script src="../js/website.js" defer></script>
  <link rel="stylesheet" href="../design/about_me.css">
</head>
<body>
<audio id="miAudio" src="../resources/startmenu.mp3" loop></audio>
<div class="container">
  <div class="dialog-box" id="dialog"></div>
  <button class="next-button" id="nextBtn">Volver al inicio</button>
</div>
<script>
  const dialog = document.getElementById('dialog');
  const nextBtn = document.getElementById('nextBtn');

  const texto = "Hola, soy Sore! Soy una chica trans súper fan de los videojuegos, mis favoritos siendo los indies y shooters, siendo mis favoritos celeste, borderlands y deltarune! \n\nAmo a los animales en general, son mi cosa favorita en el mundo! Aunque tengo cierta debilidad a los gatos, no es mi culpa que sean tan cool\n\nMe encanta aprender y discutir sobre cualquier tema relacionado con la ciencia, especialmente medicina veterinaria\n\n¿Estás listo para continuar?";
  let i = 0;

  function escribirTexto() {
    if (i < texto.length) {
      dialog.textContent += texto.charAt(i);
      i++;
      setTimeout(escribirTexto, 30); // velocidad de letras
    } else {
      nextBtn.style.display = 'inline-block';
    }
  }

  escribirTexto();

  nextBtn.onclick = () => {
    window.location.href = 'website.php';
  };
</script>

</body>
</html>
