<?php
session_start();
include '../../server/classes/class.accounts.php';

if (isset($_POST['isset'])) {
  $password = $_POST['password'];
  $email = $_POST['email'];

  $user = new Accounts();
  $obj = $user->login($email, $password);

  $out = "";
  if (!$obj['vaild'])
    $out .= $obj['msg'];
  else {
    header('Location: ' . $obj['redirectTo']);
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/~kiransingh/project/static/style-sheets/form.css">
</head>

<body>

  <header>
    <!-- Navbar -->
    <?php
    $typeNav = "login";
    include '../componets/navbarTop.php';
    ?>
  </header>

  <div class="content">
    <!-- The flexible grid (content) -->
    <div class="flexbox">

      <!-- Login form -->
      <div id="form">
        <center>
          <h1>Login</h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input class="text" max="254" type="text" placeholder="Enter email or Username" name="email" id="email" required>
            <input class="text" max="254" type="password" placeholder="Enter Password" name="password" id="password" required>
            <input name="isset" class="hidden">
            <button type="submit" id="btn">Login</button>
            <a href="forgetPassword.php">Forgot Password? </a>
          </form>
          <p id="error">
            <?php
            if (isset($_POST['isset']))
              echo _e($out);
            ?>
          </p>
        </center>
      </div>
    </div>
  </div>
</body>

<!-- Script -->
<script src="login.js"></script>

</html>
