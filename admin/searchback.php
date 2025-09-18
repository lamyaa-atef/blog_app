<?php
include '../db.php';

if (isset($_POST['query'])) {
    $query = trim($_POST['query']);

    $sql = "SELECT Name FROM users WHERE Name LIKE ? LIMIT 5";
    $stmt = $link->prepare($sql);

    if ($stmt) {
        $searchTerm = "%$query%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $name = htmlspecialchars($row['Name']);
                echo "<div>$name</div>";
            }
        } else {
            echo "<div style='color: #999; cursor: default; pointer-events: none;'>No results found</div>";        
        }
        $stmt->close();
    }
}
?>

