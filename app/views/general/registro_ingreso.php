<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #1a1a2e; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .card { background: white; padding: 40px; border-radius: 25px; width: 500px; text-align: center; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        
        /* Diseño de la Ficha de Datos */
        .student-info { 
            background: #f8f9fa; border-radius: 15px; padding: 20px; 
            margin-bottom: 25px; border-left: 5px solid #1abc9c; text-align: left;
        }
        .student-info h3 { color: #1abc9c; font-size: 1.2rem; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .student-info p { margin: 5px 0; font-size: 0.95rem; color: #2c3e50; }
        .student-info strong { color: #7f8c8d; width: 80px; display: inline-block; }

        .input-dni { width: 100%; padding: 15px; font-size: 1.8rem; text-align: center; border: 2px solid #eee; border-radius: 12px; margin-bottom: 20px; outline: none; }
        .btn-ingreso { width: 100%; padding: 15px; background: #1abc9c; color: white; border: none; border-radius: 12px; font-weight: bold; cursor: pointer; font-size: 1.1rem; }
        
        .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; font-weight: bold; }
        .error { background: #fadbd8; color: #c0392b; }
    </style>
</head>
<body>
    <div class="card">
        <img src="./public/img/logo.jpg" alt="Logo" style="width: 70px; margin-bottom: 10px;">
        <h2>Biblioteca Filial Sur</h2>
        
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
    <div class="student-info">
        <h3>✅ INGRESO REGISTRADO</h3>
        <p><strong>Nombre:</strong> <?php echo htmlspecialchars($_GET['nombre']); ?></p>
        <p><strong>Carrera:</strong> <?php echo htmlspecialchars($_GET['carrera']); ?></p>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($_GET['correo']); ?></p>
        <p><strong>Celular:</strong> <?php echo htmlspecialchars($_GET['celular'] ?? '---'); ?></p>
        
        <p><strong>Estado:</strong> 
            <span style="
                padding: 4px 10px; 
                border-radius: 15px; 
                font-size: 0.85rem; 
                font-weight: bold; 
                text-transform: uppercase;
                background: <?php echo ($_GET['estado'] == 'egresado') ? '#d4e6f1; color: #2980b9;' : '#d1f2eb; color: #16a085;'; ?>
            ">
                <?php echo htmlspecialchars($_GET['estado']); ?>
            </span>
        </p>
    </div>
<?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] != 'success'): ?>
            <div class="alert error">
                <?php 
                    if($_GET['msg'] == 'blocked') echo "🚫 ACCESO DENEGADO: Alumno Inactivo";
                    if($_GET['msg'] == 'not_found') echo "❌ DNI NO REGISTRADO";
                ?>
            </div>
        <?php endif; ?>

        <form action="procesar-ingreso" method="POST">
            <input type="text" name="dni" class="input-dni" placeholder="Ingrese DNI" required autofocus>
            <button type="submit" class="btn-ingreso">MARCAR ASISTENCIA</button>
        </form>
        <a href="inicio" style="color: #95a5a6; text-decoration: none; font-size: 0.8rem;">← Volver al Inicio</a>
    </div>

    <script>
        // Si se mostraron datos, refrescar en 5 segundos para el siguiente alumno
        if(window.location.search.includes('msg=success')) {
            setTimeout(() => { window.location.href = 'registrar-ingreso'; }, 5000);
        }
    </script>
</body>
</html>