<?php
require_once 'business.php';

function gallery_action() {
    $model = [];
    
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($page < 1) $page = 1;
    
    // Ustawienie: 3 zdjęcia na stronę
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
        $model['messages'] = upload_image_business_logic($_FILES['photo']);
    }
    
    // Po uploadzie wracamy na 1. stronę galerii
    $data = get_paginated_images(1, 3);
    $model['images'] = $data['images'];
    $model['page'] = 1;
    $model['total_pages'] = ceil($data['total'] / 3);
    
    $view_name = 'gallery_view';
    include 'views/layout.php';
}
?>