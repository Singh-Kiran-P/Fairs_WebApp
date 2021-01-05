<nav class="topNavbar">
  <!-- Navigation Bar -->
  <div class="logo">
    <?php
    echo '<a href="/~kiransingh/project/static/dashboard/visitor/profile.php">';
    ?>
    <img src="/~kiransingh/project/static/img/fair.png" alt="">
    </a>
  </div>

  <!-- navbar Links for gemeente middle -->
  <?php
  /* Visitor main navTop Links middle */

  if ($typeNav != "searchFair")
    echo '<a class="mostLeft line" href="/~kiransingh/project/static/dashboard/visitor/searchFairs.php">Search Fair</a>';
  else
    echo '<div class="line"></div>';

  ?>
  <?php
  /* Visitor main navTop Links right */
  echo '<a href="/~kiransingh/project/static/dashboard/visitor/profile.php">Profile</a>';
  echo '<a href="/~kiransingh/project/server/auth/logout.php">Logout</a>';

  ?>
</nav>
