-- ============================================================================
-- Google Calendar Clone - Database Schema
-- ============================================================================
-- Created for: Course Calendar Management System
-- Author: An-Uncreative
-- GitHub: https://github.com/An-Uncreative/Google_calendar_clone
-- ============================================================================

-- Create database (optional - can be created manually in phpMyAdmin)
-- CREATE DATABASE IF NOT EXISTS calendar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE calendar;

-- ============================================================================
-- TABLE: events
-- Purpose: Store all calendar events with course information and scheduling
-- ============================================================================

DROP TABLE IF EXISTS events;

CREATE TABLE events (
    -- Primary identifier for each event
    id INT AUTO_INCREMENT PRIMARY KEY,
    
    -- Course information
    course_name VARCHAR(255) NOT NULL COMMENT 'Name of the course/class',
    instructor_name VARCHAR(255) NOT NULL COMMENT 'Name of the instructor/teacher',
    
    -- Date scheduling (required fields)
    start_date DATE NOT NULL COMMENT 'Start date of the event (YYYY-MM-DD)',
    end_date DATE NOT NULL COMMENT 'End date of the event (YYYY-MM-DD)',
    
    -- Time scheduling (optional fields for all-day events)
    start_time TIME NULL COMMENT 'Start time of the event (HH:MM:SS)',
    end_time TIME NULL COMMENT 'End time of the event (HH:MM:SS)',
    
    -- Audit trail timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'When the event was created',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'When the event was last updated',
    
    -- Indexes for better performance
    INDEX idx_start_date (start_date),
    INDEX idx_end_date (end_date),
    INDEX idx_course_name (course_name),
    INDEX idx_date_range (start_date, end_date)
    
) ENGINE=InnoDB 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci 
  COMMENT='Calendar events table for course scheduling';

-- ============================================================================
-- SAMPLE DATA (Optional - for testing purposes)
-- Remove or modify these INSERT statements as needed
-- ============================================================================

INSERT INTO events (course_name, instructor_name, start_date, end_date, start_time, end_time) VALUES
('Web Development Fundamentals', 'Prof. Johnson', '2025-07-21', '2025-07-21', '09:00:00', '11:00:00'),
('Database Design', 'Dr. Smith', '2025-07-22', '2025-07-22', '14:00:00', '16:00:00'),
('JavaScript Advanced', 'Ms. Davis', '2025-07-23', '2025-07-25', '10:00:00', '12:00:00'),
('PHP Backend Development', 'Mr. Wilson', '2025-07-24', '2025-07-24', '13:00:00', '15:00:00'),
('Project Presentation', 'Prof. Johnson', '2025-07-26', '2025-07-26', '09:00:00', '17:00:00');

-- ============================================================================
-- VERIFICATION QUERIES
-- Run these to verify the setup is working correctly
-- ============================================================================

-- Check if table was created successfully
-- SHOW TABLES;

-- Verify table structure
-- DESCRIBE events;

-- Count sample records
-- SELECT COUNT(*) as total_events FROM events;

-- View all events ordered by date
-- SELECT 
--     id,
--     course_name,
--     instructor_name,
--     start_date,
--     end_date,
--     start_time,
--     end_time,
--     created_at
-- FROM events 
-- ORDER BY start_date, start_time;

-- ============================================================================
-- MAINTENANCE QUERIES (for future use)
-- ============================================================================

-- Remove all sample data (if needed)
-- DELETE FROM events WHERE id > 0;

-- Reset auto-increment counter
-- ALTER TABLE events AUTO_INCREMENT = 1;

-- Add new columns (example for future enhancements)
-- ALTER TABLE events ADD COLUMN event_description TEXT NULL COMMENT 'Optional event description';
-- ALTER TABLE events ADD COLUMN event_color VARCHAR(7) DEFAULT '#3B82F6' COMMENT 'Hex color code for event display';
-- ALTER TABLE events ADD COLUMN is_recurring BOOLEAN DEFAULT FALSE COMMENT 'Whether event repeats';

-- ============================================================================
-- SECURITY NOTES
-- ============================================================================
-- 1. Always use prepared statements in PHP (already implemented in calendar.php)
-- 2. Validate all input data before insertion
-- 3. Use proper user permissions in production
-- 4. Regular database backups recommended
-- 5. Monitor for unusual query patterns
-- ============================================================================
