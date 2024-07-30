<?php
include '../../Connection.php';

session_start();

if (!isset($_SESSION['user_email'])) {
  echo '<script>
      alert("You need to First Login to the system");
      window.location.href = "../../Login/login.php";
  </script>';
exit();
}

if (!isset($_GET["eventId"])) {
    header("location: Events.php");
    exit;
}

$eventId = $_GET["eventId"];
$sql = "SELECT * FROM event WHERE id = $eventId";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "Event not found";
    exit;
}

$name = $row["name"];
$date = $row["date"];
$time = $row["time"];
$price = $row["price"];
$tickets = $row["tickets"];
$image = $row["image"];
$description = $row["description"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $name; ?> - Ceylon Spice</title>
  <link rel="stylesheet" href="../css/EventBooking.css">
  <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
</head>

<body>

<?php include '../../Customer/php/Navbar.php'; ?>


  <div class="container">
    <h1 class="heading"><?php echo $name; ?></h1>
    <div class="event-details">
      <div class="event-image"><img src="../../Admin/uploads/event/<?php echo $image; ?>" alt=""></div>
      <div class="event-info">
        <p class="price">Rs.<?php echo $price; ?></p>
        <p><?php echo $description; ?></p>
        <p class="date">Date: <?php echo $date; ?></p>
        <p class="time">Time: <?php echo $time; ?></p>
        <p class="tickets">Tickets Available: <?php echo $tickets; ?></p>
      </div>
      <div class="purchase-ticket">
        <h2>Purchase Tickets</h2>
        
        <form action="EventPayment.php" method="GET" enctype="multipart/form-data">
          <div class="form-group">
            <label for="userName">Username</label>
            <input type="text" id="userName" name="userName" value="<?php echo $_SESSION["user_name"]; ?>" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $_SESSION["user_email"]; ?>" required>
          </div>
          <div class="form-group">
            <label for="tickets">Tickets</label>
            <input type="number" id="tickets" name="tickets" value="1" min="1" required oninput="calculateTotal(<?php echo $price; ?>)">
          </div>
          <p class="price" id="total">Total: Rs.<?php echo $price; ?></p>
          <input type="hidden" id="hiddenTotal" name="total" value="<?php echo $price; ?>">
          <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
          <input type="hidden" name="availableTickets" value="<?php echo $tickets; ?>">
          <button type="submit" name="btn-buy">Buy Your Tickets</button>
        </form>
      </div>
    </div>
  </div>


  <script>
    function calculateTotal(price) {
      const ticketInput = document.getElementById('tickets');
      const totalPriceElement = document.getElementById('total');
      const hiddenTotalElement = document.getElementById('hiddenTotal');
      const total = price * ticketInput.value;
      totalPriceElement.textContent = 'Total: Rs.' + total.toFixed(2);
      hiddenTotalElement.value = total.toFixed(2);
    }
  </script>
  
</body>
</html>
