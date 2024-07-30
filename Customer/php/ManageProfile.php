<?php
include '../../Connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userId = null;
$user = null;

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT * FROM user WHERE id = $userId;";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Update Profile</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../css/ManageProfile.css">
</head>
<body>

<div class="container">

<a href="../../Customer/php/Home.php" class="btn">< Back</a>

    <h2>Update Profile Information</h2>
    <form id="updateForm" action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" id="user_id" name="user_id" value="<?php echo htmlspecialchars($userId); ?>">
        <div class="form-group">
            <label for="Name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="Email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="Contact">Contact:</label>
            <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($user['contact'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="Address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
        </div>
        <button type="submit" name="btn-update">Update</button>
    </form>
</div>

</body>
</html>

<?php
if (isset($_POST["btn-update"])) {
    $update_name = $_POST["name"];
    $update_email = $_POST["email"];
    $update_contact = $_POST["contact"];
    $update_address = $_POST["address"];
    $id = $_POST["user_id"];

    if (updateUser($conn, $id, $update_name, $update_email, $update_contact, $update_address)) {
        $_SESSION['user_name'] = $update_name;
        $_SESSION['user_email'] = $update_email;
        $_SESSION['user_contact'] = $update_contact;
        $_SESSION['user_address'] = $update_address;
    
        echo '<script>alert("Your profile has been updated");
                window.location.href = "Home.php";
                </script>';
        exit();
    } else {
        echo '<script>alert("Failed to update your Profile");</script>';
    }
    
}

function fieldsEmpty($update_name, $update_email, $update_contact, $update_address) {
    return empty($update_name) || empty($update_email) || empty($update_contact) || empty($update_address);
}

function updateUser($conn, $id, $update_name, $update_email, $update_contact, $update_address) {
    $sql = "UPDATE user SET name='$update_name', email='$update_email', contact='$update_contact', address='$update_address' WHERE id='$id';";
    $result = mysqli_query($conn, $sql);

    return $result;
}
?>
