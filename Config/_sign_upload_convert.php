<?php

$imageProcess = 0;
$out_imageConvert = '';
$uploadPath = "./../OpenAccountBVN/signature_uploads/";

// Create directory if it doesn't exist
if (!file_exists($uploadPath)) {
    mkdir($uploadPath, 0755, true);
}

if (is_array($_FILES) && isset($_FILES['upload_sign'])) {

    if (isset($_FILES['upload_sign']) && $_FILES['upload_sign']['error'] === UPLOAD_ERR_OK) {       
    // Validate file upload
        die("Upload failed with error code: " . $_FILES['upload_image']['error']);
    }

    $fileName = $_FILES['upload_sign']['tmp_name'];
    
    // Verify the file is actually an image
    $validExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    $fileExt = strtolower(pathinfo($_FILES['upload_sign']['name'], PATHINFO_EXTENSION));
    
    if (!in_array($fileExt, $validExtensions)) {
        die("Invalid image file type");
    }

    // Verify MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $fileName);
    finfo_close($finfo);
    
    $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($mime, $validMimeTypes)) {
        die("Invalid MIME type");
    }

    $sourceProperties = getimagesize($fileName);
    if (!$sourceProperties) {
        die("Invalid image file");
    }

    $resizeFileName = time() . '_' . bin2hex(random_bytes(4)); // More secure random name
    $uploadImageType = $sourceProperties[2];

    switch ($uploadImageType) {
        case IMAGETYPE_JPEG:
            $resourceType = imagecreatefromjpeg($fileName);
            break;
        case IMAGETYPE_GIF:
            $resourceType = imagecreatefromgif($fileName);
            break;
        case IMAGETYPE_PNG:
            $resourceType = imagecreatefrompng($fileName);
            break;
        default:
            die("Unsupported image type");
    }

    if (!$resourceType) {
        die("Failed to create image resource");
    }

    // Resize the image
    $imageLayer = resizeImage($resourceType, $sourceProperties[0], $sourceProperties[1]);
    
    // Save thumbnail
    $thumbnailPath = $uploadPath . "thumb_" . $resizeFileName . '.' . $fileExt;
    switch ($uploadImageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($imageLayer, $thumbnailPath, 85); // 85% quality
            break;
        case IMAGETYPE_GIF:
            imagegif($imageLayer, $thumbnailPath);
            break;
        case IMAGETYPE_PNG:
            imagepng($imageLayer, $thumbnailPath, 6); // Compression level 6
            break;
    }

    // Save original
    $originalPath = $uploadPath . $resizeFileName . '.' . $fileExt;
    if (!move_uploaded_file($fileName, $originalPath)) {
        die("Failed to save original image");
    }

    // Get thumbnail content
    $out_image = file_get_contents($thumbnailPath);
    $out_signConvert = base64_encode($out_image);
    
    // Clean up resources
    imagedestroy($resourceType);
    imagedestroy($imageLayer);
    
    $imageProcess = 1;
}