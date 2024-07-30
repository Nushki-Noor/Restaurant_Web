<?php
include '../../Connection.php';

// session_start();
// if (!isset($_SESSION["user_email"])) {
//     header("Location: ../../Login/login.php");
//     exit();
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Event Update</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../css/UpdateEvent.css">
</head>
<body>

<?php include '../../Admin/php/Sidebar.php'; ?>

<div class="container">
    <h2>Event Information</h2>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Date</th>
                <th>Time</th>
                <th>Available Tickets</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM event;";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $imagePath = '../uploads/event/' . $row["image"];
                    echo "<tr>";
                    echo "<td>" . $row["name"] . "</td>";
                    echo "<td>" . $row["price"] . "</td>";
                    echo "<td>" . $row["description"] . "</td>";
                    echo "<td>" . $row["date"] . "</td>";
                    echo "<td>" . $row["time"] . "</td>";
                    echo "<td>" . $row["tickets"] . "</td>";
                    echo "<td><img src='$imagePath' alt='" . $row["name"] . "' width='50'></td>";
                    echo '<td>';
                    echo '<button class="edit-btn" onclick="openUpdateForm(\'' . $row["name"] . '\', ' . $row["price"] . ', \'' . $row["description"] . '\', \'' . $row["date"] . '\', \'' . $row["time"] . '\', \'' . $row["tickets"] . '\', \'' . $row["image"] . '\', ' . $row["id"] . ')">Edit</button>';
                    echo ' <button class="edit-btn" onclick="confirmDelete(' . $row["id"] . ')">Delete</button>';
                    echo '</td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <form id="updateForm" action="" method="POST" enctype="multipart/form-data">
    <h2>Update Event Information</h2>
        <input type="hidden" id="eventId" name="eventId">
        <div class="form-group">
            <label for="eventName">Event Name:</label>
            <input type="text" id="eventName" name="eventName" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="text" id="date" name="date" required>
        </div>
        <div class="form-group">
            <label for="time">Time:</label>
            <input type="text" id="time" name="time" required>
        </div>
        <div class="form-group">
            <label for="tickets">Available Tickets:</label>
            <input type="number" id="tickets" name="tickets" required>
        </div>
        <div class="input-pic">
            <label for="eventImage">Image:</label>
            <input type="file" id="eventImage" name="eventImage">
        </div>
        <button type="submit" name="btn-update">Update</button>
    </form>
</div>

<script>
    function openUpdateForm(name, price, description, date, time, tickets, image, id) {
        document.getElementById('eventName').value = name;
        document.getElementById('price').value = price;
        document.getElementById('description').value = description;
        document.getElementById('date').value = date;
        document.getElementById('time').value = time;
        document.getElementById('tickets').value = tickets;
        document.getElementById('eventId').value = id;
    }

    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this event?')) {
            window.location.href = 'UpdateEvent.php?delete=' + id;
        }
    }
</script>
</body>
</html>

<?php
if (isset($_POST["btn-update"])) {
    $updateName = $_POST["eventName"];
    $updatePrice = $_POST["price"];
    $updateDescription = $_POST["description"];
    $updateDate = $_POST["date"];
    $updateTime = $_POST["time"];
    $updateTickets = $_POST["tickets"];
    $id = $_POST["eventId"];

    $fileName = $_FILES['eventImage']['name'];
    $fileTmpName = $_FILES['eventImage']['tmp_name'];
    $fileType = $_FILES['eventImage']['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png');

    $imageUpdated = false;

    if (!empty($fileName)) {
        if (in_array($fileActualExt, $allowed)) {
            $fileDestination = '../uploads/event/' . $fileName;

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

    if (fieldsEmpty($updateName, $updatePrice, $updateDescription, $updateDate, $updateTime, $updateTickets)) {
        echo '<script>alert("Fields cannot be empty");</script>';
    } else {
        if ($imageUpdated) {
            updateEvent($conn, $id, $updateName, $updatePrice, $updateDescription, $updateDate, $updateTime, $updateTickets, $fileName);
        } else {
            updateEventWithoutImage($conn, $id, $updateName, $updatePrice, $updateDescription, $updateDate, $updateTime, $updateTickets);
        }
    }
}

if (isset($_GET["delete"])) {
    $id = $_GET["delete"];
    deleteEvent($conn, $id);
}

function fieldsEmpty($updateName, $updatePrice, $updateDescription, $updateDate, $updateTime, $updateTickets) {
    $value;

    if ( empty($updateName) || empty($updatePrice) || empty($updateDescription) || empty($updateDate) || empty($updateTime) || empty($updateTickets)) {
        $value=true;
    } else {
        $value=false;
    }
    return $value;
}

function updateEvent($conn, $id, $updateName, $updatePrice, $updateDescription, $updateDate, $updateTime, $updateTickets, $fileName) {
    $sql = "UPDATE event SET name='$updateName', price='$updatePrice', description='$updateDescription', date='$updateDate', time='$updateTime', tickets='$updateTickets', image='$fileName' WHERE id='$id';";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<script>alert("Event Updated Successfully");</script>';
        header("Location: UpdateEvent.php");
        exit();
    } else {
        echo '<script>alert("Error updating event.");</script>';
    }
}

function updateEventWithoutImage($conn, $id, $updateName, $updatePrice, $updateDescription, $updateDate, $updateTime, $updateTickets) {
    $sql = "UPDATE event SET name='$updateName', price='$updatePrice', description='$updateDescription', date='$updateDate', time='$updateTime', tickets='$updateTickets' WHERE id='$id';";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<script>alert("Event Updated Successfully");</script>';
     
        exit();
    } else {
        echo '<script>alert("Error updating event.");</script>';
    }
}

function deleteEvent($conn, $id) {
    $sql = "DELETE FROM event WHERE id='$id';";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo '<script>alert("Event Deleted Successfully");</script>';
       
        exit();
    } else {
        echo '<script>alert("Error deleting event.");</script>';
    }
}
?>
