<?php
require_once __DIR__ . '/../config/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_admin_session() {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "No autorizado. Inicia sesión."]);
        exit;
    }
    return $_SESSION['privilegios'];
}

function require_admin_privileges($allowed = ['administrador', 'vendedor', 'sysadmin']) {
    $privilegios = require_admin_session();
    if (!in_array($privilegios, $allowed, true)) {
        http_response_code(403);
        echo json_encode(["status" => "error", "message" => "No tienes privilegios para realizar esta acción."]);
        exit;
    }
}
