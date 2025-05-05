<?php
require_once __DIR__ . '/../config.php';

class CleanerManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Create a new cleaner (user must exist)
    public function addCleaner($user_id, $remarks = '') {
        $sql = "INSERT INTO cleaners (user_id, remarks) VALUES (:user_id, :remarks)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':remarks' => $remarks
        ]);
    }

    // Get all cleaners
    public function getCleaners() {
        $sql = "SELECT c.*, u.username FROM cleaners c JOIN users u ON c.user_id = u.id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    // Create cleaning area
    public function createCleaningArea($name, $description = '') {
        $sql = "INSERT INTO cleaning_areas (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description
        ]);
    }

    // Get all cleaning areas
    public function getCleaningAreas() {
        $sql = "SELECT * FROM cleaning_areas";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    // Assign cleaner to cleaning timetable
    public function assignCleanerToArea($cleaning_area_id, $cleaner_id, $day_of_week, $start_time, $end_time) {
        $sql = "INSERT INTO cleanliness_timetables (cleaning_area_id, cleaner_id, day_of_week, start_time, end_time) VALUES (:cleaning_area_id, :cleaner_id, :day_of_week, :start_time, :end_time)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':cleaning_area_id' => $cleaning_area_id,
            ':cleaner_id' => $cleaner_id,
            ':day_of_week' => $day_of_week,
            ':start_time' => $start_time,
            ':end_time' => $end_time
        ]);
    }

    // Get cleanliness timetable for an area
    public function getCleanlinessTimetable($cleaning_area_id) {
        $sql = "SELECT ct.*, u.username as cleaner_name FROM cleanliness_timetables ct JOIN cleaners c ON ct.cleaner_id = c.id JOIN users u ON c.user_id = u.id WHERE ct.cleaning_area_id = :cleaning_area_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cleaning_area_id' => $cleaning_area_id]);
        return $stmt->fetchAll();
    }

    // Get cleaner ratings
    public function getCleanerRatings($cleaner_id) {
        $sql = "SELECT * FROM cleaner_ratings WHERE cleaner_id = :cleaner_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':cleaner_id' => $cleaner_id]);
        return $stmt->fetchAll();
    }

    // Add cleaner rating
    public function addCleanerRating($cleaner_id, $user_id, $rating, $comment = '') {
        $sql = "INSERT INTO cleaner_ratings (cleaner_id, user_id, rating, comment) VALUES (:cleaner_id, :user_id, :rating, :comment)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':cleaner_id' => $cleaner_id,
            ':user_id' => $user_id,
            ':rating' => $rating,
            ':comment' => $comment
        ]);
    }
}
?>
