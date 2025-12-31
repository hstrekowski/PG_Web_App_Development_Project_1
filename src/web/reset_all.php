<?php
require_once 'business.php';
session_start();

$db = get_db();

// ==========================================
// 1. CZYSZCZENIE BAZY DANYCH (MongoDB)
// ==========================================
$db->images->drop(); // Usuwa kolekcję zdjęć
$db->users->drop();  // Usuwa kolekcję użytkowników

// ==========================================
// 2. CZYSZCZENIE PLIKÓW Z DYSKU
// ==========================================

// Funkcja pomocnicza do czyszczenia folderu
function clearDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }
    
    // Pobierz wszystkie pliki w folderze
    $files = glob($dir . '*'); 
    
    foreach ($files as $file) {
        // Sprawdź czy to plik (is_file), żeby nie próbować usunąć katalogu
        if (is_file($file)) {
            unlink($file); // USUWANIE PLIKU
        }
    }
}

// Czyścimy folder galerii
clearDirectory('images/');

// Czyścimy folder profili
clearDirectory('ProfilesFoto/');

// ==========================================
// 3. CZYSZCZENIE SESJI (Wylogowanie)
// ==========================================
$_SESSION = [];
session_destroy();

// ==========================================
// KONIEC
// ==========================================
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Reset zakończony</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; background: #f4f4f4; }
        .box { background: white; padding: 30px; border-radius: 8px; display: inline-block; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h1 { color: #d9534f; }
        a { display: inline-block; margin-top: 20px; padding: 10px 20px; background: #333; color: white; text-decoration: none; border-radius: 4px; }
        a:hover { background: #555; }
    </style>
</head>
<body>
    <div class="box">
        <h1>🧹 Wszystko wyczyszczone!</h1>
        <p>Baza danych jest pusta.</p>
        <p>Folder <code>images/</code> jest pusty.</p>
        <p>Folder <code>ProfilesFoto/</code> jest pusty.</p>
        <p>Zostałeś wylogowany.</p>
        
        <a href="index.php">Wróć do strony głównej</a>
    </div>
</body>
</html>