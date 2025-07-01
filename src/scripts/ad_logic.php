<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
        require 'conn.php'; // Conexión a la base de datos
        require 'imgOpt.php'; // Script de conversión

        $imagenesSubidas = [];

        // Si es un array de imágenes (múltiples imágenes)
        if (is_array($_FILES['image']['name'])) {
            $fileCount = count($_FILES['image']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $fileTmpName = $_FILES['image']['tmp_name'][$i];
                $fileName = $_FILES['image']['name'][$i];

                // Procesar cada archivo
                $rutaImagen = convertirImagenAWebP($fileTmpName, __DIR__ . '/../../public/img/');

                // Agregar la ruta de la imagen al array
                $imagenesSubidas[] = $rutaImagen;
            }
            $rutasImagenes = implode(",", $imagenesSubidas);
        }
        // Unir todas las rutas con coma
    } else {
        echo json_encode(["status" => "error", "message" => "No se subieron imágenes."]);
    }

    // Obtiene y limpia los datos enviados
    $id = trim($_POST["id"] ?? "");
    $id2 = trim($_POST["id2"] ?? "");
    $url = trim($_POST["url"] ?? "");
    $url2 = trim($_POST["url2"] ?? "");
    $imagen = explode(",", $rutasImagenes)[0];
    $imagen2 = explode(",", $rutasImagenes)[1];

    // Validación básica
    if (empty($id) || empty($url) || empty($id2) || empty($url2)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    try {
        // Prepara la primera consulta para evitar inyecciones SQL
        $stmt1 = $pdo->prepare("UPDATE ads SET url = :url, imagen = :imagen WHERE id = :id");
        $stmt1->execute(["url" => $url, "imagen" => $imagen, "id" => $id]);

        // Prepara la segunda consulta para evitar inyecciones SQL
        $stmt2 = $pdo->prepare("UPDATE ads SET url = :url, imagen = :imagen WHERE id = :id");
        $stmt2->execute(["url" => $url2, "imagen" => $imagen2, "id" => $id2]);

        echo json_encode(["status" => "success", "message" => "Ad actualizada correctamente."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al actualizar la ad: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
