<?php
include '../../Connection.php';

session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceylon Spice - Event Bookings</title>
    <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
    <link rel="stylesheet" href="../css/ViewEventBookings.css">
</head>
<body>

<div class="navbar">
        <img src="../../Pictures/Ceylon Spice Logo1.png" class="logo">
        <ul class="navbar-nav">
        <li class="nav-item">
                    <a class="dropdown-item" href="Manager.php">Dashboard</a>
                </li>
            <li class="nav-item">
                <a class="dropdown-item" href="viewMenu.php">View Menu</a>
            </li>
            <li class="nav-item">
                <a class="dropdown-item" href="ViewReservations.php">View Reservations</a>
            </li>
            <li class="nav-item">
                <a class="dropdown-item" href="ViewEventBookings.php">View Event Bookings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../logout.php">Logout</a>
            </li>
        </ul>
    </div>

    <div class="container">
        <h1 class="heading">Event Bookings</h1>

        <form method="GET" action="" class="search-form"> 
            <input type="text" name="searchEmail" placeholder="Search by email" >
            <input type="text" name="searchEventName" placeholder="Search by event name">
            <input type="text" name="searchDate" placeholder="Search by date">
            <button type="submit" name="btn-search">Search</button>
            <button type="submit" name="btn-show-all">Show All</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>User Email</th>
                    <th>Ticket Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $sql = "SELECT event.name , event.date , event.time , eventbooking.useremail , eventbooking.ticketquantity 
                FROM eventbooking
                INNER JOIN event ON eventbooking.eventid = event.id";

                $where = "";

                if (isset($_GET["btn-search"])) {

                    // $searchEmail = mysqli_real_escape_string($conn, $_GET["searchEmail"]);
                    // $sql .= " WHERE eventbooking.useremail = '$searchEmail'";

                    if (!empty($_GET["searchEmail"])) {
                        $searchEmail = mysqli_real_escape_string($conn, $_GET["searchEmail"]);
                        $where .= " eventbooking.useremail = '$searchEmail'";
                    }

                    if (!empty($_GET["searchEventName"])) {
                        $searchEventName = mysqli_real_escape_string($conn, $_GET["searchEventName"]);
                        if (!empty($where)) {
                            $where .= " AND";
                        }
                        $where .= " event.name LIKE '%$searchEventName%'";
                    }

                    if (!empty($_GET["searchDate"])) {
                        $searchDate = mysqli_real_escape_string($conn, $_GET["searchDate"]);
                        if (!empty($where)) {
                            $where .= " AND";
                        }
                        $where .= " event.date = '$searchDate'";
                    }

                    if (!empty($where)) {
                        $sql .= " WHERE" . $where;
                    } 
                } elseif (isset($_GET["btn-show-all"])) {
                   
                }

                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['date']}</td>
                                <td>{$row['time']}</td>
                                <td>{$row['useremail']}</td>
                                <td>{$row['ticketquantity']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No bookings found</td></tr>";
                }
                
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
