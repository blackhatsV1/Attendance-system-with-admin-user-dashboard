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
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$password = $_POST['password'];
$account_type = $_POST['account_type'];

// Hash the password
$hashed_password = hash('sha256', $password); // Use appropriate hashing algorithm

// Check if admin account already exists
if ($account_type === 'admin') {
    $sql = "SELECT * FROM users WHERE account_type = 'admin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Admin account already exists, display pop-up message with OK and Cancel buttons
        echo "<script>
                var confirmAdmin = confirm('Administrator account already exists. Only one admin account is allowed. Please login.');
                if (confirmAdmin) {
                    window.location.href = 'index.html'; // Redirect to login page
                } else {
                    window.location.href = 'signup.html'; // Redirect to signup page
                }
              </script>";
        exit;
    }
}

// Check if email already exists
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Email already exists, display pop-up message with OK and Cancel buttons
    echo "<script>
            var confirmEmail = confirm('Email address already exists. Please use a different email. Please login.');
            if (confirmEmail) {
                window.location.href = 'index.html'; // Redirect to login page
            } else {
                window.location.href = 'signup.html'; // Redirect to signup page
            }
          </script>";
    exit;
}

// Insert new user into database with hashed password
$sql = "INSERT INTO users (first_name, last_name, email, password, account_type) 
        VALUES ('$first_name', '$last_name', '$email', '$hashed_password', '$account_type')";

if ($conn->query($sql) === TRUE) {
    // Signup successful, display pop-up message with OK button to redirect to login page
    echo "<script>alert('Account registered successfully. Please login.'); window.location.href = 'index.html';</script>";
} else {
    // Signup failed, display pop-up message with OK button to redirect to signup page
    echo "<script>alert('Error: " . $sql . "\\n" . $conn->error . "'); window.location.href = 'signup.html';</script>";
}

// Close the database connection
$conn->close();
?>
