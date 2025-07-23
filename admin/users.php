<?php
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'blog_app';
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if (!$link) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging error #: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    $query = "SELECT * FROM users";
    $result = mysqli_query($link, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>All Users</title>
</head>
<body>
    <div class="container">
        <h1>All Users</h1>
        <a href="index.php" class="btn btn-default">Back to Admin</a>
        <a href="Add_User.php" class="btn btn-success">Add New User</a><br><br>
        <?php
        // Check if there are rows in the result
        if (mysqli_num_rows($result) > 0) {
            // Display the data in a table
            echo "<table class='table'>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>Mail Status</th>
                    <th>Actions</th>
                </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                    <td>{$row['ID']}</td>
                    <td>{$row['Name']}</td>
                    <td>{$row['Email']}</td>
                    <td>{$row['Gender']}</td>
                    <td>{$row['Mail_Status']}</td>
                    <td class='actions'>
                        <a href='View_User.php?id={$row['ID']}' title='View'><i class='fas fa-eye'></i></a>&nbsp;
                        <a href='Edit_User.php?id={$row['ID']}' title='Edit'><i class='fas fa-edit'></i></a>&nbsp;
                        <a href='Delete_User.php?id={$row['ID']}' title='Delete' onclick='return confirm(\"Are you sure you want to delete this user?\")'><i class='fas fa-trash'></i></a>
                    </td>
                </tr>";
            }
            echo "</table></div>";

        } else {
            $query = "TRUNCATE users";
            mysqli_query($link, $query);
            echo "
                <div class='alert alert-info text-center' role='alert'>
                    <h3><i class='fas fa-user-slash'></i> There are no users.</h3>
                    <p>You can add a new user by clicking the button above.</p>
                </div>
            </div>"; // closes the container div
        }
        // Close the connection
        mysqli_close($link);
    ?>
</body>
</html>
    