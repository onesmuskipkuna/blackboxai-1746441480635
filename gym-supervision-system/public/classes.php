<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/ClassManager.php';

$classManager = new ClassManager($conn);

// Fetch gym areas for dropdown
$gymAreas = [];
$sql = "SELECT id, name FROM gym_areas";
$stmt = $conn->query($sql);
if ($stmt) {
    $gymAreas = $stmt->fetchAll();
}

// Fetch trainers for assigning to classes
$trainers = [];
$sql = "SELECT t.id, u.username FROM trainers t JOIN users u ON t.user_id = u.id";
$stmt = $conn->query($sql);
if ($stmt) {
    $trainers = $stmt->fetchAll();
}

// Handle form submissions for creating class, assigning trainer, marking attendance, etc.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_class'])) {
        $name = $_POST['name'];
        $gym_area_id = $_POST['gym_area_id'];
        $description = $_POST['description'] ?? '';
        $classManager->createClass($name, $gym_area_id, $description);
    } elseif (isset($_POST['assign_trainer'])) {
        $class_id = $_POST['class_id'];
        $trainer_id = $_POST['trainer_id'];
        $day_of_week = $_POST['day_of_week'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $classManager->assignTrainerToClass($class_id, $trainer_id, $day_of_week, $start_time, $end_time);
    } elseif (isset($_POST['mark_attendance'])) {
        $class_timetable_id = $_POST['class_timetable_id'];
        $user_id = $_POST['user_id'];
        $attendance_date = $_POST['attendance_date'];
        $status = $_POST['status'];
        $classManager->markAttendance($class_timetable_id, $user_id, $attendance_date, $status);
    }
}

$classes = $classManager->getClasses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Classes</title>
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
        <h1 class="text-3xl font-bold text-gray-800">Manage Classes</h1>
    </header>

    <section class="mb-6 bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Create New Class</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Class Name</label>
                <input type="text" name="name" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">Gym Area</label>
                <select name="gym_area_id" required class="w-full border border-gray-300 rounded p-2">
                    <option value="">Select Gym Area</option>
                    <?php foreach ($gymAreas as $area): ?>
                        <option value="<?= htmlspecialchars($area['id']) ?>"><?= htmlspecialchars($area['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Description</label>
                <textarea name="description" class="w-full border border-gray-300 rounded p-2"></textarea>
            </div>
            <button type="submit" name="create_class" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Class</button>
        </form>
    </section>

    <section class="mb-6 bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Assign Trainer to Class</h2>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block mb-1 font-medium">Class</label>
                <select name="class_id" required class="w-full border border-gray-300 rounded p-2">
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= htmlspecialchars($class['id']) ?>"><?= htmlspecialchars($class['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Trainer</label>
                <select name="trainer_id" required class="w-full border border-gray-300 rounded p-2">
                    <option value="">Select Trainer</option>
                    <?php foreach ($trainers as $trainer): ?>
                        <option value="<?= htmlspecialchars($trainer['id']) ?>"><?= htmlspecialchars($trainer['username']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Day of Week</label>
                <select name="day_of_week" required class="w-full border border-gray-300 rounded p-2">
                    <option value="">Select Day</option>
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                    <option>Sunday</option>
                </select>
            </div>
            <div>
                <label class="block mb-1 font-medium">Start Time</label>
                <input type="time" name="start_time" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <div>
                <label class="block mb-1 font-medium">End Time</label>
                <input type="time" name="end_time" required class="w-full border border-gray-300 rounded p-2" />
            </div>
            <button type="submit" name="assign_trainer" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Assign Trainer</button>
        </form>
    </section>

    <section class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Existing Classes</h2>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="border border-gray-300 p-2">ID</th>
                    <th class="border border-gray-300 p-2">Name</th>
                    <th class="border border-gray-300 p-2">Gym Area</th>
                    <th class="border border-gray-300 p-2">Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                <tr>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($class['id']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($class['name']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($class['gym_area_name']) ?></td>
                    <td class="border border-gray-300 p-2"><?= htmlspecialchars($class['description']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</body>
</html>
