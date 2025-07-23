<?php
// Railway Database Setup Script
// Run this ONCE to create the events table in Railway

header('Content-Type: text/html; charset=utf-8');
echo "<h1>🛠️ Railway Database Setup</h1>";

try {
    // Include Railway connection
    include 'connection-railway.php';
    
    echo "<h2>✅ Database Connected Successfully!</h2>";
    
    // Create events table
    $sql = "
    CREATE TABLE IF NOT EXISTS events (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_name VARCHAR(255) NOT NULL COMMENT 'Name of the course/class',
        instructor_name VARCHAR(255) NOT NULL COMMENT 'Name of the instructor/teacher',
        start_date DATE NOT NULL COMMENT 'Start date of the event (YYYY-MM-DD)',
        end_date DATE NOT NULL COMMENT 'End date of the event (YYYY-MM-DD)',
        start_time TIME NULL COMMENT 'Start time of the event (HH:MM:SS)',
        end_time TIME NULL COMMENT 'End time of the event (HH:MM:SS)',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When the event was created',
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'When the event was last updated',
        INDEX idx_start_date (start_date),
        INDEX idx_end_date (end_date),
        INDEX idx_course_name (course_name),
        INDEX idx_date_range (start_date, end_date)
    ) ENGINE=InnoDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT='Calendar events table for course scheduling'";
    
    $conn->exec($sql);
    echo "<h2>✅ Events Table Created Successfully!</h2>";
    
    // Check if we need sample data
    $count = $conn->query("SELECT COUNT(*) as count FROM events")->fetch();
    
    if ($count['count'] == 0) {
        echo "<h2>📊 Adding Sample Data...</h2>";
        
        $sampleData = "
        INSERT INTO events (course_name, instructor_name, start_date, end_date, start_time, end_time) VALUES
        ('Web Development Fundamentals', 'Prof. Johnson', '2025-07-21', '2025-07-21', '09:00:00', '11:00:00'),
        ('Database Design', 'Dr. Smith', '2025-07-22', '2025-07-22', '14:00:00', '16:00:00'),
        ('JavaScript Advanced', 'Ms. Davis', '2025-07-23', '2025-07-25', '10:00:00', '12:00:00'),
        ('PHP Backend Development', 'Mr. Wilson', '2025-07-24', '2025-07-24', '13:00:00', '15:00:00'),
        ('Project Presentation', 'Prof. Johnson', '2025-07-26', '2025-07-26', '09:00:00', '17:00:00')";
        
        $conn->exec($sampleData);
        echo "<h2>✅ Sample Data Added Successfully!</h2>";
        
        $newCount = $conn->query("SELECT COUNT(*) as count FROM events")->fetch();
        echo "<p>📈 Events in database: " . $newCount['count'] . "</p>";
    } else {
        echo "<h2>📊 Database Already Has Data</h2>";
        echo "<p>Events in database: " . $count['count'] . "</p>";
    }
    
    echo "<hr>";
    echo "<h2>🎉 Setup Complete!</h2>";
    echo "<p><strong><a href='index.php'>🚀 Go to Calendar Application</a></strong></p>";
    echo "<p><strong><a href='debug.php'>🔍 View Debug Information</a></strong></p>";
    
} catch (PDOException $e) {
    echo "<h2>❌ Setup Failed</h2>";
    echo "<p style='color: red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Check your Railway MySQL service is running and connected.</p>";
}
?>
