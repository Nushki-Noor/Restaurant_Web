<?php
session_start();
include '../../Connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Reservations</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../css/ViewReservations.css" type="text/css">
    <script>
        function handleAction(reservationId, action) {
            const form = document.getElementById(`action-form-${reservationId}`);
            const actionInput = document.getElementById(`action-input-${reservationId}`);
            actionInput.value = action;
            form.action = 'updateReservationStatus.php';
            form.submit();
        }
    </script>

</head>
<body>

<div class="navbar">
    <img src="../../Pictures/Ceylon Spice Logo1.png" class="logo">
    <ul class="navbar-nav">
        <li class="nav-item"><a class="dropdown-item" href="Manager.php">Dashboard</a></li>
        <li class="nav-item"><a class="dropdown-item" href="viewMenu.php">View Menu</a></li>
        <li class="nav-item"><a class="dropdown-item" href="ViewReservations.php">View Reservations</a></li>
        <li class="nav-item"><a class="dropdown-item" href="ViewEventBookings.php">View Event Bookings</a></li>
        <li class="nav-item"><a class="nav-link" href="../../logout.php">Logout</a></li>
    </ul>
</div>

<div class="container">
    <h1>View Reservations</h1>
    <section>
        <div class="search-bar">
            <form id="searchForm" method="get">
                <label for="searchEmail">Email:</label>
                <input type="email" id="searchEmail" name="email">
                
                <label for="searchDate">Date:</label>
                <input type="date" id="searchDate" name="date">
                
                <label for="searchTime">Time:</label>
                <select id="searchTime" name="time">
                    <option value="">Select Time</option>
                    <option value="12:00 PM - 2:00 PM">12:00 PM - 2:00 PM</option>
                    <option value="2:30 PM - 4:30 PM">2:30 PM - 4:30 PM</option>
                    <option value="5:00 PM - 7:00 PM">5:00 PM - 7:00 PM</option>
                    <option value="7:30 PM - 9:30 PM">7:30 PM - 9:30 PM</option>
                    <option value="10:00 PM - 12:00 AM">10:00 PM - 12:00 AM</option>
                </select>

                <label for="searchContact">Contact Number:</label>
                <input type="text" id="searchContact" name="contact">

                <label for="searchTableCategory">Table Category:</label>
                <select id="searchTableCategory" name="tableCategory">
                    <option value="">Select Category</option>
                    <option value="2">Table For 2 Person</option>
                    <option value="4">Table For 4 Person</option>
                    <option value="6">Table For 6 Person</option>
                    <option value="8">Table For 8 Person</option>
                    <option value="10">Table For 10 Person</option>
                </select>

                <input type="submit" value="Search">
            </form>
        </div>

        <div class="reservation-table">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Table Category</th>
                        <th>Tables</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Request Action</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $email = isset($_GET['email']) ? $_GET['email'] : '';
                $date = isset($_GET['date']) ? $_GET['date'] : '';
                $time = isset($_GET['time']) ? $_GET['time'] : '';
                $contact = isset($_GET['contact']) ? $_GET['contact'] : '';
                $tableCategory = isset($_GET['tableCategory']) ? $_GET['tableCategory'] : '';

                $conditions = [];
                if ($email) $conditions[] = "email LIKE '%$email%'";
                if ($date) $conditions[] = "date = '$date'";
                if ($time) $conditions[] = "time = '$time'";
                if ($contact) $conditions[] = "contact LIKE '%$contact%'";
                if ($tableCategory) $conditions[] = "table_category = '$tableCategory'";
                // $conditions[] = "status = 'Request Cancel'";

                $sql = "SELECT reservations.*, GROUP_CONCAT(reserve_tables.tables SEPARATOR ', ') as tables 
                        FROM reservations
                        LEFT JOIN reserve_tables ON reservations.id = reserve_tables.reservation_id";

                if ($conditions) {
                    $sql .= " WHERE " . implode(' AND ', $conditions);
                }

                $sql .= " GROUP BY reservations.id";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $reservationId = $row['id'];
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['date']}</td>
                                <td>{$row['time']}</td>
                                <td>{$row['table_category']}</td>
                                <td>{$row['tables']}</td>
                                <td>{$row['total_price']}</td>
                                <td>{$row['status']}</td>";

                        if ($row['status'] == 'Request Cancel') {
                            echo "<td>
                                    <form id='action-form-{$reservationId}' method='post' action='updateReservationStatus.php'>
                                        <input type='hidden' name='reservation_id' value='{$reservationId}'>
                                        <input type='hidden' id='action-input-{$reservationId}' name='action'>
                                        <button type='button' onclick='handleAction({$reservationId}, \"Cancel Request Rejected\")'>Reject</button>
                                        <button type='button' onclick='handleAction({$reservationId}, \"Cancel Request Confirmed\")'>Confirm</button>
                                    </form>
                                </td>";
                        } else {
                            echo "<td></td>"; // Empty cell if status is not 'Request Cancel'
                        }

                        echo "<td>
                            <form id='deleteForm' method='post' action='deleteReservation.php'>
                                <input type='hidden' name='reservation_id' value='{$row['id']}'>
                                <input type='submit' value='Delete'>
                            </form>
                        </td>
                        </tr>";

                        echo "<script>
                                document.getElementById('deleteForm').addEventListener('submit', function(event) {
                                    event.preventDefault();

                                    if (confirm('Are you sure you want to delete this reservation?')) {
                                        this.submit();
                                        alert('Reservation deleted successfully.');
                                    } else {
                                    }
                                });
                            </script>";

                    }
                } else {
                    echo "<tr><td colspan='9'>No reservations found</td></tr>";
                }
                ?>

                </tbody>
            </table>
        </div>
    </section>
</div>

</body>
</html>
