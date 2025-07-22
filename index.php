<?php

include "calendar.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My_calendar</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🗓️ Course Calendar <br> A Calendar Project</h1>
    </header>

    <!-- clock -->
    <div class="clock-container">
        <div id="clock"></div>
    </div>

    <!-- Success/Error Messages -->
    <?php if (!empty($successMsg)): ?>
        <div class="alert success">
            <?php echo htmlspecialchars($successMsg); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errorMsg)): ?>
        <div class="alert error">
            <?php echo htmlspecialchars($errorMsg); ?>
        </div>
    <?php endif; ?>

    <!-- calendar -->
     <div class="calendar">
        <div class="nav-btn-container">
            <button class="nav-btn">⏮️</button>
            <h2 id="monthYear" style="margin: 0;"></h2>
            <button class="nav-btn">⏭️</button>
        </div>

        <div class="calendar-grid" id="calendar">
            <!-- Calendar days will be generated here -->
        </div>
     </div>

     <!-- Modal for adding/editing/deleting events -->
    <div class="modal" id="eventModal">
          <div class="modal-content">
          
      <div id="eventSelectorWrapper">
        <label for="eventSelector">
            <strong>Select Event:</strong>
        </label>
        <select id="eventSelector">
            <option disabled selected>Select an event...</option>
        </select>
      </div>

      <!-- main form -->
       <form method="POST" id="eventForm">
           <input type="hidden" name="action" id="formAction" value="add">
           <input type="hidden" name="event_id" id="eventId">

           <label for="courseName">Course Title:</label>
           <input type="text" name="course_name" id="courseName" required>

           <label for="instructorName">Instructor Name:</label>
           <input type="text" name="instructor_name" id="instructorName" required>

           <label for="startDate">Start Date:</label>
           <input type="date" name="start_date" id="startDate" required>

           <label for="endDate">End Date:</label>
           <input type="date" name="end_date" id="endDate" required>

           <label for="startTime">Start Time:</label>
           <input type="time" name="start_time" id="startTime" required>

           <label for="endTime">End Time:</label>
           <input type="time" name="end_time" id="endTime" required>

           <button type="submit">Save Event</button>
       </form>

       <!-- Delete form -->
        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this event?');">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="event_id" id="deleteEventId">
            <button type="submit" class="submit-btn">Delete Event</button>
        </form>

        <!-- cancel -->
         <button type="button" class="submit-btn">Cancel</button>
         </div>
    </div>

    <script>
        const events = <?php 
            echo json_encode($eventsFromDB ?? [], JSON_UNESCAPED_UNICODE); 
        ?>;
        console.log('Events loaded:', events);
        console.log('Calendar elements found:', {
            calendar: document.getElementById('calendar'),
            monthYear: document.getElementById('monthYear'),
            modal: document.getElementById('eventModal')
        });
    </script>     
    <script src="calendar.js"></script>
</body>
</html>