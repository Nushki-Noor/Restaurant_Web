<?php
include '../../Connection.php';

session_start();

// if (!isset($_SESSION["user_name"])) {
//   header("location: ../../Login/login.php");
//   exit;
// }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ceylon Spice - Events</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../../Customer/css/Events.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
</head>

<body>

<?php include '../../Customer/php/Navbar.php'; ?>

  <div class="container">
    <h1 class="heading">- Upcoming Events -</h1>
    <div class="events">
      <?php
        $sql = "SELECT * FROM event";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $eventId = $row["id"];
              $name = $row["name"];
              $date = $row["date"];
              $time = $row["time"];
              $price = $row["price"];
              $image = $row["image"];
              
              echo '<div class="card">';
              echo '<img src="../../Admin/uploads/event/' . $image . '" alt="">';
              echo '<div class="details">';
              echo '<h2>' . $name . '</h2>';
              echo '<div class="allbtn">';
              echo '<a href="../php/EventBooking.php?eventId=' . $eventId . '"><button class="seeMore">See More</button></a>';
              echo '</div>';
              echo '</div>';
              echo '</div>';
          }
        }
        else {
          echo '<p>No Upcoming Events</p>';
        }
      ?>
    </div>
  </div>

  
  <?php include '../../Customer/php/Footer.php'; ?>
</body>
</html>
