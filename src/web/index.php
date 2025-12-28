<?php
// Logika PHP do obsługi uploadu zdjęć
$messages = []; // Tablica na komunikaty (sukces lub błędy)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $file = $_FILES['photo'];
    $uploadDir = 'images/';
    
    // Sprawdź czy folder images istnieje, jeśli nie - spróbuj utworzyć (opcjonalne, ale pomocne)
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($file['name']);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    
    // 1. Walidacja rozmiaru (max 1MB = 1048576 bajtów)
    if ($file['size'] > 1048576) {
        $messages[] = "Błąd: Plik jest za duży! Maksymalny rozmiar to 1 MB.";
    }

    // 2. Walidacja formatu (tylko JPG i PNG)
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    if (!in_array($fileType, $allowedTypes)) {
        $messages[] = "Błąd: Nieprawidłowy format pliku! Dozwolone są tylko pliki JPG i PNG.";
    }

    // Jeśli nie ma błędów, próbujemy zapisać plik
    if (empty($messages)) {
        if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            $messages[] = "Sukces: Zdjęcie zostało przesłane poprawnie!";
            // Tutaj w przyszłości dodamy kod zapisujący nazwę pliku do MongoDB
        } else {
            $messages[] = "Błąd: Wystąpił problem podczas zapisywania pliku na serwerze (sprawdź uprawnienia folderu images).";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moja Galeria Zdjęć - Książki</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Galeria Projektowa</h1>
    </header>

    <main>
        <section class="upload-section">
            <h2>Dodaj nowe zdjęcie</h2>
            
            <?php if (!empty($messages)): ?>
                <div class="messages">
                    <?php foreach ($messages as $msg): ?>
                        <p class="<?php echo strpos($msg, 'Sukces') !== false ? 'success' : 'error'; ?>">
                            <?php echo $msg; ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="index.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="photo" required>
                <button type="submit">Wyślij na serwer</button>
                <small>Dozwolone: JPG, PNG. Max: 1MB.</small>
            </form>
        </section>

        <hr class="divider">

        <div class="gallery-container">
            <div class="photo-placeholder">1</div>
            <div class="photo-placeholder">2</div>
            <div class="photo-placeholder">3</div>
            <div class="photo-placeholder">4</div>
            <div class="photo-placeholder">5</div>
            <div class="photo-placeholder">6</div>
            <div class="photo-placeholder">7</div>
            <div class="photo-placeholder">8</div>
            <div class="photo-placeholder">9</div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Hubert Strękowski 208381</p>
    </footer>

</body>
</html>