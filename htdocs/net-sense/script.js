// script.js
// Load data and create the plot

async function fetchData() {
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState === XMLHttpRequest.DONE) {
			if (xhr.status === 200) {
				var data = JSON.parse(xhr.responseText);
				// Process the retrieved data
				console.log(data);
			} else {
				console.log("Error: " + xhr.status);
			}
		}
	};
	xhr.open("GET", "get_data.php", true);
	xhr.send();
}


async function plot() {
	const data = await fetchData();
	const x = [];
	const y = [];
	for (let i = 0; i < data.length; i++) {
		var e = x[i];
		if (e["s_id"] == 1 && e["v_id"] == 0)
		{
			x[i] = e["timestamp"];
			y[i] = e["value"];
		}
	}
	
    var ctx = document.getElementById("myChart").getContext("2d");
    var myChart = new Chart(ctx, {
        type: "line", // Change to the desired chart type (e.g., line, bar, pie)
        data: {
            labels: [1,2,3,4],
            datasets: [{
                label: "Temperatur",
                data: [1,2,3,4], // Example data values
				fill: false,
				borderColor: "green",
				tension: 0.2
            }]
        },
        options: {
            // Customize options as needed
        }
    });
}

document.addEventListener("DOMContentLoaded", plot());
