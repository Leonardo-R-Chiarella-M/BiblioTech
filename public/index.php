<?php
date_default_timezone_set('America/Lima'); // Establece la hora local de Perú

// public/index.php
session_start();
require_once '../app/core/Database.php';

// --- CONFIGURACIÓN GLOBAL (Agrégalo aquí para que no de error) ---
$max_cubiculos = 5; 
// --- CONFIGURACIÓN GLOBAL ---
$max_pcs = 10; // Cantidad de computadoras en la Filial Sur



// --- FUNCIÓN GLOBAL PARA REGISTRAR ASISTENCIA ---
function registrarAsistencia($db, $dni) {
    $ins = $db->prepare("INSERT INTO asistencias (alumno_dni) VALUES (?)");
    $ins->execute([$dni]);
}
// ... resto del código antes del switch ...

$url = $_GET['url'] ?? $_GET['action'] ?? 'inicio';
$url = rtrim($url, '/');

$database = new Database();
$db = $database->getConnection();

switch ($url) {
    // ==========================================
    // VISTAS PÚBLICAS Y REGISTRO DE ASISTENCIA
    // ==========================================
    case 'inicio':
        require_once '../app/views/general/inicio.php';
        break;
        
    case 'registrar-ingreso':
        require_once '../app/views/general/registro_ingreso.php';
        break;

    case 'procesar-ingreso':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dni = $_POST['dni'];
            // Aplicamos ORDER BY para respetar la jerarquía (Egresado > Activo > Inactivo)
            $stmt = $db->prepare("SELECT * FROM alumnos WHERE dni = ? 
                                  ORDER BY CASE estado 
                                    WHEN 'egresado' THEN 1 
                                    WHEN 'activo' THEN 2 
                                    ELSE 3 END ASC");
            $stmt->execute([$dni]);
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($registros) {
                $alumnoValido = $registros[0]; 
                if ($alumnoValido['estado'] !== 'inactivo') {
                    registrarAsistencia($db, $dni);
                    $params = http_build_query([
                        'msg'     => 'success',
                        'nombre'  => $alumnoValido['apellidos_nombres'],
                        'correo'  => $alumnoValido['correo_institucional'],
                        'carrera' => $alumnoValido['carrera'],
                        'celular' => $alumnoValido['celular'],
                        'estado'  => $alumnoValido['estado']
                    ]);
                    header("Location: registrar-ingreso?$params");
                } else {
                    header("Location: registrar-ingreso?msg=blocked");
                }
            } else {
                header("Location: registrar-ingreso?msg=not_found");
            }
            exit();
        }
        break;

    // ==========================================
    // SISTEMA DE LOGIN Y AUTH
    // ==========================================
    case 'login':
        if (isset($_SESSION['rol'])) { header("Location: " . $_SESSION['rol']); exit(); }
        require_once '../app/views/login.php';
        break;

    case 'auth':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $pass  = $_POST['pass'];
            $stmt = $db->prepare("SELECT id, nombre, rol, password FROM usuarios WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($pass, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nombre']  = $user['nombre'];
                    $_SESSION['rol']     = $user['rol'];
                    header("Location: " . $user['rol']); 
                    exit();
                }
            }
            header("Location: login?error=1"); exit();
        }
        break;

    // ==========================================
    // DASHBOARDS (CORREGIDO CON PCs)
    // ==========================================
    case 'superadmin':
    case 'admin':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === $url) {
            // Conteo de Alumnos
            $totalAlumnos = $db->query("SELECT COUNT(DISTINCT dni) FROM alumnos")->fetchColumn();

            // LÓGICA DE CUBÍCULOS
            $ocupados = $db->query("SELECT COUNT(*) FROM prestamos_cubiculos WHERE estado = 'ocupado'")->fetchColumn();
            $ocupacionFiltro = $ocupados . " / " . $max_cubiculos;

            // LÓGICA DE COMPUTADORAS (Añade esto para quitar el error)
            $ocupados_pc = $db->query("SELECT COUNT(*) FROM prestamos_computadoras WHERE estado = 'ocupado'")->fetchColumn();
            // $max_pcs debe estar definida al inicio de tu index.php (ej: $max_pcs = 10;)

            // Solo para SuperAdmin
            $totalUsuarios = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();

            require_once "../app/views/{$url}/dashboard.php";
        } else { 
            header("Location: login"); 
            exit();
        }
        break;

    // ==========================================
    // REPORTE Y EXPORTACIÓN DE ASISTENCIAS
    // ==========================================
    case 'superadmin/asistencias':
