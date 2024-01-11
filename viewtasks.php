<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure that session_start() is called at the beginning
session_start();

// Replace 'root' with your actual database user, and '' with the password if you have one
$conn = mysqli_connect("localhost", "root", "", "mca");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
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
   <style>
      body {
         margin-bottom: 70px;
      }

      .parent {
         background-color: #f8f9fa;
      }

      .footer {
         position: fixed;
         bottom: 0;
         width: 100%;
         background-color: #f8f9fa;
         text-align: center;
         padding: 20px;
      }

      .team-card {
         border: 1px solid #ddd;
         padding: 20px;
         margin: 10px;
         cursor: pointer;
         background-color:#fff;
      }

      .team-card:hover {
         background-color: #f5f5f5;
      }

      .work-entry-card {
         border: 1px solid #ddd;
         padding: 20px;
         margin: 10px;
      }

      .verify-btn {
         cursor: pointer;
      }

      .comments-section {
         margin-top: 10px;
      }
      /* Add this style in the <style> section of your HTML or link it from an external CSS file */

/* For Verified entries */
.verification-toggle.btn-success {
    background-color: #28a745; /* Green color */
    color: #fff; /* White text */
}

/* For Unverified entries */
.verification-toggle.btn-danger {
    background-color: #dc3545; /* Red color */
    color: #fff; /* White text */
}
.btn-success{
    background-color: #28a745; /* Green color */
    color: #fff; /* White text */
}
.btn-danger
{
    background-color: #dc3545; /* Red color */
    color: #fff; /* White text */
}
</style>
</head>
<body>
   <div class="parent">
      <div class="container">
         <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
               <a href="home.php" class="logo"><img src="images/logo2.png" ></a>
            </div>
            <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 navbar">
               <li><a href="home.php" class="nav-link px-2 link-secondary"><i class="fas fa-home"></i>Home</a></li>
               <li><a href="viewannouncements.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-scroll"></i>Announce</a></li>
               <li><a href="viewtasks.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-list-check"></i>Tasks</a></li>
               <li><a href="studentworkdiary.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-book"></i>Workdiary</a></li>
            </ul>
         </header>
      </div>
   </div>
   <?php
// Start or resume the session


// Fetch team number from the session (modify this based on your session variable)
$student_username = isset($_SESSION['student_username']) ? $_SESSION['student_username'] : null;

// Check if the student is logged in
if ($student_username) {
    // Fetch team number associated with the student from the database
    $team_number_sql = "SELECT team_number FROM studentlogin WHERE username = '$student_username'";
    $team_number_result = mysqli_query($conn, $team_number_sql);

    if ($team_number_result) {
        $team_info = mysqli_fetch_assoc($team_number_result);
        $team_number = $team_info['team_number'];

        // Fetch announcements based on the student's team number
        $announcement_sql = "SELECT * FROM tasks WHERE team_name = '$team_number' ORDER BY created_at DESC";
        $announcement_result = mysqli_query($conn, $announcement_sql);

        if ($announcement_result) {
            if (mysqli_num_rows($announcement_result) > 0) {
                echo '<div class="container">';
                echo '<div class="row">';
                while ($announcement_row = mysqli_fetch_assoc($announcement_result)) {
                    echo '<div class="col-md-10" style="margin-left:100px;">';
                    echo '<div class="card mb-4">';
                    
                    $formatted_announcement_date_time = date('d-m-Y g:i a', strtotime($announcement_row['created_at']));

                    echo '<div class="card-header d-flex justify-content-between align-items-center">';
                    echo '<span class="text-muted">' . $formatted_announcement_date_time . '</span>';
                    echo '</div>';
                    
                    echo '<div class="card-body">';
                    echo '<h5 class="card-text">' . $announcement_row['task_content'] . '</h5>';
                    
                    // Check if there is a file attached
                    if (!empty($announcement_row['file_name'])) {
                        echo '<h5 style="margin-top:20px;">Attachment: <a href="uploaded_files/' . $announcement_row['file_name'] . '">' . $announcement_row['file_name'] . '</a></h5>';
                    }



                    // Allow students to add comments
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="announcement_id" value="' . $announcement_row['id'] . '">';
                    echo '<textarea class="form-control" name="new_comment" placeholder="Add Comments..." rows="3" style="font-size: 14px; border-radius:25px; margin-top:20px;"></textarea>';
                    echo '<button type="submit" class="btn" style="width: 30%; font-size: 12px; padding: 4px; float:right;">Add Comment <i class="fa-regular fa-circle-right" style="color: #fff;"></i></button>';
                    echo '</form>';

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>';
                echo '</div>';
            } else {
                echo '<p>No announcements found for your team.</p>';
            }
        } else {
            echo "Error fetching announcements: " . mysqli_error($conn);
        }
    } else {
        echo "Error fetching team number: " . mysqli_error($conn);
    }
} else {
    echo '<p>No student username found in the session. Please log in.</p>';
}

mysqli_close($conn);
?>
<script>
// JavaScript to toggle visibility when label is clicked
document.getElementById('toggleComments').addEventListener('change', function() {
    var commentsContent = document.querySelector('.comments-content');
    commentsContent.style.display = this.checked ? 'block' : 'none';
});
</script>

 <footer class="footer">
      &copy; Copyright @ 2023 <span>PSG</span> | all rights reserved!
      </footer>
     

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
