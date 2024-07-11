<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Account</title>
    <style>
        /* Style for the registration form */
        body {
            background-image: url('login_page.png');
            opacity: 1;
            font-family: Arial, sans-serif;
            font-size: large;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
          max-width: 200px;
          margin: 50px auto;
          background-color: #fff;
          padding: 20px;
          border-radius: 8px;
          box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

       h2 {
          text-align: center;
         margin-bottom: 20px;
        }

       label {
         font-weight: bold;
        }

       input[type="text"],
       input[type="email"],
       input[type="password"] {
         width: 30%;
         font-size: larger ;
         padding: 10px;
         margin-bottom: 15px;
         border: 1px solid #ccc;
         border-radius: 4px;
         box-sizing: border-box;
         
         
        }

       input[type="submit"] {
         width: 10%;
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

        .error-message {
          color: red;
          margin-top: 10px;
        }

        .success-message {
          color: green;
          margin-top: 10px;
        }

        .btn-back-to-index {
         display: block;
         width: 15%;
         padding: 10px;
         background-color: #8f1975;
         color: #fff;
         text-decoration: none;
         border-radius: 4px;
         margin-top: 20px;
        }

        .btn-back-to-index:hover {
         background-color: #0056b3;
        }

    </style>
</head>
<body>
    <h1><u>Account Creation</u></h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="firstname">First Name:</label><br>
        <input type="text" id="firstname" name="firstname" required><br>
        <label for="lastname">Last Name:</label><br>
        <input type="text" id="lastname" name="lastname" required><br>
        <label for="email">Email Address:</label><br>
        <input type="email" id="email" name="email" required><br>
        <label for="student_id">Student ID:</label><br>
        <input type="text" id="student_id" name="student_id" required><br>
        <label for="department">Department:</label><br>
        <input type="text" id="department" name="department" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" name="register_submit" value="Register">
    </form>

    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register_submit"])) {
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

        // Prepare and bind parameters
        $stmt = $conn->prepare("INSERT INTO user_info (firstname, lastname, email, student_id, department, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $firstname, $lastname, $email, $student_id, $department, $password);

        // Set parameters
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $student_id = $_POST['student_id'];
        $department = $_POST['department'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

        // Execute prepared statement
        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
    ?>
        <!-- Button to go back to login page -->
        <a href="index.html" class="btn-back-to-index">Back to Login</a>
</body>
</html>
