<?php
// Change these to your actual database credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "social_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // SQL query to fetch user from database
    $sql = "SELECT * FROM user_info WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify hashed password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, store user information in session variables
            session_start();
            $_SESSION['user_id'] = $row['ID']; // Assuming 'user_id' is the unique identifier
            $_SESSION['first_name'] = $row['firstname'];
            $_SESSION['last_name'] = $row['lastname'];

            // Redirect to homepage or any other page
            header("Location: homepage.php");
            exit();
        } else {
            // Password is incorrect
            echo "<p style='color: red;'>Invalid username or password.</p>";
        }
    } else {
        // User not found
        echo "<p style='color: red;'>Invalid username or password.</p>";
    }
}

$conn->close();
?>

