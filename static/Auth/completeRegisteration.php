<?php
session_start();

include '../../server/classes/class.accounts.php';
include '../../server/classes/class.upload.php';
include '../../server/config/config.php';
if (isset($_POST['submit']) && isset($_SESSION['userId'])) {
  $telephone = $_POST['telephone'];
  $desc = $_POST['desc'];
  $fileToUpload = $_FILES['fileToUpload'];

  $userId = $_SESSION['userId'];

  //Login as visitor to set the gernal variable
  $user = new Accounts();
  $user->init($userId, "visitor");

  //upload img
  $_SESSION['typeImg'] = "profile";
  $_SESSION["fileToUpload"] = $fileToUpload;

  //TO-DO BUG
  $errorMsg = Upload::checkFilesImg($fileToUpload,true);
  if ($errorMsg['msg'] != '') //error while checking
    $res =  $errorMsg['msg'];
  else {

    $count = Upload::uploadFiles($fileToUpload, $userId, "profile", "img");

    if ($count != 0) // check if upload was successfull
      $res = $user->completeRegisteration($telephone, $desc);
    else
      $res = "upload was unsuccessfull";

    if ($res == "true") {
      header('Location: ' . $rootURL . '/~kiransingh/project/static/Auth/login.php');
    }
    else{
      $errorMsg = $res;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Complete Registeration</title>
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
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" id="usrform" onsubmit="return validateForm()">
            <input type="text" name="telephone" placeholder="Telephone" required>
            <textarea type="" name="desc" placeholder="Give a short discription of your city" form="usrform" required></textarea>
            Select image to upload: <input type="file" name="fileToUpload" id="fileToUpload" required>
            <h5> (only JPG, JPEG, PNG & GIF files are allowed Max 5mb )</h5>


            <input name="isset" value="set" class="hidden">
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
<script src="completeRegisteration.js"></script>


</html>
