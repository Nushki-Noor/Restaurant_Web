<?php

include '../../Connection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../../Customer/css/Home.css">
    <script src="../../Customer/js/Home.js"></script>
</head>
<body>

<?php include '../../Customer/php/Navbar.php'; ?>

<div class="content-container">
    <img src="../../Pictures/19.png" alt="" class="image1">
    <a href="Reservation.php" class="btn1"><span>Book a Table</span></a>
    <a class="wlcmtxt"><span>Welcome to Ceylon Spice Restaurant!</span></a>
    <a class="wlcmtxt2"><span>Enjoy delicious meals and exceptional service in a warm..</span></a>
</div>

<!--<hr>-->

<div class="menu-container">
    <div class="menu-header" data-aos="fade-up">
        <h1>Top Rated Foods</h1>
    </div>
    <div id="food-items" class="food-items" data-aos="fade-up">
        <?php
        $sql = "SELECT foodname, SUM(rating) AS TotalRating FROM feedback GROUP BY foodname ORDER BY TotalRating DESC LIMIT 3;";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $name = $row['foodname'];

                $sql = "SELECT * FROM foods WHERE foodname = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $name);
                mysqli_stmt_execute($stmt);
                $food_result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($food_result) > 0) {
                    while ($food_row = mysqli_fetch_assoc($food_result)) {
                        $foodname = $food_row['foodname'];
                        $pricenormal = $food_row['pricenormal'];
                        $pricemedium = $food_row['pricemedium'];
                        $pricelarge = $food_row['pricelarge'];
                        $fooddescription = $food_row['fooddescription'];
                        $foodcategory = $food_row['foodcategory'];
                        $foodimage = '../../Admin/uploads/foods/' . $food_row["foodimage"];
                        $foodDetailsUrl = "../php/FoodDetails.php?foodname=" . urlencode($foodname);

                        echo '<div class="food-item" data-category="' . $foodcategory . '">
                                <img src="' . $foodimage . '" alt="' . $foodname . '">
                                <div class="food-item-content">
                                    <div>
                                        <h2><a href="' . $foodDetailsUrl . '" class="food-item-link">' . $foodname . '</a></h2>
                                    </div>
                                </div>
                            </div>';
                    }
                }
            }
        } else {
            echo '<div class="nomv">No Food Items Available</div>';
        }

        $conn->close();
        ?>
    </div>
    
    <div class="viewmore-container">
    <a href="../../Customer/php/Menu.php"> <button class="viewmore" data-aos="fade-up">View More</button> </a>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
      AOS.init();
    </script>


<div class="container0">
    <div class="content0" data-aos="fade-up">
        <img src="../../Pictures/fries.png" alt="Fries" class="fries-image">
        <div class="text">
            <p class="hungry" data-aos="fade-up">Hungry?</p>
            <h1 class="deliver" data-aos="fade-up">We will home deliver!</h1>
            <a href="../../Customer/php/Menu.php"> <button class="order-button" data-aos="fade-up">Make an Order</button> </a>
        </div>
    </div>
</div>


<div class="container" >
        <h1 data-aos="fade-up">Some photos from Our Restaurant</h1>
        <div class="photo-grid">
            <img src="../../Pictures/TR1.jpg" alt="Restaurant Photo 1" data-aos="fade-up">
            <img src="../../Pictures/TR2.jpg" alt="Restaurant Photo 2" data-aos="fade-up">
            <img src="../../Pictures/TR3.jpg" alt="Restaurant Photo 3" data-aos="fade-up">
            <img src="../../Pictures/TR4.jpg" alt="Restaurant Photo 4" data-aos="fade-up">
        </div>
    </div>




<?php include '../../Customer/php/Footer.php'; ?>

</body>
</html>
