<?php
session_start();

include '../../server/classes/class.accounts.php';
include '../../server/config/config.php';

if (isset($_POST['isset'])) {
  $name = $_POST['name'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $password2 = $_POST['password2'];
  $email = $_POST['email'];
  $type = $_POST['type'];

  $out = "";
  if ($password != $password2) {
    $out = "Passwords do not match";
  } else {
    $user = new Accounts();
    $res = $user->register($name, $email, $password, $username, $type);
    $out = $res['msg'];

    if ($res['val'] == true && $type == "city") {
      header('Location: ' . $rootURL . '/~kiransingh/project/static/Auth/completeRegisteration.php?userId=' . $res['userId']);
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Register</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/~kiransingh/project/static/style-sheets/form.css">
</head>

<body>
  <!-- Script -->
  <script src="register.js"></script>
  <header>
    <!-- navbar -->
    <?php
    $typeNav = "register";
    include '../componets/navbarTop.php';
    ?>
  </header>
  <div class="content">
    <!-- The flexible grid (content) -->
    <div class="flexbox">

      <!-- Register form -->
      <div id="form" class="register">

        <center>
          <h1> Register </h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
            <input class="text" max="49" type="text" placeholder="Enter your name" name="name" value="<?php if (isset($_POST['name'])) echo _e($_POST['name']); ?>" required>
            <input class="text" max="49" type="text" placeholder="Enter Username" name="username" value="<?php if (isset($_POST['username'])) echo _e($_POST['username']); ?>" required>
            <input class="text" max="254" type="email" placeholder="Enter Email" name="email" value="<?php if (isset($_POST['email'])) echo _e($_POST['email']); ?>" required>
            <input class="text" max="254" type="password" placeholder="Enter Password" name="password" required>
            <input class="text" max="254" type="password" placeholder="ReEnter Password" name="password2" required>
            <label for="type">Type: </label> <select name="type" name="type" class="form-control" required>
              <option value="visitor">Visitor</option>
              <option value="city">City</option>
            </select> <br>
            <input name="isset" value="set" class="hidden">
            <p id="error">
              <?php
              if (isset($_POST['isset']))
                echo _e($out);
              ?>
            </p>
            <button type="submit" class="normalbutton"> Register </button>
          </form>

        </center>
      </div>
    </div>
  </div>
  </div>
</body>

<!-- Script -->
<script src="register.js"></script>



</html>
