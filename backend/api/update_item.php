<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "El ID es requerido"]);
            closeConnection($conn);
            exit;
        }
        
        $id = $data['id'];
        $updates = array();
        $params = array(':id' => $id);
        
        if (isset($data['nombre'])) {
            $updates[] = "nombre = :nombre";
            $params[':nombre'] = $data['nombre'];
        }
        if (isset($data['descripcion'])) {
            $updates[] = "descripcion = :descripcion";
            $params[':descripcion'] = $data['descripcion'];
        }
        if (isset($data['categoria'])) {
            $updates[] = "categoria = :categoria";
            $params[':categoria'] = $data['categoria'];
        }
        if (isset($data['cantidad'])) {
            $updates[] = "cantidad = :cantidad";
            $params[':cantidad'] = $data['cantidad'];
        }
        if (isset($data['ubicacion'])) {
            $updates[] = "ubicacion = :ubicacion";
            $params[':ubicacion'] = $data['ubicacion'];
        }
        if (isset($data['estado'])) {
            $updates[] = "estado = :estado";
            $params[':estado'] = $data['estado'];
        }
        
        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(["error" => "No hay campos para actualizar"]);
            closeConnection($conn);
            exit;
        }
        
        $query = "UPDATE Items SET " . implode(", ", $updates) . " WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        
        $rowsAffected = $stmt->rowCount();
        
        if ($rowsAffected > 0) {
            http_response_code(200);
            echo json_encode(["message" => "Item actualizado exitosamente"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Item no encontrado"]);
        }
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "Error al actualizar item",
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