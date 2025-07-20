<?php
header('Content-Type: application/json');
// Directorio de destino
$destino = __DIR__ . '/../../public/img/';

// Crear el directorio si no existe
if (!file_exists($destino)) {
    mkdir($destino, 0755, true);
}

// Verificar si el directorio de destino es escribible
if (!is_writable($destino)) {
    die(json_encode(["status" => "error", "message" => "El directorio de destino no es escribible."]));
}

// Función para convertir la imagen a WebP y devolver la ruta
function convertirImagenAWebP($imagenRuta, $destinoRuta)
{
    $info = getimagesize($imagenRuta);
    $ext = pathinfo($imagenRuta, PATHINFO_EXTENSION);

    if ($info === false) {
        die(json_encode(["status" => "error", "message" => "No es una imagen válida."]));
    }

    switch ($info[2]) {
        case IMAGETYPE_JPEG:
            $imagen = imagecreatefromjpeg($imagenRuta);
            break;
        case IMAGETYPE_PNG:
            $imagen = imagecreatefrompng($imagenRuta);
            break;
        default:
            die(json_encode(["status" => "error", "message" => "Formato de imagen no soportado."]));
    }

    if ($imagen === false) {
        die(json_encode(["status" => "error", "message" => "No se pudo crear la imagen desde el archivo."]));
    }

    // Nombre único para evitar sobreescritura
    $nuevoNombre = uniqid() . '.webp';
    $rutaCompleta = realpath($destinoRuta) . DIRECTORY_SEPARATOR . $nuevoNombre;

    // Guardar la imagen en formato WebP
    if (!imagewebp($imagen, $rutaCompleta)) {
        die(json_encode(["status" => "error", "message" => "No se pudo guardar la imagen en formato WebP."]));
    }

    // Liberar memoria
    imagedestroy($imagen);

    // Devolver la ruta relativa de la imagen para la base de datos
    return "/public/img/" . $nuevoNombre;
}
