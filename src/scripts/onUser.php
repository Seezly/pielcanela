<?php
session_start();
require "conn.php";
try {
    $session_id = session_id();
    $last_activity = date('Y-m-d H:i:s');

    // **1️⃣ Insertar o actualizar la actividad del usuario**
    $stmt = $pdo->prepare("REPLACE INTO usuarios_online (session_id, last_activity) VALUES (:session_id, :last_activity)");
    $stmt->execute([
        ':session_id' => $session_id,
        ':last_activity' => $last_activity,
    ]);

    // **2️⃣ Eliminar usuarios inactivos (sin actividad en los últimos 5 minutos)**
    $stmt = $pdo->prepare("DELETE FROM usuarios_online WHERE last_activity < NOW() - INTERVAL 5 MINUTE");
    $stmt->execute();
} catch (PDOException $e) {
    die(json_encode(["status" => "error", "message" => "Error en la base de datos: " . $e->getMessage()]));
}
