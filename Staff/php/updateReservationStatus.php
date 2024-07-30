<?php
include '../../Connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservationId = $_POST["reservation_id"];
    $action = $_POST["action"];

    $sql = "UPDATE reservations SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $action, $reservationId);

    if (mysqli_stmt_execute($stmt)) {
        if ($action === "Cancel Request Confirmed") {
            // Redirect to deleteReserveTables.php after updating the status
            header("location: deleteReserveTables.php?reservation_id=$reservationId");
        } else {
            // Redirect back to the reservations page with a success message
            $_SESSION["message"] = "Reservation status updated successfully.";
            header("location: ViewReservations.php");
        }
    } else {
        // Redirect back to the reservations page with an error message
        $_SESSION["message"] = "Error updating reservation status.";
        header("location: ViewReservations.php");
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>
