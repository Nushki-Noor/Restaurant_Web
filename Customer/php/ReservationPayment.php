<?php
include "../../Connection.php";

session_start();

$totalPrice =$_GET['totalPrice'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Reservation Payment</title>
    <link rel="stylesheet" href="../css/Payment.css">
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>
        <div class="total">Total: <span>Rs. <?php echo $totalPrice; ?></span></div>

        <form action="" method="POST">
            <div class="form-header">
                <h3>Debit/Credit Card</h3>
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
                <a href="Reservation.php" class="back-link">Go back to Reservations</a>
                <button type="submit" class="pay-button" name="btn-pay">Pay</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
if (isset($_POST["btn-pay"])) {
    $name = $_GET['name'];
    $email = $_GET['email'];
    $contact = $_GET['contact'];
    $date = $_GET['date'];
    $time = $_GET['time'];
    $tables = $_GET['tables'];
    $tableCategory = $_GET['tableCategory'];
    $totalPrice =$_GET['totalPrice'];

    
    if (!empty($tables)) {
        $ids = implode(",", $tables);

        $sql = "INSERT INTO reservations (name, email, contact , date, time , table_category, total_price) 
                VALUES ('$name', '$email', '$contact' ,'$date','$time','$tableCategory', '$totalPrice')";

        if ($conn->query($sql) === TRUE) {
            $reservationId = mysqli_insert_id($conn);
            
            $stmt = $conn->prepare("INSERT INTO reserve_tables (reservation_id, tables) VALUES (?, ?)");

            foreach ($tables as $table) {
                $stmt->bind_param("is", $reservationId, $table);
                $stmt->execute();
            }

            if ($stmt->affected_rows > 0) {
                echo '<script>alert("Your Reservation has been placed");
                window.location.href = "ViewReserveDetails.php";
                </script>';
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo '<script>alert("Please select at least one table.");</script>';
    }
}
?>
