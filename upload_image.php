<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $folder_id = $_POST['folder_id'];
    $file = $_FILES['image'];

    // Check for errors
    if ($file['error'] == UPLOAD_ERR_OK) {
        $folder_query = $conn->query("SELECT * FROM folders WHERE id = $folder_id");
        $folder = $folder_query->fetch_assoc();

        if ($folder) {
            $folder_name = $folder['name'];
            $target_dir = "uploads/$folder_name/";

            // Ensure the folder exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // Generate a unique file name to avoid overwriting
            $filename = uniqid() . '-' . basename($file['name']);
            $target_file = $target_dir . $filename;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($file['tmp_name'], $target_file)) {
                // Save the file path in the database
                $stmt = $conn->prepare("INSERT INTO images (folder_id, filename) VALUES (?, ?)");
                $stmt->bind_param('is', $folder_id, $filename);
                $stmt->execute();

                // Redirect back to the folder page
                header("Location: folder.php?id=$folder_id");
                exit;
            } else {
                echo "Failed to upload file.";
            }
        } else {
            echo "Folder not found.";
        }
    }
}
