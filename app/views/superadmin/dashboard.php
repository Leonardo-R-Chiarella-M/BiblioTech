<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel SuperAdmin - BiblioTech</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .admin-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #2c3e50; color: white; padding: 20px; }
        .content { flex: 1; padding: 40px; background: #f4f7f6; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .btn-add { background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <nav class="sidebar">
            <h2>BiblioTech</h2>
            <p>Bienvenido, <br><strong><?php echo $_SESSION['nombre']; ?></strong></p>
            <hr>
            <ul>
                <li><a href="superadmin" style="color:white">Inicio</a></li>
                <li><a href="#" style="color:white">Gestionar Bibliotecarios</a></li>
                <li><a href="#" style="color:white">Reportes Globales</a></li>
                <li><a href="logout" style="color:#e74c3c">Cerrar Sesión</a></li>
            </ul>
        </nav>

        <main class="content">
            <h1>Panel de Control (Dueño)</h1>
            
            <div class="stats-grid">
                <div class="card">
                    <h3>Bibliotecarios</h3>
                    <p style="font-size: 24px; font-weight: bold;">0</p>
                    <a href="#" class="btn-add">+ Crear Nuevo</a>
                </div>
                <div class="card">
                    <h3>Total Libros</h3>
                    <p style="font-size: 24px; font-weight: bold;">0</p>
                </div>
                <div class="card">
                    <h3>Alumnos Activos</h3>
                    <p style="font-size: 24px; font-weight: bold;">0</p>
                </div>
            </div>

            <br><br>
            <div class="card">
                <h3>Actividad Reciente</h3>
                <p>No hay acciones registradas hoy.</p>
            </div>
        </main>
    </div>
</body>
</html>