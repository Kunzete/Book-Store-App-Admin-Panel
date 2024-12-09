<?php
session_start();
include_once("conn.php");
include_once("isNotLogged.php");
include_once('./assets/header.php');

$listOfBooks = [];
$getBook     = $firestore->listDocuments('books');

foreach ($getBook['documents'] as $bookDoc)
{
    $fields = $bookDoc->toArray();
    $bookId = $fields['id'] ?? null;

    $listOfBooks[$bookId] = [
        'title' => $fields['title'] ?? null,
        'author' => $fields['author'] ?? null,
        'pages' => $fields['pages'] ?? null,
        'category' => $fields['category'] ?? null,
        'description' => $fields['description'] ?? null,
        'cover_image' => $fields['cover_image'] ?? null
    ];
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
                    if (isset($_SESSION['bookDelete']))
                    {
                        ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <h5><?php
                            echo $_SESSION['bookDelete'];
                            unset($_SESSION['bookDelete']);
                            ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php
                    }
                    else if (isset($_SESSION['bookDeleteErr']))
                    {
                        ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <h5><?php
                                echo $_SESSION['bookDeleteErr'];
                                unset($_SESSION['bookDeleteErr']);
                                ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php
                    }
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex justify-content-between">
                                <h5>List Of Books</h5>
                                <a href="createbook.php" class="btn btn-primary">Create Book</a>
                            </div>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Cover Image</th> <!-- New column for the image -->
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>Pages</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($listOfBooks as $bookId => $book): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($book['cover_image'])): ?>
                                                    <img src="<?php echo $book['cover_image']; ?>" alt="Cover Image"
                                                        class="img-responsive" />
                                                <?php else: ?>
                                                    <span>No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $book['title'] ?? 'N/A'; ?></td>
                                            <td><?php echo $book['author'] ?? 'N/A'; ?></td>
                                            <td><?php echo $book['pages'] ?? 'N/A'; ?></td>
                                            <td
                                                style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                <?php echo $book['description'] ?? 'N/A'; ?>
                                            </td>
                                            <td><?php echo $book['category'] ?? 'N/A'; ?></td>
                                            <td>
                                                <div class="dropdown d-flex justify-content-center">
                                                    <i type="button" class="dropdown-toggle bi bi-three-dots-vertical"
                                                        data-bs-toggle="dropdown" style="font-size: 18px;">
                                                    </i>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item"
                                                                href="editbook.php?id=<?php echo $bookId ?>">Edit Book</a>
                                                        </li>
                                                        <li><a class="dropdown-item"
                                                                href="deletebook.php?id=<?php echo $bookId ?>">Delete
                                                                Book</a></li>
                                                    </ul>
                                                </div>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main><!-- End #main -->

    <?php include_once('./assets/footer.php') ?>
</body>