<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Bibliotecario - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7f6; }
        .nav-top { background: #fff; padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .container { max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        
        /* Banner de Bienvenida */
        .welcome-box { background: #1abc9c; color: white; padding: 40px; border-radius: 20px; margin-bottom: 30px; }
        
        /* Cuadrícula de Estadísticas */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 15px; position: relative; }
        .stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .stat-info h3 { font-size: 1.5rem; color: #2c3e50; }
        .stat-info p { color: #7f8c8d; font-size: 0.8rem; font-weight: 600; }
        
        /* Botones Principales */
        .grid-admin { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; }
        .card-btn { background: white; padding: 30px; border-radius: 20px; text-decoration: none; color: #2c3e50; transition: 0.3s; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid transparent; }
        .card-btn:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); border-color: #1abc9c; }
        .card-btn i { font-size: 3rem; color: #1abc9c; margin-bottom: 15px; }
        .card-btn p { color: #7f8c8d; font-size: 0.9rem; margin-top: 5px; }
    </style>
</head>
<body>
    <div class="nav-top">
        <div style="font-weight: bold; font-size: 1.2rem; color: #1abc9c;">
            <i class="fas fa-university"></i> BiblioTech Filial Sur
        </div>
        <a href="logout" style="color: #e74c3c; text-decoration: none; font-weight: bold;">
            Salir <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>

    <div class="container">
        <div class="welcome-box">
            <h1>Bienvenido, <?php echo explode(' ', $_SESSION['nombre'])[0]; ?></h1>
            <p>Sede Filial Sur - Módulo de Gestión Bibliotecaria</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f4fd; color: #3498db;">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3><?php echo $totalAlumnos; ?></h3>
                    <p>Total Alumnos</p>
                </div>
            </div>

            <div class="stat-card" style="flex-wrap: wrap;">
                <div style="display: flex; align-items: center; gap: 15px; width: 100%;">
                    <div class="stat-icon" style="background: #fdf2e9; color: #e67e22;">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $ocupacionFiltro; ?></h3>
                        <p>Cubículos en Uso</p>
                    </div>
                </div>
                <div style="width: 100%; background: #eee; height: 6px; border-radius: 5px; margin-top: 10px; overflow: hidden;">
                    <div style="width: <?php echo ($ocupados / $max_cubiculos) * 100; ?>%; background: #e67e22; height: 100%; transition: 0.5s;"></div>
                </div>
            </div>
        </div>

        <div class="grid-admin">
            <a href="admin/alumnos" class="card-btn">
                <i class="fas fa-user-graduate"></i>
                <h3>Gestión de Alumnos</h3>
                <p>Buscar, editar y registrar nuevos estudiantes.</p>
            </a>
            
            <a href="admin/cubiculos" class="card-btn">
                <i class="fas fa-th-large"></i>
                <h3>Control de Cubículos</h3>
                <p>Monitorear espacios ocupados y tiempos de estancia.</p>
            </a>
            
            <a href="admin/asistencias" class="card-btn">
                <i class="fas fa-list-alt"></i>
                <h3>Historial de Asistencias</h3>
                <p>Ver quiénes ingresaron hoy a la biblioteca.</p>
            </a>
        </div>
    </div>
</body>
</html>