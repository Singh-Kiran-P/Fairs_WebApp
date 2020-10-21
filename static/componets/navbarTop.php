<nav class="topNavbar">

<!-- Navigation Bar -->
<div class="logo">
  <a href="/~kiransingh/project/static/index.html">
    <img src="/~kiransingh/project/static/img/UHasselt-liggend.jpg" width="200px" alt="">
  </a>
</div>
<div class="line"></div>

<?php
if ($typeNav == "register") {
    echo '<a class="submenu login" href="/~kiransingh/project/static/Auth/login.php">Login</a>';
}

if ($typeNav == "login") {
    echo '<a class="submenu register" href="/~kiransingh/project/static/Auth/register.php">register</a>';
}

?>
</nav>
