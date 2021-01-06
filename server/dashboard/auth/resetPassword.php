<?php
include_once "../../classes/class.accounts.php";

$error = '';
function checks($p1, $p2)
{
  // checks
  if (empty($p1 || $p2)) {
    return "Password field can not be empty.";
  }
  if ($p1 != $p2) {
    return "Password fields do not match.";
  }

  return null;
}

if (isset($_GET['token']) && isset($_GET['email'])) {
  if (isset($_POST['p1']) && isset($_POST['p2'])) {
    $p1 = $_POST['p1'];
    $p2 = $_POST['p2'];
    $token = $_GET['token'];
    $email = $_GET['email'];
    $acc = new Accounts();

    if (checks($p1, $p2) == null) {
      $res = $acc->resetPassword($email, $token, $p1);
      if ($res == true) {
        //redirect back to login page
        header("Location: ../../../static/Auth/login.php");
      } else
        $error = "Failed to reset password, try again later!";
    }
  }
} else {
  $error = 'Invalid link, please check your email!';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Activate account</title>
</head>

<body>
  <article>
    <H1 class="center">Reset Password</H1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?token=<?php if (isset($_GET['token'])) echo $_GET["token"]; ?>&email=<?php if (isset($_GET['email'])) echo $_GET["email"]; ?>" method="post" class="form">
      <label for="">Password: </label>
      <input type="Password" name="p1">
      <label for="Confirm_Password"> Confirm Password: </label>
      <input type="Password" name="p2" id="">
      <p class="error"><?php echo $error; ?></p>
      <button type="submit">Reset</button>
    </form>
  </article>
</body>

</html>
