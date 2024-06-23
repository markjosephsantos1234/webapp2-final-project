<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Page</title>
    <style>
        html {
            background-image:url("background.jpg");
            background-size: cover;
            background-repeat: no-repeat;
        }   

        h1 {
            cursor: pointer;
        }

        .post-container {
            cursor: pointer;
            color: white;
            max-width: 600px;
            margin: 50px auto;
            padding: 50px;
            border: 1px solid white;
            border-radius: 5px;
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

        button {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="post-container">
        <h1>Detail Page</h1>
        <div id="postDetails">
            <?php

            require 'config.php';

            // If 'user_id' is not set, it means the user is not logged in, so they are sent to the 'index.php' page.
            if (!isset($_SESSION['user_id'])) {
                header("Location: index.php");
                exit;
            }

            $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";// Define the Data Source Name (DSN) for the database connection.
            $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];// Define the Data Source Name (DSN) for the database connection.

            // This code tries to connect to the database using the settings we prepared earlier.
            try {
                $pdo = new PDO($dsn, $user, $password, $options);

                //This checks if the database connection was successful.
                if ($pdo) {
                    //This checks if a post ID is provided in the URL (like posts.php?id=1). 
                    //If it is, it saves the ID in a variable $id
                    if (isset($_GET['id'])) {
                        $id = $_GET['id'];

                        //These lines prepare a query to find the post with the given ID in the posts table of the database. 
                        //The :id is a placeholder that gets replaced with the actual ID.
                        $query = "SELECT * FROM `posts` WHERE id = :id";
                        $statement = $pdo->prepare($query);
                        $statement->execute([':id' => $id]);

                        //This line fetches the post data from the database and saves it in a variable $post.
                        $post = $statement->fetch(PDO::FETCH_ASSOC);

                        //This checks if the post was found in the database. 
                        //If it was, it prints the title and body of the post. If not, it prints a message saying no post was found with that ID.
                        if ($post) {
                            echo '<h3>Title: ' . $post['title'] . '</h3>';
                            echo '<p>Body: ' . $post['body'] . '</p>';
                        } else {
                            echo "No post found with ID $id!";
                        }
                    } else {
                        echo "No post ID provided!";
                    }
                }
            } catch (PDOException $e) {
                // Display any PDO exception errors
                echo '<pre>';
                echo $e->getMessage("Not connected");
                echo '</pre>';
            }
            ?>
            <!-- Back to Posts button -->
            <form action="posts.php" method="GET" style="text-align: center; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Back to Posts</button>
            </form>
        </div>
    </div>
</body>

</html>