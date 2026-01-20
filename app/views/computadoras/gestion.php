<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Computadoras - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7f6; padding: 20px; }
        .container { max-width: 1300px; margin: auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #f8f9fa; padding-bottom: 15px; }
        
        /* Grid de 4 columnas para computadoras */
        .grid-mapa { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        
        /* Tarjetas */
        .card-ocupada { background: #fff; border-left: 6px solid #3498db; border-radius: 15px; padding: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border: 1px solid #f2f2f2; }
        .card-libre { background: #fafafa; border: 2px dashed #dcdde1; border-radius: 15px; padding: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #b2bec3; min-height: 200px; }
        .badge-num { background: #34495e; color: white; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 0.8rem; }

        /* Cronómetro HH:MM:SS */
        .timer-display { display: block; text-align: center; margin: 15px 0; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 1.2rem; letter-spacing: 1px; }
        .timer-normal { background: #f0fff4; color: #27ae60; border: 1px solid #c6f6d5; }
        .timer-alert { background: #fff5f5; color: #e74c3c; border: 1px solid #ffd7d7; animation: pulse 1s infinite; }

        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.8; } 100% { opacity: 1; } }
        .btn-liberar { display: block; width: 100%; background: #34495e; color: white; text-align: center; padding: 12px; border-radius: 10px; text-decoration: none; font-size: 0.85rem; font-weight: 600; transition: 0.3s; }
        .btn-liberar:hover { background: #000; }
        
        /* Tabla Historial */
        .table-container { margin-top: 40px; overflow-x: auto; border-radius: 12px; border: 1px solid #eee; }
        table { width: 100%; border-collapse: collapse; background: white; }
        th { background: #f8f9fa; padding: 12px; text-align: left; color: #7f8c8d; font-size: 0.8rem; text-transform: uppercase; }
        td { padding: 12px; border-top: 1px solid #eee; font-size: 0.85rem; color: #2c3e50; }

        /* Ajuste para tablets */
        @media (max-width: 1024px) { .grid-mapa { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .grid-mapa { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-desktop"></i> Monitoreo de Computadoras</h1>
            <a href="../<?php echo $_SESSION['rol']; ?>" style="text-decoration:none; color:#7f8c8d;">← Volver</a>
        </div>

        <div class="grid-mapa">
            <?php 
            date_default_timezone_set('America/Lima');
            $ahora = time();

            $ocupados_map = [];
            foreach($prestamos_pc as $p) { $ocupados_map[$p['numero_pc']] = $p; }

            for($i = 1; $i <= $max_pcs; $i++): 
                if(isset($ocupados_map[$i])): 
                    $data = $ocupados_map[$i];
                    
                    $inicio_segundos = strtotime(date('Y-m-d ') . $data['hora_inicio']);
                    $fin_esperado = $inicio_segundos + ($data['tiempo_solicitado'] * 60);
                    $segundos_restantes = $fin_esperado - $ahora;
            ?>
                <div class="card-ocupada">
                    <div style="display:flex; justify-content:space-between; margin-bottom:12px; align-items:center;">
                        <span class="badge-num">PC #<?php echo $i; ?></span>
                        <span style="color:#e74c3c; font-size:0.7rem; font-weight:bold;">● EN USO</span>
                    </div>
                    <h4 style="font-size:0.9rem; margin-bottom:5px;"><?php echo htmlspecialchars($data['apellidos_nombres']); ?></h4>
                    <p style="font-size:0.75rem; color:#95a5a6; margin-bottom:12px;"><?php echo htmlspecialchars($data['carrera']); ?></p>
                    
                    <div id="timer-pc-<?php echo $i; ?>" 
                         class="timer-display <?php echo ($segundos_restantes < 0) ? 'timer-alert' : 'timer-normal'; ?>"
                         data-seconds="<?php echo $segundos_restantes; ?>">
                        <i class="fas fa-clock"></i> <span class="time-text">00:00:00</span>
                    </div>
                    
                    <a href="../computadoras/finalizar?id=<?php echo $data['id']; ?>" class="btn-liberar" onclick="return confirm('¿Liberar este equipo?')">FINALIZAR</a>
                </div>
            <?php else: ?>
                <div class="card-libre">
                    <span class="badge-num" style="background:#eee; color:#999; margin-bottom:12px;">N° <?php echo $i; ?></span>
                    <i class="fas fa-desktop" style="font-size: 2.5rem; margin-bottom: 8px; color:#eee;"></i>
                    <p style="font-size:0.8rem; font-weight:600;">DISPONIBLE</p>
                </div>
            <?php endif; endfor; ?>
        </div>

        <div class="header" style="margin-top: 50px; border-bottom: 2px solid #f1f2f6; padding-bottom: 10px;">
            <h1><i class="fas fa-history"></i> Historial de Hoy</h1>
            <a href="../computadoras/exportar" style="background:#27ae60; color:white; padding:8px 15px; border-radius:8px; text-decoration:none; font-size:0.8rem;"><i class="fas fa-file-excel"></i> Excel</a>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>PC N°</th>
                        <th>Alumno</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historial_pc)): ?>
                        <tr><td colspan="5" style="text-align:center; padding:20px; color:#999;">Sin registros hoy</td></tr>
                    <?php else: ?>
                        <?php foreach ($historial_pc as $h): ?>
                        <tr>
                            <td><strong>#<?php echo $h['numero_pc']; ?></strong></td>
                            <td><?php echo htmlspecialchars($h['apellidos_nombres']); ?></td>
                            <td><?php echo date('H:i', strtotime($h['hora_inicio'])); ?></td>
                            <td style="color:#27ae60; font-weight:bold;"><?php echo date('H:i', strtotime($h['hora_fin'])); ?></td>
                            <td><span style="background:#d1f2eb; color:#16a085; padding:3px 8px; border-radius:10px; font-size:0.7rem;">LIBRE</span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function updateTimers() {
            const timers = document.querySelectorAll('[id^="timer-pc-"]');
            
            timers.forEach(timer => {
                let seconds = parseInt(timer.getAttribute('data-seconds'));
                const textElement = timer.querySelector('.time-text');
                
                seconds--;
                timer.setAttribute('data-seconds', seconds);

                let absSeconds = Math.abs(seconds);
                let hrs = Math.floor(absSeconds / 3600);
                let mins = Math.floor((absSeconds % 3600) / 60);
                let secs = absSeconds % 60;
                
                let formatted = (hrs < 10 ? '0' : '') + hrs + ':' + 
                                (mins < 10 ? '0' : '') + mins + ':' + 
                                (secs < 10 ? '0' : '') + secs;

                if (seconds < 0) {
                    timer.classList.remove('timer-normal');
                    timer.classList.add('timer-alert');
                    textElement.innerHTML = "-" + formatted;
                } else {
                    textElement.innerHTML = formatted;
                }
            });
        }
        updateTimers();
        setInterval(updateTimers, 1000);
    </script>
</body>
</html>