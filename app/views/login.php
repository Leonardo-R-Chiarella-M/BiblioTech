<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { 
            background: linear-gradient(135deg, #2c3e50 0%, #1a1a2e 100%); 
            height: 100vh; display: flex; justify-content: center; align-items: center; padding: 20px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95); padding: 40px; border-radius: 25px;
            width: 100%; max-width: 400px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); text-align: center;
        }
        .login-card img { width: 80px; margin-bottom: 20px; border-radius: 12px; }
        .login-card h2 { color: #2c3e50; font-size: 1.8rem; margin-bottom: 10px; }
        .login-card p { color: #7f8c8d; margin-bottom: 30px; }
        input {
            width: 100%; padding: 12px 15px; border: 2px solid #eee; border-radius: 10px;
            margin-bottom: 20px; outline: none; transition: 0.3s; font-size: 1rem;
        }
        input:focus { border-color: #1abc9c; }
        .btn-login {
            width: 100%; padding: 14px; background: #1abc9c; color: white; border: none;
            border-radius: 10px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: 0.3s;
        }
        .btn-login:hover { background: #16a085; transform: translateY(-2px); }
        .error-msg { background: #fadbd8; color: #c0392b; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; }
        .btn-back { display: inline-block; margin-top: 25px; color: #95a5a6; text-decoration: none; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="login-card">
        <img src="./public/img/logo.jpg" alt="Logo">
        <h2>BiblioTech</h2>
        <p>Introduce tus datos para ingresar</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg">⚠️ Correo o contraseña incorrectos.</div>
        <?php endif; ?>

        <form action="auth" method="POST">
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            <button type="submit" class="btn-login">INGRESAR</button>
        </form>

        <a href="inicio" class="btn-back">← Volver al catálogo público</a>
    </div>
</body>
</html>