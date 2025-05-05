<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../classes/ClassManager.php';

echo "Testing ClassManager...\n";

$conn = getDbConnection();
$classManager = new ClassManager($conn);

// Test createClass
echo "Creating a new class...\n";
$result = $classManager->createClass('Yoga Basics', 1, 'Beginner yoga class');
echo $result ? "Class created successfully.\n" : "Failed to create class.\n";

// Test getClasses
echo "Fetching all classes...\n";
$classes = $classManager->getClasses();
foreach ($classes as $class) {
    echo "Class: {$class['name']} in Gym Area: {$class['gym_area_name']}\n";
}

// Test assignTrainerToClass
echo "Assigning trainer to class...\n";
$result = $classManager->assignTrainerToClass(1, 1, 'Monday', '09:00:00', '10:00:00');
echo $result ? "Trainer assigned successfully.\n" : "Failed to assign trainer.\n";

// Test markAttendance
echo "Marking attendance...\n";
$result = $classManager->markAttendance(1, 1, date('Y-m-d'), 'present');
echo $result ? "Attendance marked successfully.\n" : "Failed to mark attendance.\n";

// Test addTrainerRating
echo "Adding trainer rating...\n";
$result = $classManager->addTrainerRating(1, 1, 5, 'Great trainer!');
echo $result ? "Trainer rating added successfully.\n" : "Failed to add trainer rating.\n";

echo "ClassManager tests completed.\n";
?>
