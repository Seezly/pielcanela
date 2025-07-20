<?php
header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    require("conn.php");
    $id = $_GET["id"];

    if (empty($id)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE ads SET visitas = visitas + 1 WHERE id=:idAd");
        $stmt->execute(["idAd" => $id]);

        echo json_encode(["status" => "success", "message" => "Visita aumentada en la ad."]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(["status" => "error", "message" => "Error al contar la visita: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
