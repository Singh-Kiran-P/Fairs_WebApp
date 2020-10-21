<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'] . '/~kiransingh/project/server/classes/class.users.php';
if (isset($_POST['submit']) && isset($_SESSION['userId'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $type = $_POST['type'];

    $user = new Users();
    $res = $user->completeRegisteration($_SESSION['userId']);
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
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/~kiransingh/project/static/componets/navbarTop.php'; ?>
  </header>
  <div class="content">
    <!-- The flexible grid (content) -->
    <div class="flexbox">

      <!-- Register form -->
      <div id="authForm" class="register">

    <div id="authForm" class="completeRegisteration">
      <center>
        <h1> Complete Registeration </h1>
        <form action="/~kiransingh/project/server/io/upload.php" method="post" enctype="multipart/form-data">
          Select image to upload:
          <input type="file" name="fileToUpload" id="fileToUpload">
          <input type="submit" value="Upload Image" name="submit">
        </form>
        <p id="error"></p>
      </center>
    </div>
  </div>
  </div>
</body>


<!-- Script -->
<script src="register.js"></script>


</html>
