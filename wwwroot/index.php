<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="favicon.ico?v=2">
    <title>Abgabesystem</title>
</head>
<body>
<noscript>
    Diese Anwendung benötigt JavaScript.
</noscript>
<script>
    window.config = <?php
        require_once('../backend/frontend_config.php');
        echo getFrontendConfigAsJSonString();
    ?>;
</script>
<div id="root"></div>
<script src="app.js"></script>
</body>
</html>
