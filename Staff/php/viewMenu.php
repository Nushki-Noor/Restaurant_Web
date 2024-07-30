<?php
require_once("../../Connection.php");
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Menu</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../../Staff/css/viewMenu.css">
</head>

<body>

<div class="navbar">
    <img src="../../Pictures/Ceylon Spice Logo1.png" class="logo">
        <ul class="navbar-nav">
        <li class="nav-item">
                    <a class="dropdown-item" href="Manager.php">Dashboard</a>
                </li>
            <li class="nav-item">
                <a class="dropdown-item" href="viewMenu.php">View Menu</a>
            </li>
            <li class="nav-item">
                <a class="dropdown-item" href="ViewReservations.php">View Reservations</a>
            </li>
            <li class="nav-item">
                <a class="dropdown-item" href="ViewEventBookings.php">View Event Bookings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../logout.php">Logout</a>
            </li>
        </ul>
</div>


<div class="menu-container">
    <div class="menu-header">
        <h1>Food Menu</h1>
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

        $sql = "SELECT * FROM foods";
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
                $foodDetailsUrl = "../../Staff/php/foodDetails.php?foodname=" . urlencode($foodname);
        
                echo '<div class="food-item" data-category="' . $foodcategory . '">
                        <img src="' . $foodimage . '" alt="' . $foodname . '">
                        <div class="food-item-content">
                            <div>
                                <h2><a href="' . $foodDetailsUrl . '" class="food-item-link">' . $foodname . '</a></h2>
                            </div>
                            
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
    filterItems('All');

    const buttons = document.querySelectorAll('.categories button');
    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            const category = event.target.textContent;
            filterItems(category);
        });
    });
});

function filterItems(category) {
    const items = document.querySelectorAll('.food-item');
    items.forEach(item => {
        if (category === 'All') {
            item.style.display = 'flex';
        } else {
            item.style.display = item.getAttribute('data-category') === category ? 'flex' : 'none';
        }
    });

    const buttons = document.querySelectorAll('.categories button');
    buttons.forEach(button => {
        button.classList.remove('active');
    });
    const activeButton = Array.from(buttons).find(button => button.textContent === category);
    if (activeButton) {
        activeButton.classList.add('active');
    }
}
</script>

</body>
</html>