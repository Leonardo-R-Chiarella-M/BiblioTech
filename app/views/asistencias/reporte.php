<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Asistencias - BiblioTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f4f7f6; padding: 30px; }
        
        .container { 
            max-width: 1300px; 
            margin: auto; 
            background: white; 
            padding: 30px; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
        }

        .header-report { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 25px; 
        }
        
        .header-report h1 { color: #2c3e50; font-size: 1.8rem; }

        /* Barra de Filtros */
        .filter-bar { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 15px; 
            margin-bottom: 30px; 
            display: flex; 
            gap: 20px; 
            align-items: flex-end; 
            flex-wrap: wrap;
        }

        .filter-group { display: flex; flex-direction: column; gap: 5px; }
        .filter-group label { font-size: 0.75rem; font-weight: 600; color: #7f8c8d; text-transform: uppercase; }
        
        select, input { 
            padding: 10px; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            outline: none; 
            font-size: 0.9rem;
        }

        /* Botones */
        .btn { 
            padding: 10px 20px; 
            border-radius: 8px; 
            font-weight: 600; 
            text-decoration: none; 
            cursor: pointer; 
            transition: 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-search { background: #1abc9c; color: white; }
        .btn-excel { background: #27ae60; color: white; margin-left: auto; }
        .btn-back { background: #34495e; color: white; font-size: 0.85rem; }
        .btn:hover { opacity: 0.9; transform: translateY(-2px); }

        /* Tabla */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f8f9fa; padding: 15px; text-align: left; color: #7f8c8d; font-size: 0.8rem; text-transform: uppercase; border-bottom: 2px solid #eee; }
        td { padding: 15px; border-bottom: 1px solid #eee; font-size: 0.9rem; color: #2c3e50; }

        .time-badge { background: #e8f6f3; color: #1abc9c; padding: 5px 10px; border-radius: 8px; font-weight: 600; }
        
        /* Badges de Estado Priorizado */
        .status-pill { 
            padding: 5px 12px; 
            border-radius: 20px; 
            font-size: 0.75rem; 
            font-weight: bold; 
            text-transform: uppercase; 
        }
        .st-egresado { background: #d4e6f1; color: #2980b9; }
        .st-activo { background: #d1f2eb; color: #16a085; }
        .st-inactivo { background: #fadbd8; color: #c0392b; }
    </style>
</head>
<body>

    <div class="container">
        <div class="header-report">
            <div>
                <h1>Historial de Ingresos</h1>
                <p style="color: #95a5a6;">Biblioteca Central - Filial Sur</p>
            </div>
            <a href="../<?php echo $_SESSION['rol']; ?>" class="btn btn-back">⬅️ Volver al Panel</a>
        </div>

        <form method="GET" class="filter-bar">
            <div class="filter-group">
                <label>Año</label>
                <input type="number" name="anio" style="width: 100px;" value="<?php echo $_GET['anio'] ?? date('Y'); ?>">
            </div>
            
            <div class="filter-group">
                <label>Mes</label>
                <select name="mes">
                    <?php
                    $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
                    foreach($meses as $i => $m) {
                        $val = str_pad($i+1, 2, "0", STR_PAD_LEFT);
                        $sel = ($val == ($_GET['mes'] ?? date('m'))) ? 'selected' : '';
                        echo "<option value='$val' $sel>$m</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="filter-group">
                <label>Día (Opcional)</label>
                <input type="number" name="dia" min="1" max="31" placeholder="Ej: 08" style="width: 100px;" value="<?php echo $_GET['dia'] ?? ''; ?>">
            </div>

            <button type="submit" class="btn btn-search">🔍 Filtrar</button>
            
            <a href="../asistencias/exportar" class="btn btn-excel">📊 Exportar Excel</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Fecha y Hora</th>
                    <th>DNI</th>
                    <th>Alumno</th>
                    <th>Carrera</th>
                    <th>Celular</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asistencias as $as): ?>
                <tr>
                    <td><span class="time-badge"><?php echo date('d/m/Y H:i', strtotime($as['fecha_hora'])); ?></span></td>
                    <td><strong><?php echo htmlspecialchars($as['dni']); ?></strong></td>
                    <td><?php echo htmlspecialchars($as['apellidos_nombres']); ?></td>
                    <td><?php echo htmlspecialchars($as['carrera']); ?></td>
                    <td><?php echo htmlspecialchars($as['celular'] ?? '---'); ?></td>
                    <td>
                        <span class="status-pill st-<?php echo $as['estado']; ?>">
                            <?php echo htmlspecialchars($as['estado']); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($asistencias)): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding: 50px; color: #95a5a6;">
                        No se encontraron registros para el periodo seleccionado.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>