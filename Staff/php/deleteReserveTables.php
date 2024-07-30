<?php
session_start();
include '../../Connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $reservation_id = $_GET['reservation_id'];

    // Delete from reserve_tables first due to foreign key constraint
    $sql = "DELETE FROM reserve_tables WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $reservation_id);
    $stmt->execute();
    $stmt->close();

    
    

    header("Location: ViewReservations.php");
    exit();
}
?>
