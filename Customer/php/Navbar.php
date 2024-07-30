<html>
<link rel="stylesheet" href="../../Customer/css/Navbar.css">
<script defer src="../../Customer/js/Navbar.js"></script>
</html>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar">
    <div class="navbar-left">
        <img src="../../Pictures/Ceylon Spice Logo1.png" class="logo">
    </div>

    <div class="navbar-center">
        <a href="../../Customer/php/Home.php" class="btn2 <?php echo basename($_SERVER['PHP_SELF']) == 'Home.php' ? 'active' : ''; ?>">Home</a>
        <a href="../../Customer/php/Menu.php" class="btn2 <?php echo basename($_SERVER['PHP_SELF']) == 'Menu.php' ? 'active' : ''; ?>">Menu</a>
        <a href="../../Customer/php/Reservation.php" class="btn2 <?php echo basename($_SERVER['PHP_SELF']) == 'Reservation.php' ? 'active' : ''; ?>">Book a Table</a>
        <a href="../../Customer/php/Events.php" class="btn2 <?php echo basename($_SERVER['PHP_SELF']) == 'Events.php' ? 'active' : ''; ?>">Events</a>
        <a href="../../Customer/php/AboutUs.php" class="btn2 <?php echo basename($_SERVER['PHP_SELF']) == 'AboutUs.php' ? 'active' : ''; ?>">About Us</a>
    </div>

    <div class="navbar-right">
        <a href="../../Customer/php/Cart.php">
    <img src="../../Pictures/cart-icon.png" class="cart-icon" id="cart-icon">
    </a>
    
        <div class="user-icon-container">
            <img src="../../Pictures/user-icon.png" class="user-icon" id="user-icon">

            <div class="user-popup" id="user-popup">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="welcome-message">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                    <hr>
                    <a href="../../Customer/php/Orders.php" class="btn3">
                        <img src="../../Pictures/urOrders.png" class="user-icon1"> Your Orders</a>

                    <a href="../../Customer/php/ViewReserveDetails.php" class="btn3">
                        <img src="../../Pictures/reserved.png" class="user-icon1"> Your Reservations</a>

                    <a href="../../Customer/php/ViewEventBookings.php" class="btn3">
                        <img src="../../Pictures/event.png" class="user-icon1"> Your Event Bookings</a>

                    <a href="../../Customer/php/ManageProfile.php" class="btn3">
                        <img src="../../Pictures/editProfile.png" class="user-icon1"> Edit Profile</a>
                        
                    <hr>
                    <a href="../../logout.php" class="btn3">
                        <img src="../../Pictures/logout.png" class="user-icon1"> Logout</a>


                <?php else: ?>
                    <p class="message">Sign up or Login</p>
                    <hr>
                    <a href="../../Register/register.php" class="btn3">Sign Up</a>
                    <a href="../../Login/login.php" class="btn3">Login</a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</nav>

