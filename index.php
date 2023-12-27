<?php 
include("admin/controller.php");
ini_set('display_errors', 0);
date_default_timezone_set('Asia/Kolkata');
$time = date("h:i:s");
$today = date("D - F d, Y");
$date = date("Y-m-d");
$in = date("H:i:s");
$out = "00:00:00";

if(isset($_POST['attendance'])) {
    session_start();
    $_SESSION['expire'] = date("H:i:s", time() + 1);
    $code = $_POST['operation'];

    if($code == "time-in") {
        $id = $_POST['emp_id'];
        $sql = "SELECT * FROM emp_list WHERE emp_card = '$id'";
        $result = mysqli_query($db, $sql);

        if(!$row = $result->fetch_assoc()) {
            $_SESSION['mess'] = "<div id='time' class='alert alert-danger' role='alert'>
                <i class='fas fa-times'></i> Employee ID is not registered !
            </div>";
            header("Location: index.php");
            exit();
        } else {
            $sql2 = "SELECT * FROM emp_attendance WHERE employee_id = '$id' AND attendance_date = '$date'";
            $result2 = mysqli_query($db, $sql2);

            if(!$row2 = $result2->fetch_assoc()) {
                $fname = $row['emp_fname'];
                $lname = $row['emp_lname'];
                $full = $lname . ', ' . $fname;
                $card = $row['emp_card'];

                $actualInTime = $in;

                $employeeInTime = new DateTime($in);
                $permissionStart = new DateTime("10:00:00");
                $permissionEnd = new DateTime("12:00:00");

                $permissionHours = 0;
                $permissionMinutes = 0;

                if ($employeeInTime >= $permissionStart && $employeeInTime <= $permissionEnd) {
                    // Calculate permission hours if the employee arrives within permission time range
                    $permissionInterval = $permissionStart->diff($employeeInTime);
                    $permissionHours = $permissionInterval->format('%h');
                    $permissionMinutes = $permissionInterval->format('%i');
                    $permissionMinutes = $permissionMinutes / 60; // Convert permission minutes to hours
                }

                // Calculate total worked hours considering the permission time
                $first = new DateTime($in);
                $second = new DateTime($out);
                $interval = $first->diff($second);
                $hrs = $interval->format('%h');
                $mins = $interval->format('%i');
                $mins = $mins / 60;
                $totalWorkedHours = $hrs + $mins;

                // Add permission time to the total worked hours
                $totalAttendanceHours = $totalWorkedHours + $permissionHours + $permissionMinutes;

                // Insert data into the database, considering the permission hours and minutes
                $sql3 = "INSERT INTO emp_attendance (employee_id, employee_name, attendance_date, attendance_timein, attendance_timeout, attendance_hour, permission_hours, permission_minutes)
                VALUES ('$id', '$full', '$date', '$in', '$out', '$totalWorkedHours', '$permissionHours', '$permissionMinutes')";
                            
                $result3 = mysqli_query($db, $sql3);
                $_SESSION['mess'] = "<div id='time' class='alert alert-success' role='alert'>
                    <i class='fas fa-check'></i> Time in: $full
                </div>";
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['mess'] = "<div id='time' class='alert alert-warning' role='alert'>
                    <i class='fas fa-exclamation'></i> You already have Timed In
                </div>";
                header("Location: index.php");
                exit();
            }
        }
    }

    if ($code == "time-out") {
        $id = $_POST['emp_id'];
    
        $sql = "SELECT * FROM emp_attendance WHERE employee_id = '$id' AND attendance_date = '$date'";
        $result = mysqli_query($db, $sql);
    
        if (!$row = $result->fetch_assoc()) {
            $_SESSION['mess'] = "<div id='time' class='alert alert-danger' role='alert'>
                <i class='fas fa-times'></i> You did not Timed in !
            </div>";
            header("Location: index.php");
            exit();
        } else {
            $query = "SELECT * FROM emp_attendance WHERE employee_id = '$id' AND attendance_date = '$date'";
            $queryres = mysqli_query($db, $query);
    
            while ($rowres = mysqli_fetch_array($queryres)) {
                $timein = $row['attendance_timein'];
            }
    
            // Get current time for time-out
            $out = date("H:i:s");
            $first = new DateTime($timein);
            $second = new DateTime($out);
    
            // Calculate the difference between time-out and time-in
            $interval = $first->diff($second);
            $hrs = $interval->format('%h');
            $mins = $interval->format('%i');
            $mins = $mins / 60;
            $totalWorkedHours = $hrs + $mins;
    
            // If the time-out is after 6 PM, calculate overtime
            $sixPM = new DateTime("18:00:00");
            if ($second >= $sixPM) {
                $overtimeInterval = $sixPM->diff($second);
                $overtimeHours = $overtimeInterval->format('%h');
                $overtimeMinutes = $overtimeInterval->format('%i');
                $overtimeMinutes = $overtimeMinutes / 60;
                $overtime = $overtimeHours + $overtimeMinutes;
    
                // Add overtime hours to total worked hours
                $totalWorkedHours += $overtime;
            }
    
            // Update the database with total worked hours including overtime
            $sql2 = "UPDATE emp_attendance SET attendance_timeout = '$out', attendance_hour = '$totalWorkedHours' WHERE employee_id = '$id' AND attendance_date = '$date'";
            $result2 = mysqli_query($db, $sql2);
    
            $_SESSION['mess'] = "<div id='time' class='alert alert-success' role='alert'>
                <i class='fas fa-check'></i> Timed Out
            </div>";
            header("Location: index.php");
            exit();
        }
    }
    $month = date("m");
    $year = date("Y");
    
    $getEmployeesQuery = "SELECT DISTINCT employee_id FROM emp_attendance";
    $employeesResult = mysqli_query($db, $getEmployeesQuery);
    
    while ($employee = mysqli_fetch_assoc($employeesResult)) {
        $employeeId = $employee['employee_id'];
    
        $startDate = date('Y-m-01', strtotime("$year-$month"));
        $endDate = date('Y-m-t', strtotime("$year-$month"));
    
        $getAttendanceQuery = "SELECT * FROM emp_attendance WHERE employee_id = '$employeeId' AND attendance_date BETWEEN '$startDate' AND '$endDate'";
        $attendanceResult = mysqli_query($db, $getAttendanceQuery);
    
        $totalAttendanceHours = 0;
        $totalPermissionHours = 0;
        $totalPermissionMinutes = 0;
    
        while ($attendance = mysqli_fetch_assoc($attendanceResult)) {
            $attendanceHours = $attendance['attendance_hour'];
            $permissionHours = $attendance['permission_hours'];
            $permissionMinutes = $attendance['permission_minutes'];
            $attendanceTimeIn = $attendance['attendance_timein'];
    
            // Extract entry time for permission calculation
            $entryHour = date('H', strtotime($attendanceTimeIn));
            $entryMinute = date('i', strtotime($attendanceTimeIn));
    
            // Calculate permission minutes within the specified range (10 am to 12 pm)
            if (($entryHour == 10 && $entryMinute >= 0) || ($entryHour == 11 && $entryMinute <= 59)) {
                $totalPermissionMinutes += min($permissionMinutes, 60 - $entryMinute);
            }
    
            $totalAttendanceHours += $attendanceHours;
            $totalPermissionHours += ($permissionHours + floor($totalPermissionMinutes / 60)); // Calculate whole hours
            $totalPermissionMinutes %= 60; // Remaining minutes
        }
    
        $insertMonthlyTotalsQuery = "INSERT INTO monthly_attendance (employee_id, month, year, total_attendance_hours, total_permission_hours, total_permission_minutes) VALUES ('$employeeId', '$month', '$year', '$totalAttendanceHours', '$totalPermissionHours', '$totalPermissionMinutes') ON DUPLICATE KEY UPDATE total_attendance_hours = '$totalAttendanceHours', total_permission_hours = '$totalPermissionHours', total_permission_minutes = '$totalPermissionMinutes'";
        mysqli_query($db, $insertMonthlyTotalsQuery);
    }
    
    // Redirect after processing attendance
    header("Location: index.php");
    exit();
    


}
?> 



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Attendance and Payroll System</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="admin/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="admin/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="admin/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <script src="admin/dist/js/1.js"></script>
    <script src="admin/dist/js/2.js"></script>
    <script src="admin/dist/js/3.js"></script>
    <style type="text/css">
    .mt20 {
        margin-top: 20px;
    }

    .result {
        font-size: 20px;
    }

    .bold {
        font-weight: bold;
    }
    </style>
