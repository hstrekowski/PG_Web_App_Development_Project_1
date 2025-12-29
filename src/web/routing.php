<?php
function dispatch($url) {
    switch ($url) {
        case '/upload':
            upload_action();
            break;
        case '/register':
            register_action();
            break;
        case '/login':
            login_action();
            break;
        case '/logout':
            logout_action();
            break;
        case '/save_selected':
            save_selected_action();
            break;
        case '/saved':
            saved_action();
            break;
        case '/remove_selected':
            remove_selected_action();
            break;
        default:
            gallery_action();
    }
}
?>