<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Connect to the database
    $conn = mysqli_connect("localhost", "root", "", "mca");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch team_number from studentlogin table
    $sql = "SELECT team_number FROM studentlogin WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['team_number'];
    }

    mysqli_close($conn);
}
?>
