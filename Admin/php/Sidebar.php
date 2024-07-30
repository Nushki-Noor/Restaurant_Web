<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin</title>
  <link rel="shortcut icon" href="../../Pictures/Ceylon Spice Mini.png">
  <link rel="stylesheet" href="../css/Sidebar.css">
  <script defer src="../js/Admin.js"></script>
</head>
<body>

<!--<div>
  <h1 class="heading"><span>Welcome Admin..</span> </h1>
</div>-->

<nav class="navbar">
  <img src="../../Pictures/Ceylon Spice Logo1.png" class="logo">
  <div class="navbar-collapse">
    <hr>
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="Admin.php">Dashboard</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="manageFoodDropdown" role="button">Manage Staff</a>
        <div class="dropdown-menu" aria-labelledby="manageFoodDropdown">
          <a class="dropdown-item" href="AddStaff.php">Add Staff</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="manageTableDropdown" role="button">Manage Menu</a>
        <div class="dropdown-menu" aria-labelledby="manageTableDropdown">
          <a class="dropdown-item" href="AddFood.php">Add Food Details</a>
          <a class="dropdown-item" href="UpdateFood.php">Update Food Items</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="manageTableDropdown" role="button">Manage Events</a>
        <div class="dropdown-menu" aria-labelledby="manageTableDropdown">
          <a class="dropdown-item" href="AddEvent.php">Add Events</a>
          <a class="dropdown-item" href="UpdateEvent.php">Update Events</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../../logout.php">Logout</a>
      </li>
    </ul>
  </div>
</nav>

</body>
</html>