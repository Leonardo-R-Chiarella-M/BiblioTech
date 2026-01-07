<?php
// public/index.php
session_start();
require_once '../app/core/Database.php';

$url = $_GET['url'] ?? $_GET['action'] ?? 'inicio';
$url = rtrim($url, '/');

$database = new Database();
$db = $database->getConnection();

switch ($url) {
    case 'inicio':
        require_once '../app/views/general/inicio.php';
        break;
        
    case 'login':
        if (isset($_SESSION['rol'])) {
            header("Location: " . $_SESSION['rol']);
            exit();
        }
        require_once '../app/views/login.php';
        break;

    case 'auth':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $pass  = $_POST['pass'];
            $query = "SELECT id, nombre, rol, password FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($pass, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nombre']  = $user['nombre'];
                    $_SESSION['rol']     = $user['rol'];
                    header("Location: " . $user['rol']); 
                    exit();
                }
            }
            header("Location: login?error=1");
            exit();
        }
        break;

    // --- DASHBOARDS (Aquí se cargan las estadísticas) ---
    case 'superadmin':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
            // Contamos usuarios
            $totalUsuarios = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
            // Contamos alumnos
            $totalAlumnos = $db->query("SELECT COUNT(*) FROM alumnos")->fetchColumn();
            
            require_once '../app/views/superadmin/dashboard.php';
        } else { header("Location: login"); }
        break;

    case 'admin':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
            // Contamos alumnos para el bibliotecario
            $totalAlumnos = $db->query("SELECT COUNT(*) FROM alumnos")->fetchColumn();
            
            require_once '../app/views/admin/dashboard.php';
        } else { header("Location: login"); }
        break;

    // --- SECCIÓN DE USUARIOS (SOLO SUPERADMIN) ---
    case 'superadmin/nuevo':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
            require_once '../app/views/superadmin/crear_usuario.php';
        } else { header("Location: login"); }
        break;

    case 'superadmin/usuarios':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
            $query = "SELECT id, nombre, email, rol, fecha_registro FROM usuarios ORDER BY id DESC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            require_once '../app/views/superadmin/listar_usuarios.php';
        } else { header("Location: login"); }
        break;

    case 'superadmin/usuarios/editar':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
            $id = $_GET['id'] ?? null;
            $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $usuario_edit = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuario_edit) {
                require_once '../app/views/superadmin/editar_usuario.php';
            } else { header("Location: ../usuarios"); }
        } else { header("Location: ../../login"); }
        break;

    case 'superadmin/usuarios/actualizar':
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['rol'] === 'superadmin') {
            $id = $_POST['id']; $nombre = $_POST['nombre']; $email = $_POST['email']; $rol = $_POST['rol'];
            if (!empty($_POST['pass'])) {
                $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET nombre=?, email=?, rol=?, password=? WHERE id=?";
                $params = [$nombre, $email, $rol, $pass, $id];
            } else {
                $sql = "UPDATE usuarios SET nombre=?, email=?, rol=? WHERE id=?";
                $params = [$nombre, $email, $rol, $id];
            }
            $stmt = $db->prepare($sql);
            if($stmt->execute($params)) { header("Location: ../usuarios?msg=updated"); }
            else { header("Location: ../usuarios?msg=error"); }
            exit();
        }
        break;

    case 'superadmin/usuarios/eliminar':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
            $id = $_GET['id'] ?? null;
            if ($id && $id != $_SESSION['user_id']) {
                $stmt = $db->prepare("DELETE FROM usuarios WHERE id = ?");
                if ($stmt->execute([$id])) { header("Location: ../../superadmin/usuarios?msg=deleted"); }
                else { header("Location: ../../superadmin/usuarios?msg=error"); }
            } else { header("Location: ../../superadmin/usuarios"); }
            exit();
        }
        break;

    case 'superadmin/guardar':
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['rol'] === 'superadmin') {
            $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            if($stmt->execute([$_POST['nombre'], $_POST['email'], $pass, $_POST['rol']])) { header("Location: ../superadmin/usuarios?msg=created"); }
            else { header("Location: ../superadmin/usuarios?msg=error"); }
            exit();
        }
        break;

    // --- SECCIÓN DE ALUMNOS (PARA AMBOS) ---
    case 'superadmin/alumnos':
    case 'admin/alumnos':
        if (isset($_SESSION['rol'])) {
            $query = "SELECT * FROM alumnos 
                      ORDER BY CASE estado WHEN 'egresado' THEN 1 WHEN 'activo' THEN 2 WHEN 'inactivo' THEN 3 END ASC, apellidos_nombres ASC";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            require_once '../app/views/alumnos/listar.php';
        } else { header("Location: ../login"); }
        break;

    case 'alumnos/nuevo':
        if (isset($_SESSION['rol'])) {
            require_once '../app/views/alumnos/crear.php';
        }
        break;

    case 'alumnos/guardar':
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['rol'])) {
            $sql = "INSERT INTO alumnos (dni, apellidos_nombres, carrera, correo_institucional, estado) VALUES (?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            if($stmt->execute([$_POST['dni'], $_POST['nombres'], $_POST['carrera'], $_POST['correo'], $_POST['estado']])) {
                header("Location: ../" . $_SESSION['rol'] . "/alumnos?msg=created");
            } else { header("Location: ../alumnos/nuevo?msg=error"); }
            exit();
        }
        break;
        // Dentro del switch en public/index.php