case 'admin/asistencias':
    if (isset($_SESSION['rol'])) {
        $mes = $_GET['mes'] ?? date('m');
        $anio = $_GET['anio'] ?? date('Y');
        $dia = $_GET['dia'] ?? '';
        $semana = $_GET['semana'] ?? '';

        $where = "WHERE MONTH(a.fecha_hora) = :mes AND YEAR(a.fecha_hora) = :anio";
        $params = [':mes' => $mes, ':anio' => $anio];

        if (!empty($dia)) {
            $where .= " AND DAY(a.fecha_hora) = :dia";
            $params[':dia'] = $dia;
        }
        if (!empty($semana)) {
            $where .= " AND (FLOOR((DAY(a.fecha_hora) - 1) / 7) + 1) = :semana";
            $params[':semana'] = $semana;
        }

        // CONSULTA CORREGIDA CON PRIORIDAD DE ESTADO
        $query = "SELECT 
                    a.id, 
                    a.fecha_hora, 
                    al.dni, 
                    al.apellidos_nombres, 
                    al.carrera, 
                    al.celular, 
                    al.estado
                  FROM asistencias a
                  INNER JOIN alumnos al ON a.alumno_dni = al.dni
                  $where
                  /* Esta es la clave: solo unimos la asistencia con el ID del alumno que tenga el mejor estado */
                  AND al.id = (
                      SELECT t2.id FROM alumnos t2 
                      WHERE t2.dni = al.dni 
                      ORDER BY CASE estado 
                          WHEN 'egresado' THEN 1 
                          WHEN 'activo' THEN 2 
                          ELSE 3 END ASC 
                      LIMIT 1
                  )
                  GROUP BY a.id 
                  ORDER BY a.fecha_hora DESC";
        
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Guardamos para que el exportador herede esta misma lógica limpia
        $_SESSION['ultimo_filtro_asistencias'] = ['query' => $query, 'params' => $params];
        
        require_once '../app/views/asistencias/reporte.php';
    } break;
    case 'asistencias/exportar':
    if (isset($_SESSION['rol']) && isset($_SESSION['ultimo_filtro_asistencias'])) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reporte_asistencias.csv');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // Soporte para tildes
        fputcsv($output, ['Fecha y Hora', 'DNI', 'Alumno', 'Carrera', 'Celular', 'Estado']);
        
        $filtro = $_SESSION['ultimo_filtro_asistencias'];

        // Extraemos solo la parte del WHERE de los parámetros guardados
        // para asegurar que el Excel tenga los mismos filtros que la pantalla
        $queryExport = "SELECT 
                            a.fecha_hora, 
                            al.dni, 
                            al.apellidos_nombres, 
                            al.carrera, 
                            al.celular, 
                            al.estado
                        FROM asistencias a
                        INNER JOIN alumnos al ON a.alumno_dni = al.dni
                        WHERE a.id IN (
                            SELECT id FROM (
                                SELECT id FROM asistencias " . strstr($filtro['query'], 'WHERE') . "
                            ) AS subquery
                        )
                        GROUP BY a.id 
                        ORDER BY a.fecha_hora DESC";

        /* NOTA: Si el strstr sigue fallando, es mejor usar la consulta base con los parámetros */
        $stmt = $db->prepare($filtro['query']);
        $stmt->execute($filtro['params']);
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Eliminamos columnas internas que no van en el Excel
            unset($row['id']); 
            fputcsv($output, $row);
        }
        fclose($output);
        exit();
    } 
    break;

    // ==========================================
    // GESTIÓN DE ALUMNOS (CRUD)
    // ==========================================
    case 'superadmin/alumnos':
    case 'admin/alumnos':
        if (isset($_SESSION['rol'])) {
            $query = "SELECT * FROM alumnos ORDER BY CASE estado WHEN 'egresado' THEN 1 WHEN 'activo' THEN 2 ELSE 3 END ASC, apellidos_nombres ASC";
            $alumnos = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
            require_once '../app/views/alumnos/listar.php';
        } break;

    case 'alumnos/nuevo':
        if (isset($_SESSION['rol'])) require_once '../app/views/alumnos/crear.php';
        break;

    case 'alumnos/guardar':
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['rol'])) {
            $sql = "INSERT INTO alumnos (dni, apellidos_nombres, carrera, correo_institucional, celular, estado) VALUES (?, ?, ?, ?, ?, ?)";
            $db->prepare($sql)->execute([$_POST['dni'], $_POST['nombres'], $_POST['carrera'], $_POST['correo'], $_POST['celular'], $_POST['estado']]);
            header("Location: ../" . $_SESSION['rol'] . "/alumnos?msg=created");
            exit();
        } break;

    case 'alumnos/editar':
        if (isset($_SESSION['rol'])) {
            $stmt = $db->prepare("SELECT * FROM alumnos WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
            require_once '../app/views/alumnos/editar.php';
        } break;

    case 'alumnos/actualizar':
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['rol'])) {
            $sql = "UPDATE alumnos SET dni=?, apellidos_nombres=?, carrera=?, correo_institucional=?, celular=?, estado=? WHERE id=?";
            $db->prepare($sql)->execute([$_POST['dni'], $_POST['nombres'], $_POST['carrera'], $_POST['correo'], $_POST['celular'], $_POST['estado'], $_POST['id']]);
            header("Location: ../" . $_SESSION['rol'] . "/alumnos?msg=updated");
            exit();
        } break;

    case 'alumnos/eliminar':
        if (isset($_SESSION['rol'])) {
            $db->prepare("DELETE FROM alumnos WHERE id = ?")->execute([$_GET['id']]);
            header("Location: ../" . $_SESSION['rol'] . "/alumnos?msg=deleted");
            exit();
        } break;

    case 'superadmin/alumnos/carga-masiva':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') require_once '../app/views/superadmin/carga_alumnos.php';
        break;

    case 'superadmin/alumnos/vaciar':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
            $db->query("SET FOREIGN_KEY_CHECKS = 0");
            $db->query("TRUNCATE TABLE asistencias");
            $db->query("TRUNCATE TABLE alumnos");
            $db->query("SET FOREIGN_KEY_CHECKS = 1");
            header("Location: ../alumnos?msg=cleared"); exit();
        } break;
        // ==========================================
