<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href=".">
    <img src="image/brand.png" width="30" height="30" class="d-inline-block align-top" alt="">
    NDAtlas
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item" id="home">
        <a class="nav-link" href=".">Home</a>
      </li>
      <li class="nav-item" id="search">
        <a class="nav-link" href="index.php?c=search">Search</a>
      </li>
      <li class="nav-item" id="download">
        <a class="nav-link" href="index.php?c=download">Download</a>
      </li>
      <li class="nav-item" id="about">
        <a class="nav-link" href="index.php?c=about">About</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Try the intelligent search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">I'm feeling lucky</button>
    </form>
  </div>
</nav>

<?php
if (!isset($_GET['c'])) {
  echo ("<script>document.getElementById('home').className = 'nav-item active';</script>");
} else {
  $c = $_GET['c'];
  switch ($c) {
    case 'viewnet':
      echo ("<script>document.getElementById('search').className = 'nav-item active';</script>");
      break;
    case 'detail':
      echo ("<script>document.getElementById('search').className = 'nav-item active';</script>");
      break;
    case '404':
      echo ("<script>document.getElementById('home').className = 'nav-item active';</script>");
      break;
    default:
      echo ("<script>document.getElementById('".$c."').className = 'nav-item active';</script>");
      break;
  }
};
?>
