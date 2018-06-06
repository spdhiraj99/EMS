<?php
    require 'config.php';
    $pc=$pv=NULL;
    if ( !empty($_GET['pv'])) {
        $pv = $_REQUEST['pv'];
    }else{
      $pv=NULL;
    }
    if ( !empty($_GET['pc'])) {
        $pc = $_REQUEST['pc'];
    }
    else {
      $pc=NULL;
    }
    if(!empty($_GET['pro'])){
      $pro=$_REQUEST['pro'];
    }
    else {
      $pro=NULL;
    }
    if ( isset($_POST['sb'])) {
        // keep track post values
        $pulledname = $_POST['pulledname'];
        $pulledqty = $_POST['pulledqty'];
        $tables=explode(",",$pulledname);
        $tableqty=explode(",",$pulledqty);

        // update data
        for($i=0;$i<count($tables);$i++) {
          // code...
          $sql="";
            $tg=$tables[$i]."table";
            if(pc!=NULL)
            $sql = "UPDATE $tg set qty=(qty- $tableqty[$i]) WHERE partValue='$pv' AND Package='$pc'";
            else
              $sql = "UPDATE $tg set qty=(qty- $tableqty[$i]) WHERE partValue='$pv'";
            $pdo->query($sql);
            if(pc!=NULL)
              $sql = "UPDATE `masterstock` set qty=(qty- $tableqty[$i]) WHERE partValue='$pv' AND Package='$pc'";
            else {
                $sql = "UPDATE `masterstock` set qty=(qty- $tableqty[$i]) WHERE partValue='$pv'";
            }
            $pdo->query($sql);
            if($pc!=NULL)
              $sql="UPDATE $pro set qty=(qty+ $tableqty[$i]) Where partValue='$pv' AND Package = '$pc'";
            else {
              $sql="UPDATE $pro set qty=(qty+ $tableqty[$i]) where partValue='$PV' and Package = '$PC'";
            }
            $pdo->query($sql);
          }
          $reap=str_replace("table","",$pro);
          header("location:wastage.php?product=$reap");
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">

                <div class="span10 offset1">
                    <div class="row">
                        <h3>Pulled Stocks</h3>
                    </div>

                    <form action="update_wastage.php?pv=<?php echo $pv ?>&pc=<?php echo $pc ?>&pro=<?php echo $pro ?>" method="POST">
                          <p>From where was it pulled? (separate by comma)</p>
                          <input type="text" name="pulledname" placeholder="separate by comma">
                          <input type="text" name="pulledqty" placeholder="separate by comma">
                          <input type="hidden" name="pv" value="<?php echo $pv ?>">
                          <input type="submit" name="sb" value="enter">
                   </form>
                </div>
    </div> <!-- /container -->
  </body>
</html>
