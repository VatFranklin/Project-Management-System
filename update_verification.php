<?php
// Include your database connection code here
// For example, include_once 'db_connection.php';
require_once('config.php');
session_start();
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a function to sanitize and validate inputs
    // Include it if needed, for example, include_once 'validation.php';

    // Retrieve the form data
    $entryId = $_POST['entry_id'];
    $comments = $_POST['comments'];

    // Update the entries table
    $sqlUpdate = "UPDATE entries SET verification = 'verified', comments = ? WHERE id = ?";
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $sqlUpdate);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "si", $comments, $entryId);
    
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        echo "Verification and comments updated successfully!";
    } else {
        echo "Error updating verification and comments: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Include your code to close the database connection if needed

// Redirect back to the page where you came from
header("Location: " . $_SERVER["HTTP_REFERER"]);
exit();
?>
