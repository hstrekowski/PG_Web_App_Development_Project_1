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
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="grid-column: 1/-1; text-align: center;">Brak zdjęć w bazie.</p>
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
        <label>Autor: <input type="text" name="author" required value="<?php echo isset($_SESSION['user_login']) ? $_SESSION['user_login'] : ''; ?>"></label>
        <input type="file" name="photo" required>
        <button type="submit">Wyślij</button>
        <small>Max 1MB, JPG/PNG</small>
    </form>
</section>