<?php

use Google\Type\DateTime;
session_start();
include_once("conn.php");
include_once("isNotLogged.php");
include_once('./assets/header.php');


$authors = []; // Initialize an array to store authors

try
{
    // Reference to the 'authors' collection in Firestore
    $authorsDocuments = $firestore->listDocuments('authors');

    // Loop through each document in the 'authors' collection
    foreach ($authorsDocuments['documents'] as $authorDoc)
    {
        if (!empty($authorDoc))
        {
            // Extract fields from the document
            $fields = $authorDoc->toArray();

            // Build author data from Firestore document
            $authors[] = [
                'id' => $fields['id'] ?? "N/A",
                'name' => $fields['author'] ?? "N/A",
            ];
        }
    }
}
catch (Exception $e)
{
    // Handle Firestore-related exceptions
    echo "Error fetching authors: " . $e->getMessage();
}

if (isset($_POST['save_book']))
{
    $title       = $_POST['title'];
    $author      = $_POST['author'];
    $pages       = $_POST['pages'];
    $category    = $_POST['category'];
    $description = $_POST['description'];
    $price       = $_POST['price'];

    $bookId    = uniqid();
    $createdAt = (new \DateTime())->format('Y-m-d');

    // Handle the image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK)
    {
        $file       = $_FILES['image'];
        $fileName   = uniqid() . '-' . basename($file['name']);
        $targetPath = 'uploads/' . $fileName;

        // Ensure the 'uploads' directory exists
        if (!is_dir('uploads'))
        {
            mkdir('uploads', 0777, true);
        }

        if (move_uploaded_file($file['tmp_name'], $targetPath))
        {
            $imageUrl = $targetPath;

            try
            {
                // Create the book document
                $firestore->setDocument('books/' . $bookId, [
                    'id' => $bookId,
                    'title' => $title,
                    'author' => $author,
                    'pages' => $pages,
                    'category' => $category,
                    'price' => $price,
                    'description' => $description,
                    'cover_image' => $imageUrl,
                    'createdAt' => $createdAt,
                    'rating' => "0",
                ]);

                // Check if author exists in Firestore
                $existingAuthor = false;
                foreach ($authorsDocuments['documents'] as $authorDoc)
                {
                    $fields = $authorDoc->toArray();
                    if ($fields['author'] === $author)
                    {
                        $existingAuthor = true;
                        break;
                    }
                }

                // Create the author document if not exists
                if (!$existingAuthor)
                {
                    $authorId = uniqid();
                    $firestore->setDocument('authors/' . $authorId, [
                        'fields' => [
                            'id' => ['stringValue' => $authorId],
                            'author' => ['stringValue' => $author],
                        ]
                    ]);
                }

                $successMSG = "Book Created Successfully!";
            }
            catch (Exception $e)
            {
                $errMSG = "Failed to create book: " . $e->getMessage();
            }
        }
        else
        {
            $errMSG = "Failed to move uploaded file.";
        }
    }
    else
    {
        $errMSG = "Failed to upload image.";
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
                                                data-error="Book Title is required.">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="form_lastname">Author Name</label>
                                            <select id="form_need" name="author" class="form-select" required
                                                data-error="Please select the author.">
                                                <option value="" selected disabled>--Select author--</option>
                                                <?php foreach ($authors as $author): ?>
                                                    <option value="<?php echo htmlspecialchars($author['name']); ?>">
                                                        <?php echo htmlspecialchars($author['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="form_name">Price </label>
                                            <input id="form_name" type="text" name="price" class="form-control"
                                                placeholder="Please enter the book price" required
                                                data-error="Book Price is required.">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="form_email">Pages </label>
                                            <input id="form_email" type="number" name="pages" class="form-control"
                                                placeholder="Number of pages " required
                                                data-error="Please specify book pages.">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="form_need">Category </label>
                                            <select id="form_need" name="category" class="form-select" required
                                                data-error="Please choose a category.">
                                                <option value="" selected disabled>--Select Book Category--</option>
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
                                            data-error="Please, write a book description."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="form_image">Book Cover Image </label>
                                        <input id="form_image" type="file" name="image" class="form-control"
                                            accept="image/*" required data-error="Please upload a book cover image.">
                                    </div>
                                    <div class="text-center">
                                        <input type="submit" class="btn btn-success btn-send" value="Save Book"
                                            name="save_book">
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