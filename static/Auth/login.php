<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/~kiransingh/project/server/classes/class.users.php';
if (isset($_POST['isset'])) {
  $password = $_POST['password'];
  $email = $_POST['email'];


  $user = new Users();
  $user->login($email, $password);


  if (isset($_SESSION['redirectTo']))
    header('Location: ' . $_SESSION['redirectTo']);
  else // no redirect -> user does not exites
    $res = "User does not exites!!";
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/~kiransingh/project/static/style-sheets/authForms.css">
</head>

<body>

  <header>
    <?php
      $typeNav = "login";
      include $_SERVER['DOCUMENT_ROOT'] . '/~kiransingh/project/static/componets/navbarTop.php';
    ?>
  </header>

  <div class="content">
    <!-- The flexible grid (content) -->
    <div class="flexbox">

      <!-- Login form -->
      <div id="authForm">
        <center>
          <h1> Kermis Login Form </h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input class="text" type="text" placeholder="Enter email" name="email" required>
            <input class="text" type="password" placeholder="Enter Password" name="password" required>
            <input type="checkbox" checked="checked"> Remember me <br>
            <input name="isset" hidden>
            <button type="submit" id="btn">Login</button>
            Forgot <a href="#"> password? </a>
          </form>
          <p id="error">
            <?php
            if (isset($_POST['isset']))
              echo $res;
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
