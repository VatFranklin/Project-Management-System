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
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Projects</title>
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
<?php 
$select_projects = $conn->query("SELECT * FROM projects ORDER BY id DESC ");
?>
<section class="projects">

<h1 class="heading">Projects</h1>

<div class="projects-container">

    <?php
    while ($fetch_project = $select_projects->fetch_assoc()) {
        ?>
        <div class="feature_projects">
            <div class="batch">
                <div class="info">
                    <h3><?= $fetch_project['name']; ?></h3>
                    <span><?= $fetch_project['year']; ?></span>
                </div>
            </div>
            <div class="pro_picture">
                <img src="<?= $fetch_project['image_path']; ?>" alt="">
            </div>
            <h3 class="title"><?= $fetch_project['title']; ?></h3>
            <a href="<?= $fetch_project['documentation_link']; ?>" class="inline-btn">Documentation</a>
        </div>
        <?php
    }
    ?>

</div>



</section>

<?php
$conn->close();
?>

<footer class="footer">

  &copy; copyright @ 2023 <span>PSG</span> | all rights reserved!

</footer>
 
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>