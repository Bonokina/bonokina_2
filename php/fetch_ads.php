<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Content-Type: application/json");
    echo json_encode(['error' => 'User not authenticated']);
    exit();
}

$conn = new mysqli("localhost", "root", "", "advertisements");
if ($conn->connect_error) {
    header("Content-Type: application/json");
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$stmt = $conn->prepare("SELECT ads.id, ads.title, ads.description, ads.created_at, users.username FROM ads JOIN users ON ads.user_id = users.id ORDER BY ads.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();

$ads = [];
while ($row = $result->fetch_assoc()) {
    $ads[] = $row;
}

$stmt->close();
$conn->close();

header("Content-Type: application/json");
echo json_encode($ads);
?>