case 'superadmin/alumnos/carga-masiva':
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
        require_once '../app/views/superadmin/carga_alumnos.php';
    } else { header("Location: ../../login"); }
    break;

case 'superadmin/alumnos/procesar-carga':
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['rol'] === 'superadmin') {
        $archivo = $_FILES['archivo_csv']['tmp_name'];
        
        if (($gestor = fopen($archivo, "r")) !== FALSE) {
            // Omitir la primera línea (encabezados)
            // Cambiamos el tercer parámetro a ";" para usar punto y coma
            fgetcsv($gestor, 1000, ";"); 
            
            $db->beginTransaction();
            try {
                $stmt = $db->prepare("INSERT INTO alumnos (dni, apellidos_nombres, carrera, correo_institucional, estado) VALUES (?, ?, ?, ?, ?)");
                
                // Aplicamos el separador ";" también aquí
                while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
                    // Validar que la línea no esté vacía
                    if (count($datos) >= 3) {
                        $stmt->execute([
                            $datos[0], // DNI
                            $datos[1], // Apellidos y Nombres
                            $datos[2], // Carrera
                            $datos[3] ?? '', // Correo
                            $datos[4] ?? 'inactivo' // Estado
                        ]);
                    }
                }
                $db->commit();
                header("Location: ../alumnos?msg=mass_success");
            } catch (Exception $e) {
                $db->rollBack();
                header("Location: ../alumnos?msg=mass_error");
            }
            fclose($gestor);
        }
        exit();
    }
    break;


// --- OPERACIONES INDIVIDUALES DE ALUMNOS ---

case 'alumnos/editar':
    if (isset($_SESSION['rol'])) {
        $id = $_GET['id'] ?? null;
        $stmt = $db->prepare("SELECT * FROM alumnos WHERE id = ?");
        $stmt->execute([$id]);
        $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($alumno) {
            require_once '../app/views/alumnos/editar.php';
        } else { header("Location: ../" . $_SESSION['rol'] . "/alumnos"); }
    } else { header("Location: ../login"); }
    break;

case 'alumnos/actualizar':
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['rol'])) {
        $id = $_POST['id'];
        $dni = $_POST['dni'];
        $nombres = $_POST['nombres'];
        $carrera = $_POST['carrera'];
        $correo = $_POST['correo'];
        $estado = $_POST['estado'];

        $sql = "UPDATE alumnos SET dni=?, apellidos_nombres=?, carrera=?, correo_institucional=?, estado=? WHERE id=?";
        $stmt = $db->prepare($sql);
        if($stmt->execute([$dni, $nombres, $carrera, $correo, $estado, $id])) {
            header("Location: ../" . $_SESSION['rol'] . "/alumnos?msg=updated");
        } else { header("Location: ../alumnos/editar?id=$id&msg=error"); }
        exit();
    }
    break;

// --- OPERACIONES DE ELIMINACIÓN DE ALUMNOS ---

// Eliminar un alumno individual
case 'alumnos/eliminar':
    if (isset($_SESSION['rol'])) {
        $id = $_GET['id'] ?? null;
        $stmt = $db->prepare("DELETE FROM alumnos WHERE id = ?");
        if ($stmt->execute([$id])) {
            header("Location: ../" . $_SESSION['rol'] . "/alumnos?msg=deleted");
        } else {
            header("Location: ../" . $_SESSION['rol'] . "/alumnos?msg=error");
        }
        exit();
    }
    break;

// Vaciar tabla completa (SOLO SUPERADMIN)
case 'superadmin/alumnos/vaciar':
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
        $stmt = $db->prepare("TRUNCATE TABLE alumnos");
        if ($stmt->execute()) {
            header("Location: ../alumnos?msg=cleared");
        } else {
            header("Location: ../alumnos?msg=error");
        }
        exit();
    }
    break;



    case 'logout':
        session_destroy();
        header("Location: inicio");
        break;

    default:
        require_once '../app/views/error.php';
        break;
}