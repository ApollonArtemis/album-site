<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Photo Albums</title>

    <link rel="shortcut icon" href="images/photoalbum.png" type="image/x-icon">

    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />

    <link rel="stylesheet" href="css/style.css">

    <!-- Font Awesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

    <!-- Custom CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- CUSTOM JS -->
    <script src="js/sidebar.js"></script>
</head>

<body>
    <div class="wrapper">
        <!-- SIDEBAR -->
        <nav id="sidebar" class="d-flex flex-column justify-content-between">
            <ul class="list-unstyled components border-0">
                <!--LOGO-->
                <a href="#"
                    class="d-flex align-items-center justify-content-center mt-4 text-white text-decoration-none">
                    <img src="images/photoalbum.png" alt="Photo Album" height="70">
                    <p class="fw-bolder ms-2 mt-3 fs-5">Photo Album</p>
                </a>
                <!--NAV ITEMS-->
                <ul class="list-unstyled nav nav-pills flex-column mb-sm-auto mb-2 align-items-start" id="menu">
                    <!--Dashboard-->
                    <li class="event-link mt-5 w-100" data-content="content1">
                        <a href="#content1" class="active-menu nav-link d-flex align-items-center">
                            <i class="fs-4 bi bi-folder"></i>
                            <span class="ms-2 d-sm-inline">Dashboard</span>
                        </a>
                    </li>

                    <!--Detection-->
                    <li class="event-link w-100 mt-2" data-content="content2">
                        <a href="#content2" class="nav-link d-flex align-items-center">
                            <i class="fs-4 bi bi-heart"></i>
                            <span class="ms-2 d-sm-inline">Favorites</span>
                        </a>
                    </li>
                    <!--Archive-->
                    <li class="event-link w-100 mt-2" data-content="content3">
                        <a href="#content3" class="nav-link align-middle d-flex align-items-center">
                            <i class="fs-4 bi bi-archive"></i>
                            <span class="ms-2 d-sm-inline">Archive</span>
                        </a>
                    </li>
                    <!--Trash-->
                    <li class="event-link w-100 mt-2" data-content="content4">
                        <a href="#content4" class="nav-link align-middle d-flex align-items-center">
                            <i class="fs-4 bi bi-trash"></i>
                            <span class="ms-2 d-sm-inline">Trash</span>
                        </a>
                    </li>
                    <!--Settings-->
                    <li class="event-link w-100 mt-2" data-content="content5">
                        <a href="#content5" class="nav-link align-middle d-flex align-items-center">
                            <i class="fs-4 bi bi-gear"></i>
                            <span class="ms-2 d-sm-inline">Settings</span>
                        </a>
                    </li>
                </ul>
            </ul>
        </nav>

        <div id="content">
            <button type="button" id="sidebarCollapse" class="btn btn-dark hamburger">
                <i class="bi bi-list"></i> Menu
            </button>

            <!--CONTENT 1: DASHBOARD-->
            <div id="content1" class="content-item" data-title="Dashboard">
                <div class="inner-content mt-5">
                    <h2 class="fw-bold">My Photo Album</h2>

                    <!-- Add New Album Button (visible after folder creation) -->
                    <div class="d-flex justify-content-end">
                        <button class="btn mb-3 d-none" id="addAlbumButton" data-bs-toggle="modal" data-bs-target="#addAlbumModal">+ Add New Album</button>
                    </div>

                    <!-- Display Empty State if no folders are created -->
                    <div class="blankslate mw-100 text-center mt-5 pt-5 d-none" id="emptyState">
                        <img class="blankslate-top-img w-25" src="images/empty-folder.png" />
                        <h4 class="text-secondary">No Albums Here</h4>
                        <p>Click the button below to add a new album.</p>
                        <div class="blankslate-actions">
                            <button id="emptystateAddAlbumModal" class="btn" type="button" data-bs-toggle="modal" data-bs-target="#addAlbumModal">
                                + Add New Album
                            </button>
                        </div>
                    </div>

                    <!-- Display Folders -->
                    <div class="row" id="folderContainer">
                        <?php
                        $folders = $conn->query("SELECT * FROM folders");
                        if ($folders->num_rows > 0):
                            // Display each folder
                            while ($row = $folders->fetch_assoc()):
                        ?>
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                                    <a href="#" class="text-decoration-none open-folder" data-folder-id="<?= $row['id'] ?>" data-folder-name="<?= htmlspecialchars($row['name']) ?>">
                                        <div class="card">
                                            <div class="card-body">
                                                <i class="bi bi-journal-album"></i>
                                                <p class="ms-3 fw-semibold">
                                                    <?= htmlspecialchars($row['name']) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php endwhile; ?>
                            <script>
                                // Show the 'Add New Album' button and hide empty state if folders are found
                                document.getElementById('addAlbumButton').classList.remove('d-none');
                                document.getElementById('emptyState').classList.add('d-none');
                            </script>
                        <?php else: ?>
                            <script>
                                // Show empty state if no folders exist
                                document.getElementById('addAlbumButton').classList.add('d-none');
                                document.getElementById('emptyState').classList.remove('d-none');
                            </script>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!--CONTENT 2: FAVORTIES-->
            <div id="content2" class="content-item" style="display: none;" data-title="Favorites">
                <div class="inner-content">
                </div>
            </div>

            <!-- CONTENT 3: ARCHIVE -->
            <div id="content3" class="content-item" style="display: none" data-title="Archive">
                <div class="inner-content">

                </div>
            </div>

            <!--CONTENT 4: TRASH-->
            <div class="content-item" id="content4" style="display: none;" data-title="Trash">
                <div class="inner-content px-1 px-sm-5">
                </div>
            </div>

            <!--CONTENT 5: SETTINGS -->
            <div class="content-item" id="content5" style="display: none;" data-title="Settings">
                <div class="inner-content">

                </div>
            </div>

            <!-- CONTENT 6: View Folder -->
            <div class="content-item" id="viewFolder" style="display: none;" data-title="View Folder">
                <div class="inner-content mt-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 id="folderTitle" class="fw-bold"></h2>

                        <input type="hidden" id="uploadFolderIdDrop" value="">

                        <!-- Upload Image Button -->
                        <button id="uploadImageButton" type="button" class="btn d-none" data-bs-toggle="modal" data-bs-target="#uploadImageModal">
                            <i class="fas fa-upload"></i> Upload Image
                        </button>
                    </div>

                    <div class="d-flex justify-content-end">
                        <!-- Upload Image Button (alternative) -->
                        <button class="btn btn-primary d-none" id="uploadImageButtonAlt" data-bs-toggle="modal" data-bs-target="#uploadImageModal">+ Upload Image</button>
                    </div>

                    <!-- Drag-and-Drop Zone -->
                    <div id="dropZone" class="p-4 my-3">
                        <!-- Empty State -->
                        <div id="folderEmptyState" class="blankslate my-5 text-center d-none">
                            <div class="mb-3">
                                <i class="fas fa-folder-open fa-3x text-muted"></i>
                            </div>
                            <h3>No images in this folder yet</h3>
                            <p class="text-muted">Start uploading to fill it up or drag and drop images here</p>
                            <button class="btn" data-bs-toggle="modal" id="emptystate2AddAlbumModal" data-bs-target="#uploadImageModal">Upload Image</button>
                        </div>
                        <p class="text-muted text-center mb-0" id="dropHint">You can drag and drop images here</p>
                    </div>

                    <!-- Display Images -->
                    <div class="row mt-3" id="imageContainer">
                        <?php if ($images->num_rows > 0): ?>
                            <?php while ($img = $images->fetch_assoc()): ?>
                                <div class="col-md-3 col-sm-6 mb-4">
                                    <div class="card">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-img-src="uploads/<?= $folder['name'] ?>/<?= $img['filename'] ?>">
                                            <img src="uploads/<?= $folder['name'] ?>/<?= $img['filename'] ?>" class="card-img-top img-thumbnail" style="object-fit: cover; width: 100%; height: 200px;" alt="Image">
                                        </a>
                                        <div class="card-body text-center">
                                            <p class="card-text mb-0 text-truncate" title="<?= htmlspecialchars($img['filename']) ?>">
                                                <?= htmlspecialchars($img['filename']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>

                            <script>
                                document.getElementById('uploadImageButton').classList.remove('d-none');
                                document.getElementById('folderEmptyState').classList.add('d-none');
                            </script>

                        <?php else: ?>
                            <script>
                                document.getElementById('uploadImageButton').classList.remove('d-none'); // Show upload button
                                document.getElementById('folderEmptyState').classList.remove('d-none'); // Show empty state
                            </script>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal for adding new album -->
    <div class="modal fade" id="addAlbumModal" tabindex="-1" aria-labelledby="addAlbumModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAlbumModalLabel">Create New Album</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="create_folder.php" method="POST">
                        <div class="mb-3">
                            <label for="folder_name" class="form-label">Album Name</label>
                            <input type="text" name="folder_name" id="folder_name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn" id="createAlbum">Create Album</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for uploading an image -->
    <div class="modal fade" id="uploadImageModal" tabindex="-1" aria-labelledby="uploadImageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadImageModalLabel">Upload Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadImageForm" method="post" action="upload_image.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="uploadFolderId" name="folder_id">
                        <div class="mb-3">
                            <label for="imageFile" class="form-label">Select Image</label>
                            <input type="file" class="form-control" id="imageFile" name="image_file[]" multiple required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn" id="btnUploadImagesModal">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Modal for Dropped Images -->
    <div class="modal fade" id="droppedImagesModal" tabindex="-1" aria-labelledby="droppedImagesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="droppedImagesModalLabel">Preview Dropped Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body d-flex flex-wrap gap-3" id="droppedImagePreview">
                    <!-- Preview thumbnails go here -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn" id="confirmUploadBtn">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!--DRAG AND DROP IMAGES-->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dropZone = document.getElementById('dropZone');
            const imageContainer = document.getElementById('imageContainer'); // This is the container for displayed images
            const previewContainer = document.getElementById('droppedImagePreview');
            const uploadBtn = document.getElementById('confirmUploadBtn');
            const dropHint = document.getElementById('dropHint'); // To show or hide the drag/drop hint

            let droppedFiles = [];

            // Function to handle image previews
            function previewDroppedFiles(files) {
                previewContainer.innerHTML = ''; // Clear any previous previews
                droppedFiles.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const img = document.createElement('img');
                        img.src = event.target.result;
                        img.className = 'img-thumbnail';
                        img.style.width = '150px';
                        previewContainer.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Handle dragover event
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('bg-light'); // Add visual effect for dragover
            });

            // Handle dragleave event
            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('bg-light');
            });

            // Handle drop event on dropZone
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('bg-light');
                droppedFiles = [...e.dataTransfer.files].filter(file => file.type.startsWith('image/'));

                // Preview the dropped files
                previewDroppedFiles(droppedFiles);

                // Show the preview modal for dropped images
                const modal = new bootstrap.Modal(document.getElementById('droppedImagesModal'));
                modal.show();
            });

            // Allow images in imageContainer to be dragged and dropped
            imageContainer.addEventListener('dragover', (e) => {
                e.preventDefault();
                imageContainer.classList.add('bg-light');
            });

            imageContainer.addEventListener('dragleave', () => {
                imageContainer.classList.remove('bg-light');
            });

            imageContainer.addEventListener('drop', (e) => {
                e.preventDefault();
                imageContainer.classList.remove('bg-light');
                droppedFiles = [...e.dataTransfer.files].filter(file => file.type.startsWith('image/'));

                // Preview the dropped files
                previewDroppedFiles(droppedFiles);

                // Show the preview modal for dropped images
                const modal = new bootstrap.Modal(document.getElementById('droppedImagesModal'));
                modal.show();
            });

            // Upload dropped images
            uploadBtn.addEventListener('click', () => {
                const formData = new FormData();
                droppedFiles.forEach(file => formData.append('image_file[]', file));

                const folderId = document.getElementById('uploadFolderIdDrop').value;
                if (!folderId) {
                    alert("No folder selected.");
                    return;
                }

                formData.append('folder_id', folderId);

                fetch('upload_image.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.ok ? location.reload() : alert("Upload failed."))
                    .catch(err => console.error(err));
            });

            // Show/Hide the drop hint based on images
            function toggleDropHint(imagesPresent) {
                if (imagesPresent) {
                    dropHint.classList.add('d-none'); // Hide the drag-and-drop hint
                } else {
                    dropHint.classList.remove('d-none'); // Show the drag-and-drop hint
                }
            }

            // Show/Hide Empty State and Drag-and-Drop Hint After Folder Data Fetch
            $('.open-folder').click(function(e) {
                e.preventDefault();

                const folderId = $(this).data('folder-id');
                const folderName = $(this).data('folder-name');

                $('.content-item').hide();
                $('#viewFolder').show();

                $('#folderTitle').text("Album: " + folderName);
                $('#uploadImageModalLabel').text("Upload Image to " + folderName);
                $('input[name="folder_id"]').val(folderId);
                $('#uploadFolderIdDrop').val(folderId); // ✅ ADD THIS

                $('#imageContainer').html('');
                $('#uploadImageButton').addClass('d-none');
                $('#folderEmptyState').removeClass('d-none');
                $('#dropHint').removeClass('d-none'); // Make sure the hint is shown by default

                $.ajax({
                    url: 'fetch_images.php',
                    method: 'POST',
                    data: {
                        folder_id: folderId
                    },
                    success: function(response) {
                        const data = JSON.parse(response);
                        if (data.length > 0) {
                            // Hide Empty State & Show Images
                            $('#folderEmptyState').addClass('d-none');
                            $('#uploadImageButton').removeClass('d-none');
                            $('#dropHint').addClass('d-none'); // Hide the drag/drop hint

                            data.forEach(img => {
                                $('#imageContainer').append(`
                            <div class="col-md-3 col-sm-6 mb-4">
                                <div class="card">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-img-src="uploads/${folderName}/${img.filename}">
                                        <img src="uploads/${folderName}/${img.filename}" class="card-img-top img-thumbnail" style="object-fit: cover; width: 100%; height: 200px;" alt="Image">
                                    </a>
                                    <div class="card-body p-2 text-center">
                                        <p class="card-text mb-0 text-truncate" title="${img.filename}">${img.filename}</p>
                                    </div>
                                </div>
                            </div>
                        `);
                            });

                            // Toggle the drop hint visibility
                            toggleDropHint(true);

                        } else {
                            // Show Empty State if No Images
                            $('#folderEmptyState').removeClass('d-none');
                            $('#uploadImageButton').addClass('d-none');
                            $('#dropHint').removeClass('d-none'); // Show the drag/drop hint again

                            // Toggle the drop hint visibility
                            toggleDropHint(false);
                        }
                    },
                    error: function() {
                        console.log('Error fetching images');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const folderId = urlParams.get('folder_id');

            if (folderId) {
                // Simulate click on the folder
                const folderCard = document.querySelector(`.open-folder[data-folder-id="${folderId}"]`);
                if (folderCard) {
                    folderCard.click();
                }

                // Optionally clean the URL
                const url = new URL(window.location);
                url.searchParams.delete('folder_id');
                window.history.replaceState({}, '', url);
            }
        });
    </script>

    <!--Hide Empty state-->
    <script>
        window.addEventListener("DOMContentLoaded", () => {
            <?php if ($folders->num_rows > 0): ?>
                document.getElementById('addAlbumButton').classList.remove('d-none');
                document.getElementById('emptyState').classList.add('d-none');
            <?php else: ?>
                document.getElementById('addAlbumButton').classList.add('d-none');
                document.getElementById('emptyState').classList.remove('d-none');
            <?php endif; ?>
        });
    </script>

    <!-- Full Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow">
                <div class="modal-body p-0">
                    <img id="previewImage" src="" class="img-fluid w-100 rounded" alt="Preview">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!--Show images on Folder-->
    <!-- <script>
        $('.open-folder').click(function(e) {
            e.preventDefault();

            const folderId = $(this).data('folder-id');
            const folderName = $(this).data('folder-name');

            $('.content-item').hide();
            $('#viewFolder').show();

            $('#folderTitle').text("Album: " + folderName);
            $('#uploadImageModalLabel').text("Upload Image to " + folderName);
            $('input[name="folder_id"]').val(folderId);
            $('#uploadFolderIdDrop').val(folderId); // ✅ ADD THIS

            $('#imageContainer').html('');
            $('#uploadImageButton').addClass('d-none');
            $('#folderEmptyState').removeClass('d-none');

            $.ajax({
                url: 'fetch_images.php',
                method: 'POST',
                data: {
                    folder_id: folderId
                },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.length > 0) {
                        $('#folderEmptyState').addClass('d-none');
                        $('#uploadImageButton').removeClass('d-none');
                        $('#dropHint').addClass('d-none'); // HIDE the drag/drop hint

                        data.forEach(img => {
                            $('#imageContainer').append(`
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-img-src="uploads/${folderName}/${img.filename}">
                            <img src="uploads/${folderName}/${img.filename}" class="card-img-top img-thumbnail" style="object-fit: cover; width: 100%; height: 200px;" alt="Image">
                        </a>
                        <div class="card-body p-2 text-center">
                            <p class="card-text mb-0 text-truncate" title="${img.filename}">${img.filename}</p>
                        </div>
                    </div>
                </div>
            `);
                        });
                    } else {
                        $('#folderEmptyState').removeClass('d-none');
                        $('#uploadImageButton').addClass('d-none');
                        $('#dropHint').removeClass('d-none'); // SHOW the hint again if no images
                    }
                },
                error: function() {
                    console.log('Error fetching images');
                }
            });
        });
    </script> -->

    <!--Show Image-->
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