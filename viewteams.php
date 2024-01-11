<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Teams List</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="viewteamsstyle.css">
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
        <li><a href="teams.html" class="nav-link px-2 link-secondary"><i class="fas fa-chalkboard-user"></i>Teams</a></li>
        <li><a href="announcements.html" class="nav-link px-2 link-secondary"><i class="fa-solid fa-scroll"></i>Announce</a></li>
        <li><a href="tasks.html" class="nav-link px-2 link-secondary"><i class="fa-solid fa-list-check"></i>Tasks</a></li>
        <li><a href="workdiary.html" class="nav-link px-2 link-secondary"><i class="fa-solid fa-book"></i>Workdiary</a></li>
      </ul>
    </header>
  </div> 
</div>
<h3 style=" font-size: 3rem;
   color: var(--black);
   padding-bottom: .5rem;
   padding-top: 1rem;margin-left:40px;" class="heading">Teams List</h3>

<div class="box">
    <div class="box1" style="background-color:white;width:100%;margin-left:30px;">
        <table border="1" style="margin-top:30px;margin-bottom:100%;">
            <thead>
                <tr>
                    <th>Team Number</th>
                    <th>Team Members</th>
                </tr>
            </thead>
            <tbody>
            <?php
            ob_start();
            session_start();

            // Check if faculty is logged in
            if (!isset($_SESSION['faculty_username'])) {
                // Redirect to the login page if not logged in
                header("Location: facultylogin.html");
                exit();
            }

            // Retrieve the faculty username from the session
            $facultyUsername = $_SESSION['faculty_username'];

            $conn = mysqli_connect("localhost", "root", "", "mca");
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM teams WHERE faculty_id='$facultyUsername'";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['team_name'] . '</td>';
                    
                    // Split team members by comma and display each member in two columns
                    $teamMembers = explode(',', $row['team_members']);
                // ...
// ...
// ...

echo '<td>';
foreach ($teamMembers as $member) {
    // Retrieve and display the roll number and name of the team member from studentlogin table
    $memberQuery = "SELECT username, name FROM studentlogin WHERE username='$member'";
    $memberResult = mysqli_query($conn, $memberQuery);
    if ($memberRow = mysqli_fetch_assoc($memberResult)) {
        echo '<div class="team-member">';
        echo '<div class="roll-number">' . $memberRow['username'] . '</div>';
        echo '<div class="name">'. $memberRow['name'] . '</div>';
        echo '</div>';
    }
}
echo '</td>';

// ...


// ...

echo '</tr>';
                }
                
            } else {
                echo '<tr><td colspan="2">No teams found.</td></tr>';
            }

            mysqli_close($conn);
            ob_end_flush();
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
