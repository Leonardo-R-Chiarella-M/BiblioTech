<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alumnos - BiblioTech</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f4f7f6; display: flex; min-height: 100vh; }
        
        /* Sidebar */
        .sidebar { 
            width: 260px; background: #2c3e50; color: white; padding: 25px; 
            display: flex; flex-direction: column; position: fixed; height: 100vh;
        }
        .sidebar h2 { color: #1abc9c; text-align: center; margin-bottom: 5px; font-size: 26px; }
        .sidebar .role-tag { font-size: 11px; color: #bdc3c7; text-align: center; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 2px; }
        .sidebar ul { list-style: none; flex-grow: 1; }
        .sidebar a { color: #ecf0f1; text-decoration: none; display: block; padding: 12px 15px; border-radius: 8px; transition: 0.3s; }
        .sidebar a:hover { background: #34495e; color: #1abc9c; }
        
        .btn-logout { 
            background: #e74c3c; color: white !important; text-align: center; 
            padding: 15px; border-radius: 10px; font-weight: bold; text-decoration: none;
            margin-top: auto; transition: 0.3s;
        }

        /* Contenido */
        .main-content { flex-grow: 1; margin-left: 260px; padding: 40px; }
        .container { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }

        .header-actions { 
            display: flex; justify-content: space-between; align-items: center; 
            margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;
        }
        .btn { padding: 10px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 13px; transition: 0.3s; display: inline-block; }
        .btn-add { background: #1abc9c; color: white; }
        .btn-mass { background: #8e44ad; color: white; margin-left: 5px; }
        .btn-danger { background: #e74c3c; color: white; margin-left: 5px; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #f1f2f6; }
        th { background: #f8f9fa; color: #7f8c8d; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .status-egresado { background: #d4e6f1; color: #2980b9; }
        .status-activo { background: #d1f2eb; color: #16a085; }
        .status-inactivo { background: #fadbd8; color: #c0392b; }

        .alert { padding: 15px; margin-bottom: 20px; border-radius: 8px; font-weight: bold; text-align: center; font-size: 14px; }
        .success { background: #d1f2eb; color: #16a085; border: 1px solid #16a085; }
        .error { background: #fadbd8; color: #c0392b; border: 1px solid #c0392b; }
    </style>
</head>
<body>
    <nav class="sidebar">
        <h2>BiblioTech</h2>
        <div class="role-tag">Panel <?php echo ($_SESSION['rol'] == 'superadmin') ? 'Dueño' : 'Bibliotecario'; ?></div>
        <ul>
            <li><a href="../<?php echo $_SESSION['rol']; ?>">🏠 Dashboard</a></li>
            <li><a href="alumnos" style="background: #34495e; color: #1abc9c;">👥 Gestión Alumnos</a></li>
            <li><a href="#">📚 Libros</a></li>
        </ul>
        <a href="../logout" class="btn-logout">Cerrar Sesión</a>
    </nav>

    <main class="main-content">
        <div class="container">
            <?php $msg = $_GET['msg'] ?? ''; ?>
            <?php if ($msg == 'created'): ?> <div class="alert success">✅ Alumno agregado con éxito.</div> <?php endif; ?>
            <?php if ($msg == 'updated'): ?> <div class="alert success">ℹ️ Datos actualizados correctamente.</div> <?php endif; ?>
            <?php if ($msg == 'deleted'): ?> <div class="alert error">🗑️ Registro de alumno eliminado.</div> <?php endif; ?>
            <?php if ($msg == 'cleared'): ?> <div class="alert error">🔥 La tabla de alumnos ha sido vaciada.</div> <?php endif; ?>

            <div class="header-actions">
                <div>
                    <h1>Gestión de Alumnos</h1>
                    <p style="color: #7f8c8d;">Total: <strong><?php echo count($alumnos); ?></strong> registros encontrados.</p>
                </div>
                <div>
                    <a href="../alumnos/nuevo" class="btn btn-add">➕ Agregar Alumno</a>
                    
                    <?php if ($_SESSION['rol'] === 'superadmin'): ?>
                        <a href="../superadmin/alumnos/carga-masiva" class="btn btn-mass">📥 Carga Masiva</a>
                        <a href="../superadmin/alumnos/vaciar" class="btn btn-danger" 
                           onclick="return confirm('⚠️ ¿Desea eliminar TODOS los registros?')">
                           🗑️ Vaciar Tabla
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>DNI</th>
                        <th>Apellidos y Nombres</th>
                        <th>Carrera</th>
                        <th>Correo Institucional</th> <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alumnos as $a): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($a['dni']); ?></strong></td>
                        <td><?php echo htmlspecialchars($a['apellidos_nombres']); ?></td>
                        <td><?php echo htmlspecialchars($a['carrera']); ?></td>
                        <td><?php echo htmlspecialchars($a['correo_institucional'] ?: '---'); ?></td> <td>
                            <span class="badge status-<?php echo $a['estado']; ?>">
                                <?php echo $a['estado']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="../alumnos/editar?id=<?php echo $a['id']; ?>" style="color: #3498db; text-decoration:none; font-weight:bold; margin-right:10px;">Editar</a>
                            <a href="../alumnos/eliminar?id=<?php echo $a['id']; ?>" 
                               style="color: #e74c3c; text-decoration:none; font-weight:bold;" 
                               onclick="return confirm('¿Eliminar registro?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>