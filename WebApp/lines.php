<table class="table table-striped">
  <tbody>
    <?php
    if (isset($_GET['path'])) {
      $line=$_GET['line'];
      $poids=recupData($line,$_GET['path'], $bdd);
    }
    else{

      if (isset($_GET['line'])) {
        $path=getPaths($bdd,$_GET['line']);
        viewPath($path,$_GET['line']);
      }
      else{

        $lines=getLines($bdd);
        viewLines2($lines);
      }
    }
    ?>
  </tbody>
</table>
