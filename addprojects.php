<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Faculty</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
   <link rel="stylesheet" href="style.css">
   <style>
    /* Add these styles to your existing styles or style tag */
    .styled-table {
        border-collapse: collapse;
        width: 80%;
        margin: auto;
        border: 2px solid #fff;
        box-sizing: border-box;
        overflow-x: auto;
    }

    .styled-table th, .styled-table td {
        border: 2px solid #fff;
        padding: 8px;
        text-align: center;
        font-size: 18px;
    }

    .styled-checkbox {
        font-size: 18px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .styled-checkbox label {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding-left: 30px; /* Adjusted to center the checkbox */
        cursor: pointer;
    }

    .styled-checkbox input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    /* Updated styles for radio button */
    .styled-checkbox .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #fff; /* Set the background color to white */
        border: 1px solid #888;
        border-radius: 50%; /* Use border-radius to create a circle */
    }

    .styled-checkbox:hover .checkmark {
        background-color: #ccc;
    }

    .styled-checkbox input:checked ~ .checkmark {
        background-color: var(--main-color);
    }

    .styled-checkbox .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .styled-checkbox input:checked ~ .checkmark:after {
        display: block;
    }

    .styled-checkbox .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }
</style>
</head>
<body>
    <div class="parent">
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
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

    <section class="form-container">
        <form action="addprojectsprocess.php" method="post" enctype="multipart/form-data">
            <h3>Add Project</h3>
            
          
<select id="yearPicker" name="yearpicker" class="box" onchange="getJoinedStudents()" required>
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
        <div id="joinedStudents"></div>
        

<!-- Hidden input to store selected student's ID -->
<input type="text" name="selectedStudent" id="selectedStudent" value="" class="box" placeholder="Student Name">
        
        
            <input type="text" name="title" placeholder="Project Title" class="box">
            <p>Project Image</p>
            <input type="file" name="image_path" accept="image/*" required class="box">
            <p>Documentation</p>
            <input type="file" name="documentation_link" accept=".pdf, .docx" class="box" required>

            <input type="submit" value="Add Project" name="submit" class="btn">
        </form>
    </section>

   

    <script>
        // Initialize the date picker
        $(document).ready(function(){
            $('#datepicker').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years",
                autoclose: true
            });
        });
        // script.js
function getJoinedStudents() {
    var yearPicker = document.getElementById("yearPicker");
    var selectedYear = yearPicker.value;

    // Send a request to the server to get joined students for the selected year
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("joinedStudents").innerHTML = xhr.responseText;
        }
    };

    xhr.open("GET", "get_students.php?year=" + selectedYear, true);
    xhr.send();
}
function displaySelectedStudentName(studentUsername, studentName) {
    // Update the content of the element with the selected student's name
   

    
    document.getElementById("selectedStudent").value = studentName;
}



    </script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-HJov5RR5l1co1xnvdkzKOw5PK2e9j/WWIFHujHh3Fh6ISs2WWxBGng5KCtW5/wr" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>
</html>
