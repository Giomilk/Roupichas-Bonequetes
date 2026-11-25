<?php
/**
 * Roupichas-Bonequetes
 * Encerramento de Sessão
 */
require_once __DIR__ . '/config/bd.php';
require_once __DIR__ . '/includes/logger.php';

if (!empty($_SESSION['usuario_id'])) {
    registrarLog('LOGOUT', 'Usuário encerrou a sessão através do logout.php.');
}

$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header('Location: index.php');
exit;
