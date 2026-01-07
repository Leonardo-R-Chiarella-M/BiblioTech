<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dueño - BiblioTech</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f4f7f6; display: flex; min-height: 100vh; }
        
        /* Sidebar con Flexbox para empujar el botón al fondo */
        .sidebar { 
            width: 260px; 
            background: #2c3e50; 
            color: white; 
            padding: 25px; 
            display: flex; 
            flex-direction: column; /* Alineación vertical */
            position: fixed;
            height: 100vh;
        }

        .sidebar h2 { color: #1abc9c; text-align: center; margin-bottom: 5px; font-size: 26px; }
        .sidebar .role-tag { font-size: 11px; color: #bdc3c7; text-align: center; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 2px; }
        
        .sidebar ul { list-style: none; flex-grow: 1; } /* flex-grow hace que ocupe el espacio disponible */
        .sidebar li { margin-bottom: 8px; }
        .sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 12px 15px; border-radius: 8px; transition: 0.3s; }
        .sidebar a:hover { background: #34495e; color: #1abc9c; }

        /* Botón Cerrar Sesión Estilo Imagen */
        .btn-logout { 
            background: #e74c3c; 
            color: white !important; 
            text-align: center; 
            padding: 15px; 
            border-radius: 10px; 
            font-weight: bold; 
            text-decoration: none;
            margin-top: auto; /* Empuja el botón al final del contenedor flex */
            transition: 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-logout:hover { background: #c0392b; transform: translateY(-2px); }

        .main-content { flex-grow: 1; margin-left: 260px; padding: 40px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); text-align: center; border-top: 5px solid #1abc9c; }
        .card .number { font-size: 54px; font-weight: 800; color: #2c3e50; margin: 15px 0; }
        .btn-manage { background: #1abc9c; color: white; text-decoration: none; padding: 12px 25px; border-radius: 30px; font-weight: bold; display: inline-block; font-size: 13px; }
    </style>
</head>
<body>
    <nav class="sidebar">
        <h2>BiblioTech</h2>
        <div class="role-tag">Panel del Dueño</div>
        <ul>
            <li><a href="superadmin">🏠 Dashboard</a></li>
            <li><a href="superadmin/usuarios">👤 Gestionar Personal</a></li>
            <li><a href="superadmin/alumnos">👥 Todos los Alumnos</a></li>
        </ul>
        <a href="logout" class="btn-logout">Cerrar Sesión</a>
    </nav>

    <main class="main-content">
        <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1><br>
        <div class="stats-grid">
            <div class="card">
                <h3>Personal Activo</h3>
                <div class="number"><?php echo $totalUsuarios; ?></div>
                <a href="superadmin/usuarios" class="btn-manage">Ver Personal</a>
            </div>
            <div class="card">
                <h3>Alumnos Totales</h3>
                <div class="number"><?php echo $totalAlumnos; ?></div>
                <a href="superadmin/alumnos" class="btn-manage">Ver Alumnos</a>
            </div>
        </div>
    </main>
</body>
</html>