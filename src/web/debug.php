<?php
echo "<h2>Diagnostyka Ścieżek</h2>";
echo "Mój katalog (bieżący): " . __DIR__ . "<br><br>";

// Lista miejsc, gdzie może być biblioteka
$sciezki = [
    __DIR__ . '/vendor/autoload.php',
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../vendor/autoload.php',
    '/var/www/dev/vendor/autoload.php',
    '/var/www/html/vendor/autoload.php'
];

$znaleziono = false;

foreach ($sciezki as $sciezka) {
    if (file_exists($sciezka)) {
        echo "<strong style='color:green'>[ZNALEZIONO]</strong> Plik autoload jest tutaj: $sciezka <br>";
        require_once $sciezka;
        $znaleziono = true;
        
        if (class_exists('MongoDB\Client')) {
            echo "<strong style='color:green'>[SUKCES]</strong> Klasa MongoDB\Client jest dostępna!<br>";
        } else {
            echo "<strong style='color:red'>[BŁĄD]</strong> Plik autoload jest, ale klasy MongoDB nie ma. Biblioteka jest pusta?<br>";
        }
        break; // Kończymy szukanie po pierwszym sukcesie
    } else {
        echo "<span style='color:red'>[BRAK]</span> Nie ma pliku: $sciezka <br>";
    }
}

if (!$znaleziono) {
    echo "<br><strong>WNIOSEK:</strong> Nie masz zainstalowanej biblioteki MongoDB w żadnym typowym miejscu.";
}
?>