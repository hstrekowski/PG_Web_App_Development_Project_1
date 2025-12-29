<section class="auth-section">
    <h2>Rejestracja</h2>
    
    <?php if (isset($model['error'])): ?>
        <p class="error"><?php echo $model['error']; ?></p>
    <?php endif; ?>

    <form action="index.php?action=register" method="POST" enctype="multipart/form-data">
        <label>Login: <input type="text" name="login" required></label>
        <label>E-mail: <input type="email" name="email" required></label>
        <label>Hasło: <input type="password" name="password" required></label>
        <label>Powtórz hasło: <input type="password" name="repeat_password" required></label>
        
        <label>Zdjęcie profilowe: 
            <input type="file" name="profile_pic" required>
        </label>
        
        <button type="submit">Zarejestruj się</button>
    </form>
</section>