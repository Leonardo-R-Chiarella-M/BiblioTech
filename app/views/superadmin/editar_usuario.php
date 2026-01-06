<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - BiblioTech</title>
    <style>
        .form-container { background: white; padding: 40px; border-radius: 15px; max-width: 500px; margin: 50px auto; box-shadow: 0 10px 30px rgba(0,0,0,0.1); font-family: sans-serif; }
        input, select { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; }
        .btn-update { background: #3498db; color: white; border: none; padding: 15px; width: 100%; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .info-pass { font-size: 12px; color: #7f8c8d; margin-top: -15px; margin-bottom: 20px; display: block; }
    </style>
</head>
<body style="background: #f1f2f6;">
    <div class="form-container">
        <h2>Editar Usuario</h2>
        <p>Modifica los datos de <b><?php echo htmlspecialchars($usuario_edit['nombre']); ?></b></p><br>
        
        <form action="actualizar" method="POST">
            <input type="hidden" name="id" value="<?php echo $usuario_edit['id']; ?>">
            
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario_edit['nombre']); ?>" required>
            
            <label>Correo:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($usuario_edit['email']); ?>" required>
            
            <label>Contraseña:</label>
            <input type="password" name="pass" placeholder="Dejar en blanco para no cambiar">
            <span class="info-pass">Solo escribe una nueva si deseas cambiar la actual.</span>
            
            <label>Rol:</label>
            <select name="rol">
                <option value="admin" <?php echo ($usuario_edit['rol'] == 'admin') ? 'selected' : ''; ?>>Admin (Bibliotecario)</option>
                <option value="superadmin" <?php echo ($usuario_edit['rol'] == 'superadmin') ? 'selected' : ''; ?>>Super Admin (Dueño)</option>
            </select>
            
            <button type="submit" class="btn-update">ACTUALIZAR DATOS</button>
        </form>
        <br>
        <a href="../usuarios" style="text-decoration: none; color: #7f8c8d;">← Cancelar y volver</a>
    </div>
</body>
</html>