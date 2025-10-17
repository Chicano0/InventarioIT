<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");

$apiInfo = [
    "proyecto" => "InventarioIT API",
    "version" => "1.0.0",
    "descripcion" => "API REST para gestión de inventario tecnológico",
    "autor" => "Carlos Uriel Laureano Balderas",
    "tecnologias" => [
        "lenguaje" => "PHP " . phpversion(),
        "base_datos" => "Microsoft SQL Server 2022",
        "driver" => "PDO/ODBC Driver 18",
        "servidor_web" => "Nginx + PHP-FPM"
    ],
    "endpoints" => [
        [
            "metodo" => "GET",
            "ruta" => "/api/get_items.php",
            "descripcion" => "Obtener todos los items"
        ],
        [
            "metodo" => "POST",
            "ruta" => "/api/add_item.php",
            "descripcion" => "Agregar nuevo item"
        ],
        [
            "metodo" => "PUT",
            "ruta" => "/api/update_item.php",
            "descripcion" => "Actualizar item"
        ],
        [
            "metodo" => "DELETE",
            "ruta" => "/api/delete_item.php",
            "descripcion" => "Eliminar item"
        ]
    ],
    "estado" => [
        "servicio" => "✓ Activo",
        "timestamp" => date('Y-m-d H:i:s'),
        "servidor" => gethostname()
    ]
];

// Verificar conexión a base de datos
try {
    require_once 'config.php';
    $stmt = $conn->query("SELECT COUNT(*) as count FROM Items");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $apiInfo["estado"]["base_datos"] = "✓ Conectada";
    $apiInfo["estado"]["total_items"] = (int)$result['count'];
    closeConnection($conn);
} catch (Exception $e) {
    $apiInfo["estado"]["base_datos"] = "✗ Error: " . $e->getMessage();
}

echo json_encode($apiInfo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>