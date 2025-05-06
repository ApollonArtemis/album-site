<?php
include 'config.php';

$folderId = $_GET['folder_id'];
$images = $conn->query("SELECT * FROM images WHERE folder_id = $folderId");

if ($images->num_rows > 0) {
    while ($img = $images->fetch_assoc()) {
        $safeFilename = htmlspecialchars($img['filename']);
        $folderName = htmlspecialchars($_GET['folder_name'] ?? ''); // or fetch folder name by ID if needed
        echo "
        <div class='col-md-3 col-sm-6 mb-4'>
            <div class='card'>
                <a href='#' data-bs-toggle='modal' data-bs-target='#imagePreviewModal' data-img-src='uploads/$folderName/$safeFilename'>
                    <img src='uploads/$folderName/$safeFilename' class='card-img-top img-thumbnail' style='object-fit: cover; width: 100%; height: 200px;' alt='Image'>
                </a>
                <div class='card-body p-2 text-center'>
                    <p class='card-text mb-0 text-truncate' title='$safeFilename'>$safeFilename</p>
                </div>
            </div>
        </div>";
    }
}
