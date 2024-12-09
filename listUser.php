<?php
session_start();
include_once("auth.php");
include_once("conn.php");
include_once("isNotLogged.php");
include_once('./assets/header.php');

$listOfUsers = []; // Initialize the list of users outside the loop

$getUsers = $auth->listUsers();
if (isset($getUsers))
{
    foreach ($getUsers as $u)
    {
        $userId  = $u->uid ?? "N/A"; // Get the user ID
        $getUser = $firestore->listDocuments('user'); // Fetch user documents from Firestore

        // Initialize user data with Firebase data
        $listOfUsers[$userId] = [
            'id' => $userId,
            'name' => !empty($u->displayName) ? $u->displayName : $u->email, // Use displayName or fallback to email
            'email' => $u->email ?? "N/A",
            'isVerified' => $u->emailVerified ?? "N/A",
            'streetAddress' => "N/A",
            'number' => $u->phoneNumber ?? "N/A",
            'role' => "N/A",
            'city' => "N/A",
            'postalCode' => "N/A",
        ];

        foreach ($getUser['documents'] as $userDoc)
        {
            $fields    = $userDoc->toArray();
            $docUserId = $fields['id'] ?? "N/A";
            if ($docUserId === $userId)
            {
                $listOfUsers[$userId]['streetAddress'] = $fields['streetAddress'] ?? "N/A";
                $listOfUsers[$userId]['number']        = $fields['number'] ?? "N/A";
                $listOfUsers[$userId]['role']          = $fields['role'] ?? "N/A";
                $listOfUsers[$userId]['city']          = $fields['city'] ?? "N/A";
                $listOfUsers[$userId]['postalCode']    = $fields['postalCode'] ?? "N/A";
                $listOfUsers[$userId]['role']          = $fields['role'] ?? "N/A";
            }
        }
    }
}
?>
<style>
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
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success fade show alert-dismissible" role="alert">
                            <?php echo $_SESSION['success_message'];
                            unset($_SESSION['success_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php elseif (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-success fade show alert-dismissible" role="alert">
                            <?php echo $_SESSION['error_message'];
                            unset($_SESSION['error_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">List Of Users</h5>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Number</th>
                                        <th>Verified</th>
                                        <th>Role</th>
                                        <th>City</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($listOfUsers)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center">No users found.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($listOfUsers as $userId => $user): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['number']); ?></td>
                                                <td><?php echo $user['isVerified'] ? "Yes" : "No"; ?></td>
                                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                                <td><?php echo htmlspecialchars($user['city']); ?></td>
                                                <td><?php echo htmlspecialchars($user['streetAddress']); ?></td>
                                                <td>
                                                    <div class="dropdown d-flex justify-content-center">
                                                        <i type="button" class="dropdown-toggle bi bi-three-dots-vertical"
                                                            data-bs-toggle="dropdown" style="font-size: 18px;">
                                                        </i>
                                                        <ul class="dropdown-menu">
                                                            <li> <a class="dropdown-item"
                                                                    href="edit_user.php?id=<?php echo $user['id'] ?>">Edit
                                                                    User</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="delete_user.php?id=<?php echo $user['id'] ?>">Delete
                                                                    User</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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