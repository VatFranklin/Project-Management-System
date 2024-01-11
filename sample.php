<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Select Option to Textbox</title>
</head>
<body>

  <label for="teamSelect">Select a team:</label>
  <select id="teamSelect" onchange="updateTextbox()">
    <?php
      // Your database connection code here
      $servername = "localhost";
      $username = "root";
      $password = "";
      $dbname = "mca";

      // Create connection
      $conn = new mysqli($servername, $username, $password, $dbname);

      // Check connection
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      // Fetch team names and IDs from the database
      $sql = "SELECT  team_name FROM teams";
      $result = $conn->query($sql);

      // Add options for each team
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<option value='{$row['team_name']}'>Team {$row['team_name']}</option>";
        }
      }

      // Close the database connection
      $conn->close();
    ?>
  </select>

  <br>

  <label for="displayTextbox">Selected Team:</label>
  <input type="text" id="displayTextbox" readonly>

  <script>
    function updateTextbox() {
      var selectedOption = document.getElementById("teamSelect");
      var selectedText = selectedOption.value; // Get the value without "Team" prefix
      document.getElementById("displayTextbox").value = selectedText;
    }

    // Call the function once the page loads to set the initial state
    document.addEventListener("DOMContentLoaded", updateTextbox);
  </script>

</body>
</html>