// GESTIÓN DE USUARIOS (SOLO SUPERADMIN)
// ==========================================
case 'superadmin/usuarios':
    // Verificamos que el usuario esté logueado y sea SuperAdmin
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
        
        // Consultamos todos los usuarios registrados para enviarlos a la tabla
        $usuarios = $db->query("SELECT id, nombre, email, rol, fecha_registro 
                                FROM usuarios 
                                ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        
        // Cargamos el archivo de la vista
        require_once '../app/views/superadmin/listar_usuarios.php';
        
    } else {
        // Si no es SuperAdmin, lo mandamos al login
        header("Location: login");
        exit();
    }
    break;
    // --- CREAR NUEVO USUARIO ---
case 'superadmin/nuevo':
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
        require_once '../app/views/superadmin/crear_usuario.php';
    } break;

case 'superadmin/guardar':
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['rol'] === 'superadmin') {
        $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); // Encriptación de seguridad
        $db->prepare("INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)")
           ->execute([$_POST['nombre'], $_POST['email'], $pass, $_POST['rol']]);
        header("Location: ../superadmin/usuarios?msg=created");
        exit();
    } break;

// --- EDITAR USUARIO ---
case 'superadmin/usuarios/editar':
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $usuario_edit = $stmt->fetch(PDO::FETCH_ASSOC);
        require_once '../app/views/superadmin/editar_usuario.php';
    } break;

