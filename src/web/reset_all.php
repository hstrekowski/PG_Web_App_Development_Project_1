<?php
require_once 'business.php';
session_start();

$db = get_db();


$db->images->drop(); 
$db->users->drop();  

function clearDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    
    
    $files = glob($dir . '*'); 
    
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file); 
        }
    }
}


clearDirectory('images/');
clearDirectory('ProfilesFoto/');

$_SESSION = [];
session_destroy();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Reset zakończony</title>
</head>
<body>
    <p>Reset zakończony</p>
    <a href="index.php">Wróć do strony głównej</a>
</body>
</html>