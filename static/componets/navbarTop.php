<nav class="topNavbar">
  <!-- Navigation Bar -->
  <div class="logo">
    <?php
    if ($typeNav == "city_nav")
      echo '<a href="/~kiransingh/project/static/dashboard/city/profile.php">';
    else if ($typeNav == "visitor_nav")
      echo "";
    else
      echo '<a href="/~kiransingh/project/static/index.php">';
    ?>
    <img src="/~kiransingh/project/static/img/UHasselt-liggend.jpg" width="200px" alt="">
    </a>
  </div>

  <!-- navbar Links for City middle -->
  <?php
  /* City main navTop Links middle */
  if ($typeNav == "city_nav") {
    echo '<a class="mostLeft line" href="/~kiransingh/project/static/dashboard/city/addFair.php">Add Fair</a>';
    echo '<a class="mostRight" href="/~kiransingh/project/static/dashboard/city/listOfFair.php">List of Fair</a>';
  }
  ?>
  <!-- Break Line -->
  <div class="line"></div>

  <?php
  /* Register navTop */
  if ($typeNav == "register" || $typeNav == "index") {
    echo '<a href="/~kiransingh/project/static/Auth/login.php">Login</a>';
  }

  /* Login navTop */
  if ($typeNav == "login" || $typeNav == "index") {
    echo '<a href="/~kiransingh/project/static/Auth/register.php">register</a>';
  }

  /* City main navTop Links right */
  if ($typeNav == "city_nav") {
    echo '<a href="/~kiransingh/project/static/dashboard/city/profile.php">Profile</a>';
    echo '<a href="/~kiransingh/project/server/auth/logout.php">Logout</a>';
  }

  /* Admin main navTop */
  if ($typeNav == "admin_nav") {
    echo '<a href="/~kiransingh/project/static/dashboard/admin/actions.php">Actions</a>';
    echo '<a href="/~kiransingh/project/server/auth/logout.php">Logout</a>';
  }
  ?>
</nav>
