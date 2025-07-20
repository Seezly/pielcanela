<?php
header('Content-Type: application/json');
session_start();
require '../../scripts/conn.php'; // Conexión a la base de datos
require '../../scripts/csrf.php';

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtiene y limpia los datos enviados
    $nombre = trim($_POST["nombre"] ?? "");
    $privilegios = trim($_POST["privilegios"] ?? "");
    $user = trim($_POST["user"] ?? "");
    $pass = trim($_POST["pass"] ?? "");
    $id = trim($_POST["id"] ?? "");
    $token = $_POST["csrf_token"] ?? "";

    if (!validate_csrf_token($token)) {
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }

    // Validación básica
    if (empty($nombre) || empty($privilegios) || empty($user) || empty($pass) || empty($id)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("UPDATE usuarios SET nombre=:nombre, privilegios=:privilegios, user=:user, pass=:pass WHERE id=:id");
        $stmt->execute(["nombre" => $nombre, "privilegios" => $privilegios, "user" => $user, "pass" => password_hash($pass, PASSWORD_DEFAULT), "id" => $id]);

        echo json_encode(["status" => "success", "message" => "Usuario editado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al editar al usuario: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
