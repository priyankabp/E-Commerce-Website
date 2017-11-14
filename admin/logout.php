<?php
    require_once '../core/init.php';
    unset($_SESSION['User']);
    header('location: login.php');
?>