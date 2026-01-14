<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Alumnos - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7f6; padding: 40px; }
        
        .container { 
            max-width: 1300px; 
            margin: auto; 
            background: white; 
            padding: 30px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }

        .header-area { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
        }

        .header-area h1 { color: #2c3e50; font-size: 1.8rem; }
        
        /* Contenedor de Botones */
        .btn-group { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
        
        .btn { 
            padding: 10px 18px; 
            border-radius: 10px; 
            text-decoration: none; 
            font-size: 0.85rem; 
            font-weight: 600; 
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back { background: #34495e; color: white; }
        .btn-add { background: #1abc9c; color: white; }
        .btn-mass { background: #3498db; color: white; }
        .btn-danger { background: #e74c3c; color: white; }

        .btn:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

        /* Tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f8f9fa; color: #7f8c8d; text-align: left; padding: 15px; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; }
        td { padding: 15px; border-bottom: 1px solid #eee; color: #2c3e50; font-size: 0.9rem; }

        /* Badges de Estado */
        .status { padding: 5px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; }
        .egresado { background: #d4e6f1; color: #2980b9; }
        .activo { background: #d1f2eb; color: #16a085; }
        .inactivo { background: #fadbd8; color: #c0392b; }

        .actions a { text-decoration: none; font-weight: 600; font-size: 0.85rem; margin-right: 15px; transition: 0.2s; }
        .edit-link { color: #3498db; }
        .delete-link { color: #e74c3c; }
        .edit-link:hover, .delete-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-area">
            <div>
                <h1>Gestión de Alumnos</h1>
                <p style="color: #95a5a6;">Biblioteca Central - Filial Sur</p>
            </div>
            
            <div class="btn-group">
                <a href="../<?php echo $_SESSION['rol']; ?>" class="btn btn-back">⬅️ Volver</a>
                
                <a href="../alumnos/nuevo" class="btn btn-add">➕ Agregar Alumno</a>
                
                <?php if ($_SESSION['rol'] === 'superadmin'): ?>
                    <a href="../superadmin/alumnos/carga-masiva" class="btn btn-mass">📥 Carga Masiva</a>
                    <a href="../superadmin/alumnos/vaciar" class="btn btn-danger" 
                       onclick="return confirm('⚠️ ¿Desea eliminar TODOS los registros? Esta acción no se puede deshacer.')">
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
                    <th>Celular</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $a): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($a['dni']); ?></strong></td>
                    <td><?php echo htmlspecialchars($a['apellidos_nombres']); ?></td>
                    <td><?php echo htmlspecialchars($a['carrera']); ?></td>
                    <td><?php echo htmlspecialchars($a['celular'] ?? '---'); ?></td>
                    <td>
                        <span class="status <?php echo $a['estado']; ?>">
                            <?php echo htmlspecialchars($a['estado']); ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="../alumnos/editar?id=<?php echo $a['id']; ?>" class="edit-link">Editar</a>
                        <a href="../alumnos/eliminar?id=<?php echo $a['id']; ?>" 
                           class="delete-link" 
                           onclick="return confirm('¿Está seguro de eliminar a este alumno?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($alumnos)): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding: 60px; color: #95a5a6;">
                        No hay alumnos registrados actualmente.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>