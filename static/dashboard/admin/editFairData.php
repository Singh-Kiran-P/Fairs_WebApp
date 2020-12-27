<?php
require '../../../server/classes/class.admin.php';

session_start();


if (!(isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "admin")) {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
} else { //user is an ADMIN
  $start_date = date("Y-m-d");


  $errorMsg = "";
  $admin = new Admin();
  $fairId = "";
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
    } else {
      header('Location: actions.php');
    }

    if (isset($_POST['update'])) { // Process update

      $dataUpdated = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'start_date' => $_POST['start_date'],
        'end_date' => $_POST['end_date'],
        'opening_hour' => $_POST['opening_hour'],
        'closing_hour' => $_POST['closing_hour'],
        'location' => $_POST['location'],
      ];



      $res = $admin->updateFairData($fairId, $dataUpdated);
      if ($res != '') { //error
        $errorMsg = $res;
      } else {
        //update form fields
        $title = $_POST['title'];
        $description = $_POST['description'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $opening_hour = $_POST['opening_hour'];
        $closing_hour = $_POST['closing_hour'];
        $location = $_POST['location'];
        $errorMsg = "Updated successfully";
      }
    }

    if (isset($_POST['delete'])) { // Process delete
      if ($admin->deleteFair($fairId, $data['title']))
        header('Location:actions.php');

      else
        $errorMsg = "Not Deleted there was an error, pls try again";
    }
  } else {
    header('Location: actions.php');
  }
}
?>

<!-- https://www.w3schools.com/css/tryit.asp?filename=trycss_form_responsive -->
<!DOCTYPE html>
<html lang="en">

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
        <H2>Edit Data Of Fair: <?php if (isset($title)) echo $title; ?></H2>
      </center>
      <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?fairId=<?php echo $fairId; ?>" method="post" onsubmit="return validateForm()">
          <div class="row">
            <div class="col-25">
              <label for="title">Title</label>
            </div>
            <div class="col-75">
              <input type="text" id="title" name="title" required value="<?php if (isset($title)) echo $title; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="location">Location</label>
            </div>
            <div class="col-75">
              <input type="text" id="location" name="location" required value="<?php if (isset($location)) echo $location; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="start_date">Start Date</label>
            </div>
            <div class="col-75">
              <div class="hidden start_date_orginal ">
                <?php if (isset($start_date)) echo $start_date; ?>
              </div>
              <input type="date" id="start_date" min="<?php if (isset($start_date)) echo $start_date; ?>" name="start_date" required value="<?php if (isset($start_date)) echo $start_date; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="start_date">End Date</label>
            </div>
            <div class="col-75">
              <div class="hidden end_date_orginal ">
                <?php if (isset($end_date)) echo $end_date; ?>
              </div>
              <input type="date" id="end_date" name="end_date" required value="<?php if (isset($end_date)) echo $end_date; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="opening_hour">Opening Hour</label>
            </div>
            <div class="col-75">
              <input type="time" id="opening_hour" name="opening_hour" required value="<?php if (isset($opening_hour)) echo $opening_hour; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="closing_hour">Closing Hour</label>
            </div>
            <div class="col-75">
              <input type="time" id="closing_hour" name="closing_hour" required value="<?php if (isset($closing_hour)) echo $closing_hour; ?>">
            </div>
          </div>
          <div class="row">
            <div class="col-25">
              <label for="description">Description</label>
            </div>
            <div class="col-75">
              <textarea id="description" required name="description"><?php if (isset($description)) echo $description; ?></textarea>
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

<!-- Script -->
<script src="checkFair.js"></script>



</html>
