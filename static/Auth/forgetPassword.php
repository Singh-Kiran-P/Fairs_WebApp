<?php
include '../../server/classes/class.accounts.php';

$error = '';
$msg = '';
if (isset($_POST['submit']) && isset($_POST['email'])) {
  $email = $_POST['email'];
  $acc = new Accounts();
  if ($acc->checkIfEmailInDatabase($email))
    $acc->sendResetPasswordMail($email);

  $msg = 'If there is an account with this email, you will recieve a email to reset your password.';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/~kiransingh/project/static/style-sheets/mailForms.css">
  <title>Forget Password</title>
</head>

<body>
  <article>
    <H1 class="center">Reset Password</H1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form">
      <label for="email">Email: </label>
      <input type="email" name="email">
      <p class="error"><?php echo $error; ?></p>
      <p><?php echo $msg; ?></p>
      <button type="submit" name="submit">Reset</button>
    </form>
  </article>
</body>

</html>
