$(document).ready(function () {
    $('.folder-name').on('click', function () {
        const folderId = $(this).data('folder-id');

        $('#albumImages').html('<p class="text-muted">Loading...</p>');  // Show loading message

        $.ajax({
            url: 'display.php',  // Make sure this path is correct
            type: 'GET',
            data: { folder_id: folderId },  // Pass the folder ID to get images
            success: function (data) {
                $('#albumImages').html(data);  // Show images in the modal
                $('#viewAlbumModal').modal('show');  // Show the modal
            },
            error: function () {
                $('#albumImages').html('<p class="text-danger">Failed to load images.</p>');  // Error message
            }
        });
    });
});
