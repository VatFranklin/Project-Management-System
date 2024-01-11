<?php
session_start();
require_once("config.php");

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

$errors = array(); // Initialize an array to store validation errors

function validateForm($data)
{
    // Check if the submitted data meets your criteria
    global $errors;

    // Example: Check if password is strong
    $password = $data['password'];
    $strongRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($strongRegex, $password)) {
        $errors['password'] = "Password must be strong. Include uppercase, lowercase, digit, and special character.";
    }

    // Example: Check if username is in the correct format
    $username = $data['username'];
    $usernameRegex = '/^(CT)\d+/';
    if (!preg_match($usernameRegex, $username)) {
        $errors['username'] = "Username must start with 'CT' followed by faculty ID.";
    }
    $email = $data['email'];
    if (isEmailRegistered($email)) {
        $errors['email'] = 'Email is already registered!';
    }

    // Add more validation checks as needed
}
function isEmailRegistered($email)
{
    global $conn;

    $select_email = "SELECT * FROM `facultylogin` WHERE email = ?";
    $sts_email = $conn->prepare($select_email);
    $sts_email->bind_param("s", $email);
    $sts_email->execute();
    $sts_email->store_result(); // Store the result set

    return $sts_email->num_rows > 0;
}

// Initialize variables to hold the form data
$name = $email = $username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $formData = array(
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        // Add more fields as needed
    );

    validateForm($formData);

    if (empty($errors)) {
        // Process form data
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email=$_POST['email'];
        $email=filter_var($email,FILTER_SANITIZE_STRING);
        $username= $_POST['username'];
        $username = filter_var($username, FILTER_SANITIZE_STRING);
        $password = sha1($_POST['password']);
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $cpass = sha1($_POST['c_pass']);
        $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
     
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $ext = pathinfo($image, PATHINFO_EXTENSION);
        $rename = unique_id().'.'.$ext;
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_files/'.$rename;

        // Add more fields as needed

        // Check if the username is already registered
        $select_user = "SELECT * FROM `facultylogin` WHERE username = ?";
        $sts = $conn->prepare($select_user);
        $sts->bind_param("s", $username);
        $sts->execute();
        $sts->store_result(); // Store the result set

        if ($sts->num_rows > 0) {
            $errors['message1'] = 'User already registered!';
        } else {
            // Confirm password
            $cpass = sha1($_POST['c_pass']);
            if ($cpass != $password) {
                $errors['message'] = 'Confirm password not matched!';
            } else {
                // Registration is valid, proceed to save data in the database
                $sql = "INSERT INTO `facultylogin` (name, email, username, password, image) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $name, $email, $username, $cpass, $rename);

                if ($stmt->execute()) {
                    echo '<script>alert("Registration Successful");</script>';
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploaded_files/' . $rename);
                    

                    // Clear form data after successful registration
                    $name = $email = $username = '';
                    header('location: facultylogin.html'); 
                } else {
                    $errors['message'] = "Error: " . $stmt->error;
                }
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="parent">
        <div class="container">
            <header
                class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom ">
                <div class="col-md-3 mb-2 mb-md-0">
                    <a href="home.php" class="logo"><img src="images/logo2.png"></a>
                </div>

                <ul
                    class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0 navbar">
                    <li><a href="home.php"
                            class="nav-link px-2 link-secondary"><i
                                class="fas fa-home"></i>Home</a></li>
                    <li><a href="studentlogin.html"
                            class="nav-link px-2 link-secondary"><i
                                class="fas fa-chalkboard-user"></i>Students</a></li>
                    <li><a href="facultylogin.html"
                            class="nav-link px-2 link-secondary"><i
                                class="fas fa-chalkboard-user"></i>Faculty</a></li>
                    <li><a href="projects.php"
                            class="nav-link px-2 link-secondary"><i
                                class="fas fa-graduation-cap"></i>Projects</a></li>
                </ul>
            </header>
        </div>
    </div>

    <section class="form-container">
        <form action="facultyregistration.php" method="post" enctype="multipart/form-data">
            <h3>Register Now</h3>
         
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="Enter your name" required maxlength="50" class="box">
            <?php if (isset($errors['name'])): ?>
                <p style="color: red; font-size: 15px;"><?php echo $errors['name']; ?></p>
            <?php endif; ?>
 
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="Enter your mail-id" required maxlength="50" class="box">
            <?php if (isset($errors['email'])): ?>
                <p style="color: red; font-size: 15px;"><?php echo $errors['email']; ?></p>
            <?php endif; ?>
  
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Enter your username" required maxlength="50" class="box">
            <?php if (isset($errors['username'])): ?>
                <p style="color: red; font-size: 15px;"><?php echo $errors['username']; ?></p>
            <?php endif; ?>
            <?php if (isset($errors['message1'])): ?>
                <p style="color: red; font-size: 15px;"><?php echo $errors['message1']; ?></p>
            <?php endif; ?>
        
            <input type="password" name="password" placeholder="Enter your password" required maxlength="20"
                class="box">
            <?php if (isset($errors['password'])): ?>
                <p style="color: red; font-size: 15px;"><?php echo $errors['password']; ?></p>
            <?php endif; ?>
         
            <input type="password" name="c_pass" placeholder="Confirm your password" required maxlength="20"
                class="box">
                <?php if (isset($errors['message'])): ?>
                <p style="color: green; font-size: 15px;"><?php echo $errors['message']; ?></p>
            <?php endif; ?>
            <p>Select Profile <span></span></p>
            <input type="file" name="image" accept="image/*" class="box">
            <?php if (isset($errors['message'])): ?>
                <p style="color: green; font-size: 15px;"><?php echo $errors['message']; ?></p>
            <?php endif; ?>
            <input type="submit" value="Register Now" name="submit" class="btn">
        </form>
    </section>

    <footer class="footer">
        &copy; Copyright @ 2023 <span>PSG</span> | All Rights Reserved!
    </footer>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>
