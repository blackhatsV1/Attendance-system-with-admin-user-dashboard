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

// Get server's current date
$current_date = date("Y-m-d");

// Check if the user has already punched in for today
$sql_check_punch_in = "SELECT * FROM attendance WHERE user_id = '$user_id' AND punch_date = '$current_date'";
$result_check_punch_in = $conn->query($sql_check_punch_in);

if ($result_check_punch_in->num_rows > 0) {
    // User has already punched in for today
    // Redirect back to the regular user dashboard with an error message
    header("location: regular_user_dashboard.php?error=punched_in");
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

// Format punch-in time as 12-hour time with AM/PM
$punch_in_time = $server_datetime->format("Y-m-d h:i:s A");

// Insert punch-in record into the database
$sql_insert_punch_in = "INSERT INTO attendance (user_id, punch_date, punch_in, status) VALUES ('$user_id', '$current_date', '$punch_in_time', 'pending')";
if ($conn->query($sql_insert_punch_in) === TRUE) {
    // Punch-in successful
    // Redirect back to the regular user dashboard with a success message
    header("location: regular_user_dashboard.php?success=punch_in");
} else {
    // Error occurred while punching in
    // Redirect back to the regular user dashboard with an error message
    header("location: regular_user_dashboard.php?error=punch_in_error");
}

$conn->close();
?>
