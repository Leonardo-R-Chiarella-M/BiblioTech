<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - BiblioTech</title>
    <link rel="stylesheet" href="./public/css/login.css">
</head>
<body>

    <div class="login-card">
        <h2>BiblioTech</h2>
        <p>Introduce tus datos para ingresar</p>
        
        <form action="auth" method="POST">
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            <button type="submit" class="btn-login">INGRESAR</button>
        </form>

        <a href="inicio" class="btn-back">← Volver al catálogo público</a>
    </div>

</body>
</html>