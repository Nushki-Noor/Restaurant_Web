<?php
include '../../Connection.php';
session_start();

if (isset($_POST['update_status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['status'];

    $sql = "SELECT status FROM online_order WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $currentStatus = $row['status'];

        if ($currentStatus == 'Recieved') {
            echo '<script>alert("Cannot update status because it is already \'Recieved\'.");</script>';
            echo '<script>window.location.href = "KTManager.php";</script>';
            exit();
        } else {
            $updateSql = "UPDATE online_order SET status = ? WHERE id = ?";
            $updateStmt = mysqli_prepare($conn, $updateSql);
            mysqli_stmt_bind_param($updateStmt, "si", $newStatus, $orderId);
            mysqli_stmt_execute($updateStmt);

            if (mysqli_stmt_affected_rows($updateStmt) > 0) {
                echo '<script>alert("Status updated successfully!");</script>';
            } else {
                echo '<script>alert("Failed to update status. Please try again.");</script>';
            }

            mysqli_stmt_close($updateStmt);
        }
    } else {
        echo '<script>alert("Order not found.");</script>';
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
