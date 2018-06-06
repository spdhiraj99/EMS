<?php
    require 'config.php';
    if ( !empty($_GET['pv'])) {
        $pv = $_REQUEST['pv'];
    }
    else {
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
    }else {
      $pro=NULL;
    }
    if ( isset($_POST['sb'])) {
        // keep track post values
        $tableqty = $_POST['pulledqty'];
        // update data.
        if($pc!=NULL)
            $sql = "UPDATE $pro set qty=(qty- $tableqty) WHERE partValue='$pv' AND Package='$pc'";
        else
          $sql = "UPDATE $pro set qty=(qty- $tableqty) WHERE partValue='$pv'";
        $pdo->query($sql);
        if(pc!=NULL)
          $sql = "UPDATE `masterstock` set qty=(qty- $tableqty) WHERE partValue='$pv' AND Package='$pc'";
        else {
          $sql="UPDATE `masterstock` set qty=(qty- $tableqty) WHERE partValue='$pv'";
        }
          $pdo->query($sql);
          $reap=str_replace("table","",$pro);
          header("location:pull.php?product=$reap");
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
                        <h3>Update wastage</h3>
                    </div>

                    <form action="update_pull.php?pv=<?php echo $pv ?>&pc=<?php echo $pc ?>&pro=<?php echo $pro ?>" method="POST">
                          <input type="text" name="pulledqty" placeholder="separate by comma">
                          <input type="submit" name="sb" value="enter">
                   </form>
                </div>
    </div> <!-- /container -->
  </body>
</html>
