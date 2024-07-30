<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Customer Orders</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../../Staff/css/KTManager.css">
</head>
<body>
    <div class="navbar">
        <img src="../../Pictures/Ceylon Spice Logo1.png" class="logo">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="dropdown-item" href="KTManager.php">View Orders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <div class="container">
        <div class="category-buttons">
            <button class="btn2 active" onclick="filterOrders('All')">Show All</button>
            <button class="btn2" onclick="filterOrders('Pending')">Pending Orders</button>
            <button class="btn2" onclick="filterOrders('Order Confirmed')">Confirmed Orders</button>
            <button class="btn2" onclick="filterOrders('Order Prepared')">Prepared Orders</button>
            <button class="btn2" onclick="filterOrders('Order Dispatched')">Dispatched Orders</button>
            <button class="btn2" onclick="filterOrders('Recieved')">Received Orders</button>
        </div>

        <hr>

        <div class="orders">
            <?php
            include '../../Connection.php';
            session_start();

            function getOrdersByStatus($conn, $status) {
                $sql = "SELECT * FROM online_order WHERE status = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $status);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                return $result;
            }

            function getOrderItems($conn, $orderId) {
                $sql_items = "SELECT * FROM order_item WHERE order_id = ?";
                $stmt = mysqli_prepare($conn, $sql_items);
                mysqli_stmt_bind_param($stmt, "i", $orderId);
                mysqli_stmt_execute($stmt);
                $itemResult = mysqli_stmt_get_result($stmt);
                return $itemResult;
            }

            function displayOrderDetails($row, $conn) {
                $orderId = $row['id'];
                $cName = $row["user_name"];
                $cAddress = $row["address"];
                $contact = $row["contact_number"];
                $totalQuan = $row["total_quantity"];
                $totalAmount = $row["total_amount"];
                $status = $row["status"];

                echo '<div class="card ' . strtolower(str_replace(' ', '-', $status)) . '">';
                echo '<div class="details">';
                echo '<h3>Name : ' . $cName . '</h3>';
                echo '<p>Address : ' . $cAddress . '</p>';
                echo '<p>Contact : ' . $contact . '</p>';

                $itemResult = getOrderItems($conn, $orderId);

                if (mysqli_num_rows($itemResult) > 0) {
                    echo '<ul class="order-items">';
                    while ($itemRow = mysqli_fetch_assoc($itemResult)) {
                        $itemName = $itemRow["food_name"];
                        $itemPrice = $itemRow["price"];
                        $itemQuantity = $itemRow["quantity"];

                        echo '<li>';
                        echo '<span>' . $itemName . '</span> - <span>Rs. ' . $itemPrice . '</span> x <span> ' . $itemQuantity . '</span>';
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p>No items found for this order</p>';
                }

                echo '<p>Total Food Quantity : ' . $totalQuan . '</p>';
                echo '<p>Total Amount : ' . $totalAmount . '</p>';

                if ($status != 'Recieved') {
                    echo '<form action="updateOrderStatus.php" method="post">';
                    echo '<input type="hidden" name="order_id" value="' . $orderId . '">';
                    echo '<div class="form-group">';
                    echo '<label for="status">Status : </label>';
                    echo '<select name="status" id="status">';
                    echo '<option value="Pending" ' . ($status == 'Pending' ? 'selected' : '') . '>Pending</option>';
                    echo '<option value="Order Confirmed" ' . ($status == 'Order Confirmed' ? 'selected' : '') . '>Order Confirmed</option>';
                    echo '<option value="Order Prepared" ' . ($status == 'Order Prepared' ? 'selected' : '') . '>Order Prepared</option>';
                    echo '<option value="Order Dispatched" ' . ($status == 'Order Dispatched' ? 'selected' : '') . '>Order Dispatched</option>';
                    echo '</select>';
                    echo '</div>';
                    echo '<button type="submit" name="update_status" class="update-button">Update Status</button>';
                    echo '</form>';
                }

                echo '</div>';
                echo '</div>';
            }

            $sql = "SELECT * FROM online_order";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    displayOrderDetails($row, $conn);
                }
            } else {
                echo '<p>No Orders</p>';
            }
            ?>
        </div>
    </div>

    <script>
        function filterOrders(status) {
            var cards = document.querySelectorAll('.card');
            cards.forEach(function(card) {
                if (card.classList.contains(status.toLowerCase().replace(' ', '-')) || status === 'All') {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            var buttons = document.querySelectorAll('.category-buttons button');
            buttons.forEach(function(button) {
                button.classList.remove('active');
            });

            var activeButton = document.querySelector('.category-buttons button[onclick="filterOrders(\'' + status + '\')"]');
            if (activeButton) {
                activeButton.classList.add('active');
            }
        }

        function showAllOrders() {
            filterOrders('All');
        }
    </script>

</body>
</html>