</head>

<body class="hold-transition login-page">
    <img src="https://iocl.com/images/static/IndianOil_Logo_Fulla.jpg" alt="" style=" height: 200px;">
        <div class="login-logo">
            <p id="date"><?php echo $today; ?></p>
            <p id="time" class="bold"><?php echo $time; ?></p>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Enter Employee ID</p>

                <form method="POST">
                    <div class="input-group mb-3">
                        <select name="operation" class="form-control">
                            <option value="time-in">Time In</option>
                            <option value="time-out">Time Out</option>
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" name="emp_id" class="form-control" placeholder="Employee ID">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-id-card"></span>
                            </div>
                        </div>
                    </div>

                    <button typUe="submit" name="attendance" hidden></button>
                    
                </form>
                
            </div>

            <?php
    echo $_SESSION['mess'];
    echo $_SESSION['success'];

    $dd = date("H:i:s");

    if($dd == $_SESSION['expire'])
    {
      session_unset();
    }
    ?>

        </div>
    </div>
    <br><br>

    <script src="admin/plugins/jquery/jquery.min.js"></script>
    <script src="admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="admin/dist/js/adminlte.min.js"></script>
    <script src="admin/plugins/moment/moment.min.js"></script>
    <script src="admin/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script src="admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="admin/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="admin/plugins/toastr/toastr.min.js"></script>


    <script type="text/javascript">
    var interval = setInterval(function() {
        var momentNow = moment();
        $('#date').html(momentNow.format('dddd').substring(0, 3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));
        $('#time').html(momentNow.format('hh:mm:ss A'));
    }, 100);
    </script>


</body>

</html>