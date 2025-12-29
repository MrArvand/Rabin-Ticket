var options = {
	chart: {
		width: 300,
		type: "pie",
		fontFamily: 'IranYekanNum',
	},
	labels: ["تیم الف", "تیم ب", "تیم ج", "تیم چ", "تیم ه"],
	series: [20, 20, 20, 20, 20],
	legend: {
		position: "bottom",
		markers: {
            width: 12,
            height: 12,
            radius: 0,
            offsetX: 5,
            offsetY: 0,
			radius:6
        },
		itemMargin: {
            horizontal: 10,
            vertical: 5
        }
	},
	dataLabels: {
		enabled: false,
		offsetX: 0,
        offsetY: -10,
        style: {
            fontSize: '12px',
            colors: ["#000"]
        }
	},
	stroke: {
		width: 0,
	},
	colors: ["#30758e", "#3c92b1", "#63a8c1", "#8abed0", "#b1d3e0", "#c5dee8"],
};
var chart = new ApexCharts(document.querySelector("#pie"), options);
chart.render();
