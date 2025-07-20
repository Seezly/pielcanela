<?php
header('Content-Type: application/json');
session_start();
require '../../scripts/conn.php';
require '../../scripts/csrf.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['csrf_token'] ?? '';
    if (!validate_csrf_token($token)) {
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }

    $id = trim($_POST['id'] ?? '');
    if (empty($id)) {
        echo json_encode(["status" => "error", "message" => "El id es obligatorio."]);
        exit;
    }

    if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
        require '../../scripts/imgOpt.php';
        if (is_array($_FILES['image']['tmp_name'])) {
            $fileTmpName = $_FILES['image']['tmp_name'][0];
        } else {
            $fileTmpName = $_FILES['image']['tmp_name'];
        }
        $rutaImagen = convertirImagenAWebP($fileTmpName, __DIR__ . '/../../public/img/');
    } else {
        $stmtImg = $pdo->prepare("SELECT imagen FROM slides WHERE id = :id");
        $stmtImg->execute(['id' => $id]);
        $rutaImagen = $stmtImg->fetchColumn();
    }

    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $enlace = trim($_POST['enlace'] ?? '');

    if (empty($titulo) || empty($enlace) || empty($rutaImagen)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE slides SET titulo=:titulo, descripcion=:descripcion, enlace=:enlace, imagen=:imagen WHERE id=:id");
        $stmt->execute([
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'enlace' => $enlace,
            'imagen' => $rutaImagen,
            'id' => $id
        ]);
        echo json_encode(["status" => "success", "message" => "Slide editado correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al editar el slide: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>
