<?php
session_start();
include_once("conn.php");

if (isset($_GET['id']))
{
    $id = $_GET['id'];

    $documentPath = 'books/' . $id;
    $delete       = $firestore->deleteDocument($documentPath);

    if ($delete)
    {
        $_SESSION['bookDelete'] = "Book Deleted Successfully!";
        header("location: listBooks.php");
    }
    else
    {
        $_SESSION['bookDeleteErr'] = "Couldn't Delete Book!";
        header("location: listBooks.php");

    }
}

?>