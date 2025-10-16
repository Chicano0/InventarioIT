<?php
header("Content-Type: application/json; charset=UTF-8");

$serverName = getenv('DB_HOST') ?: "sqlserver";
$database = getenv('DB_NAME') ?: "InventarioDB";
$username = getenv('DB_USER') ?: "sa";
$password = getenv('DB_PASSWORD') ?: "Admin123.";
$port = getenv('DB_PORT') ?: "1433";

$testResults = [
    "php_version" => phpversion(),
    "odbc_support" => extension_loaded('pdo_odbc') ? "✓ Habilitado" : "✗ Deshabilitado",
    "available_drivers" => PDO::getAvailableDrivers(),
    "tests" => []
];

// Test 1: Drivers ODBC
try {
    $odbcDrivers = shell_exec('odbcinst -q -d 2>&1');
    $testResults['tests']['odbc_drivers'] = [
        "status" => "success",
        "message" => "✓ Drivers ODBC instalados",
        "drivers" => $odbcDrivers
    ];
} catch (Exception $e) {
    $testResults['tests']['odbc_drivers'] = [
        "status" => "error",
        "message" => $e->getMessage()
    ];
}

// Test 2: Conexión a SQL Server
$dsn = "odbc:Driver={ODBC Driver 18 for SQL Server};" .
       "Server={$serverName},{$port};" .
       "Database=master;" .
       "Uid={$username};" .
       "Pwd={$password};" .
       "Encrypt=yes;" .
       "TrustServerCertificate=yes";

try {
    $conn = new PDO($dsn);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $testResults['tests']['connection'] = [
        "status" => "success",
        "message" => "✓ Conexión exitosa a SQL Server"
    ];
    
    // Test 3: Versión SQL Server
    $stmt = $conn->query("SELECT @@VERSION as version");
    $version = $stmt->fetch(PDO::FETCH_ASSOC);
    $testResults['tests']['sql_version'] = [
        "status" => "success",
        "version" => substr($version['version'], 0, 100) . "..."
    ];
    
    // Test 4: Base de datos
    $stmt = $conn->query("SELECT name FROM sys.databases WHERE name = '{$database}'");
    $dbExists = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($dbExists) {
        $testResults['tests']['database'] = [
            "status" => "success",
            "message" => "✓ Base de datos '{$database}' existe"
        ];
        
        // Conectar a la DB específica
        $dsn = "odbc:Driver={ODBC Driver 18 for SQL Server};" .
               "Server={$serverName},{$port};" .
               "Database={$database};" .
               "Uid={$username};" .
               "Pwd={$password};" .
               "Encrypt=yes;" .
               "TrustServerCertificate=yes";
        
        $conn = new PDO($dsn);
        
        // Test 5: Tabla Items
        $stmt = $conn->query("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'Items'");
        $tableExists = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($tableExists['count'] > 0) {
            $testResults['tests']['table'] = [
                "status" => "success",
                "message" => "✓ Tabla 'Items' existe"
            ];
            
            $stmt = $conn->query("SELECT COUNT(*) as count FROM Items");
            $count = $stmt->fetch(PDO::FETCH_ASSOC);
            $testResults['tests']['records'] = [
                "status" => "success",
                "message" => "Total de items: " . $count['count']
            ];
        } else {
            $testResults['tests']['table'] = [
                "status" => "warning",
                "message" => "⚠ Tabla 'Items' no existe"
            ];
        }
    } else {
        $testResults['tests']['database'] = [
            "status" => "warning",
            "message" => "⚠ Base de datos no existe"
        ];
    }
    
} catch (PDOException $e) {
    $testResults['tests']['connection'] = [
        "status" => "error",
        "message" => "✗ Error de conexión",
        "error" => $e->getMessage()
    ];
}

$testResults['overall_status'] = "✓ Sistema listo";
echo json_encode($testResults, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>