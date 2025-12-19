<?php
/**
 * Database and Redis Configuration
 * 
 * Use this file for your application configuration
 */

// Database Configuration
define('DB_HOST', 'db');
define('DB_NAME', 'myapp');
define('DB_USER', 'devuser');
define('DB_PASS', 'devpassword');
define('DB_CHARSET', 'utf8mb4');

// Redis Configuration
define('REDIS_HOST', 'redis');
define('REDIS_PORT', 6379);
define('REDIS_PASSWORD', null); // No password by default

// Application Configuration
define('APP_ENV', 'development');
define('APP_DEBUG', true);
define('APP_URL', 'http://localhost:8080');

// Timezone
date_default_timezone_set('Asia/Bangkok');

// Database Connection Helper
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        if (APP_DEBUG) {
            die("Database Connection Error: " . $e->getMessage());
        }
        die("Database Connection Failed");
    }
}

// Redis Connection Helper
function getRedisConnection() {
    try {
        $redis = new Redis();
        $redis->connect(REDIS_HOST, REDIS_PORT);
        if (REDIS_PASSWORD) {
            $redis->auth(REDIS_PASSWORD);
        }
        return $redis;
    } catch (Exception $e) {
        if (APP_DEBUG) {
            die("Redis Connection Error: " . $e->getMessage());
        }
        die("Redis Connection Failed");
    }
}
?>

