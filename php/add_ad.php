<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $username = $_SESSION['username'];

    $conn = new mysqli("localhost", "root", "", "advertisements");
    if ($conn->connect_error) {
        echo json_encode(['error' => 'Database connection failed']);
        exit();
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        $stmt = $conn->prepare("INSERT INTO ads (user_id, title, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $user_id, $title, $description);
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Advertisement added successfully']);
        } else {
            echo json_encode(['error' => 'Failed to add advertisement']);
        }
    } else {
        echo json_encode(['error' => 'User not found']);
    }
    $stmt->close();
    $conn->close();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Advertisement</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h2>Add Advertisement</h2>
    <form id="addAdForm">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <button type="submit">Add Advertisement</button>
    </form>
    <div id="messageBox"></div>
    <script src="../js/add_ad.js"></script>
</body>
</html>
