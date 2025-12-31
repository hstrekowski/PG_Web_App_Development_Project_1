<form action="index.php?action=save_selected" method="POST">
    
    <div class="actions-bar" style="margin-bottom: 20px; text-align: right;">
        <button type="submit" class="btn-save">Zapamiętaj wybrane</button>
    </div>

    <div class="gallery-container">
        <?php if (!empty($model['images'])): ?>
            <?php foreach ($model['images'] as $img): ?>
                <div class="photo-item">
                    <a href="images/<?php echo $img['original_name']; ?>" target="_blank">
                        <img src="images/<?php echo $img['thumbnail_name']; ?>" alt="Okładka">
                    </a>
                    
                    <div class="photo-info">
                        <?php 
                        $id = (string)$img['_id'];
                        $isChecked = isset($model['cart'][$id]) ? 'checked' : '';
                        ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="selected_ids[]" value="<?php echo $id; ?>" <?php echo $isChecked; ?>>
                            <span>Wybierz pozycję</span>
                        </label>
                        
                        <?php 
                        $title = isset($img['title']) ? htmlspecialchars($img['title']) : 'Brak tytułu';
                        $author = isset($img['author']) ? htmlspecialchars($img['author']) : 'Anonim';
                        ?>
                        <div class="meta-data">
                            <strong><?php echo $title; ?></strong>
                            <small>Autor: <?php echo $author; ?></small>

                            <?php if (isset($img['privacy']) && $img['privacy'] === 'private'): ?>
                                <small style="display: block; color: #e57373; font-weight: bold; margin-top: 5px; font-size: 11px;">
                                    🔒 Tylko dla Ciebie
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; text-align: center; color: #888;">Biblioteka jest pusta.</p>
        <?php endif; ?>
    </div>

    <?php if (isset($model['total_pages']) && $model['total_pages'] > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $model['total_pages']; $i++): ?>
                <a href="index.php?page=<?php echo $i; ?>" class="<?php echo $i == $model['page'] ? 'active' : ''; ?>">
                   <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</form>

<hr class="divider">

<section class="upload-section">
    <h2>Dodaj nową książkę</h2>
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
        <label>Tytuł książki: <input type="text" name="title" required placeholder="Wpisz tytuł..."></label>

        <?php if (isset($_SESSION['user_id'])): ?>
            <label>Autor dodający: 
                <input type="text" name="author" value="<?php echo $_SESSION['user_login']; ?>" readonly style="cursor: not-allowed; opacity: 0.7;">
            </label>
            
            <div class="privacy-settings" style="text-align: left; padding: 15px; border-radius: 4px;">
                <span style="font-weight: bold; display: block; margin-bottom: 8px; color: #ccc;">Widoczność w bibliotece:</span>
                <label style="display: inline-block; margin-right: 15px; cursor: pointer; font-weight: normal; background: none; border: none; padding: 0;">
                    <input type="radio" name="privacy" value="public" checked> Publiczna
                </label>
                <label style="display: inline-block; cursor: pointer; font-weight: normal; background: none; border: none; padding: 0;">
                    <input type="radio" name="privacy" value="private"> Prywatna
                </label>
            </div>

        <?php else: ?>
            <label>Autor / Użytkownik: <input type="text" name="author" required placeholder="Twój nick..."></label>
            <small>Jako gość dodajesz pozycje do katalogu publicznego.</small>
        <?php endif; ?>

        <label style="margin-top: 10px;">Okładka (plik graficzny):
            <input type="file" name="photo" required>
        </label>
        
        <button type="submit">Dodaj do zbiorów</button>
        <small>Max 1MB, formaty JPG/PNG</small>
    </form>
</section>