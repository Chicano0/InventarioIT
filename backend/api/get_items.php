<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $query = "SELECT * FROM Items ORDER BY fecha_registro DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Formatear fechas
        foreach ($items as &$item) {
            if (isset($item['fecha_registro']) && $item['fecha_registro']) {
                $date = new DateTime($item['fecha_registro']);
                $item['fecha_registro'] = $date->format('Y-m-d H:i:s');
            }
        }
        
        http_response_code(200);
        echo json_encode($items);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "Error al obtener items",
            "details" => $e->getMessage()
        ]);
    } finally {
        closeConnection($conn);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>