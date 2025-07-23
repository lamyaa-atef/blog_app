<?php
include '../db.php';

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize the ID
    
    // Prepare and execute the delete statement
    $stmt = $link->prepare("DELETE FROM users WHERE ID = ?");
    
    if ($stmt) {
        $stmt->bind_param('i', $id); // Bind the integer parameter for ID
        if ($stmt->execute()) {
            // Redirect to the index page with a success message
            header("Location: index.php"); // Added success parameter
            exit();
        } else {
            echo "Error deleting user: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Failed to prepare the statement: " . $link->error;
    }
} else {
    echo "Invalid request. User ID is missing.";
}

// Close the database connection
mysqli_close($link);
?>
