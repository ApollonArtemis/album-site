<?php
include 'config.php';

// Get the folder ID from the URL
$folder_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch folder details
$folder_query = $conn->query("SELECT * FROM folders WHERE id = $folder_id");
$folder = $folder_query->fetch_assoc();

if (!$folder) {
    echo "Folder not found.";
    exit;
}

// Fetch images for the folder
$images = $conn->query("SELECT * FROM images WHERE folder_id = $folder_id");
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= htmlspecialchars($folder['name']) ?> - Photo Album</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            width: 300px;
            height: 260px;
            /* reduced from 300px to prevent cutting off file name */
            overflow: hidden;
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 220px;
                background-color: #f8f9fa;
                border-right: 1px solid #ddd;
                padding-top: 20px;
            }

            .main-content {
                margin-left: 240px;
                padding: 20px;
            }
        }

        @media (max-width: 767.98px) {
            .main-content {
                padding: 20px;
            }
        }

        .sidebar a {
            display: block;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #e9ecef;
            color: #007bff;
        }
    </style>
</head>

<body>

    <!-- Top Navbar (for small screens) -->
    <nav class="navbar navbar-light bg-light d-md-none">
        <div class="container-fluid">
            <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                ☰ Menu
            </button>
            <span class="navbar-brand mb-0 h1">Photo Album</span>
        </div>
    </nav>

    <!-- Offcanvas Sidebar (mobile) -->
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">📁 Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <a href="index.php" class="menu-link active">🏠 Dashboard</a>
            <a href="#" class="menu-link">⭐ Favorites</a>
            <a href="#" class="menu-link">🗑️ Trash</a>
            <a href="#" class="menu-link">📦 Archive</a>
            <a href="#" class="menu-link">⚙️ Settings</a>
        </div>
    </div>

    <!-- Static Sidebar (desktop) -->
    <div class="sidebar d-none d-md-block">
        <h4 class="text-center">📁 Album</h4>
        <a href="index.php" class="active">🏠 Dashboard</a>
        <a href="#">⭐ Favorites</a>
        <a href="#">🗑️ Trash</a>
        <a href="#">📦 Archive</a>
        <a href="#">⚙️ Settings</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Folder Header -->
        <div class="">
            <h2>📂 <?= htmlspecialchars($folder['name']) ?></h2>
            <!-- Upload Image Button (visible when images are available) -->
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-primary upload-btn d-none" id="uploadImageButton" data-bs-toggle="modal" data-bs-target="#uploadImageModal">+ Upload Image</button>
        </div>

        <!-- Empty State for Folder -->
        <div class="blankslate mw-100 text-center mt-5 pt-5 d-none" id="emptyState">
            <img class="blankslate-top-img w-25" src="images/empty-folder.png" />
            <h4 class="text-secondary">No Images Here</h4>
            <p>Click the button below to add a new image.</p>
            <div class="blankslate-actions">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#uploadImageModal">
                    + Upload Image
                </button>
            </div>
        </div>

        <!-- Display Images -->
        <div class="row mt-3" id="imageContainer">
            <?php if ($images->num_rows > 0): ?>
                <!-- Show Images if available -->
                <?php while ($img = $images->fetch_assoc()): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-img-src="uploads/<?= $folder['name'] ?>/<?= $img['filename'] ?>">
                                <img src="uploads/<?= $folder['name'] ?>/<?= $img['filename'] ?>" class="card-img-top img-thumbnail" style="object-fit: cover; width: 100%; height: 200px;" alt="Image">
                            </a>
                            <div class="card-body p-2 text-center">
                                <p class="card-text mb-0 text-truncate" title="<?= htmlspecialchars($img['filename']) ?>">
                                    <?= htmlspecialchars($img['filename']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
                <script>
                    // Show the upload button and hide empty state if images are found
                    document.getElementById('uploadImageButton').classList.remove('d-none');
                    document.getElementById('emptyState').classList.add('d-none');
                </script>
            <?php else: ?>
                <script>
                    // Show empty state if no images exist
                    document.getElementById('uploadImageButton').classList.add('d-none');
                    document.getElementById('emptyState').classList.remove('d-none');
                </script>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal for uploading an image -->
    <div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadImageModalLabel">Upload Image to <?= htmlspecialchars($folder['name']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="upload_image.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="folder_id" value="<?= $folder['id'] ?>">
                        <input type="file" name="image" class="form-control mb-2" required>
                        <button type="submit" class="btn btn-primary">Upload Image</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <img id="previewImage" src="" class="img-fluid" style="max-height: 95vh;" alt="Full Size Image">
                </div>
            </div>
        </div>
    </div>



    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



    <script>
        const imagePreviewModal = document.getElementById('imagePreviewModal');
        imagePreviewModal.addEventListener('show.bs.modal', function(event) {
            const trigger = event.relatedTarget;
            const imgSrc = trigger.getAttribute('data-img-src');
            const modalImg = imagePreviewModal.querySelector('#previewImage');
            modalImg.src = imgSrc;
        });
    </script>

</body>

</html>