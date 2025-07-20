<?php
header('Content-Type: application/json');
session_start();
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtiene y limpia los datos enviados
    $user = trim($_POST["user"] ?? "");
    $pass = trim($_POST["pass"] ?? "");

    // Validación básica
    if (empty($user) || empty($pass)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE user=:user");
        $stmt->execute(["user" => $user]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['pass'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['user'];
            $_SESSION['privilegios'] = $user['privilegios'];
            echo json_encode(["status" => "success", "message" => "Usuario autentificado con éxito."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Usuario o contraseña inválida: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
