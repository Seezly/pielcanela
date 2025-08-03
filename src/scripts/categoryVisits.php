<?php
require_once __DIR__ . '/../config/config.php';

// Ensure we have a database connection
if (!isset($pdo)) {
    require __DIR__ . '/conn.php';
}

if (!empty($id)) {
    $stmt = $pdo->prepare('UPDATE categorias SET visitas = visitas + 1 WHERE id = ?');
    $stmt->execute([$id]);
}
?>
