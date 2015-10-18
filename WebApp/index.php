<!DOCTYPE html>

<?php
include "lib.php";
$bdd=coBdd();
?>
<html>
<html>
<head>
  <title>BUSMASS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/foundation.min.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="fonts/font-awesome.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/bootstrap-theme.css">
  <script src="js/vendor/jquery.js"></script>
  <script src="js/foundation.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="js/vendor/modernizr.js" type="text/javascript"></script>
</head>
<body>
  <script>
  $(document).ready(function () {
    $(document).foundation();
    $('a.left-off-canvas-toggle').on('click',function(){

    });
  })
  </script>
  <div class="off-canvas-wrap docs-wrap" data-offcanvas>
    <div class="inner-wrap">
      <nav class="tab-bar">

        <section class="middle tab-bar-section">
          <a href="/"><img class="mainlogo" src="img/busmass.png"  /></a>
        </section>

        <section class="left-small">
          <a class="left-off-canvas-toggle" ><span><i class="burger fa fa-bars fa-2x"></i></span></a>
        </section>

        <section class="right-small">
          <a class="right"><span><i class="burger fa fa-compass fa-2x"></i></span></a>
        </section>

      </nav>

      <aside class="left-off-canvas-menu">
        <ul class="off-canvas-list">
          <li class="logo"><img src="img/busmass.png"  /></li>
          <li><a href="/"><i class="awicon fa fa-exchange"></i>Lines</a></li>
          <li><a href="#"><i class="awicon fa fa-map-marker"></i>Stops</a></li>
          <li><a href="#"><i class="awicon fa fa-heart"></i>Favorites</a></li>
          <li><a href="/index.php?page=co&id=youri"><i class="awicon fa fa-users"></i>Community</a></li>
          <li><a href="#"><i class="awicon fa fa-cog"></i>Settings</a></li>
          <li><a href="#"><i class="awicon fa fa-question-circle"></i>Help</a></li>
        </ul>
      </aside>
      <?php
      if (isset($_GET['page'])) {
        if ($_GET['page'] == "co") {
          include_once 'chat.php';
        }
      } else {
        include_once 'lines.php';
        if (isset($_GET['line'])) {
          print '<div class="row">
              <div class="small-6 small-centered text-center columns">
                <ul class="stack button-group">
                <li><a href="/" class="button radius">Define another route</a></li>
                <li><a href="/index.php?page=co&id=youri" class="button radius">Join the community</a></li>
              </ul>
            </div>
          </div>';
        }
      }
      ?>
      <a class="exit-off-canvas"></a>
    </div>
  </div>
</body>
</html>
