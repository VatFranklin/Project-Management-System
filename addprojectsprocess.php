<?php
require_once('config.php');
session_start();

$errors = array(); // Initialize an array to store validation errors



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $selectedStudentID = filter_var($_POST['selectedStudent'], FILTER_SANITIZE_STRING);

    // Add more validation checks as needed
    $batch_year = isset($_POST['yearpicker']) ? $_POST['yearpicker'] : '';

    // Add 2 years to the selected year if it's greater than 2019, else add 3 years
    if (empty($batch_year) || !is_numeric($batch_year)) {
        $message = 'Invalid batch details!';
    } else {
        if ($batch_year <= 2019) {
            $modifiedYear = $batch_year . '-' . ($batch_year + 3);
        } else {
            $modifiedYear = $batch_year . '-' . ($batch_year + 2);
        }
    }
    



    // Check if files were uploaded without errors
    if (isset($_FILES['image_path']) && $_FILES['image_path']['error'] == 0 &&
        isset($_FILES['documentation_link']) && $_FILES['documentation_link']['error'] == 0) {

        // Handle image upload
        $image_name = $_FILES['image_path']['name'];
        $image_tmp = $_FILES['image_path']['tmp_name'];
        $image_path = "uploaded_files/" . basename($image_name); // Adjust the folder path as needed

        // Validate and move the image file
        if (move_uploaded_file($image_tmp, $image_path)) {
            // Image uploaded successfully
        } else {
            $errors['image'] = "Error uploading image. Please try again.";
        }

        // Handle documentation upload
        $doc_name = $_FILES['documentation_link']['name'];
        $doc_tmp = $_FILES['documentation_link']['tmp_name'];
        $documentation_link = "uploaded_files/" . basename($doc_name); // Adjust the folder path as needed

        // Validate and move the documentation file
        if (move_uploaded_file($doc_tmp, $documentation_link)) {
            // Documentation uploaded successfully
        } else {
            $errors['documentation'] = "Error uploading documentation. Please try again.";
        }
    } else {
        $errors['file'] = "Error uploading files. Please try again.";
    }

    // Check for validation errors before inserting into the database
    if (empty($errors)) {
        // Insert data into the 'projects' table using prepared statements
        $insert_query = "INSERT INTO projects (title, image_path, documentation_link, year, name)
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssss", $title, $image_path, $documentation_link, $modifiedYear, $selectedStudentID);

        if ($stmt->execute()) {
            $message = "Project added successfully!";
            header("Location: addprojects.php");
            exit();
        } else {
            $message = "Error adding project. Please try again.";
            header("Location: addprojects.php");
            exit();
        }

        $stmt->close();
    } else {
        $error_message = "Recheck the project details.";
        header("Location: addprojects.php");
        exit();
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Faculty</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="parent">
<div class="container">
    <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom ">
      <div class="col-md-3 mb-2 mb-md-0">
            <a href="home.php" class="logo"><img src="images/logo2.png" ></a>
      </div>

      <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 navbar">
        <li><a href="home.php" class="nav-link px-2 link-secondary"><i class="fas fa-home"></i>Home</a></li>
        <li><a href="teams.html" class="nav-link px-2 link-secondary"><i class="fas fa-chalkboard-user"></i>Teams</a></li>
        <li><a href="announcements.html" class="nav-link px-2 link-secondary"><i class="fa-solid fa-scroll"></i>Announce</a></li>
        <li><a href="tasks.html" class="nav-link px-2 link-secondary"><i class="fa-solid fa-list-check"></i>Tasks</a></li>
        <li><a href="workdiary.html" class="nav-link px-2 link-secondary"><i class="fa-solid fa-book"></i>Workdiary</a></li>
      </ul>
    </header>
  </div> 
</div>


    <section class="form-container">
        
        <form action="addprojects.php" method="post" enctype="multipart/form-data">
            <h3>Add Project</h3>
            <?php if (isset($message)): ?>
    <p style="color: green; font-size: 15px;margin-top:20px;margin-left:130px;"><?php echo $message; ?></p>
<?php endif; ?>
<?php if (isset($error_message)): ?>
    <p style="color: red; font-size: 15px;margin-top:20px;margin-left:50px;"><?php echo $error_message; ?></p>
<?php endif; ?>
            <select id="yearPicker" onchange="getJoinedStudents()" class="box" required>
    <!-- Placeholder option -->
    <option value="" disabled selected>Select Year</option>
    
    <!-- Dynamically populate the years, adjust the range as needed -->
    <?php
        for ($year = date("Y"); $year >= 2000; $year--) {
            echo "<option value=\"$year\">$year</option>";
        }
    ?>
</select>

        <div id="joinedStudents"></div>
            <input type="text" name="title" placeholder="Project Title" class="box">
            <p>Project Image</p>
            <input type="file" name="image_path" accept="image/*" required class="box">
            <p>Documentation</p>
            <input type="file" name="documentation_link" accept=".pdf, .docx" class="box" required>
            <input type="submit" value="add project" name="submit" class="btn">
            <?php if (isset($message)): ?>
    <p style="color: green; font-size: 15px;margin-top:20px;margin-left:130px;"><?php echo $message; ?></p>
<?php endif; ?>
<?php if (isset($error_message)): ?>
    <p style="color: red; font-size: 15px;margin-top:20px;margin-left:50px;"><?php echo $error_message; ?></p>
<?php endif; ?>
        </form>
    </section>
    <?php
        // Assuming you have a MySQL database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "university";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_POST['selectedYear'])) {
            $selectedYear = $_POST['selectedYear'];

            $sql = "SELECT id, name FROM students WHERE join_year = $selectedYear";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h2>Students Joined in $selectedYear:</h2>";
                echo "<form action='process.php' method='post'>";

                while ($row = $result->fetch_assoc()) {
                    echo "<label>";
                    echo "<input type='radio' name='selectedStudent' value='" . $row['id'] . "'>";
                    echo $row['name'];
                    echo "</label>";
                }

                echo "<input type='submit' value='Select Student'>";
                echo "</form>";
            } else {
                echo "<p>No students joined in $selectedYear.</p>";
            }
        }

        $conn->close();
    ?>

    <script>
        // Initialize the date picker
        $(document).ready(function(){
            $('#datepicker').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });
        });
        // script.js
function getJoinedStudents() {
    var yearPicker = document.getElementById("yearPicker");
    var selectedYear = yearPicker.value;

    // Send a request to the server to get joined students for the selected year
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("joinedStudents").innerHTML = xhr.responseText;
        }
    };

    xhr.open("GET", "get_students.php?year=" + selectedYear, true);
    xhr.send();
}

    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-HJov5RR5l1co1xnvdkzKOw5PK2e9j/WWIFHujHh3Fh6ISs2WWxBGng5KCtW5/wr" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>

