<?php
require_once __DIR__ . '/../config.php';

class GymAreaManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Create gym area
    public function createGymArea($name, $description = '') {
        $sql = "INSERT INTO gym_areas (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description
        ]);
    }

    // Get all gym areas
    public function getGymAreas() {
        $sql = "SELECT * FROM gym_areas";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    // Get trainers for a gym area
    public function getTrainersByGymArea($gym_area_id) {
        $sql = "SELECT t.*, u.username FROM trainers t JOIN users u ON t.user_id = u.id JOIN class_timetables ct ON t.id = ct.trainer_id JOIN classes c ON ct.class_id = c.id WHERE c.gym_area_id = :gym_area_id GROUP BY t.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':gym_area_id' => $gym_area_id]);
        return $stmt->fetchAll();
    }

    // Get gym area trainers timetable
    public function getGymAreaTrainersTimetable($gym_area_id) {
        $sql = "SELECT ct.*, t.user_id, u.username as trainer_name, c.name as class_name FROM class_timetables ct JOIN trainers t ON ct.trainer_id = t.id JOIN users u ON t.user_id = u.id JOIN classes c ON ct.class_id = c.id WHERE c.gym_area_id = :gym_area_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':gym_area_id' => $gym_area_id]);
        return $stmt->fetchAll();
    }

    // Get trainer ratings for gym area trainers
    public function getTrainerRatingsByGymArea($gym_area_id) {
        $sql = "SELECT tr.* FROM trainer_ratings tr JOIN trainers t ON tr.trainer_id = t.id JOIN class_timetables ct ON t.id = ct.trainer_id JOIN classes c ON ct.class_id = c.id WHERE c.gym_area_id = :gym_area_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':gym_area_id' => $gym_area_id]);
        return $stmt->fetchAll();
    }
}
?>
