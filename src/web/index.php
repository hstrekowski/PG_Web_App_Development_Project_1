<?php
// index.php
session_start(); // --- TO JEST KLUCZOWE ---

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'routing.php';
require_once 'controllers.php';

$action = $_GET['action'] ?? '';
dispatch('/' . $action);
?>