<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Usuario - BiblioTech</title>
    <link rel="stylesheet" href="../css/login.css"> <style>
        .form-container { background: white; padding: 40px; border-radius: 15px; max-width: 500px; margin: 50px auto; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        select { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .btn-save { background: #3498db; color: white; border: none; padding: 15px; width: 100%; border-radius: 8px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body style="background: #f1f2f6;">
    <div class="form-container">
        <h2>Registrar Nuevo Usuario</h2>
        <p>Define el personal administrativo</p><br>
        
        <form action="guardar" method="POST">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            
            <label>Asignar Rol:</label>
            <select name="rol">
                <option value="admin">Admin (Bibliotecario)</option>
                <option value="superadmin">Super Admin (Dueño)</option>
            </select>
            
            <button type="submit" class="btn-save">GUARDAR USUARIO</button>
        </form>
        <br>
        <a href="../superadmin" style="text-decoration: none; color: #7f8c8d;">← Cancelar y volver</a>
    </div>
</body>
</html>