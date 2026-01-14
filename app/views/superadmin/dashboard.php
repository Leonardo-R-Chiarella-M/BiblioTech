<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Dueño - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7f6; display: flex; min-height: 100vh; }
        
        /* Sidebar Estructurada */
        .sidebar { 
            width: 260px; background: #2c3e50; color: white; padding: 25px; 
            display: flex; flex-direction: column; position: fixed; height: 100vh;
        }
        .sidebar h2 { color: #1abc9c; text-align: center; margin-bottom: 5px; font-size: 24px; }
        .sidebar .role-tag { font-size: 11px; color: #bdc3c7; text-align: center; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 2px; }
        .sidebar ul { list-style: none; flex-grow: 1; }
        .sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 12px 15px; border-radius: 8px; transition: 0.3s; margin-bottom: 5px; }
        .sidebar a:hover, .sidebar a.active { background: #34495e; color: #1abc9c; }

        /* Botón Cerrar Sesión Estilo Solicitado */
        .btn-logout { 
            background: #e74c3c; color: white !important; text-align: center; 
            padding: 15px; border-radius: 10px; font-weight: bold; text-decoration: none;
            margin-top: auto; transition: 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-logout:hover { background: #c0392b; transform: translateY(-2px); }

        /* Contenido Principal */
        .main-content { flex-grow: 1; margin-left: 260px; padding: 40px; }
        .header { margin-bottom: 40px; }
        .header h1 { color: #2c3e50; font-size: 2rem; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
        .card { 
            background: white; padding: 35px; border-radius: 20px; text-align: center; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-top: 5px solid #1abc9c;
            transition: 0.3s;
        }
        .card:hover { transform: translateY(-10px); }
        .card h3 { color: #7f8c8d; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; }
        .card .number { font-size: 3.5rem; font-weight: 700; color: #2c3e50; margin: 15px 0; }
        .btn-card { 
            background: #1abc9c; color: white; text-decoration: none; padding: 12px 25px; 
            border-radius: 30px; font-weight: bold; display: inline-block; font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <nav class="sidebar">
        <h2>BiblioTech</h2>
        <div class="role-tag">Panel Dueño</div>
        <ul>
            <li><a href="superadmin" class="active">🏠 Dashboard</a></li>
            <li><a href="superadmin/usuarios">👤 Gestionar Personal</a></li>
            <li><a href="superadmin/alumnos">👥 Todos los Alumnos</a></li>
            <li><a href="superadmin/asistencias">📋 Reporte de Ingresos</a></li>
        </ul>
        <a href="logout" class="btn-logout">Cerrar Sesión</a>
    </nav>

    <main class="main-content">
        <div class="header">
            <h1>Bienvenido, <?php echo $_SESSION['nombre']; ?></h1>
            <p style="color: #7f8c8d;">Resumen general de la Biblioteca Filial Sur</p>
        </div>

        <div class="stats-grid">
            <div class="card">
                <h3>Personal (Admins)</h3>
                <div class="number"><?php echo $totalUsuarios; ?></div>
                <a href="superadmin/usuarios" class="btn-card">Administrar Equipo</a>
            </div>

            <div class="card">
                <h3>Alumnos Registrados</h3>
                <div class="number"><?php echo $totalAlumnos; ?></div>
                <a href="superadmin/alumnos" class="btn-card">Ver Lista Completa</a>
            </div>
        </div>
    </main>
</body>
</html>