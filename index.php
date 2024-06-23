<?php

require 'config.php'; // Include the configuration file that contains database credentials

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";// Define the Data Source Name (DSN) for the database connection
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];   // Set PDO options, enabling exception mode for error handling

try {
    $pdo = new PDO($dsn, $user, $password, $options); // This code tries to connect to the database using the settings we prepared earlier.

    // Check if the PDO connection was successful
    if ($pdo) { 
        // Check if the form was submitted using POST method
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            // Retrieve the submitted username and password from POST request
            //This checks if the form was submitted, when someone tries to log in. It then gets the username and password they entered.
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Prepare a SQL query to select the user with the given username
            $query = "SELECT * FROM `users` WHERE username = :username";
            $statement = $pdo->prepare($query);
             // Execute the query with the provided username
            $statement->execute([':username' => $username]);

            // Fetch the user data from the result
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            // Check if a user was found
            if ($user) {
                // Check if the provided password matches the stored password
                if ('secret143' === $password) {
                    // Store user information in session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    // Redirect the user to the posts page
                    header("Location: posts.php");
                    exit;
                } else {
                    $alertMessage = "Invalid password!";
                    // echo "<script>alert('Invalid password!');</script>";
                }
            } else {
                    $alertMessage = "User not found!";
                    // echo "<script>alert('User not found!');</script>";
            }
        }
    }
} catch (PDOException $e) {
    // Display any PDO exception errors
    echo '<pre>';
    echo $e->getMessage("Not connected");
    echo '</pre>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>       
    <style>
        html {
            background-image:url("background.jpg");
            background-size: cover;
            background-repeat: no-repeat;
        }

        .login-container {
            max-width: 325px;
            height: 400px;
            margin: 100px auto;
            padding: 20px;
            border: 1px solid white;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.8); 
            position: relative;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h2 {
            margin-top: 80px;
            color: black;
            text-align: center;
            font-family: arial, sans-serif;
        }

        h5 {
            color: black;
            text-align: center;
            font-family: arial, sans-serif;
            font-weight: normal;
        }


        form {
            margin-top: 3px;
        }

        input {
            margin: auto;
            padding: 8px;
            border-radius: 20px;
        }

        #submit {
            margin: auto;
            border-radius: 20px;
        }

        input[type="text"], input[type="password"], button {
            display: block;
            margin-bottom: 10px;
        }

        button:hover {
            color: blue;
        }

        .alert {
            display: none; 
            background-color: red;
            color: white;
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 10px;
            position: absolute;
            top: -60px;
            left: 0;
            width: 95%;
        }

        .alert.show {
            display: block; 
        }
    </style>
</head>

<body>
    <div class="login-container">
    <div id="alert" class="alert"></div>
        <h2>Log In</h2>
        <h5>Please enter your username and password</h5>
        <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <input type="text" id="username" placeholder="Enter username" name="username" required>
            <input type="password" id="password" placeholder="Enter password" name="password" required>
            <button id="submit">Login</button>
        </form>
    </div>
    <script>
        // Check if there's an alert message
        const alertMessage = "<?php echo $alertMessage; ?>";
        if (alertMessage) {
            const alertDiv = document.getElementById('alert');
            alertDiv.textContent = alertMessage;
            alertDiv.classList.add('show');
        }
    </script>
</body>

</html>