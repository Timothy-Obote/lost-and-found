<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the class.upload.php library
require_once 'src/class.upload.php';

// Check if a file has been uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image_field'])) {
    $handle = new \Verot\Upload\Upload($_FILES['image_field']);

    if ($handle->uploaded) {
        $handle->file_new_name_body = 'image_resized';
        $handle->image_resize = true;
        $handle->image_x = 100;
        $handle->image_ratio_y = true;

        // Set upload directory (ensure it exists)
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $handle->process($uploadDir);

        if ($handle->processed) {
            echo 'Image resized and uploaded successfully!';
            $handle->clean();
        } else {
            echo 'Error: ' . $handle->error;
        }
    } else {
        echo 'File not uploaded';
    }
}
?>