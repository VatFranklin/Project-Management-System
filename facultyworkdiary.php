
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
   <section class="projects">
   <h1 class="heading">List of Teams</h1>
    </section>
    <?php
$faculty_username = $_SESSION['faculty_username'];
$query = "SELECT team_name FROM teams WHERE faculty_id = '$faculty_username'";
$result = mysqli_query($conn, $query);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="container">';
        echo '<div class="row">';
        
        while ($row = mysqli_fetch_assoc($result)) {
            $team_name = $row['team_name'];

            // Fetch the last submission from entries table
            $query_last_submission = "SELECT MAX(created_at) AS last_submission FROM entries WHERE team_number =  '$team_name'";
            $result_last_submission = mysqli_query($conn, $query_last_submission);

            if ($result_last_submission) {
                $row_last_submission = mysqli_fetch_assoc($result_last_submission);
                $last_submission = $row_last_submission['last_submission'];

                // Format the date and time
                $formatted_date_time = ($last_submission !== null) ? date('d-m-Y g:i a', strtotime($last_submission)) : 'No submissions yet';

                // Display the team card
                echo '<div class="col-md-4">';
                echo '<div class="team-card" onclick="viewWorkDiary(' . $team_name . ')">';
                echo '<h4>' . 'Team ' . $team_name . '</h4>';
                echo '<p>Last Updation: ' . $formatted_date_time . '</p>';
                echo '</div>';
                echo '</div>';
            } else {
                echo "Error fetching last submission: " . mysqli_error($conn);
            }
        }

        echo '</div>';
        echo '</div>';
    } else {
        echo "No teams found for faculty";
    }
} else {
    echo "Error fetching teams: " . mysqli_error($conn);
}

mysqli_close($conn);
?>


    


   <footer class="footer">
      &copy; Copyright @ 2023 <span>PSG</span> | all rights reserved!
      </footer>
      <script>
         function viewWorkDiary(teamName) {
        // Redirect to commentsworkdiary.php with the team information
        window.location.href = 'commentworkdiary.php?team_name=' + encodeURIComponent(teamName);
    }

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
            button.innerHTML = currentStatus === 'verified' ? '<span class="">Unverified</span>' : '<span class="text-success">Verified</span>';
            currentStatus === 'verified' ? button.setAttribute('data-current-status', 'unverified') : button.setAttribute('data-current-status', 'verified');
        });
    });
});
</script>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

