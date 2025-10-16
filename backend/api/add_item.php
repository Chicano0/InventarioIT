<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (empty($data['nombre'])) {
            http_response_code(400);
            echo json_encode(["error" => "El nombre es requerido"]);
            closeConnection($conn);
            exit;
        }
        
        $nombre = $data['nombre'];
        $descripcion = $data['descripcion'] ?? '';
        $categoria = $data['categoria'] ?? 'General';
        $cantidad = $data['cantidad'] ?? 0;
        $ubicacion = $data['ubicacion'] ?? '';
        $estado = $data['estado'] ?? 'Activo';
        
        $query = "INSERT INTO Items (nombre, descripcion, categoria, cantidad, ubicacion, estado) 
                  OUTPUT INSERTED.id
                  VALUES (:nombre, :descripcion, :categoria, :cantidad, :ubicacion, :estado)";
        
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
        $stmt->bindParam(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindParam(':ubicacion', $ubicacion, PDO::PARAM_STR);
        $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $newId = $result['id'];
        
        http_response_code(201);
        echo json_encode([
            "message" => "Item agregado exitosamente",
            "id" => $newId
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "Error al agregar item",
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