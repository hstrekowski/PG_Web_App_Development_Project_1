<?php
require_once 'business.php';

// 1. GALERIA (FILTROWANIE PRYWATNOŚCI)
function gallery_action() {
    $model = [];
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $perPage = 3; 
    
    // Sprawdzamy, kto jest zalogowany (żeby pokazać mu jego prywatne fotki)
    $userLogin = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : null;
    
    $data = get_paginated_images($page, $perPage, $userLogin);
    
    $model['images'] = $data['images'];
    $model['page'] = $page;
    $model['total_pages'] = ceil($data['total'] / $perPage);
    
    $model['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    
    $view_name = 'gallery_view';
    include 'views/layout.php'; 
}

// 2. UPLOAD (OBSŁUGA PRYWATNOŚCI)
function upload_action() {
    $model = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
        $title = $_POST['title'] ?? 'Bez tytułu';
        
        // Logika dla zalogowanego vs niezalogowanego
        if (isset($_SESSION['user_id'])) {
            $author = $_SESSION['user_login']; // Autor automatycznie z sesji
            $privacy = $_POST['privacy'] ?? 'public'; // Wybór z radia
        } else {
            $author = $_POST['author'] ?? 'Nieznany';
            $privacy = 'public'; // Niezalogowani zawsze publiczne
        }
        
        $model['messages'] = upload_image_business_logic($_FILES['photo'], $title, $author, $privacy);
    }
    
    // Powrót do galerii (z odświeżeniem danych)
    $page = 1;
    $perPage = 3;
    $userLogin = isset($_SESSION['user_login']) ? $_SESSION['user_login'] : null;
    $data = get_paginated_images($page, $perPage, $userLogin);
    
    $model['images'] = $data['images'];
    $model['page'] = $page;
    $model['total_pages'] = ceil($data['total'] / $perPage);
    $model['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    
    $view_name = 'gallery_view';
    include 'views/layout.php';
}

// 3. REJESTRACJA
function register_action() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $repeat = $_POST['repeat_password'];
        $file = $_FILES['profile_pic'];

        if ($pass !== $repeat) {
            $model['error'] = "Hasła nie są identyczne.";
        } else {
            $result = register_user($login, $email, $pass, $file);
            if ($result === true) {
                header("Location: index.php?action=login&registered=1");
                exit;
            } else {
                $model['error'] = $result;
            }
        }
    }
    $view_name = 'register_view';
    include 'views/layout.php';
}

// 4. LOGOWANIE
function login_action() {
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (login_user($_POST['login'], $_POST['password'])) {
            header("Location: index.php");
            exit;
        } else {
            $model['error'] = "Błędny login lub hasło.";
        }
    }
    if (isset($_GET['registered'])) {
        $model['success'] = "Rejestracja udana! Zaloguj się.";
    }
    $view_name = 'login_view';
    include 'views/layout.php';
}

// 5. WYLOGOWANIE
function logout_action() {
    session_destroy();
    header("Location: index.php");
    exit;
}

// 6. KOSZYK - ZAPAMIĘTAJ
function save_selected_action() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_ids'])) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        foreach ($_POST['selected_ids'] as $id) {
            if (!isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id] = 1; 
            }
        }
    }
    header("Location: index.php");
    exit;
}

// 7. KOSZYK - WIDOK
function saved_action() {
    $model = [];
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    
    if (!empty($cart)) {
        $ids = array_keys($cart);
        $images = get_images_by_ids($ids);
        
        foreach ($images as $img) {
            $id = (string)$img['_id'];
            $img['quantity'] = $cart[$id]; 
            $model['images'][] = $img;
        }
    } else {
        $model['images'] = [];
    }
    
    $view_name = 'saved_view';
    include 'views/layout.php';
}

// 8. KOSZYK - ZMIANY
function remove_selected_action() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['remove_ids'])) {
            foreach ($_POST['remove_ids'] as $id) {
                unset($_SESSION['cart'][$id]);
            }
        }
        if (isset($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $id => $qty) {
                if (isset($_SESSION['cart'][$id]) && (int)$qty > 0) {
                    $_SESSION['cart'][$id] = (int)$qty;
                }
            }
        }
    }
    header("Location: index.php?action=saved");
    exit;
}
?>