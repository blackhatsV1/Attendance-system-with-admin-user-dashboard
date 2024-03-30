<?php
// Start session
session_start();

// Check if the user is already logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit;
}
// Include your database connection code here
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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the delete button is clicked
    if (isset($_POST['delete'])) {
        // Retrieve the selected user ID
        $user_id = $_POST['user_id'];

        // Check if a user is selected
        if (!empty($user_id)) {
            // Delete the user from the database
            $sql = "DELETE FROM users WHERE id = $user_id";
            if ($conn->query($sql) === TRUE) {
                // Success: Display pop-up message and refresh the page
                echo "<script>alert('User deleted successfully!');</script>";
                echo "<script>window.location.href = 'admin_dashboard.php';</script>";
                exit();
            } else {
                // Error: Display pop-up message
                echo "<script>alert('Error deleting user: " . $conn->error . "');</script>";
            }
        } else {
            // No user selected, display error message
            echo "<script>alert('Please select a user to delete.');</script>";
        }
    }
}

/// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select_user'])) {
    // Check if a user is selected
    if (!empty($_POST['user_id'])) {
        $selected_user_id = $_POST['user_id'];

        // Fetch user data from the database
        $sql = "SELECT first_name, last_name, email FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $selected_user_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($first_name, $last_name, $email);
        $stmt->fetch();
        $stmt->close();
    }
}

