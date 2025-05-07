<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image_file'])) {
    $folder_id = intval($_POST['folder_id']);
    $files = $_FILES['image_file'];

    // Fetch folder name only once
    $folder_query = $conn->prepare("SELECT name FROM folders WHERE id = ?");
    $folder_query->bind_param("i", $folder_id);
    $folder_query->execute();
    $folder_result = $folder_query->get_result();

    if ($folder_result->num_rows > 0) {
        $folder = $folder_result->fetch_assoc();
        $folder_name = $folder['name'];
        $upload_dir = "uploads/$folder_name/";

        // Create folder if it doesn't exist
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $success = true;

        // Loop through each uploaded file
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                if (!in_array($files['type'][$i], $allowed_types)) {
                    echo "❌ Invalid file type: " . htmlspecialchars($files['name'][$i]) . "<br>";
                    continue;
                }

                $safe_filename = uniqid() . '-' . basename($files['name'][$i]);
                $target_path = $upload_dir . $safe_filename;

                if (move_uploaded_file($files['tmp_name'][$i], $target_path)) {
                    // Save file info in the database
                    $stmt = $conn->prepare("INSERT INTO images (folder_id, filename) VALUES (?, ?)");
                    $stmt->bind_param("is", $folder_id, $safe_filename);
                    $stmt->execute();
                } else {
                    $success = false;
                    echo "❌ Failed to upload " . htmlspecialchars($files['name'][$i]) . "<br>";
                }
            } else {
                echo "❌ Upload error for " . htmlspecialchars($files['name'][$i]) . ": " . $files['error'][$i] . "<br>";
            }
        }

        if ($success) {
            header("Location: homepage.php?folder_id=$folder_id");
            exit;
        }

    } else {
        echo "❌ Folder not found.";
    }
} else {
    echo "❌ Invalid request.";
}
?>
