<?php
include '../../Connection.php';
session_start();

if (!isset($_SESSION['user_email'])) {
    header("location: ../../Login/login.php");
    exit();
}

$uId = $_SESSION["user_id"];
$uEmail = $_SESSION["user_email"];
$uName = $_SESSION["user_name"];
$uContact = $_SESSION["user_contact"];
$uAddress = $_SESSION["user_address"];

$sql = "SELECT * FROM cart WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $uId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$cart_items = [];

$total = 0;
$total_quantity = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
    $total_quantity += $row['quantity'];
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Checkout</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../css/Checkout.css">
</head>
<body>

<?php include '../../Customer/php/Navbar.php'; ?>

<div class="checkout-container">
    <h1>Checkout</h1>
    
    <div class="order-summary">
        <h2>Order Summary</h2>
        <ul>
            <?php foreach ($cart_items as $item): ?>
                <li>
                    <span><?php echo $item['food_name']; ?> (<?php echo number_format($item['price']) . ' x ' . $item['quantity']; ?>)</span>
                    <span>Rs. <?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="order-total">
            <strong>Total Amount: Rs. <?php echo number_format($total, 2); ?></strong>
        </div>
    </div>

    <div class="customer-info">
        <h2>Customer Information</h2>
        <form action="Checkout.php" method="post">
            <div class="form-group">
                <label for="customer_name">Name:</label>
                <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($uName); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Delivery Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($uAddress); ?>" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact" value="<?php echo htmlspecialchars($uContact); ?>" required>
            </div>

            <div class="pay-method">
                <select id="pay-meth-select" name="payment_method" class="pay-meth-select" required>
                    <option value="" disabled selected>Choose payment method</option>
                    <option value="cash">Cash on Delivery</option>
                    <option value="card">Debit/Credit Card</option>
                </select>
            </div>

            <input type="hidden" name="total_quantity" value="<?php echo $total_quantity; ?>">
            <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
         
            <button type="submit" class="viewcart1" name="btn-place-order">Place Order</button>
        </form>
    </div>

    <a href="Cart.php" class="viewcart">View Cart</a> 
</div>

<?php
if (isset($_POST["btn-place-order"])) {
    $payment_method = $_POST['payment_method'];
    $cName = $_POST["customer_name"];
    $cAddress = $_POST["address"];
    $contact = $_POST["contact"];
    $totalQuan = $_POST["total_quantity"];
    $totalAmount = $_POST["total_amount"];
    $status = "pending";

    $_SESSION['cName'] = $cName;
    $_SESSION['cAddress'] = $cAddress;
    $_SESSION['contact'] = $contact;
    $_SESSION['totalQuan'] = $totalQuan;
    $_SESSION['totalAmount'] = $totalAmount;

    if ($payment_method == "cash") {
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        if (insertOrder($conn, $cName, $uEmail, $cAddress, $contact, $totalQuan, $totalAmount, $status)) {
            $orderId = mysqli_insert_id($conn);

            insertOrderItem($conn, $orderId, $cart_items, $uEmail);
            deleteCart($conn, $uId);

            echo '<script>alert("Your order has been placed!");</script>';
        } else {
            echo '<script>alert("Failed to place order. Please try again later.");</script>';
        }
    } else {
        echo '
        <div id="cardPaymentPopup" class="popup">
            <h2>Payment Information</h2>
            <form id="cardPaymentForm" action="" method="post">
                <div class="container">
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
                    <button type="submit" class="pay-button" name="btn-pay">Pay</button>
                </div>
            </form>
        </div>

        <script>
        function showCardPaymentPopup() {
            var popup = document.getElementById("cardPaymentPopup");
            popup.style.display = "block";
        }

        document.addEventListener("click", function(event) {
            var popup = document.getElementById("cardPaymentPopup");
            var form = document.getElementById("cardPaymentForm");
            
            if (!form.contains(event.target) && popup.style.display === "block") {
                popup.style.display = "none";
            }
        });

        window.onload = function() {
            showCardPaymentPopup();
        };
        </script>';
    }
}

if (isset($_POST["btn-pay"])) {
    $name = $_SESSION['cName'];
    $address = $_SESSION['cAddress'];
    $number = $_SESSION['contact'];
    $totQuan = $_SESSION['totalQuan'];
    $totAmou = $_SESSION['totalAmount'];
    $status = "pending";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (insertOrder($conn, $name, $uEmail, $address, $number, $totQuan, $totAmou, $status)) {
        $orderId = mysqli_insert_id($conn);

        insertOrderItem($conn, $orderId, $cart_items, $uEmail);
        deleteCart($conn, $uId);

//         echo '<script>
//         alert("Your order has been placed!");
//         window.location.href = "Menu.php";
//     </script>';
    } else {
        echo '<script>alert("Failed to place order. Please try again later.");</script>';
    }
}

function insertOrder($conn, $cName, $uEmail, $cAddress, $contact, $totalQuan, $totalAmount, $status) {
    $orderDate = date('Y-m-d');

    $sql = "INSERT INTO online_order (user_name, email, address, contact_number, total_quantity, total_amount, status, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: Checkout.php?err=FailedStmt");
        return false;
    } else {
        mysqli_stmt_bind_param($stmt, "sssiiiss", $cName, $uEmail, $cAddress, $contact, $totalQuan, $totalAmount, $status, $orderDate);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return true;
    }
}

function insertOrderItem($conn, $orderId, $items, $userEmail) {
    $sql = "INSERT INTO order_item (order_id, food_name, price, quantity, user_email) VALUES (?, ?, ?, ?, ?)";
    $updateStockSql = "UPDATE foods SET foodqty = foodqty - ? WHERE foodname = ?";
    $stmt = mysqli_stmt_init($conn);
    $updateStmt = mysqli_stmt_init($conn);

    foreach ($items as $item) {
        $foodName = $item['food_name'];
        $price = $item['price'];
        $quantity = $item['quantity'];

        mysqli_stmt_prepare($stmt, $sql);
        mysqli_stmt_bind_param($stmt, "isdss", $orderId, $foodName, $price, $quantity, $userEmail);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_prepare($updateStmt, $updateStockSql);
        mysqli_stmt_bind_param($updateStmt, "is", $quantity, $foodName);
        mysqli_stmt_execute($updateStmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($updateStmt);
}

function deleteCart($conn, $uId) {
    $sql = "DELETE FROM cart WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $uId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo '<script>
        alert("Your order has been placed!");
        window.location.href = "Orders.php";
        </script>';
}
?>

</body>
</html>
