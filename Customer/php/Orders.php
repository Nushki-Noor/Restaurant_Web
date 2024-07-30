<?php
include '../../Connection.php';
session_start();

if (!isset($_SESSION["user_name"])) {
    header("location: ../../Login/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orderId'])) {
    $orderId = $_POST['orderId'];
    $status = "Recieved";

    $updateSql = "UPDATE online_order SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $updateSql);
    mysqli_stmt_bind_param($stmt, "si", $status, $orderId);
    mysqli_stmt_execute($stmt);

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ceylon Spice - Your Orders</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../../Customer/css/Orders.css">
</head>

<body>

<?php include '../../Customer/php/Navbar.php'; ?>

<div class="container">
    <h1 class="heading">- Your Orders -</h1>
    <div class="orders">

        <?php
        $email = $_SESSION["user_email"];

        $sql = "
            SELECT * FROM online_order 
            WHERE email = ? 
            ORDER BY 
                CASE 
                    WHEN status = 'Recieved' THEN 1 
                    ELSE 0 
                END, 
                id DESC
        ";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $orderId = $row["id"];
                $cName = $row["user_name"];
                $cAddress = $row["address"];
                $contact = $row["contact_number"];
                $totalQuan = $row["total_quantity"];
                $totalAmount = $row["total_amount"];
                $status = $row["status"];

                echo '<div class="card">';
                echo '<div class="details">';
                echo '<h2><p class="o-details">Name : ' . $cName . '</p></h2>';
                echo '<h2><p class="o-details">Address : ' . $cAddress . '</p></h2>';
                echo '<h2><p class="o-details">Contact : ' . $contact . '</p></h2>';

                $sql = "SELECT * FROM order_item WHERE user_email = ? AND order_id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "si", $email, $orderId);
                mysqli_stmt_execute($stmt);
                $itemResult = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($itemResult) > 0) {
                    echo '<ul class="order-items">';
                    while ($itemRow = mysqli_fetch_assoc($itemResult)) {
                        $itemName = $itemRow["food_name"];
                        $itemPrice = $itemRow["price"];
                        $itemQuantity = $itemRow["quantity"];

                        echo '<li class="o-details">';
                        echo '<p><span>' . $itemName . '</span> - <span>Rs. ' . $itemPrice . '</span> x <span> ' . $itemQuantity . '</span> </p>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>No items found for this order</p>';
                }

                echo '<h2><p class="o-details">Total Food Quantity : ' . $totalQuan . '</p></h2>';
                echo '<h2><p class="o-details">Total Amount : ' . $totalAmount . '</p></h2>';

                $statusClass = $status === "Recieved" ? 'recieved-status' : '';

                echo '<h2><p class="o-details ' . $statusClass . '">Order Status : ' . $status . '</p></h2>';

                if ($status === "Order Dispatched") {
                    echo '<button id="recievedBtn" onclick="markAsRecieved(' . $orderId . ')" class="dispatch-button">Recieved</button>';
                    echo '<button id="rateUsBtn" style="display:none;" onclick="rateUs(' . $orderId . ')" class="dispatch-button">Rate Us</button>';
                }

                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No Orders</p>';
        }
        ?>

        <script>
            function markAsRecieved(orderId) {
                var receivedButton = document.getElementById('recievedBtn');
                receivedButton.style.display = 'none';

                var rateUsButton = document.getElementById('rateUsBtn');
                rateUsButton.style.display = 'inline-block';

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'Orders.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                    }
                };
                xhr.send('orderId=' + orderId);
            }

            function rateUs(orderId) {
                window.location.href = '../../Customer/php/Menu.php';
            }
        </script>

    </div>
</div>

</body>
</html>
