<?php
session_start();
include_once("auth.php"); // Ensure this file initializes the Firestore client
include_once("conn.php"); // Ensure this file initializes the Firestore client
include_once("isLogged.php"); // Check if the user is already logged in
include_once("./assets/header.php"); // Include your header

if (isset($_POST['login_btn']))
{
  $email    = $_POST['email'];
  $password = $_POST['password'];

  // Validate email format
  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    $_SESSION['error_message'] = 'Invalid email format.';
    header("location: login.php");
    exit();
  }

  try
  {
    $signInResult = $auth->signInWithEmailAndPassword($email, $password);
    $userId       = $signInResult->firebaseUserId();
    $userData     = $auth->getUser($userId);

    $userCollection = "user/" . $userId;
    $isAdmin        = $firestore->getDocument($userCollection)->toArray();

    if (isset($isAdmin['role']) && $isAdmin['role'] === 'admin')
    {
      $_SESSION['user_id']         = $userId;
      $_SESSION['user_email']      = $email;
      $_SESSION['user_name']       = $userData->displayName ?? 'User ';
      $_SESSION['success_message'] = "Welcome back, {$_SESSION['user_name']}! You’ve successfully logged in.";

      // Redirect to the dashboard
      header("Location: dashboard.php");
      exit();
    }
    else
    {
      $_SESSION['error_message'] = 'You are not authorized, please contact the administrator';
      header("location: login.php");
      exit();
    }
  }
  catch (\Kreait\Firebase\Exception\AuthException $e)
  {
    // Handle authentication errors
    switch ($e->getMessage())
    {
      case 'INVALID_LOGIN_CREDENTIALS':
        $_SESSION['error_message'] = 'Invalid credentials, please enter correct credentials!';
        break;
      default:
        $_SESSION['error_message'] = 'An error occurred: ' . htmlspecialchars($e->getMessage());
        break;
    }
    header("location: login.php");
    exit();
  }
  catch (Exception $e)
  {
    // Handle other exceptions
    $_SESSION['error_message'] = 'An unexpected error occurred: ' . htmlspecialchars($e->getMessage());
    header("location: login.php");
    exit();
  }
}
?>

<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
              <div class="d-flex justify-content-center py-0">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="" height="50px">
                  <img src="assets/img/branding.png" alt="" height="160px">
                </a>
              </div><!-- End Logo -->

              <div class="card ">
                <div class="card-body">
                  <div class="pt-4 pb-4">
                    <h5 class="card-title text-center pb-0 fs-4">Admin Login</h5>
                  </div>

                  <form class="row g-3 needs-validation" novalidate method="POST">
                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Email</label>
                      <div class="input-group has-validation">
                        <input type="text" name="email" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Please enter your email.</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div>

                    <?php
                    if (isset($_SESSION['error_message']))
                    {
                      echo "<p style='color: red;'>{$_SESSION['error_message']}</p>";
                      unset($_SESSION['error_message']); // Clear the message after displaying
                    }
                    ?>

                    <div class="col-12 pt-3">
                      <button class="btn btn-primary w-100" name="login_btn" type="submit">Login</button>
                    </div>
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>