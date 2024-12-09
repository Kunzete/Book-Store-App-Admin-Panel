<?php
header("Access-Control-Allow-Origin: *");

// Allow specific headers
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

require __DIR__ . '/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;

try
{
    $firestoreClient = new FirestoreClient([
        'projectId' => 'bookstore-3bdc6',
    ]);

    $serviceAccount = 'D:/Downloads/service-accounts.json';
    $factory        = (new Factory)->withServiceAccount($serviceAccount);
    $auth           = $factory->createAuth();
    $firestore      = $factory->createFirestore();
    $database       = $firestore->database();

}
catch (Exception $e)
{
    echo 'Error: ' . $e->getMessage();
}

?>