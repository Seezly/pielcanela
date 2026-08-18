<?php

require_once __DIR__ . '/../config/config.php';
session_start();
if (!headers_sent()) {
    header('Content-Type: application/json');
}

// Garantiza una respuesta JSON incluso si PHP muere por un error fatal
// (debe registrarse ANTES de cualquier require para capturar todos).
ob_start();
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && ($error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR))) {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        if (!headers_sent()) {
            header('Content-Type: application/json');
        }
        echo json_encode([
            "status" => "error",
            "message" => "Error interno del servidor: " . $error['message'] . " en " . $error['file'] . ":" . $error['line']
        ]);
    }
});

try {
    require 'conn.php';
    require 'csrf.php';
    require 'require_auth.php';
    require 'videoToWebm.php';

    require_admin_privileges();

    // La conversión de video puede tardar varios minutos; evita el timeout de PHP.
    if (function_exists('set_time_limit')) {
        set_time_limit(300);
    }


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";
    $is_video = ($_POST["is_video"] ?? "") === "true";
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

    $tieneArchivo = isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK;

    if ($tieneArchivo && !$is_video) {
        require 'imgOpt.php'; // Script de conversión

        $imagen = convertirImagenAWebP($_FILES['image']['tmp_name'], __DIR__ . '/../../public/img/');

        try {
            // Prepara la consulta para evitar inyecciones SQL
            $stmt1 = $pdo->prepare("UPDATE ads SET url = :url, imagen = :imagen, visitas = 0 WHERE id = :id");
            $stmt1->execute(["url" => $url, "imagen" => $imagen, "id" => $id]);

            echo json_encode(["status" => "success", "message" => "Ad actualizada correctamente."]);
            exit;
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Error al actualizar la ad: " . $e->getMessage()]);
            exit;
        }
    } else if ($tieneArchivo && $is_video) {
        try {
            // Crear Task en freeconvert.com
            $taskResponse = initTask($api_key, $client);
            $taskData = json_decode($taskResponse);

            if (!$taskData || !isset($taskData->id, $taskData->result->form->url, $taskData->result->form->parameters->signature)) {
                echo json_encode(["status" => "error", "message" => "Error al iniciar la tarea de conversión de video."]);
                exit;
            }

            $task_id = $taskData->id;
            $task_url = $taskData->result->form->url;
            $task_signature = $taskData->result->form->parameters->signature;

            // Iniciar la subida del archivo
            $uploadResponse = uploadFile($task_url, $task_signature, $_FILES['image']['tmp_name'], $_FILES['image']['name'], $client, $api_key);

            if (!$uploadResponse) {
                echo json_encode(["status" => "error", "message" => "Error al subir el archivo de video."]);
                exit;
            }

            $format = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

            // Convertir el video a WebM
            $convertResponse = convertFile($api_key, $client, $format, $task_id);

            $convertData = json_decode($convertResponse);

            if (!$convertResponse || !$convertData || empty($convertData->id)) {
                echo json_encode(["status" => "error", "message" => "Error al convertir el archivo de video."]);
                exit;
            }

            $videoRuta = downloadFile($api_key, $client, $convertData->id);

            if (empty($videoRuta)) {
                // No se obtuvo el video, conservar la ruta actual
                $stmt = $pdo->prepare("SELECT imagen FROM ads WHERE id = :id");
                $stmt->execute(["id" => $id]);
                $video = $stmt->fetchColumn();
            } else {
                $video = $videoRuta;
            }

            try {
                // Prepara la consulta para evitar inyecciones SQL
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
        // No se subió archivo: actualizar solo la URL conservando la imagen actual
        try {
            $stmt1 = $pdo->prepare("UPDATE ads SET url = :url, visitas = 0 WHERE id = :id");
            $stmt1->execute(["url" => $url, "id" => $id]);

            echo json_encode(["status" => "success", "message" => "Ad actualizada correctamente."]);
            exit;
        } catch (PDOException $e) {
            echo json_encode(["status" => "error", "message" => "Error al actualizar la ad: " . $e->getMessage()]);
            exit;
        }
    }

} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
    exit;
}

} catch (\Throwable $e) {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode(["status" => "error", "message" => "Error interno del servidor: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine()]);
    exit;
}