<?php
session_start();
include_once("conn.php");
include_once("isNotLogged.php");

if (isset($_GET['id']))
{
    $id = $_GET['id'];

    $collection = "orders/" . $id;
    try
    {
        $deleteDocument = $firestore->deleteDocument($collection);

        if ($deleteDocument)
        {
            $_SESSION['success_message'] = "Order Deleted Successfully!";
            header("location: orders.php");
            exit();
        }
    }
    catch (\MrShan0\PHPFirestore\Exceptions\Client\NotFound $e)
    {
        $_SESSION['error_message'] = "" . $e->getMessage() . "";
        header("location: orders.php");
        exit();
    }
}