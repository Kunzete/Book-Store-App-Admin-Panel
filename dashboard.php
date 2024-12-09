<?php
session_start();

include_once("conn.php");
include_once("isNotLogged.php");
include_once('./assets/header.php');

$book   = $firestore->listDocuments('books');
$author = $firestore->listDocuments('authors');
$order  = $firestore->listDocuments('orders');
$user   = $firestore->listDocuments('user');

foreach ($book as $countOfBooks)
{
    $totalBooks = count($countOfBooks);
}

foreach ($user as $countOfUser)
{
    $totalUsers = count($countOfUser);
}

foreach ($author as $countOfAuthor)
{
    $totalAuthors = count($countOfAuthor);
}

foreach ($order as $countOfOrder)
{
    $totalOrders = count($countOfOrder);
}

?>

<head>
    <link rel="stylesheet" type="text/css"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/fonts/simple-line-icons/style.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/colors.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
    <?php include_once('./assets/head.php') ?>
    <?php include_once('./assets/sidebar.php') ?>

    <main id="main" class="main">
        <div class="container">
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
            ?>
            <div class="container-fluid">
                <section id="minimal-statistics">
                    <div class="row">
                        <div class="col-12 mt-3 mb-1">
                            <h2 class="text-uppercase">Dashboard</h2>
                            <p>Welcome <strong><?php echo $_SESSION['user_name'] ?>!</strong></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="success"><?php echo $totalUsers ?></h3>
                                                <span>Total Users</span>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="ri-user-3-fill success font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                        <div class="progress mt-1 mb-0" style="height: 7px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="primary"><?php echo $totalBooks ?></h3>
                                                <span>Total Books</span>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="ri-book-fill primary font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                        <div class="progress mt-1 mb-0" style="height: 7px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="danger"><?php echo $totalAuthors ?></h3>
                                                <span>Total Authors</span>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="ri-user-3-fill danger font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                        <div class="progress mt-1 mb-0" style="height: 7px;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 100%"
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="media d-flex">
                                            <div class="media-body text-left">
                                                <h3 class="warning"><?php echo $totalOrders ?></h3>
                                                <span>Total Orders</span>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="ri-box-3-fill warning font-large-2 float-right"></i>
                                            </div>
                                        </div>
                                        <div class="progress mt-1 mb-0" style="height: 7px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%"
                                                aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="stats-subtitle">
                    <div class="row">
                        <div class="col-12 mt-3 mb-1">
                            <h4 class="text-uppercase">Statistics With Subtitle</h4>
                            <p>Statistics on minimal cards with Title &amp; Sub Title.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <div class="card overflow-hidden">
                                <div class="card-content">
                                    <div class="card-body cleartfix">
                                        <div class="media align-items-stretch">
                                            <div class="align-self-center">
                                                <i class="icon-pencil primary font-large-2 mr-2"></i>
                                            </div>
                                            <div class="media-body">
                                                <h4>Total Posts</h4>
                                                <span>Monthly blog posts</span>
                                            </div>
                                            <div class="align-self-center">
                                                <h1>18,000</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body cleartfix">
                                        <div class="media align-items-stretch">
                                            <div class="align-self-center">
                                                <i class="icon-speech warning font-large-2 mr-2"></i>
                                            </div>
                                            <div class="media-body">
                                                <h4>Total Comments</h4>
                                                <span>Monthly blog comments</span>
                                            </div>
                                            <div class="align-self-center">
                                                <h1>84,695</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-md-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body cleartfix">
                                        <div class="media align-items-stretch">
                                            <div class="align-self-center">
                                                <h1 class="mr-2">$76,456.00</h1>
                                            </div>
                                            <div class="media-body">
                                                <h4>Total Sales</h4>
                                                <span>Monthly Sales Amount</span>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="icon-heart danger font-large-2"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body cleartfix">
                                        <div class="media align-items-stretch">
                                            <div class="align-self-center">
                                                <h1 class="mr-2">$36,000.00</h1>
                                            </div>
                                            <div class="media-body">
                                                <h4>Total Cost</h4>
                                                <span>Monthly Cost</span>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="icon-wallet success font-large-2"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main><!-- End #main -->

    <?php include_once('./assets/footer.php') ?>
</body>