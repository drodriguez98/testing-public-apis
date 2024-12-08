<?php
    if (isset($_GET['artist']) && isset($_GET['song'])) {
        $artist = urlencode($_GET['artist']);
        $song = $_GET['song'];
        $songDecoded = urldecode($song);
        $url = "https://api.lyrics.ovh/v1/$artist/$song";
        $response = file_get_contents($url);
        if ($response === FALSE) {
            die('Error al hacer la solicitud a la API. Verifica que la URL esté correctamente construida.');
        }
        $data = json_decode($response, true);
        if (isset($data['lyrics'])) {
            $lyrics = $data['lyrics'];
        } else {
            $lyrics = null;
            $errorMessage = "No se encontraron letras para esta canción.";
        }
    } else {
        $lyrics = null;
        $errorMessage = null;
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Buscar Letra de Canción</title>
    </head>
    <body>
        <h1>Buscar Letra de Canción</h1>
        <form method="get" action="">
            <label for="artist">Artista:</label>
            <input type="text" name="artist" id="artist" required>
            <br><br>
            <label for="song">Canción:</label>
            <input type="text" name="song" id="song" required>
            <br><br>
            <input type="submit" value="Buscar Letra">
        </form>
        <?php if ($lyrics !== null): ?>
            <h1>Letra de la canción: <?php echo htmlspecialchars($songDecoded); ?></h1>
            <pre><?php echo htmlspecialchars($lyrics); ?></pre>
        <?php elseif ($errorMessage !== null): ?>
            <h1><?php echo htmlspecialchars($errorMessage); ?></h1>
        <?php endif; ?>
    </body>
</html>