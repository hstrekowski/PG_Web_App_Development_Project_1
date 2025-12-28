<?php
function dispatch($url) {
    if ($url == '/upload') {
        upload_action();
    } else {
        gallery_action();
    }
}
?>