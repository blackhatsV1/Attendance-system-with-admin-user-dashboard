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

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Check if a file is uploaded
if(isset($_FILES['resume_file'])) {
    $resume_file = $_FILES['resume_file'];
    $file_tmp = $resume_file['tmp_name'];
    $file_size = $resume_file['size'];
    $file_type = $resume_file['type']; // MIME type

    // Allowed file types
    $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];

    // Check if the uploaded file type is allowed
    if (in_array($file_type, $allowed_types)) {
        // Read file contents
        $resume_contents = file_get_contents($file_tmp);

        // Update resume file and resume status in the database
        $stmt = $conn->prepare("UPDATE users SET resume_file = ?, resume_status = 'uploaded' WHERE id = ?");
        $stmt->bind_param("bi", $resume_contents, $user_id);

        if ($stmt->execute()) {
            // Success: Display message and redirect
            echo "<script>
                    alert('Resume uploaded and stored in the database successfully!');
                    window.location.href = 'regular_user_dashboard.php';
                  </script>";
        } else {
            // Error: Display error message
            echo "<script>alert('Error uploading and storing resume: " . $conn->error . "');</script>";
        }

        $stmt->close();
    } else {
        // Error: Unsupported file type
        echo "<script>alert('Error: Unsupported file type. Allowed types are .pdf, .doc, .docx, .txt.');
        window.location.href = 'regular_user_dashboard.php';
        </script>";
    }
} else {
    // No file uploaded
    echo "<script>alert('No file uploaded.');
    
    </script>";
    
}

// Close the database connection
$conn->close();
?>
