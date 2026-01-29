<?php

require_once __DIR__ . '/../config/config.php';
session_start();
require 'conn.php';
require 'csrf.php';
require 'videoToWebm.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";
    $is_video = $_POST["is_video"] ?? "";
    // Obtiene y limpia los datos enviados
    $id = trim($_POST["id"] ?? "");
    $url = trim($_POST["url"] ?? "");

    if (!validate_csrf_token($token)) {
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }

    // Validación básica
    if (empty($id) || empty($url)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0] && $is_video == "false")) {
        require 'imgOpt.php'; // Script de conversión

        $imagenesSubidas = [];

        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];

        // Procesar cada archivo
        $rutaImagen = convertirImagenAWebP($fileTmpName, __DIR__ . '/../../public/img/');

        $imagen = isset($rutaImagen) ? $rutaImagen : null;
        
        if (!isset($rutaImagen) || empty($rutaImagen)) {
            // No se subió ninguna imagen, obtener ambas rutas actuales
            $stmt = $pdo->prepare("SELECT imagen FROM ads WHERE id = :id");
            $stmt->execute(["id" => $id]);
            $imagen = $stmt->fetchColumn();
        } else {
            // Si se subieron imágenes, asignar según corresponda
            $imagen = $rutaImagen ?? null;
        }

        try {
            // Prepara la primera consulta para evitar inyecciones SQL
            $stmt1 = $pdo->prepare("UPDATE ads SET url = :url, imagen = :imagen, visitas = 0 WHERE id = :id");
            $stmt1->execute(["url" => $url, "imagen" => $imagen, "id" => $id]);

            echo json_encode(["status" => "success", "message" => "Ad actualizada correctamente."]);
            exit;
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Error al actualizar la ad: " . $e->getMessage()]);
            exit;
        }
    } else if (!empty($_FILES['image']['name'][0]) && $is_video == "true") {
        try {
            // Crear Task en freeconvert.com
            $taskResponse = initTask($api_key, $client);

            $task_id = json_decode($taskResponse)->id;
            $task_url = json_decode($taskResponse)->result->form->url;
            $task_signature = json_decode($taskResponse)->result->form->parameters->signature;

            if (!$task_id || !$task_url || !$task_signature) {
                echo json_encode(["status" => "error", "message" => "Error al iniciar la tarea de conversión de video."]);
                exit;
            }

            // Iniciar la subida del archivo
            $uploadResponse = uploadFile($task_url, $task_signature, $_FILES['image']['tmp_name'], $_FILES['image']['name'], $client, $api_key);
        
            if (!$uploadResponse) {
                echo json_encode(["status" => "error", "message" => "Error al subir el archivo de video."]);
                exit;
            }

            // Convertir el video a WebM
            $convertResponse = convertFile($api_key, $client, $task_id);

            if (!$convertResponse) {
                echo json_encode(["status" => "error", "message" => "Error al convertir el archivo de video."]);
                exit;
            }

            $videoRuta = downloadFile($api_key, $client, json_decode($convertResponse)->id);

            $video = isset($videoRuta) ? $videoRuta : null;
        
            if (!isset($videoRuta) || empty($videoRuta)) {
                // No se subió ningún video, obtener la ruta actual
                $stmt = $pdo->prepare("SELECT imagen FROM ads WHERE id = :id");
                $stmt->execute(["id" => $id]);
                $imagen = $stmt->fetchColumn();
            } else {
                // Si se subieron videos, asignar según corresponda
                $video = $videoRuta ?? null;
            }

            try {
                // Prepara la primera consulta para evitar inyecciones SQL
                $stmt1 = $pdo->prepare("UPDATE ads SET url = :url, imagen = :imagen, visitas = 0 WHERE id = :id");
                $stmt1->execute(["url" => $url, "imagen" => $video, "id" => $id]);

                echo json_encode(["status" => "success", "message" => "Ad actualizada correctamente."]);
                exit;
            } catch (PDOException $e) {
                echo json_encode(["status" => "error", "message" => "Error al actualizar la ad: " . $e->getMessage()]);
                exit;
            }
        
        } catch (\Throwable $th) {
            echo json_encode(["status" => "error", "message" => "Error en la conversión de video."]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se subieron imágenes."]);
        exit;
    }

} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
    exit;
}