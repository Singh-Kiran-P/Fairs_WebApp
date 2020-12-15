<?php
require '../../../server/classes/class.admin.php';

session_start();

if (!(isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "admin")) {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}



?>

<!DOCTYPE html>
<html>

<head>
  <title>Profile</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/actions.css">
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
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method=" post" class="content" id="form" onsubmit="return validateForm()" enctype='multipart/form-data'>
            <div class="searchBox">
              <input type="text" name="title" placeholder="Search.." value="" required onkeyup="showResult(this.value)" autocomplete="off">
              <select name="" id="">
                <option value="">Choose type:</option>
                <option value="">City</option>
                <option value="">Visitor</option>
                <option value="">Fair</option>
              </select>
            </div>
            <div class="autocomplete" style="width:300px;">
              <div id="myInputautocomplete-list" class="autocomplete-items">
                <div><strong>R</strong>eunion<input type="hidden" value="Reunion"></div>
                <div><strong>R</strong>omania<input type="hidden" value="Romania"></div>
                <div><strong>R</strong>ussia<input type="hidden" value="Russia"></div>
                <div><strong>R</strong>wanda<input type="hidden" value="Rwanda"></div>
              </div>
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
