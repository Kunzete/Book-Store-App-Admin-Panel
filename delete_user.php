<?php
session_start();
require_once("auth.php");
require_once("conn.php");

if (isset($_GET['id']))
{
    $id = $_GET['id'];

    try
    {
        // Attempt to delete the user from Firebase Authentication
        $collection = 'user/' . $id;
        $firestore->deleteDocument($collection);
        // Attempt to delete the user document from Firestore
        try
        {
            $auth->deleteUser($id);
            $_SESSION['success_message'] = "User  deleted successfully!";
            header("location: listUser.php");
            exit();
        }
        catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e)
        {
            $_SESSION['error_message'] = "Firestore document not found: " . $e->getMessage();
            header("location: listUser.php");
            exit();
        }
    }
    catch (\MrShan0\PHPFirestore\Exceptions\Client\NotFound $e)
    {
        $_SESSION['error_message'] = "User  not found: " . $e->getMessage();
        header("location: listUser.php");
        exit();
    }
    catch (Exception $e)
    {
        $_SESSION['error_message'] = "An error occurred: " . $e->getMessage();
        header("location: listUser.php");
        exit();
    }
}
else
{
    $_SESSION['error_message'] = "No user ID provided.";
    header("location: listUser.php");
    exit();
}