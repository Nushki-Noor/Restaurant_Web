<?php
  include '../../Connection.php';

  
  session_start();
  if (!isset ($_SESSION["user_email"])) {
    header("../../login.php");
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Add Staff</title>
  <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
  <link rel="stylesheet" href="../css/AddStaff.css">
</head>
<body>


<?php include '../../Admin/php/Sidebar.php'; ?>

<div>
  <h1 class="heading"><span> Add Staff</span> </h1>
</div>

<div class="container">
    <div class="title">Staff Registration</div>

    <div class="content">
      <form action="#" method="POST">
        <div class="user-details">

          <div class="input-box">
            <span class="details">Full Name</span>
            <input type="text" name="name" placeholder="Enter Name" required>
          </div>

          <div class="input-box">
            <span class="details">Email</span>
            <input type="text" name="email" placeholder="Enter Email" required>
          </div>

          <div class="input-box">
            <span class="details">Address</span>
            <input type="text" name="address" placeholder="Enter Address" required>
          </div>

          <div class="input-box">
            <span class="details">Contact</span>
            <input type="number" name="contact" placeholder="Enter Contact" required>
          </div>

          <div class="input-box">
            <span class="details">Password</span>
            <input type="password" name="password" placeholder="Enter Password" required>
          </div>
          
          <div class="input-box">
            <span class="details">Confirm Password</span>
            <input type="password" name="confirmPassword" placeholder="Enter Confirm Password" required>
          </div>
        </div>

        <div class="roles-details">
          <input type="radio" name="role" id="dot-1" value="Manager">
          <input type="radio" name="role" id="dot-2" value="Kitchen Manager">
          <span class="roles-title">Staff Role</span>  

          <div class="option">
            <label for="dot-1">
            <span class="dot one"></span>
            <span class="role">Manager</span>
          </label>
          
          <label for="dot-2">
            <span class="dot two"></span>
            <span class="role">Kitchen Manager</span>
          </label>
          </div>
        </div>

        <div class="button">
          <input type="submit" name="btn-Register" value="Register Staff">
        </div>

      </form>
    </div>
  </div>

</body>
</html>


<?php

if (isset($_POST['btn-Register'])) {

  $name = $_POST["name"];
  $email = $_POST["email"];
  $address = $_POST["address"];
  $contact = $_POST["contact"];
  $userType = $_POST["role"];
  $password = $_POST["password"];
  $conPassword = $_POST["confirmPassword"];

  
  if (invalidName ($name)) {
    echo '<script>alert("Only use A-Z or a-z");</script>';

  } else if(invalidEmail ($conn,$email)) {
    echo '<script>alert("Email already used!!");</script>';

  // } else if (invalidContact ($contact)) {
  //   echo '<script>alert("Invalid Contact Number");</script>';

  } else if (strlen($password)<5){
    echo '<script>alert("Passwords should contain more than 5 characters");</script>';

  } else if ($password !== $conPassword) {
    echo '<script>alert("Passwords do not match");</script>';

  } else {
     registerStaff($conn,$name,$email,$address,$contact,$userType,$password);
  }

}


function invalidName ($name) {
  $value;

    if (!preg_match("/^[a-zA-Z\s]+$/",$name)) {
      $value=true;

    } else {
      $value=false;
    }
    return $value;

}

  function invalidEmail ($conn,$email) {
    $value;

    $sql = "SELECT * FROM user WHERE email =? ;";
    $stmt = mysqli_stmt_init ($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header ("location: ./register.php?err=Field_stmt");
      exit();

    } else {
      mysqli_stmt_bind_param($stmt,"s", $email);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($result) == 0) {
        $value = false;
      } else {
        $value = true;
      }
      mysqli_stmt_close($stmt);
      return $value;

    }
  }
  

  // function invalidContact ($contact) {
  //   $value;

  //   if (!preg_match("/^d{10}$/",$contact)) {
  //     $value=true;
  //   } else {
  //     $value=false;
  //   }
  //   return $value;
  // }

  function registerStaff ($conn,$name,$email,$address,$contact,$userType,$password) {

  
   $passHased = password_hash($password, PASSWORD_DEFAULT);

   $sql = "INSERT INTO user (name,email,address,contact,usertype,password) VALUES (?,?,?,?,?,?) ;";

   $stmt = mysqli_stmt_init ($conn);
   if (!mysqli_stmt_prepare($stmt, $sql)) {
     header ("location: register.php?err=FailedStmt");
     exit();

   } else {
     mysqli_stmt_bind_param($stmt,"sssiss",$name,$email,$address,$contact,$userType,$passHased);
     mysqli_stmt_execute($stmt);
     mysqli_stmt_close($stmt);

     echo '<script>alert("Registered Sucessfully!!");</script>';
  

   }
 }

?>