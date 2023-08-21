<?php
	include 'settings.php'; // load server settings

	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// data_fetch.php - Fetch data from database
	if ($_SERVER["REQUEST_METHOD"] == "GET") {

		$sql = "SELECT * FROM `sensors`";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			$data = [];
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			echo json_encode($data);
		} else {
			echo "No sensor data available.";
		}

		$sql = "SELECT timestamp as label, value, s_id as sid, v_id as vid FROM data";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			$data = [];
			while ($row = $result->fetch_assoc()) {
				$data[] = $row;
			}
			echo json_encode($data);
		} else {
			echo "No data available.";
		}
	}
	$conn->close();
?>
