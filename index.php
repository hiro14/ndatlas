<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>NDAtlas for molecular interaction overlaps between neurodegenerative diseases.</title>
    <link rel="stylesheet" href="packages/bootstrap-4.1.0-dist/css/bootstrap.min.css">
    <script src="packages/jquery/jquery-3.3.1.min.js"></script>
    <script src="packages/popper/popper.min.js"></script>
    <script src="packages/bootstrap-4.1.0-dist/js/bootstrap.min.js"></script>
  </head>
  <body>
    <?php
      
      echo <<<EOF
      <div class='container'>
EOF;
      include ('./comp/nav.php');
      echo "<div class='divider'><p></p></div>";
      if (empty($_GET)) {
        include ('./comp/home.php');
      } else {
        $c = $_GET['c'];
        $comp = './comp/'.$c.'.php';
        switch ($c) {
          case 'home':
            include ("$comp");
            break;
          case 'search':
            include ("$comp");
            break;
          case 'download':
            include ("$comp");
            break;
          case 'about':
            include ("$comp");
            break;
          case 'detail':
            include ("$comp");
            break;
          case 'viewnet':
            include ("$comp");
            break;
          default:
            include ('./comp/404.php');
            break;
        }
      }
      echo <<<EOF
      </div>
      <div class='divider'><p></p></div>
EOF;
      include ('./comp/footer.php');
    ?>
  </body>
</html>
