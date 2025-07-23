<?php

// Use Railway connection file if it exists, otherwise use local connection
if (file_exists('connection-railway.php')) {
    include 'connection-railway.php';
} else {
    include 'connection.php';
}

// Initialize variables
$successMsg = '';
$errorMsg = '';
$eventsFromDB = [];

// Handle add event
if ($_SERVER['REQUEST_METHOD'] === 'POST' and ($_POST['action']?? '')=== 'add') {
    $course = trim($_POST['course_name'] ?? '');
    $instructor = trim($_POST['instructor_name'] ?? '');
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';
    $startTime = $_POST['start_time'] ?? '';
    $endTime = $_POST['end_time'] ?? '';

    if ($course and $instructor and $startDate and $endDate and $startTime and $endTime) {
        try {
            $stmt = $conn->prepare("INSERT INTO events (course_name, instructor_name, start_date, end_date, start_time, end_time) VALUES (:course_name, :instructor_name, :start_date, :end_date, :start_time, :end_time)");
            $stmt->bindParam(':course_name', $course);
            $stmt->bindParam(':instructor_name', $instructor);
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);
            $stmt->execute();
            $stmt->closeCursor();
            header("Location: {$_SERVER['PHP_SELF']}?success=1");
            exit; // Important: Stop script execution after redirect
        } catch (PDOException $e) {
            $errorMsg = "Error adding event: " . $e->getMessage();
        }
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=1"); // ❌ User loses form data
        exit;
    }
}

// handle edit event
if ($_SERVER['REQUEST_METHOD'] === 'POST' and ($_POST['action']?? '')=== 'edit') {
    $id = $_POST['event_id'] ?? null;
    $course = trim($_POST['course_name'] ?? '');
    $instructor = trim($_POST['instructor_name'] ?? ''); 
    $startDate = $_POST['start_date'] ?? '';
    $endDate = $_POST['end_date'] ?? '';
    $startTime = $_POST['start_time'] ?? '';
    $endTime = $_POST['end_time'] ?? '';

    if ($id and $course and $instructor and $startDate and $endDate and $startTime and $endTime) {
        try {
            $stmt = $conn->prepare("UPDATE events SET course_name = :course_name, instructor_name = :instructor_name, start_date = :start_date, end_date = :end_date, start_time = :start_time, end_time = :end_time WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':course_name', $course);
            $stmt->bindParam(':instructor_name', $instructor);
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);
            $stmt->execute();
            $stmt->closeCursor();
            // Redirect to avoid resubmission
            header("Location: {$_SERVER['PHP_SELF']}?success=2");
            exit;
        } catch (PDOException $e) {
            $errorMsg = "Error updating event: " . $e->getMessage();
        }
    } else {
        header("Location: {$_SERVER['PHP_SELF']}?error=2");
        exit;
    }
}

// handle delete event
if ($_SERVER['REQUEST_METHOD'] === 'POST' and ($_POST['action']?? '')=== 'delete') {
    $id = $_POST['event_id'] ?? null;

    if ($id) {
        try {
            $stmt = $conn->prepare("DELETE FROM events WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $stmt->closeCursor();
            header("Location: {$_SERVER['PHP_SELF']}?success=3");
            exit;
        } catch (PDOException $e) {
            $errorMsg = "Error deleting event: " . $e->getMessage();
        }
    }
}

// success and error handling
if (isset($_GET['success'])) {
    $successMsg = match ($_GET['success']) {
        '1' => 'Event added successfully!',
        '2' => 'Event updated successfully!',
        '3' => 'Event deleted successfully!',
        default => ''
    };
}

if (isset($_GET['error'])) {
    $errorMsg = "An error occurred. Please try again.";
}

// Fetch events from database
$result = $conn->query("SELECT * FROM events ORDER BY start_date");
if ($result and $result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $startDate = new DateTime($row['start_date']);
        $endDate = new DateTime($row['end_date']);
        while ($startDate <= $endDate) {
            $eventsFromDB[$startDate->format('Y-m-d')][] = [
                'id' => $row['id'],
                'title' => "{$row['course_name']} - {$row['instructor_name']}",
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time']
            ];
            $startDate->modify('+1 day');
        }
    }
} else {
    $errorMsg = "No events found.";
}

$conn = null; // Close the connection
?>