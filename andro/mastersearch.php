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
   <title>Mastersearch</title>
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
    <form method="POST">
      <div class="form-group row-md-6 col-md-4">
          <label>  Enter Part Value: </label>
          <input type="text" class="form-control" name="pv" placeholder="part">
      </div>
      <div class="row-md-6 col-md-4">
      <label>  Enter Package: </label>
        <input type="text" class="form-control" name="pc" placeholder="Package">
      </div>
      &nbsp;
      <div class="col-md-4">
        <input type="submit" class="btn-success btn btn-sm" name="ch" value="Search">
      </div>
    </form>
<?php

  if(isset($_GET['partValue'])||isset($_GET['Package'])){
    if(isset($_GET['partValue']))
      $_POST['pv']=$_GET['partValue'];
    else
      $_POST['pv']=NULL;
    if(isset($_GET['Package']))
      $_POST['pc']=$_GET['Package'];
    else
      $_POST['pc']=NULL;
    $_POST['ch']=0;
  }
  if(isset($_POST['ch'])){
    if($_POST['pv']==NULL)
    unset($_POST['pv']);
    if($_POST['pc']==NULL)
    unset($_POST['pc']);
    if(isset($_POST['pv'])&&isset($_POST['pc'])){
      $pv=$_POST['pv'];
      $pc=$_POST['pc'];
      ?>
      <table class="table table-bordered table-striped">
        <thead>
          <tr><th>partValue</th><th>Package</th><th>Found in Product</th><th>Quantity</th></tr>
        </thead>
<?php PartPackageCheck($pv,$pc); ?>
      </table>
<?php
    $_POST=array();
    }
    if(isset($_POST['pv'])){
      $pv=$_POST['pv']; ?>
      <table class="table table-bordered table-striped">
        <thead>
          <tr><th>partValue</th><th>Package</th><th>Found in Product</th><th>Quantity</th></tr>
        </thead>
<?php Partcheck($pv); ?>
      </table>
<?php      $_POST=array();
    }
   if(isset($_POST['pc'])){
      $pc=$_POST['pc']; ?>
      <table class="table table-bordered table-striped">
        <thead>
          <tr><th>partValue</th><th>Package</th><th>Found in Product</th><th>Quantity</th></tr>
        </thead>
<?php   Packagecheck($pc); ?>
      </table>
<?php     $_POST=array();
    }
    unset($_POST['ch']);

  } ?>
<?php
?>
  </div>
</div>
<!--functions -->
<?php
function PartPackageCheck($pv,$pc){
  require_once('config.php');
  $str="";
  $sql = "SELECT * FROM `details`";
  $result=$alt_pdo->query($sql);
  while($row=$result->fetch()){
    $tablename=$row['tablename'];
    $name=str_replace("table","",$tablename);
    $q="SELECT qty from $tablename WHERE partValue='$pv' AND Package='$pc' ";
    if($res=$pdo->query($q)){
      $r=$res->fetch();
      $qty=$r['qty'];
      if($qty==0)
        continue;
      echo"<tr>";
      echo "<td> $pv </td> <td> $pc </td> <td> $name </td> <td> $qty </td>";
      echo "</tr>";
    }
  }
  echo $str;
}

function Partcheck($pv){
  require_once('config.php');
  $str="";
  $sql = "SELECT * FROM `details`";
  $result=$alt_pdo->query($sql);
  while($row=$result->fetch()){
    $tablename=$row['tablename'];
    $name=str_replace("table","",$tablename);
    $q="SELECT partValue,Package,qty from $tablename WHERE partValue='$pv'";
    if($res=$pdo->query($q)){
      while($r=$res->fetch()){
        $qty=$r['qty'];
        $pc=$r['Package'];
        if($qty==0)
          continue;
        echo"<tr>";
        echo "<td> $pv </td> <td> $pc </td> <td> $name </td> <td> $qty </td>";
        echo "</tr>";
      }
    }
  }
}

function Packagecheck($pc){
  require_once('config.php');
  $str="";
  $sql = "SELECT * FROM `details`";
  $result=$alt_pdo->query($sql);
  while($row=$result->fetch()){
    $tablename=$row['tablename'];
    $name=str_replace("table","",$tablename);
    $q="SELECT partValue,Package,qty from $tablename WHERE Package='$pc'";
    if($res=$pdo->query($q)){
      while($r=$res->fetch()){
        $qty=$r['qty'];
        $pv=$r['partValue'];
        if($qty==0)
          continue;
        echo"<tr>";
        echo "<td> $pv </td> <td> $pc </td> <td> $name </td> <td> $qty </td>";
        echo "</tr>";
      }
    }
  }
}
?>
<!--function ends -->
<!--end -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
