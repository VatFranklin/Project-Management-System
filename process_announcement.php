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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle the form submission

    // Retrieve data from the POST request
    $selectedTeamId = $_POST['selectedTeamId'];
    $announcementContent = $_POST['announcementContent'];
    $uploadedFile = $_FILES['uploadedFile'];
    $fileName = $uploadedFile['name'];
    $tmpFilePath = $uploadedFile['tmp_name'];

    // Database connection details
    $conn = new mysqli("localhost", "root", "", "mca");

    // Prepare and execute the SQL query to insert data into the announcements table
    $stmt = $conn->prepare("INSERT INTO announcements (selected_team, announcement_content, file_name) VALUES (?, ?, ?)");

    $stmt->bind_param("sss", $selectedTeamId, $announcementContent, $fileName);

    if ($stmt->execute()) {
        // Move the uploaded file to a desired directory
        $uploadDirectory = "uploaded_files/";
        $fileDestination = $uploadDirectory . $fileName;
        move_uploaded_file($tmpFilePath, $fileDestination);

        echo "Announcement and file stored successfully.";

        // Fetch team members' details
        $teamMembersQuery = "SELECT team_members FROM teams WHERE team_name = ?";
        $teamMembersStmt = $conn->prepare($teamMembersQuery);
        $teamMembersStmt->bind_param("s", $selectedTeamId);
        $teamMembersStmt->execute();
        $teamMembersResult = $teamMembersStmt->get_result();
        
        if ($teamMembersResult->num_rows > 0) {
            $teamMembersRow = $teamMembersResult->fetch_assoc();
            $teamMembers = explode(",", $teamMembersRow['team_members']);

            // Notify students by email
            $subject = "New Announcement for Team $selectedTeamId";
            $body = "Dear student,\n\nA new announcement has been posted!!\n\n";
            $body .= "Announcement Content: $announcementContent\n\n";
// Retrieve the faculty email from facultylogin database
$facultyEmailQuery = "SELECT email FROM facultylogin WHERE username = ?";
$facultyEmailStmt = $conn->prepare($facultyEmailQuery);
$facultyEmailStmt->bind_param("s", $facultyUsername);
$facultyEmailStmt->execute();
$facultyEmailResult = $facultyEmailStmt->get_result();

if ($facultyEmailResult->num_rows > 0) {
    $facultyEmailRow = $facultyEmailResult->fetch_assoc();
    $facultyEmail = $facultyEmailRow['email'];
} else {
    // Handle the error if the query fails or if the faculty email is not found
    echo "Error fetching faculty email.";
    exit();
}

            foreach ($teamMembers as $student_id) {
                // Get student email from studentlogin database
                $studentEmailQuery = "SELECT email FROM studentlogin WHERE username = ?";
                $studentEmailStmt = $conn->prepare($studentEmailQuery);
                $studentEmailStmt->bind_param("s", $student_id);
                $studentEmailStmt->execute();
                $studentEmailResult = $studentEmailStmt->get_result();

                if ($studentEmailResult->num_rows > 0) {
                    $studentEmailRow = $studentEmailResult->fetch_assoc();
                    $studentEmail = $studentEmailRow['email'];

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
                        $mail->setFrom($facultyEmail, 'ProjEase');
                        $mail->addAddress($studentEmail); // Send to each student individually

                        // Content
                        $mail->isHTML(false);
                        $mail->Subject = $subject;
                        $mail->Body    = $body;
                        $mail->addAttachment($fileDestination, $fileName);

                        $mail->send();
                    } catch (Exception $e) {
                        echo "Mail not sent. Check your internet connection.";
                    }
                }
            }
        }
    } else {
        echo "Error storing announcement: " . $stmt->error;
    }

    // Close connections
    $stmt->close();
    $conn->close();
} else {
    // Redirect to an error page or handle the case where the request method is not POST
    header("Location: /error.php");
    exit();
}
?>
