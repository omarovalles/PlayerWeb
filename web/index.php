<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../design/styleindex.css">
    <link rel="icon" href="../resources/favicon.ico" type="image/x-icon">
    <script src="../js/index.js" defer></script>
    <title>SoreWeb</title>

</head>
<body>
    <audio id="miAudio" src="../resources/deltarunelobbyost.mp3" loop></audio>
    <div class="container">
        <a class="loginlink" href="login.php">Log in</a>
        <img id="mutearMusica" class="speakerLogo" src="../resources/speaker.svg" alt="speak logo">
        <h1>Bienvenid@s a SoreWeb</h1>
        <p>Una pagina especial para mostrar mis jueguitos al mundo, saber un poco de mi y pasarnosla bien :3!</p>

    <div id="reproducirSonido" class="button"><p>Visitar pagina</p></div>
        <audio id="sonidoBoton" src="../resources/videoplayback.mp3"></audio>
    </div>

    <div class="respawnButton">
        <div class="respawnAction">
            <audio id="sonidoSeleccion" src="../resources/snd_menumove_ch1.mp3"></audio>
            <audio id="sonidoSave" src="../resources/snd_save_ch1.mp3"></audio>
            <audio id="sonidoRespawn" src="../resources/snd_select_ch1.mp3"></audio>
            <img id="respawnMenu" src="../resources/SAVE_Point_overworld.gif" alt="respawn" width="40" height="40">
        </div>
    </div>
    
    <p class="textRespawn">Puedes clickear el boton de respawn :3!</p>


    <div id="mostrarFoto" class="stalwalkerButton"></div>


</body>
</html>
