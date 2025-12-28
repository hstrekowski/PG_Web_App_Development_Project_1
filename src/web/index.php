<?php
// index.php

require_once 'routing.php';
require_once 'controllers.php';

$action_url = $_GET['action'] ?? '';

dispatch('/' . $action_url);
?>