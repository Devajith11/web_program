-- ============================================================================
-- GEC Wayanad TechFest Hub (FUTURA '26)
-- Assignment 3: Relational MySQL Database Schema & Baseline Seed Data
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `gecw_techfest` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `gecw_techfest`;

-- ----------------------------------------------------------------------------
-- 1. Table: users (Administrative Authentication)
-- Constraint Exclusion: No registration forms, no RBAC; basic session check.
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. Table: events (Competitions, Workshops, Tech Talks)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
    `id` VARCHAR(50) PRIMARY KEY,
    `type` VARCHAR(50) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `short_code` VARCHAR(50) DEFAULT NULL,
    `category` VARCHAR(50) NOT NULL,
    `description` TEXT NOT NULL,
    `department` VARCHAR(150) DEFAULT 'General',
    `team_size` VARCHAR(50) DEFAULT 'Individual (1 Person)',
    `duration` VARCHAR(50) DEFAULT NULL,
    `registration_required` TINYINT(1) DEFAULT 1,
    `registration_fee` VARCHAR(100) DEFAULT 'Free',
    `prize_pool` VARCHAR(100) DEFAULT NULL,
    `venue` VARCHAR(255) NOT NULL,
    `event_date` VARCHAR(100) NOT NULL,
    `event_time` VARCHAR(100) DEFAULT NULL,
    `coordinator` VARCHAR(255) DEFAULT NULL,
    `speaker` VARCHAR(255) DEFAULT NULL,
    `instructor` VARCHAR(255) DEFAULT NULL,
    `tags` TEXT DEFAULT NULL,
    `status` VARCHAR(50) DEFAULT 'Registration Open',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. Table: schedules (Festival Timeline & Itinerary)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `schedules` (
    `id` VARCHAR(50) PRIMARY KEY,
    `day` VARCHAR(100) NOT NULL,
    `day_key` VARCHAR(50) NOT NULL,
    `time_slot` VARCHAR(100) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `venue` VARCHAR(255) NOT NULL,
    `category` VARCHAR(50) NOT NULL,
    `event_id` VARCHAR(50) DEFAULT NULL,
    `speaker` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_schedule_event` FOREIGN KEY (`event_id`) 
        REFERENCES `events` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. Table: updates (Real-time Announcements & Bulletins)
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `updates` (
    `id` VARCHAR(50) PRIMARY KEY,
    `timestamp` VARCHAR(100) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `tag` VARCHAR(50) DEFAULT 'Notice',
    `badge` VARCHAR(50) DEFAULT 'Info',
    `content` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- BASELINE SEED DATA
-- ============================================================================

-- Baseline Administrator (Plain comparison per assignment constraint exclusion)
INSERT INTO `users` (`username`, `password`, `full_name`) VALUES 
('admin', 'admin123', 'TechFest Administrator')
ON DUPLICATE KEY UPDATE `username`=`username`;

-- Baseline Events (Competitions, Workshops, Tech Talks)
INSERT INTO `events` 
(`id`, `type`, `name`, `short_code`, `category`, `description`, `department`, `team_size`, `duration`, `registration_required`, `registration_fee`, `prize_pool`, `venue`, `event_date`, `event_time`, `coordinator`, `speaker`, `instructor`, `tags`, `status`) VALUES
('event-001', 'Competition', 'Hackathon: HackTheHills 2026', 'HTH-26', 'Competition', 
 'A 24-hour team-based programming and rapid prototyping challenge addressing disaster resilience, eco-tourism, and edge computing for rural ecosystems.', 
 'Computer Science & Engineering', '2-4 Members', '24 Hours', 1, 'Free (Shortlisted Teams)', '₹45,000', 
 'CSE Advanced Computing Lab & CCC', '2026-10-02 to 2026-10-03', '10:00 AM (24 Hours)', 'Dr. Alice A & Student Lead Arjun K', NULL, NULL, 
 'Coding, AI/ML, IoT, Web3, Hackathon', 'Registration Open'),

('event-002', 'Workshop', 'Introduction to Federated AI & Edge Deployments', 'WKS-FEDAI', 'Workshop', 
 'Hands-on, project-driven workshop on training privacy-preserving machine learning models and deploying lightweight inference engines on Raspberry Pi and ESP32 edge hardware.', 
 'Electronics & Communication Engineering', 'Individual (1 Person)', '3 Hours', 1, '₹250 per participant', 'Certification & Hardware Kit Discount', 
 'ECE Seminar Hall, Block B', '2026-10-02', '02:00 PM - 05:00 PM', NULL, NULL, 'Prof. Bob B (IIIT Bangalore Alum)', 
 'Machine Learning, Edge Computing, Python, IoT', 'Limited Seats'),

('event-003', 'Talk', 'Building Scalable Products from Ideas: Kerala to Silicon Valley', 'TLK-STARTUP', 'Talk', 
 'A fireside conversation and interactive AMA with Y-Combinator alumni and deep-tech startup founders sharing frameworks on product-market fit, technical architecture, and open-source monetization.', 
 'IEDC (Innovation & Entrepreneurship)', 'Open to All', '1.5 Hours', 0, 'Free Entry', NULL, 
 'Main APJ Abdul Kalam Auditorium', '2026-10-03', '11:30 AM - 01:00 PM', NULL, 'Rohan Menon (Founder & CEO, NeuralStream AI)', NULL, 
 'Entrepreneurship, Startups, Product Management, AI', 'Open for All'),

('event-004', 'Competition', 'RoboRumble: All-Terrain Robot Challenge', 'CMP-ROBO', 'Competition', 
 'Combat and obstacle-course navigation arena designed to test custom-built wired and wireless rovers tackling sand pits, inclined bridges, and water hurdles.', 
 'Mechanical Engineering & Robotics Club', '2-5 Members', '4 Hours', 1, '₹400 per team', '₹30,000', 
 'Open Air Amphitheatre Arena', '2026-10-03', '02:00 PM - 06:00 PM', 'Prof. Vignesh R & Robotech Society', NULL, NULL, 
 'Robotics, Hardware, Arduino, Mechanics', 'Registration Open'),

('event-005', 'Competition', 'CodeSprint: Speed Coding & Algorithmic Duel', 'CMP-ALGO', 'Competition', 
 'Fast-paced, multi-round competitive programming contest testing dynamic programming, graph theory, mathematical logic, and code optimization against strict time limits.', 
 'Computer Science & Engineering', 'Individual (1 Person)', '3 Hours', 1, '₹100 per participant', '₹15,000', 
 'Systems Programming Lab 2', '2026-10-04', '09:30 AM - 12:30 PM', 'Prof. Charlie C & FOSS Club', NULL, NULL, 
 'C++, Python, Data Structures, Algorithms, Competitive Coding', 'Registration Open'),

('event-006', 'Workshop', 'Cloud Native & Kubernetes Microservices Mastery', 'WKS-K8S', 'Workshop', 
 'Architecting resilient microservices using Docker containers, Helm charts, Envoy proxies, and automated Kubernetes multi-cluster rollouts on Google Cloud.', 
 'Computer Science & Engineering', 'Individual (1 Person)', '4 Hours', 1, '₹300 per participant', 'Google Cloud Credits & Certificate', 
 'Cloud Computing Center, IT Block', '2026-10-03', '09:00 AM - 01:00 PM', NULL, NULL, 'Anjali Nair (Senior SRE, Red Hat)', 
 'DevOps, Kubernetes, Docker, Cloud, Microservices', 'Limited Seats'),

('event-007', 'Competition', 'CyberSiege: 8-Hour CTF (Capture The Flag)', 'CMP-CTF', 'Competition', 
 'Jeopardy-style offensive and defensive cybersecurity tournament spanning binary exploitation, web vulnerabilities, reverse engineering, cryptography, and digital forensics.', 
 'CSE & Cyber Security Cell', '1-3 Members', '8 Hours', 1, '₹200 per team', '₹25,000', 
 'Network Security & Cyber Lab', '2026-10-04', '10:00 AM - 06:00 PM', 'Prof. Bob B & NullChapter GECW', NULL, NULL, 
 'Cybersecurity, Reverse Engineering, Cryptography, Ethical Hacking', 'Registration Open'),

('event-008', 'Talk', 'Geospatial AI & Remote Sensing for Mountain Terrain Mitigation', 'TLK-GEOAI', 'Talk', 
 'Case study review on utilizing radar satellite imagery, LiDAR point clouds, and geospatial neural networks to forecast landslide hazards across the Western Ghats region.', 
 'Civil Engineering & Environmental Cell', 'Open to All', '2 Hours', 0, 'Free Entry', NULL, 
 'Main APJ Abdul Kalam Auditorium', '2026-10-04', '02:30 PM - 04:30 PM', NULL, 'Dr. K. S. Radhakrishnan (Director of Geological Remote Sensing, ISRO)', NULL, 
 'Geospatial AI, Disaster Management, Satellite Imagery, Environmental Science', 'Open for All')
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);

-- Baseline Schedules
INSERT INTO `schedules` 
(`id`, `day`, `day_key`, `time_slot`, `title`, `venue`, `category`, `event_id`, `speaker`) VALUES
('sched-001', 'Day 1 - Oct 2, 2026', 'Day 1', '09:00 AM - 10:00 AM', 'Grand Inauguration Ceremony & Keynote Address', 'Main APJ Abdul Kalam Auditorium', 'Ceremony', NULL, 'Principal & Chief Guest from Tech Industry'),
('sched-002', 'Day 1 - Oct 2, 2026', 'Day 1', '10:00 AM - Onwards', 'HackTheHills 2026 Kickoff & Problem Statement Reveal', 'CSE Advanced Computing Lab', 'Competition', 'event-001', 'Jury Panel'),
('sched-003', 'Day 1 - Oct 2, 2026', 'Day 1', '02:00 PM - 05:00 PM', 'Hands-on Workshop: Federated AI & Edge Deployments', 'ECE Seminar Hall, Block B', 'Workshop', 'event-002', 'Prof. Bob B'),
('sched-004', 'Day 2 - Oct 3, 2026', 'Day 2', '09:00 AM - 01:00 PM', 'Workshop: Cloud Native & Kubernetes Microservices Mastery', 'Cloud Computing Center, IT Block', 'Workshop', 'event-006', 'Anjali Nair'),
('sched-005', 'Day 2 - Oct 3, 2026', 'Day 2', '11:30 AM - 01:00 PM', 'Fireside Talk: Building Scalable Products from Ideas', 'Main APJ Abdul Kalam Auditorium', 'Talk', 'event-003', 'Rohan Menon'),
('sched-006', 'Day 2 - Oct 3, 2026', 'Day 2', '02:00 PM - 06:00 PM', 'RoboRumble: All-Terrain Robot Arena Battles', 'Open Air Amphitheatre Arena', 'Competition', 'event-004', 'Robotics Club Coordinators'),
('sched-007', 'Day 3 - Oct 4, 2026', 'Day 3', '09:30 AM - 12:30 PM', 'CodeSprint: Speed Coding & Algorithmic Duel', 'Systems Programming Lab 2', 'Competition', 'event-005', 'Prof. Charlie C'),
('sched-008', 'Day 3 - Oct 4, 2026', 'Day 3', '05:00 PM - 07:00 PM', 'Valedictory Function, Prize Distribution & Cultural Pro-Show', 'College Main Ground', 'Ceremony', NULL, 'Dignitaries and Student Union')
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);

-- Baseline Updates
INSERT INTO `updates` 
(`id`, `timestamp`, `title`, `tag`, `badge`, `content`) VALUES
('update-001', '2026-10-01T18:30:00+05:30', 'HackTheHills 2026 Problem Tracks Announced', 'Announcement', 'Urgent', 'Problem statements for Disaster Management, Sustainable Agritech, and Smart Campus Solutions are now released on the portal. Participants should verify their team registration by 9 PM tonight.'),
('update-002', '2026-10-01T14:15:00+05:30', 'ECE Seminar Hall Venue Relocation Notice', 'Venue Update', 'Important', 'The Federated AI workshop will now be hosted in ECE Seminar Hall Block B (Ground Floor) to accommodate additional hardware testbeds.'),
('update-003', '2026-09-30T10:00:00+05:30', 'Free Shuttle Service for Outstation Participants', 'Logistics', 'Info', 'Complimentary college bus shuttles will be operating between Mananthavady KSRTC Bus Stand and GEC Wayanad campus every 45 minutes between 7:30 AM and 10:00 PM on all festival days.'),
('update-004', '2026-09-29T16:45:00+05:30', 'Spot Registrations for CyberSiege CTF', 'Registration', 'New', 'Due to overwhelming demand, 5 additional team slots have been enabled for CyberSiege CTF. Check the registration desk at Network Security Lab.')
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`);
