<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Alumno - BiblioTech</title>
    <style>
        body { background: #f1f2f6; font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px; }
        .form-card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); width: 450px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #34495e; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; }
        .btn-save { background: #27ae60; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-card">
        <h2>📝 Registrar Alumno</h2><br>
        <form action="../alumnos/guardar" method="POST">
            <div class="form-group">
                <label>DNI</label>
                <input type="text" name="dni" required maxlength="8">
            </div>
            <div class="form-group">
                <label>Apellidos y Nombres</label>
                <input type="text" name="nombres" required>
            </div>
            <div class="form-group">
                <label>Carrera</label>
                <input type="text" name="carrera" required>
            </div>
            <div class="form-group">
                <label>Correo Institucional</label>
                <input type="email" name="correo">
            </div>
            <div class="form-group">
                <label>Estado</label>
                <select name="estado">
                    <option value="activo">Activo</option>
                    <option value="egresado">Egresado</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn-save">GUARDAR ALUMNO</button>
            <br><br>
            <a href="../<?php echo $_SESSION['rol']; ?>/alumnos" style="color: #7f8c8d; text-decoration: none;">← Cancelar</a>
        </form>
    </div>
</body>
</html>