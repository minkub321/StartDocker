<?php
/**
 * Test Database Connection
 * Access: http://localhost:8080/test-db.php
 */

require_once 'config.php';

echo "<h1>Database Connection Test</h1>";

try {
    $pdo = getDBConnection();
    echo "<p style='color: green; font-size: 18px;'>Database Connected Successfully!</p>";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch();
    echo "<p><strong>MySQL Version:</strong> " . $version['version'] . "</p>";
    
    // Show databases
    echo "<h2>Available Databases:</h2>";
    $stmt = $pdo->query("SHOW DATABASES");
    $databases = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<ul>";
    foreach ($databases as $db) {
        echo "<li>" . htmlspecialchars($db) . "</li>";
    }
    echo "</ul>";
    
    // Test table creation
    echo "<h2>Creating Test Table...</h2>";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS test_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
    echo "<p style='color: green;'>Test table created/verified</p>";
    
    // Insert test data
    $stmt = $pdo->prepare("INSERT INTO test_users (name, email) VALUES (?, ?) ON DUPLICATE KEY UPDATE name=name");
    $stmt->execute(['Test User', 'test@example.com']);
    
    // Show data
    echo "<h2>Test Data:</h2>";
    $stmt = $pdo->query("SELECT * FROM test_users");
    $users = $stmt->fetchAll();
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Created At</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['id'] . "</td>";
        echo "<td>" . htmlspecialchars($user['name']) . "</td>";
        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
        echo "<td>" . $user['created_at'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (PDOException $e) {
    echo "<p style='color: red; font-size: 18px;'>Database Connection Failed!</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
}
?>

