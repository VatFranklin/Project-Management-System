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
               <li><a href="teams.html" class="nav-link px-2 link-secondary"><i class="fas fa-chalkboard-user"></i>Teams</a></li>
               <li><a href="announcements.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-scroll"></i>Announce</a></li>
               <li><a href="tasks.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-list-check"></i>Tasks</a></li>
               <li><a href="facultyworkdiary.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-book"></i>Workdiary</a></li>
            </ul>
         </header>
      </div>
   </div>
   <?php
// Fetch entries based on team_number
$team_number = isset($_GET['team_name']) ? $_GET['team_name'] : null;

if ($team_number) {
    $sql = "SELECT id, created_at, title, description, attachments, verification, comments FROM entries WHERE team_number = '$team_number' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="container">';
            echo '<div class="row">';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="col-md-10" style="margin-left:100px;">';
                echo '<div class="card mb-4">';
                
                $formatted_date_time = date('d-m-Y g:i a', strtotime($row['created_at']));

                
                echo '<div class="card-header d-flex justify-content-between align-items-center" >';
                echo '<span>';
                $buttonClass = 'verification-toggle ' . ($row['verification'] == 'verified' ? 'btn-success' : 'btn-danger');
                echo '<button style="font-size:14px;" class="' . $buttonClass . '" data-entry-id="' . $row['id'] . '" data-current-status="' . $row['verification'] . '">';
                echo $row['verification'] == 'verified' ? 'Verified' : 'Unverified';
                echo '</button>';
                echo '</span>';
                echo '<span class="text-muted">' . $formatted_date_time . '</span>';
                echo '</div>';
                
                echo '<div class="card-body">';
                echo '<h5 class="card-text">' . $row['description'] . '</h5>';
                echo '<h5 style="margin-top:20px;">Attachments: <a href="' . $row['attachments'] . '">Attachment</a></h5>';
                
                echo '<div class="comments-section mt-auto" >';
                echo '<form method="post" action="update_verification.php">';
                echo '<input type="hidden" name="entry_id" value="' . $row['id'] . '">';
                echo '<textarea class="form-control " name="comments" placeholder="Add Comments..." rows="3" style="font-size: 14px;border-radius:25px;margin-top:20px;">' . $row['comments'] . '</textarea>';
                echo '<button type="submit" class="btn" style="width: 10%; font-size: 12px; padding: 4px;float:right;">Send  <i class="fa-regular fa-circle-right" style="color: #fff;"></i></button>';

               
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="container" id="workDiaryContainer" style="display: block;">';
            echo '<h2 class="mt-4">Work Diary</h2>';
            echo '<p>No entries found for team number: ' . $team_number . '</p>';
            echo '</div>';
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo '<div class="container" id="workDiaryContainer" style="display: block;">';
    echo '<h2 class="mt-4">Work Diary</h2>';
    echo '<p>No team number found in the session.</p>';
    echo '</div>';
}

mysqli_close($conn);
?>
 <footer class="footer">
      &copy; Copyright @ 2023 <span>PSG</span> | all rights reserved!
      </footer>
      <script>
      document.addEventListener('DOMContentLoaded', function () {
    var toggleButtons = document.querySelectorAll('.verification-toggle');

    toggleButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var entryId = button.getAttribute('data-entry-id');
            var currentStatus = button.getAttribute('data-current-status');

            // Send an AJAX request to update the verification status in the database
            fetch('update_verification.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'entry_id=' + entryId + '&status=' + (currentStatus === 'verified' ? 'unverified' : 'verified'),
            })
            .then(response => response.text())
            .then(data => {
                console.log(data); // Handle the response as needed
            });

            // Toggle the text content and color locally
            button.innerHTML = currentStatus === 'verified' ? '<span class="btn-danger">Unverified</span>' : '<span class="btn-success">Verified</span>';
            currentStatus === 'verified' ? button.setAttribute('data-current-status', 'unverified') : button.setAttribute('data-current-status', 'verified');
        });
    });
});
</script>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
