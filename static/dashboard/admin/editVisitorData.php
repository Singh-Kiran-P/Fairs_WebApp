<?php
require '../../../server/classes/class.admin.php';

session_start();


if (!(isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "admin")) {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
} else { //user is an ADMIN
  $errorMsg = "";
  $admin = new Admin();
  $visitorId = "";
  if (isset($_GET['visitorId'])) {
    $visitorId = $_GET['visitorId'];

    $data = $admin->getVisitorData($visitorId);

    if (count($data) != 0) {
      $name = $data['name'];
      $username = $data['username'];
      $email = $data['email'];
      $createdOn = $data['created_on'];
    } else {
      header('Location: actions.php');
    }
  }
  if (isset($_POST['update'])) { // Process update
    $dataUpdated = [
      'name' => $_POST['name'],
      'username' => $data['username'],
      'email' => $_POST['email']
    ];

    $res = $admin->updateVisitorData($visitorId, $dataUpdated);
    if ($res != '') { //error
      $errorMsg = $res;
    } else {
      $errorMsg = "Updated successfully";
    }
  }

  if (isset($_POST['delete'])) { // Process delete
    if ($admin->deleteVisitor($visitorId))
      header('Location: ' . __DIR__ . 'actions.php');

    else
      $errorMsg = "Not Deleted there was an error, pls try again";
  }
}
?>

<!-- https://www.w3schools.com/css/tryit.asp?filename=trycss_form_responsive -->
<!DOCTYPE html>
<html>

<head>
  <title>Profile</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/admin_forms.css">
</head>

<body>

  <header>
    <!-- navbar -->
    <?php
    $typeNav = "admin_nav";
    include '../../componets/navbarTop.php';
    ?>
  </header>

  <!-- The flexible grid (content) -->
  <div class="content">

    <div class="mainCol">
      <center>
        <H2>Edit Data Of Visitor: <?php if (isset($name)) echo $name; ?></H2>
      </center>
      <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?visitorId=<?php echo $visitorId; ?>" method="post">
          <div class="row">
            <div class="col-25">
              <label for="Name">Name</label>
            </div>
            <div class="col-75">
              <input type="text" id="name" name="name" required value="<?php if (isset($name)) echo $name; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Username">Username</label>
            </div>
            <div class="col-75">
              <input type="text" id="username" name="username" disabled required value="<?php if (isset($username)) echo $username; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Email">Email</label>
            </div>
            <div class="col-75">
              <input type="email" id="email" name="email" required value="<?php if (isset($email)) echo $email; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Type">Type</label>
            </div>
            <div class="col-75">
              <select id="type" name="type">
                <option value="visitor" selected>Visitor</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="createdOn">Created On</label>
            </div>
            <div class="col-75">
              <input type="text" id="createdOn" name="createdOn" disabled required value="<?php if (isset($createdOn)) echo $createdOn; ?>">
            </div>
          </div>

          <div class="row sendBtn">
            <input class="btn_delete" type="submit" name="update" value="Update">
            <input class="btn_submit" type="submit" name="delete" value="Delete">
          </div>
        </form>
        <p id="error">
          <?php
          echo $errorMsg;
          ?>
        </p>
      </div>

    </div>
  </div>

</body>

<!-- Linking Events -->
<script>
  var btn = document.getElementById("btn_More");
  btn.addEventListener("click", (event) => {
    showMore()
  })
</script>

<!-- Extrenal scripts -->
<script src="../ShowMore.js"></script>


</html>
