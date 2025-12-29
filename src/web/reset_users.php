<?php
require_once 'business.php';

// Musimy wystartować sesję, żeby móc ją zniszczyć (wylogować)
session_start();

$db = get_db();

// 1. Usuwamy kolekcję użytkowników z bazy
$db->users->drop();

// 2. Usuwamy fizyczne pliki z folderu ProfilesFoto
$folder = 'ProfilesFoto/';

if (is_dir($folder)) {
    // Pobierz wszystkie pliki z folderu
    $files = glob($folder . '*'); 
    
    foreach ($files as $file) {
        // Sprawdź czy to plik (a nie np. katalog ukryty) i usuń
        if (is_file($file)) {
            unlink($file);
        }
    }
}

// 3. Niszczymy sesję (wylogowanie)
session_destroy();

echo "<h2>Sukces!</h2>";
echo "Baza użytkowników wyczyszczona.<br>";
echo "Folder ProfilesFoto wyczyszczony.<br>";
echo "Zostałeś wylogowany.<br><br>";
echo "<a href='index.php'>Wróć do strony głównej</a>";
?>