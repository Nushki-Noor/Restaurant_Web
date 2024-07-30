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
  <title>Admin - Add Food</title>
  <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
  <link rel="stylesheet" href="../css/AddFood.css">
</head>
<body>


<?php include '../../Admin/php/Sidebar.php'; ?>

<div>
  <h1 class="heading"><span> Add Food Item</span> </h1>
</div>

<div class="container">
    <div class="title">Add Food</div>

    <div class="content">
      <form action="" method="POST" enctype="multipart/form-data">
        <div class="user-details">

          <div class="input-box">
            <span class="details">Food Name</span>
            <input type="text" name="foodname" placeholder="Enter Food Name" required>
          </div>

          <div class="input-box">
            <span class="details">Normal Price</span>
            <input type="number" name="pricenormal" placeholder="Enter Price" >
          </div>

          <div class="input-pic">
            <span class="details">Food Picture</span>
            <input type="file" name="foodimage" id="myFile" required>
          </div>

          <div class="input-box">
            <span class="details">Medium Price</span>
            <input type="number" name="pricemedium" placeholder="Enter Price" >
          </div>

          <div class="input-box">
            <span class="details">Description</span>
            <textarea id="description" name="fooddescription" placeholder="Enter Description" required></textarea>
          </div>

          <div class="input-box">
            <span class="details">Large Price</span>
            <input type="number" name="pricelarge" placeholder="Enter Price" >
          </div>

          <div class="input-box">
            <span class="details">Food Quantity</span>
            <input type="number" name="foodqty" placeholder="Enter Quantity" required>
          </div>

        </div>

        <div class="food-details">
          <input type="radio" name="foodcategory" id="dot-1" value="Burgers" required>
          <input type="radio" name="foodcategory" id="dot-2" value="Pizza" required>
          <input type="radio" name="foodcategory" id="dot-3" value="Rice" required>
          <input type="radio" name="foodcategory" id="dot-4" value="Pasta" required>
          <input type="radio" name="foodcategory" id="dot-5" value="Sandwich" required>
          <input type="radio" name="foodcategory" id="dot-6" value="Kottu" required>
          <input type="radio" name="foodcategory" id="dot-7" value="Drinks" required>
          <span class="food-title">Category</span>

          <div class="option">
            <label for="dot-1">
            <span class="dot one"></span>
            <span class="food-category">Burgers</span>
          </label>
          
          <label for="dot-2">
            <span class="dot two"></span>
            <span class="food-category">Pizza</span>
          </label>

          <label for="dot-3">
            <span class="dot three"></span>
            <span class="food-category">Rice</span>
          </label>

          <label for="dot-4">
            <span class="dot four"></span>
            <span class="food-category">Pasta</span>
          </label>

          <label for="dot-5">
            <span class="dot five"></span>
            <span class="food-category">Sandwich</span>
          </label>

          <label for="dot-6">
            <span class="dot six"></span>
            <span class="food-category">Kottu</span>
          </label>

          <label for="dot-7">
            <span class="dot seven"></span>
            <span class="food-category">Drinks</span>
          </label>
          </div>
        </div>

        <div class="button">
          <input type="submit" value="Add Food Item">
        </div>

      </form>
    </div>
  </div>

</body>
</html>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $foodname = mysqli_real_escape_string($conn, $_POST["foodname"]);
    $fooddescription = mysqli_real_escape_string($conn, $_POST["fooddescription"]);
    $pricenormal = mysqli_real_escape_string($conn, $_POST["pricenormal"]);
    $pricemedium = mysqli_real_escape_string($conn, $_POST["pricemedium"]);
    $pricelarge = mysqli_real_escape_string($conn, $_POST["pricelarge"]);
    $foodqty = mysqli_real_escape_string($conn, $_POST["foodqty"]);
    $foodcategory = mysqli_real_escape_string($conn, $_POST["foodcategory"]);
  

   if (empty($foodname) || empty($fooddescription)   || empty($foodqty) || empty($foodcategory)) {
    echo '<script>alert("Please fill all the details")</script>';

   } else if(empty($pricenormal)) {

        if (empty($pricemedium) || empty ($pricelarge)) {
          echo '<script>alert("Please provide both medium and large prices if normal price is not provided")</script>';

        } else {
          insertFoodItem($conn, $foodname, $fooddescription,$pricenormal,$pricemedium,$pricelarge,$foodqty,$foodcategory);
        }

  }else if (empty($pricemedium) || empty($pricelarge)) {

        if(empty($pricenormal)){
          echo '<script>alert("Price Can not be blanks")</script>';
        } else {
          insertFoodItem($conn, $foodname, $fooddescription,$pricenormal,$pricemedium,$pricelarge,$foodqty,$foodcategory);
        }

  } else {
    insertFoodItem($conn, $foodname, $fooddescription,$pricenormal,$pricemedium,$pricelarge,$foodqty,$foodcategory);

   }

}


function insertFoodItem ($conn, $foodname, $fooddescription,$pricenormal,$pricemedium,$pricelarge,$foodqty,$foodcategory) {
   $fileName = $_FILES['foodimage']['name'];
    $fileTmpName = $_FILES['foodimage']['tmp_name']; 
    $fileType = $_FILES['foodimage']['type']; 

    $fileExt = explode('.',$fileName);

    $fileActualExt = strtolower($fileExt['1']);

    $allowed = array('jpg', 'jpeg', 'png');

    if(in_array($fileActualExt, $allowed)){

          // $imageNewName = uniqid('',true).".".$fileActualExt;
          $filDestination = '../uploads/foods/'.$fileName;

          // move_uploaded_file($fileTmpName, $filDestination);

          if (move_uploaded_file($fileTmpName, $filDestination)) {
            
            $sql = "INSERT INTO foods (foodname, fooddescription, pricenormal, pricemedium, pricelarge, foodqty, foodcategory, foodimage) VALUES ('$foodname', '$fooddescription', '$pricenormal', '$pricemedium', '$pricelarge', '$foodqty', '$foodcategory', '$fileName')";

             $query = mysqli_query($conn, $sql);

             if ($query) {
                echo '<script>alert("Food Inserted Successfully")</script>';
             } else {
                echo '<script>alert("Failed to insert food")</script>';
             } 

          } else {
               echo '<script>alert("Sorry, there was an error uploading your file.")</script>';
               exit();
            }
                
     } else {
        echo 'You cannot upload this type of file';
    }
    
}
?>
