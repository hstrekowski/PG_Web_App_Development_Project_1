<section class="upload-section">
    <h2>Dodaj nowe zdjęcie</h2>
    
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