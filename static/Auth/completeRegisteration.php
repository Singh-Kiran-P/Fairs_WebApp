<?php
session_start();

include '../../server/classes/class.users.php';
if (isset($_POST['submit']) && isset($_SESSION['userId'])) {
  $telephone = $_POST['telephone'];
  $desc = $_POST['desc'];
  $fileToUpload = $_FILES['fileToUpload'];

  $userId = $_SESSION['userId'];
  try {
    $user = new Users();
    $user->init($userId);

    //upload img
    $_SESSION['typeImg'] = "profile";
    $_SESSION["fileToUpload"] = $fileToUpload;
    require_once('../../server/io/upload.php');
  } catch (\Throwable $th) {
    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
  }

  $res = $user->completeRegisteration($telephone, $desc);

  if ($res == true) {
    header('Location: ' . $rootURL . '/~kiransingh/project/static/Auth/login.php');
  } else {
    $res = "Error saving data";
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
    <!-- Navbar -->
    <?php
    $typeNav = "register";
    include '../componets/navbarTop.php';
    ?>
  </header>
  <div class="content">
    <!-- The flexible grid (content) -->
    <div class="flexbox">
      <!-- Register form -->
      <div id="form" class="completeRegisteration">
        <center>
          <h1> Complete Registeration </h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" id="usrform">
            <input type="text" name="telephone" placeholder="Telephone" required>
            <textarea type="" name="desc" placeholder="" form="usrform" required>Give a short discription of your 'gemeente'</textarea>
            Select image to upload: <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Confirm" name="submit">
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
</body>


<!-- Script -->
<script src="register.js"></script>


</html>
