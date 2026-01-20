<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Computadoras - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body { 
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%); 
            height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            padding: 20px;
        }

        .register-card { 
            background: white; 
            padding: 40px; 
            border-radius: 30px; 
            width: 100%; 
            max-width: 500px; 
            text-align: center; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            position: relative;
        }

        .btn-back-home {
            position: absolute;
            top: 25px;
            left: 25px;
            text-decoration: none;
            color: #95a5a6;
            font-size: 0.9rem;
            font-weight: 600;
            display: flex;
            align-items: center; gap: 8px;
            transition: 0.3s;
        }
        .btn-back-home:hover { color: #3498db; }

        .logo-area i { font-size: 3.5rem; color: #3498db; margin-bottom: 15px; margin-top: 10px; }
        h1 { color: #2c3e50; font-size: 1.8rem; margin-bottom: 5px; }
        p { color: #7f8c8d; margin-bottom: 25px; font-size: 0.9rem; }

        .form-group { text-align: left; margin-bottom: 15px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #7f8c8d; font-size: 0.85rem; }
        
        input, select { 
            width: 100%; padding: 14px; border: 2px solid #f0f0f0; border-radius: 12px; outline: none; font-size: 1rem; transition: 0.3s;
        }
        input:focus, select:focus { border-color: #3498db; }

        .btn-submit { 
            background: #3498db; color: white; border: none; width: 100%; padding: 16px; border-radius: 12px; 
            font-weight: bold; font-size: 1.1rem; cursor: pointer; transition: 0.3s; margin-top: 10px;
        }
        .btn-submit:hover { background: #2980b9; transform: translateY(-3px); }

        .msg { padding: 20px; border-radius: 15px; margin-bottom: 25px; font-weight: 600; }
        .success { background: #ebf5fb; color: #2980b9; border: 1px solid #3498db; }
        .error { background: #fadbd8; color: #c0392b; border: 1px solid #e74c3c; }

        .progress-bar {
            height: 4px; background: #3498db; width: 0%; margin-top: 15px; border-radius: 2px; transition: width 5s linear;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <a href="inicio" class="btn-back-home"><i class="fas fa-home"></i> Inicio</a>

        <div class="logo-area"><i class="fas fa-laptop"></i></div>
        
        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] == 'success'): ?>
                <div class="msg success">
                    <i class="fas fa-check-circle" style="font-size: 2rem;"></i><br><br>
                    ¡REGISTRO DE PC EXITOSO!<br>
                    <small>Alumno: <?php echo htmlspecialchars($_GET['nombre']); ?></small><br>
                    <small>PC #<?php echo $_GET['num']; ?> asignada correctamente.</small>
                    
                    <div class="progress-bar" id="pbar"></div>
                    <p style="font-size: 0.75rem; margin-top: 10px; opacity: 0.8;">Volviendo al inicio en 5 segundos...</p>
                </div>
                
                <script>
                    document.getElementById('pbar').style.width = '100%';
                    setTimeout(function() { window.location.href = 'inicio'; }, 5000);
                </script>

            <?php elseif ($_GET['msg'] == 'ocupado'): ?>
                <div class="msg error">Este equipo acaba de ser ocupado. Por favor, elige otro.</div>
            <?php elseif ($_GET['msg'] == 'error_alumno'): ?>
                <div class="msg error">DNI no encontrado o alumno con estado INACTIVO.</div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (!isset($_GET['msg']) || $_GET['msg'] !== 'success'): ?>
            <h1>Uso de Computadoras</h1>
            <p>Filial Sur - Centro de Cómputo</p>

            <form action="procesar-pc-publico" method="POST">
                <div class="form-group">
                    <label>Número de DNI:</label>
                    <input type="text" name="dni" placeholder="Ingresa tu documento" required autofocus maxlength="12">
                </div>
                
                <div class="form-group">
                    <label>Seleccionar Equipo (Máximo <?php echo $max_pcs; ?>):</label>
                    <select name="numero_pc" required>
                        <option value="" disabled selected>-- Equipos Disponibles --</option>
                        <?php 
                        $stmt_occ = $db->query("SELECT numero_pc FROM prestamos_computadoras WHERE estado = 'ocupado'");
                        $ocupados = $stmt_occ->fetchAll(PDO::FETCH_COLUMN);

                        for($i = 1; $i <= $max_pcs; $i++): 
                            $isOccupied = in_array($i, $ocupados);
                        ?>
                            <option value="<?php echo $i; ?>" <?php echo $isOccupied ? 'disabled' : ''; ?>>
                                PC #<?php echo $i; ?> <?php echo $isOccupied ? '(OCUPADA)' : '(Disponible)'; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>¿Cuánto tiempo la usarás?</label>
                    <select name="tiempo_estancia" required>
                        <option value="30">30 Minutos</option>
                        <option value="60">1 Hora</option>
                        <option value="90">1 Hora 30 Minutos</option>
                        <option value="120" selected>2 Horas (Máximo)</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">REGISTRAR MI INGRESO</button>
            </form>
        <?php endif; ?>
        
        <a href="inicio" style="display:block; margin-top:20px; color:#bdc3c7; text-decoration:none; font-size:0.8rem;">Cancelar y volver</a>
    </div>

</body>
</html>