// Check if the update button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    // Retrieve the selected user ID and updated details
    $user_id = $_POST['user_id'];
    $new_first_name = $_POST['new_first_name'];
    $new_last_name = $_POST['new_last_name'];
    $new_email = $_POST['new_email'];
    $new_password = $_POST['new_password'];

    // Hash the new password if it's not empty
    if (!empty($new_password)) {
        $hashed_password = hash('sha256', $new_password);
    } else {
        // If the password field is empty, retain the existing password
        $sql_password = "SELECT password FROM users WHERE id = ?";
        $stmt_password = $conn->prepare($sql_password);
        $stmt_password->bind_param("i", $user_id);
        $stmt_password->execute();
        $stmt_password->store_result();
        $stmt_password->bind_result($existing_password);
        $stmt_password->fetch();
        $hashed_password = $existing_password;
        $stmt_password->close();
    }

    // Prepare the SQL query to update user details
    $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $new_first_name, $new_last_name, $new_email, $hashed_password, $user_id);

    if ($stmt->execute()) {
        // Success: Display pop-up message and refresh the page
        echo "<script>alert('User details updated successfully!');</script>";
        echo "<script>window.location.href = 'admin_dashboard.php';</script>";
        exit();
    } else {
        // Error: Display pop-up message
        echo "<script>alert('Error updating user details: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <link rel="icon" href="images/peakpx7.jpg">


</head>

<body>
    <header>
        <h1>Administrator Dashboard</h1>
        <!-- You can add any welcome message or admin name here -->
    </header>
    <nav>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#delete_user">Delete User</a></li>
            <li><a href="#update_user">Update User</a></li>
            <li><a href="#view_resume_status">Resume Status</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    <main>
        <section id="home">
            <h2>All User Information</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch user data from the database
                    $sql = "SELECT * FROM users";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['first_name'] . "</td>";
                            echo "<td>" . $row['last_name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>";
                            // Add a form for each user with a hidden input for user ID
                            echo "<form method='post' action='#view_attendance'>";
                            echo "<input type='hidden' name='user_id' value='" . $row['id'] . "'>";
                            echo "<button type='submit' class='link-button' name='view_attendance'>View Attendance Records</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section id="update_user">
            <!-- Display update user form here -->
            <h2>Update A User</h2>
            <form method="post">
                <label for="user_id">Select User:</label>
                <select name="user_id" id="user_id">
                    <option value="">Select User</option>
                    <?php
                    // Fetch user data from the database
                    $sql = "SELECT id, first_name, last_name, email FROM users";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . " (" . $row['email'] . ")</option>";
                        }
                    }
                    ?>
                </select>
                <button type="submit" name="select_user">Show Details</button>
            </form>

            <?php if (isset($selected_user_id)) : ?>
                <form method="post">
                    <input type="hidden" name="user_id" value="<?php echo $selected_user_id; ?>">
                    <label for="new_first_name">New First Name:</label>
                    <input type="text" name="new_first_name" id="new_first_name" value="<?php echo $first_name; ?>">
                    <label for="new_last_name">New Last Name:</label>
                    <input type="text" name="new_last_name" id="new_last_name" value="<?php echo $last_name; ?>">
                    <label for="new_email">New Email:</label>
                    <input type="email" name="new_email" id="new_email" value="<?php echo $email; ?>">
                    <label for="new_password">New Password:</label>
                    <input type="password" name="new_password" id="new_password" value="">
                    <button type="submit" name="update_user">Update User Details</button>
                </form>
            <?php endif; ?>
        </section>

        <section id="view_attendance">
            <h2>Attendance</h2>
            <!-- Display attendance table here -->
            <?php if (isset($_POST['view_attendance'])) : ?>
                <?php
                // Check if a user is selected
                if (isset($_POST['user_id'])) {
                    $selected_user_id = $_POST['user_id'];

                    // Fetch user's name
                    $stmt_user = $conn->prepare("SELECT first_name, email FROM users WHERE id = ?");
                    $stmt_user->bind_param("i", $selected_user_id);
                    $stmt_user->execute();
                    $result_user = $stmt_user->get_result();

                    if ($result_user->num_rows > 0) {
                        $row_user = $result_user->fetch_assoc();
                        $user_name = $row_user['first_name'];
                        $email = $row_user['email']; // Fetch the email field // Fetch the email field

                        // Fetch attendance records for the selected user
                        $sql = "SELECT punch_date, punch_in, punch_out, status FROM attendance WHERE user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $selected_user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            // Display attendance table
                            echo "<p><b>Attendance History:</b> {$user_name} ({$email})</p> <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Punch In Time</th>
                    <th>Punch Out Time</th>
                    <th>Total Time</th>
                    <th>Status</th>
                    <th>Total Time Worked</th>
                </tr>
            </thead>
            <tbody>";

                            $total_time = 0;
                            while ($row = $result->fetch_assoc()) {
                                // Calculate total time for each record
                                $punch_in = strtotime($row['punch_in']);
                                $punch_out = strtotime($row['punch_out']);
                                $time_diff = $punch_out - $punch_in;
                                $total_time += $time_diff;
                                // Convert total time to HH:MM:SS format
                                $total_time_formatted = gmdate("H:i:s", $total_time);

                                echo "<tr>
                <td>{$row['punch_date']}</td>
                <td>{$row['punch_in']}</td>
                <td>{$row['punch_out']}</td>
                <td>" . gmdate("H:i:s", $time_diff) . "</td>
                <td>{$row['status']}</td>
                <td>{$total_time_formatted}</td>
            </tr>";
                            }

                            
                
                            echo "</tbody></table>";
                        } else {
                            // No attendance records found for the selected user
                            echo "<b>No attendance records found for:</b> {$user_name} ({$email}).";
                        }

                        $stmt->close();
                    } else {
                        // User not found
                        echo "User not found.";
                    }

                    $stmt_user->close();
                } else {
                    // Prompt user to select a user first
                    echo "Please select a user to view attendance.";
                }
                ?>
            <?php endif; ?>

        </section>

        <section id="view_resume_status">
            <h2>Resume Status</h2>
            <!-- Display resume status table here -->
            <?php
            // Fetch resume status for all users
            $sql = "SELECT first_name, last_name, resume_status FROM users";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Display resume status table
                echo "<table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Resume Status</th>
                    </tr>
                </thead>
                <tbody>";

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>{$row['first_name']} {$row['last_name']}</td>
                    <td>{$row['resume_status']}</td>
                  </tr>";
                }

                echo "</tbody></table>";
            } else {
                // No users found
                echo "No users found.";
            }
            ?>
        </section>

        <section id="delete_user">
            <!-- Add delete user form here -->
            <h2>Delete User</h2>
            <form method="post">
                <label for="user_id">Select User for Deletion:</label>
                <select name="user_id" id="user_id">
                    <option value="">Select User to Delete</option>
                    <?php
                    // Include your database connection code here
                    // Fetch user data from the database
                    $sql = "SELECT id, first_name, last_name FROM users";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <button type="submit" name="delete">Delete User</button>
            </form>
        </section>
    </main>
</body>

</html>