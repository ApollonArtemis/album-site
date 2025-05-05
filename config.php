<?php
$conn = new mysqli("localhost", "root", "", "album_sites");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
