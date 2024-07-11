<?php
session_start();

// Fetch user information from the database based on their user ID
$servername = "localhost";
$username = "root";
$password = "";
$database = "social_app";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id']; // Get user ID from session

$sql_user = "SELECT firstname, lastname FROM user_info WHERE ID = '$user_id'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows == 1) {
    $row_user = $result_user->fetch_assoc();
    $first_name = $row_user['firstname'];
    $last_name = $row_user['lastname'];
} else {
    // Handle case where user information is not found
    $first_name = '';
    $last_name = '';
}

// Initialize search query
$search_query = "";
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["search"])) {
    $search_query = $_GET["search"];
}

// Retrieve posts from the database with corresponding user information based on search query
if ($search_query) {
    $sql_posts = "SELECT posts.*, user_info.firstname, user_info.lastname 
                  FROM posts 
                  INNER JOIN user_info ON posts.user_id = user_info.ID 
                  WHERE posts.description LIKE '%$search_query%' 
                  OR user_info.firstname LIKE '%$search_query%' 
                  OR user_info.lastname LIKE '%$search_query%' 
                  ORDER BY posts.post_id DESC";
} else {
    $sql_posts = "SELECT posts.*, user_info.firstname, user_info.lastname 
                  FROM posts 
                  INNER JOIN user_info ON posts.user_id = user_info.ID 
                  ORDER BY posts.post_id DESC";
}
$result_posts = $conn->query($sql_posts);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        .header {
           display: flex;
           justify-content: space-between;
           align-items: center;
           padding: 30px 30px;
           background-color: #001430;
           color: white;
        }

        .logo img {
           width: 250px;
           height: 80px;
        }

        .search input[type="text"] {
            padding: 5px 50px;
            border: none;
            border-radius: 5px;
        }

        .search button {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            background-color: #ffffff;
            color: #007bff;
            cursor: pointer;
        }

        .profile {
           display: flex;
           align-items: center;
        }

        .profile-name a {
         color: white;
         text-decoration: none;
         font-weight: bold;
         font-size: larger;
        }
        .profile-name a:hover {
            color: orange;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e3e3e3;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .post {
            margin-top: 20px;
            border: 1px solid #e3e3e3;
            border-radius: 10px;
            padding: 20px;
            background-color: #fafafa;
        }
        .post img {
            max-width: 100%;
            display: block;
            margin: 10px auto;
            border-radius: 10px;
        }
        .post .profile-name_on_post {
            display: block;
            font-size: 1.2em;
            margin-bottom: 10px;
            color: #007bff;
        }
        .post p {
            margin: 10px 0;
        }
        .post-time {
            font-size: 0.9em;
            color: #888;
        }
        .navigation {
            margin-top: 20px;
            text-align: right;
        }
        .navigation a {
            color: #007bff;
            text-decoration: none;
            margin-right: 20px;
        }
        .navigation a:hover {
            text-decoration: underline;
        }
        form {
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header class="header"> 
        <div class="logo">
            <img src="app_icon.png" alt="App Icon">
        </div>
        <div class="search">
            <form method="GET" action="homepage.php">
                <input type="text" name="search" placeholder="Search" value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="profile">
            <span class="profile-name"><a href="user_profile.php"><?php echo $first_name . ' ' . $last_name; ?></a></span>
        </div>
        <div class="navigation">
            <a href="logout.php">Logout</a>
        </div>
    </header>

    <div class="container">
        <h2>Add a Post</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="50"></textarea><br>
            <label for="image">Select Image:</label><br>
            <input type="file" id="image" name="image"><br><br>
            <input type="submit" name="post_submit" value="Post">
        </form>

        <?php
        // PHP code for processing form submission goes here

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
       if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["post_submit"])) {
          $description = $_POST['description'];
           $image_path = '';

         // Check if image file is uploaded
         if(isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
         $target_dir = "uploads/";
         $target_file = $target_dir . basename($_FILES["image"]["name"]);

         // Check if file already exists
         if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
         } else {
            // Upload file
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
         }
         }

         // Insert post data into database
           $sql = "INSERT INTO posts (user_id, description, image_path) VALUES (?, ?, ?)";
           $stmt = $conn->prepare($sql);
           $stmt->bind_param("iss", $user_id, $description, $image_path);
           $stmt->execute();
           $stmt->close();
        }
        ?>
    </div>

    <!-- Display posted content here -->
    <div class="container">
        <?php
        if ($result_posts->num_rows > 0) {
            while ($row = $result_posts->fetch_assoc()) {
                // Format the post time
                $post_time = date("F j, Y, g:i a", strtotime($row["created_at"]));

                // Output the post with the user's name and post time
                echo "<div class='post'>";
                echo "<span class='profile-name_on_post'><strong>{$row['firstname']} {$row['lastname']}</strong></span>";
                echo "<p><strong>Description:</strong> " . $row["description"] . "</p>";
                echo "<p><strong>Posted at:</strong> " . $post_time . "</p>";
                if (!empty($row["image_path"])) {
                    echo "<img src='" . $row["image_path"] . "' alt='Posted Image'>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No posts found.</p>";
        }
        ?>
    </div>
</body>
</html>
