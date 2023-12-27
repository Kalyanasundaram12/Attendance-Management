<?php
include("controller.php");
?>
<?php
// Include necessary files and establish database connection
// include("controller.php");
// Session handling or authentication checks if required
// Start session if needed: session_start();
// Check authentication or redirect if user not logged in
// if(!isset($_SESSION['user'])){
//     header("Location: login.php");
//     exit();
// }

// Your database connection logic here
// Example:
// $db = mysqli_connect('localhost', 'username', 'password', 'database_name');
// Check connection
// if (!$db) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// ... (other PHP code)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedMonth = $_POST['month'];
    $selectedYear = $_POST['year'];

    // Calculate total attendance hours, permission hours, and overtime hours for the selected month and year
    $sql = "SELECT emp_attendance.employee_id, emp_list.emp_fname, emp_list.emp_lname,
            SUM(attendance_hour) AS total_attendance_hours,
            SUM(permission_hours) AS total_permission_hours,
            SUM(permission_minutes) AS total_permission_minutes,
            SUM(CASE WHEN TIME(attendance_timeout) > '18:00:00' THEN (TIME_TO_SEC(TIMEDIFF(attendance_timeout, '18:00:00')) / 3600) ELSE 0 END) AS total_overtime_hours
            FROM emp_attendance
            LEFT JOIN emp_list ON emp_attendance.employee_id = emp_list.emp_card
            WHERE MONTH(attendance_date) = $selectedMonth AND YEAR(attendance_date) = $selectedYear
            GROUP BY emp_attendance.employee_id";

    $result = mysqli_query($db, $sql);
}

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
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">

    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

    <link rel="stylesheet" href="plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">


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
                        <span class="dropdown-item dropdown-header" style="max-height: 150px; overflow:hidden; background:#222d32;">
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
                            <a href="home.php" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="employee_attendance.php" class="nav-link">
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
                            <a href="print_payroll.php" class="nav-link active">
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
                    </ul>
                </nav>

            </div>

        </aside>


        <div class="content-wrapper">

            <div class="content-header">

                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Print Attendance</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home.php">Home</a></li>
                                <li class="breadcrumb-item active">Print</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <section class="content">
                <form method="POST">
                <label for="month">Select Month:</label>
                <select id="month" name="month">
                    <?php
                    for ($m = 1; $m <= 12; ++$m) {
                        echo '<option value="' . $m . '">' . date('F', mktime(0, 0, 0, $m, 1)) . '</option>';
                    }
                    ?>
                </select>

                <label for="year">Select Year:</label>
                <select id="year" name="year">
                    <?php
                    $currentYear = date("Y");
                    $startYear = $currentYear - 10; // Change the range as needed
                    for ($y = $startYear; $y <= $currentYear; ++$y) {
                        echo '<option value="' . $y . '">' . $y . '</option>';
                    }
                    ?>
                </select>

                <button type="submit">View Attendance</button>
            </form>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="box-header with-border">
                                <!-- ... -->
                            </div>
                            <hr>
                            <div class="card-body">
                                <?php if (isset($result)) : ?>
                                    <table id="example1" class="table table-bordered dataTable no-footer" role="grid" aria-describedby="example1_info">
                                        <!-- Table headers -->
                                        <thead>
                                            <tr>
                                                <th>Employee ID</th>
                                                <th>Employee Name</th>
                                                <th>Total Attendance Hours</th>
                                                <th>Total Permission Hours</th>
                                                <th>Total Overtime Hours</th>
                                                <th>Print</th> <!-- Add a column for Print -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                                                <tr>
                                                    <!-- Display attendance data -->
                                                    <td><?php echo $row['employee_id']; ?></td>
                                                    <td><?php echo $row['emp_lname'] . ", " . $row['emp_fname']; ?></td>
                                                    <td><?php echo $row['total_attendance_hours']; ?></td>
                                                    <td><?php echo $row['total_permission_hours'] . " hours " . $row['total_permission_minutes'] . " minutes"; ?></td>
                                                    <td><?php echo $row['total_overtime_hours']; ?> hours</td>
                                                    <td>
                                                        <!-- Add a Print button for each employee -->
                                                        <form method="POST" target="_blank" action="print_payslip.php">
                                                            <input type="hidden" name="employee_id" value="<?php echo $row['employee_id']; ?>">
                                                            <button type="submit" class="btn btn-primary"><i class="fas fa-print"></i> Print</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="plugins/datatables/jquery.dataTables.js"></script>
    <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="dist/js/demo.js"></script>
    <script src="plugins/select2/js/select2.full.min.js"></script>

    <script src="plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>

    <script src="plugins/moment/moment.min.js"></script>

    <script src="plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>

    <script src="plugins/daterangepicker/daterangepicker.js"></script>

    <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

    <script>
    $('.datepicker').datepicker({
        format: 'mm/dd/yyyy',
        startDate: '-3d'
    });
    </script>
    <script>
    $(function() {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
        });
    });
    </script>
</body>
</html>