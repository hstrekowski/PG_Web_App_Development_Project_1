<?php
require_once 'business.php';

// Łączymy się z bazą
$db = get_db();

// Usuwamy wszystkie dokumenty z kolekcji 'images'
$db->images->drop(); // To niszczy całą kolekcję i czyści ją do zera

echo "Baza wyczyszczona! <a href='index.php'>Wróć do galerii</a>";
?>