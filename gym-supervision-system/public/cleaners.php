<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/CleanerManager.php';

$cleanerManager = new CleanerManager($conn);

// Fetch users for adding cleaners
$users = [];
$sql = "SELECT id, username FROM users WHERE role = 'cleaner'";
$stmt = $conn->query($sql);
if ($stmt) {
    $users = $stmt->fetchAll();
}

// Fetch cleaning areas for assigning
$cleaningAreas = $cleanerManager->getCleaningAreas();

// Handle form submissions for creating cleaner, cleaning area, assigning timetable, etc.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_cleaner'])) {
        $user_id = $_POST['user_id'];
        $remarks = $_POST['remarks'] ?? '';
        $cleanerManager->addCleaner($user_id, $remarks);
    }
    if (isset($_POST['create_cleaning_area'])) {
        $name = $_POST['name'];
        $description = $_POST['description'] ?? '';
        $cleanerManager->createCleaningArea($name, $description);
    }
    // Additional POST handlers for assigning cleaner to area, adding ratings, etc.
}

$cleaners = $cleanerManager->getCleaners();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Cleaners</title>
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
        <h1 class="text-3xl font-bold text-gray-800">Manage Cleaners</h1>
    </header>

    <section class="mb-6 bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Add New Cleaner</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">User</label>
                <select name="user_id" required class="w-full border border-gray-300 rounded p-2">
                    <option value="">Select User</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user['id']) ?>"><?= htmlspecialchars($user['username']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Remarks</label>
                <textarea name="remarks" class="w-full border border-gray-300 rounded p-2"></textarea>
            </div>
            <button type="submit" name="add_cleaner" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Cleaner</button>
        </form>
    </section>

    <section class="mb-6 bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Create Cleaning Area</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Area Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Description</label>
                <textarea name="description" class="w-full border border-gray-300 rounded p-2"></textarea>
            </div>
            <button type="submit" name="create_cleaning_area" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Area</button>
        </form>
    </section>

    <section class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Existing Cleaners</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">User ID</th>
                    <th class="border border-gray-300 p-2">Username</th>
                    <th class="border border-gray-300 p-2">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cleaners as $cleaner): ?>
                <tr>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($cleaner['id']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($cleaner['user_id']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($cleaner['username']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($cleaner['remarks']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
