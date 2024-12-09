<?php
session_start();
include_once("auth.php");
include_once("conn.php");
include_once("isNotLogged.php");
include_once('./assets/header.php');

if (isset($_GET['id']))
{
    $id = htmlspecialchars($_GET['id']);

    try
    {
        $collection = "user/" . $id;
        $getuser    = $firestore->getDocument($collection);
        if (!$getuser)
        {
            $errMSG  = "User not found.";
            $getuser = [];
        }
        else
        {
            $getuser = $getuser->toArray();
            echo "<script>
                console.log(" . json_encode($getuser) . ");
            </script>";
            $isVerified    = $getuser['isVerified'];
            $city          = $getuser['city'];
            $streetAddress = $getuser['streetAddress'];
            $postalCode    = $getuser['postalCode'];
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user']))
        {
            $name   = htmlspecialchars($_POST['name']);
            $email  = htmlspecialchars($_POST['email']);
            $number = htmlspecialchars($_POST['number']);

            $data = [
                'id' => $getuser['id'],
                'displayName' => $name,
                'email' => $email,
                'number' => $number,
                'isVerified' => $isVerified,
                'role' => $getuser['role'],
                'city' => $city,
                'streetAddress' => $streetAddress,
                'postalCode' => $postalCode,
            ];

            $updateuser = $firestore->setDocument('user/' . $id, $data, ['merge' => true]);
            $properties = [
                'email' => $email,
                'displayName' => $name,
            ];
            $updateAuth = $auth->updateUser($id, $properties);
            if ($updateuser && $updateAuth)
            {
                $_SESSION['success_message'] = "User updated successfully!";
                header("location: listUser.php");
                exit();
            }
            else
            {
                $_SESSION['error_message'] = "Failed to update user. ";
                header("location: listUser.php");
                exit();
                if (!$updateuser)
                {
                    $_SESSION['error_message'] = "Firestore update failed. ";
                    header("location: listUser.php");
                    exit();
                }
                if (!$updateAuth)
                {
                    $_SESSION['error_message'] = "Authentication update failed.";
                    header("location: listUser.php");
                    exit();
                }
            }
        }
    }
    catch (\Exception $e)
    {
        $errMSG = $e->getMessage();
    }
}
else
{
    $errMSG = "No user ID provided.";
}
?>

<head>
    <style>
        h1 {
            margin-bottom: 40px;
        }

        label {
            color: #333;
        }

        .btn-send {
            font-weight: 300;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            width: 80%;
            margin-left: 3px;
        }

        .help-block.with-errors {
            color: #ff5050;
            margin-top: 5px;
        }

        .card {
            margin: 10px;
        }
    </style>
</head>

<body>
    <?php include_once('./assets/head.php'); ?>
    <?php include_once('./assets/sidebar.php'); ?>

    <main id="main" class="main">
        <section class="section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Update User</h5>
                                <form id="contact-form" method="POST">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="form_name">User Name</label>
                                            <input id="form_name" type="text" name="name" class="form-control"
                                                placeholder="Enter user name" required
                                                value="<?php echo $getuser['displayName'] ?? 'N/A'; ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="form_email">Email</label>
                                            <input id="form_email" type="email" name="email" class="form-control"
                                                placeholder="Enter email" required
                                                value="<?= htmlspecialchars($getuser['email'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="form_number">Phone Number</label>
                                            <input id="form_number" type="text" name="number" class="form-control"
                                                placeholder="Enter phone number" required
                                                value="<?= htmlspecialchars($getuser['number'] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success btn-send" name="update_user">Save
                                            User</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <?php include_once('./assets/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>