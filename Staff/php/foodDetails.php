<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../../Connection.php");

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$foodname = isset($_GET['foodname']) ? urldecode($_GET['foodname']) : '';

$sql = "SELECT * FROM foods WHERE foodname = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $foodname);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $foodname = $row['foodname'];
    $pricenormal = $row['pricenormal'];
    $pricemedium = $row['pricemedium'];
    $pricelarge = $row['pricelarge'];
    $fooddescription = $row['fooddescription'];
    $foodcategory = $row['foodcategory'];
    $foodimage = '../../Admin/uploads/foods/' . $row["foodimage"];
} else {
    echo "<p>No food details available for the selected item.</p>";
    exit;
}

$stmt->close();

$sql = "SELECT rating, comment FROM feedback WHERE foodname = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $foodname);
$stmt->execute();
$feedbackResult = $stmt->get_result();

$ratings = [];
$comments = [];
while ($row = $feedbackResult->fetch_assoc()) {
    $ratings[] = $row['rating'];
    $comments[] = $row['comment'];
}

$averageRating = !empty($ratings) ? array_sum($ratings) / count($ratings) : 0;

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Details - <?php echo htmlspecialchars($foodname); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../../Staff/css/foodDetails.css">
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


<div class="food-details-container">
    <h1><?php echo htmlspecialchars($foodname); ?></h1>
    <img src="<?php echo htmlspecialchars($foodimage); ?>" alt="<?php echo htmlspecialchars($foodname); ?>">
    <p><?php echo nl2br(htmlspecialchars($fooddescription)); ?></p>
    <div class="price-details">
        <?php if (!empty($pricenormal)) : ?>
            <p>Normal: Rs. <?php echo htmlspecialchars($pricenormal); ?></p>
        <?php endif; ?>
        <?php if (!empty($pricemedium)) : ?>
            <p>Medium: Rs. <?php echo htmlspecialchars($pricemedium); ?></p>
        <?php endif; ?>
        <?php if (!empty($pricelarge)) : ?>
            <p>Large: Rs. <?php echo htmlspecialchars($pricelarge); ?></p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
