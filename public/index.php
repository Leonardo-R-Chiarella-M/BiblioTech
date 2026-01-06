<?php
// public/index.php
session_start();
require_once '../app/core/Database.php';

$url = $_GET['url'] ?? 'inicio';
$url = rtrim($url, '/');

switch ($url) {
    case 'inicio':
        require_once '../app/views/general/inicio.php';
        break;
        
    case 'login':
        // Si ya hay sesión, mandarlo a su panel directamente
        if (isset($_SESSION['rol'])) {
            header("Location: " . $_SESSION['rol']);
            exit();
        }
        require_once '../app/views/login.php';
        break;

    case 'auth':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $database = new Database();
            $db = $database->getConnection();

            $email = $_POST['email'];
            $pass  = $_POST['pass'];

            $query = "SELECT id, nombre, rol, password FROM usuarios WHERE email = :email LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                // Verificación segura con Hash
                if (password_verify($pass, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['nombre']  = $user['nombre'];
                    $_SESSION['rol']     = $user['rol'];

                    header("Location: " . $user['rol']); 
                    exit();
                }
            }
            echo "<script>alert('Correo o clave incorrecta'); window.location.href='login';</script>";
        }
        break;

    case 'superadmin':
        // Protección: Solo entra si la sesión dice 'superadmin'
        if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
            require_once '../app/views/superadmin/dashboard.php';
        } else {
            header("Location: login");
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