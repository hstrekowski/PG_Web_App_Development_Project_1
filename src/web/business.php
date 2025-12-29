<?php
// business.php

// 1. AUTOLOAD
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} else {
    die("Błąd: Nie znaleziono biblioteki MongoDB.");
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

// 3. GENEROWANIE MINIATURKI
function createThumbnail($sourcePath, $destPath, $fileType, $width, $height) {
    list($origWidth, $origHeight) = getimagesize($sourcePath);
    $thumb = imagecreatetruecolor($width, $height);

    if ($fileType === 'jpg' || $fileType === 'jpeg') {
        $source = imagecreatefromjpeg($sourcePath);
    } elseif ($fileType === 'png') {
        $source = imagecreatefrompng($sourcePath);
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
    } else {
        return false;
    }

    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

    if ($fileType === 'jpg' || $fileType === 'jpeg') {
        imagejpeg($thumb, $destPath, 90);
    } elseif ($fileType === 'png') {
        imagepng($thumb, $destPath);
    }

    imagedestroy($thumb);
    imagedestroy($source);
    return true;
}

// 4. UPLOAD ZDJĘCIA DO GALERII
function upload_image_business_logic($file, $title, $author) {
    $messages = [];
    $uploadDir = 'images/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = basename($file['name']);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    if ($file['size'] > 1048576) $messages[] = "Błąd: Plik za duży (max 1MB).";
    if (!in_array($fileType, ['jpg', 'jpeg', 'png'])) $messages[] = "Błąd: Zły format.";

    if (empty($messages)) {
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            $thumbName = "thumbnail_" . $fileName;
            $thumbPath = $uploadDir . $thumbName;
            
            // Miniaturka galerii 200x125
            createThumbnail($targetFilePath, $thumbPath, $fileType, 200, 125);
            
            try {
                $db = get_db();
                $db->images->insertOne([
                    'original_name' => $fileName,
                    'thumbnail_name' => $thumbName,
                    'title' => $title,
                    'author' => $author
                ]);
                $messages[] = "Sukces: Zdjęcie zapisane!";
            } catch (Exception $e) {
                $messages[] = "Błąd bazy: " . $e->getMessage();
            }
        } else {
            $messages[] = "Błąd zapisu pliku.";
        }
    }
    return $messages;
}

// 5. PAGINACJA GALERII
function get_paginated_images($page, $perPage) {
    try {
        $db = get_db();
        $skip = ($page - 1) * $perPage;
        $options = ['skip' => $skip, 'limit' => $perPage, 'sort' => ['_id' => -1]];
        $cursor = $db->images->find([], $options);
        
        $total = method_exists($db->images, 'countDocuments') ? $db->images->countDocuments() : $db->images->count();

        return ['images' => $cursor->toArray(), 'total' => $total];
    } catch (Exception $e) {
        return ['images' => [], 'total' => 0];
    }
}

// 6. LOGIKA UŻYTKOWNIKÓW (REJESTRACJA/LOGOWANIE)
function register_user($login, $email, $password, $file) {
    $db = get_db();
    
    $existing = $db->users->findOne(['login' => $login]);
    if ($existing) {
        return "Błąd: Login jest już zajęty.";
    }

    $profileDir = 'ProfilesFoto/';
    if (!is_dir($profileDir)) mkdir($profileDir, 0777, true);

    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $uniqueName = uniqid() . '.' . $fileType;
    $tempPath = $file['tmp_name'];
    $destPath = $profileDir . $uniqueName;

    if ($file['size'] > 1048576) return "Błąd: Zdjęcie profilowe za duże.";
    if (!in_array($fileType, ['jpg', 'jpeg', 'png'])) return "Błąd: Zły format zdjęcia.";

    // Miniaturka profilowa 100x100
    if (!createThumbnail($tempPath, $destPath, $fileType, 100, 100)) {
        return "Błąd przetwarzania zdjęcia profilowego.";
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $db->users->insertOne([
            'login' => $login,
            'email' => $email,
            'password' => $hash,
            'profile_image' => $uniqueName
        ]);
        return true;
    } catch (Exception $e) {
        return "Błąd bazy: " . $e->getMessage();
    }
}

function login_user($login, $password) {
    $db = get_db();
    $user = $db->users->findOne(['login' => $login]);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (string)$user['_id'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['user_avatar'] = $user['profile_image'];
        return true;
    }
    return false;
}

// 7. POBIERANIE WYBRANYCH ZDJĘĆ (DO KOSZYKA)
function get_images_by_ids($ids) {
    if (empty($ids)) return [];
    
    $db = get_db();
    try {
        $mongoIds = [];
        foreach ($ids as $id) {
            $mongoIds[] = new MongoDB\BSON\ObjectId($id);
        }
        $query = ['_id' => ['$in' => $mongoIds]];
        $cursor = $db->images->find($query);
        return $cursor->toArray();
    } catch (Exception $e) {
        return [];
    }
}
?>