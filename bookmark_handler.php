<?php
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    exit;
}
if (!isset($_POST['blog_id']) || !isset($_POST['is_favorite'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}
$blog_id = intval($_POST['blog_id']);
$is_favorite = intval($_POST['is_favorite']);

$conn = new mysqli('localhost', 'root', '', 'law');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection error']);
    exit;
}
if ($is_favorite === 1) {
    $sql = "INSERT INTO favorites (user_id, blog_id) VALUES (?, ?)";
} else {
    $sql = "DELETE FROM favorites WHERE user_id = ? AND blog_id = ?";
}
$user_id = 1; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $blog_id);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Bookmark updated successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Bookmark update failed']);
}

$conn->close();
