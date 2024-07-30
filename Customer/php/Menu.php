<?php

include '../../Connection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Menu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
             integrity="sha512-bw1zTp8S7ZBxQGHQzCtdTFy1B0X0+iaa7CVMUP8krYw9Uy0jC4zrPb1G5wzRrZQO6f3HmBZ2UpxzaEztr7pziA==" 
             crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../../Customer/css/Menu.css">
    <script defer src="../../Customer/js/Menu.js"></script>
</head>
<body>

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

    <form action="" method="get" class="search-form" id="search-form">
        <input type="text" name="query" placeholder="Search for food..." class="search-input" id="search-input" autocomplete="off" value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
        <div class="search-suggestions" id="search-suggestions"></div>
        <select name="category" class="search-category" id="search-category">
            <option value="">All Categories</option>
            <option value="Burgers" <?php echo isset($_GET['category']) && $_GET['category'] == 'Burgers' ? 'selected' : ''; ?>>Burgers</option>
            <option value="Pizza" <?php echo isset($_GET['category']) && $_GET['category'] == 'Pizza' ? 'selected' : ''; ?>>Pizza</option>
            <option value="Rice" <?php echo isset($_GET['category']) && $_GET['category'] == 'Rice' ? 'selected' : ''; ?>>Rice</option>
            <option value="Pasta" <?php echo isset($_GET['category']) && $_GET['category'] == 'Pasta' ? 'selected' : ''; ?>>Pasta</option>
            <option value="Sandwich" <?php echo isset($_GET['category']) && $_GET['category'] == 'Sandwich' ? 'selected' : ''; ?>>Sandwich</option>
            <option value="Kottu" <?php echo isset($_GET['category']) && $_GET['category'] == 'Kottu' ? 'selected' : ''; ?>>Kottu</option>
            <option value="Drinks" <?php echo isset($_GET['category']) && $_GET['category'] == 'Drinks' ? 'selected' : ''; ?>>Drinks</option>
        </select>
        <button type="submit" class="search-button">Search</button>
    </form>
    
    <button id="search-toggle" class="search-toggle">
        <img src="../../Pictures/search.png" alt="Search Icon">
    </button>

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


<div class="menu-container">
    <div class="menu-header">
        <h1>- Food Menu -</h1>

        <div class="categories">
            <button class="active" onclick="filterItems('All')">All</button>
            <button onclick="filterItems('Burgers')">Burgers</button>
            <button onclick="filterItems('Pizza')">Pizza</button>
            <button onclick="filterItems('Rice')">Rice</button>
            <button onclick="filterItems('Pasta')">Pasta</button>
            <button onclick="filterItems('Sandwich')">Sandwich</button>
            <button onclick="filterItems('Kottu')">Kottu</button>
            <button onclick="filterItems('Drinks')">Drinks</button>
        </div>
    </div>
    <div id="food-items" class="food-items">

    
        <?php
       $query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';
       $category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

       $sql = "SELECT * FROM foods WHERE 1=1";
       
       if ($query) {
           $sql .= " AND foodname LIKE '%$query%'";
       }

       if ($category) {
           $sql .= " AND foodcategory = '$category'";
       }

       $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result))  {
                $foodname = $row['foodname'];
                $pricenormal = $row['pricenormal'];
                $pricemedium = $row['pricemedium'];
                $pricelarge = $row['pricelarge'];
                $fooddescription = $row['fooddescription'];
                $foodcategory = $row['foodcategory'];
                $foodimage = '../../Admin/uploads/foods/' . $row["foodimage"];
                $foodDetailsUrl = "../php/FoodDetails.php?foodname=" . urlencode($foodname);
        
                echo '<div class="food-item" data-category="' . $foodcategory . '">
                        <img src="' . $foodimage . '" alt="' . $foodname . '">
                        <div class="food-item-content">
                            <div>
                                <h2><a href="' . $foodDetailsUrl . '" class="food-item-link">' . $foodname . '</a></h2>
                            </div>

                            <hr>

                            <p>Select Size</p>
                            <form action="../../Customer/php/AddToCart.php" method="post">
                            <div class="price-dropdown">
                                <select name="price" class="price-select">';
                                if ($pricenormal > 0) {
                                    echo '<option value="' . $pricenormal . '">Normal - Rs. ' . $pricenormal . '</option>';
                                }
                                if ($pricemedium > 0) {
                                    echo '<option value="' . $pricemedium . '">Medium - Rs. ' . $pricemedium . '</option>';
                                }
                                if ($pricelarge > 0) {
                                    echo '<option value="' . $pricelarge . '">Large - Rs. ' . $pricelarge . '</option>';
                                }
                                echo '</select>
                            </div>
                           
                            <input type="hidden" name="foodname" value="' . $foodname . '">
                                
                            <div class="quantity-container">
                                <button type="button" class="decrement" onclick="this.nextElementSibling.stepDown()">-</button>
                                <input type="number" name="quantity" class="qty" min="1" max="50" value="1" maxlength="2">
                                <button type="button" class="increment" onclick="this.previousElementSibling.stepUp()">+</button>
                            </div>
                            <div class="button-container">
                                <button type="submit" class="btnmore">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </form>
                    </div>
                </div>';
            }
        } else {
            echo '<div class="nomv">No Food Items Available</div>';
        }

        $conn->close();
        ?>
    </div>
</div>

<?php include '../../Customer/php/Footer.php'; ?>


</body>
</html>
