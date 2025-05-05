<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['role'] ?? 'guest';
?>

<nav class="bg-blue-600 text-white p-4 flex justify-between items-center">
    <div class="flex items-center space-x-4">
        <a href="index.php" class="font-bold text-lg hover:underline">Dashboard</a>
        <a href="classes.php" class="hover:underline">Classes</a>
        <a href="cleaners.php" class="hover:underline">Cleaners</a>
        <a href="maintenance.php" class="hover:underline">Maintenance</a>
        <a href="gym_areas.php" class="hover:underline">Gym Areas</a>
    </div>
    <div class="flex items-center space-x-4">
        <span>Welcome, <?= htmlspecialchars($username) ?> (<?= htmlspecialchars($role) ?>)</span>
        <a href="logout.php" class="hover:underline">Logout</a>
    </div>
</nav>
