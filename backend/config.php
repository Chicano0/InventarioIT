<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Configuración de la base de datos
$serverName = getenv('DB_HOST') ?: "30.30.3.62";
$database = getenv('DB_NAME') ?: "InventarioDB";
$username = getenv('DB_USER') ?: "sa";
$password = getenv('DB_PASSWORD') ?: "Admin123.";
$port = getenv('DB_PORT') ?: "1433";

// Cadena de conexión ODBC
$dsn = "odbc:Driver={ODBC Driver 18 for SQL Server};" .
       "Server={$serverName},{$port};" .
       "Database=master;" .
       "Uid={$username};" .
       "Pwd={$password};" .
       "Encrypt=yes;" .
       "TrustServerCertificate=yes";

try {
    // Conectar a master para crear la DB si no existe
    $conn = new PDO($dsn);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Crear base de datos si no existe
    $createDBQuery = "
        IF NOT EXISTS (SELECT name FROM sys.databases WHERE name = '{$database}')
        BEGIN
            CREATE DATABASE {$database};
        END
    ";
    $conn->exec($createDBQuery);
    
    // Reconectar a la base de datos específica
    $dsn = "odbc:Driver={ODBC Driver 18 for SQL Server};" .
           "Server={$serverName},{$port};" .
           "Database={$database};" .
           "Uid={$username};" .
           "Pwd={$password};" .
           "Encrypt=yes;" .
           "TrustServerCertificate=yes";
    
    $conn = new PDO($dsn);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Crear tabla Items si no existe
    $createTableQuery = "
        IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='Items' AND xtype='U')
        BEGIN
            CREATE TABLE Items (
                id INT PRIMARY KEY IDENTITY(1,1),
                nombre NVARCHAR(100) NOT NULL,
                descripcion NVARCHAR(255),
                categoria NVARCHAR(50),
                cantidad INT DEFAULT 0,
                ubicacion NVARCHAR(100),
                fecha_registro DATETIME DEFAULT GETDATE(),
                estado NVARCHAR(20) DEFAULT 'Activo'
            )
        END
    ";
    $conn->exec($createTableQuery);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Error de conexión a la base de datos",
        "details" => $e->getMessage()
    ]);
    exit;
}

function closeConnection(&$conn) {
    $conn = null;
}
?>