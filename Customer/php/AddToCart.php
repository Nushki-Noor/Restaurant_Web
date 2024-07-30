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


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['foodname'], $_POST['price'], $_POST['quantity'])) {
        $foodName = $_POST['foodname'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $userId = $_SESSION['user_id'];

       
        $sql = "SELECT * FROM foods WHERE foodname = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $foodName);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result->num_rows > 0) {
            $foodItem = mysqli_fetch_assoc($result);
            $foodImage = '../../Admin/uploads/foods/' . $foodItem["foodimage"];
            $availableQuantity = $foodItem["foodqty"];
            mysqli_stmt_close($stmt);

            
            if ($quantity <= $availableQuantity) {
               
                $sqlInsert = "INSERT INTO cart (user_id, food_name, price, quantity, food_image) VALUES (?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($conn, $sqlInsert);
                mysqli_stmt_bind_param($stmtInsert, "isiss", $userId, $foodName, $price, $quantity, $foodImage);

                if (mysqli_stmt_execute($stmtInsert)) {
                    echo '<script>alert("Added to cart Successfully");
                    window.location.href = "Menu.php";
                    </script>';
                } else {
                    echo '<script>alert("Failed to add to Cart")</script>';
                }

                mysqli_stmt_close($stmtInsert);
            } else {
                echo '<script>alert("Requested quantity not available. Only ' . $availableQuantity . ' available for the day")</script>';

            }
        } else {
            echo '<script>alert("Food item not found")</script>';
        }

        mysqli_close($conn);
    } else {
        echo '<script>alert("Invalid request")</script>';
    }
} else {
    echo '<script>alert("Invalid request method")</script>';
}

?>
