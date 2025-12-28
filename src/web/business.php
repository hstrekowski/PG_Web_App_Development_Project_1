<?php
// business.php

function createThumbnail($sourcePath, $destPath, $fileType) {
    list($width, $height) = getimagesize($sourcePath);
    $newWidth = 200;
    $newHeight = 125;
    $thumb = imagecreatetruecolor($newWidth, $newHeight);

    if ($fileType === 'jpg' || $fileType === 'jpeg') {
        $source = imagecreatefromjpeg($sourcePath);
    } elseif ($fileType === 'png') {
        $source = imagecreatefrompng($sourcePath);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
    } else {
        return false;
    }

    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if ($fileType === 'jpg' || $fileType === 'jpeg') {
        imagejpeg($thumb, $destPath, 90);
    } elseif ($fileType === 'png') {
        imagepng($thumb, $destPath);
    }

    imagedestroy($thumb);
    imagedestroy($source);
    return true;
}

function upload_image_business_logic($file) {
    $messages = [];
    $uploadDir = 'images/';

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = basename($file['name']);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    if ($file['size'] > 1048576) {
        $messages[] = "Błąd: Plik za duży (max 1MB).";
    }
    
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    if (!in_array($fileType, $allowedTypes)) {
        $messages[] = "Błąd: Zły format (tylko JPG, PNG).";
    }

    if (empty($messages)) {
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            $thumbnailPath = $uploadDir . "thumbnail_" . $fileName;
            createThumbnail($targetFilePath, $thumbnailPath, $fileType);
            $messages[] = "Sukces: Zdjęcie przesłane!";
        } else {
            $messages[] = "Błąd: Awaria zapisu na serwerze.";
        }
    }

    return $messages;
}
?>