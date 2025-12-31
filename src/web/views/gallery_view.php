<form action="index.php?action=save_selected" method="POST">
    
    <div class="actions-bar" style="margin-bottom: 20px; text-align: right;">
        <button type="submit" class="btn-save">Zapamiętaj wybrane</button>
    </div>

    <div class="gallery-container">
        <?php if (!empty($model['images'])): ?>
            <?php foreach ($model['images'] as $img): ?>
                <div class="photo-item">
                    <a href="images/<?php echo $img['original_name']; ?>" target="_blank">
                        <img src="images/<?php echo $img['thumbnail_name']; ?>" alt="Foto">
                    </a>
                    
                    <div class="photo-info">
                        <?php 
                        $id = (string)$img['_id'];
                        $isChecked = isset($model['cart'][$id]) ? 'checked' : '';
                        ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="selected_ids[]" value="<?php echo $id; ?>" <?php echo $isChecked; ?>>
                            <span>Wybierz</span>
                        </label>
                        
                        <?php 
                        $title = isset($img['title']) ? htmlspecialchars($img['title']) : 'Brak tytułu';
                        $author = isset($img['author']) ? htmlspecialchars($img['author']) : 'Anonim';
                        ?>
                        <div class="meta-data">
                            <strong><?php echo $title; ?></strong>
                            <small>Autor: <?php echo $author; ?></small>

                            <?php if (isset($img['privacy']) && $img['privacy'] === 'private'): ?>
                                <small style="display: block; color: #d9534f; font-weight: bold; margin-top: 5px;">
                                    🔒 Zdjęcie prywatne
                                </small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; text-align: center;">Brak zdjęć do wyświetlenia.</p>
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
        <label>Tytuł: <input type="text" name="title" required></label>

        <?php if (isset($_SESSION['user_id'])): ?>
            <label>Autor: 
                <input type="text" name="author" value="<?php echo $_SESSION['user_login']; ?>" readonly style="background-color: #e9ecef; cursor: not-allowed;">
            </label>
            
            <div class="privacy-settings" style="text-align: left; background: #f8f9fa; padding: 10px; border-radius: 4px; border: 1px solid #eee;">
                <span style="font-weight: bold; display: block; margin-bottom: 5px;">Widoczność zdjęcia:</span>
                <label style="display: inline-block; margin-right: 15px; cursor: pointer; font-weight: normal;">
                    <input type="radio" name="privacy" value="public" checked> Publiczne
                </label>
                <label style="display: inline-block; cursor: pointer; font-weight: normal;">
                    <input type="radio" name="privacy" value="private"> Prywatne
                </label>
            </div>

        <?php else: ?>
            <label>Autor: <input type="text" name="author" required></label>
            <small style="color: #666;">Jako niezalogowany dodajesz zdjęcia publicznie.</small>
        <?php endif; ?>

        <input type="file" name="photo" required style="margin-top: 10px;">
        <button type="submit">Wyślij</button>
        <small>Max 1MB, JPG/PNG</small>
    </form>
</section>