<?php
include '../../Connection.php';

$query = isset($_GET['query']) ? mysqli_real_escape_string($conn, $_GET['query']) : '';
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';

$sql = "SELECT foodname FROM foods WHERE foodname LIKE '%$query%'";

if ($category) {
    $sql .= " AND foodcategory = '$category'";
}

$result = mysqli_query($conn, $sql);
$suggestions = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $suggestions[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($suggestions);

$conn->close();
?>
