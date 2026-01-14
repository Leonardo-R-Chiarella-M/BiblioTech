<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Alumno - BiblioTech</title>
    <style>
        body { background: #f1f2f6; font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px; }
        .form-card { background: white; padding: 30px; border-radius: 12px; width: 450px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        .btn-update { background: #3498db; color: white; border: none; padding: 12px; width: 100%; margin-top: 20px; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>✏️ Editar Alumno</h2>
        <form action="../alumnos/actualizar" method="POST">
            <input type="hidden" name="id" value="<?php echo $alumno['id']; ?>">
            
            <label>DNI</label>
            <input type="text" name="dni" value="<?php echo $alumno['dni']; ?>" required>
            
            <label>Apellidos y Nombres</label>
            <input type="text" name="nombres" value="<?php echo $alumno['apellidos_nombres']; ?>" required>
            
            <label>Carrera</label>
            <input type="text" name="carrera" value="<?php echo $alumno['carrera']; ?>" required>
            
            <label>Correo Institucional</label>
            <input type="email" name="correo" value="<?php echo $alumno['correo_institucional']; ?>">

            <label>Número de Celular</label>
            <input type="text" name="celular" placeholder="999888777" maxlength="15">
            <label>Estado</label>
            <select name="estado">
                <option value="activo" <?php echo ($alumno['estado']=='activo')?'selected':''; ?>>Activo</option>
                <option value="egresado" <?php echo ($alumno['estado']=='egresado')?'selected':''; ?>>Egresado</option>
                <option value="inactivo" <?php echo ($alumno['estado']=='inactivo')?'selected':''; ?>>Inactivo</option>
            </select>
            
            <button type="submit" class="btn-update">ACTUALIZAR DATOS</button>
            <br><br>
            <a href="../<?php echo $_SESSION['rol']; ?>/alumnos" style="color: #7f8c8d; text-decoration: none;">← Volver</a>
        </form>
    </div>
</body>
</html>