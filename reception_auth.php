<?php
session_start();
include('db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'receptionist') {
    header('Location: login.php');
    exit();
}
?>