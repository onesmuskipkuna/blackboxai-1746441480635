<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/MaintenanceManager.php';

header('Content-Type: application/json');

if (!isset($_GET['machine_id'])) {
    echo json_encode([]);
    exit;
}

$machine_id = intval($_GET['machine_id']);
$maintenanceManager = new MaintenanceManager($conn);
$history = $maintenanceManager->getMachineStatusHistory($machine_id);

echo json_encode($history);
?>
