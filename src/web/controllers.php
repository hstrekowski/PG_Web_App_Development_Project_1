<?php
require_once 'business.php';

function gallery_action() {
    $model = [];
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    $perPage = 3; 
    
    $data = get_paginated_images($page, $perPage);
    $model['images'] = $data['images'];
    $model['page'] = $page;
    $model['total_pages'] = ceil($data['total'] / $perPage);
    
    $view_name = 'gallery_view';
    include 'views/layout.php'; 
}

function upload_action() {
    $model = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
        $title = $_POST['title'] ?? 'Bez tytułu';
        $author = $_POST['author'] ?? 'Nieznany';
        $model['messages'] = upload_image_business_logic($_FILES['photo'], $title, $author);
    }
    
    // Powrót do galerii
    $data = get_paginated_images(1, 3);
    $model['images'] = $data['images'];
    $model['page'] = 1;
    $model['total_pages'] = ceil($data['total'] / 3);
    $view_name = 'gallery_view';
    include 'views/layout.php';
}

// --- NOWE KONTROLERY ---

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
                // Sukces - przekieruj do logowania
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

function login_action() {
    // Jeśli zalogowany, nie pokazuj formularza
    if (isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $pass = $_POST['password'];

        if (login_user($login, $pass)) {
            // Sukces
            header("Location: index.php");
            exit;
        } else {
            $model['error'] = "Błędny login lub hasło.";
        }
    }
    
    // Komunikat po rejestracji
    if (isset($_GET['registered'])) {
        $model['success'] = "Rejestracja udana! Zaloguj się.";
    }

    $view_name = 'login_view';
    include 'views/layout.php';
}

function logout_action() {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>