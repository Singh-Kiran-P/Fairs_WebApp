<?php
require '../../../server/classes/class.admin.php';

session_start();

if (!(isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "admin")) {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
} else { //user is an ADMIN
  if (isset($_GET['cityId'])) {
    $cityId = $_GET['cityId'];

    $admin = new Admin();
    $data = $admin->getCityData($cityId);

    if (count($data) != 0) {
      $name = $data['name'];
      $username = $data['username'];
      $email = $data['email'];

      $createdOn = $data['created_on'];
      $description = $data['short_description'];
      $telephone = $data['telephone'];


      $type = $data['type'];
      $outHTML_Types = '';
      if ($type == 'city') {
        $outHTML_Types .= '<option value="city" selected>City</option>';
        $outHTML_Types .= '<option value="visitor" >Visitor</option>';
      } else if ($type == 'visitor') {
        $outHTML_Types .= '<option value="visitor" selected>Visitor</option>';
        $outHTML_Types .= '<option value="city" >City</option>';
      }
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
        <H2>Edit Data Of <?php if (isset($name)) echo $name; ?></H2>
      </center>
      <div class="container">
        <form action="editCityData.php" method="post">
          <div class="row">
            <div class="col-25">
              <label for="Name">Name</label>
            </div>
            <div class="col-75">
              <input type="text" id="name" name="firstname" value="<?php if (isset($name)) echo $name; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="Username">Username</label>
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
              <input type="text" id="createdOn" name="createdOn"  value="<?php if (isset($createdOn)) echo $createdOn; ?>">
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
