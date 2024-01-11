<?php
session_start();
require_once("config.php");

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $username = $_POST['username'];
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = sha1($_POST['password']);
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $cpass = sha1($_POST['c_pass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $ext = pathinfo($image, PATHINFO_EXTENSION);
    $rename = unique_id() . '.' . $ext;
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_files/' . $rename;

    // Fetch batch details
    $batch_year = isset($_POST['yearpicker']) ? $_POST['yearpicker'] : '';

    // Validate batch details (you can add more validation as needed)
    if (empty($batch_year) || !is_numeric($batch_year)) {
        $message = 'Invalid batch details!';
    } else {
        // Calculate the batch range (e.g., 2018-2021)
        $batch_range = $batch_year . '-' . ($batch_year + 2);

        $select_user = "SELECT * FROM `studentlogin` WHERE username = ?";
        $sts = $conn->prepare($select_user);
        $sts->bind_param("s", $username);
        $sts->execute();
        $sts->store_result(); // Store the result set
        if ($sts->num_rows > 0) {
            $message1 = 'User already registered!';
        } else {
            if ($cpass != $password) {
                $message = 'Confirm password not matched!';
            } else {
                $sql = "INSERT INTO `studentlogin`(name, email, username, password, image, batch) VALUES(?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $name, $email, $username, $cpass, $rename, $batch_range);

                if ($stmt->execute()) {
                    echo "Registration successful.";
                } else {
                    echo "Error: " . $stmt->error;
                }

                move_uploaded_file($image_tmp_name, $image_folder);

                header('location:studentlogin.html');
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
   <title>Student Registration</title>
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
        <li><a href="student.html" class="nav-link px-2 link-secondary"><i class="fas fa-chalkboard-user"></i>Students</a></li>
        <li><a href="faculty.html" class="nav-link px-2 link-secondary"><i class="fas fa-chalkboard-user"></i>Faculty</a></li>
        <li><a href="projects.php" class="nav-link px-2 link-secondary"><i class="fas fa-graduation-cap"></i>Projects</a></li>
      </ul>
    </header>
  </div> 
</div><section class="form-container">

    <form action="studentregistration.php" method="post" enctype="multipart/form-data">
       <h3>register now</h3>
       <select id="yearPicker" name="yearpicker" class="box" required>
         <option value="" disabled selected>Batch</option>
     </select>
     
     <script>
         // Dynamically populate the years using JavaScript
         var yearPicker = document.getElementById('yearPicker');
     
         // Adjust the range as needed
         for (var year = new Date().getFullYear(); year >= 2000; year--) {
             var option = document.createElement('option');
             option.value = year;
             option.text = year;
             yearPicker.add(option);
         }
     </script>
       <input type="text" name="name" placeholder="enter your name" required maxlength="50" class="box">
    
       <input type="email" name="email" placeholder="enter your mail-id" required maxlength="50" class="box">

       <input type="text" name="username" placeholder="enter your username" required maxlength="50" class="box">
      
       <input type="password" name="password" placeholder="enter your password" required maxlength="20" class="box">
      
       <input type="password" name="c_pass" placeholder="confirm your password" required maxlength="20" class="box">
   
       <input type="file" name="image" accept="image/*"  class="box">
       <?php if (isset($message)): ?>
        <p style="color: red;font-size:15px;"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if (isset($message1)): ?>
        <p style="color: red;font-size:15px;"><?php echo $message1; ?></p>
    <?php endif; ?>
       <input type="submit" value="register now" name="submit" class="btn">
    </form>
 
 </section>
 
<footer class="footer">

    &copy; copyright @ 2023 <span>PSG</span> | all rights reserved!
  
  </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
  </html>