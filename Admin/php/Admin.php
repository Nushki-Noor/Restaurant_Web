<?php
session_start();
if (!isset($_SESSION["user_email"])) {
  header("Location: ../../login.php");
  exit();
}

include('../../Connection.php'); 

$current_page = basename($_SERVER['PHP_SELF']);

// Most selling food item
$most_selling_food_query = "SELECT food_name, SUM(quantity) as total_quantity FROM order_item GROUP BY food_name ORDER BY total_quantity DESC LIMIT 1";
$most_selling_food_result = $conn->query($most_selling_food_query);
$most_selling_food = $most_selling_food_result->fetch_assoc();

// Most rated food item
$most_rated_food_query = "SELECT foodname, AVG(rating) as average_rating FROM feedback GROUP BY foodname ORDER BY average_rating DESC LIMIT 1";
$most_rated_food_result = $conn->query($most_rated_food_query);
$most_rated_food = $most_rated_food_result->fetch_assoc();

// Total sales for the day
$total_sales_day_query = "SELECT SUM(total_amount) as total_sales_day FROM online_order WHERE DATE(order_date) = CURDATE()";
$total_sales_day_result = $conn->query($total_sales_day_query);
$total_sales_day = $total_sales_day_result->fetch_assoc();

// Total sales for the month
$total_sales_month_query = "SELECT SUM(total_amount) as total_sales_month FROM online_order WHERE MONTH(order_date) = MONTH(CURDATE())";
$total_sales_month_result = $conn->query($total_sales_month_query);
$total_sales_month = $total_sales_month_result->fetch_assoc();

// Total ticket sales of events
$total_ticket_sales_query = "SELECT SUM(total) as total_ticket_sales FROM eventbooking";
$total_ticket_sales_result = $conn->query($total_ticket_sales_query);
$total_ticket_sales = $total_ticket_sales_result->fetch_assoc();

// Total table reservation sales
$total_reservation_sales_query = "SELECT SUM(total_price) as total_reservation_sales FROM reservations";
$total_reservation_sales_result = $conn->query($total_reservation_sales_query);
$total_reservation_sales = $total_reservation_sales_result->fetch_assoc();

// Sales for the selected date
$total_sales_selected_date = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selected_date"])) {
  $selected_date = $_POST["selected_date"];
  $total_sales_selected_date_query = "SELECT SUM(total_amount) as total_sales_selected_date FROM online_order WHERE DATE(order_date) = ?";
  $stmt = $conn->prepare($total_sales_selected_date_query);
  $stmt->bind_param("s", $selected_date);
  $stmt->execute();
  $total_sales_selected_date_result = $stmt->get_result();
  $total_sales_selected_date = $total_sales_selected_date_result->fetch_assoc();
  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Dashboard</title>
  <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
  <link rel="stylesheet" href="../css/Admin.css">
</head>
<body>

<!--<div>
  <h1 class="heading"><span>Welcome Admin..</span> </h1>
</div>-->

<?php include '../../Admin/php/Sidebar.php'; ?>

<div class="content">
<div class="statistics">
  <div class="stat-item">
    <h3>Most Selling Food Item</h3>
    <p><?php echo ($most_selling_food['food_name'] ?? '') . " (" . ($most_selling_food['total_quantity'] ?? 0) . " sold)"; ?></p>
  </div>

  <div class="stat-item">
    <h3>Most Rated Food Item</h3>
    <p><?php echo ($most_rated_food['foodname'] ?? '') . " (Average Rating: " . number_format($most_rated_food['average_rating'] ?? 0, 2) . ")"; ?></p>
  </div>

  <div class="stat-item">
    <h3>Total Sales for Today</h3>
    <p><?php echo "Rs. " . number_format($total_sales_day['total_sales_day']?? 0, 2); ?></p>
  </div>

  <div class="stat-item">
    <h3>Total Sales for This Month</h3>
    <p><?php echo "Rs. " . number_format($total_sales_month['total_sales_month']?? 0, 2); ?></p>
  </div>
  
  <div class="stat-item">
    <h3>Total Ticket Sales</h3>
    <p><?php echo "Rs. " . number_format($total_ticket_sales['total_ticket_sales']?? 0, 2); ?></p>
  </div>

  <div class="stat-item">
    <h3>Total Reservation Sales</h3>
    <p><?php echo "Rs. " . number_format($total_reservation_sales['total_reservation_sales']?? 0, 2); ?></p>
  </div>

  <div class="stat-item">
    <h3>Online Sales</h3>
    <p><?php echo isset($total_sales_selected_date) ? "Rs. " . number_format($total_sales_selected_date['total_sales_selected_date'] ?? 0, 2) : "Select a date"; ?></p>

    <form method="post" action="">
    <label for="selected_date">Select Date:</label>
    <input type="date" id="selected_date" name="selected_date">
    <button type="submit">Show Sales</button>
    </form>
  </div>

</div>
</div>

</body>
</html>
