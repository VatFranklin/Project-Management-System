<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Student Selection</title>
   <link rel="stylesheet" href="style.css">
<style>
    /* Add these styles to your existing styles or style tag */
    .styled-table {
        border-collapse: collapse;
        width: 80%;
        margin: auto;
        border: 2px solid #fff;
        box-sizing: border-box;
        overflow-x: auto;
    }

    .styled-table th, .styled-table td {
        border: 2px solid #fff;
        padding: 8px;
        text-align: center;
        font-size: 18px;
    }

    .styled-checkbox {
        font-size: 18px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .styled-checkbox label {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding-left: 30px; /* Adjusted to center the checkbox */
        cursor: pointer;
    }

    .styled-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Updated styles for radio button */
    .styled-checkbox .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #fff; /* Set the background color to white */
        border: 1px solid #888;
        border-radius: 50%; /* Use border-radius to create a circle */
    }

    .styled-checkbox:hover .checkmark {
        background-color: #ccc;
    }

    .styled-checkbox input:checked ~ .checkmark {
        background-color: var(--main-color);
    }

    .styled-checkbox .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .styled-checkbox input:checked ~ .checkmark:after {
        display: block;
    }

    .styled-checkbox .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }
</style>

</head>
<body>
<?php
// Assuming you have a MySQL database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mca";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['year'])) {
    $selectedYear = $_GET['year'];

    $sql = "SELECT username, name FROM studentlogin WHERE batch = $selectedYear";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table class='styled-table'>";
        echo "<thead><tr><th>Select</th><th>Student Name</th></tr></thead><tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='styled-checkbox'><label><input type='radio' name='selectedStudent' value='" . $row['username'] . "' onchange='displaySelectedStudentName(\"" . $row['username'] . "\", \"" . $row['name'] . "\")'><span class='checkmark'></span></label></td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } else {
        echo "<table class='styled-table'>";
        echo "<tr><td colspan='2' style='text-align: center;'>No students joined in $selectedYear.</td></tr>";
        echo "</table>";
    }
}

$conn->close();
?>


</body>
</html>
