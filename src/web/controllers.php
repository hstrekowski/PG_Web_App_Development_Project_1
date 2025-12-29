<?php
require_once 'business.php';

// 1. GALERIA
function gallery_action() {
    $model = [];
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $perPage = 3; 
    
    $data = get_paginated_images($page, $perPage);
    
    $model['images'] = $data['images'];
    $model['page'] = $page;
    $model['total_pages'] = ceil($data['total'] / $perPage);
    
    // Przekazujemy koszyk
    $model['cart'] = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    
    $view_name = 'gallery_view';
    include 'views/layout.php'; 
}

// 2. UPLOAD (NAPRAWIONE WYŚWIETLANIE BŁĘDÓW)
function upload_action() {
    $model = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
        $title = $_POST['title'] ?? 'Bez tytułu';
        $author = $_POST['author'] ?? 'Nieznany';
        
        // Tutaj generują się błędy lub sukces
        $model['messages'] = upload_image_business_logic($_FILES['photo'], $title, $author);
    }
    
    // --- ZMIANA: Zamiast redirectu, ładujemy dane galerii i widok ---
    // Dzięki temu zmienna $model['messages'] przetrwa i zostanie wyświetlona
    
    $page = 1; // Po uploadzie wracamy na pierwszą stronę
    $perPage = 3;
    $data = get_paginated_images($page, $perPage);
    
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

// 8. KOSZYK - USUWANIE/ZMIANA
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