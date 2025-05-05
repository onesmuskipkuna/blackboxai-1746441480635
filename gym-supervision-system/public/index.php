<?php
require_once __DIR__ . '/../config.php';

// Fetch dynamic data for charts
// Trainer Ratings
$trainerRatingsCount = array_fill(1, 5, 0);
$sql = "SELECT rating, COUNT(*) as count FROM trainer_ratings GROUP BY rating";
$stmt = $conn->query($sql);
while ($row = $stmt->fetch()) {
    $rating = (int)$row['rating'];
    $count = (int)$row['count'];
    $trainerRatingsCount[$rating] = $count;
}

// Cleaner Ratings
$cleanerRatingsCount = array_fill(1, 5, 0);
$sql = "SELECT rating, COUNT(*) as count FROM cleaner_ratings GROUP BY rating";
$stmt = $conn->query($sql);
while ($row = $stmt->fetch()) {
    $rating = (int)$row['rating'];
    $count = (int)$row['count'];
    $cleanerRatingsCount[$rating] = $count;
}

// Machine Status
$machineStatusCount = ['working' => 0, 'broken' => 0, 'maintenance' => 0];
$sql = "SELECT status, COUNT(*) as count FROM machines GROUP BY status";
$stmt = $conn->query($sql);
while ($row = $stmt->fetch()) {
    $status = $row['status'];
    $count = (int)$row['count'];
    $machineStatusCount[$status] = $count;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gym Supervision Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<?php include 'includes/navbar.php'; ?>
<body class="bg-gray-100 min-h-screen p-6">
    <header class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Gym Supervision Dashboard</h1>
    </header>

    <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <section class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-semibold mb-4">Trainer Ratings</h2>
            <canvas id="trainerRatingsChart"></canvas>
        </section>

        <section class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-semibold mb-4">Cleaner Ratings</h2>
            <canvas id="cleanerRatingsChart"></canvas>
        </section>

        <section class="bg-white rounded-lg shadow p-4">
            <h2 class="text-xl font-semibold mb-4">Machine Status</h2>
            <canvas id="machineStatusChart"></canvas>
        </section>
    </main>

    <script>
        const trainerRatingsData = {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Number of Ratings',
                data: [
                    <?= $trainerRatingsCount[1] ?>,
                    <?= $trainerRatingsCount[2] ?>,
                    <?= $trainerRatingsCount[3] ?>,
                    <?= $trainerRatingsCount[4] ?>,
                    <?= $trainerRatingsCount[5] ?>
                ],
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        };

        const cleanerRatingsData = {
            labels: ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
            datasets: [{
                label: 'Number of Ratings',
                data: [
                    <?= $cleanerRatingsCount[1] ?>,
                    <?= $cleanerRatingsCount[2] ?>,
                    <?= $cleanerRatingsCount[3] ?>,
                    <?= $cleanerRatingsCount[4] ?>,
                    <?= $cleanerRatingsCount[5] ?>
                ],
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1
            }]
        };

        const machineStatusData = {
            labels: ['Working', 'Broken', 'Maintenance'],
            datasets: [{
                label: 'Machines',
                data: [
                    <?= $machineStatusCount['working'] ?>,
                    <?= $machineStatusCount['broken'] ?>,
                    <?= $machineStatusCount['maintenance'] ?>
                ],
                backgroundColor: [
                    'rgba(34, 197, 94, 0.7)',
                    'rgba(239, 68, 68, 0.7)',
                    'rgba(234, 179, 8, 0.7)'
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(239, 68, 68, 1)',
                    'rgba(234, 179, 8, 1)'
                ],
                borderWidth: 1
            }]
        };

        const configTrainer = {
            type: 'bar',
            data: trainerRatingsData,
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        };

        const configCleaner = {
            type: 'bar',
            data: cleanerRatingsData,
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        };

        const configMachine = {
            type: 'pie',
            data: machineStatusData,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        };

        new Chart(document.getElementById('trainerRatingsChart'), configTrainer);
        new Chart(document.getElementById('cleanerRatingsChart'), configCleaner);
        new Chart(document.getElementById('machineStatusChart'), configMachine);
    </script>
</body>
</html>
