<?php

session_start();

// Check kung ang user is already logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "jayrold";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Close the database connection
//$conn->close();

// Retrieve user's ID from session
$user_id = $_SESSION['user_id'];

// Fetch user's email from the database using their ID
$sql = "SELECT * FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // User found, retrieve user's email
    $row = $result->fetch_assoc();
    $user_email = $row['email']; // get user's email
    $user_first_name = $row['first_name'];
    $resume_status = $row['resume_status'];
    
} else {
    //put code here if username not found pwde leave blank
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance User- <?php echo $user_first_name; ?> </title>
    <link rel="stylesheet" href="css/regular_user_dashboard.css"> 
    <link rel="icon" href="images/peakpx7.jpg">
</head>

<body>
    <header>
        <h1>Attendance System</h1>
        <br>
        <p>Hello, <b><i><?php echo $user_first_name; ?></i></b> ! Welcome to your dashboard.</p> <!-- Display user's first name -->
    </header>
    <nav>
        <ul>
            <li><a class="home" href="#home">Home</a></li>
            <li><a href="logout.php">Logout</a></li> <!-- Link to logout script -->
        </ul>
    </nav>
    <main>
        <h2>Regular User Dashboard</h2>
        <?php
        // Display success or error messages if they exist in URL parameters
        if (isset($_GET['success'])) {
            echo "<div class='success-message'>";
            switch ($_GET['success']) {
                case 'punch_in':
                    echo "Punch-in successful.";
                    break;
                case 'punch_out':
                    echo "Punch-out successful.";
                    break;
            }
            echo "</div>";
        }
        if (isset($_GET['error'])) {
            echo "<div class='error-message'>";
            switch ($_GET['error']) {
                case 'punched_in':
                    echo "You have already punched in for today.";
                    break;
                case 'punched_out':
                    echo "You have already punched out for today.";
                    break;
                case 'punch_in_error':
                    echo "An error occurred while punching in.";
                    break;
                case 'punch_out_error':
                    echo "An error occurred while punching out.";
                    break;
            }
            echo "</div>";
        }
        ?>
        <section id="home">
            <h3>Attendance Records</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Punch In Time</th>
                        <th>Punch Out Time</th>
                        <th>Total Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetch and display attendance records from the database -->
                    <?php

                    // Function to calculate total time worked
                    function calculateTotalTime($punch_in, $punch_out)
                    {
                        // Convert punch-in and punch-out times to Unix timestamps
                        $punch_in_time = strtotime($punch_in);
                        $punch_out_time = strtotime($punch_out);

                        // Calculate the difference in seconds
                        $time_diff = $punch_out_time - $punch_in_time;

                        // Calculate hours and minutes
                        $hours = floor($time_diff / 3600);
                        $minutes = floor(($time_diff % 3600) / 60);

                        // Format the total time
                        $total_time = sprintf("%02d:%02d", $hours, $minutes);

                        return $total_time;
                    }

                    // Query to fetch attendance records for the logged-in user
                    $sql = "SELECT * FROM attendance WHERE user_id = '$user_id'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['punch_date'] . "</td>";
                            echo "<td>" . $row['punch_in'] . "</td>";
                            echo "<td>" . $row['punch_out'] . "</td>";
                            echo "<td>" . calculateTotalTime($row['punch_in'], $row['punch_out']) . "</td>"; // Function to calculate total time
                            echo "<td>" . $row['status'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No attendance records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="punch-buttons">
                <form action="punch_in.php" method="post">
                    <button type="submit" name="punch_in">Punch In</button>
                </form>
                <form action="punch_out.php" method="post">
                    <button type="submit" name="punch_out" class="punch-out-button" >Punch Out</button>
                </form>
            </div>

        </section>
        <section id="upload_resume">
            <!-- Upload resume section -->
            <h3>Upload Resume</h3>
            <h3>Resume Status: <span><?php echo $resume_status; ?> </span></h3><!-- Display resume status -->
            <form action="upload_resume.php" method="post" enctype="multipart/form-data">
                <input type="file" name="resume_file" required>
                <button type="submit" name="submit">Upload</button>
            </form>
        </section>
    </main>
</body>

</html>