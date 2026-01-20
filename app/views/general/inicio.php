<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            overflow: hidden;
        }

        /* Reloj Digital en Tiempo Real */
        .digital-clock {
            position: absolute;
            top: 30px;
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px 25px;
            border-radius: 50px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            letter-spacing: 2px;
        }

        /* Contenedor Principal de Marca */
        .brand-wrapper {
            display: flex;
            align-items: center;
            background: white;
            padding: 20px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            margin-bottom: 40px;
        }

        .brand-logo-img {
            width: 70px; height: 70px; margin-right: 20px;
            display: flex; justify-content: center; align-items: center;
            background: #f8f9fa; border-radius: 12px; overflow: hidden;
        }

        .brand-logo-img img { max-width: 100%; max-height: 100%; object-fit: contain; }

        .brand-text {
            font-size: 2.5rem; font-weight: 700; color: #2c3e50;
            padding-right: 20px; border-right: 2px solid #ddd;
        }
        .brand-text span { color: #1abc9c; }

        .brand-info { padding-left: 20px; text-align: left; }
        .brand-info h2 { font-size: 1.4rem; color: #34495e; font-weight: 600; line-height: 1.2; }
        .brand-info p { color: #1abc9c; font-size: 0.9rem; font-weight: 700; letter-spacing: 1px; }

        /* Contenedor de Botones */
        .options-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            max-width: 1200px;
            padding: 0 20px;
        }

        .card-btn {
            background: white;
            padding: 40px 30px;
            border-radius: 30px;
            text-decoration: none;
            color: #2c3e50;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            text-align: center;
        }

        .card-btn:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 45px rgba(0,0,0,0.15);
        }

        .icon-circle {
            width: 80px; height: 80px;
            background: #1abc9c; border-radius: 50%;
            display: flex; justify-content: center; align-items: center;
            font-size: 35px; color: white; margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(26, 188, 156, 0.3);
        }

        /* Colores diferenciados */
        .ingreso-icon { background: #1abc9c; }
        .cubiculo-icon { background: #3498db; box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3); }
        .pc-icon { background: #2c3e50; box-shadow: 0 8px 20px rgba(44, 62, 80, 0.3); }

        .card-btn h3 { font-size: 1.4rem; margin-bottom: 8px; color: #2c3e50; font-weight: 700; }
        .card-btn p { color: #95a5a6; font-size: 0.85rem; font-weight: 400; }

        .admin-trigger {
            position: absolute; bottom: 25px; right: 25px;
            opacity: 0.1; text-decoration: none; color: #2c3e50;
            font-size: 20px; transition: 0.3s;
        }
        .admin-trigger:hover { opacity: 0.8; transform: rotate(20deg); }
    </style>
</head>
<body>

    <div class="digital-clock" id="clock">00:00:00</div>

    <div class="brand-wrapper">
        <div class="brand-logo-img">
            <img src="./public/img/logo.jpg" alt="Logo">
        </div>
        <div class="brand-text">Biblio<span>Tech</span></div>
        <div class="brand-info">
            <h2>Biblioteca Central</h2>
            <p>FILIAL SUR</p>
        </div>
    </div>

    <div class="options-grid">
        <a href="registrar-ingreso" class="card-btn">
            <div class="icon-circle ingreso-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <h3>Registrar Ingreso</h3>
            <p>Asistencia general con DNI</p>
        </a>

        <a href="registro-cubiculo" class="card-btn">
            <div class="icon-circle cubiculo-icon">
                <i class="fas fa-door-open"></i>
            </div>
            <h3>Uso de Cubículos</h3>
            <p>Reserva tu espacio de estudio</p>
        </a>

        <a href="registro-pc" class="card-btn">
            <div class="icon-circle pc-icon">
                <i class="fas fa-desktop"></i>
            </div>
            <h3>Uso de Computadoras</h3>
            <p>Acceso al centro de cómputo</p>
        </a>
    </div>

    <a href="login" class="admin-trigger">
        <i class="fas fa-cog"></i>
    </a>

    <script>
        function updateClock() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>

</body>
</html>