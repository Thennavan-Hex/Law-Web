<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "law";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$pagesFolder = __DIR__ . '/pages/';
$files = glob($pagesFolder . '*.html');
foreach ($files as $file) {
    $content = file_get_contents($file);
    preg_match('/<title>(.*?)<\/title>/i', $content, $titleMatches);
    $title = !empty($titleMatches) ? $titleMatches[1] : pathinfo($file, PATHINFO_FILENAME);
    $checkSql = "SELECT id FROM blogs WHERE title = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $title);
    $checkStmt->execute();
    $checkStmt->store_result();
    if ($checkStmt->num_rows == 0) {
        $insertSql = "INSERT INTO blogs (title, content) VALUES (?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
        $stmt->close();
        echo "Inserted blog with title: $title<br>";
    } else {
        echo "Skipped existing blog with title: $title<br>";
    }

    $checkStmt->close();
}
$conn->close();
?>
