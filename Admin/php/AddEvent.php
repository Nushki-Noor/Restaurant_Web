<?php
  include "../../Connection.php";

  
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
  <title>Admin - Add Event</title>
  <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
  <link rel="stylesheet" href="../css/AddEvent.css">
</head>
<body>


<?php include '../../Admin/php/Sidebar.php'; ?>

<div>
  <h1 class="heading"><span> Add Events</span> </h1>
</div>

<div class="container">
    <div class="title">Add Event</div>

    <div class="content">
      <form action="#" method="POST" enctype="multipart/form-data">
        <div class="user-details">

          <div class="input-box">
            <span class="details">Event Name</span>
            <input type="text" name="eventName" placeholder="Enter Event Name" required>
          </div>

          <div class="input-box">
            <span class="details">Event Description</span>
            <input type="text" name="eventDesc" placeholder="Enter Event Description" required>
          </div>
          
          <div class="input-box">
            <span class="details">Event Price</span>
            <input type="number" name="eventPrice" placeholder="Enter Event Price" required>
          </div>

          <div class="input-box">
            <span class="details">Date</span>
            <input type="text" name="date"  required>
          </div>

          <div class="input-box">
            <span class="details">Time</span>
            <input type="text" name="time"  required>
          </div>

          <div class="input-box">
            <span class="details">Ticket Availability</span>
            <input type="number" name="availability"  required>
          </div>

          <div class="input-pic">
            <span class="details">Event Picture</span>
            <input type="file"  name="eventImage" id="myFile">
          </div>
        </div>

        <div class="button">
          <input type="submit" name="btn-Add" value="Add Event">
        </div>

      </form>
    </div>
  </div>

</body>
</html>


<?php

if (isset($_POST["btn-Add"])) {

  $name = mysqli_real_escape_string($conn,$_POST["eventName"]);
  $desc = mysqli_real_escape_string($conn,$_POST["eventDesc"]);
  $price = mysqli_real_escape_string($conn,$_POST["eventPrice"]);
  $date = mysqli_real_escape_string($conn,$_POST["date"]);
  $time = mysqli_real_escape_string($conn,$_POST["time"]);
  $ticketAvailability = mysqli_real_escape_string($conn,$_POST["availability"]);


    $fileName = $_FILES['eventImage']['name'];
    $fileTmpName = $_FILES['eventImage']['tmp_name']; 
    $fileType = $_FILES['eventImage']['type']; 

    $fileExt = explode('.',$fileName);

    $fileActualExt = strtolower($fileExt['1']);

    $allowed = array('jpg', 'jpeg', 'png');

    if(in_array($fileActualExt, $allowed)){

          // $imageNewName = uniqid('',true).".".$fileActualExt;
          $filDestination = '../uploads/event/'.$fileName;

          if (move_uploaded_file($fileTmpName, $filDestination)) {
            
            $sql = "INSERT INTO event (name, description,price,date,time,tickets,image) VALUES ('$name', '$desc','$price','$date','$time','$ticketAvailability','$fileName')";

             $query = mysqli_query($conn, $sql);

             if ($query) {
                echo '<script>alert("Event Inserted Successfully")</script>';
             } else {
                echo '<script>alert("Failed to insert event")</script>';
             } 

          } else {
               echo '<script>alert("Sorry, there was an error uploading your file.")</script>';
               exit();
            }
                
     } else {
        echo 'You cannot uploat this tyoe of files';
    }
  
}
?>