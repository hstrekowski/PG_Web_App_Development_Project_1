<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Galeria Książek</title>
    <link rel="stylesheet" href="static/style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <div class="header-container">
            
            <div class="header-left">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-info">
                        <img src="ProfilesFoto/<?php echo $_SESSION['user_avatar']; ?>" alt="Avatar" class="avatar-circle">
                        <span class="username"><?php echo htmlspecialchars($_SESSION['user_login']); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="header-center">
                <a href="index.php" class="site-title">Galeria Książek</a>
            </div>

            <div class="header-right">
                
                <?php include __DIR__ . '/cart_partial.php'; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=logout" class="btn btn-logout">Wyloguj</a>
                <?php else: ?>
                    <a href="index.php?action=login" class="link-text">Logowanie</a>
                    <a href="index.php?action=register" class="link-text">Rejestracja</a>
                <?php endif; ?>
            </div>

        </div>
    </header>

    <main>
        <?php include __DIR__ . '/' . $view_name . '.php'; ?>
    </main>

    <footer>
        <p>&copy; 2025 Wirtualna Biblioteka. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>