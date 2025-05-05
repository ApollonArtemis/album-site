<?php
include 'config.php';

$folder = $_POST['folder_name'];
if (!empty($folder)) {
    $stmt = $conn->prepare("INSERT INTO folders (name) VALUES (?)");
    $stmt->bind_param("s", $folder);
    $stmt->execute();
    
    mkdir("uploads/" . $folder);  // create folder on server
}

header("Location: index.php");
?>
