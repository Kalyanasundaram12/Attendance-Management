<?php
include("controller.php");
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
    <style>
    @page {
        size: auto;
        margin: 0mm;
    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td,
    th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
    </style>
</head>

<body>

<div class="row">
        <div class="col-12">
            <table>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Total Overtime Hours</th> <!-- Added Total Overtime Hours column -->
                    <!-- Add more table headers/columns if needed -->
                </tr>
                <?php
                // Fetch attendance details within the specified date range
                $s = $_SESSION['start_month'];
                $e = $_SESSION['end_month'];
                $sql = "SELECT * FROM emp_attendance WHERE attendance_date BETWEEN '$s' AND '$e' ORDER BY employee_id ASC";
                $result = mysqli_query($db, $sql);

                while ($row = mysqli_fetch_array($result)) {
                    // Calculate total overtime hours for each row
                    $timeOut = new DateTime($row['attendance_timeout']);
                    $overtime = new DateTime('18:00:00'); // Set the overtime threshold time

                    // Calculate overtime hours if the time out is after the defined threshold time
                    $totalOvertime = ($timeOut > $overtime) ? $timeOut->diff($overtime)->format('%H:%I:%S') : '00:00:00';
                ?>
                    <tr>
                        <td><?php echo $row['attendance_date']; ?></td>
                        <td><?php echo $row['employee_name']; ?></td>
                        <td><?php echo $row['attendance_timein']; ?></td>
                        <td><?php echo $row['attendance_timeout']; ?></td>
                        <td><?php echo $totalOvertime; ?></td> <!-- Display calculated total overtime hours -->
                        <!-- Add more table data cells if needed -->
                    </tr>
                <?php
                }
                ?>
            </table>
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

</body>
</html>