<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bibliotecario - BiblioTech</title>
    <style>
        /* CSS IGUAL AL SUPERADMIN */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f4f7f6; display: flex; min-height: 100vh; }
        
        .sidebar { 
            width: 260px; background: #2c3e50; color: white; padding: 25px; 
            display: flex; flex-direction: column; position: fixed; height: 100vh;
        }
        .sidebar h2 { color: #1abc9c; text-align: center; margin-bottom: 5px; font-size: 26px; }
        .sidebar .role-tag { font-size: 11px; color: #bdc3c7; text-align: center; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 2px; }
        
        .sidebar ul { list-style: none; flex-grow: 1; }
        .sidebar li { margin-bottom: 8px; }
        .sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 12px 15px; border-radius: 8px; transition: 0.3s; }
        .sidebar a:hover { background: #34495e; color: #3498db; }

        .btn-logout { 
            background: #e74c3c; color: white !important; text-align: center; 
            padding: 15px; border-radius: 10px; font-weight: bold; text-decoration: none;
            margin-top: auto; transition: 0.3s;
        }
        .btn-logout:hover { background: #c0392b; }

        .main-content { flex-grow: 1; margin-left: 260px; padding: 40px; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); text-align: center; border-top: 5px solid #3498db; width: 300px; }
        .card .number { font-size: 54px; font-weight: 800; color: #2c3e50; margin: 15px 0; }
        .btn-manage { background: #3498db; color: white; text-decoration: none; padding: 12px 25px; border-radius: 30px; font-weight: bold; display: inline-block; }
    </style>
</head>
<body>
    <nav class="sidebar">
        <h2>BiblioTech</h2>
        <div class="role-tag">Panel Bibliotecario</div>
        <ul>
            <li><a href="admin">🏠 Inicio</a></li>
            <li><a href="admin/alumnos">👥 Gestión Alumnos</a></li>
            <li><a href="#">📚 Libros</a></li>
        </ul>
        <a href="logout" class="btn-logout">Cerrar Sesión</a>
    </nav>

    <main class="main-content">
        <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1><br>
        <div class="card">
            <h3>Alumnos Registrados</h3>
            <div class="number"><?php echo $totalAlumnos; ?></div>
            <a href="admin/alumnos" class="btn-manage">Ver Alumnos</a>
        </div>
    </main>
</body>
</html>