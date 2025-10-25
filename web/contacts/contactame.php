<?php

require_once '../library/motor.php';
session_start();

Plantilla::aplicar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contáctame</title>
    <link rel="stylesheet" href="../design/contactame.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="contact-container">
        <h1> Contáctame</h1>
        <div class="contact-item">
            <a href="https://x.com/SoreNine_">Twitter</a>
        </div>
        <div class="contact-item">
            <a href="https://twitch.tv/sorenine_" target="_blank"> Twitch</a>
        </div>
        <div class="contact-item">
            <a href="https://discord.gg/FpEQD44kyC" target="_blank"> Discord</a>
        </div>
        <div class="contact-item">
            <a href="https://bsky.app/profile/did:plc:bfvunr6dk7fvjqo24gcr4vjk">Bluesky</a>
        </div>
    </div>

</body>
</html>
