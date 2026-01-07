<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - BiblioTech</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: #f1f2f6; padding: 30px; }
        .container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 1100px; margin: auto; }
        
        /* Cabecera con botones */
        .header-table { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 25px; 
            border-bottom: 2px solid #f1f2f6;
            padding-bottom: 15px;
        }

        .btn-volver { text-decoration: none; color: #3498db; font-weight: bold; display: block; margin-bottom: 10px; }
        
        .btn-nuevo { 
            background: #27ae60; 
            color: white; 
            padding: 10px 20px; 
            border-radius: 6px; 
            text-decoration: none; 
            font-weight: bold; 
            transition: 0.3s;
        }
        .btn-nuevo:hover { background: #219150; }

        /* Estilos de Tabla */
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background-color: #34495e; color: white; font-size: 13px; text-transform: uppercase; }
        
        /* Badges y Alertas */
        .badge { padding: 5px 10px; border-radius: 15px; font-size: 11px; font-weight: bold; }
        .badge-super { background: #ffeaa7; color: #d35400; }
        .badge-admin { background: #d1f2eb; color: #16a085; }
        
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 8px; font-weight: bold; text-align: center; }
        .success { background: #d1f2eb; color: #16a085; border: 1px solid #16a085; }
        .info { background: #d4e6f1; color: #2980b9; border: 1px solid #2980b9; }

        .action-link { text-decoration: none; font-weight: bold; font-size: 13px; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <?php $msg = $_GET['msg'] ?? ''; ?>
        <?php if ($msg == 'created'): ?> <div class="alert success">✅ Nuevo usuario registrado con éxito.</div> <?php endif; ?>
        <?php if ($msg == 'updated'): ?> <div class="alert info">ℹ️ Datos de usuario actualizados.</div> <?php endif; ?>
        <?php if ($msg == 'deleted'): ?> <div class="alert success">🗑️ Usuario eliminado correctamente.</div> <?php endif; ?>

        <a href="../superadmin" class="btn-volver">← Volver al Panel</a>
        
        <div class="header-table">
            <div>
                <h2>Personal Administrativo</h2>
                <p style="color: #7f8c8d; font-size: 14px;">Gestiona los accesos de bibliotecarios y dueños</p>
            </div>
            <a href="nuevo" class="btn-nuevo">+ AGREGAR NUEVO</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><strong><?php echo htmlspecialchars($u['nombre']); ?></strong></td>
                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                    <td>
                        <span class="badge <?php echo ($u['rol'] == 'superadmin') ? 'badge-super' : 'badge-admin'; ?>">
                            <?php echo strtoupper($u['rol']); ?>
                        </span>
                    </td>
                    <td>
                        <a href="usuarios/editar?id=<?php echo $u['id']; ?>" class="action-link" style="color: #3498db;">Editar</a>
                        
                        <?php if ($u['id'] != $_SESSION['user_id']): ?>
                            <a href="usuarios/eliminar?id=<?php echo $u['id']; ?>" 
                               class="action-link" 
                               style="color: #e74c3c;" 
                               onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</a>
                        <?php else: ?>
                            <span style="color:#bdc3c7; font-size: 12px; font-style: italic;">(Tu Sesión)</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>