<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel SuperAdmin - BiblioTech</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        .admin-wrapper { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: #1e272e; color: white; padding: 25px; }
        .sidebar h2 { margin-bottom: 30px; color: #34e7e4; text-align: center; }
        .sidebar ul { list-style: none; }
        .sidebar li { margin: 15px 0; }
        .sidebar a { color: #d2dae2; text-decoration: none; display: block; padding: 10px; border-radius: 5px; transition: 0.3s; }
        .sidebar a:hover { background: #485460; color: white; }
        .content { flex: 1; padding: 40px; background: #f1f2f6; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-top: 30px; }
        .card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; }
        .card h3 { color: #576027; margin-bottom: 15px; }
        .btn-add { background: #05c46b; color: white; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block; margin-top: 15px; }
        .btn-add:hover { background: #0be881; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <h2>BiblioTech</h2>
            <p>Bienvenido, <br><strong><?php echo $_SESSION['nombre']; ?></strong></p>
            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #485460;">
            <ul>
                <ul>
    <li><a href="superadmin">🏠 Dashboard</a></li>
    <li><a href="superadmin/nuevo">👤 Crear Usuario</a></li>
    <li><a href="superadmin/usuarios">📋 Lista de Usuarios</a></li> <li><a href="logout">🚪 Cerrar Sesión</a></li>
</ul>
                
            </ul>
        </aside>

        <main class="content">
    <h1>Panel de Control (Dueño)</h1>
    
    <div class="stats-grid">
        <div class="card">
            <h3>Usuarios Registrados</h3>
            <p style="font-size: 48px; color: #1e272e; font-weight: bold; margin: 10px 0;">
                <?php echo $totalUsuarios; ?>
            </p>
            <p style="color: #7f8c8d;">Personal administrativo activo</p>
            </div>

        <div class="card">
            <h3>Total Libros</h3>
            <p style="font-size: 48px; color: #1e272e; font-weight: bold; margin: 10px 0;">0</p>
            <p style="color: #7f8c8d;">Libros en catálogo</p>
        </div>

        <div class="card">
            <h3>Alumnos</h3>
            <p style="font-size: 48px; color: #1e272e; font-weight: bold; margin: 10px 0;">0</p>
            <p style="color: #7f8c8d;">Lectores registrados</p>
        </div>
    </div>
</main>
    </div>
</body>
</html>