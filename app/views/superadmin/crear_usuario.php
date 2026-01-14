<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { 
            background: #f1f2f6; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            padding: 20px;
        }

        .form-container { 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            max-width: 450px; 
            width: 100%; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
        }

        .form-header { text-align: center; margin-bottom: 30px; }
        .form-header h2 { color: #2c3e50; font-size: 1.6rem; }
        .form-header p { color: #95a5a6; font-size: 0.9rem; }

        .input-group { margin-bottom: 20px; position: relative; }
        .input-group i { 
            position: absolute; 
            left: 15px; 
            top: 50%; 
            transform: translateY(-50%); 
            color: #1abc9c; 
        }

        input, select { 
            width: 100%; 
            padding: 12px 15px 12px 45px; 
            border: 1px solid #dfe6e9; 
            border-radius: 10px; 
            outline: none; 
            transition: 0.3s;
            background: #fdfdfd;
        }

        input:focus, select:focus { border-color: #1abc9c; box-shadow: 0 0 8px rgba(26, 188, 156, 0.2); }

        label { 
            display: block; 
            margin-bottom: 8px; 
            color: #7f8c8d; 
            font-size: 0.8rem; 
            font-weight: 600; 
            text-transform: uppercase;
        }

        .btn-save { 
            background: #1abc9c; 
            color: white; 
            border: none; 
            padding: 15px; 
            width: 100%; 
            border-radius: 10px; 
            cursor: pointer; 
            font-weight: bold; 
            font-size: 1rem;
            transition: 0.3s;
            margin-top: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .btn-save:hover { background: #16a085; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(26, 188, 156, 0.3); }

        .btn-cancel { 
            display: block; 
            text-align: center; 
            margin-top: 20px; 
            text-decoration: none; 
            color: #bdc3c7; 
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .btn-cancel:hover { color: #e74c3c; }
    </style>
</head>
<body>

    <div class="form-container">
        <div class="form-header">
            <h2><i class="fas fa-user-shield"></i> Registrar Usuario</h2>
            <p>Define el personal administrativo de la Filial Sur</p>
        </div>
        
        <form action="guardar" method="POST">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="nombre" placeholder="Nombre completo" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Correo electrónico" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="pass" placeholder="Contraseña de acceso" required>
            </div>
            
            <div class="input-group">
                <label>Asignar Nivel de Acceso:</label>
                <i class="fas fa-id-badge" style="top: 72%;"></i>
                <select name="rol">
                    <option value="admin">Administrador (Bibliotecario)</option>
                    <option value="superadmin">Super Admin (Control Total)</option>
                </select>
            </div>
            
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> GUARDAR USUARIO
            </button>
        </form>

        <a href="usuarios" class="btn-cancel">
            <i class="fas fa-times"></i> Cancelar y volver
        </a>
    </div>

</body>
</html>