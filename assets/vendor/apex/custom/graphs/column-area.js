var options = {
	series: [
		{
			name: "درآمد ناخالص",
			type: "column",
			data: [25, 12, 20, 85, 12, 25, 19, 23, 18, 15, 22, 28],
		},
		{
			name: "درآمد",
			type: "area",
			data: [44, 55, 50, 40, 30, 10, 12, 22, 15, 19, 20, 17],
		},
	],
	chart: {
		height: 300,
		type: "line",
		fontFamily: 'IranYekanNum',
		toolbar: {
			show: false,
		},
	},
	stroke: {
		width: [0, 3],
		curve: "smooth",
	},
	plotOptions: {
		bar: {
			columnWidth: "70%",
			borderRadius: 8,
			distributed: true,
			dataLabels: {
				position: "top",
			},
		},
	},

	fill: {
		opacity: [0.85, 0.25, 1],
		gradient: {
			inverseColors: false,
			shade: "light",
			type: "vertical",
			opacityFrom: 0.85,
			opacityTo: 0.55,
			stops: [0, 100, 100, 100],
		},
	},

	markers: {
		size: 0,
	},
	xaxis: {
		categories: [
			"فروردین",
			"اردیبهشت",
			"خرداد",
			"تیر",
			"مرداد",
			"شهریور",
			"مهر",
			"آبان",
			"آذر",
			"دی",
			"بهمن",
			"اسفند",
		],
		axisBorder: {
			show: false,
		},
		tooltip: {
			enabled: true,
		},
		labels: {
			style: {
                fontSize: '12px',
            },
			show: true,
			rotate: -45,
			rotateAlways: true,
			offsetY: 20,  // اضافه کردن فاصله بین نام ماه‌ها و ستون‌ها
		},
	},
	yaxis: {
		show: false,
	},
	legend: {
		show: false,
	},
	grid: {
		borderColor: "#575e6d",
		strokeDashArray: 5,
		xaxis: {
			lines: {
				show: true,
			},
		},
		yaxis: {
			lines: {
				show: false,
			},
		},
		padding: {
			top: 0,
			right: 0,
			bottom: -20,
			left: 10,
		},
	},
	tooltip: {
		y: {
			formatter: function (val) {
				return val + " میلیون";
			},
		},
	},
	colors: ["#6fb4ce", "#bf7a6a"],
};

var chart = new ApexCharts(document.querySelector("#columnArea"), options);
chart.render();
