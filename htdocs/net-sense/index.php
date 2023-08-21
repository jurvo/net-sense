<!DOCTYPE html>
<html>
	<head>
		<style>
			/* Grid style */
			.grid {
				display: grid;
				grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); /* Adjust the number of columns as needed */
				gap: 20px;
				padding: 15px;
			}
			.grid-item {
				background: #EDE7F6;
				border: 0px solid #ccc;
				padding: 10px;
				border-radius: 1.5rem;
				font-size: 18px;
			}
			/* Table style */
			.tg  {
				border: none
				border-collapse: collapse;
				border-spacing: 0;
			}
			.tg td {
				border-style: solid;
				border-width: 0px;
				font-family: Arial, sans-serif;
				font-size: 18px;
				overflow: hidden;
				padding: 10px 5px;
				word-break: normal;
			}
			
			.tg .tg-0lax {
				text-align: left;
				vertical-align: top
			}

			@media screen and (max-width: 1000px) {
				.grid { width: auto !important; }
				.grid-item { font-size: 25px; }

				.tg td { font-size: 25px !important; }
				/* .tg { width: auto !important; }*/
				/*.tg col { width: auto !important; }*/
				
				/*.tg-wrap { 
					overflow-x: auto;
					-webkit-overflow-scrolling: touch;
				}*/
			}
		</style>
		<script src="https://cdn.plot.ly/plotly-2.25.2.min.js" charset="utf-8"></script>
	</head>
	<body>
		<div class="grid">
			<?php
				include 'settings.php'; // load server settings
				
				// Create connection
				$conn = new mysqli($servername, $username, $password, $dbname);
				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				$sql = "SELECT * FROM WebPageView";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
				//	echo $result->num_rows . " elements <br>";
				// output data of each row
					while($row = $result->fetch_assoc()) {
						$LU = DateTime::createFromFormat('Y-m-d H:i:s', $row["LastUpdate"])->format('H:i \h, d. M Y');
						echo '<div class="grid-item"> <b>' . $row["Name"] . '</b>@' . $row["Location"] . ' <i>('. $LU .  ')</i>'; // <br>Temperatur: ' . $row["Temperature"] . ' °C <br>rel. Luftfeuchtigkeit: ' . $row["Humidity"]. '%';
						echo '<div class="tg-wrap">
						<table class="tg">
						<tbody>
						<tr>
							<td class="tg-0lax">Temperatur</td>
							<td class="tg-0lax">' . $row["Temperature"] .' °C </td>
						</tr>
						<tr>
							<td class="tg-0lax">rel. Luftfeuchtigkeit</td>
							<td class="tg-0lax">' . $row["Humidity"] .' %</td>
						</tr>
						</tbody>
						</table></div>';
						echo '</div>';
					}
				} else {
					echo "No data available.";
				}

				$conn->close();
			?>
		</div>

	<div id="chart_temp" style="width:1000px;height:400px;"></div>
	<div id="chart_humid" style="width:1000px;height:400px;"></div>
	<script src="plotly.js"></script>

	</body>
</html>