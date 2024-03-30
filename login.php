<?php
// Start session
session_start();

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

// Retrieve form data
$email = $_POST['email'];
$password = $_POST['password'];
$account_type = $_POST['account_type'];

// Hash the password
$hashed_password = hash('sha256', $password); // Use appropriate hashing algorithm

// Check user credentials and account type
$sql = "SELECT * FROM users WHERE email = '$email' AND password = '$hashed_password' AND account_type = '$account_type'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Valid credentials, set session variables
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_first_name'] = $row['first_name'];
    $_SESSION['resume_status'] = $row['resume_status'];
    $_SESSION['email'] = $row['email']; // Fetch resume status

    // Redirect based on account type
    if ($account_type == 'admin') {
        header("location: admin_dashboard.php");
    } else {
        header("location: regular_user_dashboard.php");
    }
} else {
    // Invalid credentials or account type, check if email is registered
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkEmailResult = $conn->query($checkEmailQuery);

    if ($checkEmailResult->num_rows == 0) {
        // Email not registered, display pop-up message with OK and Cancel buttons
        echo "<script>
                var confirmEmail = confirm('Email not registered. Please check your account type or sign up.');
                if (confirmEmail) {
                    window.location.href = 'signup.html'; // Redirect to signup page
                } else {
                    window.location.href = 'index.html'; // Redirect to login page
                }
              </script>";
    } else {
        // Invalid credentials, display pop-up message with OK button to return to login page
        echo "<script>alert('Invalid email, password, or account type.'); window.location.href = 'index.html';</script>";
    }
}

// Close the database connection
$conn->close();
?>
