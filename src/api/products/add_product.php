<?php
require '../../scripts/conn.php'; // Conexión a la base de datos

// Verifica si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verifica si se ha enviado el formulario
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
        } else {
            // Si solo se ha subido una imagen
            $fileTmpName = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];

            // Procesar la imagen
            $rutaImagen = convertirImagenAWebP($fileTmpName, __DIR__ . '/../../../public/img/');

            // Guardar la ruta de la imagen como string
            $rutasImagenes = $rutaImagen;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se subieron imágenes."]);
    }

    // Obtiene y limpia los datos enviados
    $nombre = trim($_POST["nombre"] ?? "");
    $precio = trim($_POST["precio"] ?? "");
    $sku = trim($_POST["sku"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $categoria = $_POST["categoria"] ?? "";
    $atributo = $_POST["atributo"] ?? "";
    $opciones = $_POST["atributo"] == 4 ? "" : strtolower($_POST["opciones"]);
    $descuento = trim($_POST["descuento"] ?? 0);
    $precioD = trim($_POST["precioD"] ?? 0);
    $porcentajeD = trim($_POST["porcentajeD"] ?? 0);

    if (empty($nombre) || empty($precio) || empty($sku) || empty($descripcion) || empty($categoria) || empty($rutasImagenes) || empty($atributo)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO productos (nombre, precio, descripcion, descuento, precioD, porcentajeD, sku, categoria, imagen, atributo_id, opciones) VALUES (:nombre, :precio, :descripcion, :descuento, :precioD, :porcentajeD, :sku, :categoria, :imagen, :atributo_id, :opciones)");
        $stmt->execute([
            "nombre" => $nombre,
            "precio" => $precio,
            "descripcion" => $descripcion,
            "descuento" => $descuento,
            "precioD" => $precioD,
            "porcentajeD" => $porcentajeD,
            "sku" => $sku,
            "categoria" => $categoria,
            "imagen" => $rutasImagenes,
            "atributo_id" => $atributo,
            "opciones" => $opciones,
        ]);
        header('Content-Type: application/json');
        echo json_encode(["status" => "success", "message" => "Producto agregado correctamente."]);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(["status" => "error", "message" => "Error al agregar el producto: " . $e->getMessage()]);
    }
}
