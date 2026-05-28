<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Foutmelding</title>
    <style>
        body { font-family: sans-serif; padding: 50px; text-align: center; color: #333; }
        .error-container { border: 1px solid #ddd; padding: 20px; display: inline-block; border-radius: 8px; max-width: 500px; }
        h1 { color: #e44d26; }
        p { color: #666; }
        .status-code { font-size: 1.2em; font-weight: bold; color: #999; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="status-code">HTTP <?= (int)$statusCode ?></div>
        <h1>Oeps! Er is iets misgegaan.</h1>
        <p><?= htmlspecialchars($errorMessage) ?></p>
        <p><a href="/">Terug naar het overzicht</a></p>
    </div>
</body>
</html>