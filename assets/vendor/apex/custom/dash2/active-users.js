var options = {
	chart: {
		height: 320,
		type: "radialBar",
		fontFamily: 'IranYekanNum',
		toolbar: {
			show: false,
		},
	},
	plotOptions: {
		radialBar: {
			hollow: {
				size: "2%",
			},
			dataLabels: {
				name: {
					fontSize: "12px",
					fontColor: "black",
				},
				value: {
					fontSize: "21px",
				},
				total: {
					show: true,
					label: "مجموع",
					formatter: function (w) {
						// By default this function returns the average of all series. The below is just an example to show the use of custom formatter function
						return "250";
					},
				},
			},
			track: {
				background: "#353c48",
			},
		},
	},
	series: [80, 70, 20],
	labels: ["دسکتاپ", "تبلت", "موبایل"],
	colors: ["#3c92b1", "#a9bd7a", "#bf7a6a"],
};

var chart = new ApexCharts(document.querySelector("#device-sessions"), options);
chart.render();
