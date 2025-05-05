<?php
require_once __DIR__ . '/../config.php';

class MaintenanceManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Add machine type
    public function addMachineType($name, $description = '') {
        $sql = "INSERT INTO machine_types (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':description' => $description
        ]);
    }

    // Get all machine types
    public function getMachineTypes() {
        $sql = "SELECT * FROM machine_types";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    // Add machine
    public function addMachine($machine_type_id, $name, $gym_area_id, $status = 'working') {
        $sql = "INSERT INTO machines (machine_type_id, name, gym_area_id, status) VALUES (:machine_type_id, :name, :gym_area_id, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':machine_type_id' => $machine_type_id,
            ':name' => $name,
            ':gym_area_id' => $gym_area_id,
            ':status' => $status
        ]);
    }

    // Get all machines
    public function getMachines() {
        $sql = "SELECT m.*, mt.name as machine_type_name, ga.name as gym_area_name FROM machines m JOIN machine_types mt ON m.machine_type_id = mt.id JOIN gym_areas ga ON m.gym_area_id = ga.id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    // Update machine status and send notifications
    public function updateMachineStatus($machine_id, $status, $user_id, $remarks = '') {
        // Update status
        $sql = "UPDATE machines SET status = :status WHERE id = :machine_id";
        $stmt = $this->conn->prepare($sql);
        $updated = $stmt->execute([
            ':status' => $status,
            ':machine_id' => $machine_id
        ]);

        if ($updated) {
            // Insert maintenance message
            $sql2 = "INSERT INTO maintenance_messages (machine_id, user_id, message) VALUES (:machine_id, :user_id, :message)";
            $stmt2 = $this->conn->prepare($sql2);
            $message = "Status changed to $status. Remarks: $remarks";
            $stmt2->execute([
                ':machine_id' => $machine_id,
                ':user_id' => $user_id,
                ':message' => $message
            ]);

            // Send notifications (email and message)
            $this->sendNotifications($machine_id, $message);
        }

        return $updated;
    }

    // Add maintenance staff (user must exist)
    public function addMaintenanceStaff($user_id) {
        $sql = "INSERT INTO maintenance_staff (user_id) VALUES (:user_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':user_id' => $user_id]);
    }

    // Get maintenance staff
    public function getMaintenanceStaff() {
        $sql = "SELECT ms.*, u.username, u.email FROM maintenance_staff ms JOIN users u ON ms.user_id = u.id";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    // Get machine status history
    public function getMachineStatusHistory($machine_id) {
        $sql = "SELECT mm.*, u.username FROM maintenance_messages mm JOIN users u ON mm.user_id = u.id WHERE mm.machine_id = :machine_id ORDER BY mm.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':machine_id' => $machine_id]);
        return $stmt->fetchAll();
    }

    // Send notifications to maintenance staff, supervisor, admin
    private function sendNotifications($machine_id, $message) {
        // Get emails of maintenance staff, supervisor, admin
        $sql = "SELECT email FROM users WHERE role IN ('maintenance', 'supervisor', 'admin')";
        $stmt = $this->conn->query($sql);
        $emails = [];
        while ($row = $stmt->fetch()) {
            $emails[] = $row['email'];
        }

        // Send email to each
        foreach ($emails as $email) {
            $this->sendEmail($email, "Machine Status Update", $message);
            error_log("Email sent to: $email with message: $message");
        }

        // For messaging, this can be extended to SMS or internal messaging system
        // Simulate SMS sending by logging
        error_log("SMS notification sent for machine ID $machine_id with message: $message");
    }

    // Send email function
    private function sendEmail($to, $subject, $body) {
        $headers = "From: no-reply@gym-supervision.com\r\n";
        $result = mail($to, $subject, $body, $headers);
        error_log("mail() function called for $to with result: " . ($result ? "success" : "failure"));
    }
}
?>