case 'superadmin/usuarios/actualizar':
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['rol'] === 'superadmin') {
        if (!empty($_POST['pass'])) {
            $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET nombre=?, email=?, rol=?, password=? WHERE id=?";
            $db->prepare($sql)->execute([$_POST['nombre'], $_POST['email'], $_POST['rol'], $pass, $_POST['id']]);
        } else {
            $sql = "UPDATE usuarios SET nombre=?, email=?, rol=? WHERE id=?";
            $db->prepare($sql)->execute([$_POST['nombre'], $_POST['email'], $_POST['rol'], $_POST['id']]);
        }
        header("Location: ../../superadmin/usuarios?msg=updated"); 
        exit();
    } break;

// --- ELIMINAR USUARIO ---
case 'superadmin/usuarios/eliminar':
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
        // Evitar que el SuperAdmin se elimine a sí mismo
        if ($_GET['id'] != $_SESSION['user_id']) {
            $db->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$_GET['id']]);
            header("Location: ../../superadmin/usuarios?msg=deleted");
        } else {
            header("Location: ../../superadmin/usuarios?msg=error_self");
        }
        exit();
    } break;

// ==========================================
    // GESTIÓN DE CUBÍCULOS (Filial Sur)
    // ==========================================

    // 1. PANEL DE MONITOREO (ADMIN / SUPERADMIN)
    case 'superadmin/cubiculos':
    case 'admin/cubiculos':
        if (isset($_SESSION['rol'])) {
            // 1. CONSULTA PARA CUBÍCULOS OCUPADOS (MAPA)
            $queryOcupados = "SELECT p.*, al_p.apellidos_nombres, al_p.carrera 
                              FROM prestamos_cubiculos p 
                              INNER JOIN (
                                  SELECT t1.dni, t1.apellidos_nombres, t1.carrera 
                                  FROM alumnos t1
                                  WHERE t1.id = (
                                      SELECT t2.id FROM alumnos t2 
                                      WHERE t2.dni = t1.dni 
                                      ORDER BY CASE estado 
                                          WHEN 'egresado' THEN 1 
                                          WHEN 'activo' THEN 2 
                                          ELSE 3 END ASC 
                                      LIMIT 1
                                  )
                              ) AS al_p ON p.alumno_dni = al_p.dni 
                              WHERE p.estado = 'ocupado' 
                              ORDER BY p.hora_inicio ASC";
            
            $prestamos = $db->query($queryOcupados)->fetchAll(PDO::FETCH_ASSOC);

            // 2. CONSULTA PARA EL HISTORIAL (TABLA INFERIOR)
            // Usamos la misma lógica pero filtramos por estado 'libre' y fecha de hoy
            $queryHistorial = "SELECT p.*, al_h.apellidos_nombres, al_h.carrera 
                               FROM prestamos_cubiculos p 
                               INNER JOIN (
                                   SELECT t1.dni, t1.apellidos_nombres, t1.carrera 
                                   FROM alumnos t1
                                   WHERE t1.id = (
                                       SELECT t2.id FROM alumnos t2 
                                       WHERE t2.dni = t1.dni 
                                       ORDER BY CASE estado 
                                           WHEN 'egresado' THEN 1 
                                           WHEN 'activo' THEN 2 
                                           ELSE 3 END ASC 
                                       LIMIT 1
                                   )
                               ) AS al_h ON p.alumno_dni = al_h.dni 
                               WHERE p.estado = 'libre' AND p.fecha_prestamo = CURDATE()
                               ORDER BY p.hora_fin DESC";
            
            $historial = $db->query($queryHistorial)->fetchAll(PDO::FETCH_ASSOC);
            
            require_once '../app/views/cubiculos/gestion.php';
        } break;

    // 2. VISTA PÚBLICA PARA QUE EL ALUMNO SE REGISTRE
    case 'registro-cubiculo':
        require_once '../app/views/cubiculos/registro_publico.php';
        break;

    // 3. PROCESAR EL REGISTRO DEL ALUMNO (DESDE TABLET/KIOSKO)
    case 'procesar-cubiculo-publico':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dni = $_POST['dni'];
            $num = $_POST['numero_cubiculo'];
            $tiempo = $_POST['tiempo_estancia'];

            // Validar si el alumno existe y su estado es apto
            $stmt = $db->prepare("SELECT apellidos_nombres, estado FROM alumnos WHERE dni = ? 
                                  ORDER BY CASE estado WHEN 'egresado' THEN 1 WHEN 'activo' THEN 2 ELSE 3 END ASC LIMIT 1");
            $stmt->execute([$dni]);
            $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verificar si el cubículo no se ocupó hace un segundo
            $check = $db->prepare("SELECT id FROM prestamos_cubiculos WHERE numero_cubiculo = ? AND estado = 'ocupado'");
            $check->execute([$num]);

            if (!$alumno || $alumno['estado'] === 'inactivo') {
                header("Location: registro-cubiculo?msg=error_alumno");
            } elseif ($check->fetch()) {
                header("Location: registro-cubiculo?msg=ocupado");
            } else {
                // Insertar el préstamo
                $ins = $db->prepare("INSERT INTO prestamos_cubiculos (alumno_dni, numero_cubiculo, tiempo_solicitado) VALUES (?, ?, ?)");
                $ins->execute([$dni, $num, $tiempo]);
                
                $nombre = urlencode($alumno['apellidos_nombres']);
                header("Location: registro-cubiculo?msg=success&nombre=$nombre&num=$num&mins=$tiempo");
            }
            exit();
        } break;

    // 4. FINALIZAR PRÉSTAMO (LIBERAR CUBÍCULO)
    case 'cubiculos/finalizar':
        if (isset($_GET['id']) && isset($_SESSION['rol'])) {
            $id = $_GET['id'];
            
            // Actualizamos el estado y registramos la hora de salida real
            $stmt = $db->prepare("UPDATE prestamos_cubiculos SET estado = 'libre', hora_fin = CURRENT_TIME WHERE id = ?");
            $stmt->execute([$id]);

            header("Location: ../" . $_SESSION['rol'] . "/cubiculos?msg=liberado");
            exit();
        } break;
