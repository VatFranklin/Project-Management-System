
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
   <title>Home</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="style.css">
   <style>
    .form-container {
       display: none;
       position: fixed;
       top: 50%; /* Adjusted top position */
       left: 50%;
       transform: translate(-50%, -50%);
       background: white;
       padding: 20px;
       box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
       opacity: 0;
       transition: opacity 0.3s ease-in-out;
       width: 75%;
       max-height: 80vh; /* Added max-height */
       overflow-y: auto; /* Added overflow-y for scrolling if needed */
    }

    .cancel-btn {
        background-color: rgb(251, 73, 66);
        color: #fff;
        border: none;
        padding: 8px 16px;
        cursor: pointer;
        margin-right: 10px;
    }
    .box-full-width {
        width: 100%;
    }
    .btn-container {
        margin-top: 10px;
        display: flex;
    }

    .btn {
        width: 15%;
        margin-right: 10px; 
    }
    .save-btn{
      background-color: rgb(5, 207, 120);
    }
    .box1 {
      resize: vertical;
      min-height: 120px; 
    }
    .form-container {
    /* ... your existing styles ... */
    scrollbar-width: thin; /* For Firefox */
    scrollbar-color: #ccc transparent; /* For Firefox */
}

.form-container::-webkit-scrollbar {
    width: 6px; /* Width of the scrollbar */
}

.form-container::-webkit-scrollbar-thumb {
    background-color: #ccc; /* Color of the thumb */
}

.form-container::-webkit-scrollbar-track {
    background-color: transparent; /* Color of the track */
}
table {
          border-collapse: collapse;
          width: 100%;
          margin: auto;
          border: 2px solid #8e44ad;
          box-sizing: border-box;
          overflow-x: auto;
      }
      th{
        background-color:#8e44ad;
        color:#fff;
    
      }
      th, td {
          border: 2px solid #8e44ad;
          padding: 8px;
          text-align: center;
          font-size: 18px;
      }
      
    .entry-container {
        border: 2px solid #8e44ad;
        padding: 10px;
        margin: 10px;
        background-color: #f8f9fa;
    }

    .entry-date {
        font-size: 18px;
        color: #8e44ad;
    }

    .entry-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
    }

    .entry-description {
        font-size: 16px;
        color: #555;
    }

    .entry-attachments {
        margin-top: 10px;
    }

    .entry-comments {
        margin-top: 10px;
        font-style: italic;
        color: #777;
    }

    .no-entries {
        margin: 20px;
        font-size: 18px;
        color: #8e44ad;
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

                <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 navbar" id="myNavbar">
                    <li><a href="home.php" class="nav-link px-2 link-secondary"><i class="fas fa-home"></i>Home</a></li>
                    <li><a href="viewannouncements.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-scroll"></i>Announcements</a></li>
                    <li><a href="viewtasks.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-list-check"></i>Tasks</a></li>
                    <li><a href="studentworkdiary.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-book"></i>Workdiary</a></li>
                </ul>
            </header>
        </div> 
    </div>

    <div>
        <a href="javascript:void(0);" class="inline-btn" style="width: 450px; margin-right: 30px; float: right;" id="add-new-button" onclick="toggleRegistrationForm()">Add New <i class="fa-solid fa-note-sticky"></i></a>
    </div>

    <section class="form-container" id="registration-form">
        <h1 class="heading">Work Entry</h1>
        <form name="registrationForm" style="width:100%;" action="newentry.php" method="post" enctype="multipart/form-data">
        
            <input type="text" name="title" placeholder="TITLE.." required maxlength="200" class="box box-full-width">
            <textarea name="description" placeholder="Type..." required maxlength="500" class="box box1" ></textarea>
            <p>Attachments</p>
            <input type="file" name="image" accept=".pdf, .doc, .docx, image/*"  class="box" style="max-width: 30%;">
            <div class="btn-container">
                <input type="submit" value="Save" name="submit" class="btn save-btn">
                <button class="btn cancel-btn" onclick="cancelForm()">Cancel</button>
            </div>
        </form>
    </section>
  
    <table border="1" style="margin-top: 90px; margin-bottom: 100%;">
        <thead>
            <tr>
               
                <th>Date</th>
                <th>Title</th>
                <th>Description</th>
                <th>Attachments</th>
                <th>Comments</th>
                <th>Verification</th>
            </tr>
        </thead>
        <tbody>
<?php
// Debugging statement

// Fetch entries based on team_number
$team_number = isset($_SESSION['team_number']) ? $_SESSION['team_number'] : null;

if ($team_number) {
    $sql = "SELECT date,title,description,attachments,comments,verification FROM entries WHERE team_number = '$team_number' order by date desc";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['date'] . '</td>';
                echo '<td>' . $row['title'] . '</td>';
                echo '<td>' . $row['description'] . '</td>';
                echo '<td>';
?>
                <a href="<?= $row['attachments']; ?>" class="inline-btn">Files</a>
                <?php
                echo '</td>';
                echo '<td>' . $row['comments'] . '</td>'; 
                echo '<td>' . $row['verification'] . '</td>'; 
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4">No entries found</td></tr>';
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} 

mysqli_close($conn);
?>

        </tbody>
    </table>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
      function toggleRegistrationForm() {
          var registrationForm = document.getElementById('registration-form');
          var addNewButton = document.getElementById('add-new-button');

          if (registrationForm.style.opacity === '0') {
              addNewButton.style.display = 'none';
              registrationForm.style.display = 'block';
              setTimeout(function () {
                  registrationForm.style.opacity = '1';
              }, 50);
          } else {
              addNewButton.style.display = 'block';
              registrationForm.style.opacity = '0';
              setTimeout(function () {
                  registrationForm.style.display = 'none';
              }, 300);
          }
      }

      function cancelForm() {
          var registrationForm = document.getElementById('registration-form');
          var addNewButton = document.getElementById('add-new-button');
          
          addNewButton.style.display = 'block';
          registrationForm.style.opacity = '0';
          setTimeout(function () {
              registrationForm.style.display = 'none';
          }, 300);
      }

      // Dynamically calculate and set the top position of form-container
      window.onload = function() {
          var navbarHeight = document.getElementById('myNavbar').offsetHeight;
          document.querySelector('.form-container').style.top = `calc(50% + ${navbarHeight}px)`;
      }
      
    // Fetch team_number from the studentlogin table and set it in the form
   

    // Other existing function

    </script>
</body>
</html>
