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
          <label class="hidden" for="email">Enter your email address Or Username:</label>
          <label class="hidden" for="password">Enter your password:</label>
          <label class="hidden" for="rememberMe">Remember you?:</label>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input class="text" max="254" type="text" placeholder="Enter email or Username" name="email" id="email" required>
            <input class="text" max="254" type="password" placeholder="Enter Password" name="password" id="password" required>
            <input type="checkbox" name="" checked="checked" id="rememberMe"> Remember me <br>
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
