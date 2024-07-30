<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - About Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../../Customer/css/AboutUs.css">
</head>
<body>

<?php include '../../Customer/php/Navbar.php'; ?>

<h2>- About Our Restaurant -</h2>
<div class="container">
        <div class="image-container">
            <img src="../../Pictures/AU1.jpg" alt="Restaurant Image">
        </div>
        <div class="about-us">
        <h1>About Our Restaurant</h1>
            <p class="subtitle">We offer the best dishes in a friendly and calm atmosphere.</p>
            <p>Ceylon Spice Restaurant offers a delightful culinary journey through the rich and diverse flavors of Sri Lanka.
                 Known for its authentic Ceylonese cuisine, the restaurant uses traditional recipes and fresh spices to create 
                 vibrant dishes that capture the essence of Sri Lanka. Guests can enjoy a variety of mouthwatering options,
                  from aromatic curries and tender meats to flavorful dishes and exotic desserts, all served in a 
                  warm and inviting atmosphere. Whether you're a spice enthusiast or new to Sri Lankan cuisine, 
                  Ceylon Spice promises a memorable dining experience that celebrates the island's culinary heritage.</p>
        </div>
</div>
<hr>
<div class="why-choose-us">
        <h3>Why Choose Us</h3>
        <div class="features">
            <div class="feature">
                <img src="../../Pictures/Abt1.png" alt="Friendly Team">
                <h3>Friendly Team</h3>
            </div>

            <div class="feature">
                <img src="../../Pictures/Abt2.png" alt="Fresh Food">
                <h3>Fresh Food</h3>
            </div>

            <div class="feature">
                <img src="../../Pictures/Abt3.png" alt="Quality Cuisine">
                <h3>Quality Cuisine</h3>
            </div>

            <div class="feature">
                <img src="../../Pictures/Abt4.png" alt="Best Service">
                <h3>Best Service</h3>
            </div>

            <div class="feature">
                <img src="../../Pictures/Abt5.png" alt="Diverse Menu">
                <h3>Diverse Menu</h3>
            </div>

            <div class="feature">
                <img src="../../Pictures/Abt6.png" alt="Affordable Prices">
                <h3>Affordable Prices</h3>
            </div>

        </div>
    </div>

    <?php include '../../Customer/php/Footer.php'; ?>
    
</body>
</html>