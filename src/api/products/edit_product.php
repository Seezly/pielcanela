<?php
header('Content-Type: application/json');
session_start();
require '../../scripts/conn.php'; // Conexión a la base de datos
require '../../scripts/csrf.php';

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
        require '../../scripts/conn.php'; // Conexión a la base de datos
        require '../../scripts/imgOpt.php'; // Script de conversión

        $imagenesSubidas = [];

        // Si es un array de imágenes (múltiples imágenes)
        if (is_array($_FILES['image']['name'])) {
            $fileCount = count($_FILES['image']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $fileTmpName = $_FILES['image']['tmp_name'][$i];
                $fileName = $_FILES['image']['name'][$i];

                // Procesar cada archivo
                $rutaImagen = convertirImagenAWebP($fileTmpName, __DIR__ . '/../../../public/img/');

                // Agregar la ruta de la imagen al array
                $imagenesSubidas[] = $rutaImagen;
            }

            // Unir todas las rutas con coma
            $rutasImagenes = implode(",", $imagenesSubidas);
        } elseif (!is_array($_FILES['image']['name'])) {
            // Si solo se ha subido una imagen
            $fileTmpName = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];

            // Procesar la imagen
            $rutaImagen = convertirImagenAWebP($fileTmpName, __DIR__ . '/../../../public/img/');

            // Guardar la ruta de la imagen como string
            $rutasImagenes = $rutaImagen;
        } else {
            $imagen = ""; // En caso de que no se suba imagen
        }
    }

    // Obtiene y limpia los datos enviados
    $nombre = trim($_POST["nombre"] ?? "");
    $precio = trim($_POST["precio"] ?? "");
    $sku = trim($_POST["sku"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $categoria = trim($_POST["categoria"] ?? "");
    $descuento = trim($_POST["descuento"] ?? "");
    $precioD = trim($_POST["precioD"] ?? "");
    $porcentajeD = trim($_POST["porcentajeD"] ?? "");
    $id = trim($_POST["id"] ?? "");
    $destacado = trim($_POST["destacado"] ?? "") == "true" ? 1 : 0;
    $token = $_POST["csrf_token"] ?? "";

    if (!validate_csrf_token($token)) {
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }

    if (
        empty($id) ||
        empty($nombre) ||
        empty($precio) ||
        empty($sku) ||
        empty($descripcion) ||
        empty($categoria) ||
        strlen($descuento) === 0 ||  // Cambiar a strlen
        strlen($precioD) === 0 ||    // Cambiar a strlen
        strlen($porcentajeD) === 0 || // Cambiar a strlen
        empty($rutasImagenes)
    ) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        echo $id, $nombre, $precio, $sku, $descripcion, $categoria, $descuento, $precioD, $porcentajeD, $rutasImagenes;
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE productos SET nombre=:nombre, precio=:precio, descripcion=:descripcion, descuento=:descuento, precioD=:precioD, porcentajeD=:porcentajeD, sku=:sku, imagen=:imagen, destacado=:destacado WHERE id=:id");
        $stmt->execute([
            "nombre" => $nombre,
            "precio" => $precio,
            "descripcion" => $descripcion,
            "descuento" => $descuento,
            "precioD" => $precioD,
            "porcentajeD" => $porcentajeD,
            "sku" => $sku,
            "imagen" => $rutasImagenes,
            "id" => $id,
            "destacado" => $destacado
        ]);

        echo json_encode(["status" => "success", "message" => "Producto editado correctamente.", "destacado" => $destacado]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Error al editar el producto: " . $e->getMessage()]);
    }
}
