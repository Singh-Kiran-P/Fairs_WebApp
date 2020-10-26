<nav class="topNavbar">

  <!-- Navigation Bar -->
  <div class="logo">
    <?php
    if ($typeNav == "gemeente_nav")
      echo '<a href="/~kiransingh/project/static/dashboard/gemeente/profile.php">';
    else if ($typeNav == "user_nav")
      echo "";
    else
      echo '<a href="/~kiransingh/project/static/index.php">';
    ?>
    <img src="/~kiransingh/project/static/img/UHasselt-liggend.jpg" width="200px" alt="">
    </a>
  </div>

  <!-- navbar Links for gemeente middle -->
  <?php
  /* Gemeente main navTop Links middle */
  if ($typeNav == "gemeente_nav") {
    echo '<a class="mostLeft line" href="/~kiransingh/project/static/Auth/register.php">Add Kermis</a>';
    echo '<a class="mostRight" href="/~kiransingh/project/static/Auth/register.php">List of Kermissen</a>';
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

  /* Gemeente main navTop Links right */
  if ($typeNav == "gemeente_nav") {
    echo '<a href="/~kiransingh/project/static/Auth/register.php">Profile</a>';
    echo '<a href="/~kiransingh/project/server/auth/logout.php">Logout</a>';
  }
  ?>
</nav>
