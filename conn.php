<?php
header("Access-Control-Allow-Origin: *");

// Allow specific headers
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require 'vendor/autoload.php';

use MrShan0\PHPFirestore\FirestoreClient;

try
{
    // Initialize Firestore client
    $firestore = new FirestoreClient('bookstore-3bdc6', 'AIzaSyDRv1I2892AVfz8uJ48o80JsDCI8dsOV4M', [
        'database' => '(default)',
    ]);

}
catch (Exception $e)
{
    // Handle any errors
    echo 'Error: ' . $e->getMessage();
}
?>