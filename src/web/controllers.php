<?php
// controllers.php

require_once 'business.php';

function gallery_action() {
    $model = []; 
    $view_name = 'gallery_view';
    
    include 'views/layout.php'; 
}

function upload_action() {
    $model = [];
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
        $result_messages = upload_image_business_logic($_FILES['photo']);
        $model['messages'] = $result_messages;
    }
    
    $view_name = 'gallery_view';
    include 'views/layout.php';
}
?>