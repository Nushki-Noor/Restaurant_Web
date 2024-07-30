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
    <title>Ceylon Spice - Your Reservations</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../../Customer/css/Orders.css">
    <script>
        function handleCancel(reservationId) {
            const form = document.getElementById(`cancel-form-${reservationId}`);
            const button = document.getElementById(`cancel-button-${reservationId}`);
            const message = document.getElementById(`cancel-message-${reservationId}`);

            // Submit the form
            form.submit();
            // Hide the button and show the message
            button.style.display = 'none';
            message.style.display = 'block';
        }
    </script>
</head>

<body>

<?php include '../../Customer/php/Navbar.php'; ?>

<div class="container">
    <h1 class="heading">- Your Reservation Details -</h1>
    <div class="orders">
        <?php
        $email = $_SESSION["user_email"];
        $sql = "SELECT reservations.*, GROUP_CONCAT(reserve_tables.tables) AS tables 
                FROM reservations
                LEFT JOIN reserve_tables ON reserve_tables.reservation_id = reservations.id 
                WHERE reservations.email = ? 
                GROUP BY reservations.id";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $reservationId = $row["id"];
                $name = $row["name"];
                $email = $row["email"];
                $contact = $row["contact"];
                $date = $row["date"];
                $time = $row["time"];
                $category = $row["table_category"];
                $tables = !empty($row["tables"]) ? explode(',', $row["tables"]) : [];
                $totalPrice = $row["total_price"];

                echo '<div class="card">';
                echo '<div class="details">';
                echo '<h2><p class="o-details">Name: ' . $name . '</p></h2>';
                echo '<h2><p class="o-details">Email: ' . $email . '</p></h2>';
                echo '<h2><p class="o-details">Contact: ' . $contact . '</p></h2>';
                echo '<h2><p class="o-details">Date: ' . $date . '</p></h2>';
                echo '<h2><p class="o-details">Time: ' . $time . '</p></h2>';
                echo '<h2><p class="o-details">Table Category: ' . $category . '</p></h2>';
                echo '<h2><p class="o-details">Table Number: ' . (!empty($tables) ? implode(', ', $tables) : 'No tables reserved') . '</p></h2>';
                echo '<h2><p class="o-details">Total Price: ' . $totalPrice . '</p></h2>';
                
                if ($row['status'] == 'Request Cancel') {
                    echo '<p id="cancel-message-' . $reservationId . '" style="color: red;">Request Pending</p>';
                } else if ($row['status'] == 'Cancel Request Rejected') {
                    echo '<p id="cancel-message-' . $reservationId . '" style="color: red;">Your request has been rejected.</p>';
                } else if ($row['status'] == 'Cancel Request Confirmed') {
                    echo '<p id="cancel-message-' . $reservationId . '" style="color: red;">Your request has been confirmed. Reservation Cancelled</p>';
                } else {
                    echo '<form id="cancel-form-' . $reservationId . '" method="post" action="cancelReservation.php">';
                    echo '<input type="hidden" name="reservation_id" value="' . $reservationId . '">';
                    echo '<input type="hidden" name="reservation_status" value="Request Cancel">';
                    echo '<button type="button" class="cancelbtn" id="cancel-button-' . $reservationId . '" onclick="handleCancel(' . $reservationId . ')">Cancel Reservation</button>';
                    echo '</form>';
                    echo '<p id="cancel-message-' . $reservationId . '" style="display: none;">Request Pending</p>';
                }
                
                echo '<hr>';
                echo '<p>" Policy : Please note that cancellations are non-refundable. Thank you for your understanding. "</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No Reservations</p>';
        }
        ?>
    </div>
</div>

</body>
</html>

<script>
    function handleCancel(reservationId) {
        const form = document.getElementById(`cancel-form-${reservationId}`);
        const button = document.getElementById(`cancel-button-${reservationId}`);
        const message = document.getElementById(`cancel-message-${reservationId}`);

        button.style.display = 'none';
        message.style.display = 'block';

        form.submit();
    }
</script>

