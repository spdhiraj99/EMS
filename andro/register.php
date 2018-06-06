<?php include('server.php') ?>
<?php
  if($_SESSION['username']!="admin"){
    header('location:login.php');
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <!-- <link rel="stylesheet" type="text/css" href="style.css"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <!-- navigation bar -->
  <nav class="navbar navbar-inverse navbar-static-top">
 <div class="container">
   <div class="navbar-header">
     <a class="navbar-brand" href="index.php">Andromeda Innovators</a>
   </div>
   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
     <ul class="nav navbar-nav navbar-right">
       <?php if(isset($_SESSION['username'])) : ?>
         <li class="navbar-brand">Welcome <strong><?php echo $_SESSION['username']; ?></strong></li>
         <li><a href="index.php?logout='1'">logout</a></li>
       </div>
     <?php endif ?>
     </ul>
   </div>
 </div>
</nav>
<!-- navigation ends -->

<div class="container col-md-4 col-md-offset-4">
  <form method="post" action="register.php">
    <?php include('errors.php')?>
    <div class="input-group">
      <label class="control-label">Username</label>
      <input type="text" name="username" class="form-control" value="<?php if(isset($_SESSION['username'])) echo $username;?>">
    </div>
    <div class="input-group">
      <label class="control-label">Password</label>
      <input type="password" class="form-control" name="password_1">
    </div>
    <div class="input-group">
      <label class="control-label">Confirm Password</label>
      <input type="password" class="form-control" name="password_2">
    </div>
    <div class="input-group">
      <br>
      <button type="submit" class="btn btn-default" name="reg_user">Register Client</button>
    </div>
  </form>
</div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</body>
</html>
