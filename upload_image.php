<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image_file'])) {
    $folder_id = intval($_POST['folder_id']); // Sanitize folder ID
    $file = $_FILES['image_file'];

    // Validate uploaded file
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Fetch folder name from database
        $folder_query = $conn->prepare("SELECT name FROM folders WHERE id = ?");
        $folder_query->bind_param("i", $folder_id);
        $folder_query->execute();
        $folder_result = $folder_query->get_result();

        if ($folder_result->num_rows > 0) {
            $folder = $folder_result->fetch_assoc();
            $folder_name = $folder['name'];
            $upload_dir = "uploads/$folder_name/";

            // Ensure folder exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate safe filename
            $safe_filename = uniqid() . '-' . basename($file['name']);
            $target_path = $upload_dir . $safe_filename;

            // Optionally validate file type (e.g., allow only images)
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($file['type'], $allowed_types)) {
                echo "Invalid file type.";
                exit;
            }

            // Move uploaded file to destination
            if (move_uploaded_file($file['tmp_name'], $target_path)) {
                $stmt = $conn->prepare("INSERT INTO images (folder_id, filename) VALUES (?, ?)");
                $stmt->bind_param("is", $folder_id, $safe_filename);
                $stmt->execute();

                // Redirect to homepage and re-open folder
                header("Location: homepage.php?folder_id=$folder_id");
                exit;
            } else {
                echo "❌ Failed to move uploaded file.";
            }
        } else {
            echo "❌ Folder not found.";
        }
    } else {
        echo "❌ File upload error: " . $file['error'];
    }
} else {
    echo "❌ Invalid request.";
}
?>
