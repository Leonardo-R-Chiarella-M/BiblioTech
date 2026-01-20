<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SuperAdmin - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos base para el panel de control maestro */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7f6; display: flex; }
        
        /* Sidebar lateral oscuro */
        .sidebar { width: 260px; background: #1a1a1a; height: 100vh; color: white; padding: 20px; position: fixed; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; color: #1abc9c; letter-spacing: 1px; }
        .nav-link { display: flex; align-items: center; gap: 12px; color: #bdc3c7; text-decoration: none; padding: 12px; border-radius: 8px; margin-bottom: 10px; transition: 0.3s; }
        .nav-link:hover { background: #333; color: white; }
        .nav-link.active { background: #1abc9c; color: white; }
        
        /* Contenido principal */
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-top: 20px; }
        
        /* Tarjetas de estadísticas */
        .stat-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 20px; position: relative; overflow: hidden; }
        .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .stat-info h3 { font-size: 1.6rem; color: #2c3e50; }
        .stat-info p { color: #7f8c8d; font-size: 0.85rem; font-weight: 600; }

        /* Barra de progreso visual para ocupación */
        .progress-container { position: absolute; bottom: 0; left: 0; width: 100%; height: 5px; background: #f0f0f0; }
        .progress-bar { height: 100%; transition: 0.5s; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>SUPER ADMIN</h2>
        <a href="superadmin" class="nav-link active"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="superadmin/usuarios" class="nav-link"><i class="fas fa-users-cog"></i> Usuarios</a>
        <a href="superadmin/alumnos" class="nav-link"><i class="fas fa-user-graduate"></i> Alumnos</a>
        <a href="superadmin/cubiculos" class="nav-link"><i class="fas fa-door-open"></i> Cubículos</a>
        <a href="superadmin/computadoras" class="nav-link"><i class="fas fa-desktop"></i> Computadoras</a>
        
        <a href="logout" class="nav-link" style="margin-top:50px; color:#e74c3c;"><i class="fas fa-power-off"></i> Cerrar Sesión</a>
    </div>

    <div class="main-content">
        <h1>Panel de Control Maestro</h1>
        <p style="color:#7f8c8d; margin-bottom:30px;">Resumen general de recursos - Filial Sur</p>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e1f5fe; color: #0288d1;"><i class="fas fa-user-shield"></i></div>
                <div class="stat-info">
                    <h3><?php echo $totalUsuarios; ?></h3>
                    <p>Personal Staff</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5e9; color: #2e7d32;"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3><?php echo $totalAlumnos; ?></h3>
                    <p>Total Alumnos</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fff3e0; color: #ef6c00;"><i class="fas fa-door-open"></i></div>
                <div class="stat-info">
                    <h3><?php echo $ocupacionFiltro; ?></h3>
                    <p>Uso Cubículos</p>
                </div>
                <div class="progress-container">
                    <div class="progress-bar" style="width: <?php echo ($ocupados / $max_cubiculos) * 100; ?>%; background: #ef6c00;"></div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #ebf5fb; color: #3498db;"><i class="fas fa-laptop"></i></div>
                <div class="stat-info">
                    <h3><?php echo $ocupados_pc . " / " . $max_pcs; ?></h3>
                    <p>Uso Computadoras</p>
                </div>
                <div class="progress-container">
                    <div class="progress-bar" style="width: <?php echo ($ocupados_pc / $max_pcs) * 100; ?>%; background: #3498db;"></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>