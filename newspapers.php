<?php
    $searchQuery = isset($_GET['query']) ? $_GET['query'] : null;
    $data = null;
    if ($searchQuery) {
        $apiUrl = "https://chroniclingamerica.loc.gov/search/titles/results/?terms=" . urlencode($searchQuery) . "&format=json";
        $response = file_get_contents($apiUrl);
        if ($response === FALSE) {
            die("Error al realizar la solicitud a la API.");
        }
        $data = json_decode($response, true);
        if (!isset($data['items']) || empty($data['items'])) {
            $data = null;
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Buscar Periódicos</title>
    </head>
    <body>
        <h1>Buscar Periódicos</h1>
        <form method="get" action="">
            <label for="query">Término de búsqueda:</label>
            <input type="text" id="query" name="query" value="<?php echo htmlspecialchars($searchQuery); ?>" required>
            <button type="submit">Buscar</button>
        </form>
        <?php if ($searchQuery): ?>
            <h2>Resultados para: "<?php echo htmlspecialchars($searchQuery); ?>"</h2>
            <?php if ($data): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>LCCN</th>
                            <th>URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['items'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo htmlspecialchars($item['lccn']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($item['url']); ?>" target="_blank">Ver detalles</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron resultados para "<?php echo htmlspecialchars($searchQuery); ?>".</p>
            <?php endif; ?>
        <?php endif; ?>
    </body>
</html>