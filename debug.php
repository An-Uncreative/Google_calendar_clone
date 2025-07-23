<?php
// Railway Debug Script - Check what's wrong with deployment
header('Content-Type: text/html; charset=utf-8');
echo "<h1>🚂 Railway Deployment Debug</h1>";
echo "<hr>";

echo "<h2>1. Basic PHP Test</h2>";
echo "✅ PHP is working! Version: " . phpversion() . "<br>";
echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";

echo "<h2>2. Environment Variables</h2>";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'NOT SET') . "<br>";
echo "DB_PORT: " . ($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: 'NOT SET') . "<br>";
echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'NOT SET') . "<br>";
echo "DB_USER: " . ($_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'NOT SET') . "<br>";
echo "DB_PASS: " . (($_ENV['DB_PASS'] ?? getenv('DB_PASS')) ? '[HIDDEN]' : 'NOT SET') . "<br>";

echo "<h2>3. File System Check</h2>";
echo "Current directory: " . getcwd() . "<br>";
echo "Files in root directory:<br>";
$files = scandir('.');
foreach($files as $file) {
    if($file != '.' && $file != '..') {
        echo "- $file" . (is_dir($file) ? ' (directory)' : '') . "<br>";
    }
}

echo "<h2>4. Connection File Test</h2>";
if (file_exists('connection.php')) {
    echo "✅ connection.php exists<br>";
    
    // Test .env file loading
    if (file_exists('.env')) {
        echo "✅ .env file exists<br>";
        $lines = file('.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
            echo "Loaded: " . trim($name) . "<br>";
        }
    } else {
        echo "⚠️ .env file not found<br>";
    }
    
} else {
    echo "❌ connection.php NOT found<br>";
}

echo "<h2>5. Database Connection Test</h2>";
try {
    // Get credentials
    $db_host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?: 'maglev.proxy.rlwy.net';
    $db_user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?: 'root';
    $db_pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?: 'EUbtbwNUfAelihTAgTVVksKJXPjaOvfW';
    $db_name = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?: 'railway';
    $db_port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?: '40785';
    
    echo "Attempting connection to: $db_host:$db_port/$db_name<br>";
    
    $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4";
    
    $conn = new PDO($dsn, $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✅ <strong style='color: green;'>Database connected successfully!</strong><br>";
    
    // Test if events table exists
    $result = $conn->query("SHOW TABLES LIKE 'events'");
    if($result->rowCount() > 0) {
        echo "✅ <strong style='color: green;'>Events table exists!</strong><br>";
        
        // Count events
        $count = $conn->query("SELECT COUNT(*) as count FROM events")->fetch();
        echo "📊 Events in database: " . $count['count'] . "<br>";
        
        if ($count['count'] == 0) {
            echo "⚠️ <strong style='color: orange;'>No events found - database is empty</strong><br>";
        }
    } else {
        echo "❌ <strong style='color: red;'>Events table does NOT exist!</strong><br>";
        echo "💡 Need to create the events table<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ <strong style='color: red;'>Database connection failed!</strong><br>";
    echo "Error: " . htmlspecialchars($e->getMessage()) . "<br>";
}

echo "<h2>6. Main Application Test</h2>";
if (file_exists('index.php')) {
    echo "✅ index.php exists<br>";
} else {
    echo "❌ index.php NOT found<br>";
}

if (file_exists('calendar.php')) {
    echo "✅ calendar.php exists<br>";
} else {
    echo "❌ calendar.php NOT found<br>";
}

echo "<h2>7. Quick Fix Actions</h2>";
echo "<strong>If you see database connection errors:</strong><br>";
echo "1. Check Railway MySQL service is running<br>";
echo "2. Verify environment variables are set correctly<br>";
echo "3. Import database schema using Railway console<br>";
echo "4. Check if .env file exists with correct credentials<br>";

echo "<hr>";
echo "<p><strong>🚀 <a href='index.php'>Try Main Application</a></strong></p>";
echo "<p><em>Delete this debug.php file after fixing issues!</em></p>";
?>
