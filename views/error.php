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
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Oeps! Er is iets misgegaan.</h1>
        <p><?= htmlspecialchars($errorMessage) ?></p>
        <p><a href="/">Terug naar het overzicht</a></p>
    </div>
</body>
</html>