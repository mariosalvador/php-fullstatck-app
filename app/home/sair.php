<?php
    session_start();
    unset( $_SESSION['name']);
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    header('Location: ./../login/login.php');
    exit();
?>
