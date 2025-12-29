<section class="saved-section">
    <h2>Twoje zapamiętane zdjęcia</h2>
    
    <?php if (!empty($model['images'])): ?>
        <form action="index.php?action=remove_selected" method="POST">
            
            <div class="cart-controls">
                <button type="submit" class="btn-remove">Usuń zaznaczone</button>
                <button type="submit" class="btn-update">Zapisz ilości</button>
            </div>

            <div class="gallery-container">
                <?php foreach ($model['images'] as $img): ?>
                    <?php $id = (string)$img['_id']; ?>
                    <div class="photo-item">
                        <img src="images/<?php echo $img['thumbnail_name']; ?>" alt="Foto">
                        
                        <div class="photo-info">
                            <strong style="font-size: 16px; display: block; margin-bottom: 5px;">
                                <?php echo htmlspecialchars($img['title'] ?? 'Bez tytułu'); ?>
                            </strong>
                            
                            <label class="remove-label">
                                <input type="checkbox" name="remove_ids[]" value="<?php echo $id; ?>">
                                Zaznacz do usunięcia
                            </label>
                            
                            <label class="qty-label">
                                <span>Ilość sztuk:</span>
                                <input type="number" name="quantities[<?php echo $id; ?>]" value="<?php echo $img['quantity']; ?>" min="1">
                            </label>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
    <?php else: ?>
        <div style="text-align: center; padding: 40px;">
            <p style="font-size: 18px; color: #666; margin-bottom: 20px;">Twój koszyk jest pusty.</p>
            <a href="index.php" class="btn-back">Wróć do galerii</a>
        </div>
    <?php endif; ?>
</section>