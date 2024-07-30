<?php
include '../../Connection.php';

session_start();
if (!isset ($_SESSION["user_email"])) {
  header("Location: ../../login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Food Update</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../css/UpdateFood.css">
</head>
<body>

<?php include '../../Admin/php/Sidebar.php'; ?>

<div class="container">
    <h2>Food Information</h2>
    <table>
        <thead>
            <tr>
                <th>Food Name</th>
                <th>Normal Price</th>
                <th>Medium Price</th>
                <th>Large Price</th>
                <th>Description</th>
                <th>Quantity</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM foods;";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $imagePath = '../uploads/foods/' . $row["foodimage"];
                    echo "<tr>";
                    echo "<td>" . $row["foodname"] . "</td>";
                    echo "<td>" . $row["pricenormal"] . "</td>";
                    echo "<td>" . $row["pricemedium"] . "</td>";
                    echo "<td>" . $row["pricelarge"] . "</td>";
                    echo "<td>" . $row["fooddescription"] . "</td>";
                    echo "<td>" . $row["foodqty"] . "</td>";
                    echo "<td><img src='$imagePath' alt='" . "' width='50'></td>";
                    echo '<td><button class="edit-btn" onclick="openUpdateForm(\'' . $row["foodname"] . '\', ' . $row["pricenormal"] . ', ' . $row["pricemedium"] . ', ' . $row["pricelarge"] . ', \'' . $row["fooddescription"] . '\', \'' . $row["foodqty"] . '\', \'' . $row["foodimage"] . '\', ' . $row["id"] . ')">Edit</button></td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <form id="updateForm" action="" method="POST" enctype="multipart/form-data">
    <h2>Update Food Information</h2>
        <input type="hidden" id="foodId" name="foodId">
        <div class="form-group">
            <label for="foodName">Food Name:</label>
            <input type="text" id="foodName" name="foodName" required>
        </div>
        <div class="form-group">
            <label for="pricenormal">Normal Price:</label>
            <input type="number" id="pricenormal" name="pricenormal">
        </div>
        <div class="form-group">
            <label for="pricemedium">Medium Price:</label>
            <input type="number" id="pricemedium" name="pricemedium">
        </div>
        <div class="form-group">
            <label for="pricelarge">Large Price:</label>
            <input type="number" id="pricelarge" name="pricelarge">
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="quantity">Food Quantity:</label>
            <input type="number" id="quantity" name="quantity">
        </div>
        <div class="input-pic">
            <label for="foodImage">Food Image:</label>
            <input type="file" id="foodImage" name="foodImage">
        </div>
        <button type="submit" name="btn-update">Update</button>
    </form>
</div>

<script>
    function openUpdateForm(name, pricenormal, pricemedium, pricelarge, description, quantity, image, id) {
        document.getElementById('foodName').value = name;
        document.getElementById('pricenormal').value = pricenormal;
        document.getElementById('pricemedium').value = pricemedium;
        document.getElementById('pricelarge').value = pricelarge;
        document.getElementById('description').value = description;
        document.getElementById('quantity').value = quantity;
        document.getElementById('foodId').value = id;
    }
</script>

</body>
</html>

<?php
if (isset($_POST["btn-update"])) {
    $updateName = $_POST["foodName"];
    $updateNormalPrice = $_POST["pricenormal"];
    $updateMediumPrice = $_POST["pricemedium"];
    $updateLargePrice = $_POST["pricelarge"];
    $updateDescription = $_POST["description"];
    $updateQty = $_POST["quantity"];
    $id = $_POST["foodId"];

    $fileName = $_FILES['foodImage']['name'];
    $fileTmpName = $_FILES['foodImage']['tmp_name'];
    $fileType = $_FILES['foodImage']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    $imageUpdated = false;

    if (!empty($fileName)) {
        if (in_array($fileActualExt, $allowed)) {
            $fileDestination = '../uploads/foods/' . $fileName;

            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                $imageUpdated = true;
            } else {
                echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
                exit();
            }
        } else {
            echo '<script>alert("You cannot upload files of this type.");</script>';
            exit();
        }
    }

    if (empty($updateName) || empty($updateDescription) || empty($updateQty)) {
        echo '<script>alert("Please fill all the required details");</script>';

    } elseif (empty($updateNormalPrice) && (empty($updateMediumPrice) || empty($updateLargePrice))) {
        echo '<script>alert("Please provide both medium and large prices if normal price is not provided");</script>';

    } elseif (!empty($updateMediumPrice) && empty($updateLargePrice)) {
        echo '<script>alert("Please provide large price if medium price is provided");</script>';

    } else {
        if ($imageUpdated) {
            updateFood($conn, $id, $updateName, $updateNormalPrice, $updateMediumPrice, $updateLargePrice, $updateDescription,$updateQty ,$fileName);
        } else {
            updateFoodWithoutImage($conn, $id, $updateName, $updateNormalPrice, $updateMediumPrice, $updateLargePrice, $updateDescription,$updateQty);
        }
    }
}



function fieldsEmpty ($updateName, $updateNormalPrice, $updateDescription) {
    return empty($updateName) || empty($updateNormalPrice) || empty($updateDescription);
}

function updateFood ($conn, $id, $updateName, $updateNormalPrice, $updateMediumPrice, $updateLargePrice, $updateDescription,$updateQty ,$fileName) {
    $sql = "UPDATE foods SET foodname='$updateName', pricenormal='$updateNormalPrice', pricemedium='$updateMediumPrice', pricelarge='$updateLargePrice', fooddescription='$updateDescription',foodqty='$updateQty' ,foodimage='$fileName' WHERE id='$id';";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<script>alert("Food Updated Successfully"); window.location.href = "UpdateFood.php";</script>';
    } else {
        echo '<script>alert("Error updating record: ' . mysqli_error($conn) . '");</script>';
    }
}

function updateFoodWithoutImage ($conn, $id, $updateName, $updateNormalPrice, $updateMediumPrice, $updateLargePrice, $updateDescription,$updateQty) {
    $sql = "UPDATE foods SET foodname='$updateName', pricenormal='$updateNormalPrice', pricemedium='$updateMediumPrice', pricelarge='$updateLargePrice', fooddescription='$updateDescription',foodqty='$updateQty' WHERE id='$id';";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<script>alert("Food Updated Successfully"); window.location.href = "UpdateFood.php";</script>';
    } else {
        echo '<script>alert("Error updating record: ' . mysqli_error($conn) . '");</script>';
    }
}
?>
