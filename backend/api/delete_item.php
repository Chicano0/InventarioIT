<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (empty($data['id'])) {
            http_response_code(400);
            echo json_encode(["error" => "El ID es requerido"]);
            closeConnection($conn);
            exit;
        }
        
        $id = $data['id'];
        
        $query = "DELETE FROM Items WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $rowsAffected = $stmt->rowCount();
        
        if ($rowsAffected > 0) {
            http_response_code(200);
            echo json_encode(["message" => "Item eliminado exitosamente"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Item no encontrado"]);
        }
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "Error al eliminar item",
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