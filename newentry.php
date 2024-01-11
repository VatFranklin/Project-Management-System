<?php
session_start();
require_once("config.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
// Initialize alert message variable
$alertMessage = '';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $title = mysqli_real_escape_string($conn, $_POST["title"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
    $username = $_SESSION["student_username"];

    // Retrieve team_number and faculty_id from studentlogin
    $sql_team_info = "SELECT team_number FROM studentlogin WHERE username = '$username'";
    $result_team_info = mysqli_query($conn, $sql_team_info);

    if ($result_team_info && mysqli_num_rows($result_team_info) > 0) {
        $row_team_info = mysqli_fetch_assoc($result_team_info);
        $team_number = $row_team_info['team_number'];

        // Retrieve faculty_id from teams
        $sql_faculty_id = "SELECT faculty_id FROM teams WHERE team_name = '$team_number'";
        $result_faculty_id = mysqli_query($conn, $sql_faculty_id);

        if ($result_faculty_id && mysqli_num_rows($result_faculty_id) > 0) {
            $row_faculty_id = mysqli_fetch_assoc($result_faculty_id);
            $faculty_id = $row_faculty_id['faculty_id'];
        } else {
            echo 'error retrieving faculty id';
            $errors['faculty_id'] = "Error retrieving faculty_id.";
        }
    } else {
        $errors['team_info'] = "Error retrieving team information.";
        echo 'error in team information';
    }

    $date = date('Y-m-d'); // Assuming you want to store the current date
    $time = date('H:i:s'); // Assuming you want to store the current time

    // Check if file is uploaded
    $attachments = "";
    if ($_FILES["image"]["name"]) {
        $target_dir = "uploaded_files/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $attachments = $target_file;
    }

    // Insert data into the database
    $sql = "INSERT INTO entries (date, time, title, description, attachments, team_number) VALUES ('$date', '$time', '$title', '$description', '$attachments', '$team_number')";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "New entry created successfully";

        // Send email to faculty
      // Send email to faculty
$facultyEmailQuery = "SELECT email FROM facultylogin WHERE username = '$faculty_id'";
$facultyEmailResult = mysqli_query($conn, $facultyEmailQuery);

if ($facultyEmailResult && mysqli_num_rows($facultyEmailResult) > 0) {
    $row_faculty_email = mysqli_fetch_assoc($facultyEmailResult);
    $facultyEmail = $row_faculty_email['email'];

    // Fetch student email
    $studentEmailQuery = "SELECT email FROM studentlogin WHERE username = '$username'";
    $studentEmailResult = mysqli_query($conn, $studentEmailQuery);

    if ($studentEmailResult && mysqli_num_rows($studentEmailResult) > 0) {
        $row_student_email = mysqli_fetch_assoc($studentEmailResult);
        $studentEmail = $row_student_email['email'];

        // Compose email
        $subject = "New Entry Posted by Student";
        $body = "Dear Faculty,\n\nA new entry has been posted by the student with username '$username' in team '$team_number'.\n\nTitle: $title\nDescription: $description\n\nBest regards,\nYour App";

        // Use PHPMailer to send the email
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
            $mail->setFrom($studentEmail, 'Projease');  // Set student's email as "From"
            $mail->addReplyTo($studentEmail, $username);  // Set student's email as "Reply-To"
            $mail->addAddress($facultyEmail);  // Send to faculty

            // Content
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            } catch (Exception $e) {
                $_SESSION['error_message'] = "Mail not sent. Error: {$mail->ErrorInfo}";
            }
        } else {
            $_SESSION['error_message'] = "Error fetching student email.";
        }
    } else {
        $_SESSION['error_message'] = "Error fetching faculty email.";
    }
    header('location: studentworkdiary.php');

} else {
    $alertMessage = "Error: Invalid request method.";
}
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include any necessary meta tags, stylesheets, or other head content -->
    <script>
        // Display the alert message using JavaScript
        window.onload = function() {
            var alertMessage = "<?php echo $alertMessage; ?>";
            if (alertMessage) {
                alert(alertMessage);
             
            }
        };
    </script>
</head>
<body>
    <!-- Include your existing HTML body content -->
</body>
</html>
