<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/MaintenanceManager.php';

echo "Testing MaintenanceManager...\n";

$conn = getDbConnection();
$maintenanceManager = new MaintenanceManager($conn);

// Test addMachineType
echo "Adding a new machine type...\n";
$result = $maintenanceManager->addMachineType('Treadmill', 'Cardio machine');
echo $result ? "Machine type added successfully.\n" : "Failed to add machine type.\n";

// Test getMachineTypes
echo "Fetching all machine types...\n";
$machineTypes = $maintenanceManager->getMachineTypes();
foreach ($machineTypes as $type) {
    echo "Machine Type: {$type['name']} - {$type['description']}\n";
}

// Test addMachine
echo "Adding a new machine...\n";
$result = $maintenanceManager->addMachine(1, 'Treadmill #1', 1, 'working');
echo $result ? "Machine added successfully.\n" : "Failed to add machine.\n";

// Test updateMachineStatus
echo "Updating machine status...\n";
$result = $maintenanceManager->updateMachineStatus(1, 'maintenance', 1, 'Routine check');
echo $result ? "Machine status updated successfully.\n" : "Failed to update machine status.\n";

// Test getMachineStatusHistory
echo "Fetching machine status history...\n";
$history = $maintenanceManager->getMachineStatusHistory(1);
foreach ($history as $entry) {
    echo "Status: {$entry['message']} by {$entry['username']}\n";
}

// Test addMaintenanceStaff
echo "Adding maintenance staff...\n";
$result = $maintenanceManager->addMaintenanceStaff(1);
echo $result ? "Maintenance staff added successfully.\n" : "Failed to add maintenance staff.\n";

echo "MaintenanceManager tests completed.\n";
?>
