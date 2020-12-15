<?php
session_start();
include '../../server/classes/class.accounts.php';

if (isset($_POST['isset'])) {
  $password = $_POST['password'];
  $email = $_POST['email'];

  $user = new Accounts();
  $redirectTo = $user->login($email, $password);

  $out = "";
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)
    header('Location: ' . $redirectTo);
  else // no redirect -> user does not exites
    $out = "User does not exites!!";
}
?>

<!DOCTYPE html>
<html>

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
            <input class="text" type="text" placeholder="Enter email" name="email" required>
            <input class="text" type="password" placeholder="Enter Password" name="password" required>
            <input type="checkbox" checked="checked"> Remember me <br>
            <input name="isset" class="hidden">
            <button type="submit" id="btn">Login</button>
            Forgot <a href="#"> password? </a>
          </form>
          <p id="error">
            <?php
            if (isset($_POST['isset']))
              echo $out;
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