case 'cubiculos/exportar':
    if (isset($_SESSION['rol'])) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=reporte_cubiculos_'.date('Y-m-d').'.csv');
        
        $output = fopen('php://output', 'w');
        // Soporte para caracteres especiales (tildes)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); 
        
        // Encabezados del Excel
        fputcsv($output, ['Fecha', 'Cubiculo', 'DNI', 'Alumno', 'Carrera', 'Entrada', 'Salida', 'Minutos Solicitados']);
        
        // Consulta del historial completo o del día
        $query = "SELECT 
                    p.fecha_prestamo, 
                    p.numero_cubiculo, 
                    p.alumno_dni, 
                    al_p.apellidos_nombres, 
                    al_p.carrera, 
                    p.hora_inicio, 
                    p.hora_fin, 
                    p.tiempo_solicitado
                  FROM prestamos_cubiculos p 
                  INNER JOIN (
                      SELECT t1.dni, t1.apellidos_nombres, t1.carrera FROM alumnos t1
                      WHERE t1.id = (
                          SELECT t2.id FROM alumnos t2 WHERE t2.dni = t1.dni 
                          ORDER BY CASE estado WHEN 'egresado' THEN 1 WHEN 'activo' THEN 2 ELSE 3 END ASC LIMIT 1
                      )
                  ) AS al_p ON p.alumno_dni = al_p.dni 
                  WHERE p.estado = 'libre' 
                  ORDER BY p.fecha_prestamo DESC, p.hora_fin DESC";
        
        $stmt = $db->query($query);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit();
    } break;








