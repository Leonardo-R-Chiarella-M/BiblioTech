<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carga Masiva - BiblioTech</title>
    <style>
        .upload-container { background: white; padding: 40px; border-radius: 15px; max-width: 600px; margin: 50px auto; box-shadow: 0 10px 30px rgba(0,0,0,0.1); font-family: sans-serif; }
        .format-info { background: #f8f9fa; padding: 15px; border-left: 4px solid #8e44ad; margin-bottom: 20px; font-size: 14px; }
        input[type="file"] { border: 2px dashed #ddd; padding: 20px; width: 100%; cursor: pointer; margin-bottom: 20px; }
        .btn-upload { background: #8e44ad; color: white; border: none; padding: 15px; width: 100%; border-radius: 8px; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body style="background: #f1f2f6;">
    <div class="upload-container">
        <h2>📥 Carga Masiva de Alumnos</h2>
        <p>Sube un archivo CSV con la lista de estudiantes.</p>
        
        <div class="format-info">
            <strong>Formato del archivo (.csv):</strong><br>
            DNI, Apellidos_Nombres, Carrera, Correo, Estado<br>
            <small>*El estado puede ser: activo, inactivo o egresado.</small>
        </div>

        <form action="procesar-carga" method="POST" enctype="multipart/form-data">
            <input type="file" name="archivo_csv" accept=".csv" required>
            <button type="submit" class="btn-upload">INICIAR IMPORTACIÓN</button>
        </form>
        
        <br>
        <a href="../alumnos" style="text-decoration: none; color: #7f8c8d;">← Volver a la lista</a>
    </div>
</body>
</html>