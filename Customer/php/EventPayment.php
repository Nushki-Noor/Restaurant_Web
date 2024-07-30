<?php
include "../../Connection.php";

session_start();

// if (!isset($_SESSION["user_name"])) {
//   header("location: Login/login.php");
//   exit;
// }

if (!isset($_GET["total"])) {
  header("location: Events.php");
  exit;
}

$total = $_GET['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Checkout</title>
    <link rel="stylesheet" href="../css/Payment.css">
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>
        <div class="total">Total : <span>Rs. <?php echo $total; ?></span></div>

        <form action="" method="POST">
            <div class="form-header">
                <h3>Debit/ Credit Card</h3>
            </div>

            <div class="form-group">
                <label for="card-number">Card number</label>
                <input type="text" id="card-number" name="card_number" placeholder="0000 1234 1234 0000" required>
            </div>

            <div class="form-group small">
                <label for="expiry-date">Expiration date</label>
                <input type="text" id="expiry-date" name="expiry_date" placeholder="MM/YY" required>
            </div>

            <div class="form-group small">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="123" required>
            </div>

            <div class="actions">
                <a href="Events.php" class="back-link">Go back to Events</a>
                <button type="submit" class="pay-button" name="btn-pay">Pay</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST["btn-pay"])) {
    $eventId = $_GET['eventId'];
    $userName = $_GET['userName'];
    $userEmail = $_GET['email'];
    $ticketQuantity = $_GET['tickets'];
    $total = $_GET['total'];
    $availableTickets = $_GET['availableTickets'];

    if ($ticketQuantity > $availableTickets) {
        echo '<script>alert("Not enough tickets available")</script>';
    } else {
        $newTicketCount = $availableTickets - $ticketQuantity;

        $sql = "INSERT INTO eventbooking (eventid, useremail, ticketquantity, total) VALUES ('$eventId', '$userEmail', '$ticketQuantity', '$total')";
        $query = mysqli_query($conn, $sql);

        if ($query) {
            $sql2 = "UPDATE event SET tickets='$newTicketCount' WHERE id='$eventId'";
            $query2 = mysqli_query($conn, $sql2);

            if ($query2) {
                echo '<script>alert("Event Booked Successfuly");
                window.location.href = "ViewEventBookings.php";
                </script>';
            } else {
                echo '<script>alert("Failed to update available tickets")</script>';
            }
        } else {
            echo '<script>alert("Failed to book event")</script>';
        }
    }
}
?>
