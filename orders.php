<?php
session_start();
include_once("conn.php");
include_once("isNotLogged.php");
include_once('./assets/header.php');

$listOfOrders = [];
$getOrders    = $firestore->listDocuments('orders');

foreach ($getOrders['documents'] as $orderDoc)
{
    $fields  = $orderDoc->toArray(); // Use toArray() to get the document data
    $orderId = $fields['orderId'] ?? null;

    if ($orderId)
    { // Ensure orderId is not null
        // Access the shipping address
        $shippingAddress = $fields['shippingAddress']->getData()[0] ?? []; // Get the first element of the array

        // Initialize the order array
        $listOfOrders[$orderId] = [
            'Name' => $shippingAddress['fullName'] ?? 'N/A',
            'City' => $shippingAddress['city'] ?? 'N/A',
            'Street Address' => $shippingAddress['streetAddress'] ?? 'N/A',
            'Postal Code' => $shippingAddress['postalCode'] ?? 'N/A',
            'Books' => [], // Initialize as an empty array
            'Total Price' => $fields['totalPrice'] ?? 'N/A',
            'Status' => $fields['status'] ?? 'N/A',
        ];

        // Extract book titles from the FirestoreObject
        if (!empty($fields['books']))
        {
            foreach ($fields['books'] as $key => $book)
            {
                // Access the book data
                $bookData = $book->getData()[0] ?? []; // Get the first element of the array
                $bookName = $bookData['name'] ?? 'N/A'; // Access the 'name' property

                $listOfOrders[$orderId]['Books'][$key] = [
                    "bookName" => $bookName, // Store the book name
                ];
            }
        }
    }
}
?>
<style>
    .img-responsive {
        width: 100px;
        /* Set a fixed width */
        height: auto;
        /* Maintain aspect ratio */
        max-height: 150px;
        /* Set a maximum height */
        object-fit: cover;
        /* Cover the area while maintaining aspect ratio */
    }

    .dropdown-toggle::after {
        display: none !important;
    }
</style>

<body>
    <?php include_once('./assets/head.php') ?>
    <?php include_once('./assets/sidebar.php') ?>

    <main id="main" class="main">

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <?php
                    if (isset($_SESSION['success_message']))
                    {
                        ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h5><?php
                            echo $_SESSION['success_message'];
                            unset($_SESSION['success_message']);
                            ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php
                    }
                    else if (isset($_SESSION['error_message']))
                    {
                        ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <h5><?php
                                echo $_SESSION['error_message'];
                                unset($_SESSION['error_message']);
                                ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php
                    }
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-between">
                                <h5>List Of orders</h5>
                                <a href="createorder.php" class="btn btn-primary">Create order</a>
                            </div>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>User Name</th> <!-- New column for the image -->
                                        <th>City</th>
                                        <th>Street Address</th>
                                        <th>Postal Code</th>
                                        <th>Books</th>
                                        <th>Total Price</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listOfOrders as $orderId => $order): ?>
                                        <tr>
                                            <td><?php echo $order['Name'] ?? 'N/A'; ?></td>
                                            <td><?php echo $order['City'] ?? 'N/A'; ?></td>
                                            <td><?php echo $order['Street Address'] ?? 'N/A'; ?></td>
                                            <td><?php echo $order['Postal Code'] ?? 'N/A'; ?></td>
                                            <td>
                                                <?php
                                                if (!empty($order['Books']))
                                                {
                                                    foreach ($order['Books'] as $book)
                                                    {
                                                        echo htmlspecialchars($book['bookName']) . "<br>";
                                                    }
                                                }
                                                else
                                                {
                                                    echo 'N/A';
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $order['Total Price'] ?? 'N/A'; ?></td>
                                            <td><?php echo $order['Status'] ?? 'N/A'; ?></td>
                                            <td>
                                                <div class="dropdown d-flex justify-content-center">
                                                    <i type="button" class="dropdown-toggle bi bi-three-dots-vertical"
                                                        data-bs-toggle="dropdown" style="font-size: 18px;">
                                                    </i>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                href="edit_order.php?id=<?php echo $orderId ?>">Edit
                                                                order</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                href="delete_order.php?id=<?php echo $orderId ?>">Delete
                                                                order</a></li>
                                                    </ul>
                                                </div>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main><!-- End #main -->

    <?php include_once('./assets/footer.php') ?>
</body>