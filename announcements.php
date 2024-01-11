<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Faculty</title>
   <script async defer src="https://apis.google.com/js/api.js"></script>
   <script async defer src="https://accounts.google.com/gsi/client"></script>
   
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="style.css">
   <style>
         body {
         margin-bottom: 70px;
      }

      .footer {
         position: fixed;
         bottom: 0;
         width: 100%;
         background-color: #f8f9fa;
         text-align: center;
         padding: 20px;
      }

      .announcement-container {
         background-color: #fff;
         padding: 20px;
         border-radius: 8px;
         box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
         max-width: 400px;
         width: 100%;
         text-align: center;
      }

      .announcement-header {
         margin-bottom: 10px;
      }

      .announcement-title {
         font-size: 18px;
         font-weight: bold;
      }

      .announcement-content {
         border: 1px solid #ddd;
         min-height: 190px;
         padding: 10px;
         margin-bottom: 10px;
         text-align: left;
         font-size:16px;
      }

      .announcement-toolbar button {
         background-color: #8e44ad;
         color: #fff;
         padding: 8px;
         border: none;
         border-radius: 5px;
         margin-right: 5px;
         cursor: pointer;
      }

      button {
         background-color: #8e44ad;
         color: #fff;
         padding: 10px;
         border: none;
         border-radius: 5px;
         cursor: pointer;
      }

      button:hover {
         background-color: #6c3483;
      }
      .custom-select {
        position: relative;
        font-family: 'Arial', sans-serif;
      }

      .custom-select select {
        display: none;
      }

      .select-selected {
        background-color: #8e44ad;
        color: #ffffff;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
      }

      .select-selected:after {
        position: absolute;
        content: "";
        top: 50%;
        right: 15px;
        margin-top: -4px;
        border-width: 6px;
        border-style: solid;
        border-color: transparent transparent transparent transparent;
      }

      .select-selected.select-arrow-active:after {
        border-color: transparent transparent #ffffff transparent;
        top: 7px;
      }

      .select-items {
        position: absolute;
        background-color: #8e44ad;
        color: #ffffff;
        border-radius: 5px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        max-height: 200px;
        overflow-y: auto;
        z-index: 1;
      }

      .select-items div,
      .select-selected {
        padding: 12px;
        border-bottom: 1px solid #ffffff;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.3s;
      }

      .select-items div:hover,
      .same-as-selected {
        background-color: #ffffff;
        color: #8e44ad;
      }

      .custom-select-container label {
        font-size: 18px;
        margin-right: 10px;
        color: #8e44ad;
      }

      .custom-select {
        width: 200px;
      }

      .file-input-label {
        display: inline-block;
        background-color: #8e44ad;
        color: #fff;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
      }

      .file-input {
        position: absolute;
        left: -9999px;
      }

      .file-input-label:hover {
        background-color: #6c3483;
      }
   </style>
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
              <li><a href="announcements.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-scroll"></i>Announce</a></li>
              <li><a href="tasks.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-list-check"></i>Tasks</a></li>
              <li><a href="facultyworkdiary.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-book"></i>Workdiary</a></li>
            </ul>
          </header>
      </div> 
    </div>

</div>
<div class="container mt-4">
      <div class="row">
         <!-- Left Column: Announce something to your class -->
         
         <div class="col-md-6">
            <div class="announcement-container">
               <form id="announcementForm" action="process_announcement.php" method="post" enctype="multipart/form-data">
                  <div class="announcement-header">
                     <div class="row">
                        <div class="announcement-title">Select Team</div>
                        

      <div style="margin-left:23px;margin-top:5px;">
      <select id="teamSelect" class="form-select mb-3" style="background-color: #8e44ad;color:#fff;font-size:14px; width: 300px;height:40px; margin-left: 10px;" onchange="updateTextbox()">
      
<?php
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
// Check if the faculty is logged in
if (!isset($_SESSION['faculty_username'])) {
    // Redirect to the faculty login page or handle the case where the faculty is not logged in
    header('Location: facultylogin.html');
    exit();
}

// Retrieve faculty ID from the session
$faculty_id = $_SESSION['faculty_username'];

// ... (rest of your HTML and styles)

// Modify the SQL query to fetch teams based on the faculty ID
$sql = "SELECT team_name, faculty_id FROM teams WHERE faculty_id = '$faculty_id'";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $faculty_id); // Assuming faculty_id is an integer

if ($stmt->execute()) {
    $result = $stmt->get_result();

    // Add options for each team
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['team_name']}'>Team {$row['team_name']}</option>";
        }
    }
} else {
    echo "Query failed: " . $stmt->error;
}

