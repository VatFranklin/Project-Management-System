<<?php
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

function unique_id() {
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $rand = array();
    $length = strlen($str) - 1;
    for ($i = 0; $i < 20; $i++) {
        $n = mt_rand(0, $length);
        $rand[] = $str[$n];
    }
    return implode($rand);
 }
?>




 