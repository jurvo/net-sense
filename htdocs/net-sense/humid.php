<?php
	include 'settings.php'; // load server settings
	// Get temperature from an arduino and write it into the database
	
	if (!isset($_GET['sensor_id']) or !isset($_GET['value']))
	{
		echo "0";
    	return;
	}

	$sid = $_GET['sensor_id'];
	$value = $_GET['value'];
	$timestamp = date('Y-m-d H:i:s', time());

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = 
	"INSERT INTO data (s_id, v_id, timestamp, value)
	VALUES ($sid, $humididity_key, '$timestamp', $value)";

	if ($conn->query($sql) === TRUE) {
		echo "1";
	} else {
		echo "0";
		echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$conn->close();
?>