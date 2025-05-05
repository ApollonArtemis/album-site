<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>Responsive Photo Album</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .blankslate {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .blankslate-top-img {
            max-width: 150px;
        }

        .blankslate-actions button {
            margin-top: 15px;
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

    <!-- Offcanvas Sidebar (small screens) -->
    <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">📁 Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <a href="#" class="menu-link active" data-target="dashboard">🏠 Dashboard</a>
            <a href="#" class="menu-link" data-target="favorites">⭐ Favorites</a>
            <a href="#" class="menu-link" data-target="trash">🗑️ Trash</a>
            <a href="#" class="menu-link" data-target="archive">📦 Archive</a>
            <a href="#" class="menu-link" data-target="settings">⚙️ Settings</a>
        </div>
    </div>

    <!-- Sidebar for large screens -->
    <div class="sidebar d-none d-md-block">
        <h4 class="text-center">📁 Album</h4>
        <a href="#" class="menu-link active" data-target="dashboard">🏠 Dashboard</a>
        <a href="#" class="menu-link" data-target="favorites">⭐ Favorites</a>
        <a href="#" class="menu-link" data-target="trash">🗑️ Trash</a>
        <a href="#" class="menu-link" data-target="archive">📦 Archive</a>
        <a href="#" class="menu-link" data-target="settings">⚙️ Settings</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Dashboard Section (default visible) -->
        <div id="dashboard" class="content-section active">
            <h2>📷 My Photo Album (Dashboard)</h2>

            <!-- Add New Album Button (visible after folder creation) -->
            <button class="btn btn-primary d-flex float-end mb-3 d-none" id="addAlbumButton" data-bs-toggle="modal" data-bs-target="#addAlbumModal">+ Add New Album</button>

            <!-- Display Empty State if no folders are created -->
            <div class="blankslate mw-100 text-center mt-5 pt-5 d-none" id="emptyState">
                <img class="blankslate-top-img w-25" src="images/empty-folder.png" />
                <h4 class="text-secondary">No Albums Here</h4>
                <p>Click the button below to add a new album.</p>
                <div class="blankslate-actions">
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#addAlbumModal">
                        + Add New Album
                    </button>
                </div>
            </div>

            <!-- Display Folders -->
            <div class="row mt-5" id="folderContainer">
                <?php
                $folders = $conn->query("SELECT * FROM folders");
                if ($folders->num_rows > 0):
                    // Display each folder
                    while ($row = $folders->fetch_assoc()):
                ?>
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5>
                                        <a href="folder.php?id=<?= $row['id'] ?>" class="text-decoration-none">
                                            <?= htmlspecialchars($row['name']) ?>
                                        </a>
                                    </h5>
                                </div>
                            </div>
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
                            <button type="submit" class="btn btn-primary">Create Album</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Placeholder Sections (Favorites, Trash, Archive, Settings) -->
        <div id="favorites" class="content-section">
            <h2>⭐ Favorites</h2>
            <p>Feature coming soon...</p>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>