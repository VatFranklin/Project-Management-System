<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
session_start();

// Check if faculty is logged in
if (!isset($_SESSION['faculty_username'])) {
    // Redirect to the login page if not logged in
    header("Location: facultylogin.html");
    exit();
}
$conn = mysqli_connect("localhost", "root", "", "mca");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve the faculty username from the session
$facultyUsername = $_SESSION['faculty_username'];
$error_message1 = "";
$error_message2 = "";
// Continue with the rest of your code...
// ...

if (isset($_POST['create_team'])) {
    $team_name = $_POST['team_name'];
    $student_ids = $_POST['student_ids'];


    // Check if selected students are already in a team
    $isStudentInTeam = array();

    foreach ($student_ids as $student_id) {
        $check_team_sql = "SELECT * FROM teams WHERE FIND_IN_SET('$student_id', team_members) > 0";
        $check_team_result = mysqli_query($conn, $check_team_sql);

        $isStudentInTeam[$student_id] = (mysqli_num_rows($check_team_result) > 0);
    }

    // If any selected student is already in a team, display an error message
    foreach ($isStudentInTeam as $student => $inTeam) {
        if ($inTeam) {
            $_SESSION['error_message']="Error: Recheck the student details";
            header("Location: process.php");
            exit(); // Stop execution if there's an error
        }
    }

    // Insert the new team into the teams table
    // Replace 'faculty_username' with the actual column name in your 'teams' table
    $team_members = implode(",", $student_ids);
    $insert_team_sql = "INSERT INTO teams (team_name, team_members, faculty_id) VALUES ('$team_name', '$team_members', '$facultyUsername')";

   

// If the team is created successfully
if (mysqli_query($conn, $insert_team_sql)) {
    $message = "Team created successfully!";

 // Update the is_in_team status for selected students
    foreach ($student_ids as $student_id) {
        $update_student_sql = "UPDATE studentlogin SET is_in_team = 1, team_number = '$team_name' WHERE username = '$student_id'";
        mysqli_query($conn, $update_student_sql);
    }
    // Notify students by email
    $subject = "You have been added to a team";
    $body = "Dear student,\n\nYou have been added to a project team number '$team_name' with the following members:\n\n";
    
    foreach ($student_ids as $student_id) {
        $body .= "- $student_id\n";
    }

    // Get faculty email from facultylogin database
    $faculty_email_query = "SELECT email FROM facultylogin WHERE username = '$facultyUsername'";
    $faculty_email_result = mysqli_query($conn, $faculty_email_query);

    if ($faculty_email_result) {
        $faculty_email_row = mysqli_fetch_assoc($faculty_email_result);
        $faculty_email = $faculty_email_row['email'];
        
        // Send email
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  // Specify your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vatfranklin@gmail.com';  // SMTP username
            $mail->Password   = 'yndyssgwujlgotut';  // SMTP password
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom($faculty_email, 'ProjEase');
            $mail->addReplyTo($faculty_email, 'Faculty Name');
            $student_emails_query = "SELECT email FROM studentlogin WHERE username IN ('" . implode("','", $student_ids) . "')";
            $student_emails_result = mysqli_query($conn, $student_emails_query);
    
            if ($student_emails_result) {
                while ($student_email_row = mysqli_fetch_assoc($student_emails_result)) {
                    $mail->addAddress($student_email_row['email']); // Send to each student individually
                }
            }
          

            // Content
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
        } catch (Exception $e) {
            $_SESSION['error_message'] = "mail not send.check your internet connection.";
        }
    }
} else {
    $_SESSION['error_message'] = "Recheck the team details ";
    header("Location: process.php");
}
}

// ...

mysqli_close($conn);
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
    /* Add the styling code here */

 

    /* ... (rest of the existing styling code) ... */

    /* Additional styling for the checkbox */
    .container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size:30px;
    }

    .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
        border: 1px solid #888;
        border-radius: 4px;
    }

    .container:hover input ~ .checkmark {
        background-color: #ccc;
    }

    .container input:checked ~ .checkmark {
        background-color: var(--main-color);
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .container input:checked ~ .checkmark:after {
        display: block;
    }

    .container .checkmark:after {
        left: 10px;
        top: 4px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }
    .container {
            font-size: 18px;
        }

        .team-name {
            font-size: 24px; /* Adjust the font size for the team name */
            font-family: 'Nunito', sans-serif;
        }

        .styled-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            border: 2px solid #4CAF50; /* Green border color */
            color: #4CAF50; /* Green text color */
            border-radius: 5px;
            background-color: white;
            transition: background-color 0.3s, color 0.3s;
        }

        .styled-button:hover {
            background-color: #4CAF50; /* Green background color */
            color: white; /* White text color */
        }
        .team-name-input {
    font-size: 18px;
    padding: 10px;
    margin-left:20px;
    margin-bottom: 20px; 
    margin-top:10px;/* Adjust the margin as needed */
    width: 35%; /* Make the input box fill the container */
    box-sizing: border-box; 
   /* Include padding and border in the element's total width and height */
      
    border: 1px solid #ccc;
    border-radius: 4px;
}
table {
    border-collapse: collapse;
    width: 80%; /* Adjust the width as needed */
    margin: auto; /* Center the table */
    border: 2px solid #8e44ad; /* Set border color and thickness */
    box-sizing: border-box; /* Include padding and border in the element's total width and height */
    overflow-x: auto; /* Enable horizontal scrolling */
}

