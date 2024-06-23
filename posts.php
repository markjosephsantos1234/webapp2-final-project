<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts Page</title>
    <style>
        html {
            background-image:url("background.jpg");
            background-size: cover;
            background-repeat: no-repeat;
        }

        .posts-container {
            background-color: gray;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid black;
            border-radius: 5px;
        }

        h1 {
            cursor: pointer;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9ff;
            cursor: pointer;
        }

        li:hover {
            background-color: #f0f0f0;
        }

        form {
            text-align: center; 
            margin-top: 20px; 
        }

        button {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="posts-container">
        <h1>Posts Page</h1>
        <ul id="postLists">
            <?php

            require 'config.php';
            
            // If 'user_id' is not set, it means the user is not logged in, so they are sent to the 'index.php' page.
            if (!isset($_SESSION['user_id'])) {
                header("Location: index.php");
                exit;
            }
            
            $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";// Define the Data Source Name (DSN) for the database connection
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];// Set PDO options, enabling exception mode for error handling

            // This code tries to connect to the database using the settings we prepared earlier.
            try {
                $pdo = new PDO($dsn, $user, $password, $options);

                // If the connection is successful, get the user ID from the session.
                if ($pdo) {
                    $user_id = $_SESSION['user_id'];

                    $query = "SELECT * FROM `posts` WHERE user_id = :id";// Prepare a SQL query to select all posts where the user ID matches the logged-in user.
                    $statement = $pdo->prepare($query);// Prepare the query for execution (this helps protect against SQL injection attacks).
                    $statement->execute([':id' => $user_id]);// Execute the query, replacing ':id' with the actual user ID.

                    // Fetch all the results from the query and store them in the '$rows' variable as an associative array.
                    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

                    // Loop through each row in the results.
                    // For each row, create a list item with a link to a detail page for that post.
                    // The link includes the post ID and the title of the post.
                    foreach ($rows as $row) {
                        echo '<li><a href="detail.php?id=' . $row['id'] . '">' . $row['title'] . '</li>';
                    }
                }
            } catch (PDOException $e) {
                // Display any PDO exception errors
                echo '<pre>';
                echo $e->getMessage("Not connected");
                echo '</pre>';
            }
            ?>
            <!-- Logout form -->
            <form action="logout.php" method="POST">
                <button type="submit" name="logout" class="btn btn-danger">Log out</button>
            </form>
        </ul>
    </div>
</body>
</html>