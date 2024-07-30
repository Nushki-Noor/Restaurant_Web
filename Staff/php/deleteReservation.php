<?php
session_start();
include '../../Connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservation_id = $_POST['reservation_id'];

    // Delete from reserve_tables first due to foreign key constraint
    $sql = "DELETE FROM reserve_tables WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $reservation_id);
    $stmt->execute();
    $stmt->close();

    // Delete from reservations
    $sql = "DELETE FROM reservations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $reservation_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Reservation deleted successfully!";
    } else {
        $_SESSION['message'] = "Failed to delete reservation.";
    }
    $stmt->close();
    $conn->close();

    header("Location: ViewReservations.php");
    exit();
}
?>
