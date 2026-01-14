<?php
// public/index.php
session_start();
require_once '../app/core/Database.php';

// --- FUNCIÓN GLOBAL PARA REGISTRAR ASISTENCIA ---
function registrarAsistencia($db, $dni) {
    $ins = $db->prepare("INSERT INTO asistencias (alumno_dni) VALUES (?)");
    $ins->execute([$dni]);
}

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
    // DASHBOARDS
    // ==========================================
    case 'superadmin':
    case 'admin':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === $url) {
            $totalUsuarios = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
            $totalAlumnos = $db->query("SELECT COUNT(*) FROM alumnos")->fetchColumn();
            require_once "../app/views/{$url}/dashboard.php";
        } else { header("Location: login"); }
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
    // OTROS (USUARIOS, LOGOUT)
    // ==========================================
    case 'logout':
        session_destroy(); header("Location: inicio"); exit();
        break;

    default:
        require_once '../app/views/error.php';
        break;
}