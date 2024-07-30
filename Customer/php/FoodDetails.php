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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rating']) && isset($_POST['comment'])) {
    $rating = (int)$_POST['rating'];
    $comment = trim($_POST['comment']);
    $customer_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Anonymous';

    if ($rating > 0 && $rating <= 5) { 
        $sql = "INSERT INTO feedback (foodname, rating, comment, userName) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siss", $foodname, $rating, $comment, $customer_name);
        $stmt->execute();
        $stmt->close();

        $_SESSION['feedback_submitted'] = true;

        header("Location: " . $_SERVER['PHP_SELF'] . "?foodname=" . urlencode($foodname));
        exit;
    }
}

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

$sql = "SELECT rating, comment, userName FROM feedback WHERE foodname = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $foodname);
$stmt->execute();
$feedbackResult = $stmt->get_result();

$ratings = [];
$comments = [];
$customer_names = [];
while ($row = $feedbackResult->fetch_assoc()) {
    $ratings[] = $row['rating'];
    $comments[] = $row['comment'];
    $customer_names[] = $row['userName'];
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
    <link rel="stylesheet" href="../../Customer/css/FoodDetails.css">
    <script src="../../Customer/js/FoodDetails.js"></script>
</head>
<body>

<?php include '../../Customer/php/Navbar.php'; ?>

<div class="food-details-container">
    <h1><?php echo htmlspecialchars($foodname); ?></h1>
    <img src="<?php echo htmlspecialchars($foodimage); ?>" alt="<?php echo htmlspecialchars($foodname); ?>">
    <p><?php echo nl2br(htmlspecialchars($fooddescription)); ?></p>
    
    <div class="price-details">
    <?php if ($pricenormal > 0): ?>
        <p>Normal: Rs. <?php echo htmlspecialchars($pricenormal); ?></p>
    <?php endif; ?>
    <?php if ($pricemedium > 0): ?>
        <p>Medium: Rs. <?php echo htmlspecialchars($pricemedium); ?></p>
    <?php endif; ?>
    <?php if ($pricelarge > 0): ?>
        <p>Large: Rs. <?php echo htmlspecialchars($pricelarge); ?></p>
    <?php endif; ?>
</div>


    <?php
    echo '<form action="../../Customer/php/AddToCart.php" method="post">
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
        <div class="quantity-container">
            <button type="button" class="decrement" onclick="this.nextElementSibling.stepDown()">-</button>
            <input type="number" name="quantity" class="qty" min="1" max="50" value="1" maxlength="2">
            <button type="button" class="increment" onclick="this.previousElementSibling.stepUp()">+</button>
        </div>

        <div class="button-container">
            <button type="submit" class="btnmore">
                <i class="fas fa-cart-plus"></i> Add to Cart
            </button>
        </div>
    </form>';
?>

    <hr class="separator">

    <div class="feedback-section">
        <h2>Rate Us</h2>
        
        <form method="POST" action="">
        <div class="rating" id="rating" name="rating" required>
        <input type="radio" id="star5" name="rating" value="5"><label for="star5"></label>
        <input type="radio" id="star4" name="rating" value="4"><label for="star4"></label>
        <input type="radio" id="star3" name="rating" value="3"><label for="star3"></label>
        <input type="radio" id="star2" name="rating" value="2"><label for="star2"></label>
        <input type="radio" id="star1" name="rating" value="1"><label for="star1"></label>
    </div>
            <br>
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" rows="4" cols="50"></textarea>
            <br>
            <button type="submit">Submit Feedback</button>
        </form>

        <hr>


 <div class="feedback-list">
    <h3>Customer's Reviews</h3>
    <p>Average Rating: <?php echo number_format($averageRating, 1); ?>/5</p>
    <div class="feedback-container">
        <button class="scroll-button left" onclick="scrollCarousel(-1)">&#10094;</button>
        <div class="feedback-carousel">
            <?php
            if (!empty($comments)) {
                foreach ($comments as $index => $comment) {
                    echo '<div class="feedback-item">';
                    echo '<div class="feedback-image">';
                    echo '</div>';
                    echo '<p>' . nl2br(htmlspecialchars($comment)) . '</p>';
                    echo '<div class="feedback-rating">';
                    for ($i = 0; $i < 5; $i++) {
                        echo $i < $ratings[$index] ? '<span class="star filled">&#9733;</span>' : '<span class="star">&#9734;</span>';
                    }
                    echo '</div>';
                    echo '<p class="customer-name">' . htmlspecialchars($customer_names[$index]) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No Feedbacks.</p>';
            }
            ?>
        </div>
        <button class="scroll-button right" onclick="scrollCarousel(1)">&#10095;</button>
    </div>
</div>

    </div>
</div>

</body>
</html>
