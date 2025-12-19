<?php
/**
 * Quick Environment Check
 * Access: http://localhost:8080/check.php
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Environment Check</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 30px;
        }
        .status {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-weight: bold;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>PHP Development Environment Check</h1>
        
        <h2>PHP Information</h2>
        <table>
            <tr>
                <th>Item</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>PHP Version</td>
                <td><code><?php echo PHP_VERSION; ?></code></td>
            </tr>
            <tr>
                <td>Server Software</td>
                <td><code><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></code></td>
            </tr>
            <tr>
                <td>Document Root</td>
                <td><code><?php echo $_SERVER['DOCUMENT_ROOT']; ?></code></td>
            </tr>
            <tr>
                <td>Timezone</td>
                <td><code><?php echo date_default_timezone_get(); ?></code></td>
            </tr>
        </table>

        <h2>PHP Extensions</h2>
        <?php
        $required_extensions = [
            'pdo', 'pdo_mysql', 'mysqli', 'redis', 
            'mbstring', 'gd', 'zip', 'intl', 'opcache'
        ];
        
        $loaded = [];
        $missing = [];
        
        foreach ($required_extensions as $ext) {
            if (extension_loaded($ext)) {
                $loaded[] = $ext;
            } else {
                $missing[] = $ext;
            }
        }
        ?>
        
        <div class="status success">
            Loaded Extensions: <?php echo implode(', ', $loaded); ?>
        </div>
        
        <?php if (!empty($missing)): ?>
        <div class="status error">
            Missing Extensions: <?php echo implode(', ', $missing); ?>
        </div>
        <?php endif; ?>

        <h2>MySQL Connection Test</h2>
        <?php
        try {
            $pdo = new PDO(
                'mysql:host=db;dbname=myapp;charset=utf8mb4',
                'devuser',
                'devpassword',
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo '<div class="status success">MySQL Connection: SUCCESS</div>';
            
            $stmt = $pdo->query("SELECT VERSION() as version");
            $version = $stmt->fetch(PDO::FETCH_ASSOC);
            echo '<div class="status info">MySQL Version: ' . htmlspecialchars($version['version']) . '</div>';
        } catch (PDOException $e) {
            echo '<div class="status error">MySQL Connection: FAILED</div>';
            echo '<div class="status error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>

        <h2>Redis Connection Test</h2>
        <?php
        if (extension_loaded('redis')) {
            try {
                $redis = new Redis();
                $redis->connect('redis', 6379);
                $redis->set('test:check', 'OK');
                $value = $redis->get('test:check');
                
                if ($value === 'OK') {
                    echo '<div class="status success">Redis Connection: SUCCESS</div>';
                    
                    $info = $redis->info();
                    echo '<div class="status info">Redis Version: ' . htmlspecialchars($info['redis_version']) . '</div>';
                } else {
                    echo '<div class="status error">Redis Connection: FAILED (Unable to set/get values)</div>';
                }
                $redis->close();
            } catch (Exception $e) {
                echo '<div class="status error">Redis Connection: FAILED</div>';
                echo '<div class="status error">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        } else {
            echo '<div class="status error">Redis Extension: NOT LOADED</div>';
        }
        ?>

        <h2>File System</h2>
        <table>
            <tr>
                <th>Path</th>
                <th>Status</th>
            </tr>
            <tr>
                <td><code>php/src/</code></td>
                <td><?php echo is_dir('/var/www/html') ? 'Exists' : 'Missing'; ?></td>
            </tr>
            <tr>
                <td><code>Writable</code></td>
                <td><?php echo is_writable('/var/www/html') ? 'Yes' : 'No'; ?></td>
            </tr>
        </table>

        <h2>Quick Links</h2>
        <ul>
            <li><a href="/">Home (index.php)</a></li>
            <li><a href="/test-db.php">Test Database</a></li>
            <li><a href="/test-redis.php">Test Redis</a></li>
            <li><a href="/info.php">PHP Info</a></li>
            <li><a href="http://localhost:8000" target="_blank">phpMyAdmin</a></li>
        </ul>

        <div style="margin-top: 30px; padding: 20px; background: #e7f3ff; border-radius: 5px;">
            <strong>Tip:</strong> พัฒนาโปรเจค PHP ของคุณในโฟลเดอร์ <code>php/src/</code> 
            และไฟล์ทั้งหมดจะสามารถเข้าถึงได้ที่ <code>http://localhost:8080</code>
        </div>
    </div>
</body>
</html>

