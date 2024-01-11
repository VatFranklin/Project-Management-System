<?php
session_start();

// Check if the faculty is logged in
if (!isset($_SESSION['faculty_username'])) {
    // Redirect to the faculty login page or handle the case where the faculty is not logged in
    header('Location: facultylogin.html');
    exit();
}

// Assuming you have a connection to the database already
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mca";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve faculty ID from the session
$faculty_id = $_SESSION['faculty_username'];

// Get form data
$task_name = $_POST['taskName'];
$task_content = $_POST['taskContent'];
$team_name = $_POST['teamSelect'];
$deadline = $_POST['deadline'];

// Insert task into the database
$sql = "INSERT INTO tasks (task_name, task_content, team_name, deadline, faculty_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssss", $task_name, $task_content, $team_name, $deadline, $faculty_id);

if ($stmt->execute()) {
    // Task created successfully
    header('Location: tasks.php'); // Redirect to the tasks page
    exit();
} else {
    echo "Error creating task: " . $stmt->error;
}

// Close the database connection
$stmt->close();
$conn->close();
?>
