<?php
session_start();
if(!isset($_SESSION['username'])){
  header('location:login.php');
}
else if($_SESSION['username']!="admin"){
  $_SESSION=array();
  header('location:login.php');
}
if(isset($_GET['logout'])){
  $_SESSION=array();
  session_destroy();
  header('location:login.php');
}
 ?>
<!DOCTYPE html>
<html>
<head>
  <title>Welcome</title>
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

<?php
  if(!empty($_GET)){
    if(isset($_GET['mode'])){
      if($_GET['mode']=="delete"){
        $tablename=$_GET['table'];
        require_once 'config.php';
        masterhandle($tablename);
        $sql="TRUNCATE table $tablename";
        $pdo->query($sql);
        header('location:dbhandle.php');
      }
      else if($_GET['mode']=="drop"){
        $tablename=$_GET['table'];
        require_once 'config.php';
        masterhandle($tablename);
        $sql="DROP table $tablename";
        $alt_sql="DELETE FROM `details` WHERE `tablename`='$tablename'";
        $pdo->query($sql);
        $alt_pdo->query($alt_sql);
        header('location:dbhandle.php');
      }
    }
  }
  function masterhandle($tablename){
    require 'config.php';
    $sql = "SELECT * FROM $tablename";
    if($result = $pdo->query($sql)){
      while($row = $result->fetch()){
        $pv=$row['partValue'];
        $pc=$row['Package'];
        $qty=$row['qty'];
        $qtypb=$row['qtypb'];
        $sql="UPDATE `masterstock` SET qty=(qty-$qty),qtypb=(qtypb-$qtypb) WHERE partValue='$pv' AND Package='$pc'";
        $pdo->query($sql);
        $zero=0;
        $sql="SELECT * FROM masterstock WHERE `qty`=0 AND `qtypb`=0";
        $re=$pdo->query($sql);
        while($r=$re->fetch()){
          $pv=$r['partValue'];
          $pc=$r['Package'];
          $s="DELETE FROM masterstock WHERE partValue='$pv' AND Package='$pc'";
          $pdo->query($s);
        }
      }
  }
}
  require_once 'config.php';
  $sql = "SELECT * FROM `details`";
  if($result = $alt_pdo->query($sql)){
      if($result->rowCount() > 0){
          echo "<table class='table table-bordered table-striped'>";
              echo "<thead>";
                  echo "<tr>";
                      echo "<th>Client/product id</th>";
                      echo "<th>Client/product name</th>";
                      echo "<th>Action</th>";
                  echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while($row = $result->fetch()){
                  $cln=str_replace("table","",$row['tablename']);
                  $n=$row['tablename'];
                  echo "<tr>";
                      echo "<td>" . $row['id'] . "</td>";
                      echo "<td>"; ?>
                      <a href="admin_panel.php?view=<?php echo $cln ?>" > <?php echo $cln ?> </a> </td>
                      <td><a href="dbhandle.php?mode=delete&table=<?php echo $n ?>"><strong><font color=#ffa500>Empty</font></strong></a>
                      <a href="dbhandle.php?mode=drop&table=<?php echo $n ?>"><strong><font color="red">Delete</font></a></strong></td>
                  <?php
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

 ?>

<!--end -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
