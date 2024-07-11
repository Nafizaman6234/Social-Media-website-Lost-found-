<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$database = "social_app";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deleting a user
if (isset($_GET['delete_user_id'])) {
    $delete_user_id = $_GET['delete_user_id'];
    
    // Delete user's posts from posts table
    $sql_delete_user_posts = "DELETE FROM posts WHERE user_id = $delete_user_id";
    $conn->query($sql_delete_user_posts);

    // Delete user from user_info table
    $sql_delete_user = "DELETE FROM user_info WHERE ID = $delete_user_id";
    $conn->query($sql_delete_user);

    // Redirect back to admin panel after deletion
    header("Location: admin_panel.php");
    exit;
}

// Handle deleting a post
if (isset($_GET['delete_post_id'])) {
    $delete_post_id = $_GET['delete_post_id'];

    // Delete post from posts table
    $sql_delete_post = "DELETE FROM posts WHERE post_id = $delete_post_id";
    $conn->query($sql_delete_post);

    // Redirect back to admin panel after deletion
    header("Location: admin_panel.php");
    exit;
}

// Count total number of users
$sql_users = "SELECT COUNT(*) AS total_users FROM user_info";
$result_users = $conn->query($sql_users);
$total_users = $result_users->fetch_assoc()['total_users'];

// Count total number of posts
$sql_posts = "SELECT COUNT(*) AS total_posts FROM posts";
$result_posts = $conn->query($sql_posts);
$total_posts = $result_posts->fetch_assoc()['total_posts'];

// Retrieve user list
$sql_user_list = "SELECT ID, firstname, lastname, email FROM user_info";
$result_user_list = $conn->query($sql_user_list);

// Retrieve post list
$sql_post_list = "SELECT posts.*, user_info.firstname, user_info.lastname FROM posts INNER JOIN user_info ON posts.user_id = user_info.ID";
$result_post_list = $conn->query($sql_post_list);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #947ed6;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        li:last-child {
            border-bottom: none;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .delete {
            color: red;
            cursor: pointer;
        }
        .post {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .post img {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 10px;
        }
        .navigation {
            margin-top: 20px;
            text-align: right;
        }
        .navigation a {
            color: #0056b3;
            text-decoration: none;
            margin-right: 20px;
        }
       
    </style>
</head>
<body>
    <div class="container">
       <div class="navigation">
            <a href="logout.php">Logout</a>
        </div>
        <h1>Welcome to Admin Panel</h1>
        
        <h2>Total Users: <?php echo $total_users; ?></h2>
        <h2>Total Posts: <?php echo $total_posts; ?></h2>

        <h2>User List:</h2>
        <ul>
            <?php
            if ($result_user_list->num_rows > 0) {
                while ($row_user = $result_user_list->fetch_assoc()) {
                    echo "<li>{$row_user['firstname']} {$row_user['lastname']} - {$row_user['email']} ";
                    echo "<a href='admin_panel.php?delete_user_id={$row_user['ID']}'>Delete User</a></li>";
                }
            } else {
                echo "<li>No users found.</li>";
            }
            ?>
        </ul>

        <h2>Post List:</h2>
        <ul>
            <?php
            if ($result_post_list->num_rows > 0) {
                while ($row_post = $result_post_list->fetch_assoc()) {
                    echo "<li>";
                    echo "<div class='post'>";
                    echo "<p><strong>User:</strong> {$row_post['firstname']} {$row_post['lastname']}</p>";
                    echo "<p><strong>Description:</strong> {$row_post['description']}</p>";
                    echo "<p><strong>Time:</strong> {$row_post['created_at']}</p>";
                    if (!empty($row_post['image_path'])) {
                        echo "<img src='{$row_post['image_path']}' alt='Posted Image'>";
                    }
                    echo "<p class='delete'><a href='admin_panel.php?delete_post_id={$row_post['post_id']}'>Delete Post</a></p>";
                    echo "</div>";
                    echo "</li>";
                }
            } else {
                echo "<li>No posts found.</li>";
            }
            ?>
        </ul>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
