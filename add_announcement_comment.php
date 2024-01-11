<?php
// Start or resume the session
session_start();

// Check if the student is logged in
if (!isset($_SESSION['student_username'])) {
    echo "Error: User not logged in.";
    exit;
}

// Include your database connection code
// Replace 'root' with your actual database user, and '' with the password if you have one
$conn = mysqli_connect("localhost", "root", "", "mca");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the announcement ID and new comment from the form
$announcement_id = isset($_POST['announcement_id']) ? $_POST['announcement_id'] : null;
$new_comment = isset($_POST['new_comment']) ? $_POST['new_comment'] : null;

// Validate input
if (!$announcement_id || !$new_comment) {
    echo "Error: Invalid input.";
    exit;
}

// Sanitize input
$announcement_id = mysqli_real_escape_string($conn, $announcement_id);
$new_comment = mysqli_real_escape_string($conn, $new_comment);

// Insert the new comment into the database
$insert_comment_sql = "UPDATE announcements SET comments = CONCAT(IFNULL(comments, ''), '\n', '$new_comment') WHERE id = '$announcement_id'";
$insert_comment_result = mysqli_query($conn, $insert_comment_sql);

if ($insert_comment_result) {
    header('location: viewannouncements.php'); 
    echo "Comment added successfully.";
} else {
    echo "Error adding comment: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
