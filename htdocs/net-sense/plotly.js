var xhr = new XMLHttpRequest();

xhr.onreadystatechange = function() {
	if (xhr.readyState === XMLHttpRequest.DONE) {
		if (xhr.status === 200) {
			const text = xhr.responseText.split("][");
			var sensor_data = JSON.parse(text[0] + "]");
			var data = JSON.parse("[" + text[1]);
			createChart(0, sensor_data, data);
			createChart(1, sensor_data, data);
		} else {
			console.log("Error: " + xhr.status);
		}
	}
};
xhr.open("GET", "get_data.php", true);
xhr.send();

// Create a chart using Chart.js
function createChart(vid, sensor_data, data) {
	let chart = "chart_temp";
	if (vid == 1) chart = "chart_humid";
	var ctx = document.getElementById(chart);

	var labels = data.map(item => Date.parse(item.label));
	console.log(labels);
	var values = []; //data.map(item => item.value);
	var datasets = [];

	for (let i = 0; i < sensor_data.length; i++) 
	{
		var sub_data = data.filter((row) => row.sid == sensor_data[i].s_id && row.vid == vid);
		var s = {
			x: sub_data.map(row => row.label),
			y: sub_data.map(row => row.value),
			name: sensor_data[i].s_name + "@" + sensor_data[i].s_location,
			mode: 'lines+markers',
			line: {
				shape: 'spline',
				smothing: 0.2
			}
		}

		datasets.push(s);
	}
	var layout = {
		title: ((vid==0) ? 'Temperatur' : 'relative Luftfeuchtigkeit'),
		xaxis: {
			title: 'Zeit',
			range: [Date.now() - 3600000, Date.now()]
		  },
		yaxis: {
			title: ((vid==0) ? 'Temperatur in °C' : 'relative Luftfeuchtigkeit in %')
		}
	};
	var config = {
		modeBarButtonsToRemove: ['select2d', "lasso2d", ],
		displaylogo: false
	}
//	console.log(datasets);
	Plotly.newPlot(ctx, datasets, layout, config);
}