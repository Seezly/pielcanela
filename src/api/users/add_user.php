<?php
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtiene y limpia los datos enviados
    $nombre = trim($_POST["nombre"] ?? "");
    $privilegios = trim($_POST["privilegios"] ?? "");
    $user = $_POST["user"] ?? "";
    $pass = $_POST["pass"] ?? "";

    // Validación básica
    if (empty($nombre) || empty($privilegios) || empty($user) || empty($pass)) {
        echo json_encode(["status" => "error", "message" => "El nombre es obligatorio."]);
        exit;
    }

    try {
        // Prepara la consulta para evitar inyecciones SQL
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, privilegios, user, pass) VALUES (:nombre, :privilegios, :user, :pass)");
        $stmt->execute(["nombre" => $nombre, "privilegios" => $privilegios, "user" => $user, "pass" => password_hash($pass, PASSWORD_DEFAULT)]);

        echo json_encode(["status" => "success", "message" => "Usuario agregado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al agregar el usuario: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
