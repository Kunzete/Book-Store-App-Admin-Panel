<?php
session_start();
include_once("conn.php"); // Include your Firestore connection
include_once("isNotLogged.php"); // Check if the user is logged in
include_once('./assets/header.php');

// Check if the order ID is provided
if (!isset($_GET['id']))
{
    echo "Order ID is missing.";
    exit;
}

$orderId = $_GET['id'];

// Fetch the order from Firestore
$orderDoc  = $firestore->getDocument('orders/' . $orderId);
$orderData = $orderDoc->toArray();

if (!$orderData)
{
    echo "Order not found.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $newStatus = $_POST['status'] ?? null;

    if ($newStatus)
    {
        // Update the order status in Firestore
        $firestore->updateDocument('orders/' . $orderId, [
            'status' => $newStatus
        ]);

        $_SESSION['success_message'] = "Order status updated successfully.";
        header("Location: orders.php");
        exit;
    }
    else
    {
        $error = "Please select a status.";
    }
}

// Available statuses
$statuses = ['Pending', 'Processing', 'Completed', 'Cancelled'];
?>

<body>
    <?php include_once('./assets/head.php') ?>
    <?php include_once('./assets/sidebar.php') ?>

    <main id="main" class="main">
        <div class="container mt-4">
            <h2>Edit Order Status</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php
                    echo $_SESSION['success_message'];
                    unset($_SESSION['success_message']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card p-4">
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="status">Select Status:</label>
                            <select name="status" id="status" class="form-control">
                                <?php foreach ($statuses as $status): ?>
                                    <option value="<?php echo $status; ?>" <?php echo ($orderData['status'] === $status) ? 'selected' : ''; ?>>
                                        <?php echo $status; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                            <a href="orders.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    <?php include_once('./assets/footer.php'); ?> <!-- Include footer -->
</body>

</html>