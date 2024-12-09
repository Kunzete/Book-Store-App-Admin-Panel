<?php
if (isset($_SESSION['user_email']))
{
    header('location: dashboard.php');
    exit();
}