th {
    border: 2px solid #8e44ad; /* Set cell border color and thickness */
    padding: 8px;
    text-align: center;
    font-size: 18px;
}
td{
   border: 2px solid #8e44ad; /* Set cell border color and thickness */
    padding: 8px;
    text-align: left;
    font-size: 18px;
    margin-bottom:3px;
}
.box{
    display:flex;
    margin-left:20px;
}
.box {
    display: flex;
    position: relative; /* Set position relative for proper positioning of box2 */
}

.box1 {
    background-color: white;
    width: 60%;
    
    overflow: auto;
    padding-right: 20px; /* Add padding to prevent overlap with box2 */
}

.box2 {
    background-color: white;
    width: 30%;
    padding-left: 50px;
    position: fixed;
    top: 160px;
    right: 10px;
    bottom:0;

    z-index: 1; /* Ensure box2 appears above other elements */
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
        <li><a href="tasks.html" class="nav-link px-2 link-secondary"><i class="fa-solid fa-list-check"></i>Tasks</a></li>
        <li><a href="facultyworkdiary.php" class="nav-link px-2 link-secondary"><i class="fa-solid fa-book"></i>Workdiary</a></li>
      </ul>
    </header>
  </div> 
</div>
 

    
         <h3 style=" font-size: 3rem;
   color: var(--black);
   padding-bottom: .5rem;
   padding-top: 1rem;margin-left:40px;" class="heading">Students List</h3>
      
      <div class="box">
      <div class="box1" style="background-color:white;width:60%;margin-left:30px;">
<form method="post" action="process.php">
    
    <table border="1" style="margin-top:30px;margin-bottom:100%;">
    <thead>
        <tr >
            <th style="height: 25px;">Select</th>
            <th>Roll No</th>
            <th>Name</th>
        </tr>
    </thead>
        <tbody>
            <?php
        
            
            
            // Check for an error message in the session
            if (isset($_SESSION['error_message'])) {
                $error_message = $_SESSION['error_message'];
                unset($_SESSION['error_message']); 
                 // Clear the session variable
            }
            
            // ... (rest of your HTML code)
            
            // Display the error message if it exists
          
            
            
            // Connect to the database and fetch student data
            $conn = mysqli_connect("localhost", "root", "", "mca");
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT username, name FROM studentlogin";
            $result = mysqli_query($conn, $sql);
          
            
            
            if (mysqli_num_rows($result) > 0) {
             
while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td>'; // Center the checkbox in the cell
    
    // Check if the student is already in a team
    /* Retrieve is_in_team value for the student */;
    $studentUsername = $row['username'];
    $check_is_in_team_sql = "SELECT is_in_team FROM studentlogin WHERE username = '$studentUsername'";
    $check_is_in_team_result = mysqli_query($conn, $check_is_in_team_sql);

    if ($check_is_in_team_result) {
        $is_in_team_row = mysqli_fetch_assoc($check_is_in_team_result);
        $isStudentInTeam = $is_in_team_row['is_in_team'];
    } else {
        // Handle the error if the query fails
        $_SESSION['error_message'] = "Error retrieving is_in_team value for student $studentUsername";
        header("Location: process.php");
        exit();
    } /* Retrieve is_in_team value for the student */;
    $isDisabled = ($isStudentInTeam == 1) ? 'disabled' : '';
    
    echo '<label class="container" style="font-size: 16px; margin-bottom: 30px;">';
    echo '<input type="checkbox" name="student_ids[]" value="' . $row['username'] . '" ' . $isDisabled . '>';
    echo '<span class="checkmark"></span>';
    echo '</label>';
    echo '</td>';
    
    // Display username and name
    echo '<td style="font-size: 16px; margin-bottom: 10px;">' . $row['username'] . '</td>';
    echo '<td style="font-size: 16px; margin-bottom: 10px;">' . $row['name'] . '</td>';
    // Display image if URL is present
    
    echo '</tr>';
}

            }
        
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
        </div>
    <div class="box2 fixed-box2" style="background-color:white;width:35%;height: 50%;padding-left:50px;margin-left:20px;">
    <label for="team_name" class="team-name" style="margin-top:80px;">Team Number:</label>
    <div class="box2 fixed-box2" style="background-color:white;width:35%;height: 50%;padding-left:50px;margin-left:20px;">
    <label for="team_name" class="team-name" style="margin-top:80px;">Team Number:</label>
  
    <?php
    // Connect to the database and fetch the next team number
    $conn = mysqli_connect("localhost", "root", "", "mca");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $fetch_next_team_number_sql = "SELECT MAX(team_name) + 1 AS next_team_number FROM teams";
    $fetch_next_team_number_result = mysqli_query($conn, $fetch_next_team_number_sql);

    if ($fetch_next_team_number_result) {
        $row = mysqli_fetch_assoc($fetch_next_team_number_result);
        $nextTeamNumber = $row['next_team_number'];
    } else {
        // Handle the error if the query fails
        $nextTeamNumber = 1; // Default to 1 if there's an error
    }

    echo '<input type="text" name="team_name" class="team-name-input" value="' . $nextTeamNumber . '" required><br>';

    mysqli_close($conn);
    ?>

    
    <input type="submit" name="create_team" class="inline-btn" value="Create Team" style="margin-left:80px;margin-top:30px;">
    
<?php if (isset($message)): ?>
    <p style="color: green; font-size: 15px;margin-top:20px;margin-left:50px;"><?php echo $message; ?></p>
<?php endif; ?>
<?php if (isset($error_message)): ?>
    <p style="color: red; font-size: 15px;margin-top:20px;margin-left:50px;"><?php echo $error_message; ?></p>
<?php endif; ?>

        </form>
      </div>
        </div>
</div>





</body>
</html>




