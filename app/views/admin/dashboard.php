<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Bibliotecario - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Mismo CSS base para consistencia */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7f6; display: flex; min-height: 100vh; }
        
        .sidebar { 
            width: 260px; background: #2c3e50; color: white; padding: 25px; 
            display: flex; flex-direction: column; position: fixed; height: 100vh;
        }
        .sidebar h2 { color: #3498db; text-align: center; margin-bottom: 5px; font-size: 24px; }
        .sidebar .role-tag { font-size: 11px; color: #bdc3c7; text-align: center; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 2px; }
        .sidebar ul { list-style: none; flex-grow: 1; }
        .sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 12px 15px; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; color: #3498db; }

        .btn-logout { 
            background: #e74c3c; color: white !important; text-align: center; 
            padding: 15px; border-radius: 10px; font-weight: bold; text-decoration: none;
            margin-top: auto; transition: 0.3s;
        }

        .main-content { flex-grow: 1; margin-left: 260px; padding: 40px; }
        .header { margin-bottom: 40px; }
        
        .stats-grid { display: flex; gap: 25px; }
        .card { 
            background: white; padding: 35px; border-radius: 20px; text-align: center; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-top: 5px solid #3498db;
            width: 320px; transition: 0.3s;
        }
        .card:hover { transform: translateY(-10px); }
        .card h3 { color: #7f8c8d; font-size: 0.9rem; text-transform: uppercase; }
        .card .number { font-size: 3.5rem; font-weight: 700; color: #2c3e50; margin: 15px 0; }
        .btn-card { 
            background: #3498db; color: white; text-decoration: none; padding: 12px 25px; 
            border-radius: 30px; font-weight: bold; display: inline-block; font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <nav class="sidebar">
        <h2>BiblioTech</h2>
        <div class="role-tag">Bibliotecario</div>
        <ul>
            <li><a href="admin" class="active">🏠 Inicio</a></li>
            <li><a href="admin/alumnos">👥 Gestión Alumnos</a></li>
            <li><a href="admin/asistencias">📋 Ver Ingresos Hoy</a></li>
        </ul>
        <a href="logout" class="btn-logout">Cerrar Sesión</a>
    </nav>

    <main class="main-content">
        <div class="header">
            <h1>Panel de Control</h1>
            <p style="color: #7f8c8d;">Hola, <?php echo $_SESSION['nombre']; ?>. Gestiona el ingreso y catálogo hoy.</p>
        </div>

        <div class="stats-grid">
            <div class="card">
                <h3>Alumnos Registrados</h3>
                <div class="number"><?php echo $totalAlumnos; ?></div>
                <a href="admin/alumnos" class="btn-card">Administrar Alumnos</a>
            </div>
            
            <div class="card" style="border-top-color: #bdc3c7; opacity: 0.7;">
                <h3>Libros en Sistema</h3>
                <div class="number">0</div>
                <a href="#" class="btn-card" style="background: #95a5a6;">Ver Catálogo</a>
            </div>
        </div>
    </main>
</body>
</html>