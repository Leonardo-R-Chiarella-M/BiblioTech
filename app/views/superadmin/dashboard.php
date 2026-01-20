<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>SuperAdmin - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Estilos base idénticos al admin para mantener consistencia */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7f6; display: flex; }
        .sidebar { width: 260px; background: #1a1a1a; height: 100vh; color: white; padding: 20px; position: fixed; }
        .nav-link { display: flex; align-items: center; gap: 10px; color: #bdc3c7; text-decoration: none; padding: 12px; border-radius: 8px; margin-bottom: 10px; }
        .nav-link.active { background: #1abc9c; color: white; }
        .main-content { margin-left: 260px; width: calc(100% - 260px); padding: 40px; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .stat-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 20px; }
        .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 style="text-align:center; margin-bottom:30px;">SUPER ADMIN</h2>
        <a href="superadmin" class="nav-link active"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="superadmin/usuarios" class="nav-link"><i class="fas fa-users-cog"></i> Usuarios</a>
        <a href="superadmin/alumnos" class="nav-link"><i class="fas fa-user-graduate"></i> Alumnos</a>
        <a href="superadmin/cubiculos" class="nav-link"><i class="fas fa-door-open"></i> Cubículos</a>
        <a href="logout" class="nav-link" style="margin-top:50px; color:#e74c3c;"><i class="fas fa-power-off"></i> Cerrar Sesión</a>
    </div>

    <div class="main-content">
        <h1>Panel de Control Maestro</h1>
        <p style="color:#7f8c8d; margin-bottom:30px;">Estado global del sistema BiblioTech</p>

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
                <div class="stat-icon" style="background: #fff3e0; color: #ef6c00;"><i class="fas fa-th-large"></i></div>
                <div class="stat-info">
                    <h3><?php echo $ocupacionFiltro; ?></h3>
                    <p>Ocupación Cubículos</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>