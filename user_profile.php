<?php
session_start();

// Fetch user ID from session
$user_id = $_SESSION['user_id'];

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$database = "social_app";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user information
$sql_user = "SELECT * FROM user_info WHERE ID = $user_id";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $row_user = $result_user->fetch_assoc();
    $first_name = $row_user["firstname"];
    $last_name = $row_user["lastname"];
} else {
    // Handle case where user information is not found
    $first_name = '';
    $last_name = '';
}

// Retrieve posts associated with the user
$sql_posts = "SELECT * FROM posts WHERE user_id = $user_id ORDER BY post_id DESC";
$result_posts = $conn->query($sql_posts);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $first_name . ' ' . $last_name; ?>'s Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        h2 {
            margin-bottom: 10px;
        }
        .post {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .post img {
            max-width: 100%;
            display: block;
            margin-top: 10px;
            border-radius: 8px;
        }
        .post-time {
            font-size: 0.9em;
            color: #888;
            
        }
        .navigation {
            margin-top: 20px;
            text-align: center;
        }
        .navigation a {
            color: #007bff;
            text-decoration: none;
            margin-right: 20px;
        }
        .navigation a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="navigation">
            <a href="homepage.php">Go to Homepage</a>
            <a href="logout.php">Logout</a>
        </div>
        
        <h1>Welcome, <?php echo $first_name . ' ' . $last_name; ?></h1>

        <h2>Your Posts:</h2>

        <?php
        if ($result_posts->num_rows > 0) {
            // Output data of each post
            while($row_post = $result_posts->fetch_assoc()) {
                // Format the post time
                $post_time = date("F j, Y, g:i a", strtotime($row_post["created_at"]));

                echo "<div class='post'>";
                echo "<p><strong>Description:</strong> " . $row_post["description"] . "</p>";
                echo "<p class='post-time'><strong>Posted on:</strong> " . $post_time . "</p>";
                if (!empty($row_post["image_path"])) {
                    echo "<img src='" . $row_post["image_path"] . "' alt='Posted Image'>";
                }
                
                echo "</div>";
            }
        } else {
            echo "<p>You haven't posted anything yet.</p>";
        }
        ?>
    </div>
</body>
</html>
