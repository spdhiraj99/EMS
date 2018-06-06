<?php
session_start();
if(!isset($_SESSION['username'])){
  header('location:login.php');
}
if(isset($_GET['logout'])){
  $_SESSION=array();
  session_destroy();
  header('location: index_guest.php');
}
 ?>
 <!DOCTYPE html>
 <html>
 <head>
   <title>admin Panel</title>
 <!--  <link rel="stylesheet" type="text/css" href="style.css"> -->
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <link href="css/bootstrap.min.css" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <style type="text/css">
     .wrapper{
         width: 650px;
         margin: 0 auto;
     }
     .page-header h2{
         margin-top: 0;
     }
     table tr td:last-child a{
         margin-right: 15px;
     }
     .form-actions {
     margin: 10px;
     height: 20px
     background-color: transparent;
     text-align: center;
     }
     /* Dropdown Button */
 .dropbtn {
     background-color: #222;
     color: #999;
     padding: 16px;
     font-size: 16px;
     border: none;
 }

 /* The container <div> - needed to position the dropdown content */
 .dropdown {
     position: relative;
     display: inline-block;
 }

 /* Dropdown Content (Hidden by Default) */
 .dropdown-content {
     display: none;
     position: absolute;
     background-color: #222;
     min-width: 160px;
     box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
     z-index: 1;
 }

 /* Links inside the dropdown */
 .dropdown-content a {
     color: #999;
     padding: 12px 16px;
     text-decoration: none;
     display: block;
 }

 /* Change color of dropdown links on hover */
 .dropdown-content a:hover {background-color: #444;color:white;}

 /* Show the dropdown menu on hover */
 .dropdown:hover .dropdown-content {
     display: block;
 }

 /* Change the background color of the dropdown button when the dropdown content is shown */
 .dropdown:hover .dropbtn {
     background-color: #444;
     color:white;
 }
 </style>
 </head>
 <body>
   <!-- navigation bar -->
   <nav class="navbar navbar-inverse navbar-static-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="index_admin.php">Andromeda Innovators</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
        <?php if(isset($_SESSION['username'])) : ?>
          <li class="navbar-brand">Welcome <strong><?php echo $_SESSION['username']; ?></strong></li>
            <li><a href="dbhandle.php">Manage DB</a></li>
            <li><a href="register.php">Register a client</a></li>
            <li><a href="index_admin.php?logout='1'">logout</a></li>
            <li><a href="admin_panel.php?mode=prochk">Production check</a></li>
            <li><a href="admin_panel.php?mode=dispatch">Dispatch</a></li>
            <li><a href="mastersearch.php">Mastersearch</a></li>
            <li>
              <div class="dropdown">
                <button class="dropbtn">View</button>
                 <div class="dropdown-content">
                   <a href="enter.php?view=admin">View Master stock</a>
                   <a href="admin_panel.php?mode=view">View client stocks</a>
                   <a href="viewclients.php?view=clients">View clients/products</a>
                 </div>
              </div>
            </li>
        </div>
      <?php endif ?>
      </ul>
    </div>
  </div>
 </nav>
 <!-- navigation ends -->
 <div class="wrapper">
  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="page-header clearfix">
                <h2 class="pull-left">Stock management</h2>
            </div>
            <?php
            // Include config file
            if(isset($_GET['view'])){
              $tablename=$_GET['view']."table";
              view($tablename);
              unset($_POST['client']);
              unset($_POST['submit']); ?>
              <a class="pull-right" href="index_admin.php">Go back to home page</a>
              <a class="pull-left" href="admin_panel.php?mode=view">Check another client</a>
  <?php     }
            if(isset($_POST['pull'])){

            }
            if(isset($_GET['mode'])){
              if($_GET['mode']=="view"){
                if(isset($_POST['sub'])){
                  $tablename=$_POST['client']."table";
                  view($tablename);
                  unset($_POST['client']);
                  unset($_POST['submit']); ?>
                  <a class="pull-right" href="index_admin.php">Go back to home page</a>
                  <a class="pull-left" href="admin_panel.php?mode=view">Check another client</a>
                <?php }
                else{ ?>
                  <div>
                    <form method="POST" class="form-group" action="<?php echo $_SERVER['PHP_SELF'] ?>?mode=view">
                      <div class="col-md-4">
                        <input type="text" class="form-control" name="client" placeholder="Enter the client's name">
                      </div>
                      <input type="submit" class="btn btn-success btn-sm" name="sub" value="view stocks">
                    </form>
                  </div>
                <?php  }
            }
            else if($_GET['mode']=="prochk"){
              if(isset($_POST['chk'])){
                $wish=$_POST['wish'];
                $me=$_POST['me'];
                prochk($wish,$me);
                unset($_POST['wish']);
                unset($_POST['me']);
                unset($_POST['chk']); ?>
                <a class="pull-right" href="index_admin.php">Go back to home page</a>
                <a class="pull-left" href="admin_panel.php?mode=prochk">Check another Product</a>
<?php
              }
              else{ ?>
                <div>
                  <form method="POST" class="form-group" action="<?php echo $_SERVER['PHP_SELF'] ?>?mode=prochk">
                    <div class="col-md-12">
                      <label>How many boards Do you wish to produce? </label>
                    </div>
                    <div class="col-md-4">
                      <input type="text" class="form-control" name="wish" placeholder="Quantity">
                    </div>
                  <div class="col-md-12">
                    <p></p>
                    <label>Which product? </label>
                  </div>
                  <div class="col-md-4">
                    <input type="text" class="form-control" name="me" placeholder="product">
                  </div>
                  <div class="col-md-12">
                    <p></p>
                    <input type="submit" class="btn btn-success btn-sm" name="chk" value="Check">
                  </div>
                  </form>
                </div>
        <?php   }
            }
            else if($_GET['mode']=="dispatch"){
                if(isset($_POST['dis'])){
                  $disquan=$_POST['disquan'];
                  $dispro=$_POST['dispro'];
                  if(checkbdispatch($disquan,$dispro)){
                    dispatch($disquan,$dispro);
                    unset($_POST['disquan']);
                    unset($_POST['dispro']);
                    unset($_POST['dis']); ?>
                  <table class='table table-bordered table-striped'>
                    <tbody>
                      <tr> <!--wastage->pull and pull->wastage -->
                        <td>Was there any wastage?</td>
                        <td><a href="pull.php?product=<?php echo $dispro ?>" target="_blank"><font colour="green">Yes</font></a></td>
                      </tr> <!--wastage->pull and pull->wastage -->
                    </tbody>
                  </table>
                  <a class="pull-right" href="index_admin.php">Go back to home page</a>
                  <a class="pull-left" href="admin_panel.php?mode=dispatch">Dispatch another Product</a>
          <?php
      }  }
                else{ ?>
                  <div>
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>?mode=dispatch">
                      <div class="col-md-12">
                        <label>How many boards did you dispatch?</label>
                      </div>
                      <div class="col-md-5">
                        <input type="text" class="form-control" name="disquan" placeholder="Quantity">
                      </div>
                      <div class="col-md-12" >
                        <label>Which product? </label>
                      </div>
                      <div class="col-md-5">
                        <input type="text" class="form-control" name="dispro" placeholder="product">
                      </div>
                      <div class="col-md-12">
                        <p></p>
                        <input type="submit" class="btn btn-success btm-sm" name="dis" value="Dispatch">
                      </div>
                    </form>
                  </div>
          <?php }
            }
          } ?>
        </div>
    </div>
  </div>
</div>

<!--function modules -->
<?php
function checkbdispatch($disquan,$dispro){
  define('DBS_SERVER', 'localhost');
  define('DBS_USERNAME', 'root');
  define('DBS_PASSWORD', 'root');
  define('DBS_NAME', 'androclients');
  define('AltS_DB_NAME', 'searchdb');

  /* Attempt to connect to MySQL database */
  try{
      $pdo = new PDO("mysql:host=" . DBS_SERVER . ";dbname=" . DBS_NAME, DBS_USERNAME, DBS_PASSWORD);
      // Set the PDO error mode to exception
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e){
      die("ERROR: Could not connect. " . $e->getMessage());
  }
  $tablename=$dispro."table";
  $redir=false; $retval=false;
  $sql="select * from $tablename";
  $result=$pdo->query($sql);
  while($row=$result->fetch()){
    $req=$disquan*$row['qtypb'];
    if(($row['qty']==0)||(($row['qty']-$req)<0)){
      $redir=true;
      break;
    }
  }
  if($redir){
    unset($_POST['dis']);
    echo "<script>
    alert('Dispatch is not possible click ok to pull first! then try to dispatch later');
    window.location.href='wastage.php?product=$dispro';
    </script>";
  }
  else{
    $retval=true;
  }
  return $retval;
}
function view($tablename){
  require_once 'config.php';
$sql = "SELECT partValue,Package,type,qty,qtypb,comments FROM $tablename ORDER BY type";
if($result = $pdo->query($sql)){
    if($result->rowCount() > 0){
        echo "<table class='table table-bordered table-striped'>";
            echo "<thead>";
                echo "<tr>";
                    echo "<th>partValue</th>";
                    echo "<th>Package</th>";
                    echo "<th>type</th>";
                    echo "<th>qty</th>";
                    echo "<th>qty per board</th>";
                    echo "<th>comments</th>";
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            while($row = $result->fetch()){
                echo "<tr>";
                    echo "<td>" . $row['partValue'] . "</td>";
                    echo "<td>" . $row['Package'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    echo "<td>" . $row['qty'] . "</td>";
                    echo "<td>" . $row['qtypb'] . "</td>";
                    echo "<td>" . $row['comments'] . "</td>";
                echo "</tr>";
            }
            echo "</tbody>";
        echo "</table>";
        // Free result set
        unset($result);
    } else{
        echo "<p class='lead'><em>No records were found.</em></p>";
    }
  } else{
    echo "ERROR: Not able to execute $sql. " . $mysqli->error;
  }
// Close connection
  unset($pdo);
}
function prochk($qty,$pro){
    require_once('config.php');
    $tablename=$pro."table";
  $sql = "SELECT partValue,Package,type,qty,qtypb FROM $tablename ORDER BY type";
  if($result = $pdo->query($sql)){
      if($result->rowCount() > 0){
          echo "<table class='table table-bordered table-striped'>";
              echo "<thead>";
                  echo "<tr>";
                      echo "<th>partValue</th>";
                      echo "<th>Package</th>";
                      echo "<th>type</th>";
                      echo "<th>qty per board</th>";
                      echo "<th>Required Quantity</th>";
                      echo "<th>Available qty in stock</th>";
                      echo "<th>Available qty in  master stock</th>";
                      echo "<th>Possible by product's stock?</th>";
                      echo "<th>Possible by master stock?</th>";
                  echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while($row = $result->fetch()){
                  echo "<tr>";
                      echo "<td>" . $row['partValue'] . "</td>";
                      echo "<td>" . $row['Package'] . "</td>";
                      echo "<td>" . $row['type'] . "</td>";
                      echo "<td>" . $row['qtypb'] . "</td>";
                      $need=$row['qtypb']*$qty;
                      echo "<td>" . $need . "</td>";
                      echo "<td>" . $row['qty'] . "</td>";
                      $pv=$row['partValue'];
                      $pc=$row['Package'];
                      $mastersql="SELECT qty FROM `masterstock` WHERE `partValue`='$pv' AND `Package`='$pc'";
                      $res=$pdo->query($mastersql);
                      $rw=$res->fetch();
                      if($pc==NULL){ ?>
                          <td><a href="mastersearch.php?partValue=<?php echo $pv ?>"target="_blank"><?php echo $rw['qty'] ?></td>
                    <?php  }else if($pv==NUll) {
                      ?>
                        <td><a href="mastersearch.php?Package=<?php echo $pc ?>"target="_blank"><?php echo $rw['qty'] ?></td>
                  <?php } else { ?>
                      <td><a href="mastersearch.php?partValue=<?php echo $pv ?>&Package=<?php echo $pc ?>" target="_blank"><?php echo $rw['qty'] ?></td>
                <?php }
                      $poss_client=$poss_master=false;
                      if(($need-$row['qty'])>0){
                        $poss_client=true;
                      }
                      if(($need-$rw['qty'])>0){
                        $poss_master=true;
                      }
                      if(!$poss_client)
                        echo "<td><font color='green'>Possible</font></td>";
                      else
                        echo "<td><font color='red'>Not Possible</font></td>";
                      if(!$poss_master)
                        echo "<td><font color='green'>Possible</font></td>";
                      else
                        echo "<td><font color='red'>Not Possible</font></td>";
                  echo "</tr>";
              }
              echo "</tbody>";
          echo "</table>";
          // Free result set
          unset($result);
      } else{
          echo "<p class='lead'><em>No records were found.</em></p>";
      }
    } else{
      echo "ERROR: Not able to execute $sql. " . $mysqli->error;
    }
  // Close connection
    unset($pdo);
}
function dispatch($disquan,$dispro){
  $tablename=$dispro."table";
  require_once 'config.php';
  $sql = "SELECT partValue,Package,type,qty,qtypb FROM $tablename";
  if($result = $pdo->query($sql)){
    while($row = $result->fetch()){
      $rowqty=$row['qty'];$rowqtyorg=$row['qty'];
      $pv=$row['partValue'];$pc=$row['Package'];
      $dispatchedqty=$row['qtypb']* $disquan;
    /*if($rowqty<$dispatchedqty){ ?>
        <form method="POST" action="admin_panel.php?mode=dispatch" target="_blank" >
          <p>from which stock did u pull <?php echo " ".$row['partValue']." ".$row['Package'] ?> ?</p>
          <input type="text" class="form-control" name="stkpull" placeholder="stock name">
          <p>How much?</p>
          <input type="number" class="form control" name="qtypull" placeholder="stock Quantity">
          <input type="submit" name="pull" value="ok">
        </form> <?php
        if(isset($_POST['pull'])&&isset($_POST['qtpull'])){
          $pulltb=$_POST['pull']."table";
          $pullqt=$_POST['qtypull'];
          $sql="UPDATE `$pulltb` SET qty='qty - $pullqt' Where partValue= '$pv' AND Package= '$pc' ";
          $pdo->query($sql);
          $rowqty+=$pullqt;
        }
      }
      */
      if($rowqty>=$dispatchedqty)
        $sql="UPDATE `$tablename` SET qty=(qty- $dispatchedqty ) WHERE partValue= '$pv' AND Package= '$pc' ";
      else {
        $sql="UPDATE `$tablename` SET `qty`=0 WHERE partValue= '$pv' AND Package= '$pc' ";
      }
      $pdo->query($sql);
        $sql="UPDATE `masterstock` SET qty=(qty-$rowqty) WHERE partValue= '$pv' AND Package= '$pc'";
      $pdo->query($sql);
    }
  }
  echo '<script language="javascript" type="text/javascript"> ';
  echo 'if(!alert("successfully dispatched")) {' ;//msg
  echo '}';
  echo '</script>';
}
?>

<!--end -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
