<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/GymAreaManager.php';

$gymAreaManager = new GymAreaManager($conn);

// Handle form submissions for creating gym area, etc.
// For brevity, only basic structure is provided here.

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_gym_area'])) {
        $name = $_POST['name'];
        $description = $_POST['description'] ?? '';
        $gymAreaManager->createGymArea($name, $description);
    }
}

$gym_areas = $gymAreaManager->getGymAreas();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Gym Areas</title>
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
        <h1 class="text-3xl font-bold text-gray-800">Manage Gym Areas</h1>
    </header>

    <section class="mb-6 bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Create New Gym Area</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Gym Area Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Description</label>
                <textarea name="description" class="w-full border border-gray-300 rounded p-2"></textarea>
            </div>
            <button type="submit" name="create_gym_area" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Gym Area</button>
        </form>
    </section>

    <section class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Existing Gym Areas</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Name</th>
                    <th class="border border-gray-300 p-2">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gym_areas as $area): ?>
                <tr>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($area['id']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($area['name']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($area['description']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
