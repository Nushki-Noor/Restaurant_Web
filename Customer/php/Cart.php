<?php
include '../../Connection.php';

session_start();

if (!isset($_SESSION['user_email'])) {
    echo '<script>
        alert("You need to First Login to the system");
        window.location.href = "../../Login/login.php";
    </script>';
exit();
}

$userId = $_SESSION['user_id'];
        $sql = "SELECT * FROM cart WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_quantity'])) {
        $itemId = $_POST['item_id'];
        $newQuantity = $_POST['quantity'];

       
        $updateSql = "UPDATE cart SET quantity = ? WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $updateSql);
        mysqli_stmt_bind_param($updateStmt, "ii", $newQuantity, $itemId);
        mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt);

    } elseif (isset($_POST['delete_item'])) {
        $itemId = $_POST['item_id'];

        
        $deleteSql = "DELETE FROM cart WHERE id = ?";
        $deleteStmt = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($deleteStmt, "i", $itemId);
        mysqli_stmt_execute($deleteStmt);
        mysqli_stmt_close($deleteStmt);
        
    } elseif (isset($_POST['delete_all'])) {
        
        $deleteAllSql = "DELETE FROM cart WHERE user_id = ?";
        $deleteAllStmt = mysqli_prepare($conn, $deleteAllSql);
        mysqli_stmt_bind_param($deleteAllStmt, "i", $userId);
        mysqli_stmt_execute($deleteAllStmt);
        mysqli_stmt_close($deleteAllStmt);
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
             integrity="sha512-bw1zTp8S7ZBxQGHQzCtdTFy1B0X0+iaa7CVMUP8krYw9Uy0jC4zrPb1G5wzRrZQO6f3HmBZ2UpxzaEztr7pziA==" 
             crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../Customer/css/Cart.css">
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
 
</head>
<body>

<?php include '../../Customer/php/Navbar.php'; ?>

<div class="cart-container">
    <h1>Your Cart</h1>
    
    <div class="cart-items">
        <?php
        

        $totalAmount = 0;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $itemId = $row['id'];
                $foodName = $row['food_name'];
                $price = $row['price'];
                $quantity = $row['quantity'];
                $foodImage = $row['food_image'];

                $itemTotal = $price * $quantity;
                $totalAmount += $itemTotal;

                echo '<div class="cart-item">
                        <div class="item-image">
                            <img src="' . $foodImage . '" alt="' . $foodName . '">
                        </div>
                        <div class="item-details">
                            <h3>' . $foodName . '</h3>
                            <p>Price : Rs. ' . number_format($price, 2) . '</p>
                            <p>Quantity: 
                                <form class="update-form" method="post" action="' . $_SERVER['PHP_SELF'] . '">
                                    <input type="hidden" name="item_id" value="' . $itemId . '">
                                    <input type="number" name="quantity" value="' . $quantity . '" min="1">
                                    <button type="submit" name="update_quantity" class="update-button">
                                        <img src="../../Pictures/icon-update.png" alt="Update">
                                    </button>
                                </form>
                            </p>
                            <div class="total"><p> Total: Rs.'.$itemTotal.' </p></div>
                            
                            <form class="delete-form" method="post" action="' . $_SERVER['PHP_SELF'] . '">
                                <input type="hidden" name="item_id" value="' . $itemId . '">
                                <button type="submit" name="delete_item" class="delete-button">
                                    <img src="../../Pictures/icon-delete.png">
                                </button>
                                <hr>
                            </form>
                        </div>
                    </div>';
            }
        } else {
            echo '<p>Your cart is empty</p>';
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        ?>
    </div>

    <div class="cart-summary">
        <h3>Total Amount: Rs. <?php echo number_format($totalAmount, 2); ?></h3>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <button type="submit" name="delete_all" class="deleteall-btn">Delete All</button>
        </form>
    
    <div>
        <a href="Checkout.php?total=<?php $totalAmount ?>"> <button class="checkout-btn">Checkout</button></a>  
    </div>
    </div>

</div>



</body>
</html>



