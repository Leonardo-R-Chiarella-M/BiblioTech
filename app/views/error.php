<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Error de Sistema - BiblioTech</title>
    <style>
        .debug-box { background: #fff3cd; border: 1px solid #ffeeba; padding: 15px; margin-top: 20px; color: #856404; font-family: monospace; }
    </style>
</head>
<body>
    <h1>¡Ups! Página no encontrada</h1>
    <p>El sistema no pudo encontrar la sección solicitada.</p>

    <div class="debug-box">
        <strong>Datos para el desarrollador:</strong><br>
        Ruta solicitada: <?php echo $_GET['url'] ?? 'Ninguna'; ?><br>
        Directorio actual: <?php echo __DIR__; ?>
    </div>

    <br>
    <a href="inicio">Volver al inicio</a>
</body>
</html>