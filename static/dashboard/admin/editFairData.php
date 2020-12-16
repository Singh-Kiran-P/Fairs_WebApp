<?php
require '../../../server/classes/class.admin.php';

session_start();

if (!(isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "admin")) {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
} else { //user is an ADMIN
  if (isset($_GET['fairId'])) {
    $fairId = $_GET['fairId'];

    $admin = new Admin();
    $data = $admin->getFairData($fairId);

    if (count($data) != 0) {
      $title = $data['title'];
      $description = $data['description'];
      $start_date = $data['start_date'];
      $end_date = $data['end_date'];
      $opening_hour = $data['opening_hour'];
      $closing_hour = $data['closing_hour'];
      $location = $data['location'];
    }
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
        <H2>Edit Data Of <?php if (isset($title)) echo $title; ?></H2>
      </center>
      <div class="container">
        <form action="editCityData.php" method="post">
          <div class="row">
            <div class="col-25">
              <label for="Title">Title</label>
            </div>
            <div class="col-75">
              <input type="text" id="title" name="title" value="<?php if (isset($title)) echo $title; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="start_date">Start Date</label>
            </div>
            <div class="col-75">
              <input type="text" id="username" name="username" value="<?php if (isset($username)) echo $username; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Email">Email</label>
            </div>
            <div class="col-75">
              <input type="text" id="email" name="email" value="<?php if (isset($email)) echo $email; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Telephone">Telephone</label>
            </div>
            <div class="col-75">
              <input type="text" id="telephone" name="telephone" value="<?php if (isset($telephone)) echo $telephone; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Type">Type</label>
            </div>
            <div class="col-75">
              <select id="types" name="types">
                <?php if (isset($outHTML_Types)) echo $outHTML_Types; ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="createdOn">Created On</label>
            </div>
            <div class="col-75">
              <input type="text" id="createdOn" name="createdOn" value="<?php if (isset($createdOn)) echo $createdOn; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="description">Description</label>
            </div>
            <div class="col-75">
              <textarea id="description" name="description"><?php if (isset($description)) echo $description; ?></textarea>
            </div>
          </div>
          <div class="row sendBtn">
            <input class="btn_delete" type="submit" name="update" value="Delete">
            <input class="btn_submit" type="submit" name="delete" value="Update">
          </div>
        </form>
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
