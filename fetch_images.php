<?php
include 'config.php';
$folder_id = $_POST['folder_id'];
$result = $conn->query("SELECT filename FROM images WHERE folder_id = $folder_id");
$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}
echo json_encode($images);
?>
