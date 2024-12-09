<?php
session_start();
include_once("conn.php");
include_once("isNotLogged.php");
include_once('./assets/header.php');

if (isset($_GET['id']))
{
    $id = $_GET['id'];

    $collection = "books/" . $id;
    $getBook    = $firestore->getDocument($collection)->toArray();
    try
    {
        if (isset($_POST['update_book']))
        {
            $bookId      = $id;
            $title       = $_POST['title'];
            $author      = $_POST['author'];
            $pages       = $_POST['pages'];
            $category    = $_POST['category'];
            $description = $_POST['description'];
            $data        = [
                'id' => $bookId,
                'title' => $title,
                'author' => $author,
                'pages' => $pages,
                'category' => $category,
                'description' => $description,
                'cover_image' => $getBook['cover_image']
            ];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK)
            {
                $file       = $_FILES['image'];
                $fileName   = uniqid() . '-' . basename($file['name']); // Unique file name
                $targetPath = 'uploads/' . $fileName; // Path to save the file

                // Move the uploaded file to the target directory
                if (move_uploaded_file($file['tmp_name'], $targetPath))
                {
                    // File uploaded successfully
                    $imageUrl            = $targetPath; // Store the local path
                    $data['cover_image'] = $imageUrl; // Add the image URL to the data array
                }
                else
                {
                    $errMSG = "Failed to move uploaded file.";
                }
            }
            $updateBook = $firestore->setDocument('books/' . $bookId, $data, ['merge' => true]);

            if ($updateBook)
            {
                $successMSG = "Book Updated Successfully!";
            }
            else
            {
                $errMSG = "Failed to Update Book!";
            }

        }
    }
    catch (\MrShan0\PHPFirestore\Exceptions\Client\FieldTypeError $e)
    {
        $errMSG = $e->getMessage();
    }
}
?>

<head>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"> -->
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
    <?php include_once('./assets/head.php') ?>
    <?php include_once('./assets/sidebar.php') ?>

    <main id="main" class="main">
        <section class="section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="card mt-4">
                            <div class="card-body">
                                <h5 class="card-title">Add New Book</h5>
                                <h4>
                                    <?php
                                    if (isset($successMSG))
                                    {
                                        ?>
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            <h5><?php
                                            echo $successMSG;
                                            ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                        <?php
                                    }
                                    else if (isset($errMSG))
                                    {
                                        ?>
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <h5><?php
                                                echo $errMSG;
                                                ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                            </div>
                                        <?php
                                    }

                                    ?>
                                </h4>
                                <form id="contact-form" role="form" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="form_name">Book Title </label>
                                            <input id="form_name" type="text" name="title" class="form-control"
                                                placeholder="Please enter the book title" required
                                                data-error="Book Title is required."
                                                value="<?php echo $getBook['title'] ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="form_lastname">Author Name </label>
                                            <input id="form_lastname" type="text" name="author" class="form-control"
                                                placeholder="Please enter author name " required
                                                data-error="Author name is required."
                                                value="<?php echo $getBook['author'] ?>">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="form_email">Pages </label>
                                            <input id="form_email" type="number" name="pages" class="form-control"
                                                placeholder="Number of pages " required
                                                data-error="Please specify book pages."
                                                value="<?php echo $getBook['pages'] ?>">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="form_need">Category </label>
                                            <select id="form_need" name="category" class="form-select" required
                                                data-error="Please choose a category.">
                                                <option value="<?php echo $getBook['category'] ?>" selected>
                                                    <?php echo $getBook['category'] ?>
                                                </option>
                                                <option value="Education">Education</option>
                                                <option value="Fantasy">Fantasy</option>
                                                <option value="Novel">Novel</option>
                                                <option value="Fiction">Fiction</option>
                                                <option value="Adventure">Adventure</option>
                                                <option value="Romance">Romance</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="form_message">Description </label>
                                        <textarea id="form_message" name="description" class="form-control"
                                            placeholder="Write book description here." rows="4" required
                                            data-error="Please, write a book description."><?php echo $getBook['description'] ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="form_image">Book Cover Image </label>
                                        <?php if (!empty($getBook['cover_image'])): ?>
                                            <img src="<?php echo htmlspecialchars($getBook['cover_image']); ?>"
                                                alt="Current Book Cover"
                                                style="max-width: 100px; max-height: 100px; display: block; margin-bottom: 10px;">
                                        <?php endif; ?>
                                        <input id="form_image" type="file" name="image" class="form-control"
                                            accept="image/*">
                                    </div>
                                    <div class="text-center">
                                        <input type="submit" class="btn btn-success btn-send" value="Save Book"
                                            name="update_book">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->

    <?php include_once('./assets/footer.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>