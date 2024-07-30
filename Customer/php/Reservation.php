<?php
session_start();

include '../../Connection.php';

if (!isset($_SESSION['user_email'])) {
    echo '<script>
        alert("You need to First Login to the system");
        window.location.href = "../../Login/login.php";
    </script>';
exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <title>Ceylon Spice - Table Reservation</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../css/Reservation.css" type="text/css">
</head>

<body>

<?php include '../../Customer/php/Navbar.php'; ?>

    <h1>- Reserve your Table -</h1>

    <div class="container">
    <div class="left-section">
        <section>
            <form id="reservationForm" action="ReservationPayment.php" method="GET">
                <label for="name">Name :</label><br>
                <input type="text" id="name" name="name" value="<?php echo $_SESSION["user_name"]; ?>" required><br><br>

                <label for="email">Email :</label><br>
                <input type="email" id="email" name="email" value="<?php echo $_SESSION["user_email"]; ?>" required><br><br>

                <label for="contact">Contact Number :</label><br>
                <input type="number" id="contact" name="contact" required><br><br>

                <label for="date">Choose a Date :</label>
                <input type="date" id="date" name="date" required><br><br>

                <label for="time">Choose a Time :</label>
                <select id="time" name="time" required>
                    <option value="12:00 PM - 2:00 PM">12:00 PM - 2:00 PM</option>
                    <option value="2:30 PM - 4:30 PM">2:30 PM - 4:30 PM</option>
                    <option value="5:00 PM - 7:00 PM">5:00 PM - 7:00 PM</option>
                    <option value="7:30 PM - 9:30 PM">7:30 PM - 9:30 PM</option>
                    <option value="10:00 PM - 12:00 AM">10:00 PM - 12:00 AM</option>
                </select><br><br>

                <label for="tableCategory">Table Category :</label><br>
                <select id="tableCategory" name="tableCategory" required onchange="updateTotalPrice()">
                    <option value="2" data-price="2000.00">Table For 2 People</option>
                    <option value="4" data-price="3000.00">Table For 4 People</option>
                    <option value="6" data-price="5000.00">Table For 6 People</option>
                    <option value="8" data-price="7000.00">Table For 8 People</option>
                    <option value="10" data-price="9000.00">Table For 10 People</option>
                </select><br><br>

                <label>Total Price :</label>
                <input type="text" id="totalPrice" name="totalPrice" value="" readonly>

                <input type="hidden" name="tables" id="tables"> <!-- To handle selected tables -->
                <div class="tables" data-table-price="2000.00"></div>

                <br><br>

                <div class="abc">
                    <div class="ab">
                        <span style='color:red;font-size:40px;font-color:#fff; margin-left: 20px; background-color: red; border-radius: 5px;'>&#9744;</span>
                        <p>Reserved</p>
                    </div>
                    <br><br>
                    <div class="ab">
                        <span style='color:#eaebef; font-size:40px; margin-left: 20px; background-color: #eaebef; border-radius: 5px;'>&#9744;</span>
                        <p>Available</p>
                    </div>
                    <br><br>
                    <div class="ab">
                        <span style='color:#cedd00; font-size:40px; margin-left: 20px; background-color: #cedd00; border-radius: 5px;'>&#9744;</span>
                        <p>Selected</p>
                    </div>
                    <br><br>
                </div>
                

                <input type="submit" id="submitButton" value="Reserve & Pay">
            </form>
        </section>
    </div>

    

    <div class="right-section">
        <div class="image-grid">
            <div class="row">
                <div class="column">
                    <img src="../../Pictures/TR1.jpg" alt="Image 1">
                </div>
                <div class="column">
                    <img src="../../Pictures/TR2.jpg" alt="Image 2">
                </div>
            </div>
            <div class="row">
                <div class="column">
                    <img src="../../Pictures/TR3.jpg" alt="Image 3">
                </div>
                <div class="column">
                    <img src="../../Pictures/TR4.jpg" alt="Image 4">
                </div>
            </div>
        </div>
    </div>
</div>


<script defer src="../js/Reservation.js"></script>
<?php include '../../Customer/php/Footer.php'; ?>

</body>
</html>