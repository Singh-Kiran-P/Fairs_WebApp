<?php
require '../../../server/classes/class.admin.php';

session_start();

if (!(isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "admin")) {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}

$outVisitors = '';
$outCity = '';
$outFair = '';
if (isset($_GET['title']) && isset($_GET['type'])) {
  $title = $_GET['title'];
  $type = $_GET['type'];
  if ($title == "")
    $title = '%';

  $admin = new Admin();

  if ($type == 'visitor' ||  $type == '') {
    //make Visitors HTML
    $allVisitors = $admin->getAllVisitors($title);
    $outVisitors = '<p>Visitors:</p>';
    if (count($allVisitors) == 0)
      $outVisitors .= '<p class="noData">No data found!</p>';
    else {
      $outVisitors .= '<ul>';
      foreach ($allVisitors as $vis)
        $outVisitors .= '<li><p><a href="editVisitorData.php?visitorId=' . _e($vis['user_id']) . '">' . _e($vis['name']) . '</a></p></li>';
      $outVisitors .= '</ul>';
    }
  }

  if ($type == 'city' ||  $type == '') {

    //make City HTML
    $allCity = $admin->getAllCity($title);
    $outCity = '<p>City:</p>';
    if (count($allCity) == 0)
      $outCity .= '<p class="noData">No data found!</p>';
    else {
      $outCity .= '<ul>';

      foreach ($allCity as $vis)
        $outCity .= '<li><p><a href="editCityData.php?cityId=' . _e($vis['city_id']) . '">' . _e($vis['name']) . '</a></p></li>';
      $outCity .= '</ul>';
    }
  }

  if ($type == 'fair' || $type == '') {

    //make Fair HTML
    $allFairs = $admin->getAllFairs($title);
    $outFair = 'Fairs:';
    if (count($allFairs) == 0)
      $outFair .= '<p class="noData">No data found!</p>';
    else {
      $outFair .= '<ul>';

      foreach ($allFairs as $vis)
        $outFair .= '<li><p><a href="editFairData.php?fairId=' . _e($vis['fairId']) . '">' . _e($vis['title']) . '</a></p></li>';
      $outFair .= '</ul>';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Actions</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/actions.css">
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
        <H2>Main actions</H2>
      </center>
      <div class="heading">
        <H3>Update:</H3>
        <div class="links">
          <ul>
            <li> CITY Data</li>
            <li> USER Data</li>
            <li> FAIR Data</li>
          </ul>
        </div>
        <H3>Delete:</H3>
        <div class="links">
          <ul>
            <li> CITY Data</a></li>
            <li> USER Data</a></li>
            <li> FAIR Data</a></li>
          </ul>
        </div>
      </div>

      <div class="search">
        <center>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" class="content" id="form" onsubmit="return validateForm()" enctype='multipart/form-data'>
            <div class="searchBox">
              <input type="text" name="title" max="49" placeholder="Search.." value="" onkeyup="showResult(this.value)" autocomplete="off">
              <select name="type" id="">
                <option value="">All</option>
                <option value="city" <?php if (isset($_POST['type']) && $_POST['type'] == 'city') echo 'selected'; ?>>City</option>
                <option value="visitor" <?php if (isset($_POST['type']) && $_POST['type'] == 'visitor') echo 'selected'; ?>>Visitor</option>
                <option value="fair" <?php if (isset($_POST['type']) && $_POST['type'] == 'fair') echo 'selected'; ?>>Fair</option>
              </select>
              <button type="submit" id="btn">Search</button>
            </div>
            <div class="results">

              <?php echo $outVisitors; ?>
              <?php echo $outCity; ?>
              <?php echo $outFair; ?>


            </div>
          </form>
        </center>
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
