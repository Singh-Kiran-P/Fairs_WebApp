<?php
require '../../../server/classes/class.admin.php';

session_start();


if (!(isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "admin")) {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
} else { //user is an ADMIN
  $errorMsg = "";
  $admin = new Admin();
  $cityId = "";
  if (isset($_GET['cityId'])) {
    $cityId = $_GET['cityId'];


    $data = $admin->getCityData($cityId);


    if (count($data) != 0) {
      $name = $data['name'];
      $username = $data['username'];
      $email = $data['email'];

      $createdOn = $data['created_on'];
      $description = $data['short_description'];
      $telephone = $data['telephone'];
    } else {
      header('Location: actions.php');
    }
  }
  if (isset($_POST['update'])) { // Process update
    $dataUpdated = [
      'name' => $_POST['name'],
      'username' => $data['username'],
      'email' => $_POST['email'],
      'telephone' => $_POST['telephone'],
      'createdOn' =>  $data['created_on'],
      'description' => $_POST['description']
    ];

    $res = $admin->updateCityData($cityId, $dataUpdated);
    if ($res != '') { //error
      $errorMsg = $res;
    } else {
      $name = $dataUpdated['name'];
      $username = $dataUpdated['username'];
      $email = $dataUpdated['email'];
      $createdOn = $data['created_on'];
      $description = $data['short_description'];
      $telephone = $dataUpdated['telephone'];
      $errorMsg = "Updated successfully";
    }
  }
  if (isset($_POST['delete'])) { // Process delete
    if ($admin->deleteCity($cityId))
      header('Location: actions.php');
  }
}
?>

<!-- https://www.w3schools.com/css/tryit.asp?filename=trycss_form_responsive -->
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edit City Data</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/admin_forms.css">
  <!-- favicon -->
  <?php include "../../favicon/favicon.php"; ?>
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
        <H2>Edit Data Of City: <?php if (isset($name)) echo _e($name); ?></H2>
      </center>
      <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?cityId=<?php echo _e($cityId); ?>" method="post">
          <div class="row">
            <div class="col-25">
              <label for="Name">Name</label>
            </div>
            <div class="col-75">
              <input type="text" id="name" name="name" required value="<?php if (isset($name)) echo _e($name); ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Username">Username</label>
            </div>
            <div class="col-75">
              <input type="text" id="username" name="username" disabled required value="<?php if (isset($username)) echo _e($username); ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Email">Email</label>
            </div>
            <div class="col-75">
              <input type="email" id="email" name="email" required value="<?php if (isset($email)) echo _e($email); ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Telephone">Telephone</label>
            </div>
            <div class="col-75">
              <input type="text" id="telephone" name="telephone" required value="<?php if (isset($telephone)) echo _e($telephone); ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Type">Type</label>
            </div>
            <div class="col-75">
              <select id="type" name="type">
                <option value="city" selected>City</option>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="createdOn">Created On</label>
            </div>
            <div class="col-75">
              <input type="text" id="createdOn" name="createdOn" disabled required value="<?php if (isset($createdOn)) echo _e($createdOn); ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="description">Description</label>
            </div>
            <div class="col-75">
              <textarea id="description" required name="description"><?php if (isset($description)) echo _e($description); ?></textarea>
            </div>
          </div>
          <div class="row sendBtn">
            <input class="btn_delete" type="submit" name="update" value="Update">
            <input class="btn_submit" type="submit" name="delete" value="Delete">
          </div>
        </form>
        <p id="error">
          <?php
          echo _e($errorMsg);
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
