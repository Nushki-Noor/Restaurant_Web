<?php
include '../../Connection.php';
session_start();

if (!isset($_SESSION["user_name"])) {
    header("location: ../../Login/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Event Reservations</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../../Customer/css/Orders.css">
</head>

<body>

<?php include '../../Customer/php/Navbar.php'; ?>

<div class="container">
    <h1 class="heading">- Your Event Reservation Details -</h1>
    <div class="orders">

        <?php

        $user_email = $_SESSION["user_email"];

        $sql = "SELECT * FROM eventbooking WHERE useremail = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $user_email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $event_id = $row["eventid"];
                $ticket_quantity = $row["ticketquantity"];
                $total_price = $row["total"];

                $sql2 = "SELECT name FROM event WHERE id = ?";
                $stmt2 = mysqli_prepare($conn, $sql2);
                mysqli_stmt_bind_param($stmt2, "i", $event_id);
                mysqli_stmt_execute($stmt2);
                $result2 = mysqli_stmt_get_result($stmt2);

                if (mysqli_num_rows($result2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        $event_name = $row2["name"];
                    }
                } else {
                    $event_name = "Unknown Event";
                }
                mysqli_stmt_close($stmt2);

                echo '<div class="card">';
                echo '<div class="details">';
                echo '<h2> <p class="o-details">Event Name : ' . $event_name . '</p></h2>';
                echo '<h2> <p class="o-details">Event ID : ' . $event_id . '</p></h2>';
                echo '<h2> <p class="o-details">User Email : ' . $user_email . '</p></h2>';
                echo '<h2> <p class="o-details">Ticket Quantity : ' . $ticket_quantity . '</p></h2>';
                echo '<h2> <p class="o-details">Total Price : ' . $total_price . '</p></h2>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No Reservations</p>';
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        ?>
    </div>
</div>

</body>
</html>
