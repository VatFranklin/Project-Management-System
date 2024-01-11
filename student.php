<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="parent">
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
                <div class="col-md-3 mb-2 mb-md-0">
                    <a href="home.php" class="logo"><img src="images/logo2.png"></a>
                </div>
                <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 navbar">
                    <li><a href="home.php" class="nav-link px-2 link-secondary"><i class="fas fa-home"></i>Home</a></li>
                    <li><a href="viewannouncements.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-scroll"></i>Announcements</a></li>
                    <li><a href="viewtasks.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-list-check"></i>Tasks</a></li>
                    <li><a href="studentworkdiary.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-book"></i>Workdiary</a></li>
                </ul>
            </header>
        </div>
    </div>

    <div class="container mt-4">
        <?php
        // Assuming you have a MySQL database connection
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "mca";

        $conn = new mysqli($host, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Assuming you have a session variable for the student name
        session_start();
        $studentName = $_SESSION['student_username'];

        echo '<section class="welcome-section">';
        echo "<h2>Welcome, $studentName!</h2>";
        echo '<p>Explore your personalized dashboard and stay updated with the latest information.</p>';
        echo '</section>';

        // Add similar code to fetch and display upcoming events and quick links

        // Close the database connection
        $conn->close();
        ?>
    </div>

    <!-- The rest of your HTML and JavaScript remains unchanged -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Q
</body>

</html>
