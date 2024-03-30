<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("location: index.html");
    exit();
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

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Check if the user has already punched in for today
$current_date = date("Y-m-d");
$sql_check_punch_in = "SELECT * FROM attendance WHERE user_id = '$user_id' AND punch_date = '$current_date' AND punch_in IS NOT NULL";
$result_check_punch_in = $conn->query($sql_check_punch_in);

if ($result_check_punch_in->num_rows == 0) {
    // User has not punched in for today
    // Redirect back to the regular user dashboard with an error message
    header("location: regular_user_dashboard.php?error=not_punched_in");
    exit();
}

// Check if the user has already punched out for today
$sql_check_punch_out = "SELECT * FROM attendance WHERE user_id = '$user_id' AND punch_date = '$current_date' AND punch_out IS NOT NULL";
$result_check_punch_out = $conn->query($sql_check_punch_out);

if ($result_check_punch_out->num_rows > 0) {
    // User has already punched out for today
    // Redirect back to the regular user dashboard with an error message
    header("location: regular_user_dashboard.php?error=punched_out");
    exit();
}

// Get server's current time
$current_time = time();

// Set user's time zone (Change 'Asia/Manila' to the user's time zone)
$user_timezone = 'Asia/Manila';

// Create a new DateTime object with server's time zone
$server_datetime = new DateTime();
$server_datetime->setTimestamp($current_time);
$server_datetime->setTimezone(new DateTimeZone('UTC')); // Set server's time zone

// Convert server time to user's time zone
$server_datetime->setTimezone(new DateTimeZone($user_timezone));

// Format punch-out time as 12-hour time with AM/PM
$punch_out_time = $server_datetime->format("Y-m-d h:i:s A");

// Update punch-out time in the database
$sql_update_punch_out = "UPDATE attendance SET punch_out = '$punch_out_time', status = 'present' WHERE user_id = '$user_id' AND punch_date = '$current_date'";
if ($conn->query($sql_update_punch_out) === TRUE) {
    // Punch-out successful
    // Redirect back to the regular user dashboard with a success message
    header("location: regular_user_dashboard.php?success=punch_out");
} else {
    // Error occurred while punching out
    // Redirect back to the regular user dashboard with an error message
    header("location: regular_user_dashboard.php?error=punch_out_error");
}

$conn->close();
?>
