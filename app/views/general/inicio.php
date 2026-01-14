<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body { 
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); 
            height: 100vh; 
            display: flex; 
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            position: relative;
        }

        /* Contenedor Principal de Marca */
        .brand-wrapper {
            display: flex;
            align-items: center;
            background: white;
            padding: 25px 45px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            margin-bottom: 60px;
        }

        /* Espacio para el Logo */
        .brand-logo-img {
            width: 80px;
            height: 80px;
            margin-right: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f8f9fa; /* Fondo gris claro mientras no hay imagen */
            border-radius: 12px;
            overflow: hidden;
        }

        .brand-logo-img img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* Texto BiblioTech */
        .brand-text {
            font-size: 2.8rem;
            font-weight: 700;
            color: #2c3e50;
            padding-right: 25px;
            border-right: 2px solid #ddd;
        }

        .brand-text span { color: #1abc9c; }

        /* Información de la Filial */
        .brand-info {
            padding-left: 25px;
            text-align: left;
        }

        .brand-info h2 {
            font-size: 1.6rem;
            color: #34495e;
            font-weight: 600;
            line-height: 1.2;
        }

        .brand-info p {
            color: #1abc9c;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 1px;
        }

        /* Botón de Registro */
        .card-btn {
            background: white;
            padding: 60px 100px;
            border-radius: 30px;
            text-decoration: none;
            color: #2c3e50;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .card-btn:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(26, 188, 156, 0.25);
        }

        .icon-circle {
            width: 110px;
            height: 110px;
            background: #1abc9c;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 50px;
            color: white;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(26, 188, 156, 0.3);
        }

        .card-btn h3 { font-size: 2rem; margin-bottom: 8px; }

        /* Acceso Admin Oculto */
        .admin-trigger {
            position: absolute;
            bottom: 25px;
            right: 25px;
            opacity: 0.1;
            text-decoration: none;
            color: #2c3e50;
            font-size: 20px;
            transition: 0.3s;
        }

        .admin-trigger:hover { opacity: 1; transform: rotate(20deg); }
    </style>
</head>
<body>

    <div class="brand-wrapper">
        <div class="brand-logo-img">
            <img src="./public/img/logo.jpg" alt="Logo">
        </div>

        <div class="brand-text">
            Biblio<span>Tech</span>
        </div>

        <div class="brand-info">
            <h2>Biblioteca Central</h2>
            <p>FILIAL SUR</p>
        </div>
    </div>

    <a href="registrar-ingreso" class="card-btn">
        <div class="icon-circle">👤</div>
        <h3>Registrar Ingreso</h3>
        <p style="color: #95a5a6;">Marca tu asistencia con tu DNI</p>
    </a>

    <a href="login" class="admin-trigger">⚙️</a>

</body>
</html>