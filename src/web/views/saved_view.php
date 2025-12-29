<section class="saved-section">
    <h2>Twoje zapamiętane zdjęcia</h2>
    
    <?php if (!empty($model['images'])): ?>
        <form action="index.php?action=remove_selected" method="POST">
            
            <div class="cart-controls">
                <button type="submit" class="btn-remove">Usuń zaznaczone z zapamiętanych</button>
                <button type="submit" class="btn-update">Zapisz zmiany ilości</button>
            </div>

            <div class="gallery-container">
                <?php foreach ($model['images'] as $img): ?>
                    <?php $id = (string)$img['_id']; ?>
                    <div class="photo-item">
                        <img src="images/<?php echo $img['thumbnail_name']; ?>" alt="Foto">
                        
                        <div class="photo-info">
                            <strong><?php echo htmlspecialchars($img['title'] ?? 'Bez tytułu'); ?></strong>
                            
                            <label class="remove-label">
                                <input type="checkbox" name="remove_ids[]" value="<?php echo $id; ?>">
                                Zaznacz do usunięcia
                            </label>
                            
                            <label class="qty-label">
                                Ilość: 
                                <input type="number" name="quantities[<?php echo $id; ?>]" value="<?php echo $img['quantity']; ?>" min="1">
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
    <?php else: ?>
        <div style="text-align: center; padding: 40px;">
            <p>Nie masz żadnych zapamiętanych zdjęć.</p>
            <a href="index.php" class="btn-back">Wróć do galerii</a>
        </div>
    <?php endif; ?>
</section>