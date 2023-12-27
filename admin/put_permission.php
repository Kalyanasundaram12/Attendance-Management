<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "staf_activity";

// Create connection
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch form data
    $employeeID = isset($_POST['employeeID']) ? $_POST['employeeID'] : "";
    $employeeName = isset($_POST['employeeName']) ? $_POST['employeeName'] : "";
    $permissionDate = isset($_POST['permissionDate']) ? $_POST['permissionDate'] : "";
    $permissionReason = isset($_POST['permissionReason']) ? $_POST['permissionReason'] : "";
    $permissionHours = isset($_POST['permissionHours']) ? $_POST['permissionHours'] : "";

    // Set timezone to Asia/Kolkata
    date_default_timezone_set('Asia/Kolkata');
    $time = date("H:i:s");
    $today = date("D - F d, Y");
    $date = date("Y-m-d");

    // Insert data into permissions table using prepared statements
    $sql = "INSERT INTO permissions (employee_id, employee_name, permission_date, permission_reason, time, permission_hours) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssss", $employeeID, $employeeName, $permissionDate, $permissionReason, $time, $permissionHours);
        if ($stmt->execute()) {
            // Redirect to admin panel or display success message
            header("Location: home.php");
            exit();
        } else {
            // Handle error, display error message or log
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $db->error;
    }

    // Close prepared statement
    $stmt->close();
}

// Close database connection
$db->close();
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>HR | Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
    </li>
</ul>

<form class="form-inline ml-3">
    <div class="input-group input-group-sm">

    </div>
</form>
<ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fas fa-user"></i>
            <span class="hidden-xs"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header" style="max-height: 150px; overflow:hidden; background: #1c2121;">
                <div class="image">
                    <img src="dist/img/admin.png" style="border-radius: 50%;width: 100x;height: 100px;" alt="User Image">
                </div>
            </span>

            <form method="POST">
                <button type="submit" name="logout" class="dropdown-item dropdown-footer">Logout</a>
            </form>
        </div>
    </li>

</ul>
</nav>




<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: #222d32;">

<a href="home.php" class="brand-link">
    <img src="dist/img/admin.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
    <span class="brand-text font-weight-light">Employee Attendance</span>
</a>

<div class="sidebar">
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-flat nav-legacy nav-compact" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item">
                <a href="home.php" class="nav-link ">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>

            <li class="nav-item active">
                <a href="employee_attendance.php" class="nav-link active">
                    <i class="nav-icon far fa-calendar-alt"></i>
                    <p>
                        Attendance
                    </p>
                </a>
            </li>
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Employees
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="employee_list.php" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Employee List</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="employee_sched.php" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Schedules</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="employee_deduction.php" class="nav-link">
                    <i class="nav-icon fas fa-sticky-note"></i>
                    <p>
                        Deduction
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="employee_positions.php" class="nav-link">
                    <i class="nav-icon fas fa-briefcase"></i>
                    <p>
                        Positions
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="print_payroll.php" class="nav-link">
                    <i class="nav-icon fas fa-money-bill-alt"></i>
                    <p>Attendance Calculator</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="print_sched.php" class="nav-link">
                    <i class="nav-icon far fa-clock"></i>
                    <p>Schedules</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="employee_permission.php" class="nav-link">
                    <i class="nav-icon fa fa-lock"></i>
                    <p>Permissions</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="put_permission.php" class="nav-link">
                    <i class="nav-icon fa fa-lock"></i>
                    <p>Admin Permissions</p>
                </a>
            </li>
        </ul>
    </nav>

</div>
</aside>
<div class="content-wrapper">

            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Permission</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item active">Attendance</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Permission Details</h3>
                            </div>
                            <div class="card-body">
                                <!-- Form for admin to input permission details -->
                                <form method="POST">
                                    <div class="form-group">
                                        <label for="employeeID">Employee ID:</label>
                                        <input type="text" class="form-control" id="employeeID" name="employeeID">
                                    </div>
                                    <div class="form-group">
                                        <label for="employeeName">Employee Name:</label>
                                        <input type="text" class="form-control" id="employeeName" name="employeeName">
                                    </div>
                                    <div class="form-group">
                                        <label for="permissionDate">Permission Date:</label>
                                        <input type="text" class="form-control" id="permissionDate" name="permissionDate" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="permissionReason">Permission Reason:</label>
                                        <input type="text" class="form-control" id="permissionReason" name="permissionReason">
                                    </div>
                                    <div class="form-group">
                                        <label for="permissionHours">Permission Hours:</label>
                                        <input type="text" class="form-control" id="permissionHours" name="permissionHours">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>

                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
        <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="plugins/datatables/jquery.dataTables.js"></script>
        <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
        <script src="dist/js/adminlte.min.js"></script>
        <script src="dist/js/demo.js"></script>

        <script>
            $(function() {
                $("#permissionTable").DataTable();
            });
        </script>
    </div>
</body>

</html>