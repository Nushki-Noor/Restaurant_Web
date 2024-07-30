<?php
     include "../Connection.php";
     session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ceylon Spice - Login</title>
    <link rel="shortcut icon" href="../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="Login.css">
</head>

<body>
    <div class="container">
        <div class="backg1"></div>
        <form method="POST" action="">

            <div class="login1">
                <h1>Login</h1>
            </div>

            <div class="input-container">
                 <ion-icon name="mail-outline"></ion-icon> 
                 <input type="text" placeholder="Email" name="email" id="email" required>
               
            </div>

            <div class="input-container">
                 <ion-icon name="lock-closed-outline"></ion-icon> 
                 <input type="password" placeholder="Password" name="password" id="password" required>
              
            </div>

            <div class="register">
                <p>Don't Have an Account? <a href="../Register/register.php">Sign Up</a></p>
        </div>

            <input type="submit" name="btn-login" class="loginbtn" value="Login">
        </form>
    </div>
    
</body>
</html>


<?php

if (isset($_POST["btn-login"])) {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM user WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt,"s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if($row) {
            $passHashed = $row["password"];

            if(password_verify($password,$passHashed )) {

               $uName= $row["name"];
               $uEmail= $row["email"];
               $uType= $row["usertype"];
               $uId= $row["id"];
               $uContact = $row["contact"];
               $uAddress = $row["address"];

                $_SESSION["user_name"] = $uName;
                $_SESSION["user_email"]= $uEmail;
                $_SESSION["user_type"] = $uType;
                $_SESSION["user_id"] = $uId;
                $uContact = $_SESSION["user_contact"] = $uContact;
                $uAddress = $_SESSION["user_address"] = $uAddress;

                if ($uType == "Admin") {
                    header("location: ../Admin/php/Admin.php");
                } else if($uType == "Manager") {
                    header("location: ../Staff/php/Manager.php");
                } elseif ($uType == "Kitchen Manager") {
                    header("location: ../Staff/php/KTManager.php");
                } else if ($uType == "Customer") {
                    header("location: ../Customer/php/Home.php");
                }
            } else {
                echo '<script>alert("Invalid Password");</script>';
            }

        } else {
            echo '<script>alert("Invalid Email");</script>';
        }
    } else {
        header ('location: login.php?err=FailedStmt');
        exit;
    } 
    mysqli_stmt_close($stmt);
}
?>
