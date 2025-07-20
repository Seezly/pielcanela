<?php
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    require("conn.php");

    try {
        // Obtener los últimos 7 días agrupados por día
        $stmt7 = $pdo->prepare("
            SELECT DATE(fecha) as fecha, COUNT(*) as total 
            FROM compras 
            WHERE fecha >= NOW() - INTERVAL 7 DAY 
            GROUP BY DATE(fecha) 
            ORDER BY fecha ASC
        ");
        $stmt7->execute();
        $data7_raw = $stmt7->fetchAll(PDO::FETCH_ASSOC);

        // Obtener los últimos 14 días antes de los últimos 7 días agrupados por día
        $stmt14 = $pdo->prepare("
            SELECT DATE(fecha) as fecha, COUNT(*) as total 
            FROM compras 
            WHERE fecha >= NOW() - INTERVAL 14 DAY AND fecha < NOW() - INTERVAL 7 DAY 
            GROUP BY DATE(fecha) 
            ORDER BY fecha ASC
        ");
        $stmt14->execute();
        $data14_raw = $stmt14->fetchAll(PDO::FETCH_ASSOC);

        // Obtener los últimos 7 días agrupados por día
        $stmtv7 = $pdo->prepare("
            SELECT DATE(visit_date) as fecha, COUNT(*) as total
            FROM visitas
            WHERE visit_date >= NOW() - INTERVAL 7 DAY
            GROUP BY DATE(visit_date)
            ORDER BY fecha ASC
        ");
        $stmtv7->execute();
        $datav7_raw = $stmtv7->fetchAll(PDO::FETCH_ASSOC);

        // Obtener los últimos 14 días antes de los últimos 7 días agrupados por día
        $stmtv14 = $pdo->prepare("
            SELECT DATE(visit_date) as fecha, COUNT(*) as total
            FROM visitas
            WHERE visit_date >= NOW() - INTERVAL 14 DAY AND visit_date < NOW() - INTERVAL 7 DAY
            GROUP BY DATE(visit_date)
            ORDER BY fecha ASC
        ");
        $stmtv14->execute();
        $datav14_raw = $stmtv14->fetchAll(PDO::FETCH_ASSOC);

        // Formatear los resultados para devolver un array de conteos por día
        function formatData($rawData, $days)
        {
            $result = array_fill(0, $days, 0); // Array con ceros por defecto
            $dates = [];

            foreach ($rawData as $row) {
                $dates[$row["fecha"]] = (int) $row["total"];
            }

            for ($i = 0; $i < $days; $i++) {
                $date = date('Y-m-d', strtotime("-$i days"));
                if (isset($dates[$date])) {
                    $result[$i] = $dates[$date];
                }
            }

            return array_reverse($result); // Revertimos para tenerlo en orden cronológico
        }

        $data7 = formatData($data7_raw, 7);
        $data14 = formatData($data14_raw, 7);
        $datav7 = formatData($datav7_raw, 7);
        $datav14 = formatData($datav14_raw, 7);

        echo json_encode([
            "status" => "success",
            "message" => "Datos obtenidos correctamente.",
            "data" => [
                "7 days" => $data7,
                "14 days" => $data14,
                "7visit" => $datav7,
                "14visit" => $datav14
            ]
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Error al obtener los datos: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
