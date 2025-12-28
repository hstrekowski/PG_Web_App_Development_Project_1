<?php
// business.php

// 1. AUTOLOAD - Szukamy biblioteki w różnych miejscach
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} else {
    die("Błąd: Nie znaleziono biblioteki MongoDB (vendor/autoload.php).");
}

// 2. POŁĄCZENIE Z BAZĄ
function get_db() {
    try {
        $mongo = new MongoDB\Client(
            "mongodb://localhost:27017/wai",
            [
                'username' => 'wai_web',
                'password' => 'w@i_w3b',
            ]
        );
        return $mongo->wai;
    } catch (Exception $e) {
        die("Błąd połączenia z bazą: " . $e->getMessage());
    }
}

// 3. GENEROWANIE MINIATURKI (GD)
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

// 4. UPLOAD PLIKU
function upload_image_business_logic($file) {
    $messages = [];
    $uploadDir = 'images/';

    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = basename($file['name']);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    if ($file['size'] > 1048576) $messages[] = "Błąd: Plik za duży (max 1MB).";
    
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    if (!in_array($fileType, $allowedTypes)) $messages[] = "Błąd: Zły format (tylko JPG, PNG).";

    if (empty($messages)) {
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            $thumbName = "thumbnail_" . $fileName;
            $thumbPath = $uploadDir . $thumbName;
            
            createThumbnail($targetFilePath, $thumbPath, $fileType);
            
            try {
                $db = get_db();
                $db->images->insertOne([
                    'original_name' => $fileName,
                    'thumbnail_name' => $thumbName
                ]);
                $messages[] = "Sukces: Zdjęcie zapisane w bazie!";
            } catch (Exception $e) {
                $messages[] = "Błąd bazy: " . $e->getMessage();
            }
        } else {
            $messages[] = "Błąd zapisu pliku na dysku.";
        }
    }
    return $messages;
}

// 5. POBIERANIE DANYCH (PAGINACJA)
function get_paginated_images($page, $perPage) {
    try {
        $db = get_db();
        $skip = ($page - 1) * $perPage;
        
        $options = [
            'skip' => $skip,
            'limit' => $perPage,
            'sort' => ['_id' => -1]
        ];
        
        $cursor = $db->images->find([], $options);
        
        // Kompatybilność z różnymi wersjami biblioteki
        if (method_exists($db->images, 'countDocuments')) {
            $total = $db->images->countDocuments();
        } else {
            $total = $db->images->count();
        }

        return ['images' => $cursor->toArray(), 'total' => $total];
    } catch (Exception $e) {
        return ['images' => [], 'total' => 0];
    }
}
?>