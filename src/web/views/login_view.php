<section class="auth-section">
    <h2>Logowanie</h2>

    <?php if (isset($model['success'])): ?>
        <p class="success"><?php echo $model['success']; ?></p>
    <?php endif; ?>
    
    <?php if (isset($model['error'])): ?>
        <p class="error"><?php echo $model['error']; ?></p>
    <?php endif; ?>

    <form action="index.php?action=login" method="POST">
        <label>Login: <input type="text" name="login" required></label>
        <label>Hasło: <input type="password" name="password" required></label>
        <button type="submit">Zaloguj się</button>
    </form>
</section>