<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('config.php');

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $username = $_POST["username"];
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = sha1($_POST["password"]);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

 
    $_SESSION['student_username'] = $username;

    $select_query = "SELECT username, password, team_number FROM studentlogin WHERE username = ? LIMIT 1";
  
    $stmt = $conn->prepare($select_query);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            $_SESSION['team_number'] = $row['team_number'];
            // Verify the entered password against the retrieved hashed password
            if ($password == $hashed_password) {
                // Password is correct, login successful
                // Set the team_number session variable
               
                header('location: student.php'); // Redirect to the home page
            } else {
                // Incorrect password
                $message = "Incorrect password";
            }
        } else {
            // Username not found
            $message1 = "Username not found.";
        }
    } else {
        echo "Login failed: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!-- Your HTML code remains unchanged -->
<!DOCTYPE html>
<html lang="en">
<head>
   <!-- ... (your existing head content) ... -->
</head>
<body>
   <!-- ... (your existing body content) ... -->
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Student login</title>
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
        <li><a href="studentlogin.php" class="nav-link px-2 link-secondary"><i class="fas fa-chalkboard-user"></i>Students</a></li>
        <li><a href="facultylogin.html" class="nav-link px-2 link-secondary"><i class="fas fa-chalkboard-user"></i>Faculty</a></li>
        <li><a href="projects.php" class="nav-link px-2 link-secondary"><i class="fas fa-graduation-cap"></i>Projects</a></li>
      </ul>
    </header>
  </div> 
</div>

<section class="form-container">

   <form action="studentlogin.php" method="post" enctype="multipart/form-data">
      <h3>login now</h3>
      <p>UserName <span>*</span></p> <?php if (isset($message1)): ?>
        <p style="color: red;font-size:15px;"><?php echo $message1; ?></p>
    <?php endif; ?>
      <input type="text" name="username" placeholder="enter your username" required maxlength="50" class="box">
      <p>Password <span>*</span></p>
      <input type="password" name="password" placeholder="enter your password" required maxlength="20" class="box">
      <?php if (isset($message)): ?>
        <p style="color: red;font-size:15px;"><?php echo $message; ?></p>
    <?php endif; ?>
      <input type="submit" value="login now" name="submit" class="btn">
      <p style="margin-left: 50px;">Don't have an account?<a href="studentregistration.html" style="text-decoration: underline;margin-left: 10px;cursor: pointer;color:red">Register</a></p>
  
   </form>

</section>















<footer class="footer">

  &copy; copyright @ 2023 <span>PSG</span> | all rights reserved!

</footer>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>














