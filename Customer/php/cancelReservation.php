<?php
include '../../Connection.php';
session_start();

if (!isset($_SESSION["user_name"])) {
    header("location: ../../Login/login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservationId = $_POST["reservation_id"];

    $sql = "UPDATE reservations SET status = 'Request Cancel' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $reservationId);
    
    if (mysqli_stmt_execute($stmt)) {
       
        $_SESSION["message"] = "Reservation cancellation requested successfully.";
        echo '<script>alert("Reservation cancellation requested successfully")</script>';
        header("location: ViewReserveDetails.php");
    } else {
       
        $_SESSION["message"] = "Error requesting reservation cancellation.";
        header("location:  ViewReserveDetails.php");
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
