<?php
$ip = $_SERVER['REMOTE_ADDR']; // Obtiene la IP del usuario

// Intenta insertar la visita única
$sql = "INSERT INTO visitas (ip_address, visit_date)
        SELECT :ip, NOW() FROM DUAL
        WHERE NOT EXISTS (
            SELECT 1 FROM visitas WHERE ip_address = :ip_check AND DATE(visit_date) = CURDATE()
        ) LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'ip' => $ip,
    'ip_check' => $ip  // Debes asignar el segundo parámetro también
]);
