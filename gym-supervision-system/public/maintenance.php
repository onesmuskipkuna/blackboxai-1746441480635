<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/MaintenanceManager.php';

$maintenanceManager = new MaintenanceManager($conn);

// Fetch machine types, machines, and maintenance staff
$machine_types = $maintenanceManager->getMachineTypes();
$machines = $maintenanceManager->getMachines();
$maintenance_staff = $maintenanceManager->getMaintenanceStaff();

// Fetch users for adding maintenance staff
$users = [];
$sql = "SELECT id, username FROM users WHERE role = 'maintenance'";
$stmt = $conn->query($sql);
if ($stmt) {
    $users = $stmt->fetchAll();
}

// Handle form submissions for adding machine types, machines, updating status, adding maintenance staff, etc.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_machine_type'])) {
        $name = $_POST['name'];
        $description = $_POST['description'] ?? '';
        $maintenanceManager->addMachineType($name, $description);
    }
    if (isset($_POST['add_machine'])) {
        $machine_type_id = $_POST['machine_type_id'];
        $name = $_POST['name'];
        $gym_area_id = $_POST['gym_area_id'];
        $status = $_POST['status'] ?? 'working';
        $maintenanceManager->addMachine($machine_type_id, $name, $gym_area_id, $status);
    }
    if (isset($_POST['update_machine_status'])) {
        $machine_id = $_POST['machine_id'];
        $status = $_POST['status'];
        $user_id = $_POST['user_id'];
        $remarks = $_POST['remarks'] ?? '';
        $maintenanceManager->updateMachineStatus($machine_id, $status, $user_id, $remarks);
    }
    if (isset($_POST['add_maintenance_staff'])) {
        $user_id = $_POST['user_id'];
        $maintenanceManager->addMaintenanceStaff($user_id);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Maintenance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<?php include 'includes/navbar.php'; ?>
<body class="bg-gray-100 p-6">
    <header class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manage Maintenance</h1>
    </header>

    <section class="mb-6 bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Add Machine Type</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Machine Type Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Description</label>
                <textarea name="description" class="w-full border border-gray-300 rounded p-2"></textarea>
            </div>
            <button type="submit" name="add_machine_type" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Machine Type</button>
        </form>
    </section>

    <section class="mb-6 bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Add Machine</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Machine Type</label>
                <select name="machine_type_id" required class="w-full border border-gray-300 rounded p-2">
                    <option value="">Select Machine Type</option>
                    <?php foreach ($machine_types as $type): ?>
                    <option value="<?= htmlspecialchars($type['id']) ?>"><?= htmlspecialchars($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Machine Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Gym Area ID</label>
                <input type="number" name="gym_area_id" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded p-2">
                    <option value="working">Working</option>
                    <option value="broken">Broken</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <button type="submit" name="add_machine" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Machine</button>
        </form>
    </section>

    <section class="mb-6 bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Update Machine Status</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Machine ID</label>
                <input type="number" name="machine_id" id="machine_id_input" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">New Status</label>
                <select name="status" required class="w-full border border-gray-300 rounded p-2">
                    <option value="working">Working</option>
                    <option value="broken">Broken</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">User ID (Updater)</label>
                <input type="number" name="user_id" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Remarks</label>
                <textarea name="remarks" class="w-full border border-gray-300 rounded p-2"></textarea>
            </div>
            <button type="submit" name="update_machine_status" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Update Status</button>
        </form>
    </section>

    <section class="mb-6 bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Add Maintenance Staff</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">User ID</label>
                <input type="number" name="user_id" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <button type="submit" name="add_maintenance_staff" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Add Staff</button>
        </form>
    </section>

    <section class="mb-6 bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Existing Machines</h2>
        <input type="text" id="machine_search" placeholder="Search machines..." class="mb-4 w-full border border-gray-300 rounded p-2" />
        <table id="machines_table" class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Name</th>
                    <th class="border border-gray-300 p-2">Type</th>
                    <th class="border border-gray-300 p-2">Gym Area</th>
                    <th class="border border-gray-300 p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($machines as $machine): ?>
                <tr data-machine-id="<?= htmlspecialchars($machine['id']) ?>">
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($machine['id']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($machine['name']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($machine['machine_type_name']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($machine['gym_area_name']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($machine['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <section class="bg-white p-4 rounded shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">Machine Status History</h2>
        <div id="status_history" class="overflow-auto max-h-64 border border-gray-300 rounded p-2">
            <p>Select a machine from the table above to view its status history.</p>
        </div>
    </section>

    <script>
        // Filter machines table based on search input
        document.getElementById('machine_search').addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#machines_table tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        // Fetch and display machine status history when a machine row is clicked
        document.querySelectorAll('#machines_table tbody tr').forEach(row => {
            row.addEventListener('click', () => {
                const machineId = row.getAttribute('data-machine-id');
                // Set machine ID in update form
                document.getElementById('machine_id_input').value = machineId;

                fetch(`maintenance_status_history.php?machine_id=${machineId}`)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('status_history');
                        if (data.length === 0) {
                            container.innerHTML = '<p>No status history found for this machine.</p>';
                            return;
                        }
                        let html = '<ul class="list-disc pl-5">';
                        data.forEach(entry => {
                            html += `<li><strong>${entry.username}</strong> (${entry.created_at}): ${entry.message}</li>`;
                        });
                        html += '</ul>';
                        container.innerHTML = html;
                    })
                    .catch(() => {
                        document.getElementById('status_history').innerHTML = '<p>Error loading status history.</p>';
                    });
            });
        });
    </script>
</body>
</html>
