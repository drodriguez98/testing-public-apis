<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Búsqueda MediaWiki API</title>
    </head>
    <body>
        <h1>Buscar en Wikipedia</h1>
        <form method="POST" action="">
            <label for="searchTerm">Término de búsqueda:</label>
            <input type="text" id="searchTerm" name="searchTerm" required>
            <button type="submit">Buscar</button>
        </form>
    </body>
</html>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchTerm'])) {
        $searchTerm = urlencode($_POST['searchTerm']);
        $apiUrl = "https://es.wikipedia.org/w/api.php?action=query&list=search&srsearch=" . $searchTerm . "&format=json";
        $response = file_get_contents($apiUrl);
        if ($response === FALSE) {
            echo 'Error en la solicitud.';
            exit;
        }
        $data = json_decode($response, true);
        function clean_html($text) {
            return strip_tags($text);
        }
        if (isset($data['query']['search'])) {
            echo "<h2>Resultados para: " . htmlspecialchars($_POST['searchTerm']) . "</h2>";
            echo "<ul>";
            foreach ($data['query']['search'] as $item) {
                $title = clean_html($item['title']);
                $snippet = clean_html($item['snippet']);
                echo "<li><strong>" . htmlspecialchars($title) . ":</strong> " . htmlspecialchars($snippet) . "...</li>";
            }
            echo "</ul>";
        } else {
            echo "No se encontraron resultados para tu búsqueda.";
        }
    }
?>