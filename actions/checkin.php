<?php
if(!isset($_POST['id'])) {
	redirect('./','');
}

// Connect to DB
$conn = connect();

// Student ID
$id = addslashes($_POST['id']);
$course_id = addslashes($_POST['course_id']);

// Make sure student isn't already checked in
$sql = "SELECT session_id FROM sessions 
		WHERE CURDATE()=DATE(session_timein)
			AND session_timeout IS NULL
			AND student_id=$id";
$results = $conn->query($sql);
$students = get_results($results);
if(count($students) > 0) {
	redirect('./','This student is already checked in.','');
	die();
}

// Insert student
$sql = "INSERT INTO sessions (student_id,course_id) VALUES($id,$course_id)";
$conn->query($sql);

// If an error occurred
if($conn->error != '') {
	$message = 'An error occurred and the student was not checked in. Please try again.';
	$message .= "<br/>SQL: <code>$sql</code>";
	$message .= "<br/>Error: <code>{$conn->error}</code>";
	$type = 'danger';
} else {
	$message = null;
	$type = '';
}

// Close DB connection
$conn->close();

// Redirect
redirect('./',$message,$type);