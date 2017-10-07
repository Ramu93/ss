<?php

	$db_servername = 'localhost';
	$db_username = 'root';
	$db_password = 'root';
	$db_name = 'sbbs';

	// Connect to MySQL:
	$dbc = mysqli_connect($db_servername,$db_username,$db_password,$db_name);

	// Confirm the connection and select the database:
	if (mysqli_connect_errno()) {
	    echo "Could not establish database connection!<br>";
	    exit();
	}

	$currDate = date("Y-m-d H:i:s");

	$query = "SELECT * FROM hr_employee_master";
	$result = mysqli_query($dbc, $query);
	if(mysqli_num_rows($result) > 0){
		while($row = mysqli_fetch_assoc($result)){
			$attendanceQuery = "INSERT INTO hr_attendance (emp_id, attendance_date, department, office, location, present_absent, entry_time, exit_time) VALUES ('".$row['emp_id']."', '$currDate', '".$row['department']."', '".$row['office']."', '".$row['location']."', 'present', '09:30 AM', '06:00 PM')";
			mysqli_query($dbc, $attendanceQuery);
		}
	}

?>