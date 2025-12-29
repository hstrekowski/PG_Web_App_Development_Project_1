<section class="upload-section">
    <h2>Dodaj zdjęcie</h2>
    <?php if (isset($model['messages'])): ?>
        <div class="messages">
            <?php foreach ($model['messages'] as $msg): ?>
                <p class="<?php echo strpos($msg, 'Sukces') !== false ? 'success' : 'error'; ?>">
                    <?php echo $msg; ?>
                </p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="index.php?action=upload" method="POST" enctype="multipart/form-data">
        <label>
            Tytuł: <input type="text" name="title" required placeholder="np. Wakacje 2024">
        </label>
        <label>
            Autor: <input type="text" name="author" required placeholder="np. Jan Kowalski" value="<?php echo isset($_SESSION['user']) ? $_SESSION['user'] : ''; ?>"> 
            </label>
        
        <input type="file" name="photo" required>
        <button type="submit">Wyślij</button>
        <small>Max 1MB, JPG/PNG</small>
    </form>
</section>

<hr class="divider">

<div class="gallery-container">
    <?php if (!empty($model['images'])): ?>
        <?php foreach ($model['images'] as $img): ?>
            <div class="photo-item">
                <a href="images/<?php echo $img['original_name']; ?>" target="_blank">
                    <img src="images/<?php echo $img['thumbnail_name']; ?>" alt="Foto">
                </a>
                
                <div class="photo-info">
                    <?php 
                    // Używamy htmlspecialchars dla bezpieczeństwa (ochrona przed XSS)
                    $title = isset($img['title']) ? htmlspecialchars($img['title']) : 'Brak tytułu';
                    $author = isset($img['author']) ? htmlspecialchars($img['author']) : 'Anonim';
                    ?>
                    <strong><?php echo $title; ?></strong><br>
                    <small>Autor: <?php echo $author; ?></small>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="grid-column: 1/-1; text-align: center;">Brak zdjęć w bazie.</p>
    <?php endif; ?>
</div>

<?php if (isset($model['total_pages']) && $model['total_pages'] > 1): ?>
    <div style="text-align: center; margin-top: 20px;">
        <?php for ($i = 1; $i <= $model['total_pages']; $i++): ?>
            <a href="index.php?page=<?php echo $i; ?>" 
               style="padding: 5px 10px; margin: 0 2px; background: <?php echo $i == $model['page'] ? '#333' : '#ddd'; ?>; color: <?php echo $i == $model['page'] ? '#fff' : '#000'; ?>; text-decoration: none;">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>