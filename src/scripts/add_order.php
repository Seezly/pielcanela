<?php
require_once __DIR__ . '/../config/config.php';
header('Content-Type: application/json');
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require("conn.php");
    require("csrf.php");
    $token = $_POST["csrf_token"] ?? "";
    if (!validate_csrf_token($token)) {
        echo json_encode(["status" => "error", "message" => "Token CSRF inválido."]);
        exit;
    }
    $producto_ids = $_POST["id"] ?? [];
    $carrito_id = uniqid();

    if (empty($producto_ids) || empty($carrito_id)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    try {
        $pdo->beginTransaction();
        $stmt1 = $pdo->prepare("INSERT INTO compras (producto_id, carrito_id, fecha) VALUES (:producto_id, :carrito_id, CURDATE())");

        foreach ($producto_ids as $producto_id) {
            $stmt1->execute(["producto_id" => $producto_id, "carrito_id" => $carrito_id]);
        }

        $pdo->commit();
        echo json_encode(["status" => "success", "message" => "Órden insertada correctamente."]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => "Error al insertar las órdenes: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
