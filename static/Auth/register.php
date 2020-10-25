<?php
session_start();

include '../../server/classes/class.users.php';
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
    $user = new Users();
    $res = $user->register($name, $email, $password, $username, $type);
    $out = $res['msg'];

    if ($res['val'] == true && $type == "gemeente") {
      header('Location: ' . $rootURL . '/~kiransingh/project/static/Auth/completeRegisteration.php');
    } else if ($res['val'] == true && $type == "bezoeker") {
      header('Location: ' . $rootURL . '/~kiransingh/project/static/Auth/login.php');
    }
  }
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
          <h1> Kermis Register Form </h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input class="text" type="text" placeholder="Enter your name" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" required>
            <input class="text" type="text" placeholder="Enter Username" name="username" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" required>
            <input class="text" type="email" placeholder="Enter Email" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" required>
            <input class="text" type="password" placeholder="Enter Password" name="password" required>
            <input class="text" type="password" placeholder="ReEnter Password" name="password2" required>
            Soort gebruiker: <select name="type" name="type" class="form-control" required>
              <option value="bezoeker">Bezoeker</option>
              <option value="gemeente">Gemeente</option>
            </select> <br>
            <input name="isset" value="set" class="hidden">
            <button type="submit" class="normalbutton"> Register </button>
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
  </div>
</body>


<!-- Script -->
<script src="register.js"></script>


</html>