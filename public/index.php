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

    case 'superadmin':
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
            $stmt = $db->prepare("SELECT COUNT(*) as total FROM usuarios");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $totalUsuarios = $row['total'];
            require_once '../app/views/superadmin/dashboard.php';
        } else { header("Location: login"); }
        break;

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
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $rol = $_POST['rol'];

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
            $nombre = $_POST['nombre']; $email = $_POST['email']; $rol = $_POST['rol'];
            $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            if($stmt->execute([$nombre, $email, $pass, $rol])) { header("Location: ../superadmin/usuarios?msg=created"); }
            else { header("Location: ../superadmin/usuarios?msg=error"); }
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