// --- RUTAS PARA COMPUTADORAS ---
// ==========================================
// GESTIÓN DE COMPUTADORAS (ADMIN & SUPERADMIN)
// ==========================================
case 'admin/computadoras':
case 'superadmin/computadoras':
    if (isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'superadmin')) {
        
        // 1. Obtener PCs OCUPADAS para el Mapa Visual
        $queryActivos = "SELECT p.*, al.apellidos_nombres, al.carrera 
                         FROM prestamos_computadoras p 
                         INNER JOIN alumnos al ON p.alumno_dni = al.dni 
                         WHERE p.estado = 'ocupado' 
                         AND al.id = (SELECT id FROM alumnos WHERE dni = al.dni ORDER BY CASE estado WHEN 'egresado' THEN 1 WHEN 'activo' THEN 2 ELSE 3 END ASC LIMIT 1)
                         ORDER BY p.numero_pc ASC";
        $prestamos_pc = $db->query($queryActivos)->fetchAll(PDO::FETCH_ASSOC);

        // 2. Obtener Historial de HOY para la Tabla
        $queryHistorial = "SELECT p.*, al.apellidos_nombres, al.carrera 
                           FROM prestamos_computadoras p 
                           INNER JOIN alumnos al ON p.alumno_dni = al.dni 
                           WHERE p.estado = 'libre' AND p.fecha_prestamo = CURDATE()
                           ORDER BY p.hora_fin DESC";
        $historial_pc = $db->query($queryHistorial)->fetchAll(PDO::FETCH_ASSOC);

        require_once '../app/views/computadoras/gestion.php';
    } else {
        header("Location: ../login");
    }
    break;

case 'computadoras/finalizar':
    if (isset($_GET['id'])) {
        $db->prepare("UPDATE prestamos_computadoras SET estado = 'libre', hora_fin = CURRENT_TIME WHERE id = ?")->execute([$_GET['id']]);
        header("Location: ../" . $_SESSION['rol'] . "/computadoras?msg=liberado");
    } break;

// --- RUTAS PÚBLICAS DE COMPUTADORAS ---
case 'registro-pc':
    // Esta ruta carga la vista del formulario para el alumno
    require_once '../app/views/computadoras/registro_publico.php';
    break;

case 'procesar-pc-publico':
    // Esta ruta procesa los datos enviados por el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dni = $_POST['dni'];
        $num_pc = $_POST['numero_pc'];
        $tiempo = $_POST['tiempo_estancia'];

        // Validar alumno activo o egresado
        $stmt = $db->prepare("SELECT apellidos_nombres FROM alumnos WHERE dni = ? AND estado IN ('activo', 'egresado') LIMIT 1");
        $stmt->execute([$dni]);
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($alumno) {
            $ins = $db->prepare("INSERT INTO prestamos_computadoras (alumno_dni, numero_pc, tiempo_solicitado) VALUES (?, ?, ?)");
            $ins->execute([$dni, $num_pc, $tiempo]);
            
            header("Location: registro-pc?msg=success&nombre=" . urlencode($alumno['apellidos_nombres']) . "&num=" . $num_pc);
        } else {
            header("Location: registro-pc?msg=error_alumno");
        }
        exit();
    }
    break;








    // ==========================================
    // OTROS (USUARIOS, LOGOUT)
    // ==========================================
    case 'logout':
        session_destroy(); header("Location: inicio"); exit();
        break;

    default:
        require_once '../app/views/error.php';
        break;
}