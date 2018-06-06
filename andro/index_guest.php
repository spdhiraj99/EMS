<?php
session_start();
if(!isset($_SESSION['username'])){
  header('location:login.php');
}
else if($_SESSION['username']=="admin"){
  header('location:index_admin.php');
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
  <title>Welcome</title>
<!--  <link rel="stylesheet" type="text/css" href="style.css"> -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
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
</style>
</head>
<body>
  <!-- navigation bar -->
  <nav class="navbar navbar-inverse navbar-static-top">
 <div class="container">
   <div class="navbar-header">
     <a class="navbar-brand" href="index_guest.php">Andromeda Innovators</a>
   </div>
   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
     <ul class="nav navbar-nav navbar-right">
       <?php if(isset($_SESSION['username'])) : ?>
         <li class="navbar-brand">Welcome <strong><?php echo $_SESSION['username']; ?></strong></li>
         <li><a href="index_guest.php?logout='1'">logout</a></li>
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
                <h2 class="pull-left">Your current Stock</h2>
                <h2 class="pull-right"><a href="enter.php?user=client">Enter stock</a></h2>
            </div>
            <?php
            // Include config file
            require_once 'config.php';
            $tablename=$_SESSION['username']."table";
            // Attempt select query execution
            try {
                $result = $pdo->query("SELECT 1 FROM $tablename LIMIT 1");
                $count=2;
            }catch (Exception $e) {
                $count=0;
            }
            if($count>1){
            $sql = "SELECT partValue,Package,type,qty,comments FROM $tablename";
            if($result = $pdo->query($sql)){
                if($result->rowCount() > 0){
                    echo "<table class='table table-bordered table-striped'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>partValue</th>";
                                echo "<th>Package</th>";
                                echo "<th>type</th>";
                                echo "<th>qty</th>";
                                echo "<th>comments</th>";
                                echo "<th>Action</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = $result->fetch()){
                            echo "<tr>";
                                echo "<td>" . $row['partValue'] . "</td>";
                                echo "<td>" . $row['Package'] . "</td>";
                                echo "<td>" . $row['type'] . "</td>";
                                echo "<td>" . $row['qty'] . "</td>";
                                echo "<td>" . $row['comments'] . "</td>";
                                echo "<td>Action</td>";
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
          }else{
            echo "<p class='lead'><em>No records were found.</em></p>";
          }
            // Close connection
            unset($pdo);
            ?>
        </div>
    </div>
  </div>
</div>


<!--end -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
