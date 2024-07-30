<?php
  include "../Connection.php";

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Ceylon Spice - Sign up </title> 
    <link rel="shortcut icon" href="../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="register.css">
   </head>
   <body>
  <div class="container">
    <div class="backg1"></div>
    <form action="#" method="POST">
      <div class="login1">
        <h1>Sign Up</h1>
      </div>

      <div class="input-container">
        <ion-icon name="person-outline"></ion-icon>
        <input type="text" id="name" name="name" placeholder="Enter your name" required>
      </div>

      <div class="input-container">
        <ion-icon name="mail-outline"></ion-icon>
        <input type="text" id="email" name="email" placeholder="Enter your email" required>
      </div>

      <div class="input-container">
        <ion-icon name="home-outline"></ion-icon>
        <input type="text" id="address" name="address" placeholder="Enter your Address" required>
      </div>

      <div class="input-container">
        <ion-icon name="call-outline"></ion-icon>
        <input type="number" id="phoneNumber" name="phoneNumber" placeholder="Phone Number" required>
      </div>

      <div class="input-container">
        <ion-icon name="lock-closed-outline"></ion-icon>
        <input type="password" id="password" name="password" placeholder="Create password" required>
      </div>

      <div class="input-container">
        <ion-icon name="lock-closed-outline"></ion-icon>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" required>
      </div>

      <div class="register">
        <p>Already have an account? <a href="../Login/login.php">Login now</a></p>
      </div>

      <input type="Submit" name="btn-register" class="loginbtn" value="Sign Up">
    </form>
  </div>
</body>
</html>


<?php

 if (isset($_POST["btn-register"])) {

        $name = $_POST["name"];
        $email = $_POST["email"];
        $address = $_POST["address"];
        $contact = $_POST["phoneNumber"];
        $password = $_POST["password"];
        $conPassowrd = $_POST["confirmPassword"];


        if (invalidName ($name)) {
          echo '<script>alert("Only use A-Z or a-z");</script>';

        } else if(invalidEmail ($conn,$email)) {
          echo '<script>alert("Email already used!!");</script>';

        // } else if (invalidContact ($contact)) {
        //   echo '<script>alert("Invalid Contact Number");</script>';

        } else if (strlen($password)<5){
          echo '<script>alert("Passwords should contain more than 5 characters");</script>';

        } else if ($password !== $conPassowrd) {
          echo '<script>alert("Passwords do not match");</script>';

        } else {
          registerUser($conn,$name,$email,$address,$contact,$password);
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

  function registerUser ($conn,$name,$email,$address,$contact,$password) {

     $userType = "Customer";
    // $userType = "Admin";

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

      echo '<script>alert("Registered Sucessfully!")</script>';

    }
  }

?>