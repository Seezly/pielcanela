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
    $id = trim($_POST['id'] ?? '');
    $destacado = trim($_POST['destacado'] ?? '');

    if (empty($nombre) || empty($id)) {
        echo json_encode(["status" => "error", "message" => "El nombre e ID son obligatorios."]);
        exit;
    }

    // Obtener imagen actual
    $stmtImg = $pdo->prepare("SELECT imagen FROM categorias WHERE id=:id");
    $stmtImg->execute(['id' => $id]);
    $currentImagen = $stmtImg->fetchColumn();
    $rutaImagen = $currentImagen;

    // Procesar nueva imagen si se sube
    if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['tmp_name'])) {
        require '../../scripts/imgOpt.php';
        if (is_array($_FILES['imagen']['tmp_name'])) {
            $fileTmpName = $_FILES['imagen']['tmp_name'][0];
        } else {
            $fileTmpName = $_FILES['imagen']['tmp_name'];
        }
        $rutaImagen = convertirImagenAWebP($fileTmpName, __DIR__ . '/../../../public/img/');

        // Eliminar imagen anterior
        if (!empty($currentImagen)) {
            $oldPath = __DIR__ . '/../../..' . $currentImagen;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
    }

    try {
        $stmt = $pdo->prepare("UPDATE categorias SET nombre=:nombre, destacado=:destacado, imagen=:imagen WHERE id=:id");
        $stmt->execute([
            'nombre' => $nombre,
            'destacado' => $destacado,
            'imagen' => $rutaImagen,
            'id' => $id
        ]);
        echo json_encode([
            'status' => 'success',
            'message' => 'Categoría editada correctamente.',
            'imagen' => BASE_URL . ltrim($rutaImagen, '/')
        ]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al editar la categoría: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]); 
}
