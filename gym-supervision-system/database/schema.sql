-- Database schema for Gym Supervision System

CREATE DATABASE IF NOT EXISTS gym_supervision;
USE gym_supervision;

-- Users table (for admin, supervisor, maintenance staff, trainers, cleaners)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'supervisor', 'maintenance', 'trainer', 'cleaner') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Gym Areas (main gym, ladies gym)
CREATE TABLE gym_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

-- Classes
CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    gym_area_id INT NOT NULL,
    description TEXT,
    FOREIGN KEY (gym_area_id) REFERENCES gym_areas(id) ON DELETE CASCADE
);

-- Trainers
CREATE TABLE trainers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    bio TEXT,
    rating FLOAT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Class Timetable
CREATE TABLE class_timetables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    trainer_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE CASCADE
);

-- Attendance
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_timetable_id INT NOT NULL,
    user_id INT NOT NULL,
    attendance_date DATE NOT NULL,
    status ENUM('present', 'absent') NOT NULL,
    FOREIGN KEY (class_timetable_id) REFERENCES class_timetables(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Cleaners
CREATE TABLE cleaners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    rating FLOAT DEFAULT 0,
    remarks TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Cleaning Areas
CREATE TABLE cleaning_areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

-- Cleanliness Timetable
CREATE TABLE cleanliness_timetables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cleaning_area_id INT NOT NULL,
    cleaner_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (cleaning_area_id) REFERENCES cleaning_areas(id) ON DELETE CASCADE,
    FOREIGN KEY (cleaner_id) REFERENCES cleaners(id) ON DELETE CASCADE
);

-- Maintenance Staff
CREATE TABLE maintenance_staff (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Machine Types
CREATE TABLE machine_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);

-- Machines
CREATE TABLE machines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_type_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    status ENUM('working', 'broken', 'maintenance') NOT NULL DEFAULT 'working',
    gym_area_id INT NOT NULL,
    FOREIGN KEY (machine_type_id) REFERENCES machine_types(id) ON DELETE CASCADE,
    FOREIGN KEY (gym_area_id) REFERENCES gym_areas(id) ON DELETE CASCADE
);

-- Maintenance Messages and Remarks
CREATE TABLE maintenance_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    machine_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (machine_id) REFERENCES machines(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Trainer Ratings
CREATE TABLE trainer_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trainer_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trainer_id) REFERENCES trainers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Cleaner Ratings
CREATE TABLE cleaner_ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cleaner_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cleaner_id) REFERENCES cleaners(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
