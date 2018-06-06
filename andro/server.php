<?php
session_start();
$username="";
$email="";
$errors=array();
$db = mysqli_connect('localhost','root','root','AndroClients');
  if(isset($_POST['reg_user'])){
    $username=mysqli_real_escape_string($db,$_POST['username']);
    $password_1=mysqli_real_escape_string($db,$_POST['password_1']);
    $password_2=mysqli_real_escape_string($db,$_POST['password_2']);
    if(empty($username)){array_push($errors,"username is required");}
    if(empty($password_1)){array_push($errors,"password is required");}
    if($password_1!=$password_2){array_push($errors,"The two passwords do not match");}
    $sql="SELECT * FROM users WHERE username = '$username' OR email= '$email' LIMIT 1";
    $result = mysqli_query($db,$sql);
    $user =mysqli_fetch_assoc($result);
      if($user){
      if($user['username']===$username){array_push($errors,"Username already exists");}
      if($user['email']===$email){array_push($errors,"email already exists");}
    }
    if(count($errors) ==0){
      $password = md5($password_1);
      $sql="Insert INTO users (username, password) VALUES('$username', '$password')";
      mysqli_query($db,$sql);
      $_SESSION['username'] = $username;
        header('location:index_guest.php');
    }
  }
  //for Login
  if(isset($_POST['login_user'])){
    $username= mysqli_real_escape_string($db,$_POST['username']);
    $password= mysqli_real_escape_string($db,$_POST['password']);
    if(empty($username)){
        array_push($errors,"Username is Required");
    }
    if(empty($password)){
        array_push($errors,"Password is required");
    }
    if(count($errors)==0){
      $password=md5($password);
      $query="SELECT * FROM users WHERE username='$username' AND password='$password'";
      $results=mysqli_query($db,$query);
      if(mysqli_num_rows($results)==1){
        $_SESSION['username']=$username;
        $_SESSION['success']="You are now logged in";
        if($_SESSION){
          if($_SESSION['username']=="admin"){
            header('location:index_admin.php');
          }
          header('location: index_guest.php');
        }
      }
      else{
        array_push($errors,"Wrong username/password combination");
      }
    }
    }
 ?>
