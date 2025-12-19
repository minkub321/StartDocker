<?php
/**
 * PHP Development Environment - Test Page
 * 
 * This file is for testing your PHP development environment.
 * Access this page at: http://localhost:8080
 */

phpinfo();

// Test MySQL Connection
echo "<h2>MySQL Connection Test</h2>";
try {
    $pdo = new PDO(
        'mysql:host=db;dbname=myapp;charset=utf8mb4',
        'devuser',
        'devpassword',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    echo "<p style='color: green;'>MySQL Connection: SUCCESS</p>";
    echo "<p>Database: myapp</p>";
    echo "<p>Host: db</p>";
    echo "<p>User: devuser</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>MySQL Connection: FAILED</p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Test Redis Connection
echo "<h2>Redis Connection Test</h2>";
try {
    $redis = new Redis();
    $redis->connect('redis', 6379);
    $redis->set('test_key', 'Hello Redis!');
    $value = $redis->get('test_key');
    echo "<p style='color: green;'>Redis Connection: SUCCESS</p>";
    echo "<p>Test Value: " . htmlspecialchars($value) . "</p>";
    $redis->close();
} catch (Exception $e) {
    echo "<p style='color: red;'>Redis Connection: FAILED</p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Environment Information</h2>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Server: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
?>

