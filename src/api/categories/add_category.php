<?php
require_once __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
session_start();
require '../../scripts/conn.php'; // Conexión a la base de datos
require '../../scripts/csrf.php';

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($token)) {
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $destacado = trim($_POST['destacado'] ?? '');

    if (empty($nombre)) {
        echo json_encode(["status" => "error", "message" => "El nombre es obligatorio."]);
        exit;
    }

    if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['tmp_name'])) {
        require '../../scripts/imgOpt.php';
        if (is_array($_FILES['imagen']['tmp_name'])) {
            $fileTmpName = $_FILES['imagen']['tmp_name'][0];
        } else {
            $fileTmpName = $_FILES['imagen']['tmp_name'];
        }
        $rutaImagen = convertirImagenAWebP($fileTmpName, __DIR__ . '/../../../public/img/');
    } else {
        echo json_encode(["status" => "error", "message" => "No se subió imagen."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO categorias (nombre, destacado, imagen) VALUES (:nombre, :destacado, :imagen)");
        $stmt->execute([
            'nombre' => $nombre,
            'destacado' => $destacado,
            'imagen' => $rutaImagen
        ]);
        echo json_encode([
            'status' => 'success',
            'message' => 'Categoría agregada correctamente.',
            'imagen' => BASE_URL . ltrim($rutaImagen, '/')
        ]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al agregar la categoría: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
