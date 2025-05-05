<?php
require_once __DIR__ . '/../config.php';

class ClassManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Create a new class
    public function createClass($name, $gym_area_id, $description = '') {
        $sql = "INSERT INTO classes (name, gym_area_id, description) VALUES (:name, :gym_area_id, :description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':gym_area_id' => $gym_area_id,
            ':description' => $description
        ]);
    }

    // Get all classes
    public function getClasses() {
        $sql = "SELECT c.*, g.name as gym_area_name FROM classes c JOIN gym_areas g ON c.gym_area_id = g.id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    // Assign trainer to class timetable
    public function assignTrainerToClass($class_id, $trainer_id, $day_of_week, $start_time, $end_time) {
        $sql = "INSERT INTO class_timetables (class_id, trainer_id, day_of_week, start_time, end_time) VALUES (:class_id, :trainer_id, :day_of_week, :start_time, :end_time)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':class_id' => $class_id,
            ':trainer_id' => $trainer_id,
            ':day_of_week' => $day_of_week,
            ':start_time' => $start_time,
            ':end_time' => $end_time
        ]);
    }

    // Get timetable for a class
    public function getClassTimetable($class_id) {
        $sql = "SELECT ct.*, t.user_id, u.username as trainer_name FROM class_timetables ct JOIN trainers t ON ct.trainer_id = t.id JOIN users u ON t.user_id = u.id WHERE ct.class_id = :class_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':class_id' => $class_id]);
        return $stmt->fetchAll();
    }

    // Mark attendance
    public function markAttendance($class_timetable_id, $user_id, $attendance_date, $status) {
        $sql = "INSERT INTO attendance (class_timetable_id, user_id, attendance_date, status) VALUES (:class_timetable_id, :user_id, :attendance_date, :status)
                ON DUPLICATE KEY UPDATE status = VALUES(status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':class_timetable_id' => $class_timetable_id,
            ':user_id' => $user_id,
            ':attendance_date' => $attendance_date,
            ':status' => $status
        ]);
    }

    // Get trainer ratings
    public function getTrainerRatings($trainer_id) {
        $sql = "SELECT * FROM trainer_ratings WHERE trainer_id = :trainer_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':trainer_id' => $trainer_id]);
        return $stmt->fetchAll();
    }

    // Add trainer rating
    public function addTrainerRating($trainer_id, $user_id, $rating, $comment = '') {
        $sql = "INSERT INTO trainer_ratings (trainer_id, user_id, rating, comment) VALUES (:trainer_id, :user_id, :rating, :comment)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':trainer_id' => $trainer_id,
            ':user_id' => $user_id,
            ':rating' => $rating,
            ':comment' => $comment
        ]);
    }

    // Add a new trainer (user must exist)
    public function addTrainer($user_id, $bio = '') {
        $sql = "INSERT INTO trainers (user_id, bio) VALUES (:user_id, :bio)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':bio' => $bio
        ]);
    }
}
?>
