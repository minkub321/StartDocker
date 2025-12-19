<?php
/**
 * Test Redis Connection
 * Access: http://localhost:8080/test-redis.php
 */

require_once 'config.php';

echo "<h1>Redis Connection Test</h1>";

try {
    $redis = getRedisConnection();
    echo "<p style='color: green; font-size: 18px;'>Redis Connected Successfully!</p>";
    
    // Get Redis info
    $info = $redis->info();
    echo "<p><strong>Redis Version:</strong> " . $info['redis_version'] . "</p>";
    echo "<p><strong>Used Memory:</strong> " . $info['used_memory_human'] . "</p>";
    
    // Test SET/GET
    echo "<h2>Testing SET/GET Operations:</h2>";
    $redis->set('test:key', 'Hello from Redis!');
    $value = $redis->get('test:key');
    echo "<p>Set value: <code>test:key = 'Hello from Redis!'</code></p>";
    echo "<p>Get value: <code>" . htmlspecialchars($value) . "</code></p>";
    
    // Test expiration
    echo "<h2>Testing Expiration:</h2>";
    $redis->setex('test:expire', 60, 'This will expire in 60 seconds');
    $ttl = $redis->ttl('test:expire');
    echo "<p>Key <code>test:expire</code> will expire in: <strong>{$ttl} seconds</strong></p>";
    
    // Test increment
    echo "<h2>Testing INCR Operation:</h2>";
    $redis->del('test:counter');
    $redis->incr('test:counter');
    $redis->incr('test:counter');
    $redis->incr('test:counter');
    $counter = $redis->get('test:counter');
    echo "<p>Counter value: <strong>{$counter}</strong></p>";
    
    // Test hash
    echo "<h2>Testing Hash Operations:</h2>";
    $redis->hset('test:user', 'name', 'John Doe');
    $redis->hset('test:user', 'email', 'john@example.com');
    $redis->hset('test:user', 'age', '30');
    $user = $redis->hgetall('test:user');
    echo "<pre>";
    print_r($user);
    echo "</pre>";
    
    // Test list
    echo "<h2>Testing List Operations:</h2>";
    $redis->del('test:list');
    $redis->rpush('test:list', 'item1', 'item2', 'item3');
    $list = $redis->lrange('test:list', 0, -1);
    echo "<p>List items:</p><ul>";
    foreach ($list as $item) {
        echo "<li>" . htmlspecialchars($item) . "</li>";
    }
    echo "</ul>";
    
    // Show all test keys
    echo "<h2>All Test Keys:</h2>";
    $keys = $redis->keys('test:*');
    echo "<ul>";
    foreach ($keys as $key) {
        $type = $redis->type($key);
        $typeNames = [Redis::REDIS_STRING => 'String', Redis::REDIS_HASH => 'Hash', Redis::REDIS_LIST => 'List'];
        echo "<li><code>{$key}</code> (Type: " . ($typeNames[$type] ?? 'Unknown') . ")</li>";
    }
    echo "</ul>";
    
    echo "<p style='color: green;'>All Redis operations working correctly!</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red; font-size: 18px;'>Redis Connection Failed!</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Make sure Redis extension is installed: <code>php -m | grep redis</code></p>";
}
?>