// Close the database connection
$stmt->close();
$conn->close();
?>
 
   
        
                               <option value="all">All Teams</option>
                           </select>
                             
         
                              </div>
                     </div>
                  </div>
                  <div class="announcement-content" id="announcementContent" contenteditable="true"></div>

                  <div class="announcement-toolbar" id="announcementToolbar">
                     <button type="button" onclick="applyFormatting('bold')"><i class="fas fa-bold"></i></button>
                     <button type="button" onclick="applyFormatting('italic')"><i class="fas fa-italic"></i></button>
                     <button type="button" onclick="applyFormatting('underline')"><i class="fas fa-underline"></i></button>
                     <button type="button" onclick="applyFormatting('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
                     <button type="button" onclick="removeFormatting()"><i class="fas fa-eraser"></i></button>
                  </div>
                  <hr>
                  <div>
                  <input type="hidden" id="announcementContentInput" name="announcementContent">


                  <input type="hidden" id="selectedTeamIdInput" name="selectedTeamId" >

                   
                    
<input type="file" name="uploadedFile" id="fileInput" class="file-input-label" >
                     <button type="button">
                     Cancel
                     </button>
                  <button type="submit" >
                     Post</button>
                     
                  </div>
               </form>
            </div>
         </div>
         <!-- Right Column: Posted Announcements -->
         <div class="col-md-6">
    <div id="postedAnnouncementsContainer">
    <?php
    // Assuming you have a connection to the database already
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

    // Check if the faculty is logged in
    if (!isset($_SESSION['faculty_username'])) {
        // Redirect to the faculty login page or handle the case where the faculty is not logged in
        header('Location: facultylogin.html');
        exit();
    }

    // Retrieve faculty ID from the session
    $faculty_id = $_SESSION['faculty_username'];

    // Assuming there is a 'teams' table with 'faculty_id' and 'team_name' columns
    $sql = "SELECT a.id, a.announcement_content, a.selected_team, a.file_name, a.created_at, a.comments
            FROM announcements a
            JOIN teams t ON a.selected_team = t.team_name
            WHERE t.faculty_id = ? 
            ORDER BY a.created_at DESC";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $faculty_id); // Assuming faculty_id is an integer

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Check if there are any announcements
        if ($result->num_rows > 0) {
            // Display each announcement
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card mb-4">';

                // Assuming 'created_at' is a datetime field in your database
                $formatted_date_time = date('d-m-Y g:i a', strtotime($row['created_at']));

                echo '<div class="card-header d-flex justify-content-between align-items-center">';
                echo '<span>';
                echo '<label style="font-size: 14px;">Team ' . $row['selected_team'] . '</label>';
                echo '</span>';
                echo '<span class="text-muted">' . $formatted_date_time . '</span>';
                echo '</div>';

                echo '<div class="card-body">';
                // Output announcement content (you may need to sanitize it based on your requirements)
                echo '<label style="font-size: 14px ;">' . $row['announcement_content'] . '</label>';
                echo '<h5 style="margin-top:20px;">Attachments: <a href="uploaded_files/' . $row['file_name'] . '">Attachment</a></h5>';

         // Display existing comments
echo '<div class="comments-section mt-auto" style="border: 1px solid #ddd; padding: 10px; margin-top: 10px;">';
if (!empty($row['comments'])) {
    echo '<p><b>Comments..</b></p>';
    
    $commentsArray = explode("\n", $row['comments']); // Split comments into an array based on newline
    foreach ($commentsArray as $comment) {
        $comment = nl2br(htmlspecialchars($comment)); // Convert newlines to HTML line breaks and escape HTML characters
        echo '<p>' . $comment . '</p>';
    }
} else {
    echo '<p>No comments...</p>';
}
echo '</div>';



                // You can add more elements or styling as needed

                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "No announcements found.";
        }
    } else {
        echo "Query failed: " . $stmt->error;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
    ?>

    </div>
</div>

      </div>
   </div>
    <footer class="footer">
        &copy; Copyright @ 2023 <span>PSG</span> | All rights reserved!
    </footer>

    <script>

function updateTextbox() {
      var selectedOption = document.getElementById("teamSelect");
      var selectedText = selectedOption.value; // Get the value without "Team" prefix
      document.getElementById("selectedTeamIdInput").value = selectedText;
    }

    // Call the function once the page loads to set the initial state
    document.addEventListener("DOMContentLoaded", updateTextbox);


 


       function uploadFile() {
        document.getElementById('fileInput').click();
    }

    // Add an event listener to the file input for handling file selection
    document.getElementById('fileInput').addEventListener('change', handleFileSelect);

    function handleFileSelect(event) {
        var file = event.target.files[0];
        if (file) {
            // Handle the file as needed (e.g., upload to the server)
            alert(`Selected File: ${file.name}`);
        }
    }
      
  
    document.getElementById('announcementForm').addEventListener('submit', function() {
    var announcementContent = document.getElementById('announcementContent').innerHTML;
    document.getElementById('announcementContentInput').value = announcementContent;
});

        function applyFormatting(command) {
            document.execCommand(command, false, null);
        }

        function removeFormatting() {
            document.execCommand('removeFormat', false, null);
        }

       





    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Load the Google API client library -->
    <script src="https://apis.google.com/js/api.js"></script>
</body>
</html>
